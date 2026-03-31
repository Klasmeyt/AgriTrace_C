<?php 
require_once 'header.php'; 
requireRole('Farmer');
$page = 'farmer-panel';
?>
<div class="page panel-page active" id="page-farmer-panel">
  <!-- Sidebar Overlay -->
  <div class="sidebar-overlay" id="farmer-overlay" onclick="closePanelSidebar('farmer')"></div>

  <!-- Farmer Sidebar -->
  <aside class="panel-sidebar" id="farmer-sidebar">
    <div class="panel-sidebar-header">
      <div class="panel-sidebar-logo">Agri<span>Trace+</span></div>
      <div class="panel-sidebar-sub">Farmer Portal</div>
    </div>
    
    <nav class="panel-nav">
      <div class="panel-nav-item active" data-section="dashboard" onclick="showPanel('farmer','dashboard')">
        <i class="bi bi-speedometer2"></i> Dashboard
      </div>
      <div class="panel-nav-item" data-section="farm" onclick="showPanel('farmer','farm')">
        <i class="bi bi-house-gear"></i> Farm Registration
      </div>
      <div class="panel-nav-item" data-section="livestock" onclick="showPanel('farmer','livestock')">
        <i class="bi bi-journal-check"></i> Livestock Monitoring
      </div>
      <div class="panel-nav-item" data-section="incidents" onclick="showPanel('farmer','incidents')">
        <i class="bi bi-exclamation-triangle"></i> Incident Reporting
      </div>
      <div class="panel-nav-item" data-section="notifications" onclick="showPanel('farmer','notifications')">
        <i class="bi bi-bell"></i> Notifications
      </div>
      <div class="panel-nav-item" data-section="map" onclick="showPanel('farmer','map')">
        <i class="bi bi-geo-alt"></i> Farm Map
      </div>
      <div class="panel-nav-item" data-section="profile" onclick="showPanel('farmer','profile')">
        <i class="bi bi-person-circle"></i> Profile
      </div>
      <div class="panel-nav-divider"></div>
      <div class="panel-nav-item logout" onclick="logout()">
        <i class="bi bi-power"></i> Logout
      </div>
    </nav>
    
    <div class="panel-sidebar-footer">
      <div class="panel-user-info">
        <div class="panel-avatar" id="farmer-avatar">JD</div>
        <div>
          <div class="panel-user-name" id="farmer-name"><?php echo $_SESSION['user_name'] ?? 'Juan dela Cruz'; ?></div>
          <div class="panel-user-role">Farmer</div>
        </div>
      </div>
    </div>
  </aside>

  <!-- Main Content -->
  <main class="panel-main">
    <!-- Topbar -->
    <div class="panel-topbar">
      <div style="display:flex; align-items:center; gap:12px;">
        <button class="mobile-sidebar-toggle" onclick="openPanelSidebar('farmer')">
          <i class="bi bi-list"></i>
        </button>
        <span class="panel-topbar-title" id="farmer-section-title">Dashboard</span>
      </div>
      <div class="topbar-right">
        <button class="topbar-notif" onclick="showNotifications('farmer')">
          <i class="bi bi-bell"></i>
          <span class="notif-dot"></span>
        </button>
        <div class="panel-user-info">
          <div class="panel-avatar" style="width:32px;height:32px;font-size:0.8rem;" id="topbar-avatar">JD</div>
        </div>
      </div>
    </div>

    <div class="panel-content">
      <!-- Dashboard Section (Active by default) -->
      <div class="panel-section active" id="farmer-dashboard">
        <div class="page-header-panel">
          <h2 id="farmer-greeting">Good morning, Juan! 👋</h2>
          <p>Here's an overview of your farm activities</p>
        </div>

        <!-- Stats Cards -->
        <div class="stat-cards">
          <div class="stat-card">
            <div class="stat-icon-wrap stat-icon-green">
              <i class="bi bi-database"></i>
            </div>
            <div>
              <div class="stat-num" id="farmer-total-livestock">24</div>
              <div class="stat-lbl">Total Livestock</div>
            </div>
          </div>
          <div class="stat-card">
            <div class="stat-icon-wrap stat-icon-amber">
              <i class="bi bi-exclamation-triangle"></i>
            </div>
            <div>
              <div class="stat-num" id="farmer-active-incidents">2</div>
              <div class="stat-lbl">Active Incidents</div>
            </div>
          </div>
          <div class="stat-card">
            <div class="stat-icon-wrap stat-icon-blue">
              <i class="bi bi-clipboard-check"></i>
            </div>
            <div>
              <div class="stat-num" id="farmer-pending-inspections">1</div>
              <div class="stat-lbl">Pending Inspections</div>
            </div>
          </div>
          <div class="stat-card">
            <div class="stat-icon-wrap stat-icon-green">
              <i class="bi bi-patch-check-fill"></i>
            </div>
            <div>
              <div class="stat-num" id="farmer-farm-status">Active</div>
              <div class="stat-lbl">Farm Status</div>
            </div>
          </div>
        </div>

        <!-- Dashboard Row -->
        <div class="dash-row">
          <!-- Livestock Chart -->
          <div class="dash-card">
            <div class="dash-card-header">
              <span class="dash-card-title">Livestock by Type</span>
            </div>
            <div class="dash-card-body">
              <div class="chart-container">
                <canvas id="farmer-livestock-chart"></canvas>
              </div>
            </div>
          </div>

          <!-- Notifications -->
          <div class="dash-card">
            <div class="dash-card-header">
              <span class="dash-card-title">Recent Notifications</span>
            </div>
            <div class="dash-card-body">
              <div style="display:flex;flex-direction:column;gap:10px;max-height:320px;overflow-y:auto;">
                <div class="notification-item" data-id="1">
                  <div style="display:flex;gap:12px;align-items:flex-start;padding:12px;background:var(--c-slate-50);border-radius:10px;border-left:3px solid var(--c-amber);">
                    <i class="bi bi-virus" style="color:var(--c-amber);font-size:1.1rem;margin-top:2px;flex-shrink:0;"></i>
                    <div>
                      <p style="margin:0;font-size:0.85rem;font-weight:600;">Disease Outbreak Alert</p>
                      <p style="margin:0;font-size:0.8rem;color:var(--c-slate-400);">Avian Flu in nearby areas</p>
                      <small style="color:var(--c-slate-500);">2 hours ago</small>
                    </div>
                  </div>
                </div>
                <div class="notification-item" data-id="2">
                  <div style="display:flex;gap:12px;align-items:flex-start;padding:12px;background:var(--c-slate-50);border-radius:10px;border-left:3px solid var(--c-blue);">
                    <i class="bi bi-syringe" style="color:var(--c-blue);font-size:1.1rem;margin-top:2px;flex-shrink:0;"></i>
                    <div>
                      <p style="margin:0;font-size:0.85rem;font-weight:600;">Vaccination Reminder</p>
                      <p style="margin:0;font-size:0.8rem;color:var(--c-slate-400);">Cattle vaccination due next week</p>
                      <small style="color:var(--c-slate-500);">1 day ago</small>
                    </div>
                  </div>
                </div>
                <div class="notification-item" data-id="3">
                  <div style="display:flex;gap:12px;align-items:flex-start;padding:12px;background:var(--c-slate-50);border-radius:10px;border-left:3px solid var(--c-emerald);">
                    <i class="bi bi-calendar-check" style="color:var(--c-emerald);font-size:1.1rem;margin-top:2px;flex-shrink:0;"></i>
                    <div>
                      <p style="margin:0;font-size:0.85rem;font-weight:600;">Inspection Scheduled</p>
                      <p style="margin:0;font-size:0.8rem;color:var(--c-slate-400);">Farm inspection on Mar 25, 2026</p>
                      <small style="color:var(--c-slate-500);">3 days ago</small>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Report Status -->
        <div class="dash-card">
          <div class="dash-card-header">
            <span class="dash-card-title">Report Status Updates</span>
          </div>
          <div class="dash-card-body">
            <div style="display:flex;flex-direction:column;gap:10px;">
              <div style="display:flex;gap:14px;align-items:flex-start;padding:14px;border:1.5px solid var(--c-slate-200);border-radius:10px;">
                <i class="bi bi-exclamation-triangle-fill" style="color:var(--c-red);font-size:1.3rem;margin-top:2px;flex-shrink:0;"></i>
                <div style="flex:1;">
                  <p style="margin:0;font-weight:600;font-size:0.9rem;">Disease Symptoms: Chicken showing flu-like symptoms</p>
                  <p style="margin:4px 0 0;font-size:0.8rem;">
                    <span class="badge badge-amber">Pending</span>
                  </p>
                </div>
                <button class="btn btn-outline btn-sm" onclick="viewReport('disease')">View</button>
              </div>
              <div style="display:flex;gap:14px;align-items:flex-start;padding:14px;border:1.5px solid var(--c-slate-200);border-radius:10px;">
                <i class="bi bi-exclamation-triangle-fill" style="color:var(--c-emerald);font-size:1.3rem;margin-top:2px;flex-shrink:0;"></i>
                <div style="flex:1;">
                  <p style="margin:0;font-weight:600;font-size:0.9rem;">Livestock Death: 1 pig died unexpectedly</p>
                  <p style="margin:4px 0 0;font-size:0.8rem;">
                    <span class="badge badge-green">Resolved</span>
                  </p>
                </div>
                <button class="btn btn-outline btn-sm" onclick="viewReport('death')">View</button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Farm Registration Section -->
      <div class="panel-section" id="farmer-farm">
        <div class="page-header-panel">
          <h2>Farm & Livestock Registration</h2>
          <p>Register or update your farm and livestock details</p>
        </div>
        <div class="dash-card">
          <div class="dash-card-header">
            <span class="dash-card-title">Farm Details</span>
          </div>
          <div class="dash-card-body">
            <form id="farm-registration-form">
              <div class="panel-form-row">
                <div class="panel-form-group">
                  <label class="panel-form-label">Farm Name</label>
                  <input type="text" class="form-input panel-input no-icon" name="farm_name" required>
                </div>
                <div class="panel-form-group">
                  <label class="panel-form-label">Farm Address</label>
                  <input type="text" class="form-input panel-input no-icon" name="farm_address" required>
                </div>
              </div>
              <!-- Additional farm fields... -->
              <button type="submit" class="btn btn-primary" style="width:100%;margin-top:20px;">
                Register / Update Farm
              </button>
            </form>
          </div>
        </div>
      </div>

      <!-- Other sections (livestock, incidents, etc.) follow similar pattern -->
      <!-- ... Additional sections truncated for brevity ... -->
    </div>
  </main>
