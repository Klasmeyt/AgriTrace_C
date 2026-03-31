// ======================================
// AGRI TRACE+ - COMPLETE JAVASCRIPT APP
// ======================================

// ===================== DATABASE API =====================
const DB = {
    async getAll(table) {
        try {
            const res = await fetch(`api.php/${table}`);
            if (!res.ok) throw new Error(`Failed to fetch ${table}`);
            return await res.json();
        } catch (err) {
            console.error(`DB.getAll(${table}) failed:`, err);
            return [];
        }
    },

    async getById(table, id) {
        try {
            const res = await fetch(`api.php/${table}/${id}`);
            if (!res.ok) throw new Error(`Failed to fetch ${table}/${id}`);
            return await res.json();
        } catch (err) {
            console.error(`DB.getById(${table}, ${id}) failed:`, err);
            return null;
        }
    },

    async insert(table, data) {
        try {
            const res = await fetch(`api.php/${table}`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            });
            if (!res.ok) throw new Error(`Failed to insert into ${table}`);
            return await res.json();
        } catch (err) {
            console.error(`DB.insert(${table}) failed:`, err);
            throw err;
        }
    },

    async update(table, id, data) {
        try {
            const res = await fetch(`api.php/${table}/${id}`, {
                method: 'PUT',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            });
            if (!res.ok) throw new Error(`Failed to update ${table}/${id}`);
            return await res.json();
        } catch (err) {
            console.error(`DB.update(${table}, ${id}) failed:`, err);
            throw err;
        }
    },

    async delete(table, id) {
        try {
            const res = await fetch(`api.php/${table}/${id}`, {
                method: 'DELETE'
            });
            if (!res.ok) throw new Error(`Failed to delete ${table}/${id}`);
            return await res.json();
        } catch (err) {
            console.error(`DB.delete(${table}, ${id}) failed:`, err);
            throw err;
        }
    },

    async query(table, fn) {
        const all = await this.getAll(table);
        return all.filter(fn);
    }
};

// ===================== SESSION MANAGEMENT =====================
const SESSION = {
    get user() {
        return {
            id: sessionStorage.getItem('user_id'),
            email: sessionStorage.getItem('user_email'),
            role: sessionStorage.getItem('user_role'),
            isLoggedIn: !!sessionStorage.getItem('user_id')
        };
    },

    set user(data) {
        sessionStorage.setItem('user_id', data.id);
        sessionStorage.setItem('user_email', data.email);
        sessionStorage.setItem('user_role', data.role);
    },

    logout() {
        sessionStorage.clear();
        navigate('login');
    },

    async checkSession() {
        if (this.user.isLoggedIn) {
            // Auto-redirect to appropriate panel
            if (this.user.role === 'Admin') navigate('admin-panel');
            else if (this.user.role === 'Agriculture Official') navigate('agri-panel');
            else navigate('farmer-panel');
        }
    }
};

// ===================== UTILITY FUNCTIONS =====================
function badge(status) {
    const badges = {
        'Active': 'badge-green',
        'Approved': 'badge-green',
        'Healthy': 'badge-green',
        'Resolved': 'badge-green',
        'Pending': 'badge-amber',
        'Investigating': 'badge-amber',
        'Under Review': 'badge-amber',
        'Rejected': 'badge-red',
        'Critical': 'badge-red',
        'Urgent': 'badge-red',
        'Failed': 'badge-red',
        'Blocked': 'badge-red',
        'Official': 'badge-purple',
        'Admin': 'badge-purple'
    };
    return `<span class="badge ${badges[status] || 'badge-gray'}">${status}</span>`;
}

function fmtDate(dateStr) {
    return new Date(dateStr).toLocaleDateString('en-US', {
        year: 'numeric', month: 'short', day: 'numeric'
    });
}

