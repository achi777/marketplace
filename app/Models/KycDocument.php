<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class KycDocument extends Model
{
    protected $fillable = [
        'user_id',
        'document_type',
        'document_number',
        'document_path',
        'status',
        'rejection_reason',
        'reviewed_at',
        'reviewed_by',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function getDocumentUrlAttribute(): string
    {
        return Storage::url($this->document_path);
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match($this->status) {
            'approved' => 'bg-success',
            'rejected' => 'bg-danger',
            'pending' => 'bg-warning',
            default => 'bg-secondary'
        };
    }

    public function getDocumentTypeDisplayAttribute(): string
    {
        return match($this->document_type) {
            'identity' => 'Identity Document',
            'address_proof' => 'Address Proof',
            'business_license' => 'Business License',
            default => ucfirst($this->document_type)
        };
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function approve($reviewerId = null): bool
    {
        return $this->update([
            'status' => 'approved',
            'reviewed_at' => now(),
            'reviewed_by' => $reviewerId ?? auth()->id(),
            'rejection_reason' => null,
        ]);
    }

    public function reject(string $reason, $reviewerId = null): bool
    {
        return $this->update([
            'status' => 'rejected',
            'reviewed_at' => now(),
            'reviewed_by' => $reviewerId ?? auth()->id(),
            'rejection_reason' => $reason,
        ]);
    }

    public function canBeReviewed(): bool
    {
        return $this->status === 'pending';
    }

    public static function getRequiredDocumentTypes(): array
    {
        return [
            'identity' => 'Identity Document (Passport, Driver\'s License, National ID)',
            'address_proof' => 'Address Proof (Utility Bill, Bank Statement)',
            'business_license' => 'Business License (Required for sellers)',
        ];
    }

    public static function getUserKycStatus($userId): array
    {
        $documents = static::where('user_id', $userId)->get();
        $requiredDocs = ['identity', 'address_proof', 'business_license'];
        
        $status = [
            'overall_status' => 'incomplete',
            'documents' => [],
            'completion_percentage' => 0,
        ];

        foreach ($requiredDocs as $docType) {
            $doc = $documents->where('document_type', $docType)->first();
            $status['documents'][$docType] = [
                'uploaded' => $doc !== null,
                'status' => $doc?->status ?? 'not_uploaded',
                'document' => $doc,
            ];
        }

        // Calculate completion percentage
        $uploadedCount = collect($status['documents'])->where('uploaded', true)->count();
        $status['completion_percentage'] = ($uploadedCount / count($requiredDocs)) * 100;

        // Determine overall status
        $approvedCount = collect($status['documents'])->where('status', 'approved')->count();
        $rejectedCount = collect($status['documents'])->where('status', 'rejected')->count();
        $pendingCount = collect($status['documents'])->where('status', 'pending')->count();

        if ($approvedCount === count($requiredDocs)) {
            $status['overall_status'] = 'verified';
        } elseif ($rejectedCount > 0) {
            $status['overall_status'] = 'rejected';
        } elseif ($pendingCount > 0) {
            $status['overall_status'] = 'pending';
        } else {
            $status['overall_status'] = 'incomplete';
        }

        return $status;
    }
}