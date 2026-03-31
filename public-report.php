<?php 
require_once 'header.php'; 
$page = 'public-report';
?>
<div class="page auth-page active" id="page-public-report">
  <div class="auth-card" style="max-width:620px; margin:auto; width:100%;">
    <button class="auth-close" onclick="navigate('home')">
      <i class="bi bi-x-lg"></i>
    </button>
    
    <div class="text-center" style="margin-bottom:28px;">
      <div class="auth-logo">Agri<span>Trace+</span></div>
      <div class="geo-badge">
        <i class="bi bi-globe2"></i>
        PUBLIC ACCESS
      </div>
      <p class="auth-subtitle" style="margin-bottom:0;">
        Submit Anonymous Reports for Livestock Health and Safety
      </p>
    </div>

    <div id="report-success" class="alert alert-success hidden"></div>
    <div id="report-error" class="alert alert-danger hidden"></div>

    <form id="public-report-form" enctype="multipart/form-data">
      <!-- Report Type -->
      <div class="form-group">
        <label class="form-label">Report Type <span style="color:var(--c-red);">*</span></label>
        <div class="radio-group">
          <div class="radio-item">
            <input type="radio" name="rtype" id="r-sick" value="Sick livestock" required>
            <label for="r-sick">Sick livestock</label>
          </div>
          <div class="radio-item">
            <input type="radio" name="rtype" id="r-dead" value="Dead animals">
            <label for="r-dead">Dead animals</label>
          </div>
          <div class="radio-item">
            <input type="radio" name="rtype" id="r-stray" value="Stray livestock">
            <label for="r-stray">Stray livestock</label>
          </div>
          <div class="radio-item">
            <input type="radio" name="rtype" id="r-outbreak" value="Suspected disease outbreak">
            <label for="r-outbreak">Suspected disease outbreak</label>
          </div>
          <div class="radio-item">
            <input type="radio" name="rtype" id="r-other" value="Others">
            <label for="r-other">Others</label>
          </div>
        </div>
        <input 
          type="text" 
          class="form-input no-icon mt-8 hidden" 
          id="other-txt" 
          name="other_type"
          placeholder="Please specify..."
        >
      </div>

      <!-- Photos/Videos -->
      <div class="form-group">
        <label class="form-label">Upload Photos/Videos</label>
        <div class="file-btn-wrap">
          <input 
            type="file" 
            class="form-input no-icon" 
            id="report-photos" 
            name="photos[]" 
            multiple 
            accept="image/*,video/*"
          >
          <button type="button" class="btn-camera" onclick="document.getElementById('report-photos').click()">
            <i class="bi bi-camera-fill"></i> Add
          </button>
        </div>
        <div id="photos-preview" class="mt-8" style="display:flex;flex-wrap:wrap;gap:8px;"></div>
      </div>

      <!-- Description -->
      <div class="form-group">
        <label class="form-label">Description <span style="color:var(--c-red);">*</span></label>
        <textarea 
          class="form-input" 
          name="description"
          rows="4" 
          placeholder="Describe the issue, location, or observation..." 
          required
        ></textarea>
      </div>

      <!-- Contact Phone -->
      <div class="form-group">
        <label class="form-label">Contact Phone <span style="color:var(--c-red);">*</span></label>
        <div class="input-wrap">
          <i class="input-icon bi bi-telephone"></i>
          <input 
            type="tel" 
            class="form-input" 
            name="phone"
            placeholder="+63 9XX XXX XXXX" 
            required
            pattern="[+]?[0-9\s\-\$\$]{10,}"
          >
        </div>
      </div>

      <!-- Contact Email -->
      <div class="form-group">
        <label class="form-label">Contact Email (Optional)</label>
        <div class="input-wrap">
          <i class="input-icon bi bi-envelope"></i>
          <input 
            type="email" 
            class="form-input" 
            name="email"
            placeholder="your.email@example.com"
          >
        </div>
      </div>

      <!-- ID Photo -->
      <div class="form-group">
        <label class="form-label">Upload ID Photo <span style="color:var(--c-red);">*</span></label>
        <div class="file-btn-wrap">
          <input 
            type="file" 
            class="form-input no-icon" 
            id="id-photo" 
            name="id_photo"
            accept="image/*" 
            required
          >
          <button type="button" class="btn-camera" onclick="document.getElementById('id-photo').click()">
            <i class="bi bi-camera-fill"></i> Add
          </button>
        </div>
        <div id="id-preview" class="mt-8" style="display:flex;gap:8px;"></div>
      </div>

      <!-- Face Photo -->
      <div class="form-group">
        <label class="form-label">Upload Face Photo <span style="color:var(--c-red);">*</span></label>
        <div class="file-btn-wrap">
          <input 
            type="file" 
            class="form-input no-icon" 
            id="face-photo" 
            name="face_photo"
            accept="image/*" 
            capture="user" 
            required
          >
          <button type="button" class="btn-camera" onclick="document.getElementById('face-photo').click()">
            <i class="bi bi-camera-fill"></i> Selfie
          </button>
        </div>
        <div id="face-preview" class="mt-8" style="display:flex;gap:8px;"></div>
      </div>

      <!-- Confirmation Checkbox -->
      <div class="form-group">
        <div class="check-group">
          <input type="checkbox" id="genuine-check" name="genuine_check" required>
          <label for="genuine-check">
            I confirm that this report is accurate, genuine, and not submitted in bad faith or for fraudulent purposes.
          </label>
        </div>
      </div>

      <button type="submit" class="btn btn-primary" id="submit-report-btn">
        <i class="bi bi-paper-plane-fill me-2"></i>
        SUBMIT REPORT
      </button>
    </form>

    <div class="auth-links mt-12">
      <a href="login.php" onclick="navigate('login'); return false;">
        Log In for Full Access
      </a>
      <a href="#" onclick="toggleTrack(); return false;" id="track-toggle">
        Track Report
      </a>
    </div>

    <!-- Track Section -->
    <div class="track-section hidden" id="track-section">
      <h5 style="margin-bottom:14px; font-size:0.95rem; font-weight:600; color:#fff;">
        Track Your Report
      </h5>
      <div class="track-row">
        <input 
          type="text" 
          class="form-input no-icon" 
          id="track-ref"
          placeholder="Enter Reference Number e.g. RPT-AB1234"
        >
        <button type="button" class="btn btn-panel" onclick="trackReport()" style="white-space:nowrap;">
          <i class="bi bi-search me-1"></i>Check
        </button>
      </div>
      <div id="track-result" class="mt-12" style="padding:16px; background:rgba(255,255,255,0.08); border-radius:12px; font-size:0.85rem;"></div>
    </div>

    <p class="auth-footer">© 2026 AgriTrace Technologies</p>
  </div>
