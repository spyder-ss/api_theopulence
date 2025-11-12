<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ env('APP_NAME') }} - Login</title>
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <link href="{{ asset('css/login.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&display=swap" rel="stylesheet">
</head>

<body class="login-page">
    <div class="split-container">
        <!-- Left Section: Login Form -->
        <div class="left-section">
            <div class="login-card">
                <h2 class="welcome-back">Welcome Back!</h2>
                <p class="welcome-subtitle">Enter your credentials</p>

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
                        <label for="email" class="form-label">Email</label>
                        <div class="input-group">
                            <input type="email" name="email" id="email" value="{{ old('email') }}"
                                class="form-input @error('email') error @enderror" placeholder="Enter your email"
                                required autocomplete="email">
                        </div>
                    </div>

                    <!-- Password Field -->
                    <div class="form-group">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-group">
                            <input type="password" name="password" id="password"
                                class="form-input @error('password') error @enderror" placeholder="Enter your password"
                                required autocomplete="current-password">
                            <i data-lucide="eye-off" class="input-icon password-toggle"></i>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn-login" id="loginBtn">
                        <span>Sign In</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- Right Section: Promotional Content -->
        <div class="right-section">
            <div class="right-content">
                <h1 class="opulence-logo">The Opulence</h1>
                <h2 class="welcome-text">Welcome</h2>
                <p class="destination-text">You Are At India's Best & Top Destination</p>
                <p class="search-text">Searching Place</p>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }

            const passwordField = document.getElementById('password');
            const passwordToggle = document.querySelector('.password-toggle');

            if (passwordToggle) {
                passwordToggle.addEventListener('click', function () {
                    const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordField.setAttribute('type', type);
                    this.setAttribute('data-lucide', type === 'password' ? 'eye-off' : 'eye');
                    lucide.createIcons();
                });
            }
        });

        const loginForm = document.getElementById('loginForm');
        const loginBtn = document.getElementById('loginBtn');
        const btnText = loginBtn.querySelector('span');

        loginForm.addEventListener('submit', function (e) {
            loginBtn.classList.add('loading');
            btnText.textContent = 'Signing in...';
        });

        @if ($errors->any())
            loginBtn.classList.remove('loading');
            document.querySelector('.btn-login span').textContent = 'Sign In';
        @endif
    </script>
</body>

</html>