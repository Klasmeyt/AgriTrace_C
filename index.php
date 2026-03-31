<?php require_once 'config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
  <meta name="theme-color" content="#064e3b">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
  <title>AgriTrace+ | Digital Livestock Registration System</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&family=Syne:wght@700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
  <style>
    /* ===================== CSS RESET & ROOT ===================== */
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    
    :root {
      --c-forest: #064e3b;
      --c-forest-deep: #042f24;
      --c-emerald: #10b981;
      --c-emerald-light: #34d399;
      --c-emerald-pale: #d1fae5;
      --c-emerald-mid: #059669;
      --c-blue: #3b82f6;
      --c-blue-dark: #2563eb;
      --c-amber: #f59e0b;
      --c-red: #ef4444;
      --c-white: #ffffff;
      --c-slate-50: #f8fafc;
      --c-slate-100: #f1f5f9;
      --c-slate-200: #e2e8f0;
      --c-slate-400: #94a3b8;
      --c-slate-600: #475569;
      --c-slate-800: #1e293b;
      --c-slate-900: #0f172a;
      --glass-bg: rgba(255,255,255,0.11);
      --glass-border: rgba(255,255,255,0.22);
      --glass-blur: blur(28px);
      --radius-sm: 10px;
      --radius-md: 16px;
      --radius-lg: 24px;
      --shadow-card: 0 4px 24px rgba(0,0,0,0.08);
      --shadow-float: 0 8px 32px rgba(0,0,0,0.14);
      --transition: all 0.28s cubic-bezier(0.4,0,0.2,1);
      --sidebar-w: 270px;
    }

    html { scroll-behavior: smooth; -webkit-text-size-adjust: 100%; text-size-adjust: 100%; }
    body { 
      font-family: 'DM Sans', sans-serif; 
      color: var(--c-slate-800); 
      background: var(--c-slate-50);
      min-height: 100vh;
      overflow-x: hidden;
      -webkit-font-smoothing: antialiased;
      -moz-osx-font-smoothing: grayscale;
      text-rendering: optimizeLegibility;
    }
    * { font-stretch: normal !important; }
    h1,h2,h3,h4,h5,h6 { word-break: normal; overflow-wrap: normal; }
    .panel-topbar-title, .page-header-panel h2, .dash-card-title { white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

    /* ===================== UTILITY ===================== */
    .hidden { display: none !important; }
    .page { display: none; }
    .page.active { display: flex; flex-direction: column; min-height: 100vh; }
    .page.panel-page { display: none; flex-direction: row; }
    .page.panel-page.active { display: flex; }

    /* ===================== BG HERO ===================== */
    .hero-bg {
      position: fixed; inset: 0; z-index: 0;
      background: url('https://images.unsplash.com/photo-1500382017468-9049fed747ef?auto=format&fit=crop&q=80&w=1600') center/cover no-repeat;
    }
    .hero-bg::after {
      content: '';
      position: absolute; inset: 0;
      background: linear-gradient(135deg, rgba(6,78,59,0.72) 0%, rgba(4,23,14,0.88) 100%);
    }

    /* ===================== NAVBAR ===================== */
    .navbar {
      position: fixed; top: 0; left: 0; width: 100%; z-index: 900;
      background: rgba(255,255,255,0.08);
      backdrop-filter: blur(20px);
      -webkit-backdrop-filter: blur(20px);
      border-bottom: 1px solid rgba(255,255,255,0.15);
      padding: 0 clamp(20px, 5vw, 80px);
      height: 68px;
      display: flex; align-items: center; justify-content: space-between;
    }
    .navbar-logo {
      font-family: 'Syne', sans-serif;
      font-size: 1.5rem; font-weight: 800;
      color: #fff; text-decoration: none;
      display: flex; align-items: center; gap: 10px;
      cursor: pointer;
    }
    .navbar-logo .leaf { color: var(--c-emerald); font-size: 1.3em; }
    .navbar-logo span { color: var(--c-emerald); }
    .navbar-links { display: flex; align-items: center; gap: 4px; list-style: none; }
    .navbar-links a {
      color: rgba(255,255,255,0.85); text-decoration: none;
      padding: 8px 16px; border-radius: 8px;
      font-weight: 500; font-size: 0.95rem;
      transition: var(--transition);
    }
    .navbar-links a:hover { background: rgba(16,185,129,0.18); color: #fff; }
    .navbar-links .btn-nav {
      background: linear-gradient(135deg, var(--c-emerald), var(--c-emerald-mid));
      color: #fff !important; font-weight: 600;
      padding: 9px 24px; border-radius: 10px;
      box-shadow: 0 4px 14px rgba(16,185,129,0.35);
    }
    .navbar-links .btn-nav:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(16,185,129,0.5); }
    .nav-hamburger {
      display: none; background: none; border: none;
      color: #fff; font-size: 1.6rem; cursor: pointer; padding: 4px;
    }
    .mobile-menu {
      display: none; position: fixed; top: 68px; left: 0; width: 100%;
      background: rgba(6,78,59,0.97); backdrop-filter: blur(20px);
      border-bottom: 1px solid rgba(255,255,255,0.12);
      z-index: 899; padding: 16px 0; flex-direction: column;
    }
    .mobile-menu.open { display: flex; }
    .mobile-menu a {
      color: rgba(255,255,255,0.9); text-decoration: none;
      padding: 14px 28px; font-weight: 500; font-size: 1rem;
      border-bottom: 1px solid rgba(255,255,255,0.07);
      transition: var(--transition);
    }
    .mobile-menu a:hover { background: rgba(16,185,129,0.2); color: #fff; padding-left: 38px; }

    /* ===================== HERO PAGE ===================== */
    #page-home {
      position: relative; z-index: 1;
      justify-content: center; align-items: center;
      padding-top: 68px; min-height: 100vh;
    }
    .hero-content {
      position: relative; z-index: 2; text-align: center;
      color: #fff; padding: clamp(40px, 8vw, 80px) 20px;
      max-width: 820px; margin: auto; animation: fadeUp 0.9s ease both;
    }
    .hero-badge {
      display: inline-flex; align-items: center; gap: 8px;
      background: rgba(59,130,246,0.25); border: 1px solid rgba(59,130,246,0.4);
      color: #93c5fd; border-radius: 100px; padding: 7px 18px;
      font-size: 0.8rem; font-weight: 600; letter-spacing: 0.08em;
      text-transform: uppercase; margin-bottom: 28px;
      backdrop-filter: blur(10px);
    }
    .hero-content h1 {
      font-family: 'Syne', sans-serif;
      font-size: clamp(2.4rem, 7vw, 4.2rem); font-weight: 800;
      line-height: 1.1; margin-bottom: 20px;
      text-shadow: 0 4px 24px rgba(0,0,0,0.3);
    }
    .hero-content h1 .hl {
      background: linear-gradient(135deg, var(--c-emerald), var(--c-emerald-light));
      -webkit-background-clip: text; -webkit-text-fill-color: transparent;
      background-clip: text;
    }
    .hero-content p {
      font-size: clamp(1rem, 2.5vw, 1.2rem);
      opacity: 0.9; line-height: 1.7; margin-bottom: 42px;
    }
    .cta-group { display: flex; gap: 14px; justify-content: center; flex-wrap: wrap; }
    .btn-hero-primary {
      display: inline-flex; align-items: center; gap: 10px;
      background: linear-gradient(135deg, var(--c-emerald), var(--c-emerald-mid));
      color: #fff; font-weight: 700; font-size: 1rem;
      padding: 15px 36px; border-radius: 12px; text-decoration: none;
      box-shadow: 0 6px 28px rgba(16,185,129,0.45);
      transition: var(--transition); cursor: pointer; border: none;
    }
    .btn-hero-primary:hover { transform: translateY(-3px); box-shadow: 0 10px 36px rgba(16,185,129,0.6); color: #fff; }
    .btn-hero-secondary {
      display: inline-flex; align-items: center; gap: 10px;
      background: rgba(255,255,255,0.12); border: 1.5px solid rgba(255,255,255,0.3);
      backdrop-filter: blur(12px); color: #fff;
      font-weight: 600; font-size: 1rem;
      padding: 15px 36px; border-radius: 12px; text-decoration: none;
      transition: var(--transition); cursor: pointer;
    }
    .btn-hero-secondary:hover { background: rgba(255,255,255,0.2); transform: translateY(-3px); color: #fff; }

    .hero-feature-strip {
      position: absolute; bottom: 40px; left: 50%; transform: translateX(-50%);
      display: flex; gap: 20px; z-index: 2;
    }
    .hf-card {
      background: rgba(255,255,255,0.1); backdrop-filter: blur(16px);
      border: 1px solid rgba(255,255,255,0.18); border-radius: 14px;
      padding: 18px 22px; color: #fff; text-align: center; min-width: 120px;
      transition: var(--transition);
    }
    .hf-card:hover { background: rgba(255,255,255,0.18); transform: translateY(-4px); }
    .hf-card i { display: block; font-size: 1.8rem; color: var(--c-emerald); margin-bottom: 8px; }
    .hf-card h5 { font-size: 0.9rem; font-weight: 600; margin-bottom: 3px; }
    .hf-card p { font-size: 0.75rem; opacity: 0.75; margin: 0; }

    /* ===================== AUTH PAGES ===================== */
    .auth-page {
      position: relative; z-index: 1;
      justify-content: center; align-items: center;
      padding: 90px 16px 40px;
    }
    .auth-card {
      background: var(--glass-bg); backdrop-filter: var(--glass-blur);
      -webkit-backdrop-filter: var(--glass-blur);
      border: 1px solid var(--glass-border); border-radius: var(--radius-lg);
      padding: clamp(30px, 5vw, 52px) clamp(24px, 4vw, 48px);
      width: 100%; max-width: 480px;
      box-shadow: 0 16px 48px rgba(0,0,0,0.4);
      color: #fff; position: relative;
      animation: slideUp 0.55s cubic-bezier(0.4,0,0.2,1) both;
    }
    .auth-logo {
      font-family: 'Syne', sans-serif; font-size: 2rem; font-weight: 800;
      margin-bottom: 6px; text-align: center;
    }
    .auth-logo span { color: var(--c-emerald); }
    .geo-badge {
      display: inline-flex; align-items: center; gap: 6px;
      background: linear-gradient(135deg, var(--c-blue), var(--c-blue-dark));
      color: #fff; font-size: 0.72rem; font-weight: 700; letter-spacing: 0.07em;
      padding: 7px 16px; border-radius: 100px;
      box-shadow: 0 4px 14px rgba(59,130,246,0.32);
      text-transform: uppercase; margin-bottom: 10px;
    }
    .auth-subtitle {
      font-size: 0.88rem; opacity: 0.82; text-align: center;
      line-height: 1.55; margin-bottom: 32px;
    }
    .auth-close {
      position: absolute; top: 18px; right: 20px;
      width: 36px; height: 36px; border-radius: 50%;
      background: rgba(255,255,255,0.1); border: none;
      color: rgba(255,255,255,0.7); cursor: pointer; font-size: 1.1rem;
      display: flex; align-items: center; justify-content: center;
      transition: var(--transition);
    }
    .auth-close:hover { background: rgba(255,255,255,0.2); color: #fff; transform: rotate(90deg); }

    /* ===================== FORM ELEMENTS ===================== */
    .form-group { margin-bottom: 18px; }
    .form-label { display: block; font-size: 0.82rem; font-weight: 600; margin-bottom: 7px; opacity: 0.9; }
    .input-wrap { position: relative; display: flex; align-items: stretch; }
    .input-icon {
      position: absolute; left: 14px; top: 50%; transform: translateY(-50%);
      color: var(--c-slate-400); font-size: 1.1rem; pointer-events: none; z-index: 2;
    }
    .form-input {
      width: 100%; padding: 13px 16px 13px 42px;
      background: rgba(255,255,255,0.96);
      border: 2px solid transparent; border-radius: var(--radius-sm);
      font-family: 'DM Sans', sans-serif; font-size: 0.92rem; color: var(--c-slate-800);
      transition: var(--transition); outline: none;
    }
    .form-input:focus {
      background: #fff; border-color: var(--c-emerald);
      box-shadow: 0 0 0 4px rgba(16,185,129,0.13);
    }
    .form-input.no-icon { padding-left: 16px; }
    .form-input.panel-input {
      background: #fff; border: 2px solid var(--c-slate-200);
      color: var(--c-slate-800);
    }
    .form-input.panel-input:focus { border-color: var(--c-emerald); box-shadow: 0 0 0 4px rgba(16,185,129,0.1); }
    .form-select {
      width: 100%; padding: 12px 16px;
      background: rgba(255,255,255,0.96);
      border: 2px solid transparent; border-radius: var(--radius-sm);
      font-family: 'DM Sans', sans-serif; font-size: 0.92rem; color: var(--c-slate-800);
      cursor: pointer; outline: none; transition: var(--transition);
    }
    .form-select:focus { border-color: var(--c-emerald); box-shadow: 0 0 0 4px rgba(16,185,129,0.13); }
    .form-select.panel-select {
      background: #fff; border: 2px solid var(--c-slate-200);
    }
    .form-select.panel-select:focus { border-color: var(--c-emerald); }
    textarea.form-input { resize: vertical; padding-left: 16px; min-height: 100px; }

    .toggle-pass {
      position: absolute; right: 12px; top: 50%; transform: translateY(-50%);
      background: none; border: none; color: var(--c-slate-400);
      cursor: pointer; font-size: 1.1rem; padding: 4px; z-index: 2;
      transition: var(--transition);
    }
    .toggle-pass:hover { color: var(--c-slate-600); }

    /* ===================== BUTTONS ===================== */
    .btn {
      display: inline-flex; align-items: center; justify-content: center; gap: 8px;
      font-family: 'DM Sans', sans-serif; font-weight: 700;
      border: none; cursor: pointer; transition: var(--transition);
      text-decoration: none; white-space: nowrap;
    }
    .btn:disabled { opacity: 0.55; cursor: not-allowed; transform: none !important; }
    .btn-primary {
      background: linear-gradient(135deg, var(--c-emerald), var(--c-emerald-mid));
      color: #fff; padding: 14px 20px; border-radius: var(--radius-sm);
      box-shadow: 0 6px 20px rgba(16,185,129,0.35); font-size: 1rem;
      width: 100%;
    }
    .btn-primary:hover:not(:disabled) { transform: translateY(-2px); box-shadow: 0 8px 28px rgba(16,185,129,0.5); }
    .btn-secondary {
      background: rgba(255,255,255,0.14); border: 1.5px solid rgba(255,255,255,0.28);
      color: #fff; padding: 12px 20px; border-radius: var(--radius-sm); font-size: 0.95rem;
      width: 100%; margin-top: 12px;
    }
    .btn-secondary:hover { background: rgba(255,255,255,0.22); }
    .btn-panel {
      background: linear-gradient(135deg, var(--c-emerald), var(--c-emerald-mid));
      color: #fff; padding: 10px 22px; border-radius: var(--radius-sm);
      box-shadow: 0 4px 14px rgba(16,185,129,0.28); font-size: 0.9rem;
    }
    .btn-panel:hover { transform: translateY(-2px); box-shadow: 0 6px 18px rgba(16,185,129,0.42); }
    .btn-danger { background: linear-gradient(135deg, #ef4444, #dc2626); color: #fff; padding: 10px 22px; border-radius: var(--radius-sm); }
    .btn-outline {
      background: transparent; border: 1.5px solid var(--c-slate-200);
      color: var(--c-slate-600); padding: 10px 22px; border-radius: var(--radius-sm);
      font-size: 0.9rem;
    }
    .btn-outline:hover { border-color: var(--c-emerald); color: var(--c-emerald); }
    .btn-sm { padding: 7px 14px; font-size: 0.82rem; border-radius: 8px; }
    .btn-icon { width: 36px; height: 36px; padding: 0; border-radius: 8px; }

    /* ===================== AUTH LINKS ===================== */
    .auth-links {
      display: flex; justify-content: space-between; align-items: center;
      margin: 14px 0;
    }
    .auth-links a {
      color: rgba(255,255,255,0.8); text-decoration: none; font-size: 0.87rem;
      transition: var(--transition);
    }
    .auth-links a:hover { color: var(--c-emerald); }
    .auth-footer { text-align: center; margin-top: 40px; font-size: 0.68rem; opacity: 0.5; }

    /* ===================== METHOD TABS ===================== */
    .method-tabs { display: flex; gap: 10px; margin-bottom: 24px; }
    .method-tab {
      flex: 1; background: rgba(255,255,255,0.1); border: 1.5px solid rgba(255,255,255,0.25);
      border-radius: var(--radius-sm); padding: 12px; color: rgba(255,255,255,0.75);
      font-weight: 600; cursor: pointer; transition: var(--transition); font-size: 0.88rem;
      display: flex; align-items: center; justify-content: center; gap: 8px;
    }
    .method-tab:hover { background: rgba(255,255,255,0.16); }
    .method-tab.active {
      background: linear-gradient(135deg, var(--c-emerald), var(--c-emerald-mid));
      border-color: var(--c-emerald); color: #fff;
      box-shadow: 0 4px 14px rgba(16,185,129,0.38);
    }

    /* ===================== ALERTS ===================== */
    .alert {
      padding: 12px 16px; border-radius: var(--radius-sm); margin-bottom: 18px;
      font-size: 0.88rem; font-weight: 500; display: flex; align-items: center; gap: 10px;
      animation: fadeIn 0.3s ease;
    }
    .alert-success { background: rgba(16,185,129,0.18); border: 1px solid rgba(16,185,129,0.4); color: #a7f3d0; }
    .alert-danger { background: rgba(239,68,68,0.18); border: 1px solid rgba(239,68,68,0.4); color: #fca5a5; }
    .alert-panel-success { background: var(--c-emerald-pale); border: 1px solid #6ee7b7; color: #065f46; }
    .alert-panel-danger { background: #fee2e2; border: 1px solid #fca5a5; color: #991b1b; }

    /* ===================== PASSWORD STRENGTH ===================== */
    .pw-strength-bar { height: 5px; background: var(--c-slate-200); border-radius: 99px; margin: 8px 0 4px; overflow: hidden; }
    .pw-strength-fill { height: 100%; border-radius: 99px; transition: var(--transition); width: 0; }
    .pw-strength-fill.weak { width: 33%; background: var(--c-red); }
    .pw-strength-fill.medium { width: 66%; background: var(--c-amber); }
    .pw-strength-fill.strong { width: 100%; background: var(--c-emerald); }
    .pw-strength-text { font-size: 0.75rem; opacity: 0.8; }
    .pw-strength-text.weak { color: #fca5a5; }
    .pw-strength-text.medium { color: #fcd34d; }
    .pw-strength-text.strong { color: var(--c-emerald-light); }
    .pw-reqs { list-style: none; margin-top: 10px; }
    .pw-reqs li { font-size: 0.78rem; padding: 3px 0; opacity: 0.75; display: flex; align-items: center; gap: 8px; }
    .pw-reqs li.met { color: var(--c-emerald-light); opacity: 1; }
    .pw-reqs li.met i::before { content: "\f26b"; } /* bi-check-circle-fill */

    /* ===================== CHECKBOX ===================== */
    .check-group { display: flex; align-items: flex-start; gap: 12px; }
    .check-group input[type="checkbox"] { 
      width: 18px; height: 18px; accent-color: var(--c-emerald); 
      cursor: pointer; flex-shrink: 0; margin-top: 2px;
    }
    .check-group label { font-size: 0.85rem; opacity: 0.9; cursor: pointer; line-height: 1.5; }
    .check-group a { color: var(--c-emerald); text-decoration: underline; }

    /* ===================== REGISTER ===================== */
    #page-register .auth-card { max-width: 520px; max-height: 90vh; overflow-y: auto; }
    #page-register .auth-card::-webkit-scrollbar { width: 6px; }
    #page-register .auth-card::-webkit-scrollbar-track { background: rgba(255,255,255,0.05); }
    #page-register .auth-card::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.2); border-radius: 99px; }

    /* ===================== MODAL ===================== */
    .modal-overlay {
      position: fixed; inset: 0; background: rgba(0,0,0,0.55);
      backdrop-filter: blur(6px); z-index: 2000;
      display: none; align-items: center; justify-content: center; padding: 16px;
    }
    .modal-overlay.open { display: flex; }
    .modal-box {
      background: #fff; border-radius: var(--radius-lg); width: 100%; max-width: 500px;
      max-height: 85vh; overflow-y: auto; padding: 32px;
      animation: scaleIn 0.3s cubic-bezier(0.4,0,0.2,1) both;
      position: relative;
    }
    .modal-box h3 { font-family: 'Syne', sans-serif; font-size: 1.4rem; margin-bottom: 16px; color: var(--c-forest); }
    .modal-close {
      position: absolute; top: 16px; right: 16px; background: var(--c-slate-100);
      border: none; border-radius: 50%; width: 32px; height: 32px; cursor: pointer;
      display: flex; align-items: center; justify-content: center; transition: var(--transition);
    }
    .modal-close:hover { background: var(--c-slate-200); transform: rotate(90deg); }
    .modal-terms p { font-size: 0.88rem; color: var(--c-slate-600); line-height: 1.7; margin-bottom: 10px; }
    .modal-terms p strong { color: var(--c-forest); }
    .modal-footer { margin-top: 24px; padding-top: 16px; border-top: 1px solid var(--c-slate-200); }
    .modal-footer .check-group { margin-bottom: 16px; }
    .modal-footer .check-group label { color: var(--c-slate-700); opacity: 1; }
    .btn-modal-confirm {
      background: linear-gradient(135deg, var(--c-emerald), var(--c-emerald-mid));
      color: #fff; padding: 12px 28px; border-radius: var(--radius-sm);
      font-weight: 700; font-size: 0.95rem; border: none; cursor: pointer;
      width: 100%; transition: var(--transition);
    }
    .btn-modal-confirm:disabled { opacity: 0.5; cursor: not-allowed; }
    .btn-modal-confirm:hover:not(:disabled) { transform: translateY(-2px); }

    /* ===================== RADIO BUTTONS ===================== */
    .radio-group { display: flex; flex-direction: column; gap: 10px; }
    .radio-item {
      display: flex; align-items: center; gap: 12px;
      padding: 10px 14px; background: rgba(255,255,255,0.08);
      border: 1.5px solid rgba(255,255,255,0.15); border-radius: var(--radius-sm);
      cursor: pointer; transition: var(--transition);
    }
    .radio-item:hover { background: rgba(16,185,129,0.12); border-color: rgba(16,185,129,0.35); }
    .radio-item input[type="radio"] { accent-color: var(--c-emerald); width: 17px; height: 17px; cursor: pointer; }
    .radio-item label { cursor: pointer; font-size: 0.9rem; margin: 0; }

    /* ===================== ABOUT / CONTACT ===================== */
    .content-page-wrap {
      position: relative; z-index: 1; padding: 88px 0 60px;
    }
    .container { max-width: 900px; margin: auto; padding: 0 clamp(16px, 4vw, 40px); }
    .container-wide { max-width: 1100px; margin: auto; padding: 0 clamp(16px, 3vw, 40px); }
    .content-header { text-align: center; color: #fff; margin-bottom: 40px; }
    .content-header h1 { font-family: 'Syne', sans-serif; font-size: clamp(1.8rem, 5vw, 2.8rem); font-weight: 800; margin-bottom: 10px; }
    .content-header p { opacity: 0.85; font-size: 1rem; }
    .glass-card {
      background: var(--glass-bg); backdrop-filter: var(--glass-blur);
      -webkit-backdrop-filter: var(--glass-blur);
      border: 1px solid var(--glass-border); border-radius: var(--radius-lg);
      padding: 32px; color: #fff; margin-bottom: 24px;
      animation: fadeUp 0.5s ease both;
    }
    .glass-card h2 { 
      font-family: 'Syne', sans-serif; font-size: 1.4rem; font-weight: 700;
      color: var(--c-emerald-light); margin-bottom: 16px;
      display: flex; align-items: center; gap: 10px;
    }
    .glass-card p { font-size: 0.92rem; line-height: 1.75; opacity: 0.9; margin-bottom: 10px; }
    .glass-card ul { list-style: none; padding: 0; }
    .glass-card ul li { 
      padding: 5px 0 5px 28px; position: relative;
      font-size: 0.9rem; opacity: 0.9; line-height: 1.5;
    }
    .glass-card ul li::before { 
      content: ''; position: absolute; left: 0; top: 13px;
      width: 12px; height: 12px; background: var(--c-emerald);
      border-radius: 50%; opacity: 0.8;
    }
    .feature-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 14px; margin-top: 18px; }
    .feat-box {
      background: rgba(255,255,255,0.07); border: 1px solid rgba(255,255,255,0.15);
      border-radius: var(--radius-md); padding: 20px 16px; text-align: center;
      transition: var(--transition);
    }
    .feat-box:hover { background: rgba(255,255,255,0.13); transform: translateY(-3px); }
    .feat-box i { display: block; font-size: 2rem; color: var(--c-emerald); margin-bottom: 10px; }
    .feat-box h4 { font-size: 0.9rem; font-weight: 600; margin-bottom: 4px; }
    .feat-box p { font-size: 0.78rem; opacity: 0.75; margin: 0; }
    .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 14px; margin-top: 18px; }
    .stat-box {
      background: rgba(16,185,129,0.12); border: 1px solid rgba(16,185,129,0.25);
      border-radius: var(--radius-md); padding: 22px; text-align: center;
    }
    .stat-box .num { font-family: 'Syne', sans-serif; font-size: 2rem; font-weight: 800; color: var(--c-emerald-light); display: block; }
    .stat-box .lbl { font-size: 0.8rem; opacity: 0.8; }
    .cta-section {
      background: linear-gradient(135deg, rgba(16,185,129,0.2), rgba(5,150,105,0.3));
      border: 1px solid rgba(16,185,129,0.35); border-radius: var(--radius-lg);
      padding: 40px; text-align: center; color: #fff; margin-top: 24px;
    }
    .cta-section h2 { font-family: 'Syne', sans-serif; font-size: 1.7rem; margin-bottom: 10px; }
    .cta-section p { opacity: 0.85; margin-bottom: 24px; }
    .cta-btns { display: flex; gap: 12px; justify-content: center; flex-wrap: wrap; }
    .btn-cta {
      display: inline-flex; align-items: center; gap: 8px;
      background: linear-gradient(135deg, var(--c-emerald), var(--c-emerald-mid));
      color: #fff; padding: 12px 26px; border-radius: var(--radius-sm);
      font-weight: 700; font-size: 0.92rem; text-decoration: none;
      box-shadow: 0 4px 16px rgba(16,185,129,0.3); transition: var(--transition);
      cursor: pointer; border: none;
    }
    .btn-cta:hover { transform: translateY(-2px); box-shadow: 0 6px 22px rgba(16,185,129,0.5); color: #fff; }
    .btn-cta-outline {
      background: transparent; border: 1.5px solid rgba(255,255,255,0.35); color: #fff;
      padding: 12px 26px; border-radius: var(--radius-sm); font-weight: 600; font-size: 0.92rem;
      text-decoration: none; display: inline-flex; align-items: center; gap: 8px;
      transition: var(--transition); cursor: pointer;
    }
    .btn-cta-outline:hover { background: rgba(255,255,255,0.15); color: #fff; }
    
    /* Contact form */
    .contact-info-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 14px; margin-bottom: 24px; }
    .contact-card {
      background: rgba(255,255,255,0.07); border: 1px solid rgba(255,255,255,0.15);
      border-radius: var(--radius-md); padding: 20px; text-align: center;
      transition: var(--transition);
    }
    .contact-card:hover { background: rgba(255,255,255,0.13); }
    .contact-card i { font-size: 1.8rem; color: var(--c-emerald); display: block; margin-bottom: 8px; }
    .contact-card h5 { font-size: 0.88rem; font-weight: 600; margin-bottom: 4px; }
    .contact-card p { font-size: 0.8rem; opacity: 0.75; margin: 0; }

    /* ===================== PUBLIC REPORT ===================== */
    #page-public-report .auth-card { max-width: 640px; max-height: 90vh; overflow-y: auto; }
    #page-public-report .auth-card::-webkit-scrollbar { width: 6px; }
    #page-public-report .auth-card::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.2); border-radius: 99px; }
    .file-btn-wrap { display: flex; align-items: center; gap: 8px; }
    .file-btn-wrap .form-input { flex: 1; }
    .btn-camera {
      background: linear-gradient(135deg, var(--c-blue), var(--c-blue-dark));
      color: #fff; padding: 12px 16px; border-radius: var(--radius-sm);
      border: none; cursor: pointer; font-size: 0.88rem; font-weight: 600;
      display: flex; align-items: center; gap: 6px; white-space: nowrap;
      box-shadow: 0 4px 12px rgba(59,130,246,0.3); transition: var(--transition);
    }
    .btn-camera:hover { transform: translateY(-2px); box-shadow: 0 6px 16px rgba(59,130,246,0.45); }
    .track-section {
      margin-top: 20px; padding: 20px;
      background: rgba(255,255,255,0.07); border-radius: var(--radius-md);
      border: 1px solid rgba(255,255,255,0.12);
    }
    .track-row { display: flex; gap: 10px; }
    .track-row .form-input { flex: 1; }
    .ref-chip {
      display: inline-flex; align-items: center; gap: 8px;
      background: rgba(16,185,129,0.2); border: 1px solid rgba(16,185,129,0.4);
      padding: 6px 14px; border-radius: 100px; font-size: 0.82rem;
      color: var(--c-emerald-light); font-weight: 600; margin-left: 8px;
    }

    /* ===================== PANEL LAYOUT ===================== */
    .panel-sidebar {
      width: var(--sidebar-w); height: 100vh;
      background: linear-gradient(180deg, var(--c-forest) 0%, var(--c-forest-deep) 100%);
      position: fixed; left: 0; top: 0;
      display: flex; flex-direction: column;
      z-index: 800; transition: transform 0.32s ease;
      box-shadow: 4px 0 28px rgba(0,0,0,0.18);
      overflow: hidden;
    }
    .panel-nav {
      flex: 1; overflow-y: auto; overflow-x: hidden;
      padding: 16px 12px;
      scrollbar-width: thin;
      scrollbar-color: rgba(255,255,255,0.2) transparent;
    }
    .panel-nav::-webkit-scrollbar { width: 4px; }
    .panel-nav::-webkit-scrollbar-track { background: transparent; }
    .panel-nav::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.2); border-radius: 99px; }
    .panel-sidebar-header {
      padding: 28px 24px 20px;
      border-bottom: 1px solid rgba(255,255,255,0.1);
    }
    .panel-sidebar-logo {
      font-family: 'Syne', sans-serif; font-size: 1.6rem; font-weight: 800; color: #fff;
    }
    .panel-sidebar-logo span { color: var(--c-emerald); }
    .panel-sidebar-sub { font-size: 0.75rem; opacity: 0.6; margin-top: 3px; }

    .panel-nav-item {
      display: flex; align-items: center; gap: 14px;
      padding: 13px 18px; border-radius: 12px; margin-bottom: 6px;
      color: rgba(255,255,255,0.65); text-decoration: none;
      font-weight: 500; font-size: 0.9rem; cursor: pointer;
      transition: var(--transition); position: relative;
    }
    .panel-nav-item i { font-size: 1.2rem; flex-shrink: 0; transition: transform 0.25s ease; }
    .panel-nav-item:hover { background: rgba(255,255,255,0.1); color: #fff; }
    .panel-nav-item:hover i { transform: scale(1.15); }
    .panel-nav-item.active {
      background: linear-gradient(135deg, var(--c-emerald), var(--c-emerald-mid));
      color: #fff; box-shadow: 0 4px 16px rgba(16,185,129,0.35);
    }
    .panel-nav-item.logout { color: rgba(252,165,165,0.75); margin-top: auto; }
    .panel-nav-item.logout:hover { background: rgba(239,68,68,0.15); color: #fca5a5; }
    .panel-nav-divider { height: 1px; background: rgba(255,255,255,0.08); margin: 10px 16px; }
    .panel-sidebar-footer { padding: 16px 24px; border-top: 1px solid rgba(255,255,255,0.08); }
    .panel-user-info { display: flex; align-items: center; gap: 12px; }
    .panel-avatar {
      width: 38px; height: 38px; border-radius: 50%;
      background: linear-gradient(135deg, var(--c-emerald), var(--c-emerald-mid));
      display: flex; align-items: center; justify-content: center;
      font-weight: 700; color: #fff; font-size: 0.9rem; flex-shrink: 0;
    }
    .panel-user-name { font-size: 0.88rem; color: rgba(255,255,255,0.9); font-weight: 600; }
    .panel-user-role { font-size: 0.73rem; color: rgba(255,255,255,0.5); }

    /* Panel main content */
    .panel-main { 
      margin-left: var(--sidebar-w); min-height: 100vh;
      background: var(--c-slate-50); flex: 1;
    }
    .panel-topbar {
      background: #fff; border-bottom: 1px solid var(--c-slate-200);
      padding: 0 32px; height: 64px;
      display: flex; align-items: center; justify-content: space-between;
      position: sticky; top: 0; z-index: 700;
      box-shadow: 0 1px 8px rgba(0,0,0,0.05);
    }
    .panel-topbar-title { font-family: 'Syne', sans-serif; font-size: 1.2rem; font-weight: 700; color: var(--c-forest); }
    .topbar-right { display: flex; align-items: center; gap: 16px; }
    .topbar-notif {
      width: 36px; height: 36px; border-radius: 10px; background: var(--c-slate-100);
      border: none; cursor: pointer; display: flex; align-items: center; justify-content: center;
      color: var(--c-slate-600); font-size: 1.1rem; position: relative; transition: var(--transition);
    }
    .topbar-notif:hover { background: var(--c-emerald-pale); color: var(--c-emerald); }
    .notif-dot {
      position: absolute; top: 5px; right: 5px; width: 8px; height: 8px;
      background: var(--c-red); border-radius: 50%; border: 2px solid #fff;
    }
    .mobile-sidebar-toggle {
      display: none; background: none; border: none;
      color: var(--c-forest); font-size: 1.4rem; cursor: pointer; padding: 4px;
    }
    .panel-content { padding: 28px 32px; }
    .panel-section { display: none; animation: fadeUp 0.38s ease both; }
    .panel-section.active { display: block; }

    /* Stat cards in panel */
    .stat-cards { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 18px; margin-bottom: 28px; }
    .stat-card {
      background: #fff; border-radius: var(--radius-md); padding: 22px 20px;
      display: flex; align-items: center; gap: 16px;
      box-shadow: var(--shadow-card); transition: var(--transition);
      position: relative; overflow: hidden;
    }
    .stat-card::after {
      content: ''; position: absolute; top: 0; left: 0; width: 100%; height: 3px;
      background: linear-gradient(90deg, var(--c-emerald), var(--c-emerald-light));
      transform: scaleX(0); transition: transform 0.3s ease; transform-origin: left;
    }
    .stat-card:hover { transform: translateY(-4px); box-shadow: var(--shadow-float); }
    .stat-card:hover::after { transform: scaleX(1); }
    .stat-icon-wrap {
      width: 52px; height: 52px; border-radius: 14px;
      display: flex; align-items: center; justify-content: center;
      font-size: 1.4rem; flex-shrink: 0;
    }
    .stat-icon-green { background: #ecfdf5; color: var(--c-emerald); }
    .stat-icon-amber { background: #fffbeb; color: var(--c-amber); }
    .stat-icon-blue { background: #eff6ff; color: var(--c-blue); }
    .stat-icon-red { background: #fef2f2; color: var(--c-red); }
    .stat-icon-purple { background: #faf5ff; color: #8b5cf6; }
    .stat-num { font-family: 'Syne', sans-serif; font-size: 1.7rem; font-weight: 800; color: var(--c-slate-800); line-height: 1; }
    .stat-lbl { font-size: 0.78rem; color: var(--c-slate-400); margin-top: 4px; }

    /* Dashboard cards */
    .dash-card {
      background: #fff; border-radius: var(--radius-md);
      box-shadow: var(--shadow-card); transition: var(--transition);
      margin-bottom: 20px; overflow: hidden;
    }
    .dash-card:hover { box-shadow: var(--shadow-float); }
    .dash-card-header {
      padding: 18px 22px; border-bottom: 1px solid var(--c-slate-100);
      display: flex; align-items: center; justify-content: space-between;
    }
    .dash-card-title { font-family: 'Syne', sans-serif; font-size: 1rem; font-weight: 700; color: var(--c-slate-800); }
    .dash-card-body { padding: 20px 22px; }
    .dash-row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px; }

    /* Tables */
    .table-wrap { overflow-x: auto; }
    table { width: 100%; border-collapse: collapse; }
    thead th { 
      padding: 13px 16px; text-align: left; font-size: 0.78rem;
      font-weight: 600; color: var(--c-slate-400); text-transform: uppercase;
      letter-spacing: 0.06em; background: var(--c-slate-50); border-bottom: 1px solid var(--c-slate-200);
      white-space: nowrap;
    }
    tbody td { padding: 14px 16px; font-size: 0.88rem; color: var(--c-slate-700); border-bottom: 1px solid var(--c-slate-100); }
    tbody tr { transition: var(--transition); }
    tbody tr:hover { background: var(--c-slate-50); }
    tbody tr:last-child td { border-bottom: none; }

    /* Badges */
    .badge {
      display: inline-flex; align-items: center;
      padding: 4px 10px; border-radius: 6px; font-size: 0.74rem; font-weight: 600;
    }
    .badge-green { background: #ecfdf5; color: #065f46; }
    .badge-amber { background: #fffbeb; color: #92400e; }
    .badge-red { background: #fef2f2; color: #991b1b; }
    .badge-blue { background: #eff6ff; color: #1e40af; }
    .badge-gray { background: var(--c-slate-100); color: var(--c-slate-600); }
    .badge-purple { background: #faf5ff; color: #6b21a8; }

    /* Map */
    #leaflet-map, #admin-map, #agri-map { width: 100%; height: 420px; border-radius: var(--radius-md); z-index: 1; }

    /* Charts */
    .chart-container { position: relative; height: 280px; }

    /* Form in panel */
    .panel-form-group { margin-bottom: 18px; }
    .panel-form-label { 
      display: block; font-size: 0.82rem; font-weight: 600; 
      color: var(--c-slate-600); margin-bottom: 7px; 
    }
    .panel-form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }

    /* Search bar */
    .search-wrap { position: relative; flex: 1; max-width: 340px; }
    .search-wrap input {
      width: 100%; padding: 10px 16px 10px 38px;
      background: var(--c-slate-100); border: 1.5px solid var(--c-slate-200);
      border-radius: var(--radius-sm); font-size: 0.88rem;
      outline: none; transition: var(--transition);
    }
    .search-wrap input:focus { border-color: var(--c-emerald); background: #fff; box-shadow: 0 0 0 3px rgba(16,185,129,0.1); }
    .search-wrap i { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--c-slate-400); }
    .filter-bar { display: flex; align-items: center; gap: 12px; margin-bottom: 20px; flex-wrap: wrap; }

    /* Page header in panel */
    .page-header-panel { margin-bottom: 24px; }
    .page-header-panel h2 { font-family: 'Syne', sans-serif; font-size: 1.6rem; font-weight: 800; color: var(--c-forest); }
    .page-header-panel p { font-size: 0.9rem; color: var(--c-slate-400); margin-top: 4px; }

    /* Sidebar overlay (mobile) */
    .sidebar-overlay {
      display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5);
      z-index: 799;
    }
    .sidebar-overlay.open { display: block; }

    /* ===================== FOOTER ===================== */
    .site-footer {
      position: relative; z-index: 2;
      background: rgba(4,23,14,0.85); backdrop-filter: blur(14px);
      border-top: 1px solid rgba(255,255,255,0.08);
      text-align: center; padding: 20px;
      color: rgba(255,255,255,0.5); font-size: 0.8rem;
    }
    .site-footer a { color: var(--c-emerald); text-decoration: none; }
    .site-footer a:hover { color: var(--c-emerald-light); }

    /* ===================== ANIMATIONS ===================== */
    @keyframes fadeUp {
      from { opacity: 0; transform: translateY(22px); }
      to   { opacity: 1; transform: translateY(0); }
    }
    @keyframes slideUp {
      from { opacity: 0; transform: translateY(36px); }
      to   { opacity: 1; transform: translateY(0); }
    }
    @keyframes fadeIn {
      from { opacity: 0; } to { opacity: 1; }
    }
    @keyframes scaleIn {
      from { opacity: 0; transform: scale(0.9); }
      to   { opacity: 1; transform: scale(1); }
    }

    /* ===================== RESPONSIVE ===================== */
    @media (max-width: 768px) {
      .navbar-links { display: none; }
      .nav-hamburger { display: block; }
      .hero-feature-strip { 
        position: relative; bottom: auto; left: auto; transform: none;
        flex-wrap: wrap; justify-content: center; margin-top: 32px; padding-bottom: 20px;
      }
      .panel-sidebar { transform: translateX(-100%); }
      .panel-sidebar.open { transform: translateX(0); }
      .panel-main { margin-left: 0; }
      .mobile-sidebar-toggle { display: block; }
      .dash-row { grid-template-columns: 1fr; }
      .panel-form-row { grid-template-columns: 1fr; }
      .panel-content { padding: 20px 16px; }
      .panel-topbar { padding: 0 16px; }
    }

    @media (max-width: 480px) {
      .stat-cards { grid-template-columns: 1fr 1fr; }
      .hero-content h1 { font-size: 2rem; }
      .cta-group { flex-direction: column; align-items: center; }
      .auth-card { padding: 28px 20px; }
    }

    /* ===================== MISC ===================== */
    hr.section-divider { border: none; border-top: 1px solid rgba(255,255,255,0.1); margin: 24px 0; }
    .text-center { text-align: center; }
    .mt-8 { margin-top: 8px; }
    .mt-12 { margin-top: 12px; }
    .mt-16 { margin-top: 16px; }
    .mt-24 { margin-top: 24px; }
    .mb-0 { margin-bottom: 0; }
    .d-flex { display: flex; }
    .gap-8 { gap: 8px; }
    .align-center { align-items: center; }
    .justify-between { justify-content: space-between; }
    .fw-600 { font-weight: 600; }
    .text-emerald { color: var(--c-emerald); }
    .text-muted { color: var(--c-slate-400); }
    .text-sm { font-size: 0.82rem; }
    .rounded-full { border-radius: 100px; }
    .scroll-y { overflow-y: auto; }
    
    /* Dots loader */
    .dots-loader { display: inline-flex; gap: 5px; align-items: center; }
    .dots-loader span { width: 6px; height: 6px; background: currentColor; border-radius: 50%; animation: dot-bounce 1.2s infinite ease-in-out; }
    .dots-loader span:nth-child(2) { animation-delay: 0.2s; }
    .dots-loader span:nth-child(3) { animation-delay: 0.4s; }
    @keyframes dot-bounce { 0%, 80%, 100% { transform: scale(0.8); opacity: 0.5; } 40% { transform: scale(1.2); opacity: 1; } }

    /* Leaflet fix */
    .leaflet-container { font-family: 'DM Sans', sans-serif; }
  </style>
</head>
<body>
<div class="hero-bg" id="hero-bg"></div>

<!-- =========== NAVBAR =========== -->
<nav class="navbar" id="main-navbar">
  <div class="navbar-logo" onclick="navigate('home')">
    <i class="bi bi-leaf-fill leaf"></i>
    Agri<span>Trace+</span>
  </div>
  <ul class="navbar-links" id="desktop-nav">
    <li><a href="#" onclick="navigate('home'); return false;">Home</a></li>
    <li><a href="#" onclick="navigate('about'); return false;">About</a></li>
    <li><a href="#" onclick="navigate('contact'); return false;">Contact</a></li>
    <li><a href="#" onclick="navigate('login'); return false;" class="btn-nav">Login</a></li>
  </ul>
  <button class="nav-hamburger" onclick="toggleMobileMenu()" id="hamburger"><i class="bi bi-list"></i></button>
</nav>
<div class="mobile-menu" id="mobile-menu">
  <a href="#" onclick="navigate('home'); closeMobileMenu(); return false;"><i class="bi bi-house me-2"></i>Home</a>
  <a href="#" onclick="navigate('about'); closeMobileMenu(); return false;"><i class="bi bi-info-circle me-2"></i>About</a>
  <a href="#" onclick="navigate('contact'); closeMobileMenu(); return false;"><i class="bi bi-envelope me-2"></i>Contact</a>
  <a href="#" onclick="navigate('public-report'); closeMobileMenu(); return false;"><i class="bi bi-file-earmark-text me-2"></i>Public Report</a>
  <a href="#" onclick="navigate('login'); closeMobileMenu(); return false;"><i class="bi bi-box-arrow-in-right me-2"></i>Login</a>
</div>

<!-- =========== HOME PAGE =========== -->
<div class="page auth-page" id="page-home" style="min-height:100vh; justify-content:center; align-items:center; padding-top:68px; flex-direction:column;">
  <div class="hero-content">
    <div class="hero-badge"><i class="bi bi-geo-alt-fill"></i> GEO-TAGGING ENABLED</div>
    <h1>Welcome to<br><span class="hl">AgriTrace+</span></h1>
    <p>A Digital Livestock Registration & Reporting System<br>with Geo-Tagging Integration</p>
    <div class="cta-group">
      <button class="btn-hero-primary" onclick="navigate('login')"><i class="bi bi-box-arrow-in-right"></i> Get Started</button>
      <button class="btn-hero-secondary" onclick="navigate('public-report')"><i class="bi bi-file-earmark-text"></i> Public Report</button>
    </div>
  </div>
  <div class="hero-feature-strip">
    <div class="hf-card"><i class="bi bi-geo-alt-fill"></i><h5>Geo-Tagging</h5><p>GPS Enabled</p></div>
    <div class="hf-card"><i class="bi bi-shield-check"></i><h5>Secure</h5><p>Data Protected</p></div>
    <div class="hf-card"><i class="bi bi-graph-up"></i><h5>Real-time</h5><p>Live Monitoring</p></div>
  </div>
  <footer class="site-footer" style="position:relative; z-index:2; width:100%; margin-top: auto;">© 2026 AgriTrace Technologies | <a href="#" onclick="navigate('about'); return false;">About</a> | <a href="#" onclick="navigate('contact'); return false;">Contact</a></footer>
</div>

<!-- =========== LOGIN PAGE =========== -->
<div class="page auth-page" id="page-login">
  <div class="auth-card" style="max-width:480px; margin:auto; width:100%;">
    <button class="auth-close" onclick="navigate('home')"><i class="bi bi-x-lg"></i></button>
    <div class="text-center" style="margin-bottom:28px;">
      <div class="auth-logo">Agri<span>Trace+</span></div>
      <div class="geo-badge"><i class="bi bi-geo-alt-fill"></i> GEO-TAGGING ENABLED</div>
      <p class="auth-subtitle">A Digital Livestock Registration and<br>Reporting System</p>
    </div>
    <div id="login-error" class="alert alert-danger hidden"></div>
    <form id="login-form" onsubmit="handleLogin(event)">
      <div class="form-group">
        <div class="input-wrap">
          <i class="input-icon bi bi-person"></i>
          <input type="email" class="form-input" placeholder="Email Address" id="login-email" required>
        </div>
      </div>
      <div class="form-group">
        <div class="input-wrap">
          <i class="input-icon bi bi-lock"></i>
          <input type="password" class="form-input" placeholder="Password" id="login-password" required>
          <button type="button" class="toggle-pass" onclick="togglePw('login-password',this)"><i class="bi bi-eye"></i></button>
        </div>
      </div>
      <button type="submit" class="btn btn-primary mt-8">LOG IN</button>
    </form>
    <div class="auth-links mt-12">
      <a href="#" onclick="navigate('register'); return false;">Register Account</a>
      <a href="#" onclick="navigate('forgot-password'); return false;">Forgot Password?</a>
    </div>
    <button class="btn btn-secondary" onclick="navigate('public-report')"><i class="bi bi-globe me-2"></i>Access Public Panel</button>
    <p class="auth-footer">© 2026 AgriTrace Technologies</p>
  </div>
</div>

<!-- =========== REGISTER PAGE =========== -->
<div class="page auth-page" id="page-register">
  <div class="auth-card" style="max-width:520px; margin:auto; width:100%;">
    <button class="auth-close" onclick="navigate('login')"><i class="bi bi-x-lg"></i></button>
    <div class="text-center" style="margin-bottom:24px;">
      <div class="auth-logo">Agri<span>Trace+</span></div>
      <div class="geo-badge"><i class="bi bi-geo-alt-fill"></i> GEO-TAGGING ENABLED</div>
      <p class="auth-subtitle">Create Your Account</p>
    </div>
    <div id="reg-success" class="alert alert-success hidden"></div>
    <form id="register-form" onsubmit="handleRegisterWithDB(event)">
      <div class="panel-form-row">
        <div class="form-group">
          <label class="form-label">First Name</label>
          <input type="text" class="form-input no-icon" placeholder="Juan" required>
        </div>
        <div class="form-group">
          <label class="form-label">Last Name</label>
          <input type="text" class="form-input no-icon" placeholder="dela Cruz" required>
        </div>
      </div>
      <div class="form-group">
        <label class="form-label">Email Address</label>
        <div class="input-wrap">
          <i class="input-icon bi bi-envelope"></i>
          <input type="email" class="form-input" placeholder="juan@example.com" required>
        </div>
      </div>
      <div class="form-group">
        <label class="form-label">Mobile Number</label>
        <div class="input-wrap">
          <i class="input-icon bi bi-phone"></i>
          <input type="tel" class="form-input" placeholder="+63 9XX XXX XXXX" required>
        </div>
      </div>
      <div class="form-group">
        <label class="form-label">Role</label>
        <select class="form-select" required>
          <option value="">-- Select Role --</option>
          <option>Farmer</option>
          <option>Agriculture Official</option>
        </select>
      </div>
      <div class="form-group">
        <label class="form-label">Password <span style="cursor:pointer; color:var(--c-emerald);" onclick="togglePwReqs()"><i class="bi bi-info-circle"></i></span></label>
        <div class="input-wrap">
          <i class="input-icon bi bi-lock"></i>
          <input type="password" class="form-input" id="reg-password" placeholder="Create a strong password" required oninput="checkPwStrength(this.value)">
          <button type="button" class="toggle-pass" onclick="togglePw('reg-password',this)"><i class="bi bi-eye"></i></button>
        </div>
        <div class="pw-strength-bar"><div class="pw-strength-fill" id="pw-fill"></div></div>
        <div class="pw-strength-text" id="pw-text"></div>
        <ul class="pw-reqs" id="pw-reqs">
          <li id="req-len"><i class="bi bi-circle me-2"></i>At least 8 characters</li>
          <li id="req-num"><i class="bi bi-circle me-2"></i>Contains a number</li>
          <li id="req-spec"><i class="bi bi-circle me-2"></i>Contains a special character</li>
          <li id="req-low"><i class="bi bi-circle me-2"></i>Contains a lowercase letter</li>
          <li id="req-up"><i class="bi bi-circle me-2"></i>Contains an uppercase letter</li>
        </ul>
      </div>
      <div class="form-group">
        <label class="form-label">Confirm Password</label>
        <div class="input-wrap">
          <i class="input-icon bi bi-lock-fill"></i>
          <input type="password" class="form-input" id="reg-confirm" placeholder="Repeat password" required>
        </div>
      </div>
      <div class="form-group">
        <div class="check-group">
          <input type="checkbox" id="terms-check" onchange="handleTermsCheck(this)">
          <label for="terms-check">I agree to the <a href="#" onclick="openTermsModal(); return false;">Terms and Conditions</a></label>
        </div>
      </div>
      <button type="submit" class="btn btn-primary" id="reg-btn" disabled>REGISTER</button>
    </form>
    <div class="auth-links mt-12" style="justify-content:center;">
      <a href="#" onclick="navigate('login'); return false;">Already have an account? Log In</a>
    </div>
    <p class="auth-footer">© 2026 AgriTrace Technologies</p>
  </div>
</div>

<!-- =========== FORGOT PASSWORD =========== -->
<div class="page auth-page" id="page-forgot-password">
  <div class="auth-card" style="max-width:460px; margin:auto; width:100%;">
    <div class="text-center" style="margin-bottom:28px;">
      <div class="auth-logo">Agri<span>Trace+</span></div>
      <div class="geo-badge"><i class="bi bi-geo-alt-fill"></i> GEO-TAGGING ENABLED</div>
      <p class="auth-subtitle">Reset Your Password Securely</p>
    </div>
    <div id="forgot-success" class="alert alert-success hidden"></div>
    <form onsubmit="handleForgot(event)">
      <div class="method-tabs">
        <button type="button" class="method-tab active" id="tab-email" onclick="switchForgotMethod('email')"><i class="bi bi-envelope-fill"></i> Email</button>
        <button type="button" class="method-tab" id="tab-mobile" onclick="switchForgotMethod('mobile')"><i class="bi bi-phone-fill"></i> Mobile</button>
      </div>
      <div class="form-group" id="forgot-email-group">
        <div class="input-wrap">
          <i class="input-icon bi bi-envelope"></i>
          <input type="email" class="form-input" placeholder="Enter your email address" id="forgot-email">
        </div>
      </div>
      <div class="form-group hidden" id="forgot-mobile-group">
        <div class="input-wrap">
          <i class="input-icon bi bi-phone"></i>
          <input type="tel" class="form-input" placeholder="Enter your mobile number" id="forgot-mobile">
        </div>
        <p class="text-sm mt-8" style="opacity:0.7;"><i class="bi bi-info-circle me-1"></i>We'll send an OTP to your mobile</p>
      </div>
      <button type="submit" class="btn btn-primary" id="forgot-btn">
        <i class="bi bi-send-fill me-2"></i><span id="forgot-btn-text">SEND RESET LINK</span>
      </button>
    </form>
    <div class="auth-links mt-16" style="justify-content:center;">
      <a href="#" onclick="navigate('login'); return false;"><i class="bi bi-arrow-left me-1"></i>Back to Login</a>
    </div>
    <p class="auth-footer">© 2026 AgriTrace Technologies</p>
  </div>
</div>

<!-- =========== RESET PASSWORD =========== -->
<div class="page auth-page" id="page-reset-password">
  <div class="auth-card" style="max-width:440px; margin:auto; width:100%;">
    <div class="text-center" style="margin-bottom:28px;">
      <div class="auth-logo">Agri<span>Trace+</span></div>
      <p class="auth-subtitle">Enter your new password</p>
    </div>
    <div id="reset-success" class="alert alert-success hidden"></div>
    <div id="reset-error" class="alert alert-danger hidden"></div>
    <form onsubmit="handleReset(event)">
      <div class="form-group">
        <input type="password" class="form-input no-icon" placeholder="New Password (min 8 chars)" id="reset-pw" required minlength="8">
      </div>
      <div class="form-group">
        <input type="password" class="form-input no-icon" placeholder="Confirm Password" id="reset-confirm" required>
      </div>
      <button type="submit" class="btn btn-primary">RESET PASSWORD</button>
    </form>
    <p class="auth-footer">© 2026 AgriTrace Technologies</p>
  </div>
</div>

<!-- =========== PUBLIC REPORT =========== -->
<div class="page auth-page" id="page-public-report">
  <div class="auth-card" style="max-width:620px; margin:auto; width:100%;">
    <button class="auth-close" onclick="navigate('home')"><i class="bi bi-x-lg"></i></button>
    <div class="text-center" style="margin-bottom:28px;">
      <div class="auth-logo">Agri<span>Trace+</span></div>
      <div class="geo-badge"><i class="bi bi-globe2"></i> PUBLIC ACCESS</div>
      <p class="auth-subtitle" style="margin-bottom:0;">Submit Anonymous Reports for Livestock Health and Safety</p>
    </div>
    <div id="report-success" class="alert alert-success hidden"></div>
    <form onsubmit="submitPublicReport(event)">
      <div class="form-group">
        <label class="form-label">Report Type</label>
        <div class="radio-group">
          <div class="radio-item"><input type="radio" name="rtype" id="r-sick" value="Sick livestock" required><label for="r-sick">Sick livestock</label></div>
          <div class="radio-item"><input type="radio" name="rtype" id="r-dead" value="Dead animals"><label for="r-dead">Dead animals</label></div>
          <div class="radio-item"><input type="radio" name="rtype" id="r-stray" value="Stray livestock"><label for="r-stray">Stray livestock</label></div>
          <div class="radio-item"><input type="radio" name="rtype" id="r-outbreak" value="Suspected disease outbreak"><label for="r-outbreak">Suspected disease outbreak</label></div>
          <div class="radio-item"><input type="radio" name="rtype" id="r-other" value="Others" onchange="document.getElementById('other-txt').classList.toggle('hidden', !this.checked)"><label for="r-other">Others</label></div>
        </div>
        <input type="text" class="form-input no-icon mt-8 hidden" id="other-txt" placeholder="Please specify...">
      </div>
      <div class="form-group">
        <label class="form-label">Upload Photos/Videos</label>
        <div class="file-btn-wrap">
          <input type="file" class="form-input no-icon" id="report-photos" multiple accept="image/*,video/*">
          <button type="button" class="btn-camera" onclick="document.getElementById('report-photos').click()"><i class="bi bi-camera-fill"></i> Add</button>
        </div>
      </div>
      <div class="form-group">
        <label class="form-label">Description</label>
        <textarea class="form-input" rows="4" placeholder="Describe the issue, location, or observation..." required></textarea>
      </div>
      <div class="form-group">
        <label class="form-label">Contact Phone <span style="color:var(--c-red);">*</span></label>
        <div class="input-wrap"><i class="input-icon bi bi-telephone"></i><input type="tel" class="form-input" required></div>
      </div>
      <div class="form-group">
        <label class="form-label">Contact Email (Optional)</label>
        <div class="input-wrap"><i class="input-icon bi bi-envelope"></i><input type="email" class="form-input"></div>
      </div>
      <div class="form-group">
        <label class="form-label">Upload ID Photo <span style="color:var(--c-red);">*</span></label>
        <div class="file-btn-wrap">
          <input type="file" class="form-input no-icon" id="id-photo" accept="image/*" required>
          <button type="button" class="btn-camera" onclick="document.getElementById('id-photo').click()"><i class="bi bi-camera-fill"></i> Add</button>
        </div>
      </div>
      <div class="form-group">
        <label class="form-label">Upload Face Photo <span style="color:var(--c-red);">*</span></label>
        <div class="file-btn-wrap">
          <input type="file" class="form-input no-icon" id="face-photo" accept="image/*" capture="user" required>
          <button type="button" class="btn-camera" onclick="document.getElementById('face-photo').click()"><i class="bi bi-camera-fill"></i> Selfie</button>
        </div>
      </div>
      <div class="form-group">
        <div class="check-group">
          <input type="checkbox" id="genuine-check" required>
          <label for="genuine-check">I confirm that this report is accurate, genuine, and not submitted in bad faith or for fraudulent purposes.</label>
        </div>
      </div>
      <button type="submit" class="btn btn-primary">SUBMIT REPORT</button>
    </form>
    <div class="auth-links mt-12">
      <a href="#" onclick="navigate('login'); return false;">Log In for Full Access</a>
      <a href="#" onclick="toggleTrack(); return false;">Track Report</a>
    </div>
    <div class="track-section hidden" id="track-section">
      <h5 style="margin-bottom:14px; font-size:0.95rem; font-weight:600;">Track Your Report</h5>
      <div class="track-row">
        <input type="text" class="form-input no-icon" placeholder="Enter Reference Number e.g. RPT-AB1234">
        <button type="button" class="btn btn-panel" style="white-space:nowrap;">Check</button>
      </div>
    </div>
    <p class="auth-footer">© 2026 AgriTrace Technologies</p>
  </div>
</div>

<!-- =========== ABOUT PAGE =========== -->
<div class="page" id="page-about" style="padding-top:0;">
  <div class="content-page-wrap">
    <div class="container">
      <div class="content-header">
        <h1><i class="bi bi-info-circle-fill me-2 text-emerald"></i>About AgriTrace+</h1>
        <p>Revolutionizing Agricultural Traceability and Farm Management</p>
      </div>
      <div class="glass-card">
        <h2><i class="bi bi-bullseye"></i>Our Mission</h2>
        <p>AgriTrace+ is committed to transforming agricultural practices through cutting-edge technology and comprehensive traceability solutions. We empower farmers, agriculture officials, and administrators with the tools they need to ensure food safety, improve farm management, and promote sustainable agricultural practices.</p>
        <h3 style="color:rgba(255,255,255,0.85); font-size:1.05rem; margin-top:16px; margin-bottom:8px;"><i class="bi bi-eye me-2 text-emerald"></i>Our Vision</h3>
        <p>To become the leading agricultural traceability platform that connects farmers, officials, and consumers in a transparent ecosystem, ensuring food security, quality standards, and sustainable farming practices for future generations.</p>
      </div>
      <div class="glass-card">
        <h2><i class="bi bi-gear-fill"></i>What We Do</h2>
        <p>AgriTrace+ provides a comprehensive web-based platform for managing and tracking agricultural operations from farm to table.</p>
        <ul>
          <li>Complete farm registration and management</li>
          <li>Real-time livestock tracking and health monitoring</li>
          <li>Incident reporting and resolution management</li>
          <li>Farm inspection scheduling and compliance tracking</li>
          <li>Public reporting system for community engagement</li>
          <li>Role-based access control for different user types</li>
          <li>Comprehensive audit logging and analytics</li>
          <li>Geographic mapping and location-based insights</li>
        </ul>
      </div>
      <div class="glass-card">
        <h2><i class="bi bi-stars"></i>Key Features</h2>
        <div class="feature-grid">
          <div class="feat-box"><i class="bi bi-shield-check"></i><h4>Secure & Compliant</h4><p>End-to-end encryption and role-based access control</p></div>
          <div class="feat-box"><i class="bi bi-graph-up-arrow"></i><h4>Real-Time Analytics</h4><p>Comprehensive dashboards and reporting tools</p></div>
          <div class="feat-box"><i class="bi bi-geo-alt"></i><h4>Geo-Mapping</h4><p>Location-based tracking and monitoring</p></div>
          <div class="feat-box"><i class="bi bi-people"></i><h4>Multi-User Platform</h4><p>Farmers, officials, and administrators</p></div>
          <div class="feat-box"><i class="bi bi-phone"></i><h4>Mobile Responsive</h4><p>Access anywhere, anytime on any device</p></div>
          <div class="feat-box"><i class="bi bi-clock-history"></i><h4>Audit Trail</h4><p>Complete activity logging and traceability</p></div>
        </div>
      </div>
      <div class="glass-card">
        <h2><i class="bi bi-bar-chart-fill"></i>Platform Capabilities</h2>
        <div class="stats-grid">
          <div class="stat-box"><span class="num">∞</span><span class="lbl">Unlimited Farms</span></div>
          <div class="stat-box"><span class="num">∞</span><span class="lbl">Livestock Tracking</span></div>
          <div class="stat-box"><span class="num">24/7</span><span class="lbl">System Availability</span></div>
          <div class="stat-box"><span class="num">100%</span><span class="lbl">Data Integrity</span></div>
        </div>
      </div>
      <div class="cta-section">
        <h2>Ready to Get Started?</h2>
        <p>Join AgriTrace+ today and transform your agricultural management experience</p>
        <div class="cta-btns">
          <button class="btn-cta" onclick="navigate('register')"><i class="bi bi-person-plus"></i> Register Now</button>
          <button class="btn-cta-outline" onclick="navigate('login')"><i class="bi bi-box-arrow-in-right"></i> Login</button>
          <button class="btn-cta-outline" onclick="navigate('contact')"><i class="bi bi-envelope"></i> Contact Us</button>
        </div>
      </div>
    </div>
  </div>
  <footer class="site-footer">© 2026 AgriTrace Technologies | <a href="#" onclick="navigate('about'); return false;">About</a> | <a href="#" onclick="navigate('contact'); return false;">Contact</a></footer>
</div>

<!-- =========== CONTACT PAGE =========== -->
<div class="page" id="page-contact" style="padding-top:0;">
  <div class="content-page-wrap">
    <div class="container">
      <div class="content-header">
        <h1><i class="bi bi-envelope-fill me-2 text-emerald"></i>Contact Us</h1>
        <p>Get in touch with the AgriTrace+ team</p>
      </div>
      <div class="contact-info-grid">
        <div class="contact-card glass-card" style="margin-bottom:0;"><i class="bi bi-envelope-fill"></i><h5>Email</h5><p>support@agritrace.ph</p></div>
        <div class="contact-card glass-card" style="margin-bottom:0;"><i class="bi bi-telephone-fill"></i><h5>Phone</h5><p>+63 2 8XXX XXXX</p></div>
        <div class="contact-card glass-card" style="margin-bottom:0;"><i class="bi bi-geo-alt-fill"></i><h5>Address</h5><p>Manila, Philippines</p></div>
        <div class="contact-card glass-card" style="margin-bottom:0;"><i class="bi bi-clock-fill"></i><h5>Hours</h5><p>Mon–Fri, 8AM–5PM</p></div>
      </div>
      <div class="glass-card mt-24">
        <h2><i class="bi bi-chat-dots-fill"></i>Send Us a Message</h2>
        <div id="contact-success" class="alert alert-success hidden"></div>
        <form onsubmit="handleContact(event)">
          <div class="panel-form-row" style="margin-bottom:16px;">
            <div>
              <label class="form-label">Your Name</label>
              <input type="text" class="form-input no-icon" placeholder="Juan dela Cruz" required>
            </div>
            <div>
              <label class="form-label">Email Address</label>
              <input type="email" class="form-input no-icon" placeholder="juan@example.com" required>
            </div>
          </div>
          <div style="margin-bottom:16px;">
            <label class="form-label">Subject</label>
            <input type="text" class="form-input no-icon" placeholder="How can we help?" required>
          </div>
          <div style="margin-bottom:16px;">
            <label class="form-label">Message</label>
            <textarea class="form-input" rows="5" placeholder="Describe your concern or inquiry..." required></textarea>
          </div>
          <button type="submit" class="btn btn-primary" style="width:auto; padding:13px 36px;">Send Message <i class="bi bi-send ms-2"></i></button>
        </form>
      </div>
    </div>
  </div>
  <footer class="site-footer">© 2026 AgriTrace Technologies | <a href="#" onclick="navigate('about'); return false;">About</a></footer>
</div>

<!-- =========== FARMER PANEL =========== -->
<div class="page panel-page" id="page-farmer-panel">
  <div class="sidebar-overlay" id="farmer-overlay" onclick="closePanelSidebar('farmer')"></div>
  <aside class="panel-sidebar" id="farmer-sidebar">
    <div class="panel-sidebar-header">
      <div class="panel-sidebar-logo">Agri<span>Trace+</span></div>
      <div class="panel-sidebar-sub">Farmer Portal</div>
    </div>
    <nav class="panel-nav">
      <div class="panel-nav-item active" onclick="showPanel('farmer','dashboard')"><i class="bi bi-speedometer2"></i> Dashboard</div>
      <div class="panel-nav-item" onclick="showPanel('farmer','farm')"><i class="bi bi-house-gear"></i> Farm Registration</div>
      <div class="panel-nav-item" onclick="showPanel('farmer','livestock')"><i class="bi bi-journal-check"></i> Livestock Monitoring</div>
      <div class="panel-nav-item" onclick="showPanel('farmer','incidents')"><i class="bi bi-exclamation-triangle"></i> Incident Reporting</div>
      <div class="panel-nav-item" onclick="showPanel('farmer','notifications')"><i class="bi bi-bell"></i> Notifications</div>
      <div class="panel-nav-item" onclick="showPanel('farmer','map')"><i class="bi bi-geo-alt"></i> Farm Map</div>
      <div class="panel-nav-item" onclick="showPanel('farmer','profile')"><i class="bi bi-person-circle"></i> Profile</div>
      <div class="panel-nav-divider"></div>
      <div class="panel-nav-item logout" onclick="navigate('login')"><i class="bi bi-power"></i> Logout</div>
    </nav>
    <div class="panel-sidebar-footer">
      <div class="panel-user-info">
        <div class="panel-avatar">JD</div>
        <div><div class="panel-user-name">Juan dela Cruz</div><div class="panel-user-role">Farmer</div></div>
      </div>
    </div>
  </aside>
  <main class="panel-main">
    <div class="panel-topbar">
      <div style="display:flex; align-items:center; gap:12px;">
        <button class="mobile-sidebar-toggle" onclick="openPanelSidebar('farmer')"><i class="bi bi-list"></i></button>
        <span class="panel-topbar-title" id="farmer-section-title">Dashboard</span>
      </div>
      <div class="topbar-right">
        <button class="topbar-notif"><i class="bi bi-bell"></i><span class="notif-dot"></span></button>
        <div class="panel-user-info">
          <div class="panel-avatar" style="width:32px;height:32px;font-size:0.8rem;">JD</div>
        </div>
      </div>
    </div>
    <div class="panel-content">
      <!-- Farmer Dashboard -->
      <div class="panel-section active" id="farmer-dashboard">
        <div class="page-header-panel"><h2>Good morning, Juan! 👋</h2><p>Here's an overview of your farm activities</p></div>
        <div class="stat-cards">
          <div class="stat-card"><div class="stat-icon-wrap stat-icon-green"><i class="bi bi-database"></i></div><div><div class="stat-num">24</div><div class="stat-lbl">Total Livestock</div></div></div>
          <div class="stat-card"><div class="stat-icon-wrap stat-icon-amber"><i class="bi bi-exclamation-triangle"></i></div><div><div class="stat-num">2</div><div class="stat-lbl">Active Incidents</div></div></div>
          <div class="stat-card"><div class="stat-icon-wrap stat-icon-blue"><i class="bi bi-clipboard-check"></i></div><div><div class="stat-num">1</div><div class="stat-lbl">Pending Inspections</div></div></div>
          <div class="stat-card"><div class="stat-icon-wrap stat-icon-green"><i class="bi bi-patch-check-fill"></i></div><div><div class="stat-num">Active</div><div class="stat-lbl">Farm Status</div></div></div>
        </div>
        <div class="dash-row">
          <div class="dash-card">
            <div class="dash-card-header"><span class="dash-card-title">Livestock by Type</span></div>
            <div class="dash-card-body"><div class="chart-container"><canvas id="farmer-livestock-chart"></canvas></div></div>
          </div>
          <div class="dash-card">
            <div class="dash-card-header"><span class="dash-card-title">Notifications</span></div>
            <div class="dash-card-body">
              <div style="display:flex;flex-direction:column;gap:10px;">
                <div style="display:flex;gap:12px;align-items:flex-start;padding:12px;background:var(--c-slate-50);border-radius:10px;border-left:3px solid var(--c-amber);">
                  <i class="bi bi-virus" style="color:var(--c-amber);font-size:1.1rem;margin-top:2px;"></i>
                  <div><p style="margin:0;font-size:0.85rem;font-weight:600;">Disease Outbreak Alert</p><p style="margin:0;font-size:0.8rem;color:var(--c-slate-400);">Avian Flu in nearby areas</p></div>
                </div>
                <div style="display:flex;gap:12px;align-items:flex-start;padding:12px;background:var(--c-slate-50);border-radius:10px;border-left:3px solid var(--c-blue);">
                  <i class="bi bi-syringe" style="color:var(--c-blue);font-size:1.1rem;margin-top:2px;"></i>
                  <div><p style="margin:0;font-size:0.85rem;font-weight:600;">Vaccination Reminder</p><p style="margin:0;font-size:0.8rem;color:var(--c-slate-400);">Cattle vaccination due next week</p></div>
                </div>
                <div style="display:flex;gap:12px;align-items:flex-start;padding:12px;background:var(--c-slate-50);border-radius:10px;border-left:3px solid var(--c-emerald);">
                  <i class="bi bi-calendar-check" style="color:var(--c-emerald);font-size:1.1rem;margin-top:2px;"></i>
                  <div><p style="margin:0;font-size:0.85rem;font-weight:600;">Inspection Scheduled</p><p style="margin:0;font-size:0.8rem;color:var(--c-slate-400);">Farm inspection on Mar 25, 2026</p></div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="dash-card">
          <div class="dash-card-header"><span class="dash-card-title">Report Status Updates</span></div>
          <div class="dash-card-body">
            <div style="display:flex;flex-direction:column;gap:10px;">
              <div style="display:flex;gap:14px;align-items:flex-start;padding:14px;border:1.5px solid var(--c-slate-200);border-radius:10px;">
                <i class="bi bi-exclamation-triangle-fill" style="color:var(--c-red);font-size:1.3rem;margin-top:2px;"></i>
                <div><p style="margin:0;font-weight:600;font-size:0.9rem;">Disease Symptoms: Chicken showing flu-like symptoms</p><p style="margin:4px 0 0;font-size:0.8rem;"><span class="badge badge-amber">Pending</span></p></div>
              </div>
              <div style="display:flex;gap:14px;align-items:flex-start;padding:14px;border:1.5px solid var(--c-slate-200);border-radius:10px;">
                <i class="bi bi-exclamation-triangle-fill" style="color:var(--c-emerald);font-size:1.3rem;margin-top:2px;"></i>
                <div><p style="margin:0;font-weight:600;font-size:0.9rem;">Livestock Death: 1 pig died unexpectedly</p><p style="margin:4px 0 0;font-size:0.8rem;"><span class="badge badge-green">Resolved</span></p></div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Farmer Farm Registration -->
      <div class="panel-section" id="farmer-farm">
        <div class="page-header-panel"><h2>Farm & Livestock Registration</h2><p>Register or update your farm and livestock details</p></div>
        <div class="dash-card">
          <div class="dash-card-header"><span class="dash-card-title">Farm Details</span></div>
          <div class="dash-card-body">
            <form onsubmit="handlePanelForm(event,'Farm registered successfully!')">
              <div class="panel-form-row" style="margin-bottom:16px;">
                <div class="panel-form-group"><label class="panel-form-label">Farm Name</label><input type="text" class="form-input panel-input no-icon" placeholder="e.g. dela Cruz Family Farm" required></div>
                <div class="panel-form-group"><label class="panel-form-label">Farm Address</label><input type="text" class="form-input panel-input no-icon" placeholder="Street, Barangay, Municipality" required></div>
              </div>
              <div class="panel-form-row" style="margin-bottom:16px;">
                <div class="panel-form-group"><label class="panel-form-label">Farm Type</label><select class="form-select panel-select" required><option>Cattle Farm</option><option>Poultry Farm</option><option>Swine Farm</option><option>Mixed Farm</option></select></div>
                <div class="panel-form-group"><label class="panel-form-label">Farm Size (Hectares)</label><input type="number" step="0.01" class="form-input panel-input no-icon" placeholder="e.g. 5.2" required></div>
              </div>
              <div class="panel-form-row" style="margin-bottom:16px;">
                <div class="panel-form-group"><label class="panel-form-label">Animal Type</label><select class="form-select panel-select" required><option>Cattle</option><option>Swine</option><option>Poultry</option><option>Goat</option><option>Sheep</option></select></div>
                <div class="panel-form-group"><label class="panel-form-label">Quantity</label><input type="number" class="form-input panel-input no-icon" min="1" placeholder="Number of heads" required></div>
              </div>
              <div class="panel-form-row" style="margin-bottom:16px;">
                <div class="panel-form-group"><label class="panel-form-label">GPS Latitude</label><input type="text" class="form-input panel-input no-icon" placeholder="e.g. 14.5995"></div>
                <div class="panel-form-group"><label class="panel-form-label">GPS Longitude</label><input type="text" class="form-input panel-input no-icon" placeholder="e.g. 120.9842"></div>
              </div>
              <div class="panel-form-group" style="margin-bottom:16px;">
                <label class="panel-form-label">Upload Ownership Documents</label>
                <div class="file-btn-wrap"><input type="file" class="form-input panel-input no-icon" id="doc-upload" accept=".pdf,.jpg,.png"><button type="button" class="btn-camera" onclick="openCameraModal('doc')"><i class="bi bi-camera-fill"></i> Capture</button></div>
                <div id="doc-preview" style="display:flex;flex-wrap:wrap;gap:8px;margin-top:8px;"></div>
              </div>
              <div class="panel-form-group" style="margin-bottom:16px;">
                <label class="panel-form-label">Livestock Photos</label>
                <div class="file-btn-wrap"><input type="file" class="form-input panel-input no-icon" id="livestock-upload" accept="image/*" multiple><button type="button" class="btn-camera" onclick="openCameraModal('livestock')"><i class="bi bi-camera-fill"></i> Capture</button></div>
                <div id="livestock-preview" style="display:flex;flex-wrap:wrap;gap:8px;margin-top:8px;"></div>
              </div>
              <div class="panel-form-group" style="margin-bottom:20px;">
                <label class="panel-form-label">Farm Photos</label>
                <div class="file-btn-wrap"><input type="file" class="form-input panel-input no-icon" id="farm-upload" accept="image/*" multiple><button type="button" class="btn-camera" onclick="openCameraModal('farm')"><i class="bi bi-camera-fill"></i> Capture</button></div>
                <div id="farm-preview" style="display:flex;flex-wrap:wrap;gap:8px;margin-top:8px;"></div>
              </div>
              <button type="submit" class="btn btn-panel" style="width:100%;padding:14px;">Register / Update Farm & Livestock</button>
            </form>
          </div>
        </div>
      </div>

      <!-- Farmer Livestock Monitoring -->
      <div class="panel-section" id="farmer-livestock">
        <div class="page-header-panel"><h2>Farm Livestock Monitoring</h2><p>Track and manage all your registered livestock</p></div>
        <div class="filter-bar">
          <div class="search-wrap"><i class="bi bi-search"></i><input type="text" placeholder="Search livestock..." oninput="renderFarmerLivestock(this.value)"></div>
          <select class="form-select panel-select" style="width:auto;"><option>All Types</option><option>Cattle</option><option>Swine</option><option>Poultry</option><option>Goat</option></select>
          <button class="btn btn-panel btn-sm" onclick="showToast('Add livestock form ready')"><i class="bi bi-plus-lg me-1"></i>Add Livestock</button>
        </div>
        <div class="dash-card">
          <div class="table-wrap"><table id="farmer-livestock-table">
            <thead><tr><th>Tag ID</th><th>Type</th><th>Breed</th><th>Age / Qty</th><th>Health</th><th>Actions</th></tr></thead>
            <tbody></tbody>
          </table></div>
        </div>
      </div>

      <!-- Farmer Incident Reporting -->
      <div class="panel-section" id="farmer-incidents">
        <div class="page-header-panel"><h2>Report Incident</h2><p>File and track incident reports for your farm</p></div>
        <div class="dash-card" style="margin-bottom:20px;">
          <div class="dash-card-header"><span class="dash-card-title">File New Incident</span></div>
          <div class="dash-card-body">
            <form onsubmit="handlePanelForm(event,'Incident reported successfully! Reference: INC-'+Math.random().toString(36).substring(2,7).toUpperCase())">
              <div class="panel-form-group" style="margin-bottom:16px;"><label class="panel-form-label">Incident Type</label><select class="form-select panel-select" required><option>Disease Symptoms</option><option>Livestock Death</option><option>Theft</option><option>Disaster</option><option>Others</option></select></div>
              <div class="panel-form-group" style="margin-bottom:16px;"><label class="panel-form-label">Description</label><textarea class="form-input panel-input no-icon" rows="3" placeholder="Describe the incident in detail..." required></textarea></div>
              <div class="panel-form-group" style="margin-bottom:16px;">
                <label class="panel-form-label">Upload Photos / Videos</label>
                <div class="file-btn-wrap"><input type="file" class="form-input panel-input no-icon" id="incident-upload" accept="image/*,video/*" multiple><button type="button" class="btn-camera" onclick="openCameraModal('incident')"><i class="bi bi-camera-fill"></i> Capture</button></div>
                <div id="incident-preview" style="display:flex;flex-wrap:wrap;gap:8px;margin-top:8px;"></div>
                <p style="font-size:0.78rem;color:var(--c-slate-400);margin-top:6px;"><i class="bi bi-info-circle me-1"></i>Capture photos/videos for documentation</p>
              </div>
              <div class="panel-form-row" style="margin-bottom:16px;">
                <div class="panel-form-group"><label class="panel-form-label">GPS Coordinates (Optional)</label><input type="text" class="form-input panel-input no-icon" placeholder="e.g. 14.5995° N, 120.9842° E"></div>
                <div class="panel-form-group"><label class="panel-form-label">Date & Time of Incident</label><input type="datetime-local" class="form-input panel-input no-icon" required></div>
              </div>
              <button type="submit" class="btn btn-danger" style="width:100%;padding:14px;border-radius:10px;font-weight:700;"><i class="bi bi-exclamation-triangle-fill me-2"></i>Report Incident</button>
            </form>
          </div>
        </div>
        <div class="dash-card">
          <div class="dash-card-header"><span class="dash-card-title">My Incident Reports</span></div>
          <div class="dash-card-body">
            <div class="table-wrap"><table id="farmer-incidents-table">
              <thead><tr><th>Ref #</th><th>Date</th><th>Type</th><th>Description</th><th>Status</th><th>Edit</th></tr></thead>
              <tbody></tbody>
            </table></div>
          </div>
        </div>
      </div>

      <!-- Farmer Notifications -->
      <div class="panel-section" id="farmer-notifications">
        <div class="page-header-panel"><h2>Notifications</h2><p>Stay updated with alerts and reminders</p></div>
        <div class="dash-card">
          <div style="display:flex;flex-direction:column;gap:12px;">
            <div style="display:flex;gap:14px;align-items:flex-start;padding:16px;border:1.5px solid #fef3c7;border-radius:12px;background:#fffbeb;">
              <i class="bi bi-virus" style="color:var(--c-amber);font-size:1.4rem;margin-top:2px;flex-shrink:0;"></i>
              <div><p style="margin:0;font-weight:600;font-size:0.92rem;color:var(--c-slate-800);">Disease Outbreak Alert: Avian Flu in nearby areas</p><p style="margin:4px 0 0;font-size:0.8rem;color:var(--c-slate-400);">Mar 21, 2026 · System Alert</p></div>
            </div>
            <div style="display:flex;gap:14px;align-items:flex-start;padding:16px;border:1.5px solid #dbeafe;border-radius:12px;background:#eff6ff;">
              <i class="bi bi-syringe" style="color:var(--c-blue);font-size:1.4rem;margin-top:2px;flex-shrink:0;"></i>
              <div><p style="margin:0;font-weight:600;font-size:0.92rem;color:var(--c-slate-800);">Vaccination Reminder: Due for cattle next week</p><p style="margin:4px 0 0;font-size:0.8rem;color:var(--c-slate-400);">Mar 20, 2026 · DA Notification</p></div>
            </div>
            <div style="display:flex;gap:14px;align-items:flex-start;padding:16px;border:1.5px solid #d1fae5;border-radius:12px;background:#ecfdf5;">
              <i class="bi bi-calendar-check" style="color:var(--c-emerald);font-size:1.4rem;margin-top:2px;flex-shrink:0;"></i>
              <div><p style="margin:0;font-weight:600;font-size:0.92rem;color:var(--c-slate-800);">Inspection Scheduled: Farm inspection on Mar 25</p><p style="margin:4px 0 0;font-size:0.8rem;color:var(--c-slate-400);">Mar 18, 2026 · Maria Reyes (Agriculture Official)</p></div>
            </div>
          </div>
        </div>
      </div>

      <!-- Farmer Map -->
      <div class="panel-section" id="farmer-map">
        <div class="page-header-panel"><h2>Farm Map</h2><p>View your farm location and boundaries</p></div>
        <div class="dash-card"><div class="dash-card-body"><div id="leaflet-map"></div></div></div>
      </div>

      <!-- Farmer Profile -->
      <div class="panel-section" id="farmer-profile">
        <div class="page-header-panel"><h2>Profile & Security</h2><p>View and manage your personal and farm account details</p></div>
        <div class="dash-card">
          <div class="dash-card-header">
            <span class="dash-card-title">👤 My Profile</span>
            <button class="btn btn-panel btn-sm" onclick="openProfileModal('farmer')"><i class="bi bi-pencil me-1"></i>Edit Profile</button>
          </div>
          <div class="dash-card-body" id="farmer-profile-view">
            <!-- Populated by renderProfileView('farmer') -->
          </div>
        </div>
      </div>
    </div>
  </main>
</div>

<!-- =========== AGRICULTURE OFFICIAL PANEL =========== -->
<div class="page panel-page" id="page-agri-panel">
  <div class="sidebar-overlay" id="agri-overlay" onclick="closePanelSidebar('agri')"></div>
  <aside class="panel-sidebar" id="agri-sidebar">
    <div class="panel-sidebar-header">
      <div class="panel-sidebar-logo">Agri<span>Trace+</span></div>
      <div class="panel-sidebar-sub">Agriculture Official</div>
    </div>
    <nav class="panel-nav">
      <div class="panel-nav-item active" onclick="showPanel('agri','dashboard')"><i class="bi bi-grid-1x2"></i> Official Dashboard</div>
      <div class="panel-nav-item" onclick="showPanel('agri','farms')"><i class="bi bi-house-check"></i> Farm Inspection</div>
      <div class="panel-nav-item" onclick="showPanel('agri','incidents')"><i class="bi bi-exclamation-triangle"></i> Incident Management</div>
      <div class="panel-nav-item" onclick="showPanel('agri','publicreports')"><i class="bi bi-file-earmark-text"></i> Public Reports</div>
      <div class="panel-nav-item" onclick="showPanel('agri','map')"><i class="bi bi-geo-alt"></i> Geo-Monitoring</div>
      <div class="panel-nav-item" onclick="showPanel('agri','reports')"><i class="bi bi-bar-chart-line"></i> Reports & Analytics</div>
      <div class="panel-nav-item" onclick="showPanel('agri','profile')"><i class="bi bi-person-circle"></i> Profile & Security</div>
      <div class="panel-nav-divider"></div>
      <div class="panel-nav-item logout" onclick="navigate('login')"><i class="bi bi-power"></i> Logout</div>
    </nav>
    <div class="panel-sidebar-footer">
      <div class="panel-user-info">
        <div class="panel-avatar">MR</div>
        <div><div class="panel-user-name">Maria Reyes</div><div class="panel-user-role">Agriculture Official</div></div>
      </div>
    </div>
  </aside>
  <main class="panel-main">
    <div class="panel-topbar">
      <div style="display:flex;align-items:center;gap:12px;">
        <button class="mobile-sidebar-toggle" onclick="openPanelSidebar('agri')"><i class="bi bi-list"></i></button>
        <span class="panel-topbar-title" id="agri-section-title">Dashboard</span>
      </div>
      <div class="topbar-right">
        <button class="topbar-notif"><i class="bi bi-bell"></i><span class="notif-dot"></span></button>
        <div class="panel-avatar" style="width:32px;height:32px;font-size:0.8rem;">MR</div>
      </div>
    </div>
    <div class="panel-content">
      <!-- Agri Dashboard -->
      <div class="panel-section active" id="agri-dashboard">
        <div class="page-header-panel"><h2>Agriculture Official Dashboard</h2><p>Monitor agricultural activities in your assigned region</p></div>
        <div class="stat-cards">
          <div class="stat-card"><div class="stat-icon-wrap stat-icon-green"><i class="bi bi-house-door"></i></div><div><div class="stat-num">47</div><div class="stat-lbl">Approved Farms</div></div></div>
          <div class="stat-card"><div class="stat-icon-wrap stat-icon-amber"><i class="bi bi-exclamation-triangle"></i></div><div><div class="stat-num">8</div><div class="stat-lbl">Active Incidents</div></div></div>
          <div class="stat-card"><div class="stat-icon-wrap stat-icon-blue"><i class="bi bi-file-earmark-text"></i></div><div><div class="stat-num">5</div><div class="stat-lbl">Pending Public Reports</div></div></div>
          <div class="stat-card"><div class="stat-icon-wrap stat-icon-red"><i class="bi bi-bell"></i></div><div><div class="stat-num">3</div><div class="stat-lbl">Active Alerts</div></div></div>
        </div>
        <div class="dash-row">
          <div class="dash-card">
            <div class="dash-card-header"><span class="dash-card-title">Recent Incidents</span></div>
            <div class="dash-card-body">
              <div style="display:flex;flex-direction:column;gap:10px;">
                <div style="display:flex;justify-content:space-between;align-items:center;padding:12px;border:1px solid var(--c-slate-200);border-radius:10px;">
                  <div><p style="margin:0;font-weight:600;font-size:0.88rem;">Disease Symptoms</p><p style="margin:3px 0 0;font-size:0.78rem;color:var(--c-slate-400);">Status: <span class="badge badge-amber">Pending</span></p></div>
                  <button class="btn btn-panel btn-sm" onclick="showToast('Incident resolved!')">Resolve</button>
                </div>
                <div style="display:flex;justify-content:space-between;align-items:center;padding:12px;border:1px solid var(--c-slate-200);border-radius:10px;">
                  <div><p style="margin:0;font-weight:600;font-size:0.88rem;">Livestock Death</p><p style="margin:3px 0 0;font-size:0.78rem;color:var(--c-slate-400);">Status: <span class="badge badge-amber">Pending</span></p></div>
                  <button class="btn btn-panel btn-sm" onclick="showToast('Incident resolved!')">Resolve</button>
                </div>
                <div style="display:flex;justify-content:space-between;align-items:center;padding:12px;border:1px solid var(--c-slate-200);border-radius:10px;">
                  <div><p style="margin:0;font-weight:600;font-size:0.88rem;">Outbreak Alert</p><p style="margin:3px 0 0;font-size:0.78rem;color:var(--c-slate-400);">Status: <span class="badge badge-red">Critical</span></p></div>
                  <button class="btn btn-panel btn-sm" onclick="showToast('Incident resolved!')">Resolve</button>
                </div>
              </div>
            </div>
          </div>
          <div class="dash-card">
            <div class="dash-card-header"><span class="dash-card-title">Approved Farms</span></div>
            <div class="dash-card-body">
              <div style="display:flex;flex-direction:column;gap:10px;">
                <div style="padding:12px;border:1px solid var(--c-slate-200);border-radius:10px;"><p style="margin:0;font-weight:600;font-size:0.88rem;">Green Valley Farm</p><p style="margin:3px 0 0;font-size:0.78rem;color:var(--c-slate-400);">Type: Cattle Farm</p></div>
                <div style="padding:12px;border:1px solid var(--c-slate-200);border-radius:10px;"><p style="margin:0;font-weight:600;font-size:0.88rem;">Sunny Acres Poultry</p><p style="margin:3px 0 0;font-size:0.78rem;color:var(--c-slate-400);">Type: Poultry Farm</p></div>
                <div style="padding:12px;border:1px solid var(--c-slate-200);border-radius:10px;"><p style="margin:0;font-weight:600;font-size:0.88rem;">Hillside Ranch</p><p style="margin:3px 0 0;font-size:0.78rem;color:var(--c-slate-400);">Type: Mixed Farm</p></div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- Agri Farm Inspection -->
      <div class="panel-section" id="agri-farms">
        <div class="page-header-panel"><h2>Farm Inspection</h2><p>View and inspect all registered farms in your region</p></div>
        <div class="filter-bar">
          <div class="search-wrap"><i class="bi bi-search"></i><input type="text" placeholder="Search farms..." oninput="renderAgriFarms(this.value)"></div>
          <select class="form-select panel-select" style="width:auto;"><option>All Status</option><option>Approved</option><option>Pending</option><option>Rejected</option></select>
        </div>
        <div class="dash-card">
          <div class="table-wrap"><table id="agri-farms-table">
            <thead><tr><th>Farm Name</th><th>Owner</th><th>Type</th><th>Status</th><th>Actions</th></tr></thead>
            <tbody>
              <tr><td>Green Valley Farm</td><td>Juan dela Cruz</td><td>Cattle Farm</td><td><span class="badge badge-green">Approved</span></td><td><button class="btn btn-outline btn-sm" onclick="showToast('Inspecting Green Valley Farm')">Inspect</button></td></tr>
              <tr><td>Sunny Acres Poultry</td><td>Pedro Santos</td><td>Poultry Farm</td><td><span class="badge badge-green">Approved</span></td><td><button class="btn btn-outline btn-sm" onclick="showToast('Inspecting Sunny Acres Poultry')">Inspect</button></td></tr>
              <tr><td>Hillside Ranch</td><td>Ana Reyes</td><td>Mixed Farm</td><td><span class="badge badge-green">Approved</span></td><td><button class="btn btn-outline btn-sm" onclick="showToast('Inspecting Hillside Ranch')">Inspect</button></td></tr>
              <tr><td>Bautista Farm</td><td>Lito Bautista</td><td>Cattle Farm</td><td><span class="badge badge-amber">Pending</span></td><td><button class="btn btn-outline btn-sm" onclick="showToast('Reviewing Bautista Farm')">Review</button></td></tr>
            </tbody>
          </table></div>
        </div>
      </div>
      <!-- Agri Incident Management -->
      <div class="panel-section" id="agri-incidents">
        <div class="page-header-panel"><h2>Incident Management</h2><p>Review and resolve incidents in your region</p></div>
        <div class="dash-card">
          <div style="display:flex;flex-direction:column;gap:14px;">
            <div style="padding:18px;border:2px solid var(--c-slate-200);border-radius:14px;background:#fff;transition:all .25s ease;" onmouseover="this.style.borderColor='#10b981'" onmouseout="this.style.borderColor='#e2e8f0'">
              <p style="margin:0;font-weight:700;font-size:0.95rem;">Disease Symptoms</p>
              <p style="margin:6px 0;font-size:0.85rem;color:var(--c-slate-400);">Avian flu suspected in poultry farm</p>
              <div style="display:flex;gap:8px;flex-wrap:wrap;">
                <span class="badge badge-amber">Pending</span>
                <button class="btn btn-panel btn-sm" onclick="showToast('Incident resolved!')">Resolve</button>
                <button class="btn btn-outline btn-sm" onclick="showToast('Status updated!')">Update Status</button>
              </div>
            </div>
            <div style="padding:18px;border:2px solid var(--c-slate-200);border-radius:14px;background:#fff;" onmouseover="this.style.borderColor='#10b981'" onmouseout="this.style.borderColor='#e2e8f0'">
              <p style="margin:0;font-weight:700;font-size:0.95rem;">Livestock Death</p>
              <p style="margin:6px 0;font-size:0.85rem;color:var(--c-slate-400);">Multiple cattle deaths reported in Nueva Ecija</p>
              <div style="display:flex;gap:8px;flex-wrap:wrap;">
                <span class="badge badge-amber">Investigating</span>
                <button class="btn btn-panel btn-sm" onclick="showToast('Incident resolved!')">Resolve</button>
                <button class="btn btn-outline btn-sm" onclick="showToast('Status updated!')">Update Status</button>
              </div>
            </div>
            <div style="padding:18px;border:2px solid var(--c-slate-200);border-radius:14px;background:#fff;" onmouseover="this.style.borderColor='#10b981'" onmouseout="this.style.borderColor='#e2e8f0'">
              <p style="margin:0;font-weight:700;font-size:0.95rem;">Outbreak Alert</p>
              <p style="margin:6px 0;font-size:0.85rem;color:var(--c-slate-400);">Swine flu outbreak in Pampanga region</p>
              <div style="display:flex;gap:8px;flex-wrap:wrap;">
                <span class="badge badge-red">Critical</span>
                <button class="btn btn-panel btn-sm" onclick="showToast('Incident resolved!')">Resolve</button>
                <button class="btn btn-outline btn-sm" onclick="showToast('Status updated!')">Update Status</button>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- Agri Public Reports -->
      <div class="panel-section" id="agri-publicreports">
        <div class="page-header-panel"><h2>Public Reports</h2><p>Review and respond to public-submitted livestock reports</p></div>
        <div class="dash-card">
          <div style="display:flex;flex-direction:column;gap:14px;">
            <div style="padding:18px;border:2px solid var(--c-slate-200);border-radius:14px;" onmouseover="this.style.borderColor='#10b981'" onmouseout="this.style.borderColor='#e2e8f0'">
              <p style="margin:0;font-weight:700;font-size:0.95rem;">Sick livestock</p>
              <p style="margin:6px 0;font-size:0.85rem;color:var(--c-slate-400);">Sick cattle spotted near National Highway, Bulacan</p>
              <div style="display:flex;gap:8px;flex-wrap:wrap;">
                <span class="badge badge-blue">Pending</span>
                <button class="btn btn-panel btn-sm" onclick="showToast('Investigation started!')">Investigate</button>
                <button class="btn btn-outline btn-sm" onclick="showToast('Report resolved!')">Resolve</button>
              </div>
            </div>
            <div style="padding:18px;border:2px solid var(--c-slate-200);border-radius:14px;" onmouseover="this.style.borderColor='#10b981'" onmouseout="this.style.borderColor='#e2e8f0'">
              <p style="margin:0;font-weight:700;font-size:0.95rem;">Dead animals</p>
              <p style="margin:6px 0;font-size:0.85rem;color:var(--c-slate-400);">Multiple dead fish near river in Tarlac</p>
              <div style="display:flex;gap:8px;flex-wrap:wrap;">
                <span class="badge badge-amber">Under Review</span>
                <button class="btn btn-panel btn-sm" onclick="showToast('Investigation started!')">Investigate</button>
                <button class="btn btn-outline btn-sm" onclick="showToast('Report resolved!')">Resolve</button>
              </div>
            </div>
            <div style="padding:18px;border:2px solid var(--c-slate-200);border-radius:14px;" onmouseover="this.style.borderColor='#10b981'" onmouseout="this.style.borderColor='#e2e8f0'">
              <p style="margin:0;font-weight:700;font-size:0.95rem;">Suspected disease outbreak</p>
              <p style="margin:6px 0;font-size:0.85rem;color:var(--c-slate-400);">Several chickens dying rapidly in Pampanga barangay</p>
              <div style="display:flex;gap:8px;flex-wrap:wrap;">
                <span class="badge badge-red">Urgent</span>
                <button class="btn btn-panel btn-sm" onclick="showToast('Investigation started!')">Investigate</button>
                <button class="btn btn-outline btn-sm" onclick="showToast('Escalated to admin!')">Escalate</button>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- Agri Geo Map -->
      <div class="panel-section" id="agri-map">
        <div class="page-header-panel"><h2>Geo-Monitoring</h2><p>Real-time farm locations and livestock density across your region</p></div>
        <div class="dash-card"><div class="dash-card-header"><span class="dash-card-title"><i class="bi bi-globe me-2"></i>Farm Locations & Livestock Density</span></div><div class="dash-card-body"><div id="agri-map"></div></div></div>
        <div class="dash-card" style="margin-top:16px;">
          <div class="dash-card-header"><span class="dash-card-title">Map Legend</span></div>
          <div class="dash-card-body">
            <div style="display:flex;gap:20px;flex-wrap:wrap;">
              <div style="display:flex;align-items:center;gap:8px;"><div style="width:18px;height:18px;background:#10b981;border-radius:50%;"></div><span style="font-size:0.85rem;"><strong>Green:</strong> Healthy (10–20)</span></div>
              <div style="display:flex;align-items:center;gap:8px;"><div style="width:18px;height:18px;background:#f59e0b;border-radius:50%;"></div><span style="font-size:0.85rem;"><strong>Yellow:</strong> Moderate (20–30)</span></div>
              <div style="display:flex;align-items:center;gap:8px;"><div style="width:18px;height:18px;background:#ef4444;border-radius:50%;"></div><span style="font-size:0.85rem;"><strong>Red:</strong> High Density (&gt;30)</span></div>
              <div style="display:flex;align-items:center;gap:8px;"><div style="width:18px;height:18px;background:#8b5cf6;border-radius:50%;"></div><span style="font-size:0.85rem;"><strong>Purple:</strong> Inspection Site</span></div>
            </div>
          </div>
        </div>
        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-top:16px;">
          <div class="dash-card"><div class="dash-card-body" style="text-align:center;"><h3 style="color:var(--c-emerald);font-family:'Syne',sans-serif;">47</h3><p style="color:var(--c-slate-400);margin:0;font-size:0.85rem;">Farms in Region</p></div></div>
          <div class="dash-card"><div class="dash-card-body" style="text-align:center;"><h3 style="color:var(--c-blue);font-family:'Syne',sans-serif;">1,240</h3><p style="color:var(--c-slate-400);margin:0;font-size:0.85rem;">Monitored Livestock</p></div></div>
          <div class="dash-card"><div class="dash-card-body" style="text-align:center;"><h3 style="color:var(--c-amber);font-family:'Syne',sans-serif;">3</h3><p style="color:var(--c-slate-400);margin:0;font-size:0.85rem;">Pending Inspections</p></div></div>
        </div>
      </div>
      <!-- Agri Analytics -->
      <div class="panel-section" id="agri-reports">
        <div class="page-header-panel"><h2>Reports & Analytics</h2><p>Regional livestock and farm performance insights</p></div>
        <div class="dash-row">
          <div class="dash-card"><div class="dash-card-header"><span class="dash-card-title"><i class="bi bi-pie-chart-fill me-2 text-emerald"></i>Farm Types Distribution</span></div><div class="dash-card-body"><div class="chart-container"><canvas id="agri-farm-types-chart"></canvas></div></div></div>
          <div class="dash-card"><div class="dash-card-header"><span class="dash-card-title"><i class="bi bi-pie-chart-fill me-2" style="color:var(--c-blue);"></i>Livestock Population Distribution</span></div><div class="dash-card-body"><div class="chart-container"><canvas id="agri-livestock-pop-chart"></canvas></div></div></div>
        </div>
        <div class="dash-card"><div class="dash-card-header"><span class="dash-card-title"><i class="bi bi-graph-up me-2" style="color:var(--c-amber);"></i>Incident Types Over Time (Last 6 Months)</span></div><div class="dash-card-body"><div class="chart-container"><canvas id="agri-incidents-time-chart"></canvas></div></div></div>
        <div class="dash-row">
          <div class="dash-card"><div class="dash-card-header"><span class="dash-card-title"><i class="bi bi-bar-chart-fill me-2" style="color:var(--c-blue);"></i>Farm Registration Status</span></div><div class="dash-card-body"><div class="chart-container"><canvas id="agri-farm-status-chart"></canvas></div></div></div>
          <div class="dash-card"><div class="dash-card-header"><span class="dash-card-title"><i class="bi bi-graph-up-arrow me-2" style="color:var(--c-red);"></i>Public Reports Trend</span></div><div class="dash-card-body"><div class="chart-container"><canvas id="agri-public-reports-chart"></canvas></div></div></div>
        </div>
        <div class="dash-card"><div class="dash-card-header"><span class="dash-card-title"><i class="bi bi-geo-alt-fill me-2 text-emerald"></i>Regional Farm Distribution</span></div><div class="dash-card-body"><div class="chart-container"><canvas id="agri-regional-chart"></canvas></div></div></div>
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:16px;margin-top:20px;">
          <div class="dash-card"><div class="dash-card-header"><span class="dash-card-title"><i class="bi bi-file-earmark-text me-2"></i>Farm Inspection Report</span></div><div class="dash-card-body"><p style="font-size:0.85rem;color:var(--c-slate-400);">Comprehensive inspection statistics and compliance reports</p><button class="btn btn-panel btn-sm" onclick="showToast('PDF Report generated successfully!')"><i class="bi bi-download me-1"></i>Generate PDF</button></div></div>
          <div class="dash-card"><div class="dash-card-header"><span class="dash-card-title"><i class="bi bi-clipboard-data me-2"></i>Incident Analytics</span></div><div class="dash-card-body"><p style="font-size:0.85rem;color:var(--c-slate-400);">Detailed analysis of disease outbreaks and incidents</p><button class="btn btn-panel btn-sm" onclick="showToast('Excel Report generated successfully!')"><i class="bi bi-download me-1"></i>Generate Excel</button></div></div>
          <div class="dash-card"><div class="dash-card-header"><span class="dash-card-title"><i class="bi bi-people me-2"></i>Public Reports Summary</span></div><div class="dash-card-body"><p style="font-size:0.85rem;color:var(--c-slate-400);">Summary of public health reports and community feedback</p><button class="btn btn-panel btn-sm" onclick="showToast('Summary generated!')"><i class="bi bi-download me-1"></i>Generate Summary</button></div></div>
          <div class="dash-card"><div class="dash-card-header"><span class="dash-card-title"><i class="bi bi-map me-2"></i>Regional Statistics</span></div><div class="dash-card-body"><p style="font-size:0.85rem;color:var(--c-slate-400);">Regional livestock and farm statistics by province</p><button class="btn btn-panel btn-sm" onclick="showToast('Regional report generated!')"><i class="bi bi-download me-1"></i>Generate Regional</button></div></div>
        </div>
      </div>
      <!-- Agri Profile -->
            <div class="panel-section" id="agri-profile">
        <div class="page-header-panel"><h2>Profile & Security</h2><p>View and manage your account and work information</p></div>
        <div class="dash-card">
          <div class="dash-card-header">
            <span class="dash-card-title">👤 My Profile</span>
            <button class="btn btn-panel btn-sm" onclick="openProfileModal('agri')"><i class="bi bi-pencil me-1"></i>Edit Profile</button>
          </div>
          <div class="dash-card-body" id="agri-profile-view"></div>
        </div>
      </div>
    </div>
  </main>
</div>

<!-- =========== ADMIN PANEL =========== -->
<div class="page panel-page" id="page-admin-panel">
  <div class="sidebar-overlay" id="admin-overlay" onclick="closePanelSidebar('admin')"></div>
  <aside class="panel-sidebar" id="admin-sidebar">
    <div class="panel-sidebar-header">
      <div class="panel-sidebar-logo">Agri<span>Trace+</span></div>
      <div class="panel-sidebar-sub">Admin Panel</div>
    </div>
    <nav class="panel-nav">
      <div class="panel-nav-item active" onclick="showPanel('admin','dashboard')"><i class="bi bi-grid-1x2"></i> Admin Dashboard</div>
      <div class="panel-nav-item" onclick="showPanel('admin','users')"><i class="bi bi-people"></i> User Management</div>
      <div class="panel-nav-item" onclick="showPanel('admin','roles')"><i class="bi bi-shield-check"></i> Role & Permissions</div>
      <div class="panel-nav-item" onclick="showPanel('admin','config')"><i class="bi bi-gear"></i> System Config</div>
      <div class="panel-nav-item" onclick="showPanel('admin','data')"><i class="bi bi-database"></i> Data Management</div>
      <div class="panel-nav-item" onclick="showPanel('admin','geo')"><i class="bi bi-geo-alt"></i> Geo-Mapping</div>
      <div class="panel-nav-item" onclick="showPanel('admin','audit')"><i class="bi bi-lock"></i> Audit & Security</div>
      <div class="panel-nav-item" onclick="showPanel('admin','analytics')"><i class="bi bi-bar-chart-line"></i> Reports & Analytics</div>
      <div class="panel-nav-divider"></div>
      <div class="panel-nav-item logout" onclick="navigate('login')"><i class="bi bi-power"></i> Logout</div>
    </nav>
    <div class="panel-sidebar-footer">
      <div class="panel-user-info">
        <div class="panel-avatar">AD</div>
        <div><div class="panel-user-name">System Admin</div><div class="panel-user-role">Administrator</div></div>
      </div>
    </div>
  </aside>
  <main class="panel-main">
    <div class="panel-topbar">
      <div style="display:flex;align-items:center;gap:12px;">
        <button class="mobile-sidebar-toggle" onclick="openPanelSidebar('admin')"><i class="bi bi-list"></i></button>
        <span class="panel-topbar-title" id="admin-section-title">Admin Dashboard</span>
      </div>
      <div class="topbar-right">
        <button class="topbar-notif"><i class="bi bi-bell"></i><span class="notif-dot"></span></button>
        <div class="panel-avatar" style="width:32px;height:32px;font-size:0.8rem;">AD</div>
      </div>
    </div>
    <div class="panel-content">
      <!-- Admin Dashboard -->
      <div class="panel-section active" id="admin-dashboard">
        <div class="page-header-panel"><h2>System Overview</h2><p>Full system health and activity summary</p></div>
        <div class="stat-cards">
          <div class="stat-card"><div class="stat-icon-wrap stat-icon-green"><i class="bi bi-people"></i></div><div><div class="stat-num">0</div><div class="stat-lbl">Total Users</div></div></div>
          <div class="stat-card"><div class="stat-icon-wrap stat-icon-amber"><i class="bi bi-house-door"></i></div><div><div class="stat-num">0</div><div class="stat-lbl">Registered Farms</div></div></div>
          <div class="stat-card"><div class="stat-icon-wrap stat-icon-blue"><i class="bi bi-database"></i></div><div><div class="stat-num">0</div><div class="stat-lbl">Total Livestock</div></div></div>
          <div class="stat-card"><div class="stat-icon-wrap stat-icon-red"><i class="bi bi-exclamation-triangle"></i></div><div><div class="stat-num">0</div><div class="stat-lbl">Pending Reports</div></div></div>
        </div>
        <div class="dash-row">
          <div class="dash-card">
            <div class="dash-card-header"><span class="dash-card-title">System Activity</span></div>
            <div class="dash-card-body"><div class="chart-container"><canvas id="admin-activity-chart"></canvas></div></div>
          </div>
          <div class="dash-card">
            <div class="dash-card-header"><span class="dash-card-title">User Roles Distribution</span></div>
            <div class="dash-card-body"><div class="chart-container"><canvas id="admin-roles-chart"></canvas></div></div>
          </div>
        </div>
      </div>
      <!-- Admin Users -->
      <div class="panel-section" id="admin-users">
        <div class="page-header-panel"><h2>User Management</h2><p>Manage all registered users and their accounts</p></div>
        <div class="dash-card" style="margin-bottom:20px;">
          <div class="dash-card-header"><span class="dash-card-title">➕ Create New User</span></div>
          <div class="dash-card-body">
            <form onsubmit="addUser(event)">
              <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(150px,1fr));gap:14px;margin-bottom:16px;">
                <div class="panel-form-group"><label class="panel-form-label">First Name</label><input type="text" class="form-input panel-input no-icon" placeholder="First Name" required></div>
                <div class="panel-form-group"><label class="panel-form-label">Last Name</label><input type="text" class="form-input panel-input no-icon" placeholder="Last Name" required></div>
                <div class="panel-form-group"><label class="panel-form-label">Email</label><input type="email" class="form-input panel-input no-icon" placeholder="email@example.com" required></div>
                <div class="panel-form-group"><label class="panel-form-label">Role</label><select class="form-select panel-select" required><option>Farmer</option><option>Agriculture Official</option><option>Admin</option></select></div>
                <div class="panel-form-group"><label class="panel-form-label">Password</label><input type="password" class="form-input panel-input no-icon" placeholder="Temporary password" required></div>
              </div>
              <button type="submit" class="btn btn-panel" style="padding:11px 28px;"><i class="bi bi-person-plus me-2"></i>Create User</button>
            </form>
          </div>
        </div>
        <div class="dash-card">
          <div class="dash-card-header">
            <span class="dash-card-title">All Users</span>
            <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap;">
              <div class="search-wrap" style="max-width:220px;"><i class="bi bi-search"></i><input type="text" placeholder="Search users..." oninput="renderAdminUsers(this.value)"></div>
              <select class="form-select panel-select" style="width:auto;"><option>All Roles</option><option>Farmer</option><option>Official</option><option>Admin</option></select>
            </div>
          </div>
          <div class="dash-card-body">
            <div class="table-wrap"><table id="admin-users-table">
              <thead><tr><th>Name</th><th>Email</th><th>Role</th><th>Status</th><th>Joined</th><th>Actions</th></tr></thead>
              <tbody>
                <tr><td>Juan dela Cruz</td><td>farmer@agritrace.ph</td><td><span class="badge badge-green">Farmer</span></td><td><span class="badge badge-blue">Active</span></td><td>Jan 5, 2026</td><td><div style="display:flex;gap:4px;"><button class="btn btn-panel btn-sm" onclick="showToast('Editing Juan dela Cruz')">Edit</button><button class="btn btn-danger btn-sm" onclick="if(confirm('Delete user?'))showToast('User deleted')">Del</button></div></td></tr>
                <tr><td>Maria Reyes</td><td>official@agritrace.ph</td><td><span class="badge badge-purple">Official</span></td><td><span class="badge badge-blue">Active</span></td><td>Jan 8, 2026</td><td><div style="display:flex;gap:4px;"><button class="btn btn-panel btn-sm" onclick="showToast('Editing Maria Reyes')">Edit</button><button class="btn btn-danger btn-sm" onclick="if(confirm('Delete user?'))showToast('User deleted')">Del</button></div></td></tr>
                <tr><td>Pedro Santos</td><td>pedro@example.com</td><td><span class="badge badge-green">Farmer</span></td><td><span class="badge badge-amber">Pending</span></td><td>Mar 10, 2026</td><td><div style="display:flex;gap:4px;"><button class="btn btn-panel btn-sm" onclick="showToast('Reviewing Pedro Santos')">Review</button><button class="btn btn-danger btn-sm" onclick="if(confirm('Delete user?'))showToast('User deleted')">Del</button></div></td></tr>
                <tr><td>Ana Garcia</td><td>ana@example.com</td><td><span class="badge badge-green">Farmer</span></td><td><span class="badge badge-blue">Active</span></td><td>Feb 2, 2026</td><td><div style="display:flex;gap:4px;"><button class="btn btn-panel btn-sm" onclick="showToast('Editing Ana Garcia')">Edit</button><button class="btn btn-danger btn-sm" onclick="if(confirm('Delete user?'))showToast('User deleted')">Del</button></div></td></tr>
                <tr><td>System Admin</td><td>admin@agritrace.ph</td><td><span class="badge badge-amber">Admin</span></td><td><span class="badge badge-blue">Active</span></td><td>Jan 1, 2026</td><td><div style="display:flex;gap:4px;"><button class="btn btn-panel btn-sm" onclick="showToast('Editing Admin')">Edit</button></div></td></tr>
              </tbody>
            </table></div>
          </div>
        </div>
      </div>
      <!-- Admin Roles -->
      <div class="panel-section" id="admin-roles">
        <div class="page-header-panel"><h2>Role & Permissions</h2><p>Configure access levels for each role</p></div>
        <div class="dash-row">
          <div class="dash-card"><div class="dash-card-header"><span class="dash-card-title">Farmer Permissions</span></div><div class="dash-card-body">
            <div style="display:flex;flex-direction:column;gap:10px;">
              <label style="display:flex;align-items:center;gap:10px;"><input type="checkbox" checked style="accent-color:var(--c-emerald);"> View own farm data</label>
              <label style="display:flex;align-items:center;gap:10px;"><input type="checkbox" checked style="accent-color:var(--c-emerald);"> Manage livestock records</label>
              <label style="display:flex;align-items:center;gap:10px;"><input type="checkbox" checked style="accent-color:var(--c-emerald);"> Submit incident reports</label>
              <label style="display:flex;align-items:center;gap:10px;"><input type="checkbox" style="accent-color:var(--c-emerald);"> View other farm data</label>
              <label style="display:flex;align-items:center;gap:10px;"><input type="checkbox" style="accent-color:var(--c-emerald);"> Manage users</label>
            </div>
          </div></div>
          <div class="dash-card"><div class="dash-card-header"><span class="dash-card-title">Official Permissions</span></div><div class="dash-card-body">
            <div style="display:flex;flex-direction:column;gap:10px;">
              <label style="display:flex;align-items:center;gap:10px;"><input type="checkbox" checked style="accent-color:var(--c-emerald);"> View regional farm data</label>
              <label style="display:flex;align-items:center;gap:10px;"><input type="checkbox" checked style="accent-color:var(--c-emerald);"> Manage incident reports</label>
              <label style="display:flex;align-items:center;gap:10px;"><input type="checkbox" checked style="accent-color:var(--c-emerald);"> Schedule inspections</label>
              <label style="display:flex;align-items:center;gap:10px;"><input type="checkbox" checked style="accent-color:var(--c-emerald);"> Approve farm registrations</label>
              <label style="display:flex;align-items:center;gap:10px;"><input type="checkbox" style="accent-color:var(--c-emerald);"> Manage users</label>
            </div>
          </div></div>
        </div>
        <button class="btn btn-panel" onclick="showToast('Permissions saved successfully!')">Save Permissions</button>
      </div>
      <!-- Admin Config -->
      <div class="panel-section" id="admin-config">
        <div class="page-header-panel"><h2>System Configuration</h2><p>Configure application-wide settings and integrations</p></div>
        <div class="dash-card" style="margin-bottom:20px;">
          <div class="dash-card-header"><span class="dash-card-title">⚙️ General Settings</span></div>
          <div class="dash-card-body">
            <form onsubmit="handlePanelForm(event,'Configuration saved successfully!')">
              <div class="panel-form-row" style="margin-bottom:16px;">
                <div class="panel-form-group"><label class="panel-form-label">System / Site Name</label><input type="text" class="form-input panel-input no-icon" value="AgriTrace+"></div>
                <div class="panel-form-group"><label class="panel-form-label">Default Region</label><select class="form-select panel-select"><option>All Regions</option><option>NCR</option><option>Region I</option><option>Region III</option><option>Region IV-A</option><option>Region IV-B</option><option>CAR</option></select></div>
              </div>
              <div class="panel-form-row" style="margin-bottom:16px;">
                <div class="panel-form-group"><label class="panel-form-label">Session Timeout (minutes)</label><input type="number" class="form-input panel-input no-icon" value="30"></div>
                <div class="panel-form-group"><label class="panel-form-label">Max Login Attempts</label><input type="number" class="form-input panel-input no-icon" value="5"></div>
              </div>
              <div class="panel-form-row" style="margin-bottom:20px;">
                <div class="panel-form-group"><label class="panel-form-label">Backup Frequency (days)</label><input type="number" class="form-input panel-input no-icon" value="7"></div>
                <div class="panel-form-group"><label class="panel-form-label">Min Password Length</label><input type="number" class="form-input panel-input no-icon" value="8"></div>
              </div>
              <div style="border-top:1px solid var(--c-slate-200);padding-top:20px;margin-bottom:16px;"><p style="font-weight:700;color:var(--c-forest);margin-bottom:14px;">📧 Email (SMTP) Settings</p></div>
              <div class="panel-form-row" style="margin-bottom:16px;">
                <div class="panel-form-group"><label class="panel-form-label">SMTP Host</label><input type="text" class="form-input panel-input no-icon" placeholder="e.g. smtp.gmail.com"></div>
                <div class="panel-form-group"><label class="panel-form-label">SMTP Port</label><input type="number" class="form-input panel-input no-icon" value="587"></div>
              </div>
              <div class="panel-form-row" style="margin-bottom:16px;">
                <div class="panel-form-group"><label class="panel-form-label">SMTP Username</label><input type="email" class="form-input panel-input no-icon" placeholder="noreply@agritrace.ph"></div>
                <div class="panel-form-group"><label class="panel-form-label">SMTP Password</label><input type="password" class="form-input panel-input no-icon" placeholder="App password"></div>
              </div>
              <div class="panel-form-group" style="margin-bottom:20px;"><label class="panel-form-label">From Address</label><input type="email" class="form-input panel-input no-icon" placeholder="noreply@agritrace.ph"></div>
              <div style="border-top:1px solid var(--c-slate-200);padding-top:20px;margin-bottom:16px;"><p style="font-weight:700;color:var(--c-forest);margin-bottom:14px;">📱 SMS Settings (OTP)</p></div>
              <div class="panel-form-row" style="margin-bottom:20px;">
                <div class="panel-form-group"><label class="panel-form-label">SMS Provider</label><select class="form-select panel-select"><option>Semaphore PH</option><option>Twilio</option><option>Vonage</option></select></div>
                <div class="panel-form-group"><label class="panel-form-label">API Key</label><input type="text" class="form-input panel-input no-icon" placeholder="Enter provider API key"></div>
              </div>
              <button type="submit" class="btn btn-panel" style="padding:13px 32px;">Save Configuration</button>
            </form>
          </div>
        </div>
        <div class="dash-card">
          <div class="dash-card-header"><span class="dash-card-title">🖥️ System Status</span></div>
          <div class="dash-card-body">
            <div style="display:flex;flex-direction:column;gap:10px;">
              <div style="display:flex;justify-content:space-between;align-items:center;padding:12px 16px;background:var(--c-slate-50);border-radius:10px;"><span style="font-size:0.88rem;font-weight:500;">System Health</span><span class="badge badge-green"><i class="bi bi-check-circle me-1"></i>Good</span></div>
              <div style="display:flex;justify-content:space-between;align-items:center;padding:12px 16px;background:var(--c-slate-50);border-radius:10px;"><span style="font-size:0.88rem;font-weight:500;">Database Status</span><span class="badge badge-green"><i class="bi bi-check-circle me-1"></i>Online</span></div>
              <div style="display:flex;justify-content:space-between;align-items:center;padding:12px 16px;background:var(--c-slate-50);border-radius:10px;"><span style="font-size:0.88rem;font-weight:500;">Pending Backups</span><span class="badge badge-amber">0</span></div>
              <div style="display:flex;justify-content:space-between;align-items:center;padding:12px 16px;background:var(--c-slate-50);border-radius:10px;"><span style="font-size:0.88rem;font-weight:500;">Storage Used</span><span style="font-size:0.82rem;color:var(--c-slate-500);">2.4 GB / 50 GB</span></div>
              <div style="display:flex;justify-content:space-between;align-items:center;padding:12px 16px;background:var(--c-slate-50);border-radius:10px;"><span style="font-size:0.88rem;font-weight:500;">Last Backup</span><span style="font-size:0.82rem;color:var(--c-slate-500);">Mar 22, 2026 02:00 AM</span></div>
            </div>
          </div>
        </div>
      </div>
      <!-- Admin Geo -->
      <div class="panel-section" id="admin-geo">
        <div class="page-header-panel"><h2>Geo-Mapping Control</h2><p>Farm locations and livestock density across the country</p></div>
        <div class="dash-card" style="margin-bottom:16px;">
          <div class="dash-card-header"><span class="dash-card-title"><i class="bi bi-map me-2"></i>Farm Locations & Livestock Density</span></div>
          <div class="dash-card-body"><div id="admin-map"></div></div>
        </div>
        <div class="dash-card" style="margin-bottom:16px;">
          <div class="dash-card-header"><span class="dash-card-title"><i class="bi bi-info-circle me-2"></i>Map Legend</span></div>
          <div class="dash-card-body">
            <div style="display:flex;gap:20px;flex-wrap:wrap;">
              <div style="display:flex;align-items:center;gap:8px;"><div style="width:18px;height:18px;background:#10b981;border-radius:50%;flex-shrink:0;"></div><span style="font-size:0.85rem;"><strong>Green:</strong> Low Density (10–20 heads)</span></div>
              <div style="display:flex;align-items:center;gap:8px;"><div style="width:18px;height:18px;background:#f59e0b;border-radius:50%;flex-shrink:0;"></div><span style="font-size:0.85rem;"><strong>Yellow:</strong> Medium (20–30 heads)</span></div>
              <div style="display:flex;align-items:center;gap:8px;"><div style="width:18px;height:18px;background:#ef4444;border-radius:50%;flex-shrink:0;"></div><span style="font-size:0.85rem;"><strong>Red:</strong> High Density (&gt;30 heads)</span></div>
              <div style="display:flex;align-items:center;gap:8px;"><div style="width:18px;height:18px;background:#8b5cf6;border-radius:50%;flex-shrink:0;"></div><span style="font-size:0.85rem;"><strong>Purple:</strong> Inspection Site</span></div>
              <div style="display:flex;align-items:center;gap:8px;"><div style="width:18px;height:18px;background:#3b82f6;border-radius:50%;flex-shrink:0;"></div><span style="font-size:0.85rem;"><strong>Blue:</strong> Incident Alert</span></div>
            </div>
          </div>
        </div>
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:16px;">
          <div class="dash-card"><div class="dash-card-body" style="text-align:center;"><h3 style="color:var(--c-emerald);font-family:'Syne',sans-serif;">0</h3><p style="color:var(--c-slate-400);margin:0;font-size:0.85rem;">Total Farms</p></div></div>
          <div class="dash-card"><div class="dash-card-body" style="text-align:center;"><h3 style="color:var(--c-blue);font-family:'Syne',sans-serif;">0</h3><p style="color:var(--c-slate-400);margin:0;font-size:0.85rem;">Total Livestock</p></div></div>
          <div class="dash-card"><div class="dash-card-body" style="text-align:center;"><h3 style="color:var(--c-amber);font-family:'Syne',sans-serif;">0</h3><p style="color:var(--c-slate-400);margin:0;font-size:0.85rem;">Avg. per Farm</p></div></div>
          <div class="dash-card"><div class="dash-card-body" style="text-align:center;"><h3 style="color:var(--c-red);font-family:'Syne',sans-serif;">0</h3><p style="color:var(--c-slate-400);margin:0;font-size:0.85rem;">Active Incidents</p></div></div>
        </div>
      </div>
      <!-- Admin Audit -->
      <div class="panel-section" id="admin-audit">
        <div class="page-header-panel"><h2>Audit & Security</h2><p>Security settings and system activity logs</p></div>
        <div class="dash-card" style="margin-bottom:20px;">
          <div class="dash-card-header"><span class="dash-card-title">🔒 Security Settings</span></div>
          <div class="dash-card-body">
            <form onsubmit="handlePanelForm(event,'Security settings saved successfully!')">
              <div class="panel-form-row" style="margin-bottom:16px;">
                <div class="panel-form-group"><label class="panel-form-label">Max Login Attempts</label><input type="number" class="form-input panel-input no-icon" value="5"></div>
                <div class="panel-form-group"><label class="panel-form-label">Lockout Duration (minutes)</label><input type="number" class="form-input panel-input no-icon" value="15"></div>
              </div>
              <div class="panel-form-row" style="margin-bottom:16px;">
                <div class="panel-form-group"><label class="panel-form-label">Min Password Length</label><input type="number" class="form-input panel-input no-icon" value="8"></div>
                <div class="panel-form-group"><label class="panel-form-label">Password Expiry (days)</label><input type="number" class="form-input panel-input no-icon" value="90"></div>
              </div>
              <div class="panel-form-row" style="margin-bottom:20px;">
                <div class="panel-form-group"><label class="panel-form-label">Session Timeout (minutes)</label><input type="number" class="form-input panel-input no-icon" value="30"></div>
                <div class="panel-form-group"><label class="panel-form-label">2FA Required</label><select class="form-select panel-select"><option>Disabled</option><option>Optional</option><option>Required for Admins</option><option>Required for All</option></select></div>
              </div>
              <button type="submit" class="btn btn-panel" style="padding:13px 32px;">Save Security Settings</button>
            </form>
          </div>
        </div>
        <div class="dash-card">
          <div class="dash-card-header">
            <span class="dash-card-title">📋 Audit Log</span>
            <button class="btn btn-outline btn-sm" onclick="showToast('Audit log exported!')"><i class="bi bi-download me-1"></i>Export</button>
          </div>
          <div class="dash-card-body">
            <div class="filter-bar" style="margin-bottom:16px;">
              <select class="form-select panel-select" style="width:auto;"><option>All Actions</option><option>Login</option><option>Login Failed</option><option>Created</option><option>Updated</option><option>Deleted</option></select>
              <select class="form-select panel-select" style="width:auto;"><option>All Users</option><option>Farmers</option><option>Officials</option><option>Admins</option></select>
              <input type="date" class="form-input panel-input no-icon" style="width:auto;padding:9px 14px;">
            </div>
            <div class="table-wrap"><table>
              <thead><tr><th>Timestamp</th><th>User</th><th>Action</th><th>Record Type</th><th>Description</th><th>IP Address</th><th>Status</th></tr></thead>
              <tbody>
                <tr><td>2026-03-22 08:32</td><td>farmer@agritrace.ph</td><td>Login</td><td>—</td><td>Successful login</td><td>192.168.1.1</td><td><span class="badge badge-green">Success</span></td></tr>
                <tr><td>2026-03-22 08:15</td><td>official@agritrace.ph</td><td>Updated</td><td>Incident</td><td>Updated incident #003</td><td>10.0.0.5</td><td><span class="badge badge-green">Success</span></td></tr>
                <tr><td>2026-03-21 17:44</td><td>unknown</td><td>Login Failed</td><td>—</td><td>Invalid credentials (3rd attempt)</td><td>203.177.90.12</td><td><span class="badge badge-red">Failed</span></td></tr>
                <tr><td>2026-03-21 14:20</td><td>admin@agritrace.ph</td><td>Created</td><td>User</td><td>Created new user: Pedro Santos</td><td>192.168.1.10</td><td><span class="badge badge-green">Success</span></td></tr>
                <tr><td>2026-03-21 10:05</td><td>official@agritrace.ph</td><td>Login</td><td>—</td><td>Successful login</td><td>10.0.0.5</td><td><span class="badge badge-green">Success</span></td></tr>
                <tr><td>2026-03-21 09:30</td><td>admin@agritrace.ph</td><td>Deleted</td><td>User</td><td>Deleted inactive user account</td><td>192.168.1.10</td><td><span class="badge badge-green">Success</span></td></tr>
                <tr><td>2026-03-20 16:15</td><td>farmer@agritrace.ph</td><td>Updated</td><td>Farm</td><td>Updated farm registration details</td><td>192.168.1.1</td><td><span class="badge badge-green">Success</span></td></tr>
                <tr><td>2026-03-20 11:22</td><td>unknown</td><td>Login Failed</td><td>—</td><td>Account locked — too many attempts</td><td>203.177.90.12</td><td><span class="badge badge-red">Blocked</span></td></tr>
              </tbody>
            </table></div>
          </div>
        </div>
      </div>
      <!-- Admin Data Management -->
      <div class="panel-section" id="admin-data">
        <div class="page-header-panel"><h2>Data Management</h2><p>CRUD operations, import/export, and activity logs</p></div>
        <div class="dash-card" style="margin-bottom:20px;">
          <div class="dash-card-header">
            <span class="dash-card-title"><i class="bi bi-table me-2"></i>Records Management</span>
            <button class="btn btn-panel btn-sm" onclick="showToast('Create record modal opened')"><i class="bi bi-plus-circle me-1"></i>Create New Record</button>
          </div>
          <div class="dash-card-body">
            <div class="filter-bar" style="margin-bottom:16px;">
              <div class="search-wrap"><i class="bi bi-search"></i><input type="text" id="admin-data-search" placeholder="Search records..." oninput="renderAdminDataTable(this.value)"></div>
              <select class="form-select panel-select" style="width:auto;" id="categoryFilter"><option value="">All Categories</option><option value="farm">Farm Records</option><option value="livestock">Livestock Records</option><option value="incident">Incident Records</option><option value="user">User Records</option></select>
              <select class="form-select panel-select" style="width:auto;" id="statusFilter"><option value="">All Status</option><option value="active">Active</option><option value="pending">Pending</option><option value="archived">Archived</option></select>
              <select class="form-select panel-select" style="width:auto;"><option>Newest First</option><option>Oldest First</option></select>
            </div>
            <div class="table-wrap"><table id="admin-data-table">
              <thead><tr><th><input type="checkbox" onclick="toggleSelectAll(this)" style="accent-color:var(--c-emerald);"></th><th>ID</th><th>Category</th><th>Name</th><th>Status</th><th>Date Created</th><th>Actions</th></tr></thead>
              <tbody>
                <tr><td><input type="checkbox" class="row-cb" style="accent-color:var(--c-emerald);"></td><td>#001</td><td>Farm</td><td>Green Valley Farm</td><td><span class="badge badge-green">Active</span></td><td>Jan 10, 2026</td><td><div style="display:flex;gap:4px;"><button class="btn btn-panel btn-sm" onclick="showToast('Viewing record #001')">View</button><button class="btn btn-outline btn-sm" onclick="showToast('Editing record #001')">Edit</button><button class="btn btn-danger btn-sm" onclick="if(confirm('Delete?')) showToast('Record deleted')">Del</button></div></td></tr>
                <tr><td><input type="checkbox" class="row-cb" style="accent-color:var(--c-emerald);"></td><td>#002</td><td>Livestock</td><td>Cattle – Brahman</td><td><span class="badge badge-green">Active</span></td><td>Jan 12, 2026</td><td><div style="display:flex;gap:4px;"><button class="btn btn-panel btn-sm" onclick="showToast('Viewing record #002')">View</button><button class="btn btn-outline btn-sm" onclick="showToast('Editing record #002')">Edit</button><button class="btn btn-danger btn-sm" onclick="if(confirm('Delete?')) showToast('Record deleted')">Del</button></div></td></tr>
                <tr><td><input type="checkbox" class="row-cb" style="accent-color:var(--c-emerald);"></td><td>#003</td><td>Incident</td><td>Avian Flu Report</td><td><span class="badge badge-amber">Pending</span></td><td>Mar 15, 2026</td><td><div style="display:flex;gap:4px;"><button class="btn btn-panel btn-sm" onclick="showToast('Viewing record #003')">View</button><button class="btn btn-outline btn-sm" onclick="showToast('Editing record #003')">Edit</button><button class="btn btn-danger btn-sm" onclick="if(confirm('Delete?')) showToast('Record deleted')">Del</button></div></td></tr>
                <tr><td><input type="checkbox" class="row-cb" style="accent-color:var(--c-emerald);"></td><td>#004</td><td>User</td><td>Juan dela Cruz</td><td><span class="badge badge-blue">Active</span></td><td>Jan 5, 2026</td><td><div style="display:flex;gap:4px;"><button class="btn btn-panel btn-sm" onclick="showToast('Viewing record #004')">View</button><button class="btn btn-outline btn-sm" onclick="showToast('Editing record #004')">Edit</button><button class="btn btn-danger btn-sm" onclick="if(confirm('Delete?')) showToast('Record deleted')">Del</button></div></td></tr>
              </tbody>
            </table></div>
            <div style="display:flex;justify-content:space-between;align-items:center;margin-top:14px;flex-wrap:wrap;gap:8px;">
              <div style="display:flex;gap:8px;"><button class="btn btn-danger btn-sm" onclick="bulkDelete()"><i class="bi bi-trash me-1"></i>Delete Selected</button><button class="btn btn-outline btn-sm" onclick="bulkArchive()"><i class="bi bi-archive me-1"></i>Archive Selected</button></div>
              <p style="font-size:0.82rem;color:var(--c-slate-400);margin:0;">Showing 4 of 4 records</p>
            </div>
          </div>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:20px;">
          <div class="dash-card"><div class="dash-card-header"><span class="dash-card-title"><i class="bi bi-upload me-2 text-emerald"></i>Import Data</span></div><div class="dash-card-body">
            <p style="font-size:0.85rem;color:var(--c-slate-400);">Upload CSV or Excel files to import records in bulk</p>
            <div class="panel-form-group" style="margin-bottom:12px;"><label class="panel-form-label">Select File</label><input type="file" class="form-input panel-input no-icon" accept=".csv,.xlsx,.xls" id="import-file"></div>
            <div class="panel-form-group" style="margin-bottom:16px;"><label class="panel-form-label">Data Type</label><select class="form-select panel-select"><option>Farm Records</option><option>Livestock Records</option><option>User Records</option><option>Incident Records</option></select></div>
            <button class="btn btn-panel" style="width:100%;" onclick="showToast('Data imported successfully! 50 records added.')"><i class="bi bi-upload me-2"></i>Upload & Import</button>
            <p style="font-size:0.78rem;color:var(--c-slate-400);margin-top:8px;"><i class="bi bi-info-circle me-1"></i><a href="#" style="color:var(--c-emerald);" onclick="showToast('CSV template downloaded!');return false;">Download CSV Template</a></p>
          </div></div>
          <div class="dash-card"><div class="dash-card-header"><span class="dash-card-title"><i class="bi bi-download me-2" style="color:var(--c-blue);"></i>Export Data</span></div><div class="dash-card-body">
            <p style="font-size:0.85rem;color:var(--c-slate-400);">Export records to CSV, Excel, or PDF format</p>
            <div class="panel-form-group" style="margin-bottom:12px;"><label class="panel-form-label">Data Type</label><select class="form-select panel-select" id="exportDataType"><option>All Records</option><option>Farm Records</option><option>Livestock Records</option><option>User Records</option><option>Incident Records</option></select></div>
            <div class="panel-form-group" style="margin-bottom:16px;"><label class="panel-form-label">Export Format</label><select class="form-select panel-select" id="exportFormat"><option>CSV (.csv)</option><option>Excel (.xlsx)</option><option>PDF (.pdf)</option></select></div>
            <button class="btn btn-panel" style="width:100%;background:linear-gradient(135deg,#3b82f6,#2563eb);" onclick="showToast('Data exported successfully!')"><i class="bi bi-download me-2"></i>Export Data</button>
          </div></div>
        </div>
        <div class="dash-card" style="margin-bottom:16px;">
          <div class="dash-card-header"><span class="dash-card-title"><i class="bi bi-clock-history me-2" style="color:var(--c-blue);"></i>Activity Logs & Audit Trail</span></div>
          <div class="dash-card-body">
            <div class="filter-bar" style="margin-bottom:16px;">
              <select class="form-select panel-select" style="width:auto;"><option>All Actions</option><option>Created</option><option>Updated</option><option>Deleted</option><option>Login</option><option>Logout</option></select>
              <input type="date" class="form-input panel-input no-icon" style="width:auto;padding:10px 14px;">
              <input type="date" class="form-input panel-input no-icon" style="width:auto;padding:10px 14px;">
            </div>
            <div class="table-wrap"><table>
              <thead><tr><th>Timestamp</th><th>User</th><th>Action</th><th>Record Type</th><th>Description</th><th>IP Address</th></tr></thead>
              <tbody>
                <tr><td>2026-03-22 08:32</td><td>farmer@agritrace.ph</td><td>Login</td><td>—</td><td>User logged in</td><td>192.168.1.1</td></tr>
                <tr><td>2026-03-22 08:15</td><td>official@agritrace.ph</td><td>Updated</td><td>Incident</td><td>Updated incident #003 status</td><td>10.0.0.5</td></tr>
                <tr><td>2026-03-21 17:44</td><td>unknown</td><td>Login Failed</td><td>—</td><td>Invalid credentials</td><td>203.177.90.12</td></tr>
                <tr><td>2026-03-21 14:20</td><td>admin@agritrace.ph</td><td>Created</td><td>User</td><td>Created new user account</td><td>192.168.1.10</td></tr>
              </tbody>
            </table></div>
            <div style="text-align:right;margin-top:12px;"><button class="btn btn-outline btn-sm" onclick="showToast('Audit log exported!')"><i class="bi bi-download me-1"></i>Export Audit Log</button></div>
          </div>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
          <div class="dash-card"><div class="dash-card-header"><span class="dash-card-title"><i class="bi bi-hdd me-2 text-emerald"></i>Database Backup</span></div><div class="dash-card-body"><p style="font-size:0.85rem;color:var(--c-slate-400);">Create a backup of all tables as a downloadable SQL file.</p><button class="btn btn-panel" style="width:100%;" onclick="showToast('Database backup created successfully!')"><i class="bi bi-cloud-download me-2"></i>Create Backup</button></div></div>
          <div class="dash-card"><div class="dash-card-header"><span class="dash-card-title"><i class="bi bi-arrow-clockwise me-2" style="color:var(--c-amber);"></i>Restore from Backup</span></div><div class="dash-card-body"><p style="font-size:0.85rem;color:var(--c-slate-400);">Upload a .sql backup file to restore data.</p><input type="file" class="form-input panel-input no-icon" accept=".sql" style="margin-bottom:10px;"><button class="btn btn-danger" style="width:100%;border-radius:10px;" onclick="if(confirm('This will overwrite existing data. Continue?')) showToast('Database restored successfully!')"><i class="bi bi-arrow-clockwise me-2"></i>Restore Backup</button></div></div>
        </div>
      </div>

      <!-- Admin Analytics -->
      <div class="panel-section" id="admin-analytics">
        <div class="page-header-panel"><h2>Reports & Analytics</h2><p>System-wide analytics and performance reporting</p></div>
        <div class="dash-row">
          <div class="dash-card"><div class="dash-card-header"><span class="dash-card-title"><i class="bi bi-pie-chart-fill me-2 text-emerald"></i>User Role Distribution</span></div><div class="dash-card-body"><div class="chart-container"><canvas id="admin-roles-pie-chart"></canvas></div></div></div>
          <div class="dash-card"><div class="dash-card-header"><span class="dash-card-title"><i class="bi bi-pie-chart-fill me-2" style="color:var(--c-blue);"></i>Farm Types Distribution</span></div><div class="dash-card-body"><div class="chart-container"><canvas id="admin-farm-types-chart"></canvas></div></div></div>
        </div>
        <div class="dash-card"><div class="dash-card-header"><span class="dash-card-title"><i class="bi bi-graph-up me-2" style="color:var(--c-blue);"></i>User Registrations Over Time (Last 6 Months)</span></div><div class="dash-card-body"><div class="chart-container"><canvas id="admin-reg-chart"></canvas></div></div></div>
        <div class="dash-row">
          <div class="dash-card"><div class="dash-card-header"><span class="dash-card-title"><i class="bi bi-bar-chart-fill me-2" style="color:var(--c-amber);"></i>Livestock Population by Type</span></div><div class="dash-card-body"><div class="chart-container"><canvas id="admin-livestock-chart"></canvas></div></div></div>
          <div class="dash-card"><div class="dash-card-header"><span class="dash-card-title"><i class="bi bi-pie-chart-fill me-2" style="color:var(--c-red);"></i>Incident Status Overview</span></div><div class="dash-card-body"><div class="chart-container"><canvas id="admin-incident-status-chart"></canvas></div></div></div>
        </div>
        <div class="dash-card"><div class="dash-card-header"><span class="dash-card-title"><i class="bi bi-activity me-2 text-emerald"></i>System Activity Over Time</span></div><div class="dash-card-body"><div class="chart-container"><canvas id="admin-system-activity-chart"></canvas></div></div></div>
        <div class="dash-row">
          <div class="dash-card"><div class="dash-card-header"><span class="dash-card-title"><i class="bi bi-geo-alt-fill me-2" style="color:var(--c-blue);"></i>Regional User Distribution</span></div><div class="dash-card-body"><div class="chart-container"><canvas id="admin-regional-chart"></canvas></div></div></div>
          <div class="dash-card"><div class="dash-card-header"><span class="dash-card-title"><i class="bi bi-clipboard-check me-2 text-emerald"></i>Farm Registration Status</span></div><div class="dash-card-body"><div class="chart-container"><canvas id="admin-farm-status-chart"></canvas></div></div></div>
        </div>
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:16px;margin-top:8px;">
          <div class="dash-card"><div class="dash-card-header"><span class="dash-card-title"><i class="bi bi-people me-2"></i>User Activity Report</span></div><div class="dash-card-body"><p style="font-size:0.85rem;color:var(--c-slate-400);">Comprehensive user login and activity statistics</p><button class="btn btn-panel btn-sm" onclick="showToast('User Activity Report generated!')"><i class="bi bi-download me-1"></i>Generate PDF</button></div></div>
          <div class="dash-card"><div class="dash-card-header"><span class="dash-card-title"><i class="bi bi-house-door me-2"></i>Farm & Livestock Stats</span></div><div class="dash-card-body"><p style="font-size:0.85rem;color:var(--c-slate-400);">View livestock registration and farm trends</p><button class="btn btn-panel btn-sm" onclick="showToast('Farm Statistics Report generated!')"><i class="bi bi-download me-1"></i>Generate Excel</button></div></div>
          <div class="dash-card"><div class="dash-card-header"><span class="dash-card-title"><i class="bi bi-shield-check me-2"></i>Security Audit Report</span></div><div class="dash-card-body"><p style="font-size:0.85rem;color:var(--c-slate-400);">System security logs and audit trail</p><button class="btn btn-panel btn-sm" onclick="showToast('Security Audit Report generated!')"><i class="bi bi-download me-1"></i>Generate Audit</button></div></div>
          <div class="dash-card"><div class="dash-card-header"><span class="dash-card-title"><i class="bi bi-database me-2"></i>System Performance</span></div><div class="dash-card-body"><p style="font-size:0.85rem;color:var(--c-slate-400);">Database and system performance metrics</p><button class="btn btn-panel btn-sm" onclick="showToast('Performance Report generated!')"><i class="bi bi-download me-1"></i>Generate Report</button></div></div>
        </div>
      </div>
    </div>
  </main>
</div>


<!-- =========== PROFILE EDIT MODAL =========== -->
<div class="modal-overlay" id="profile-modal">
  <div class="modal-box" style="max-width:640px; max-height:92vh; overflow-y:auto;">
    <button class="modal-close" onclick="closeProfileModal()"><i class="bi bi-x-lg"></i></button>
    <h3 id="profile-modal-title">Edit Profile</h3>
    <div id="profile-modal-body"><!-- dynamic --></div>
    <div style="margin-top:20px; display:flex; gap:10px; justify-content:flex-end; padding:0 0 4px;">
      <button class="btn btn-outline btn-sm" onclick="closeProfileModal()">Cancel</button>
      <button class="btn btn-panel" style="padding:11px 28px;" onclick="saveProfileModal()"><i class="bi bi-check-lg me-1"></i>Save Changes</button>
    </div>
  </div>
</div>

<!-- =========== RECORD EDIT MODAL =========== -->
<div class="modal-overlay" id="record-modal">
  <div class="modal-box" style="max-width:560px;">
    <button class="modal-close" onclick="closeModal('record-modal')"><i class="bi bi-x-lg"></i></button>
    <h3 id="record-modal-title">Record</h3>
    <div id="record-modal-body"></div>
    <div style="margin-top:20px; display:flex; gap:10px; justify-content:flex-end;">
      <button class="btn btn-outline btn-sm" onclick="closeModal('record-modal')">Cancel</button>
      <button class="btn btn-panel" id="record-modal-save" onclick="saveModalRecord()">Save</button>
    </div>
  </div>
</div>

<!-- =========== CONFIRM MODAL =========== -->
<div class="modal-overlay" id="confirm-modal">
  <div class="modal-box" style="max-width:400px;">
    <h3 id="confirm-modal-title">Confirm</h3>
    <p id="confirm-modal-msg" style="color:var(--c-slate-600); font-size:0.92rem; margin-bottom:20px;"></p>
    <div style="display:flex; gap:10px; justify-content:flex-end;">
      <button class="btn btn-outline btn-sm" onclick="closeModal('confirm-modal')">Cancel</button>
      <button class="btn btn-danger btn-sm" id="confirm-modal-ok">Delete</button>
    </div>
  </div>
</div>

<!-- =========== TERMS MODAL =========== -->
<div class="modal-overlay" id="terms-modal">
  <div class="modal-box">
    <button class="modal-close" onclick="closeTermsModal()"><i class="bi bi-x-lg"></i></button>
    <h3>Terms and Conditions</h3>
    <div class="modal-terms">
      <p><strong>1. Acceptance of Terms:</strong> By accessing and using AgriTrace+, you accept and agree to be bound by the terms and provisions of this agreement.</p>
      <p><strong>2. Use License:</strong> Permission is granted to temporarily use the materials on AgriTrace+ for personal, non-commercial transitory viewing only.</p>
      <p><strong>3. Data Privacy:</strong> We are committed to protecting your personal data in accordance with applicable Philippine data privacy laws (RA 10173).</p>
      <p><strong>4. Disclaimer:</strong> The materials on AgriTrace+ are provided on an 'as is' basis. AgriTrace+ makes no warranties, expressed or implied.</p>
      <p><strong>5. Limitations:</strong> In no event shall AgriTrace+ or its suppliers be liable for any damages arising out of the use or inability to use the materials on this platform.</p>
      <p><strong>6. Governing Law:</strong> These terms and conditions are governed by and construed in accordance with the laws of the Philippines.</p>
    </div>
    <div class="modal-footer">
      <div class="check-group" style="margin-bottom:16px;">
        <input type="checkbox" id="modal-agree" onchange="document.getElementById('modal-confirm-btn').disabled = !this.checked;">
        <label for="modal-agree" style="color:var(--c-slate-700);">I have read and agree to the terms and conditions</label>
      </div>
      <button class="btn-modal-confirm" id="modal-confirm-btn" disabled onclick="confirmTerms()">Confirm & Accept</button>
    </div>
  </div>
</div>

<!-- =========== TOAST =========== -->
<div id="toast" style="position:fixed;bottom:28px;right:28px;z-index:9999;display:none;">
  <div style="background:var(--c-forest);color:#fff;padding:14px 22px;border-radius:12px;box-shadow:0 8px 28px rgba(0,0,0,0.25);display:flex;align-items:center;gap:10px;font-size:0.9rem;font-weight:500;max-width:340px;animation:fadeIn 0.3s ease;">
    <i class="bi bi-check-circle-fill" style="color:var(--c-emerald);font-size:1.1rem;"></i>
    <span id="toast-msg">Action completed</span>
  </div>
</div>
  
  <!-- Updated JavaScript with Supabase API calls -->
  <script>
  // Updated DB object to use PHP API
  const DB = {
      async getAll(table) {
          const res = await fetch(`api.php/${table}`);
          return await res.json();
      },
      async getById(table, id) {
          const res = await fetch(`api.php/${table}/${id}`);
          return await res.json();
      },
      async insert(table, data) {
          const res = await fetch(`api.php/${table}`, {
              method: 'POST',
              headers: {'Content-Type': 'application/json'},
              body: JSON.stringify(data)
          });
          return await res.json();
      },
      async update(table, id, data) {
          const res = await fetch(`api.php/${table}/${id}`, {
              method: 'PUT',
              headers: {'Content-Type': 'application/json'},
              body: JSON.stringify(data)
          });
          return await res.json();
      },
      async delete(table, id) {
          const res = await fetch(`api.php/${table}/${id}`, {method: 'DELETE'});
          return await res.json();
      },
      async query(table, fn) {
          const all = await this.getAll(table);
          return all.filter(fn);
      }
  };

  // Updated auth functions
  async function handleLogin(e) {
      e.preventDefault();
      const email = document.getElementById('login-email').value.trim().toLowerCase();
      const pw = document.getElementById('login-password').value;
      
      try {
          const res = await fetch('api.php/login', {
              method: 'POST',
              headers: {'Content-Type': 'application/json'},
              body:              body: JSON.stringify({email, password: pw})
          });
          
          const data = await res.json();
          
          if (!res.ok) {
              document.getElementById('login-error').innerHTML = '<i class="bi bi-x-circle-fill me-2"></i>' + data.error;
              document.getElementById('login-error').classList.remove('hidden');
              return;
          }
          
          // Store session info
          sessionStorage.setItem('user_id', data.user.id);
          sessionStorage.setItem('user_email', data.user.email);
          sessionStorage.setItem('user_role', data.user.role);
          
          if (data.user.role === 'Admin') {
              navigate('admin-panel');
          } else if (data.user.role === 'Agriculture Official') {
              navigate('agri-panel');
          } else {
              navigate('farmer-panel');
          }
      } catch (err) {
          showToast('Login failed. Please try again.', true);
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
      
      try {
          const res = await fetch('api.php/register', {
              method: 'POST',
              headers: {'Content-Type': 'application/json'},
              body: JSON.stringify({
                  first_name: data['firstName'] || data['first_name'],
                  last_name: data['lastName'] || data['last_name'],
                  email: data.email.toLowerCase(),
                  mobile: data.mobile,
                  role: data.role
              })
          });
          
          const result = await res.json();
          
          if (!res.ok) {
              showToast(result.error, true);
              return;
          }
          
          document.getElementById('reg-success').innerHTML = '<i class="bi bi-check-circle-fill me-2"></i>Registration submitted! Awaiting approval.';
          document.getElementById('reg-success').classList.remove('hidden');
          setTimeout(() => navigate('login'), 2200);
      } catch (err) {
          showToast('Registration failed. Please try again.', true);
      }
  }

  // Updated SESSION object
  const SESSION = {
      get user() {
          return {
              id: sessionStorage.getItem('user_id'),
              email: sessionStorage.getItem('user_email'),
              role: sessionStorage.getItem('user_role')
          };
      },
      async login(email) {
          // Trigger PHP login endpoint
          const res = await fetch('api.php/login', {
              method: 'POST',
              headers: {'Content-Type': 'application/json'},
              body: JSON.stringify({email, password: 'temp'})
          });
          return await res.json();
      },
      logout() {
          sessionStorage.clear();
          navigate('login');
      }
  };

  // Update all DB calls to be async
  async function refreshStats() {
      try {
          const [livestock, incidents, farms, users, pubReports] = await Promise.all([
              DB.getAll('livestock'),
              DB.getAll('incidents'),
              DB.getAll('farms'),
              DB.getAll('users'),
              DB.getAll('public_reports')
          ]);
          
          const totalLivestock = livestock.reduce((sum, l) => sum + (parseInt(l.qty) || 0), 0);
          const activeIncidents = incidents.filter(i => i.status !== 'Resolved').length;
          
          // Update farmer dashboard stats
          const fCards = document.querySelectorAll('#farmer-dashboard .stat-num');
          if (fCards[0]) fCards[0].textContent = totalLivestock;
          if (fCards[1]) fCards[1].textContent = activeIncidents;
          
          // Update admin dashboard stats
          const aCards = document.querySelectorAll('#admin-dashboard .stat-num');
          if (aCards[0]) aCards[0].textContent = users.length;
          if (aCards[1]) aCards[1].textContent = farms.length;
          if (aCards[2]) aCards[2].textContent = totalLivestock;
          if (aCards[3]) aCards[3].textContent = activeIncidents;
          
      } catch (err) {
          console.error('Stats refresh failed:', err);
      }
  }

  // Update render functions to be async
  async function renderFarmerLivestock(filter = '') {
      try {
          const tbody = document.querySelector('#farmer-livestock-table tbody');
          if (!tbody) return;
          
          const livestock = await DB.query('livestock', l => l.farm_id == 1);
          let rows = livestock;
          
          if (filter) {
              rows = rows.filter(l => JSON.stringify(l).toLowerCase().includes(filter.toLowerCase()));
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
                  <td>${badge(l.health)}</td>
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
      }
  }

  // Update all other render functions similarly...
  async function renderFarmerIncidents() {
      try {
          const tbody = document.querySelector('#farmer-incidents-table tbody');
          if (!tbody) return;
          
          const incidents = await DB.query('incidents', i => i.farm_id == 1);
          
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
              rows = rows.filter(f => JSON.stringify(f).toLowerCase().includes(filter.toLowerCase()));
          }
          
          tbody.innerHTML = rows.map(f => `
              <tr>
                  <td>${f.name}</td>
                  <td>${f.owner}</td>
                  <td>${f.type}</td>
                  <td>${badge(f.status)}</td>
                  <td>
                      <div style="display:flex;gap:6px;">
                          <button class="btn btn-panel btn-sm" onclick="approveFarm(${f.id},'Approved')">Approve</button>
                          <button class="btn btn-danger btn-sm" onclick="approveFarm(${f.id},'Rejected')">Reject</button>
                      </div>
                  </td>
              </tr>
          `).join('');
      } catch (err) {
          console.error('Render farms failed:', err);
      }
  }

  // Update CRUD operations
  async function editLivestock(id) {
      try {
          const l = await DB.getById('livestock', id);
          if (!l) return;
          
          // Show modal with current data (same modal logic as original)
          // On save:
          const data = { /* form data */ };
          await DB.update('livestock', id, data);
          renderFarmerLivestock();
          refreshStats();
          showToast('Livestock updated!');
      } catch (err) {
          showToast('Update failed', true);
      }
  }

  async function deleteLivestock(id) {
      try {
          await DB.delete('livestock', id);
          renderFarmerLivestock();
          refreshStats();
          showToast('Livestock deleted.');
      } catch (err) {
          showToast('Delete failed', true);
      }
  }

  async function approveFarm(id, status) {
      try {
          await DB.update('farms', id, {status});
          renderAgriFarms();
          refreshStats();
          showToast(`Farm ${status.toLowerCase()}!`);
      } catch (err) {
          showToast('Update failed', true);
      }
  }

  // Public report submission
  async function submitPublicReport(e) {
      e.preventDefault();
      const formData = new FormData(e.target);
      const data = Object.fromEntries(formData);
      
      try {
          const report = await DB.insert('public_reports', data);
          document.getElementById('report-success').innerHTML = 
              `<i class="bi bi-check-circle-fill me-2"></i>Report submitted! Reference: <strong>${report.ref_id}</strong>`;
          document.getElementById('report-success').classList.remove('hidden');
          e.target.reset();
      } catch (err) {
          showToast('Report submission failed', true);
      }
  }

  // Update panel initializers
  async function initFarmerPanel() {
      await refreshStats();
      await renderFarmerLivestock();
      await renderFarmerIncidents();
      updateSidebarUser('farmer');
      initFarmerCharts();
  }

  async function initAgriPanel() {
      await refreshStats();
      await renderAgriFarms();
      await renderAgriIncidents();
      await renderAgriPublicReports();
      updateSidebarUser('agri');
  }

  async function initAdminPanel() {
      await refreshStats();
      await renderAdminUsers();
      await renderAdminDataTable();
      updateSidebarUser('admin');
      initAdminCharts();
  }

  // Update navigate function for panels
  function navigate(page) {
      // SAME ORIGINAL NAVIGATION LOGIC
      // But update panel initialization calls:
      if (page === 'farmer-panel') {
          initFarmerPanel();
      } else if (page === 'agri-panel') {
          initAgriPanel();
      } else if (page === 'admin-panel') {
          initAdminPanel();
      }
  }

  // Update profile functions to use async DB calls
  async function renderProfileView(panelId) {
      try {
          const roleKey = panelId === 'farmer' ? 'Farmer' : 
                         panelId === 'agri' ? 'Agriculture Official' : 'Admin';
          
          const users = await DB.getAll('users');
          const user = users.find(u => u.role === roleKey);
          
          // Render profile HTML with user data (same as original)
      } catch (err) {
          console.error('Profile render failed:', err);
      }
  }

  // Keep all original UI functions (showToast, badge, fmtDate, etc.)
  // Copy ALL original JavaScript functions that don't use DB

  // Initialize on load
  document.addEventListener('DOMContentLoaded', async function() {
      navigate('home');
      
      // Check session on load
      const userRole = sessionStorage.getItem('user_role');
      if (userRole) {
          // Auto-redirect to appropriate panel
          if (userRole === 'Admin') navigate('admin-panel');
          else if (userRole === 'Agriculture Official') navigate('agri-panel');
          else navigate('farmer-panel');
      }
  });
  </script>
</body>
</html>