function showToast(message, isError = false) {
    const toast = document.getElementById('toast');
    const msgEl = document.getElementById('toast-msg');
    
    msgEl.textContent = message;
    toast.style.display = 'block';
    
    if (isError) {
        toast.querySelector('i').style.color = '#fca5a5';
        toast.querySelector('div').style.background = 'linear-gradient(135deg, #991b1b, #dc2626)';
    }
    
    setTimeout(() => {
        toast.style.display = 'none';
    }, 4000);
}

function navigate(page) {
    // Hide all pages
    document.querySelectorAll('.page').forEach(p => p.classList.remove('active'));
    
    // Show target page
    const target = document.getElementById(`page-${page}`);
    if (target) {
        target.classList.add('active');
        document.body.scrollTop = 0;
        document.documentElement.scrollTop = 0;
    }

    // Update navbar active states
    updateNavbar(page);
    
    // Initialize panel-specific content
    initPage(page);
}

function updateNavbar(page) {
    document.querySelectorAll('.navbar-links a, .mobile-menu a').forEach(a => {
        a.classList.remove('active');
        if (a.textContent.toLowerCase().includes(page)) a.classList.add('active');
    });
}

// ===================== PAGE INITIALIZATION =====================
async function initPage(page) {
    switch (page) {
        case 'farmer-panel':
            await initFarmerPanel();
            break;
        case 'agri-panel':
            await initAgriPanel();
            break;
        case 'admin-panel':
            await initAdminPanel();
            break;
        case 'farmer':
            await renderFarmerLivestock();
            await renderFarmerIncidents();
            break;
        case 'agri':
            await renderAgriFarms();
            break;
    }
}

// ===================== PANEL INITIALIZATION =====================
async function initFarmerPanel() {
    await refreshStats();
    await renderFarmerLivestock();
    await renderFarmerIncidents();
    updateSidebarUser('farmer');
    initFarmerCharts();
    document.getElementById('farmer-section-title').textContent = 'Dashboard';
}

async function initAgriPanel() {
    await refreshStats();
    await renderAgriFarms();
    updateSidebarUser('agri');
    initAgriCharts();
    document.getElementById('agri-section-title').textContent = 'Dashboard';
}

async function initAdminPanel() {
    await refreshStats();
    await renderAdminUsers();
    updateSidebarUser('admin');
    initAdminCharts();
    document.getElementById('admin-section-title').textContent = 'Dashboard';
}

// ===================== SIDEBAR FUNCTIONS =====================
function showPanel(panelType, section) {
    // Update sidebar active states
    document.querySelectorAll(`#${panelType}-sidebar .panel-nav-item`).forEach(item => {
        item.classList.remove('active');
    });
    event.target.closest('.panel-nav-item').classList.add('active');
    
    // Update section content
    document.querySelectorAll(`#${panelType}-panel .panel-section`).forEach(s => {
        s.classList.remove('active');
    });
    document.getElementById(`${panelType}-${section}`).classList.add('active');
    
    // Update topbar title
    const titles = {
        dashboard: 'Dashboard',
        farm: 'Farm Registration',
        livestock: 'Livestock Monitoring',
        incidents: 'Incident Reporting',
        notifications: 'Notifications',
        map: 'Farm Map',
        profile: 'Profile',
        farms: 'Farm Inspection',
        publicreports: 'Public Reports',
        reports: 'Reports & Analytics',
        users: 'User Management',
        roles: 'Role & Permissions',
        config: 'System Config',
        data: 'Data Management',
        geo: 'Geo-Mapping',
        audit: 'Audit & Security',
        analytics: 'Reports & Analytics'
    };
    
    document.getElementById(`${panelType}-section-title`).textContent = titles[section] || section;
}

function openPanelSidebar(panelType) {
    document.getElementById(`${panelType}-sidebar`).classList.add('open');
    document.getElementById(`${panelType}-overlay`).classList.add('open');
}

function closePanelSidebar(panelType) {
    document.getElementById(`${panelType}-sidebar`).classList.remove('open');
    document.getElementById(`${panelType}-overlay`).classList.remove('open');
}

