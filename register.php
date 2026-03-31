<?php 
require_once 'header.php'; 
$page = 'register';
?>
<div class="page auth-page active" id="page-register">
  <div class="auth-card" style="max-width:520px; max-height:90vh; overflow-y:auto; margin:auto; width:100%;">
    <button class="auth-close" onclick="navigate('login')">
      <i class="bi bi-x-lg"></i>
    </button>
    
    <div class="text-center" style="margin-bottom:24px;">
      <div class="auth-logo">Agri<span>Trace+</span></div>
      <div class="geo-badge">
        <i class="bi bi-geo-alt-fill"></i>
        GEO-TAGGING ENABLED
      </div>
      <p class="auth-subtitle">Create Your Account</p>
    </div>

    <div id="reg-success" class="alert alert-success hidden"></div>
    <div id="reg-error" class="alert alert-danger hidden"></div>

    <form id="register-form" enctype="multipart/form-data">
      <!-- Names Row -->
      <div class="panel-form-row">
        <div class="form-group">
          <label class="form-label">First Name <span style="color:var(--c-red);">*</span></label>
          <input 
            type="text" 
            class="form-input no-icon" 
            name="first_name"
            placeholder="Juan" 
            required
            pattern="[A-Za-z\s]{2,}"
          >
        </div>
        <div class="form-group">
          <label class="form-label">Last Name <span style="color:var(--c-red);">*</span></label>
          <input 
            type="text" 
            class="form-input no-icon" 
            name="last_name"
            placeholder="dela Cruz" 
            required
            pattern="[A-Za-z\s]{2,}"
          >
        </div>
      </div>

      <!-- Email -->
      <div class="form-group">
        <label class="form-label">Email Address <span style="color:var(--c-red);">*</span></label>
        <div class="input-wrap">
          <i class="input-icon bi bi-envelope"></i>
          <input 
            type="email" 
            class="form-input" 
            name="email"
            placeholder="juan@example.com" 
            required
            autocomplete="email"
          >
        </div>
      </div>

      <!-- Mobile -->
      <div class="form-group">
        <label class="form-label">Mobile Number <span style="color:var(--c-red);">*</span></label>
        <div class="input-wrap">
          <i class="input-icon bi bi-phone"></i>
          <input 
            type="tel" 
            class="form-input" 
            name="mobile"
            placeholder="+63 9XX XXX XXXX" 
            required
            pattern="[+]?[0-9\s\-\$\$]{10,}"
          >
        </div>
      </div>

      <!-- Role -->
      <div class="form-group">
        <label class="form-label">Role <span style="color:var(--c-red);">*</span></label>
        <select class="form-select" name="role" required>
          <option value="">-- Select Role --</option>
          <option value="Farmer">Farmer</option>
          <option value="Agriculture Official">Agriculture Official</option>
        </select>
      </div>

      <!-- Password with Strength Meter -->
      <div class="form-group">
        <label class="form-label">
          Password 
          <span style="cursor:pointer; color:var(--c-emerald);" onclick="togglePwReqs()" title="Password requirements">
            <i class="bi bi-info-circle"></i>
          </span>
          <span style="color:var(--c-red);">*</span>
        </label>
        <div class="input-wrap">
          <i class="input-icon bi bi-lock"></i>
          <input 
            type="password" 
            class="form-input" 
            id="reg-password" 
            name="password"
            placeholder="Create a strong password" 
            required 
            minlength="8"
            autocomplete="new-password"
          >
          <button type="button" class="toggle-pass" onclick="togglePw('reg-password',this)">
            <i class="bi bi-eye" id="reg-eye"></i>
          </button>
        </div>
        
        <!-- Password Strength Bar -->
        <div class="pw-strength-bar">
          <div class="pw-strength-fill" id="pw-fill"></div>
        </div>
        <div class="pw-strength-text" id="pw-text">Enter password</div>
        
        <!-- Password Requirements -->
        <ul class="pw-reqs" id="pw-reqs">
          <li id="req-len"><i class="bi bi-circle me-2"></i>At least 8 characters</li>
          <li id="req-num"><i class="bi bi-circle me-2"></i>Contains a number</li>
          <li id="req-spec"><i class="bi bi-circle me-2"></i>Contains a special character</li>
          <li id="req-low"><i class="bi bi-circle me-2"></i>Contains a lowercase letter</li>
          <li id="req-up"><i class="bi bi-circle me-2"></i>Contains an uppercase letter</li>
        </ul>
      </div>

      <!-- Confirm Password -->
      <div class="form-group">
        <label class="form-label">Confirm Password <span style="color:var(--c-red);">*</span></label>
        <div class="input-wrap">
          <i class="input-icon bi bi-lock-fill"></i>
          <input 
            type="password" 
            class="form-input" 
            id="reg-confirm" 
            name="confirm_password"
            placeholder="Repeat password" 
            required
            autocomplete="new-password"
          >
        </div>
        <div id="pw-match" class="pw-strength-text mt-4" style="display:none;"></div>
      </div>

      <!-- Terms Checkbox -->
      <div class="form-group">
        <div class="check-group">
          <input type="checkbox" id="terms-check" name="terms_check" onchange="handleTermsCheck(this)">
          <label for="terms-check">
            I agree to the <a href="#" onclick="openTermsModal(); return false;">Terms and Conditions</a> 
            <span style="color:var(--c-red);">*</span>
          </label>
        </div>
      </div>

      <button 
        type="submit" 
        class="btn btn-primary" 
        id="reg-btn" 
        disabled
        style="width:100%; padding:16px 20px; font-size:1.05rem;"
      >
        <i class="bi bi-person-plus me-2"></i>
        CREATE ACCOUNT
      </button>
    </form>

    <div class="auth-links mt-12" style="justify-content:center;">
      <a href="login.php" onclick="navigate('login'); return false;">
        Already have an account? <strong>Log In</strong>
      </a>
    </div>

    <p class="auth-footer">© 2026 AgriTrace Technologies</p>
  </div>
