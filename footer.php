<!-- TOAST NOTIFICATION -->
<div id="toast" style="position:fixed;bottom:28px;right:28px;z-index:9999;display:none;">
  <div style="background:var(--c-forest);color:#fff;padding:14px 22px;border-radius:12px;box-shadow:0 8px 28px rgba(0,0,0,0.25);display:flex;align-items:center;gap:10px;font-size:0.9rem;font-weight:500;max-width:340px;animation:fadeIn 0.3s ease;">
    <i class="bi bi-check-circle-fill" style="color:var(--c-emerald);font-size:1.1rem;" id="toast-icon"></i>
    <span id="toast-msg">Action completed</span>
  </div>
</div>

<!-- PROFILE EDIT MODAL -->
<div class="modal-overlay" id="profile-modal">
  <div class="modal-box" style="max-width:640px; max-height:92vh; overflow-y:auto;">
    <button class="modal-close" onclick="closeProfileModal()">
      <i class="bi bi-x-lg"></i>
    </button>
    <h3 id="profile-modal-title">Edit Profile</h3>
    <div id="profile-modal-body">
      <!-- Dynamic content loaded here -->
    </div>
    <div style="margin-top:20px; display:flex; gap:10px; justify-content:flex-end; padding:0 0 4px;">
      <button class="btn btn-outline btn-sm" onclick="closeProfileModal()">Cancel</button>
      <button class="btn btn-panel" id="profile-save-btn" onclick="saveProfileChanges()">
        <i class="bi bi-check-lg me-1"></i>Save Changes
      </button>
    </div>
  </div>
</div>

<!-- RECORD EDIT MODAL -->
<div class="modal-overlay" id="record-modal">
  <div class="modal-box" style="max-width:560px;">
    <button class="modal-close" onclick="closeModal('record-modal')">
      <i class="bi bi-x-lg"></i>
    </button>
    <h3 id="record-modal-title">Edit Record</h3>
    <div id="record-modal-body"></div>
    <div style="margin-top:20px; display:flex; gap:10px; justify-content:flex-end;">
      <button class="btn btn-outline btn-sm" onclick="closeModal('record-modal')">Cancel</button>
      <button class="btn btn-panel" id="record-save-btn" onclick="saveRecord()">Save</button>
    </div>
  </div>
</div>

<!-- CONFIRM MODAL -->
<div class="modal-overlay" id="confirm-modal">
  <div class="modal-box" style="max-width:400px;">
    <i class="bi bi-exclamation-triangle-fill" style="font-size:3rem; color:var(--c-amber); display:block; text-align:center; margin-bottom:16px;"></i>
    <h3 id="confirm-modal-title">Confirm Action</h3>
    <p id="confirm-modal-msg" style="color:var(--c-slate-600); font-size:0.92rem; margin-bottom:20px; line-height:1.6;"></p>
    <div style="display:flex; gap:10px; justify-content:flex-end;">
      <button class="btn btn-outline btn-sm" onclick="closeModal('confirm-modal')">Cancel</button>
      <button class="btn btn-danger btn-sm" id="confirm-ok-btn">Confirm</button>
    </div>
  </div>
</div>

<!-- TERMS & CONDITIONS MODAL -->
<div class="modal-overlay" id="terms-modal">
  <div class="modal-box">
    <button class="modal-close" onclick="closeTermsModal()">
      <i class="bi bi-x-lg"></i>
    </button>
    <h3>Terms and Conditions</h3>
    <div class="modal-terms">
      <p><strong>1. Acceptance of Terms:</strong> By accessing and using AgriTrace+, you accept and agree to be bound by these terms.</p>
      <p><strong>2. Use License:</strong> Permission to use for personal, non-commercial purposes only. No redistribution allowed.</p>
      <p><strong>3. Data Privacy:</strong> Compliant with RA 10173 (Data Privacy Act of the Philippines). Your data is protected.</p>
      <p><strong>4. Reports Accuracy:</strong> You confirm all reports are accurate and submitted in good faith.</p>
      <p><strong>5. Limitation of Liability:</strong> AgriTrace+ not liable for indirect damages from platform use.</p>
      <p><strong>6. Governing Law:</strong> Governed by laws of the Republic of the Philippines.</p>
      <p><strong>7. Account Termination:</strong> Accounts violating terms may be suspended or terminated.</p>
    </div>
    <div class="modal-footer">
      <div class="check-group">
        <input type="checkbox" id="modal-terms-check" onchange="toggleTermsConfirm(this)">
        <label for="modal-terms-check" style="color:var(--c-slate-700); font-weight:500;">
          I have read and agree to the Terms and Conditions
        </label>
      </div>
      <button class="btn-modal-confirm" id="terms-confirm-btn" disabled onclick="confirmTerms()">
        <i class="bi bi-check-lg me-1"></i>Accept Terms
      </button>
    </div>
  </div>
