<?php
// Basic page rendering only; form posts handled via fetch to php/auth_login.php and php/auth_signup.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login / Sign Up - Namma Fontsu</title>
  
  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>
  
  <!-- Font Awesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&family=Manrope:wght@200;400;500;600;700;800&family=Noto+Sans+Kannada:wght@400;700&family=Noto+Sans+Bengali:wght@400;700&family=Noto+Sans+Devanagari:wght@400;700&family=Permanent+Marker&display=swap" rel="stylesheet">
  
  <!-- Custom CSS -->
  <link rel="stylesheet" href="css/styles.css">
  <link rel="stylesheet" href="css/login.css">
</head>
<body class="font-sans antialiased min-h-screen flex flex-col selection:bg-teal selection:text-white">
  <!-- NAV -->
  <nav class="sticky top-0 z-50 bg-bg/95 backdrop-blur-md border-b-2 border-text">
    <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
      <a href="index.html" class="flex flex-col items-start leading-none group">
        <div class="text-xl font-brush tracking-wide scale-y-110">NAMMA</div>
        <div class="text-xl font-brush tracking-wide relative scale-y-110">FONTSU
          <span class="absolute -bottom-1 left-0 w-full h-1 bg-teal rounded-full transform origin-left group-hover:scale-x-110 transition-transform"></span>
        </div>
      </a>
      <div class="hidden md:flex items-center gap-8 font-bold text-sm">
        <a href="fonts.html" class="hover:text-teal uppercase tracking-wider">All Fonts</a>
        <a href="designers.html" class="hover:text-teal uppercase tracking-wider">Designers</a>
        <a href="about.html" class="hover:text-teal uppercase tracking-wider">About</a>
      </div>
    </div>
  </nav>

  <!-- MAIN -->
  <main class="flex-1 relative z-10">
    
    <div class="auth-container">
      <div class="auth-wrapper">
        
        <!-- Left Panel -->
        <div class="auth-left">
          <div class="auth-logo">NAMMA FONTSU</div>
          <h2 class="auth-tagline">India's Premier Font Marketplace</h2>
          <p class="auth-description">
            Join thousands of designers and type creators building the future of Indian typography.
          </p>
          <ul class="auth-features">
            <li>
              <i class="fa-solid fa-check-circle"></i>
              <span>Access 500+ premium fonts</span>
            </li>
            <li>
              <i class="fa-solid fa-check-circle"></i>
              <span>Support independent designers</span>
            </li>
            <li>
              <i class="fa-solid fa-check-circle"></i>
              <span>Sell your own typefaces</span>
            </li>
            <li>
              <i class="fa-solid fa-check-circle"></i>
              <span>Flexible licensing options</span>
            </li>
          </ul>
        </div>

        <!-- Right Panel -->
        <div class="auth-right">
          
          <!-- Tabs -->
          <div class="auth-tabs">
            <button class="auth-tab active" onclick="switchTab('login')">Login</button>
            <button class="auth-tab" onclick="switchTab('signup')">Sign Up</button>
          </div>

          <!-- Login Form -->
          <div id="login-form" class="tab-content active">
            <form class="auth-form" onsubmit="handleLogin(event)">
              <div class="form-group">
                <label class="form-label">Email</label>
                <input type="email" id="login-email" class="form-input" placeholder="you@example.com" required>
              </div>
              
              <div class="form-group">
                <label class="form-label">Password</label>
                <input type="password" id="login-password" class="form-input" placeholder="••••••••" required>
              </div>

              <div class="flex justify-between items-center">
                <label class="form-checkbox-group">
                  <input type="checkbox" class="form-checkbox">
                  <span class="form-checkbox-label">Remember me</span>
                </label>
                <a href="#" class="text-sm font-bold text-teal hover:underline">Forgot password?</a>
              </div>

              <button type="submit" class="btn-primary w-full form-submit">Log In</button>
              <div id="login-message" class="hidden p-3 text-sm font-bold"></div>
            </form>

            <div class="divider">Or continue with</div>

            <div class="social-login">
              <button class="social-btn" onclick="continueWithGoogle()">
                <i class="fa-brands fa-google"></i>
                <span>Continue with Google</span>
              </button>
            </div>

            <div class="form-link">
              Don't have an account? <a href="#" onclick="switchTab('signup'); return false;">Sign up</a>
            </div>
          </div>

          <!-- Signup Form -->
          <div id="signup-form" class="tab-content">
            <form class="auth-form" onsubmit="handleSignup(event)">
              <div class="form-group">
                <label class="form-label">Full Name</label>
                <input type="text" id="signup-name" class="form-input" placeholder="John Doe" required>
              </div>

              <div class="form-group">
                <label class="form-label">Phone Number</label>
                <input type="tel" id="signup-phone" class="form-input" placeholder="9876543210" required>
              </div>

              <div class="form-group">
                <label class="form-label">Email</label>
                <input type="email" id="signup-email" class="form-input" placeholder="you@example.com" required>
              </div>
              
              <div class="form-group">
                <label class="form-label">Password</label>
                <input type="password" id="signup-password" class="form-input" placeholder="••••••••" required>
              </div>

              <div class="form-group">
                <label class="form-label">I am a</label>
                <select id="signup-role" class="form-input" required>
                  <option value="">Select...</option>
                  <option value="designer">Designer</option>
                  <option value="buyer">Buyer</option>
                  <option value="both">Both</option>
                </select>
              </div>

              <label class="form-checkbox-group">
                <input type="checkbox" class="form-checkbox" required>
                <span class="form-checkbox-label">I agree to the Terms & Conditions</span>
              </label>

              <button type="submit" class="btn-primary w-full form-submit">Create Account</button>
              <div id="signup-message" class="hidden p-3 text-sm font-bold"></div>
            </form>

            <div class="divider">Or continue with</div>

            <div class="social-login">
              <button class="social-btn" onclick="continueWithGoogle()">
                <i class="fa-brands fa-google"></i>
                <span>Continue with Google</span>
              </button>
            </div>

            <div class="form-link">
              Already have an account? <a href="#" onclick="switchTab('login'); return false;">Log in</a>
            </div>
          </div>

        </div>

      </div>
    </div>

  </main>

  <script>
    function switchTab(tab) {
      const loginTab = document.getElementById('login-tab');
      const signupTab = document.getElementById('signup-tab');
      const loginForm = document.getElementById('login-form');
      const signupForm = document.getElementById('signup-form');
      // Update tabs (earlier style)
      document.querySelectorAll('.auth-tab').forEach(t => t.classList.remove('active'));
      // Event target could be undefined if called programmatically; set active via ids
      if (tab === 'login') document.querySelectorAll('.auth-tab')[0].classList.add('active');
      if (tab === 'signup') document.querySelectorAll('.auth-tab')[1].classList.add('active');
      
      // Update content (earlier style)
      document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
      document.getElementById(tab + '-form').classList.add('active');
    }

    async function handleLogin(e) {
      e.preventDefault();
      const email = document.getElementById('login-email').value.trim();
      const password = document.getElementById('login-password').value;
      const messageEl = document.getElementById('login-message');
      messageEl.classList.add('hidden');
      try {
        const resp = await fetch('php/auth_login.php', {
          method: 'POST', headers: {'Content-Type':'application/json'}, body: JSON.stringify({ email, password })
        });
        const result = await resp.json();
        if (result.success) {
          messageEl.textContent = 'Login successful!';
          messageEl.className = 'p-3 text-sm font-bold bg-teal/20 border-2 border-teal text-teal';
          messageEl.classList.remove('hidden');
          localStorage.setItem('user', JSON.stringify(result.user));
          setTimeout(()=>{ window.location.href='fonts.html'; }, 1200);
        } else {
          messageEl.textContent = result.message || 'Login failed';
          messageEl.className = 'p-3 text-sm font-bold bg-red-100 border-2 border-red-500 text-red-700';
          messageEl.classList.remove('hidden');
        }
      } catch (err) {
        messageEl.textContent = 'Error connecting to server';
        messageEl.className = 'p-3 text-sm font-bold bg-red-100 border-2 border-red-500 text-red-700';
        messageEl.classList.remove('hidden');
      }
    }

    async function handleSignup(e) {
      e.preventDefault();
      const payload = {
        name: document.getElementById('signup-name').value.trim(),
        phone: document.getElementById('signup-phone').value.trim(),
        email: document.getElementById('signup-email').value.trim(),
        role: document.getElementById('signup-role').value,
        password: document.getElementById('signup-password').value
      };
      const messageEl = document.getElementById('signup-message');
      messageEl.classList.add('hidden');
      try {
        const resp = await fetch('php/auth_signup.php', {
          method: 'POST', headers: {'Content-Type':'application/json'}, body: JSON.stringify(payload)
        });
        const result = await resp.json();
        if (result.success) {
          messageEl.textContent = 'Account created successfully!';
          messageEl.className = 'p-3 text-sm font-bold bg-teal/20 border-2 border-teal text-teal';
          messageEl.classList.remove('hidden');
          localStorage.setItem('user', JSON.stringify(result.user));
          setTimeout(()=>{ window.location.href='fonts.html'; }, 1200);
        } else {
          messageEl.textContent = result.message || 'Signup failed';
          messageEl.className = 'p-3 text-sm font-bold bg-red-100 border-2 border-red-500 text-red-700';
          messageEl.classList.remove('hidden');
        }
      } catch (err) {
        messageEl.textContent = 'Error connecting to server';
        messageEl.className = 'p-3 text-sm font-bold bg-red-100 border-2 border-red-500 text-red-700';
        messageEl.classList.remove('hidden');
      }
    }

    function continueWithGoogle() {
      alert('Google login is available soon.');
    }

    // default
    switchTab('login');
  </script>
</body>
</html>