</div>

<script>
let filePreviews = {
    photos: [],
    id: null,
    face: null
};

function initPage() {
    // Radio button handlers
    document.querySelectorAll('input[name="rtype"]').forEach(radio => {
        radio.addEventListener('change', function() {
            const otherTxt = document.getElementById('other-txt');
            otherTxt.classList.toggle('hidden', !this.checked || this.value !== 'Others');
            if (this.checked && this.value === 'Others') {
                otherTxt.focus();
            }
        });
    });

    // File preview handlers
    document.getElementById('report-photos').addEventListener('change', handlePhotos);
    document.getElementById('id-photo').addEventListener('change', handleIdPhoto);
    document.getElementById('face-photo').addEventListener('change', handleFacePhoto);

    // Form submission
    document.getElementById('public-report-form').addEventListener('submit', submitPublicReport);
}

function handlePhotos(e) {
    const files = Array.from(e.target.files);
    const preview = document.getElementById('photos-preview');
    
    files.forEach(file => {
        if (!filePreviews.photos.includes(file.name)) {
            filePreviews.photos.push(file.name);
            const reader = new FileReader();
            reader.onload = function(e) {
                const div = document.createElement('div');
                div.style.cssText = 'position:relative; width:80px; height:80px; border-radius:8px; overflow:hidden; background:#fff; display:flex; align-items:center; justify-content:center;';
                div.innerHTML = `
                    <img src="${e.target.result}" style="width:100%; height:100%; object-fit:cover;">
                    <button type="button" onclick="removePreview(this, 'photos')" style="position:absolute; top:4px; right:4px; width:20px; height:20px; border:none; background:var(--c-red); color:#fff; border-radius:50%; font-size:10px; cursor:pointer;">×</button>
                `;
                preview.appendChild(div);
            };
            reader.readAsDataURL(file);
        }
    });
}

function handleIdPhoto(e) {
    const file = e.target.files[0];
    if (file) {
        filePreviews.id = file.name;
        const preview = document.getElementById('id-preview');
        preview.innerHTML = `
            <div style="position:relative; width:80px; height:80px; border-radius:8px; overflow:hidden; background:#fff;">
                <img src="${URL.createObjectURL(file)}" style="width:100%; height:100%; object-fit:cover;">
                <button type="button" onclick="document.getElementById('id-photo').value=''; this.parentElement.remove();" style="position:absolute; top:4px; right:4px; width:20px; height:20px; border:none; background:var(--c-red); color:#fff; border-radius:50%; font-size:10px; cursor:pointer;">×</button>
            </div>
        `;
    }
}