</div>

<!-- CAMERA MODAL (for mobile file capture) -->
<div class="modal-overlay" id="camera-modal">
  <div class="modal-box" style="max-width:400px;">
    <button class="modal-close" onclick="closeCameraModal()">
      <i class="bi bi-x-lg"></i>
    </button>
    <h3 id="camera-modal-title">Capture Photo</h3>
    <div style="position:relative; height:300px; background:#000; border-radius:12px; overflow:hidden; margin:20px 0;">
      <video id="camera-video" style="width:100%; height:100%; object-fit:cover;" autoplay></video>
      <canvas id="camera-canvas" style="display:none;"></canvas>
    </div>
    <div style="display:flex; gap:12px; justify-content:center;">
      <button class="btn btn-outline" onclick="closeCameraModal()">Cancel</button>
      <button class="btn btn-primary" id="capture-btn" onclick="capturePhoto()">
        <i class="bi bi-camera-fill me-2"></i>Capture
      </button>
    </div>
  </div>
</div>

<script>
// ========== GLOBAL UTILITY FUNCTIONS ==========
function showToast(message, isError = false, duration = 4000) {
    const toast = document.getElementById('toast');
    const icon = document.getElementById('toast-icon');
    const msg = document.getElementById('toast-msg');
    
    msg.textContent = message;
    
    if (isError) {
        icon.className = 'bi bi-x-circle-fill';
        icon.style.color = 'var(--c-red)';
        toast.querySelector('div').style.background = 'linear-gradient(135deg, var(--c-red), #dc2626)';
    } else {
        icon.className = 'bi bi-check-circle-fill';
        icon.style.color = 'var(--c-emerald)';
        toast.querySelector('div').style.background = 'var(--c-forest)';
    }
    
    toast.style.display = 'block';
    setTimeout(() => toast.style.display = 'none', duration);
}

function showConfirm(title, message, callback) {
    document.getElementById('confirm-modal-title').textContent = title;
    document.getElementById('confirm-modal-msg').innerHTML = message;
    document.getElementById('confirm-modal').classList.add('open');
    
    document.getElementById('confirm-ok-btn').onclick = function() {
        closeModal('confirm-modal');
        if (callback) callback();
    };
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.remove('open');
}

// ========== MODAL FUNCTIONS ==========
function openTermsModal() {
    document.getElementById('terms-modal').classList.add('open');
}

function closeTermsModal() {
    document.getElementById('terms-modal').classList.remove('open');
    document.getElementById('modal-terms-check').checked = false;
}

function toggleTermsConfirm(checkbox) {
    document.getElementById('terms-confirm-btn').disabled = !checkbox.checked;
}

function confirmTerms() {
    document.getElementById('terms-check').checked = true;
    closeTermsModal();
    handleTermsCheck(document.getElementById('terms-check'));
    showToast('Terms accepted');
}

function openProfileModal(panel) {
    loadProfileForm(panel);
    document.getElementById('profile-modal').classList.add('open');
}

function closeProfileModal() {
    document.getElementById('profile-modal').classList.remove('open');
}

function closeCameraModal() {
    document.getElementById('camera-modal').classList.remove('open');
    stopCamera();
}

// ========== PANEL FUNCTIONS ==========
function openPanelSidebar(panel) {
    document.getElementById(`${panel}-sidebar`).classList.add('open');
    document.getElementById(`${panel}-overlay`).classList.add('open');
}

