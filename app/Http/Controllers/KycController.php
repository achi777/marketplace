<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KycDocument;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class KycController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $kycStatus = $user->kyc_status;
        $documents = $user->kycDocuments()->latest()->get();
        $requiredDocuments = KycDocument::getRequiredDocumentTypes();

        return view('kyc.index', compact('kycStatus', 'documents', 'requiredDocuments'));
    }

    public function upload(Request $request)
    {
        $request->validate([
            'document_type' => 'required|in:identity,address_proof,business_license',
            'document_number' => 'nullable|string|max:255',
            'document_file' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120', // 5MB max
        ]);

        $user = auth()->user();
        $documentType = $request->document_type;

        // Check if user already has this document type uploaded and pending/approved
        $existingDoc = $user->kycDocuments()
            ->where('document_type', $documentType)
            ->whereIn('status', ['pending', 'approved'])
            ->first();

        if ($existingDoc) {
            return response()->json([
                'success' => false,
                'message' => 'You already have a ' . $existingDoc->document_type_display . ' that is ' . $existingDoc->status
            ], 400);
        }

        try {
            // Store the file
            $file = $request->file('document_file');
            $filename = $user->id . '_' . $documentType . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('kyc-documents', $filename, 'private');

            // Delete any existing rejected document of the same type
            $rejectedDoc = $user->kycDocuments()
                ->where('document_type', $documentType)
                ->where('status', 'rejected')
                ->first();

            if ($rejectedDoc) {
                Storage::disk('private')->delete($rejectedDoc->document_path);
                $rejectedDoc->delete();
            }

            // Create new document record
            $document = KycDocument::create([
                'user_id' => $user->id,
                'document_type' => $documentType,
                'document_number' => $request->document_number,
                'document_path' => $path,
                'status' => 'pending',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Document uploaded successfully and is now pending review',
                'document' => $document->load('user'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload document: ' . $e->getMessage()
            ], 500);
        }
    }

    public function download(KycDocument $document)
    {
        $user = auth()->user();

        // Check if user can access this document
        if ($document->user_id !== $user->id && $user->role !== 'admin') {
            abort(403, 'Unauthorized access to document');
        }

        if (!Storage::disk('private')->exists($document->document_path)) {
            abort(404, 'Document file not found');
        }

        return Storage::disk('private')->download(
            $document->document_path,
            $document->document_type . '_' . $document->user->name . '.' . pathinfo($document->document_path, PATHINFO_EXTENSION)
        );
    }

    public function delete(KycDocument $document)
    {
        $user = auth()->user();

        // Only allow deletion of own documents and only if rejected or pending
        if ($document->user_id !== $user->id || !in_array($document->status, ['pending', 'rejected'])) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete this document'
            ], 403);
        }

        try {
            // Delete file from storage
            if (Storage::disk('private')->exists($document->document_path)) {
                Storage::disk('private')->delete($document->document_path);
            }

            $document->delete();

            return response()->json([
                'success' => true,
                'message' => 'Document deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete document: ' . $e->getMessage()
            ], 500);
        }
    }

    // Admin methods
    public function adminIndex()
    {
        $this->authorize('admin');

        $pendingDocuments = KycDocument::with(['user'])
            ->pending()
            ->latest()
            ->paginate(20);

        $stats = [
            'pending' => KycDocument::pending()->count(),
            'approved_today' => KycDocument::approved()->whereDate('reviewed_at', today())->count(),
            'rejected_today' => KycDocument::rejected()->whereDate('reviewed_at', today())->count(),
        ];

        return view('admin.kyc.index', compact('pendingDocuments', 'stats'));
    }

    public function adminShow(KycDocument $document)
    {
        $this->authorize('admin');

        $document->load(['user', 'reviewer']);
        $userKycStatus = $document->user->kyc_status;

        return view('admin.kyc.show', compact('document', 'userKycStatus'));
    }

    public function adminApprove(Request $request, KycDocument $document)
    {
        $this->authorize('admin');

        if (!$document->canBeReviewed()) {
            return response()->json([
                'success' => false,
                'message' => 'Document cannot be reviewed'
            ], 400);
        }

        try {
            $document->approve(auth()->id());

            return response()->json([
                'success' => true,
                'message' => 'Document approved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to approve document: ' . $e->getMessage()
            ], 500);
        }
    }

    public function adminReject(Request $request, KycDocument $document)
    {
        $this->authorize('admin');

        $request->validate([
            'rejection_reason' => 'required|string|max:1000',
        ]);

        if (!$document->canBeReviewed()) {
            return response()->json([
                'success' => false,
                'message' => 'Document cannot be reviewed'
            ], 400);
        }

        try {
            $document->reject($request->rejection_reason, auth()->id());

            return response()->json([
                'success' => true,
                'message' => 'Document rejected successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to reject document: ' . $e->getMessage()
            ], 500);
        }
    }

    protected function authorize(string $role)
    {
        if (auth()->user()->role !== $role) {
            abort(403, 'Access denied');
        }
    }
}