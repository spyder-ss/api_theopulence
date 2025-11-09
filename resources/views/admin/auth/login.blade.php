<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{env('APP_NAME')}} - Admin Dashboard</title>
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <link href="{{ asset('css/login.css') }}" rel="stylesheet">
</head>

<body class="login-page">
    <div class="login-container">
        <div class="login-card">
            <!-- Logo Section -->
            <div class="logo-section">
                <div class="logo">
                    <div class="logo-icon">LM</div>
                    <span>Lagan Matrimonials</span>
                </div>
                <h1 class="login-title">Admin Login</h1>
                <p class="login-subtitle">Sign in to your admin account</p>
            </div>

            <!-- Error Messages -->
            @if ($errors->any())
                <div class="error-alert">
                    <ul class="error-list">
                        @foreach ($errors->all() as $error)
                            <li class="error-item">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Login Form -->
            <form method="POST" action="{{ route('login') }}" id="loginForm">
                @csrf

                <!-- Email Field -->
                <div class="form-group">
                    <label for="email" class="form-label">Email Address</label>
                    <div class="input-group">
                        <i data-lucide="mail" class="input-icon"></i>
                        <input type="email" name="email" id="email" value="{{ old('email') }}"
                            class="form-input @error('email') error @enderror" placeholder="Enter your email address"
                            required>
                    </div>
                </div>

                <!-- Password Field -->
                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-group">
                        <i data-lucide="lock" class="input-icon"></i>
                        <input type="password" name="password" id="password"
                            class="form-input @error('password') error @enderror" placeholder="Enter your password"
                            required>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn-login" id="loginBtn">
                    <i data-lucide="log-in" style="width: 18px; height: 18px;"></i>
                    <span>Sign In</span>
                </button>
            </form>
        </div>
    </div>

    <script>
        // Initialize Lucide icons
        document.addEventListener('DOMContentLoaded', function () {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });

        // Form submission handling
        const loginForm = document.getElementById('loginForm');
        const loginBtn = document.getElementById('loginBtn');
        const btnText = loginBtn.querySelector('span');
        const btnIcon = loginBtn.querySelector('i');

        loginForm.addEventListener('submit', function (e) {
            // Add loading state to button
            loginBtn.classList.add('loading');
            btnText.textContent = 'Signing in...';

            // You can add additional validation or AJAX handling here if needed

            // For now, just let the form submit normally
            // The loading state will reset on page reload if there are errors
        });

        // Reset button state if there are validation errors
        @if ($errors->any())
            loginBtn.classList.remove('loading');
            document.querySelector('span').textContent = 'Sign In';
        @endif
    </script>
</body>

</html>