function updateSidebarUser(panelType) {
    const userData = {
        farmer: { name: 'Juan dela Cruz', role: 'Farmer', avatar: 'JD' },
        agri: { name: 'Maria Reyes', role: 'Agriculture Official', avatar: 'MR' },
        admin: { name: 'System Admin', role: 'Administrator', avatar: 'AD' }
    };
    
    const data = userData[panelType];
    const sidebarUser = document.querySelector(`#${panelType}-sidebar .panel-user-name`);
    const sidebarRole = document.querySelector(`#${panelType}-sidebar .panel-user-role`);
    const sidebarAvatar = document.querySelector(`#${panelType}-sidebar .panel-avatar`);
    
    if (sidebarUser) sidebarUser.textContent = data.name;
    if (sidebarRole) sidebarRole.textContent = data.role;
    if (sidebarAvatar) sidebarAvatar.textContent = data.avatar;
}

// ===================== AUTH FUNCTIONS =====================
async function handleLogin(e) {
    e.preventDefault();
    const email = document.getElementById('login-email').value.trim().toLowerCase();
    const pw = document.getElementById('login-password').value;
    
    const errorEl = document.getElementById('login-error');
    
    try {
        const res = await fetch('api.php/login', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ email, password: pw })
        });
        
        const data = await res.json();
        
        if (!res.ok) {
            errorEl.innerHTML = `<i class="bi bi-x-circle-fill me-2"></i>${data.error}`;
            errorEl.classList.remove('hidden');
            return;
        }
        
        // Store session
        SESSION.user = data.user;
        
        // Navigate to appropriate panel
        if (data.user.role === 'Admin') {
            navigate('admin-panel');
        } else if (data.user.role === 'Agriculture Official') {
            navigate('agri-panel');
        } else {
            navigate('farmer-panel');
        }
        
    } catch (err) {
        showToast('Login failed. Please check your connection.', true);
    }
}

async function handleRegisterWithDB(e) {
    e.preventDefault();
    const formData = new FormData(e.target);
    const data = Object.fromEntries(formData);
    
    if (data['reg-password'] !== data['reg-confirm']) {
        showToast('Passwords do not match!', true);
        return;
    }
    
    const successEl = document.getElementById('reg-success');
    
    try {
        const res = await fetch('api.php/register', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                first_name: data.firstName || data['first_name'],
                last_name: data.lastName || data['last_name'],
                email: data.email.toLowerCase(),
                mobile: data.mobile,
                role: data.role,
                password: data['reg-password']
            })
        });
        
        const result = await res.json();
        
        if (!res.ok) {
            showToast(result.error, true);
            return;
        }
        
        successEl.innerHTML = '<i class="bi bi-check-circle-fill me-2"></i>Registration successful! You can now log in.';
        successEl.classList.remove('hidden');
        e.target.reset();
        setTimeout(() => navigate('login'), 2500);
        
    } catch (err) {
        showToast('Registration failed. Please try again.', true);
    }
}

async function handleForgot(e) {
    e.preventDefault();
    const email = document.getElementById('forgot-email').value;
    const mobile = document.getElementById('forgot-mobile').value;
    
    const successEl = document.getElementById('forgot-success');
    
    try {
        const res = await fetch('api.php/forgot-password', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ email: email || mobile })
        });
        
        const data = await res.json();
        
        if (!res.ok) throw new Error(data.error);
        
        successEl.innerHTML = `<i class="bi bi-check-circle-fill me-2"></i>${data.message}`;
        successEl.classList.remove('hidden');
        e.target.reset();
        
    } catch (err) {
        showToast(err.message, true);
    }
}

async function handleReset(e) {
    e.preventDefault();
    const pw = document.getElementById('reset-pw').value;
    const confirm = document.getElementById('reset-confirm').value;
    
    if (pw !== confirm) {
        showToast('Passwords do not match!', true);
        return;
    }
    
    try {
        const res = await fetch('api.php/reset-password', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ password: pw })
        });
        
        const data = await res.json();
        
        if (!res.ok) throw new Error(data.error);
        
        showToast('Password reset successful!');
        setTimeout(() => navigate('login'), 1500);
        
    } catch (err) {
        showToast(err.message, true);
    }
}