function handleFacePhoto(e) {
    const file = e.target.files[0];
    if (file) {
        filePreviews.face = file.name;
        const preview = document.getElementById('face-preview');
        preview.innerHTML = `
            <div style="position:relative; width:80px; height:80px; border-radius:50%; overflow:hidden; background:#fff; border:3px solid var(--c-emerald);">
                <img src="${URL.createObjectURL(file)}" style="width:100%; height:100%; object-fit:cover;">
                <button type="button" onclick="document.getElementById('face-photo').value=''; this.parentElement.remove();" style="position:absolute; bottom:-8px; right:4px; width:24px; height:24px; border:none; background:var(--c-emerald); color:#fff; border-radius:50%; font-size:12px; cursor:pointer;">×</button>
            </div>
        `;
    }
}

function removePreview(btn, type) {
    btn.parentElement.remove();
    if (type === 'photos') {
        filePreviews.photos = filePreviews.photos.filter(name => 
            !btn.parentElement.querySelector('img').src.includes(name)
        );
    }
}

async function submitPublicReport(e) {
    e.preventDefault();
    
    const formData = new FormData(e.target);
    const submitBtn = document.getElementById('submit-report-btn');
    const originalText = submitBtn.innerHTML;
    
    // Add files to FormData
    const photosInput = document.getElementById('report-photos');
    const idInput = document.getElementById('id-photo');
    const faceInput = document.getElementById('face-photo');
    
    Array.from(photosInput.files).forEach((file, index) => {
        formData.append('photos[]', file);
    });
    if (idInput.files[0]) formData.append('id_photo', idInput.files[0]);
    if (faceInput.files[0]) formData.append('face_photo', faceInput.files[0]);
    
    // Validation
    if (!formData.get('rtype') || !formData.get('description') || !formData.get('phone')) {
        showToast('Please fill all required fields', true);
        return;
    }
    
    // Show loading
    submitBtn.innerHTML = '<span class="dots-loader"><span></span><span></span><span></span></span> Submitting...';
    submitBtn.disabled = true;
    
    try {
        const response = await fetch('api.php/public-reports', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (response.ok) {
            document.getElementById('report-success').innerHTML = 
                `<i class="bi bi-check-circle-fill me-2"></i> 
                 Report submitted successfully! 
                 <strong>Reference: ${result.ref_id}</strong>`;
            document.getElementById('report-success').classList.remove('hidden');
            e.target.reset();
            clearPreviews();
            
            // Show track section
            setTimeout(() => {
                toggleTrack();
            }, 2000);
        } else {
            throw new Error(result.error || 'Submission failed');
        }
    } catch (error) {
        document.getElementById('report-error').innerHTML = 
            `<i class="bi bi-x-circle-fill me-2"></i> ${error.message}`;
        document.getElementById('report-error').classList.remove('hidden');
    } finally {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    }
}

function clearPreviews() {
    document.getElementById('photos-preview').innerHTML = '';
    document.getElementById('id-preview').innerHTML = '';
    document.getElementById('face-preview').innerHTML = '';
    filePreviews = { photos: [], id: null, face: null };
}

function toggleTrack() {
    const trackSection = document.getElementById('track-section');
    const toggleLink = document.getElementById('track-toggle');
    trackSection.classList.toggle('hidden');
    toggleLink.textContent = trackSection.classList.contains('hidden') ? 'Track Report' : 'Hide Tracker';
}

async function trackReport() {
    const ref = document.getElementById('track-ref').value.trim().toUpperCase();
    const resultDiv = document.getElementById('track-result');
    
    if (!ref) {
        resultDiv.innerHTML = '<span style="color:#fca5a5;">Please enter a reference number</span>';
        return;
    }
    
    resultDiv.innerHTML = '<span class="dots-loader" style="justify-content:center; padding:12px;"></span>';
    
    try {
        const response = await fetch(`api.php/public-reports/track/${ref}`);
        const data = await response.json();
        
        if (data.status) {
            resultDiv.innerHTML = `
                <div style="color:var(--c-emerald-light);">
                    <strong>${data.status}</strong><br>
                    <small style="opacity:0.8;">Updated: ${data.updated_at}</small>
                </div>
            `;
        } else {
            resultDiv.innerHTML = '<span style="color:#fca5a5;">Report not found or invalid reference</span>';
        }
    } catch (error) {
        resultDiv.innerHTML = '<span style="color:#fca5a5;">Tracking failed. Please try again.</span>';
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', initPage);
</script>

<?php require_once 'footer.php'; ?>