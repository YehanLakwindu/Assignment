<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Weather App - Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-blue-100 to-indigo-200 min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full">
        <div class="bg-white rounded-xl shadow-lg p-8">

            <div class="text-center mb-6">
                <div class="mx-auto h-16 w-16 bg-blue-500 rounded-full flex items-center justify-center mb-4">
                    <span class="text-2xl">🌤️</span>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Weather Dashboard</h1>
                <p class="text-gray-600">Dual Email MFA Authentication</p>
            </div>

            @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
                <div class="flex items-center">
                    <span class="mr-2">❌</span>
                    <span>{{ session('error') }}</span>
                </div>
            </div>
            @endif

            @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
                <div class="flex items-center">
                    <span class="mr-2">✅</span>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
            @endif

            <!-- Working Login Form -->
            <form id="auth0-login-form" class="space-y-6">
                @csrf
                
                <!-- Account Selection -->
                <div class="space-y-4">
                    <label class="block font-semibold text-gray-700">Choose Account:</label>
                    
                    <!-- Account 1 Option -->
                    <div class="border rounded-lg p-4 cursor-pointer hover:bg-blue-50 account-option" 
                         data-email="careers@fidenz.com" 
                         data-password="Pass#fidenz">
                        <div class="flex items-center">
                            <input type="radio" name="account" value="account1" id="account1" class="mr-3" checked>
                            <label for="account1" class="cursor-pointer flex-1">
                                <div class="font-medium text-gray-900">Account 1 - Original</div>
                                <div class="text-sm text-gray-600">careers@fidenz.com</div>
                                <div class="text-xs text-gray-500">Password: Pass#fidenz</div>
                            </label>
                        </div>
                    </div>

                    <!-- Account 2 Option -->
                    <div class="border rounded-lg p-4 cursor-pointer hover:bg-blue-50 account-option" 
                         data-email="yehanlakvindurcg@gmail.com" 
                         data-password="fpW!6AtHjP!WTeb">
                        <div class="flex items-center">
                            <input type="radio" name="account" value="account2" id="account2" class="mr-3">
                            <label for="account2" class="cursor-pointer flex-1">
                                <div class="font-medium text-gray-900">Account 2 - New</div>
                                <div class="text-sm text-gray-600">yehanlakvindurcg@gmail.com</div>
                                <div class="text-xs text-gray-500">Password: fpW!6AtHjP!WTeb</div>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Current Selection Display -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h4 class="font-medium text-gray-700 mb-2">Selected Credentials:</h4>
                    <div class="space-y-2">
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Email:</label>
                            <input type="email" id="selected-email" value="careers@fidenz.com" 
                                   class="w-full px-3 py-2 bg-white border rounded focus:ring-2 focus:ring-blue-500" readonly>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Password:</label>
                            <input type="password" id="selected-password" value="Pass#fidenz" 
                                   class="w-full px-3 py-2 bg-white border rounded focus:ring-2 focus:ring-blue-500" readonly>
                        </div>
                    </div>
                </div>

                <!-- Login Button -->
                <div class="text-center">
                    <button type="submit" 
                            class="w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-4 px-6 rounded-lg transition-colors transform hover:scale-105 shadow-lg">
                        🔐 Login with Auth0 + Dual MFA
                    </button>
                </div>
            </form>

            <!-- Dual Email MFA Info -->
            <div class="mt-6 bg-purple-50 p-4 rounded-lg border border-purple-200">
                <h3 class="font-semibold text-purple-900 mb-2">📧📧 Dual Email MFA</h3>
                <p class="text-sm text-purple-700 mb-3">
                    After login, verification codes will be sent to <strong>BOTH</strong> emails:
                </p>
                <div class="grid grid-cols-1 gap-2">
                    <div class="bg-white p-2 rounded border flex items-center">
                        <span class="text-green-600 mr-2">📬</span>
                        <span class="text-xs font-mono">careers@fidenz.com</span>
                    </div>
                    <div class="bg-white p-2 rounded border flex items-center">
                        <span class="text-blue-600 mr-2">📬</span>
                        <span class="text-xs font-mono">yehanlakvindurcg@gmail.com</span>
                    </div>
                </div>
                <div class="mt-2 p-2 bg-purple-100 rounded text-xs text-purple-800">
                    💡 <strong>Same code in both emails</strong> - use either one!
                </div>
            </div>

            <!-- Instructions -->
            <div class="mt-6 bg-yellow-50 p-4 rounded-lg border border-yellow-200">
                <h4 class="font-semibold text-yellow-800 mb-2">📋 Login Steps:</h4>
                <ol class="text-sm text-yellow-700 space-y-1">
                    <li>1. Select account above (Account 1 or Account 2)</li>
                    <li>2. Click "Login with Auth0 + Dual MFA"</li>
                    <li>3. Enter credentials on Auth0 page</li>
                    <li>4. Check BOTH emails for verification code</li>
                    <li>5. Enter code and access weather dashboard</li>
                </ol>
            </div>

        </div>
    </div>

    <script>
        // Handle account selection
        document.querySelectorAll('.account-option').forEach(option => {
            option.addEventListener('click', function() {
                // Check the radio button
                const radio = this.querySelector('input[type="radio"]');
                radio.checked = true;
                
                // Update display fields
                const email = this.dataset.email;
                const password = this.dataset.password;
                
                document.getElementById('selected-email').value = email;
                document.getElementById('selected-password').value = password;
                
                // Visual feedback
                document.querySelectorAll('.account-option').forEach(opt => {
                    opt.classList.remove('bg-blue-50', 'border-blue-300');
                    opt.classList.add('border-gray-300');
                });
                
                this.classList.add('bg-blue-50', 'border-blue-300');
                this.classList.remove('border-gray-300');
            });
        });

        // Handle form submission
        document.getElementById('auth0-login-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const selectedEmail = document.getElementById('selected-email').value;
            const selectedPassword = document.getElementById('selected-password').value;
            
            console.log('🔐 Starting Auth0 login with:', {
                email: selectedEmail,
                password: '***hidden***'
            });
            
            // Store selected credentials in session storage for reference
            sessionStorage.setItem('selected_credentials', JSON.stringify({
                email: selectedEmail,
                password: selectedPassword
            }));
            
            // Redirect to Auth0 with email hint
            const authUrl = buildAuth0Url(selectedEmail);
            console.log('🚀 Redirecting to Auth0:', authUrl);
            
            window.location.href = authUrl;
        });

        function buildAuth0Url(emailHint) {
            const domain = '{{ env("AUTH0_DOMAIN") }}';
            const clientId = '{{ env("AUTH0_CLIENT_ID") }}';
            const redirectUri = '{{ env("AUTH0_REDIRECT_URI") }}';
            
            const params = new URLSearchParams({
                response_type: 'code',
                client_id: clientId,
                redirect_uri: redirectUri,
                scope: 'openid profile email',
                state: generateRandomString(32),
                nonce: generateRandomString(32),
                login_hint: emailHint, // Pre-fill email on Auth0 page
                // Dual email MFA parameters
                mfa_mode: 'dual_email',
                primary_email: 'careers@fidenz.com',
                secondary_email: 'yehanlakvindurcg@gmail.com',
                send_to_both: 'true'
            });
            
            return `https://${domain}/authorize?${params.toString()}`;
        }

        function generateRandomString(length) {
            const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
            let result = '';
            for (let i = 0; i < length; i++) {
                result += chars.charAt(Math.floor(Math.random() * chars.length));
            }
            return result;
        }

        // Initialize first account as selected
        document.querySelector('.account-option[data-email="careers@fidenz.com"]').click();
    </script>
</body>
</html>