</div>

<script>
let farmerCharts = {};

// Farmer Panel Initialization
async function initFarmerPanel() {
    await loadFarmerData();
    initFarmerCharts();
    updateSidebarUser('farmer');
    setupPanelNavigation('farmer');
}

async function loadFarmerData() {
    try {
        // Load stats
        const stats = await fetch('api.php/farmer/stats');
        const data = await stats.json();
        
        document.getElementById('farmer-total-livestock').textContent = data.total_livestock || 0;
        document.getElementById('farmer-active-incidents').textContent = data.active_incidents || 0;
        document.getElementById('farmer-pending-inspections').textContent = data.pending_inspections || 0;
        
        // Update greeting
        const now = new Date();
        const hour = now.getHours();
        const greeting = hour < 12 ? 'Good morning' : hour < 17 ? 'Good afternoon' : 'Good evening';
        document.getElementById('farmer-greeting').textContent = `${greeting}, ${sessionStorage.getItem('user_name') || 'Farmer'}! 👋`;
        
    } catch (error) {
        console.error('Failed to load farmer data:', error);
    }
}

function initFarmerCharts() {
    // Livestock by type chart
    const ctx = document.getElementById('farmer-livestock-chart');
    if (ctx && !farmerCharts.livestock) {
        farmerCharts.livestock = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Cattle', 'Swine', 'Poultry', 'Goat'],
                datasets: [{
                    data: [12, 6, 4, 2],
                    backgroundColor: ['#10b981', '#f59e0b', '#3b82f6', '#8b5cf6']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'bottom' } }
            }
        });
    }
}

