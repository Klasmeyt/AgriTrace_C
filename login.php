<?php 
require_once 'header.php'; 
$page = 'login';

if (isLoggedIn()) {
    $role = getUserRole();
    if ($role === 'Admin') header('Location: admin-dashboard.php');
    elseif ($role === 'Agriculture Official') header('Location: agri-dashboard.php');
    else header('Location: farmer-dashboard.php');
    exit;
}
?>
<div class="page auth-page active" id="page-login">
  <div class="auth-card" style="max-width:480px; margin:auto; width:100%;">
    <button class="auth-close" onclick="navigate('home')">
      <i class="bi bi-x-lg"></i>
    </button>
    
    <div class="text-center" style="margin-bottom:28px;">
      <div class="auth-logo">Agri<span>Trace+</span></div>
      <div class="geo-badge">
        <i class="bi bi-geo-alt-fill"></i>
        GEO-TAGGING ENABLED
      </div>
      <p class="auth-subtitle">
        A Digital Livestock Registration and<br>Reporting System
      </p>
    </div>

    <div id="login-error" class="alert alert-danger hidden"></div>

    <form id="login-form" method="POST" action="api.php/login">
      <div class="form-group">
        <div class="input-wrap">
          <i class="input-icon bi bi-person"></i>
          <input 
            type="email" 
            class="form-input" 
            name="email"
            id="login-email" 
            placeholder="Email Address" 
            required 
            autocomplete="email"
          >
        </div>
      </div>

      <div class="form-group">
        <div class="input-wrap">
          <i class="input-icon bi bi-lock"></i>
          <input 
            type="password" 
            class="form-input" 
            name="password"
            id="login-password" 
            placeholder="Password" 
            required 
            autocomplete="current-password"
          >
          <button type="button" class="toggle-pass" onclick="togglePw('login-password',this)">
            <i class="bi bi-eye" id="login-eye"></i>
          </button>
        </div>
      </div>

      <button type="submit" class="btn btn-primary mt-8">
        <i class="bi bi-box-arrow-in-right me-2"></i>
        LOG IN
      </button>
    </form>

    <div class="auth-links mt-12">
      <a href="register.php" onclick="navigate('register'); return false;">
        Register Account
      </a>
      <a href="forgot-password.php" onclick="navigate('forgot-password'); return false;">
        Forgot Password?
      </a>
    </div>

    <button class="btn btn-secondary" onclick="navigate('public-report')">
      <i class="bi bi-globe me-2"></i>Access Public Panel
    </button>

    <p class="auth-footer">© 2026 AgriTrace Technologies</p>
  </div>
</div>

<script>
function initPage() {
    // Enhanced login form handler
    const form = document.getElementById('login-form');
    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const email = document.getElementById('login-email').value.trim().toLowerCase();
        const password = document.getElementById('login-password').value;
        const errorDiv = document.getElementById('login-error');
        
        // Show loading state
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<span class="dots-loader"><span></span><span></span><span></span></span> Signing In...';
        submitBtn.disabled = true;
        errorDiv.classList.add('hidden');
        
        try {
            const response = await fetch('api.php/login', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ email, password })
            });
            
            const data = await response.json();
            
            if (response.ok && data.success) {
                // Store session and redirect
                sessionStorage.setItem('user_id', data.user.id);
                sessionStorage.setItem('user_email', data.user.email);
                sessionStorage.setItem('user_role', data.user.role);
                
                // Redirect based on role
                if (data.user.role === 'Admin') {
                    window.location.href = 'admin-dashboard.php';
                } else if (data.user.role === 'Agriculture Official') {
                    window.location.href = 'agri-dashboard.php';
                } else {
                    window.location.href = 'farmer-dashboard.php';
                }
            } else {
                errorDiv.innerHTML = '<i class="bi bi-x-circle-fill me-2"></i>' + (data.error || 'Login failed');
                errorDiv.classList.remove('hidden');
            }
        } catch (error) {
            errorDiv.innerHTML = '<i class="bi bi-wifi-off me-2"></i> Network error. Please check your connection.';
            errorDiv.classList.remove('hidden');
        } finally {
            // Reset button
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }
    });
}

// Password toggle functionality
function togglePw(inputId, button) {
    const input = document.getElementById(inputId);
    const icon = button.querySelector('i');
    
    if (input.type === 'password') {
        input.type = 'text';
        icon.className = 'bi bi-eye-slash';
    } else {
        input.type = 'password';
        icon.className = 'bi bi-eye';
    }
}

// Enter key navigation
document.addEventListener('DOMContentLoaded', function() {
    initPage();
    
    // Allow Enter key to submit form
    document.getElementById('login-password').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            document.getElementById('login-form').dispatchEvent(new Event('submit'));
        }
    });
    
    // Focus first input
    document.getElementById('login-email').focus();
});
</script>

<?php require_once 'footer.php'; ?>