</div>

<script>
let pwReqs = {
    len: false, num: false, spec: false, low: false, up: false
};
let pwStrong = false;

function initPage() {
    const passwordInput = document.getElementById('reg-password');
    const confirmInput = document.getElementById('reg-confirm');
    const form = document.getElementById('register-form');
    
    // Real-time password strength checking
    passwordInput.addEventListener('input', function() {
        checkPwStrength(this.value);
    });
    
    // Confirm password matching
    confirmInput.addEventListener('input', function() {
        checkPwMatch(this.value, passwordInput.value);
    });
    
    // Form submission
    form.addEventListener('submit', handleRegisterWithDB);
    
    // Email validation
    document.querySelector('input[name="email"]').addEventListener('blur', function() {
        validateEmail(this);
    });
}

function checkPwStrength(password) {
    const fill = document.getElementById('pw-fill');
    const text = document.getElementById('pw-text');
    const btn = document.getElementById('reg-btn');
    
    // Reset requirements
    Object.keys(pwReqs).forEach(key => pwReqs[key] = false);
    updatePwReqs();
    
    if (password.length === 0) {
        fill.className = '';
        text.textContent = 'Enter password';
        pwStrong = false;
        updateRegisterBtn();
        return;
    }
    
    // Check requirements
    pwReqs.len = password.length >= 8;
    pwReqs.num = /\d/.test(password);
    pwReqs.spec = /[!@#$%^&*(),.?":{}|<>]/.test(password);
    pwReqs.low = /[a-z]/.test(password);
    pwReqs.up = /[A-Z]/.test(password);
    
    updatePwReqs();
    
    // Determine strength
    const metCount = Object.values(pwReqs).filter(Boolean).length;
    if (metCount <= 2) {
        fill.className = 'weak';
        text.textContent = 'Weak';
        text.className = 'pw-strength-text weak';
        pwStrong = false;
    } else if (metCount <= 4) {
        fill.className = 'medium';
        text.textContent = 'Medium';
        text.className = 'pw-strength-text medium';
        pwStrong = false;
    } else {
        fill.className = 'strong';
        text.textContent = 'Strong';
        text.className = 'pw-strength-text strong';
        pwStrong = true;
    }
    
    updateRegisterBtn();
}

function updatePwReqs() {
    document.querySelectorAll('.pw-reqs li').forEach((li, index) => {
        const reqId = li.id.split('-')[1];
        const icon = li.querySelector('i');
        
        if (pwReqs[reqId]) {
            li.classList.add('met');
            icon.className = 'bi bi-check-circle-fill me-2';
        } else {
            li.classList.remove('met');
            icon.className = 'bi bi-circle me-2';
        }
    });
}

function checkPwMatch(confirm, original) {
    const matchDiv = document.getElementById('pw-match');
    if (confirm === original && confirm.length > 0) {
        matchDiv.textContent = 'Passwords match ✓';
        matchDiv.style.color = 'var(--c-emerald-light)';
        matchDiv.style.display = 'block';
    } else if (confirm.length > 0) {
        matchDiv.textContent = "Passwords don't match";
        matchDiv.style.color = '#fca5a5';
        matchDiv.style.display = 'block';
    } else {
        matchDiv.style.display = 'none';
    }
    updateRegisterBtn();
}

function validateEmail(input) {
    const email = input.value.trim();
    const error = input.parentElement.parentElement.querySelector('.text-sm');
    if (error) error.remove();
    
    if (email && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        input.style.borderColor = 'var(--c-red)';
        input.style.background = 'rgba(254, 242, 242)';
        const errMsg = document.createElement('p');
        errMsg.className = 'text-sm mt-8';
        errMsg.style.cssText = 'color:#fca5a5; font-size:0.78rem; margin:4px 0 0;';
        errMsg.innerHTML = '<i class="bi bi-info-circle-fill me-1"></i> Please enter a valid email address';
        input.parentElement.parentElement.appendChild(errMsg);
    } else {
        input.style.borderColor = 'transparent';
        input.style.background = 'rgba(255,255,255,0.96)';
    }
}

function handleTermsCheck(checkbox) {
    updateRegisterBtn();
}

function updateRegisterBtn() {
    const btn = document.getElementById('reg-btn');
    const termsCheck = document.getElementById('terms-check').checked;
    const roleSelect = document.querySelector('select[name="role"]').value;
    const confirmPw = document.getElementById('reg-confirm').value;
    const password = document.getElementById('reg-password').value;
    
    const isValid = pwStrong && 
                   termsCheck && 
                   roleSelect && 
                   confirmPw === password && 
                   password.length >= 8;
    
    btn.disabled = !isValid;
    btn.style.opacity = isValid ? '1' : '0.6';
}

async function handleRegisterWithDB(e) {
    e.preventDefault();
    
    const formData = new FormData(e.target);
    const submitBtn = document.getElementById('reg-btn');
    const originalText = submitBtn.innerHTML;
    
    // Show loading
    submitBtn.innerHTML = '<span class="dots-loader"><span></span><span></span><span></span></span> Creating Account...';
    submitBtn.disabled = true;
    
    try {
        const response = await fetch('api.php/register', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                first_name: formData.get('first_name'),
                last_name: formData.get('last_name'),
                email: formData.get('email').toLowerCase().trim(),
                mobile: formData.get('mobile'),
                role: formData.get('role'),
                password: formData.get('password')
            })
        });
        
        const result = await response.json();
        
        if (response.ok && result.success) {
            document.getElementById('reg-success').innerHTML = 
                `<i class="bi bi-check-circle-fill me-2"></i>
                 Registration successful! Account created. 
                 <strong>Please login to continue.</strong>`;
            document.getElementById('reg-success').classList.remove('hidden');
            
            setTimeout(() => {
                navigate('login');
            }, 3000);
        } else {
            throw new Error(result.error || 'Registration failed');
        }
    } catch (error) {
        document.getElementById('reg-error').innerHTML = 
            `<i class="bi bi-x-circle-fill me-2"></i> ${error.message}`;
        document.getElementById('reg-error').classList.remove('hidden');
    } finally {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    }
}

function togglePwReqs() {
    const reqs = document.getElementById('pw-reqs');
    reqs.style.display = reqs.style.display === 'none' ? 'block' : 'none';
}

// Initialize
document.addEventListener('DOMContentLoaded', initPage);
</script>

<?php require_once 'footer.php'; ?>