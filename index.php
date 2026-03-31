<?php 
require_once 'header.php'; 
$page = 'home';
?>
<div class="page auth-page active" id="page-home" style="min-height:100vh; justify-content:center; align-items:center; padding-top:68px; flex-direction:column;">
  <div class="hero-content">
    <div class="hero-badge">
      <i class="bi bi-geo-alt-fill"></i>
      GEO-TAGGING ENABLED
    </div>
    <h1>Welcome to<br><span class="hl">AgriTrace+</span></h1>
    <p>A Digital Livestock Registration & Reporting System<br>with Geo-Tagging Integration</p>
    <div class="cta-group">
      <a href="login.php" class="btn-hero-primary">
        <i class="bi bi-box-arrow-in-right"></i> Get Started
      </a>
      <a href="public-report.php" class="btn-hero-secondary">
        <i class="bi bi-file-earmark-text"></i> Public Report
      </a>
    </div>
  </div>
  
  <div class="hero-feature-strip">
    <div class="hf-card">
      <i class="bi bi-geo-alt-fill"></i>
      <h5>Geo-Tagging</h5>
      <p>GPS Enabled</p>
    </div>
    <div class="hf-card">
      <i class="bi bi-shield-check"></i>
      <h5>Secure</h5>
      <p>Data Protected</p>
    </div>
    <div class="hf-card">
      <i class="bi bi-graph-up"></i>
      <h5>Real-time</h5>
      <p>Live Monitoring</p>
    </div>
  </div>
  
  <footer class="site-footer" style="position:relative; z-index:2; width:100%; margin-top: auto;">
    © 2026 AgriTrace Technologies | 
    <a href="about.php" onclick="navigate('about'); return false;">About</a> | 
    <a href="contact.php" onclick="navigate('contact'); return false;">Contact</a>
  </footer>
</div>

<!-- Page switching script for SPA-like navigation -->
<script>
function navigate(page) {
    // Hide all pages
    document.querySelectorAll('.page').forEach(p => p.classList.remove('active'));
    
    // Show target page
    const targetPage = document.getElementById('page-' + page);
    if (targetPage) {
        targetPage.classList.add('active');
    } else {
        // Navigate to external page
        window.location.href = page + '.php';
    }
    
    // Close mobile menu
    document.getElementById('mobile-menu').classList.remove('open');
    
    // Update page title
    document.title = 'AgriTrace+ | ' + (page === 'home' ? 'Digital Livestock Registration System' : page.charAt(0).toUpperCase() + page.slice(1));
}

function initPage() {
    navigate('<?php echo $page; ?>');
}

// Mobile menu toggle
function toggleMobileMenu() {
    const menu = document.getElementById('mobile-menu');
    menu.classList.toggle('open');
}

function closeMobileMenu() {
    document.getElementById('mobile-menu').classList.remove('open');
}

// Toast notification
function showToast(message, isError = false) {
    const toast = document.getElementById('toast');
    const msg = document.getElementById('toast-msg');
    msg.textContent = message;
    
    if (isError) {
        toast.querySelector('i').className = 'bi bi-x-circle-fill';
        toast.style.background = 'linear-gradient(135deg, var(--c-red), #dc2626)';
    } else {
        toast.querySelector('i').className = 'bi bi-check-circle-fill';
        toast.style.background = 'var(--c-forest)';
    }
    
    toast.style.display = 'block';
    setTimeout(() => {
        toast.style.display = 'none';
    }, 4000);
}
</script>

<?php require_once 'footer.php'; ?>