<div class="text-center">
    <div class="alert alert-danger border-0">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>
        <strong>{{ __('Warning!') }}</strong> {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
    </div>

    <button type="button" class="btn btn-danger-gradient" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
        <i class="bi bi-trash me-2"></i>{{ __('Delete Account') }}
    </button>
</div>

<!-- Delete Account Modal -->
<div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0" style="border-radius: 20px;">
            <div class="modal-header border-0 bg-danger text-white" style="border-radius: 20px 20px 0 0;">
                <h5 class="modal-title fw-bold" id="deleteAccountModalLabel">
                    <i class="bi bi-exclamation-triangle me-2"></i>{{ __('Delete Account') }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post" action="{{ route('profile.destroy') }}">
                @csrf
                @method('delete')
                
                <div class="modal-body p-4">
                    <div class="text-center mb-4">
                        <div class="bg-danger bg-opacity-10 rounded-circle p-3 d-inline-flex mb-3">
                            <i class="bi bi-exclamation-triangle text-danger" style="font-size: 2rem;"></i>
                        </div>
                        <h5 class="fw-bold text-danger">{{ __('Are you absolutely sure?') }}</h5>
                        <p class="text-muted">{{ __('This action cannot be undone. This will permanently delete your account and remove all your data from our servers.') }}</p>
                    </div>

                    <div class="mb-3">
                        <label for="delete_password" class="form-label fw-semibold">
                            <i class="bi bi-key me-2 text-danger"></i>{{ __('Enter your password to confirm') }}
                        </label>
                        <input id="delete_password" name="password" type="password" class="form-control" placeholder="{{ __('Your current password') }}" required>
                        @error('password', 'userDeletion')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="modal-footer border-0 p-4">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-2"></i>{{ __('Cancel') }}
                    </button>
                    <button type="submit" class="btn btn-danger-gradient">
                        <i class="bi bi-trash me-2"></i>{{ __('Yes, Delete My Account') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@if($errors->userDeletion->isNotEmpty())
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            new bootstrap.Modal(document.getElementById('deleteAccountModal')).show();
        });
    </script>
@endif