function closePanelSidebar(panel) {
    document.getElementById(`${panel}-sidebar`).classList.remove('open');
    document.getElementById(`${panel}-overlay`).classList.remove('open');
}

function showPanel(panel, section) {
    // Update nav active state
    document.querySelectorAll(`#${panel}-sidebar .panel-nav-item`).forEach(item => {
        item.classList.remove('active');
        if (item.dataset.section === section) item.classList.add('active');
    });
    
    // Update title
    document.getElementById(`${panel}-section-title`).textContent = 
        document.querySelector(`#${panel}-sidebar [data-section="${section}"]`).textContent.trim();
    
    // Switch sections
    document.querySelectorAll(`#${panel} .panel-section`).forEach(s => s.classList.remove('active'));
    document.getElementById(`${panel}-${section}`).classList.add('active');
}

// ========== CAMERA FUNCTIONS ==========
let stream = null;
function openCameraModal(type) {
    document.getElementById('camera-modal-title').textContent = `Capture ${type} Photo`;
    document.getElementById('camera-modal').classList.add('open');
    startCamera();
}

async function startCamera() {
    try {
        stream = await navigator.mediaDevices.getUserMedia({ 
            video: { facingMode: 'user', width: { ideal: 640 }, height: { ideal: 480 } } 
        });
        document.getElementById('camera-video').srcObject = stream;
    } catch (err) {
        showToast('Camera access denied', true);
    }
}

function stopCamera() {
    if (stream) {
        stream.getTracks().forEach(track => track.stop());
        stream = null;
    }
}

function capturePhoto() {
    const video = document.getElementById('camera-video');
    const canvas = document.getElementById('camera-canvas');
    const ctx = canvas.getContext('2d');
    
    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;
    ctx.drawImage(video, 0, 0);
    
    const dataUrl = canvas.toDataURL('image/jpeg', 0.8);
    
    // Trigger file input with captured image
    const file = dataURLtoFile(dataUrl, 'captured-photo.jpg');
    const dt = new DataTransfer();
    dt.items.add(file);
    document.getElementById('current-file-input').files = dt.files;
    
    closeCameraModal();
    showToast('Photo captured successfully!');
}

function dataURLtoFile(dataurl, filename) {
    const arr = dataurl.split(',');
    const mime = arr[0].match(/:(.*?);/)[1];
    const bstr = atob(arr[1]);
    let n = bstr.length;
    const u8arr = new Uint8Array(n);
    while(n--) u8arr[n] = bstr.charCodeAt(n);
    return new File([u8arr], filename, {type:mime});
}

// ========== PASSWORD FUNCTIONS ==========
function togglePw(inputId, button) {
    const input = document.getElementById(inputId);
    const icon = button.querySelector('i');
    
    if (input.type === 'password') {
        input.type = 'text';
        icon.className = icon.id === 'login-eye' ? 'bi bi-eye-slash' : 'bi bi-eye-slash-fill';
    } else {
        input.type = 'password';
        icon.className = icon.id === 'login-eye' ? 'bi bi-eye' : 'bi bi-eye-fill';
    }
}

// ========== PAGE INITIALIZATION ==========
document.addEventListener('DOMContentLoaded', function() {
    // Initialize current page
    if (typeof initPage === 'function') {
        initPage();
    }
    
    // Global modal close on overlay click
    document.querySelectorAll('.modal-overlay').forEach(overlay => {
        overlay.addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.remove('open');
            }
        });
    });
    
    // Escape key closes modals
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            document.querySelectorAll('.modal-overlay.open').forEach(modal => {
                modal.classList.remove('open');
            });
        }
    });
    
    // Prevent body scroll when modals open
    document.querySelectorAll('.modal-overlay').forEach(modal => {
        modal.addEventListener('classchange', function() {
            document.body.style.overflow = this.classList.contains('open') ? 'hidden' : '';
        });
    });
});

// Polyfill for classList change detection
(function() {
    const getClass = (el) => el.className.baseVal || el.className;
    const origHas = DOMTokenList.prototype.has;
    DOMTokenList.prototype.has = function(cls) {
        return origHas.call(this, cls) || getClass(this.ownerElement).split(' ').indexOf(cls) !== -1;
    };
})();
</script>
</body>
</html>