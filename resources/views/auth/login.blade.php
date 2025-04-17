<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="login-form" id="loginForm">
        @csrf

        <!-- Username -->
        <div class="auth-input-group">
            <label for="username">{{ __('Felhasználónév') }}</label>
            <div class="input-with-icon">
                <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                <input id="username" type="text" name="username" value="{{ old('username') }}" required autofocus autocomplete="username" disabled />
            </div>
            <x-input-error :messages="$errors->get('username')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="auth-input-group mt-4">
            <label for="password">{{ __('Jelszó') }}</label>
            <div class="input-with-icon">
                <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
                <input id="password" type="password" name="password" required autocomplete="current-password" disabled />
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- ÁSZF Elfogadása -->
        <div class="auth-checkbox-wrapper mt-4">
            <input id="terms" type="checkbox" name="terms" required>
            <label for="terms" class="text-sm text-gray-300">
                Elfogadom az 
                <a href="{{ route('policy.terms') }}" target="_blank" class="text-blue-400 hover:text-blue-300 transition-colors">
                    Általános Szerződési Feltételeket
                </a>
            </label>
        </div>

        <!-- Remember Me -->
        <div class="auth-checkbox-wrapper">
            <input id="remember_me" type="checkbox" name="remember">
            <label for="remember_me">{{ __('Adatok megjegyzése') }}</label>
        </div>

        <!-- reCAPTCHA -->
        <div class="mt-4">
            <div class="g-recaptcha" 
                data-sitekey="{{ config('services.recaptcha.site_key') }}"
                data-theme="dark"
                data-size="normal"
                data-callback="onRecaptchaSuccess"
                data-expired-callback="onRecaptchaExpired"></div>
            <x-input-error :messages="$errors->get('g-recaptcha-response')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <button type="submit" class="bg-blue-500/10 hover:bg-blue-500/20 text-blue-400 hover:text-blue-300 p-1.5 rounded-lg transition-colors inline-flex items-center gap-2" id="loginButton" disabled>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                </svg>
                <span>{{ __('Bejelentkezés') }}</span>
            </button>
        </div>
    </form>

    <!-- reCAPTCHA Script -->
    <script src="https://www.google.com/recaptcha/api.js?hl=hu" async defer></script>

    <script>
        function onRecaptchaSuccess() {
            document.querySelector('button[type="submit"]').classList.add('bg-blue-600/20');
        }

        function onRecaptchaExpired() {
            document.querySelector('button[type="submit"]').classList.remove('bg-blue-600/20');
        }

        document.addEventListener('DOMContentLoaded', function() {
            const termsCheckbox = document.getElementById('terms');
            const usernameInput = document.getElementById('username');
            const passwordInput = document.getElementById('password');
            const loginButton = document.getElementById('loginButton');

            // Ellenőrizzük, hogy korábban elfogadta-e az ÁSZF-et
            const termsAccepted = localStorage.getItem('termsAccepted') === 'true';
            if (termsAccepted) {
                termsCheckbox.checked = true;
                usernameInput.disabled = false;
                passwordInput.disabled = false;
                loginButton.disabled = false;
            }

            // ÁSZF elfogadás kezelése
            termsCheckbox.addEventListener('change', function() {
                const isChecked = this.checked;
                usernameInput.disabled = !isChecked;
                passwordInput.disabled = !isChecked;
                loginButton.disabled = !isChecked;

                if (!isChecked) {
                    usernameInput.value = '';
                    passwordInput.value = '';
                }

                // Mentjük az ÁSZF elfogadását localStorage-be
                localStorage.setItem('termsAccepted', this.checked);
            });

            // Elmentett felhasználónév betöltése
            const savedUsername = localStorage.getItem('rememberedUsername');
            if (savedUsername) {
                usernameInput.value = savedUsername;
            }

            // Form elküldés kezelése
            document.getElementById('loginForm').addEventListener('submit', function() {
                const rememberCheckbox = document.getElementById('remember_me');
                const usernameInput = document.getElementById('username');

                if (rememberCheckbox.checked) {
                    // Felhasználónév mentése
                    localStorage.setItem('rememberedUsername', usernameInput.value);
                } else {
                    // Ha nincs bepipálva, töröljük a mentett adatokat
                    localStorage.removeItem('rememberedUsername');
                }
            });
        });
    </script>

    <style>
        .auth-input-group {
            @apply space-y-2;
        }
        
        .auth-input-group label {
            @apply block text-sm font-medium text-gray-300;
        }
        
        .input-with-icon {
            @apply relative;
        }
        
        .input-with-icon input {
            @apply w-full pl-10 pr-3 py-2 border border-gray-600 rounded-lg bg-gray-700 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200;
        }
        
        .input-icon {
            @apply absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400;
        }
        
        .auth-checkbox-wrapper {
            @apply flex items-center space-x-2 my-4;
        }
        
        .auth-checkbox-wrapper input[type="checkbox"] {
            @apply rounded border-gray-600 bg-gray-700 text-blue-500 focus:ring-blue-500;
        }
        
        .auth-checkbox-wrapper label {
            @apply text-sm text-gray-300;
        }

        /* reCAPTCHA container stílus */
        .g-recaptcha {
            display: flex;
            justify-content: center;
            margin: 0 auto;
        }

        /* reCAPTCHA iframe stílus */
        .g-recaptcha iframe {
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        /* reCAPTCHA error üzenet stílus */
        .g-recaptcha + .text-red-600 {
            text-align: center;
            margin-top: 0.5rem;
            font-size: 0.875rem;
        }
    </style>
</x-guest-layout>
