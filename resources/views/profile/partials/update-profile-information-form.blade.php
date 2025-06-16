<form id="send-verification" method="post" action="{{ route('verification.send') }}">
    @csrf
</form>

<form method="post" action="{{ route('profile.update') }}">
    @csrf
    @method('patch')

    <div class="mb-4">
        <label for="name" class="form-label fw-semibold">
            <i class="bi bi-person me-2 text-primary"></i>{{ __('Full Name') }}
        </label>
        <input id="name" name="name" type="text" class="form-control" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" placeholder="Enter your full name">
        @error('name')
            <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-4">
        <label for="email" class="form-label fw-semibold">
            <i class="bi bi-envelope me-2 text-primary"></i>{{ __('Email Address') }}
        </label>
        <input id="email" name="email" type="email" class="form-control" value="{{ old('email', $user->email) }}" required autocomplete="username" placeholder="Enter your email address">
        @error('email')
            <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror

        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
            <div class="alert alert-warning mt-3">
                <i class="bi bi-exclamation-triangle me-2"></i>
                <strong>{{ __('Your email address is unverified.') }}</strong>
                <br>
                <button form="send-verification" class="btn btn-link p-0 text-decoration-underline">
                    {{ __('Click here to re-send the verification email.') }}
                </button>
                
                @if (session('status') === 'verification-link-sent')
                    <div class="alert alert-success mt-2">
                        <i class="bi bi-check-circle me-2"></i>
                        {{ __('A new verification link has been sent to your email address.') }}
                    </div>
                @endif
            </div>
        @endif
    </div>

    <div class="d-flex align-items-center gap-3">
        <button type="submit" class="btn btn-gradient">
            <i class="bi bi-check-circle me-2"></i>{{ __('Save Changes') }}
        </button>

        @if (session('status') === 'profile-updated')
            <div class="alert alert-success mb-0 py-2 px-3" id="saved-message">
                <i class="bi bi-check-circle me-2"></i>{{ __('Profile updated successfully!') }}
            </div>
            <script>
                setTimeout(() => {
                    const message = document.getElementById('saved-message');
                    if (message) message.style.display = 'none';
                }, 3000);
            </script>
        @endif
    </div>
</form>
