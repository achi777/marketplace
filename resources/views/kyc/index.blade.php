<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="h4 mb-1 text-dark fw-bold">KYC Verification</h2>
                <p class="text-muted small mb-0">Verify your identity to unlock all marketplace features</p>
            </div>
            <div class="d-flex align-items-center gap-2">
                <span class="badge rounded-pill px-3 py-2 text-uppercase fw-bold
                    @if($kycStatus['overall_status'] === 'verified') bg-success
                    @elseif($kycStatus['overall_status'] === 'pending') bg-warning text-dark
                    @elseif($kycStatus['overall_status'] === 'rejected') bg-danger
                    @else bg-secondary @endif">
                    {{ ucfirst($kycStatus['overall_status']) }}
                </span>
            </div>
        </div>
    </x-slot>

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #f8f9fa;
        }
        
        .kyc-card {
            background: white;
            border-radius: 16px;
            border: none;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s ease;
        }
        
        .kyc-card:hover {
            transform: translateY(-2px);
        }
        
        .progress-modern {
            height: 12px;
            border-radius: 10px;
            background: #e9ecef;
            overflow: hidden;
        }
        
        .progress-bar-modern {
            height: 100%;
            border-radius: 10px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            transition: width 0.6s ease;
        }
        
        .document-card {
            background: white;
            border-radius: 12px;
            border: 2px solid #e9ecef;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .document-card.uploaded {
            border-color: #28a745;
        }
        
        .document-card.pending {
            border-color: #ffc107;
        }
        
        .document-card.rejected {
            border-color: #dc3545;
        }
        
        .document-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }
        
        .upload-area {
            border: 2px dashed #dee2e6;
            border-radius: 12px;
            padding: 2rem;
            text-align: center;
            transition: all 0.3s ease;
            background: #f8f9fa;
            cursor: pointer;
        }
        
        .upload-area:hover {
            border-color: #667eea;
            background: rgba(102, 126, 234, 0.05);
        }
        
        .btn-primary-custom {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 25px;
            padding: 8px 20px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }
        
        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
        }
        
        .status-indicator {
            position: absolute;
            top: -5px;
            right: -5px;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 12px;
            font-weight: bold;
        }
        
        @media (max-width: 768px) {
            .kyc-card {
                margin-bottom: 1rem;
            }
        }
    </style>

    <div class="container py-4">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <!-- KYC Status Overview -->
                <div class="kyc-card mb-4">
                    <div class="card-header bg-transparent border-0 p-4">
                        <h5 class="mb-0 fw-bold d-flex align-items-center">
                            <i class="bi bi-shield-check me-2 text-primary"></i>
                            Verification Progress
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="progress-modern mb-4">
                            <div class="progress-bar-modern" style="width: {{ $kycStatus['completion_percentage'] }}%"></div>
                        </div>
                        
                        <div class="row g-3 text-center mb-4">
                            <div class="col-md-4">
                                <div class="bg-light rounded-3 p-3">
                                    <div class="h4 fw-bold text-primary mb-1">{{ number_format($kycStatus['completion_percentage']) }}%</div>
                                    <small class="text-muted fw-semibold">Completion</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="bg-light rounded-3 p-3">
                                    <div class="h4 fw-bold text-success mb-1">{{ collect($kycStatus['documents'])->where('uploaded', true)->count() }}</div>
                                    <small class="text-muted fw-semibold">Documents Uploaded</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="bg-light rounded-3 p-3">
                                    <div class="h4 fw-bold text-info mb-1">{{ collect($kycStatus['documents'])->where('status', 'approved')->count() }}</div>
                                    <small class="text-muted fw-semibold">Documents Approved</small>
                                </div>
                            </div>
                        </div>

                        @if($kycStatus['overall_status'] === 'verified')
                            <div class="alert alert-success border-0 rounded-3 shadow-sm">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-check-circle-fill me-3" style="font-size: 1.5rem;"></i>
                                    <div>
                                        <h6 class="alert-heading mb-1">Congratulations!</h6>
                                        <p class="mb-0">Your identity has been verified. You can now access all marketplace features.</p>
                                    </div>
                                </div>
                            </div>
                        @elseif($kycStatus['overall_status'] === 'pending')
                            <div class="alert alert-info border-0 rounded-3 shadow-sm">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-clock me-3" style="font-size: 1.5rem;"></i>
                                    <div>
                                        <h6 class="alert-heading mb-1">Under Review</h6>
                                        <p class="mb-0">Your documents are being reviewed. This typically takes 24-48 hours.</p>
                                    </div>
                                </div>
                            </div>
                        @elseif($kycStatus['overall_status'] === 'rejected')
                            <div class="alert alert-danger border-0 rounded-3 shadow-sm">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-exclamation-triangle-fill me-3" style="font-size: 1.5rem;"></i>
                                    <div>
                                        <h6 class="alert-heading mb-1">Action Required</h6>
                                        <p class="mb-0">Some documents were rejected. Please upload new documents to continue.</p>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="alert alert-warning border-0 rounded-3 shadow-sm">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-upload me-3" style="font-size: 1.5rem;"></i>
                                    <div>
                                        <h6 class="alert-heading mb-1">Upload Required</h6>
                                        <p class="mb-0">Please upload the required documents to verify your identity.</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Document Upload Forms -->
                @foreach($requiredDocuments as $docType => $description)
                    @php
                        $docStatus = $kycStatus['documents'][$docType] ?? ['uploaded' => false, 'status' => 'not_uploaded', 'document' => null];
                        $document = $docStatus['document'];
                    @endphp
                    
                    <div class="document-card mb-4 {{ $document ? $document->status : '' }}">
                        @if($document)
                            <div class="status-indicator {{ $document->status_badge_class }}">
                                @if($document->status === 'approved')
                                    <i class="bi bi-check"></i>
                                @elseif($document->status === 'pending')
                                    <i class="bi bi-clock"></i>
                                @else
                                    <i class="bi bi-x"></i>
                                @endif
                            </div>
                        @endif
                        <div class="card-header bg-transparent border-0 p-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="mb-0 fw-bold d-flex align-items-center">
                                    <i class="bi bi-file-earmark-text me-2 text-primary"></i>
                                    {{ $requiredDocuments[$docType] }}
                                </h6>
                                @if($document)
                                    <span class="badge rounded-pill {{ $document->status_badge_class }} px-3 py-2">
                                        {{ ucfirst($document->status) }}
                                    </span>
                                @else
                                    <span class="badge bg-light text-dark rounded-pill px-3 py-2">Not Uploaded</span>
                                @endif
                            </div>
                        </div>
                        <div class="card-body p-4">
                            @if($document && in_array($document->status, ['pending', 'approved']))
                                <!-- Document already uploaded -->
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>{{ $document->document_type_display }}</strong>
                                        @if($document->document_number)
                                            <div class="small text-muted">Document #: {{ $document->document_number }}</div>
                                        @endif
                                        <div class="small text-muted">
                                            Uploaded: {{ $document->created_at->format('M d, Y H:i') }}
                                        </div>
                                        @if($document->reviewed_at)
                                            <div class="small text-muted">
                                                Reviewed: {{ $document->reviewed_at->format('M d, Y H:i') }}
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <a href="{{ route('kyc.download', $document) }}" class="btn btn-outline-primary btn-sm">
                                            <i class="bi bi-download"></i> Download
                                        </a>
                                        @if($document->status === 'pending')
                                            <button class="btn btn-outline-danger btn-sm ms-2" onclick="deleteDocument({{ $document->id }})">
                                                <i class="bi bi-trash"></i> Delete
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            @elseif($document && $document->status === 'rejected')
                                <!-- Document rejected, show rejection reason and allow re-upload -->
                                <div class="alert alert-danger">
                                    <strong>Document Rejected:</strong> {{ $document->rejection_reason }}
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div>
                                        <strong>Previous Upload:</strong> {{ $document->document_type_display }}
                                        <div class="small text-muted">Rejected: {{ $document->reviewed_at->format('M d, Y H:i') }}</div>
                                    </div>
                                    <button class="btn btn-outline-danger btn-sm" onclick="deleteDocument({{ $document->id }})">
                                        <i class="bi bi-trash"></i> Remove
                                    </button>
                                </div>
                                <!-- Show upload form -->
                                @include('kyc.partials.upload-form', ['docType' => $docType, 'description' => $description])
                            @else
                                <!-- No document uploaded, show upload form -->
                                @include('kyc.partials.upload-form', ['docType' => $docType, 'description' => $description])
                            @endif
                        </div>
                    </div>
                @endforeach

                <!-- Help Information -->
                <div class="kyc-card">
                    <div class="card-header bg-transparent border-0 p-4">
                        <h5 class="mb-0 fw-bold d-flex align-items-center">
                            <i class="bi bi-question-circle me-2 text-primary"></i>
                            Need Help?
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="bg-light rounded-3 p-4 h-100">
                                    <h6 class="fw-bold mb-3 text-primary">
                                        <i class="bi bi-file-check me-2"></i>Acceptable Documents
                                    </h6>
                                    <ul class="list-unstyled">
                                        <li class="mb-2">
                                            <i class="bi bi-dot text-primary"></i>
                                            <strong>Identity:</strong> Passport, Driver's License, National ID
                                        </li>
                                        <li class="mb-2">
                                            <i class="bi bi-dot text-primary"></i>
                                            <strong>Address Proof:</strong> Utility Bill, Bank Statement (within 3 months)
                                        </li>
                                        <li class="mb-0">
                                            <i class="bi bi-dot text-primary"></i>
                                            <strong>Business License:</strong> Valid business registration certificate
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="bg-light rounded-3 p-4 h-100">
                                    <h6 class="fw-bold mb-3 text-success">
                                        <i class="bi bi-gear me-2"></i>File Requirements
                                    </h6>
                                    <ul class="list-unstyled">
                                        <li class="mb-2">
                                            <i class="bi bi-dot text-success"></i>
                                            Formats: JPG, PNG, PDF
                                        </li>
                                        <li class="mb-2">
                                            <i class="bi bi-dot text-success"></i>
                                            Maximum size: 5MB per file
                                        </li>
                                        <li class="mb-2">
                                            <i class="bi bi-dot text-success"></i>
                                            Documents must be clear and readable
                                        </li>
                                        <li class="mb-0">
                                            <i class="bi bi-dot text-success"></i>
                                            All information must be visible
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function deleteDocument(documentId) {
            if (!confirm('Are you sure you want to delete this document?')) {
                return;
            }

            fetch(`/kyc/documents/${documentId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification(data.message, 'success');
                    location.reload();
                } else {
                    showNotification(data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Failed to delete document', 'error');
            });
        }
    </script>
</x-app-layout>