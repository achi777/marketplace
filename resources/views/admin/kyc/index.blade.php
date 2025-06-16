<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 mb-0 text-dark">
            {{ __('KYC Document Review') }}
        </h2>
    </x-slot>

    <div class="container py-4">
        <!-- Stats Cards -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title">Pending Review</h6>
                                <h3 class="mb-0">{{ $stats['pending'] }}</h3>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-clock-history display-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title">Approved Today</h6>
                                <h3 class="mb-0">{{ $stats['approved_today'] }}</h3>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-check-circle display-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-danger text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title">Rejected Today</h6>
                                <h3 class="mb-0">{{ $stats['rejected_today'] }}</h3>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-x-circle display-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Documents -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-file-earmark-check"></i>
                    Pending Document Reviews
                </h5>
            </div>
            <div class="card-body p-0">
                @if($pendingDocuments->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>User</th>
                                    <th>Document Type</th>
                                    <th>Document Number</th>
                                    <th>Uploaded</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pendingDocuments as $document)
                                    <tr>
                                        <td>
                                            <div>
                                                <strong>{{ $document->user->name }}</strong>
                                                <div class="small text-muted">{{ $document->user->email }}</div>
                                                <span class="badge bg-{{ $document->user->role === 'seller' ? 'info' : 'secondary' }} badge-sm">
                                                    {{ ucfirst($document->user->role) }}
                                                </span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="fw-medium">{{ $document->document_type_display }}</span>
                                        </td>
                                        <td>
                                            {{ $document->document_number ?? '-' }}
                                        </td>
                                        <td>
                                            <div>{{ $document->created_at->format('M d, Y') }}</div>
                                            <small class="text-muted">{{ $document->created_at->format('H:i') }}</small>
                                        </td>
                                        <td>
                                            <span class="badge {{ $document->status_badge_class }} fs-6">
                                                {{ ucfirst($document->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('admin.kyc.show', $document) }}" 
                                                   class="btn btn-outline-primary">
                                                    <i class="bi bi-eye"></i> Review
                                                </a>
                                                <a href="{{ route('kyc.download', $document) }}" 
                                                   class="btn btn-outline-secondary">
                                                    <i class="bi bi-download"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    @if($pendingDocuments->hasPages())
                        <div class="card-footer">
                            {{ $pendingDocuments->links() }}
                        </div>
                    @endif
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-check-circle display-1 text-success"></i>
                        <h4 class="mt-3">All Caught Up!</h4>
                        <p class="text-muted">No pending KYC documents to review.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>