async function submitPublicReport(e) {
    e.preventDefault();
    const formData = new FormData(e.target);
    const data = Object.fromEntries(formData);
    
    const successEl = document.getElementById('report-success');
    
    try {
        const report = await DB.insert('public_reports', data);
        successEl.innerHTML = `<i class="bi bi-check-circle-fill me-2"></i>Report submitted! Reference: <strong>${report.ref_id}</strong>`;
        successEl.classList.remove('hidden');
        e.target.reset();
    } catch (err) {
        showToast('Report submission failed', true);
    }
}

// ===================== FORM HANDLERS =====================
function handlePanelForm(e, successMsg) {
    e.preventDefault();
    showToast(successMsg);
    e.target.reset();
}

async function addUser(e) {
    e.preventDefault();
    const formData = new FormData(e.target);
    const data = Object.fromEntries(formData);
    
    try {
        await DB.insert('users', data);
        showToast('User created successfully!');
        e.target.reset();
        renderAdminUsers();
    } catch (err) {
        showToast('Failed to create user', true);
    }
}

// ===================== PASSWORD FUNCTIONS =====================
function togglePw(inputId, btn) {
    const input = document.getElementById(inputId);
    const icon = btn.querySelector('i');
    
    if (input.type === 'password') {
        input.type = 'text';
        icon.className = 'bi bi-eye-slash';
    } else {
        input.type = 'password';
        icon.className = 'bi bi-eye';
    }
}

