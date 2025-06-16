<form method="post" action="{{ route('password.update') }}">
    @csrf
    @method('put')

    <div class="mb-3">
        <label for="update_password_current_password" class="form-label fw-semibold">
            <i class="bi bi-lock me-2 text-primary"></i>{{ __('Current Password') }}
        </label>
        <input id="update_password_current_password" name="current_password" type="password" class="form-control" autocomplete="current-password" placeholder="Enter your current password">
        @error('current_password', 'updatePassword')
            <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="update_password_password" class="form-label fw-semibold">
            <i class="bi bi-key me-2 text-primary"></i>{{ __('New Password') }}
        </label>
        <input id="update_password_password" name="password" type="password" class="form-control" autocomplete="new-password" placeholder="Enter a strong new password">
        @error('password', 'updatePassword')
            <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-4">
        <label for="update_password_password_confirmation" class="form-label fw-semibold">
            <i class="bi bi-shield-check me-2 text-primary"></i>{{ __('Confirm New Password') }}
        </label>
        <input id="update_password_password_confirmation" name="password_confirmation" type="password" class="form-control" autocomplete="new-password" placeholder="Confirm your new password">
        @error('password_confirmation', 'updatePassword')
            <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror
    </div>

    <div class="d-flex align-items-center gap-3">
        <button type="submit" class="btn btn-gradient">
            <i class="bi bi-shield-lock me-2"></i>{{ __('Update Password') }}
        </button>

        @if (session('status') === 'password-updated')
            <div class="alert alert-success mb-0 py-2 px-3" id="password-saved-message">
                <i class="bi bi-check-circle me-2"></i>{{ __('Password updated successfully!') }}
            </div>
            <script>
                setTimeout(() => {
                    const message = document.getElementById('password-saved-message');
                    if (message) message.style.display = 'none';
                }, 3000);
            </script>
        @endif
    </div>
</form>
