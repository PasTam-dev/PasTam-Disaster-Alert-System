<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Two-Factor Authentication - Human Resource 3</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --secondary: #10b981;
            --light: #f8fafc;
            --dark: #1e293b;
            --gray: #64748b;
            --error: #ef4444;
            --success: #10b981;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 50%, #faf5ff 100%);
        }
        
        .glass-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }
        
        .otp-input {
            width: 3.5rem;
            height: 3.5rem;
            text-align: center;
            font-size: 1.5rem;
            font-weight: 600;
            background: rgba(255, 255, 255, 0.8);
            border: 2px solid #e2e8f0;
            transition: all 0.3s ease;
        }
        
        .otp-input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
            transform: translateY(-2px);
        }
        
        .otp-input.filled {
            border-color: var(--primary);
            background-color: rgba(99, 102, 241, 0.05);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(99, 102, 241, 0.2);
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(99, 102, 241, 0.3);
        }
        
        .btn-secondary {
            background: rgba(255, 255, 255, 0.8);
            border: 1px solid rgba(99, 102, 241, 0.2);
            transition: all 0.3s ease;
        }
        
        .btn-secondary:hover {
            background: rgba(99, 102, 241, 0.05);
            transform: translateY(-1px);
        }
        
        .pulse-animation {
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
        
        .floating {
            animation: floating 3s ease-in-out infinite;
        }
        
        @keyframes floating {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }
        
        @media (max-width: 640px) {
            .otp-input {
                width: 2.75rem;
                height: 2.75rem;
                font-size: 1.25rem;
            }
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <!-- Floating Elements (Background) -->
        <div class="absolute top-10 left-10 w-20 h-20 bg-indigo-200 rounded-full opacity-20 floating"></div>
        <div class="absolute bottom-10 right-10 w-16 h-16 bg-blue-300 rounded-full opacity-30 floating" style="animation-delay: 1.5s;"></div>
        <div class="absolute top-1/3 right-1/4 w-12 h-12 bg-purple-200 rounded-full opacity-40 floating" style="animation-delay: 2.5s;"></div>
        
        <!-- Card Container -->
        <div class="glass-card rounded-2xl overflow-hidden relative">
            <!-- Header Section -->
            <div class="relative px-8 py-10 text-center overflow-hidden">
                <!-- Background Pattern -->
                <div class="absolute inset-0 bg-gradient-to-br from-indigo-500/10 to-purple-500/10"></div>
                <div class="absolute -top-20 -right-20 w-40 h-40 bg-white/10 rounded-full"></div>
                <div class="absolute -bottom-20 -left-20 w-40 h-40 bg-white/10 rounded-full"></div>
                
                <!-- Content -->
                <div class="relative z-10">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-white/30 rounded-full mb-6 backdrop-blur-sm pulse-animation">
                        <i class="fas fa-shield-alt text-white text-3xl"></i>
                    </div>
                    <h1 class="text-3xl font-bold text-gray-800 mb-2">Two-Factor Authentication</h1>
                    <p class="text-gray-600">Secure your account with an extra layer of protection</p>
                </div>
            </div>

            <!-- Main Content -->
            <div class="px-8 py-8">
                <!-- Info Alert -->
                <div class="mb-6 bg-blue-50/80 border border-blue-200 rounded-xl p-4 backdrop-blur-sm">
                    <div class="flex items-start">
                        <div class="flex-shrink-0 mt-0.5 mr-3">
                            <i class="fas fa-envelope text-blue-500"></i>
                        </div>
                        <div>
                            <p class="text-sm text-blue-800 font-medium">Verification code sent</p>
                            <p class="text-xs text-blue-600 mt-1">We've sent a 6-digit code to your registered email address. Please check your inbox.</p>
                        </div>
                    </div>
                </div>

                <!-- Error Messages -->
                <div class="mb-6 bg-red-50/80 border border-red-200 rounded-xl p-4 backdrop-blur-sm hidden" id="errorContainer">
                    <div class="flex items-start">
                        <div class="flex-shrink-0 mt-0.5 mr-3">
                            <i class="fas fa-exclamation-circle text-red-500"></i>
                        </div>
                        <div>
                            <p class="text-sm text-red-800 font-medium">Verification failed</p>
                            <p class="text-xs text-red-600 mt-1" id="errorMessage">Error message will appear here</p>
                        </div>
                    </div>
                </div>

                <!-- OTP Form -->
                <form method="POST" action="{{ route('two-factor.verify') }}" id="otpForm">
                    @csrf
                    
                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-gray-700 mb-4 text-center">Enter Verification Code</label>
                        
                        <!-- OTP Input Fields -->
                        <div class="flex justify-center gap-3 mb-6">
                            <input type="text" maxlength="1" class="otp-input rounded-xl" data-index="0" autocomplete="off">
                            <input type="text" maxlength="1" class="otp-input rounded-xl" data-index="1" autocomplete="off">
                            <input type="text" maxlength="1" class="otp-input rounded-xl" data-index="2" autocomplete="off">
                            <input type="text" maxlength="1" class="otp-input rounded-xl" data-index="3" autocomplete="off">
                            <input type="text" maxlength="1" class="otp-input rounded-xl" data-index="4" autocomplete="off">
                            <input type="text" maxlength="1" class="otp-input rounded-xl" data-index="5" autocomplete="off">
                        </div>

                        <!-- Hidden input for submission -->
                        <input type="hidden" name="code" id="codeInput">
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="w-full btn-primary text-white font-semibold py-3.5 rounded-xl mb-4 flex items-center justify-center">
                        <i class="fas fa-check-circle mr-2"></i>
                        Verify Code
                    </button>
                </form>

                <!-- Additional Actions -->
                <div class="mt-6 text-center space-y-4">
                    <p class="text-sm text-gray-600">
                        Didn't receive the code? 
                        <button id="resendBtn" class="text-indigo-600 hover:text-indigo-700 font-medium transition-colors">
                            <i class="fas fa-redo-alt mr-1"></i>
                            Resend Code
                        </button>
                    </p>
                    
                    <div class="pt-4 border-t border-gray-200/50">
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-sm text-gray-500 hover:text-gray-700 font-medium flex items-center justify-center mx-auto transition-colors">
                                <i class="fas fa-arrow-left mr-2"></i>
                                Return to Login
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="mt-8 text-center">
            <p class="text-sm text-gray-500 flex items-center justify-center">
                <i class="fas fa-shield-alt mr-2"></i>
                Your security is our priority
            </p>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const inputs = document.querySelectorAll('.otp-input');
            const form = document.getElementById('otpForm');
            const codeInput = document.getElementById('codeInput');
            const resendBtn = document.getElementById('resendBtn');
            const errorContainer = document.getElementById('errorContainer');
            const errorMessage = document.getElementById('errorMessage');
            
            // Simulate errors for demo purposes
            let errorCount = 0;
            
            // Handle resend button
            resendBtn.addEventListener('click', function() {
                // Show loading state
                const originalText = resendBtn.innerHTML;
                resendBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Sending...';
                resendBtn.disabled = true;
                
                // Simulate API call
                setTimeout(() => {
                    // Reset button
                    resendBtn.innerHTML = originalText;
                    resendBtn.disabled = false;
                    
                    // Show success message
                    showMessage('Verification code sent successfully!', 'success');
                    
                    // Clear inputs
                    inputs.forEach(input => {
                        input.value = '';
                        input.classList.remove('filled');
                    });
                    inputs[0].focus();
                }, 1500);
            });
            
            // Show message function
            function showMessage(message, type) {
                if (type === 'error') {
                    errorMessage.textContent = message;
                    errorContainer.classList.remove('hidden');
                    errorContainer.classList.remove('bg-green-50/80', 'border-green-200');
                    errorContainer.classList.add('bg-red-50/80', 'border-red-200');
                } else {
                    errorMessage.textContent = message;
                    errorContainer.classList.remove('hidden');
                    errorContainer.classList.remove('bg-red-50/80', 'border-red-200');
                    errorContainer.classList.add('bg-green-50/80', 'border-green-200');
                }
                
                // Auto-hide after 5 seconds
                setTimeout(() => {
                    errorContainer.classList.add('hidden');
                }, 5000);
            }
            
            inputs.forEach((input, index) => {
                // Focus first input on load
                if (index === 0) {
                    input.focus();
                }

                // Handle input
                input.addEventListener('input', function(e) {
                    const value = e.target.value;
                    
                    // Only allow numbers
                    if (!/^\d*$/.test(value)) {
                        e.target.value = '';
                        return;
                    }
                    
                    // Update filled state
                    if (value.length === 1) {
                        e.target.classList.add('filled');
                    } else {
                        e.target.classList.remove('filled');
                    }

                    // Move to next input
                    if (value.length === 1 && index < inputs.length - 1) {
                        inputs[index + 1].focus();
                    }

                    // Auto-submit when all filled
                    updateCodeInput();
                });

                // Handle paste
                input.addEventListener('paste', function(e) {
                    e.preventDefault();
                    const pasteData = e.clipboardData.getData('text').replace(/\D/g, '');
                    
                    if (pasteData.length === 6) {
                        inputs.forEach((inp, i) => {
                            inp.value = pasteData[i] || '';
                            if (pasteData[i]) {
                                inp.classList.add('filled');
                            }
                        });
                        inputs[5].focus();
                        updateCodeInput();
                    }
                });

                // Handle backspace
                input.addEventListener('keydown', function(e) {
                    if (e.key === 'Backspace' && !e.target.value && index > 0) {
                        inputs[index - 1].focus();
                        inputs[index - 1].value = '';
                        inputs[index - 1].classList.remove('filled');
                    }
                });

                // Handle arrow keys
                input.addEventListener('keydown', function(e) {
                    if (e.key === 'ArrowLeft' && index > 0) {
                        inputs[index - 1].focus();
                    } else if (e.key === 'ArrowRight' && index < inputs.length - 1) {
                        inputs[index + 1].focus();
                    }
                });
            });

            function updateCodeInput() {
                const code = Array.from(inputs).map(input => input.value).join('');
                codeInput.value = code;
                
                // Auto-submit when complete
                if (code.length === 6) {
                    setTimeout(() => {
                        // For demo purposes, simulate occasional errors
                        errorCount++;
                        if (errorCount % 3 === 0) {
                            showMessage('Invalid verification code. Please try again.', 'error');
                            // Clear inputs
                            inputs.forEach(input => {
                                input.value = '';
                                input.classList.remove('filled');
                            });
                            inputs[0].focus();
                        } else {
                            form.submit();
                        }
                    }, 500);
                }
            }

            // Form submission validation
            form.addEventListener('submit', function(e) {
                const code = Array.from(inputs).map(input => input.value).join('');
                if (code.length !== 6) {
                    e.preventDefault();
                    showMessage('Please enter the complete 6-digit code.', 'error');
                    inputs[0].focus();
                }
            });
        });
    </script>
</body>
</html>