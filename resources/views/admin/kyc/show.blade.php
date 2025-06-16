<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 mb-0 text-dark">
                {{ __('Review KYC Document') }}
            </h2>
            <a href="{{ route('admin.kyc.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Back to List
            </a>
        </div>
    </x-slot>

    <div class="container py-4">
        <div class="row">
            <div class="col-lg-8">
                <!-- Document Details -->
                <div class="card mb-4">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="bi bi-file-earmark-text"></i>
                                {{ $document->document_type_display }}
                            </h5>
                            <span class="badge {{ $document->status_badge_class }} fs-6">
                                {{ ucfirst($document->status) }}
                            </span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <strong>Document Number:</strong><br>
                                {{ $document->document_number ?? 'Not provided' }}
                            </div>
                            <div class="col-md-6">
                                <strong>Uploaded:</strong><br>
                                {{ $document->created_at->format('M d, Y H:i') }}
                            </div>
                        </div>

                        @if($document->reviewed_at)
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <strong>Reviewed:</strong><br>
                                    {{ $document->reviewed_at->format('M d, Y H:i') }}
                                </div>
                                <div class="col-md-6">
                                    <strong>Reviewed By:</strong><br>
                                    {{ $document->reviewer?->name ?? 'System' }}
                                </div>
                            </div>
                        @endif

                        @if($document->rejection_reason)
                            <div class="alert alert-danger">
                                <strong>Rejection Reason:</strong><br>
                                {{ $document->rejection_reason }}
                            </div>
                        @endif

                        <!-- Document Preview -->
                        <div class="document-preview text-center">
                            @php
                                $extension = pathinfo($document->document_path, PATHINFO_EXTENSION);
                            @endphp
                            
                            @if(in_array(strtolower($extension), ['jpg', 'jpeg', 'png']))
                                <img src="{{ route('kyc.download', $document) }}" 
                                     class="img-fluid border rounded" 
                                     style="max-height: 500px;"
                                     alt="Document preview">
                            @elseif(strtolower($extension) === 'pdf')
                                <div class="d-flex flex-column align-items-center py-4">
                                    <i class="bi bi-file-earmark-pdf display-1 text-danger"></i>
                                    <h5 class="mt-2">PDF Document</h5>
                                    <p class="text-muted">Click download to view the PDF file</p>
                                </div>
                            @else
                                <div class="d-flex flex-column align-items-center py-4">
                                    <i class="bi bi-file-earmark display-1 text-muted"></i>
                                    <h5 class="mt-2">Document File</h5>
                                    <p class="text-muted">Click download to view the file</p>
                                </div>
                            @endif
                        </div>

                        <div class="text-center mt-3">
                            <a href="{{ route('kyc.download', $document) }}" 
                               class="btn btn-outline-primary" target="_blank">
                                <i class="bi bi-download"></i> Download Original
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Review Actions -->
                @if($document->canBeReviewed())
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="bi bi-clipboard-check"></i>
                                Review Actions
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <button class="btn btn-success w-100" onclick="approveDocument()">
                                        <i class="bi bi-check-circle"></i> Approve Document
                                    </button>
                                </div>
                                <div class="col-md-6">
                                    <button class="btn btn-danger w-100" onclick="showRejectModal()">
                                        <i class="bi bi-x-circle"></i> Reject Document
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <div class="col-lg-4">
                <!-- User Information -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0">
                            <i class="bi bi-person"></i>
                            User Information
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-3">
                            <div class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center" 
                                 style="width: 60px; height: 60px;">
                                <i class="bi bi-person-fill text-white h4 mb-0"></i>
                            </div>
                        </div>
                        
                        <div class="text-center">
                            <h6>{{ $document->user->name }}</h6>
                            <p class="text-muted small">{{ $document->user->email }}</p>
                            <span class="badge bg-{{ $document->user->role === 'seller' ? 'info' : 'secondary' }}">
                                {{ ucfirst($document->user->role) }}
                            </span>
                        </div>

                        <hr>

                        <div class="small">
                            <div class="row mb-2">
                                <div class="col-6"><strong>Phone:</strong></div>
                                <div class="col-6">{{ $document->user->phone ?? 'Not provided' }}</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-6"><strong>Address:</strong></div>
                                <div class="col-6">{{ $document->user->address ?? 'Not provided' }}</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-6"><strong>Joined:</strong></div>
                                <div class="col-6">{{ $document->user->created_at->format('M d, Y') }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- KYC Status Overview -->
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">
                            <i class="bi bi-shield-check"></i>
                            KYC Status Overview
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-3">
                            <span class="badge fs-6 
                                @if($userKycStatus['overall_status'] === 'verified') bg-success
                                @elseif($userKycStatus['overall_status'] === 'pending') bg-warning
                                @elseif($userKycStatus['overall_status'] === 'rejected') bg-danger
                                @else bg-secondary @endif">
                                {{ ucfirst($userKycStatus['overall_status']) }}
                            </span>
                        </div>

                        <div class="progress mb-3" style="height: 8px;">
                            <div class="progress-bar bg-info" 
                                 style="width: {{ $userKycStatus['completion_percentage'] }}%"></div>
                        </div>

                        <div class="small">
                            @foreach($userKycStatus['documents'] as $type => $status)
                                <div class="d-flex justify-content-between mb-2">
                                    <span>{{ ucfirst(str_replace('_', ' ', $type)) }}:</span>
                                    <span class="badge badge-sm
                                        @if($status['status'] === 'approved') bg-success
                                        @elseif($status['status'] === 'pending') bg-warning
                                        @elseif($status['status'] === 'rejected') bg-danger
                                        @else bg-light text-dark @endif">
                                        {{ $status['uploaded'] ? ucfirst($status['status']) : 'Not uploaded' }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Rejection Modal -->
    <div class="modal fade" id="rejectModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Reject Document</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="rejectForm">
                        <div class="mb-3">
                            <label for="rejection_reason" class="form-label">Rejection Reason</label>
                            <textarea class="form-control" 
                                      id="rejection_reason" 
                                      name="rejection_reason" 
                                      rows="4" 
                                      placeholder="Please provide a clear reason for rejection..."
                                      required></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" onclick="rejectDocument()">
                        <i class="bi bi-x-circle"></i> Reject Document
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function approveDocument() {
            if (!confirm('Are you sure you want to approve this document?')) {
                return;
            }

            fetch(`/admin/kyc/documents/{{ $document->id }}/approve`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification(data.message, 'success');
                    setTimeout(() => {
                        window.location.href = '{{ route('admin.kyc.index') }}';
                    }, 1000);
                } else {
                    showNotification(data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Failed to approve document', 'error');
            });
        }

        function showRejectModal() {
            new bootstrap.Modal(document.getElementById('rejectModal')).show();
        }

        function rejectDocument() {
            const form = document.getElementById('rejectForm');
            const formData = new FormData(form);

            if (!formData.get('rejection_reason').trim()) {
                showNotification('Please provide a rejection reason', 'error');
                return;
            }

            fetch(`/admin/kyc/documents/{{ $document->id }}/reject`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    rejection_reason: formData.get('rejection_reason')
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification(data.message, 'success');
                    setTimeout(() => {
                        window.location.href = '{{ route('admin.kyc.index') }}';
                    }, 1000);
                } else {
                    showNotification(data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Failed to reject document', 'error');
            });
        }
    </script>
</x-app-layout>