function checkPwStrength(pw) {
    const strength = {
        0: { text: 'Weak', fill: 'weak' },
        1: { text: 'Medium', fill: 'medium' },
        2: { text: 'Strong', fill: 'strong' }
    };
    
    let score = 0;
    if (pw.length >= 8) score++;
    if (/[0-9]/.test(pw)) score++;
    if (/[!@#$%^&*]/.test(pw)) score++;
    if (/[a-z]/.test(pw)) score++;
    if (/[A-Z]/.test(pw)) score++;
    
    const fill = document.getElementById('pw-fill');
    const text = document.getElementById('pw-text');
    const reqs = document.querySelectorAll('.pw-reqs li');
    
    fill.className = `pw-strength-fill ${strength[Math.min(score, 2)].fill}`;
    text.textContent = strength[Math.min(score, 2)].text;
    text.className = `pw-strength-text ${strength[Math.min(score, 2)].fill}`;
    
    // Update requirements
    reqs[0].classList.toggle('met', pw.length >= 8);
    reqs[1].classList.toggle('met', /[0-9]/.test(pw));
    reqs[2].classList.toggle('met', /[!@#$%^&*]/.test(pw));
    reqs[3].classList.toggle('met', /[a-z]/.test(pw));
    reqs[4].classList.toggle('met', /[A-Z]/.test(pw));
}

function handleTermsCheck(cb) {
    document.getElementById('reg-btn').disabled = !cb.checked;
}

// ===================== RENDER FUNCTIONS =====================
async function refreshStats() {
    try {
        const [livestock, incidents, farms, users] = await Promise.all([
            DB.getAll('livestock'),
            DB.getAll('incidents'),
            DB.getAll('farms'),
            DB.getAll('users')
        ]);
        
        const totalLivestock = livestock.reduce((sum, l) => sum + (parseInt(l.qty) || 0), 0);
        const activeIncidents = incidents.filter(i => i.status !== 'Resolved').length;
        
        // Update farmer dashboard
        updateStatCard('#farmer-dashboard .stat-num', [totalLivestock, activeIncidents, 1, 'Active']);
        // Update agri dashboard  
        updateStatCard('#agri-dashboard .stat-num', [47, 8, 5, 3]);
        // Update admin dashboard
        updateStatCard('#admin-dashboard .stat-num', [users.length, farms.length, totalLivestock, activeIncidents]);
        
    } catch (err) {
        console.error('Stats refresh failed:', err);
    }
}

function updateStatCard(selector, values) {
    const cards = document.querySelectorAll(selector);
    values.forEach((val, i) => {
        if (cards[i]) cards[i].textContent = val;
    });
}

async function renderFarmerLivestock(filter = '') {
    try {
        const tbody = document.querySelector('#farmer-livestock-table tbody');
        if (!tbody) return;
        
        const livestock = await DB.query('livestock', l => l.farm_id == SESSION.user.id);
        let rows =        let rows = livestock;
        
        if (filter) {
            rows = rows.filter(l => 
                JSON.stringify(l).toLowerCase().includes(filter.toLowerCase())
            );
        }
        
        if (!rows.length) {
            tbody.innerHTML = '<tr><td colspan="6" style="text-align:center;color:var(--c-slate-400);padding:24px;">No livestock records found.</td></tr>';
            return;
        }
        
        tbody.innerHTML = rows.map(l => `
            <tr>
                <td style="font-family:monospace;font-size:.82rem;">${l.tag_id || 'N/A'}</td>
                <td>${l.type || ''}</td>
                <td>${l.breed || ''}</td>
                <td>${l.age || ''} <span style="color:var(--c-slate-400);font-size:.8rem;">(${l.qty || 0} heads)</span></td>
                <td>${badge(l.health || 'Healthy')}</td>
                <td>
                    <div style="display:flex;gap:6px;">
                        <button class="btn btn-panel btn-sm" onclick="editLivestock(${l.id})">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <button class="btn btn-danger btn-sm" onclick="deleteLivestock(${l.id})">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `).join('');
    } catch (err) {
        console.error('Render livestock failed:', err);
        document.querySelector('#farmer-livestock-table tbody').innerHTML = 
            '<tr><td colspan="6" style="text-align:center;color:var(--c-red);">Failed to load livestock data</td></tr>';
    }
}

async function renderFarmerIncidents() {
    try {
        const tbody = document.querySelector('#farmer-incidents-table tbody');
        if (!tbody) return;
        
        const incidents = await DB.query('incidents', i => i.farmer_id == SESSION.user.id);
        
        if (!incidents.length) {
            tbody.innerHTML = '<tr><td colspan="6" style="text-align:center;color:var(--c-slate-400);padding:24px;">No incidents reported.</td></tr>';
            return;
        }
        
        tbody.innerHTML = incidents.map(i => `
            <tr>
                <td style="font-family:monospace;font-size:.8rem;">${i.ref_id}</td>
                <td style="white-space:nowrap;">${fmtDate(i.incident_date)}</td>
                <td>${i.type}</td>
                <td style="max-width:180px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">${i.description}</td>
                <td>${badge(i.status)}</td>
                <td><button class="btn btn-outline btn-sm" onclick="editIncident(${i.id})"><i class="bi bi-pencil"></i></button></td>
            </tr>
        `).join('');
    } catch (err) {
        console.error('Render incidents failed:', err);
    }
}

async function renderAgriFarms(filter = '') {
    try {
        const tbody = document.querySelector('#agri-farms-table tbody');
        if (!tbody) return;
        
        const farms = await DB.getAll('farms');
        let rows = farms;
        
        if (filter) {
            rows = rows.filter(f => 
                JSON.stringify(f).toLowerCase().includes(filter.toLowerCase())
            );
        }
        
        tbody.innerHTML = rows.map(f => `
            <tr>
                <td>${f.name}</td>
                <td>${f.owner}</td>
                <td>${f.type}</td>
                <td>${badge(f.status || 'Pending')}</td>
                <td>
                    <div style="display:flex;gap:6px;">
                        <button class="btn btn-panel btn-sm" onclick="inspectFarm(${f.id})">Inspect</button>
                        <button class="btn btn-outline btn-sm" onclick="approveFarm(${f.id}, 'Approved')">Approve</button>
                        <button class="btn btn-danger btn-sm" onclick="approveFarm(${f.id}, 'Rejected')">Reject</button>
                    </div>
                </td>
            </tr>
        `).join('');
    } catch (err) {
        console.error('Render farms failed:', err);
    }
}

async function renderAdminUsers(filter = '') {
    try {
        const tbody = document.querySelector('#admin-users-table tbody');
        if (!tbody) return;
        
        const users = await DB.getAll('users');
        let rows = users;
        
        if (filter) {
            rows = rows.filter(u => 
                JSON.stringify(u).toLowerCase().includes(filter.toLowerCase())
            );
        }
        
        tbody.innerHTML = rows.map(u => `
            <tr>
                <td>${u.first_name} ${u.last_name}</td>
                <td>${u.email}</td>
                <td>${badge(u.role)}</td>
                <td>${badge(u.status || 'Active')}</td>
                <td>${fmtDate(u.created_at)}</td>
                <td>
                    <div style="display:flex;gap:4px;">
                        <button class="btn btn-panel btn-sm" onclick="editUser(${u.id})">Edit</button>
                        <button class="btn btn-danger btn-sm" onclick="deleteUser(${u.id})">Del</button>
                    </div>
                </td>
            </tr>
        `).join('');
    } catch (err) {
        console.error('Render users failed:', err);
    }
}

// ===================== CRUD OPERATIONS =====================
async function editLivestock(id) {
    try {
        const livestock = await DB.getById('livestock', id);
        openRecordModal('livestock', livestock);
    } catch (err) {
        showToast('Failed to load livestock data', true);
    }
}

async function deleteLivestock(id) {
    if (!confirm('Delete this livestock record?')) return;
    
    try {
        await DB.delete('livestock', id);
        renderFarmerLivestock();
        refreshStats();
        showToast('Livestock record deleted');
    } catch (err) {
        showToast('Delete failed', true);
    }
}

async function approveFarm(id, status) {
    try {
        await DB.update('farms', id, { status });
        renderAgriFarms();
        refreshStats();
        showToast(`Farm ${status.toLowerCase()} successfully!`);
    } catch (err) {
        showToast('Update failed', true);
    }
}

async function editUser(id) {
    try {
        const user = await DB.getById('users', id);
        openRecordModal('users', user);
    } catch (err) {
        showToast('Failed to load user data', true);
    }
}

async function deleteUser(id) {
    if (!confirm('Delete this user account?')) return;
    
    try {
        await DB.delete('users', id);
        renderAdminUsers();
        showToast('User deleted successfully');
    } catch (err) {
        showToast('Delete failed', true);
    }
}

// ===================== MODAL FUNCTIONS =====================
function openProfileModal(panelType) {
    document.getElementById('profile-modal-title').textContent = 'Edit Profile';
    renderProfileModal(panelType);
    document.getElementById('profile-modal').classList.add('open');
}

function closeProfileModal() {
    document.getElementById('profile-modal').classList.remove('open');
}

function renderProfileModal(panelType) {
    const body = document.getElementById('profile-modal-body');
    const userData = {
        farmer: { name: 'Juan dela Cruz', email: 'juan@agritrace.ph', mobile: '+639123456789', role: 'Farmer' },
        agri: { name: 'Maria Reyes', email: 'maria@agritrace.ph', mobile: '+639987654321', role: 'Agriculture Official' },
        admin: { name: 'System Admin', email: 'admin@agritrace.ph', mobile: '+639000000000', role: 'Administrator' }
    };
    
    const data = userData[panelType];
    body.innerHTML = `
        <div class="panel-form-row">
            <div class="panel-form-group">
                <label class="panel-form-label">Full Name</label>
                <input type="text" class="form-input panel-input no-icon" value="${data.name}">
            </div>
            <div class="panel-form-group">
                <label class="panel-form-label">Email</label>
                <input type="email" class="form-input panel-input no-icon" value="${data.email}">
            </div>
        </div>
        <div class="panel-form-row">
            <div class="panel-form-group">
                <label class="panel-form-label">Mobile Number</label>
                <input type="tel" class="form-input panel-input no-icon" value="${data.mobile}">
            </div>
            <div class="panel-form-group">
                <label class="panel-form-label">Role</label>
                <select class="form-select panel-select" disabled>
                    <option>${data.role}</option>
                </select>
            </div>
        </div>
        <div style="margin-top:20px; padding-top:16px; border-top:1px solid var(--c-slate-200);">
            <h4 style="font-size:1rem; color:var(--c-forest); margin-bottom:12px;">Change Password</h4>
            <div class="panel-form-row">
                <div class="panel-form-group">
                    <label class="panel-form-label">Current Password</label>
                    <input type="password" class="form-input panel-input no-icon" placeholder="Current password">
                </div>
                <div class="panel-form-group">
                    <label class="panel-form-label">New Password</label>
                    <input type="password" class="form-input panel-input no-icon" placeholder="New password">
                </div>
            </div>
        </div>
    `;
}

function saveProfileModal() {
    showToast('Profile updated successfully!');
    closeProfileModal();
}

function openRecordModal(table, data) {
    document.getElementById('record-modal-title').textContent = `${table.charAt(0).toUpperCase() + table.slice(1)} Record`;
    document.getElementById('record-modal').classList.add('open');
    // Render form based on table and data
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.remove('open');
}

function openTermsModal() {
    document.getElementById('terms-modal').classList.add('open');
}

function closeTermsModal() {
    document.getElementById('terms-modal').classList.remove('open');
}

function confirmTerms() {
    document.getElementById('terms-check').checked = true;
    handleTermsCheck(document.getElementById('terms-check'));
    closeTermsModal();
    showToast('Terms accepted!');
}

// ===================== CHART FUNCTIONS =====================
function initFarmerCharts() {
    const ctx = document.getElementById('farmer-livestock-chart');
    if (ctx) {
        new Chart(ctx, {
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

function initAgriCharts() {
    // Initialize agriculture official charts
    ['agri-farm-types-chart', 'agri-livestock-pop-chart', 'agri-incidents-time-chart', 
     'agri-farm-status-chart', 'agri-public-reports-chart', 'agri-regional-chart'].forEach(id => {
        const ctx = document.getElementById(id);
        if (ctx) {
            new Chart(ctx.getContext('2d'), {
                type: 'bar',
                data: { labels: ['Jan', 'Feb', 'Mar'], datasets: [{ data: [10, 20, 30] }] },
                options: { responsive: true, maintainAspectRatio: false }
            });
        }
    });
}

function initAdminCharts() {
    // Initialize admin charts
    ['admin-activity-chart', 'admin-roles-chart', 'admin-roles-pie-chart', 'admin-farm-types-chart',
     'admin-reg-chart', 'admin-livestock-chart', 'admin-incident-status-chart', 
     'admin-system-activity-chart', 'admin-regional-chart', 'admin-farm-status-chart'].forEach(id => {
        const ctx = document.getElementById(id);
        if (ctx) {
            new Chart(ctx.getContext('2d'), {
                type: 'line',
                data: { labels: ['Jan', 'Feb', 'Mar'], datasets: [{ data: [10, 20, 15] }] },
                options: { responsive: true, maintainAspectRatio: false }
            });
        }
    });
}

// ===================== MAP FUNCTIONS =====================
function initMaps() {
    const maps = ['leaflet-map', 'agri-map', 'admin-map'];
    maps.forEach(mapId => {
        const mapEl = document.getElementById(mapId);
        if (mapEl && !mapEl._leaflet_id) {
            const map = L.map(mapId).setView([14.5995, 120.9842], 13);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);
            
            // Add sample markers
            L.marker([14.5995, 120.9842]).addTo(map)
                .bindPopup('Your Farm Location');
        }
    });
}

// ===================== UI INTERACTIONS =====================
function toggleMobileMenu() {
    const menu = document.getElementById('mobile-menu');
    menu.classList.toggle('open');
}

function closeMobileMenu() {
    document.getElementById('mobile-menu').classList.remove('open');
}

function toggleTrack() {
    const trackSection = document.getElementById('track-section');
    trackSection.classList.toggle('hidden');
}

function switchForgotMethod(method) {
    document.querySelectorAll('.method-tab').forEach(tab => tab.classList.remove('active'));
    event.target.classList.add('active');
    
    document.getElementById('forgot-email-group').classList.toggle('hidden', method !== 'email');
    document.getElementById('forgot-mobile-group').classList.toggle('hidden', method !== 'mobile');
    
    document.getElementById('forgot-btn-text').textContent = 
        method === 'email' ? 'SEND RESET LINK' : 'SEND OTP';
}

function togglePwReqs() {
    const reqs = document.getElementById('pw-reqs');
    reqs.style.display = reqs.style.display === 'none' ? 'block' : 'none';
}

function handleContact(e) {
    e.preventDefault();
    showToast('Message sent successfully! We\'ll get back to you soon.');
    e.target.reset();
}

// ===================== BULK OPERATIONS =====================
function toggleSelectAll(cb) {
    document.querySelectorAll('.row-cb').forEach(rowCb => {
        rowCb.checked = cb.checked;
    });
}

function bulkDelete() {
    const selected = document.querySelectorAll('.row-cb:checked');
    if (selected.length === 0) {
        showToast('No records selected', true);
        return;
    }
    
    if (confirm(`Delete ${selected.length} selected records?`)) {
        showToast(`${selected.length} records deleted`);
    }
}

function bulkArchive() {
    const selected = document.querySelectorAll('.row-cb:checked');
    if (selected.length === 0) {
        showToast('No records selected', true);
        return;
    }
    
    showToast(`${selected.length} records archived`);
}

// ===================== INITIALIZATION =====================
document.addEventListener('DOMContentLoaded', async function() {
    // Initialize navigation
    navigate('home');
    
    // Check session
    await SESSION.checkSession();
    
    // Initialize maps after a short delay
    setTimeout(initMaps, 500);
    
    // Event listeners
    document.getElementById('login-form')?.addEventListener('submit', handleLogin);
    document.getElementById('register-form')?.addEventListener('submit', handleRegisterWithDB);
    
    // Mobile menu toggle
    document.querySelector('.nav-hamburger')?.addEventListener('click', toggleMobileMenu);
    
    // Close modals on overlay click
    document.querySelectorAll('.modal-overlay').forEach(overlay => {
        overlay.addEventListener('click', e => {
            if (e.target === overlay) overlay.classList.remove('open');
        });
    });
    
    // File input styling
    document.querySelectorAll('input[type="file"]').forEach(input => {
        input.addEventListener('change', function() {
            const label = this.nextElementSibling || this.parentNode.querySelector('label');
            if (this.files.length) {
                label.textContent = `${this.files.length} file(s) selected`;
            } else {
                label.textContent = 'Choose file';
            }
        });
    });
});

// ===================== WINDOW EVENTS =====================
window.addEventListener('resize', function() {
    // Handle responsive layouts
    if (window.innerWidth > 768) {
        document.querySelectorAll('.panel-sidebar').forEach(sidebar => {
            sidebar.classList.remove('open');
        });
        document.querySelectorAll('.sidebar-overlay').forEach(overlay => {
            overlay.classList.remove('open');
        });
    }
});

// Export for external use
window.AgriTraceApp = {
    DB, SESSION, navigate, showToast, badge, fmtDate,
    initFarmerPanel, initAgriPanel, initAdminPanel,
    renderFarmerLivestock, renderFarmerIncidents,
    handleLogin, handleRegisterWithDB
};