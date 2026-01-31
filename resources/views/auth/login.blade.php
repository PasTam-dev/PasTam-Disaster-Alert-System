<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Pasong Tamo Risk Reduction System — Login</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;500;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            primary: '#115272',
            'primary-dark': '#0d3f55',
            'primary-light': '#1a6e96',
            'accent': '#FF6B6B',
            'accent-dark': '#FF5252',
          }
        }
      }
    }
  </script>
  <style>
    body {
      font-family: "Montserrat", system-ui, -apple-system, Arial, sans-serif;
      background: linear-gradient(135deg, #f5f7fa 0%, #e4edf5 100%);
    }

    .glass-effect {
      background: rgba(255, 255, 255, 0.25);
      backdrop-filter: blur(10px);
      -webkit-backdrop-filter: blur(10px);
      border: 1px solid rgba(255, 255, 255, 0.18);
    }

    .floating-card {
      box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
    }

    .input-transition {
      transition: all 0.3s ease;
    }

    .gradient-bg {
      background: linear-gradient(135deg, #115272 0%, #0d3f55 100%);
    }

    .accent-gradient {
      background: linear-gradient(135deg, #FF6B6B 0%, #FF5252 100%);
    }

    .logo-text {
      text-shadow: 2px 4px 8px rgba(0,0,0,0.28);
    }

    .form-section {
      position: relative;
      overflow: hidden;
    }

    .logo-placeholder {
      width: 48px;
      height: 48px;
      background: linear-gradient(45deg, #2563eb, #3b82f6);
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-weight: bold;
      font-size: 20px;
      box-shadow: 0 4px 10px rgba(37, 99, 235, 0.3);
    }

    .floating-element {
      animation: float 6s ease-in-out infinite;
    }

    @keyframes float {
      0% { transform: translateY(0px); }
      50% { transform: translateY(-10px); }
      100% { transform: translateY(0px); }
    }

    .pulse-animation {
      animation: pulse 2s infinite;
    }

    @keyframes pulse {
      0% { transform: scale(1); }
      50% { transform: scale(1.05); }
      100% { transform: scale(1); }
    }
  </style>
</head>

<body class="min-h-screen flex items-center justify-center p-4">
  <div class="flex w-full max-w-4xl h-auto md:h-[600px] rounded-2xl floating-card overflow-hidden">
    
    <!-- Left Branding Section -->
   <div class="hidden md:flex w-2/5 items-center justify-center gradient-bg p-8 relative overflow-hidden">
  <!-- Background Pattern -->
  <div class="absolute inset-0 opacity-10">
    <div class="absolute top-10 left-10 w-20 h-20 rounded-full bg-white floating-element"></div>
    <div class="absolute bottom-16 right-16 w-16 h-16 rounded-full bg-white floating-element" style="animation-delay: 2s;"></div>
    <div class="absolute top-1/2 left-1/4 w-12 h-12 rounded-full bg-white floating-element" style="animation-delay: 4s;"></div>
  </div>
  
  <!-- Content -->
  <div class="text-center z-10">
    <div class="flex flex-col items-center justify-center mb-6">
      <div class="logo-placeholder pulse-animation mb-4">
        <i class="fas fa-shield-alt"></i>
      </div>
     
      <div class="text-sm font-medium tracking-widest text-white/80">
        RISK REDUCTION MANAGEMENT SYSTEM
      </div>
    </div>

    <!-- Feature List -->
    <div class="my-8 space-y-4 text-left">
      <div class="flex items-center">
        <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center mr-3">
          <i class="fas fa-check text-white text-sm"></i>
        </div>
        <span class="text-white/80 text-sm">Hazard Identification & Assessment</span>
      </div>

      <div class="flex items-center">
        <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center mr-3">
          <i class="fas fa-check text-white text-sm"></i>
        </div>
        <span class="text-white/80 text-sm">Incident Reporting & Monitoring</span>
      </div>

      <div class="flex items-center">
        <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center mr-3">
          <i class="fas fa-check text-white text-sm"></i>
        </div>
        <span class="text-white/80 text-sm">Data-Driven Risk Analysis</span>
      </div>
    </div>

    <div class="mt-10">
      <div class="w-16 h-1 bg-white/30 mx-auto rounded-full mb-2"></div>
      <p class="text-white/60 text-xs max-w-xs mx-auto">
        Helping organizations reduce risks and improve safety
      </p>
    </div>
  </div>

  <div class="absolute bottom-6 left-0 right-0 text-center text-white/50 text-xs">
    © 2026 Risk Reduction Management System
  </div>
</div>


    <!-- Right Login Form -->
    <div class="flex w-full md:w-3/5 items-center justify-center bg-white p-8 md:p-12 form-section">
      <div class="w-full max-w-sm z-10">
        <!-- Mobile Logo (visible only on small screens) -->
        <div class="md:hidden flex justify-center mb-8">
          <div class="flex items-center">
            <div class="logo-placeholder">
              <i class="fas fa-users"></i>
            </div>
          </div>
        </div>

        <div class="text-center mb-8">
          <h2 class="text-3xl font-bold text-gray-800 mb-2">Welcome Back</h2>
          <p class="text-gray-600">Sign in to access your Pasong Tamo dashboard</p>
        </div>

        <!-- Display Error Messages -->
        <div class="bg-red-50 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded-lg mb-4 text-sm hidden" id="errorMessage">
          <!-- Error messages will be displayed here -->
        </div>

        <div class="bg-green-50 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded-lg mb-4 text-sm hidden" id="successMessage">
          <!-- Success messages will be displayed here -->
        </div>

        <form method="POST" action="{{ route('authenticate') }}" class="space-y-6">
          @csrf

          <!-- Email -->
          <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
            <div class="relative">
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fas fa-envelope text-gray-400"></i>
              </div>
              <input id="email" name="email" type="email" placeholder="Enter your email" required
                value="{{ old('email') }}"
                class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-primary-light focus:border-transparent input-transition outline-none bg-gray-50" />
            </div>
          </div>

          <!-- Password -->
          <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
            <div class="relative">
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fas fa-lock text-gray-400"></i>
              </div>
              <input id="password" name="password" type="password" placeholder="Enter your password" required
                class="w-full pl-10 pr-10 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-primary-light focus:border-transparent input-transition outline-none bg-gray-50" />
              <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center" id="togglePassword">
                <i class="fas fa-eye text-gray-400 hover:text-gray-600"></i>
              </button>
            </div>
          </div>

          <!-- Remember & Forgot -->
          <div class="flex items-center justify-between">
            <div class="flex items-center">
              <input id="remember" name="remember" type="checkbox" class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded" />
              <label for="remember" class="ml-2 block text-sm text-gray-700">Remember me</label>
            </div>
            <a href="#" class="text-sm text-primary hover:text-primary-dark font-medium">Forgot Password?</a>
          </div>

          <!-- Button -->
          <button type="submit"
            class="w-full py-3 px-4 rounded-xl accent-gradient text-white font-semibold shadow-md hover:shadow-lg transition-all duration-300 flex items-center justify-center pulse-animation">
            <span>Sign In</span>
            <i class="fas fa-arrow-right ml-2"></i>
          </button>

          <!-- Divider -->
          {{-- <div class="relative flex items-center my-6">
            <div class="flex-grow border-t border-gray-300"></div>
            <span class="flex-shrink mx-4 text-gray-500 text-sm">or continue with</span>
            <div class="flex-grow border-t border-gray-300"></div>
          </div> --}}

          <!-- Social Login -->
          
        </form>

        {{-- <div class="text-center mt-8 text-sm text-gray-600">
          Don't have an account? <a href="{{route('register')}}" class="text-primary font-medium hover:text-primary-dark">Register Now</a>
        </div> --}}

      </div>
    </div>
  </div>
  <script>
    // Toggle password visibility
    document.getElementById('togglePassword').addEventListener('click', function() {
      const passwordInput = document.getElementById('password');
      const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
      passwordInput.setAttribute('type', type);

      // Toggle eye icon
      this.querySelector('i').classList.toggle('fa-eye');
      this.querySelector('i').classList.toggle('fa-eye-slash');
    });

    // Add focus styles to inputs
    const inputs = document.querySelectorAll('input');
    inputs.forEach(input => {
      input.addEventListener('focus', () => {
        input.parentElement.classList.add('ring-2', 'ring-primary-light', 'rounded-xl');
      });

      input.addEventListener('blur', () => {
        input.parentElement.classList.remove('ring-2', 'ring-primary-light', 'rounded-xl');
      });
    });
  </script>
</body>

</html>