function setupPanelNavigation(panel) {
    document.querySelectorAll(`#${panel}-sidebar .panel-nav-item`).forEach(item => {
        item.addEventListener('click', function() {
            // Update active nav
            document.querySelectorAll(`#${panel}-sidebar .panel-nav-item`).forEach(nav => nav.classList.remove('active'));
            this.classList.add('active');
            
            // Update title
            document.getElementById(`${panel}-section-title`).textContent = this.textContent.trim();
            
            // Show section
            document.querySelectorAll(`#${panel} .panel-section`).forEach(sec => sec.classList.remove('active'));
            document.getElementById(`${panel}-${this.dataset.section}`).classList.add('active');
        });
    });
}

function openPanelSidebar(panel) {
    document.getElementById(`${panel}-sidebar`).classList.add('open');
    document.getElementById(`${panel}-overlay`).classList.add('open');
}

function closePanelSidebar(panel) {
    document.getElementById(`${panel}-sidebar`).classList.remove('open');
    document.getElementById(`${panel}-overlay`).classList.remove('open');
}

function logout() {
    if (confirm('Are you sure you want to logout?')) {
        fetch('api.php/logout', { method: 'POST' })
            .then(() => {
                sessionStorage.clear();
                window.location.href = 'login.php';
            });
    }
}

function showNotifications(panel) {
    showToast('Notifications panel coming soon!');
}

// Initialize farmer panel
document.addEventListener('DOMContentLoaded', initFarmerPanel);
</script>

<?php require_once 'footer.php'; ?>