<?php require_once 'config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
  <meta name="theme-color" content="#064e3b">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
  <title>AgriTrace+ | <?php echo isset($page) ? ucfirst($page) . ' - Digital Livestock Registration System' : 'Digital Livestock Registration System'; ?></title>
  
  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&family=Syne:wght@700;800&display=swap" rel="stylesheet">
  
  <!-- Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  
  <!-- Maps -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="">
  
  <!-- Custom Styles -->
  <link rel="stylesheet" href="assets/css/style.css">
  
  <!-- Scripts (loaded at end for performance) -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js" defer></script>
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" defer></script>
  <script src="assets/js/app.js" defer></script>
</head>
<body>
  <!-- Hero Background -->
  <div class="hero-bg" id="hero-bg"></div>

  <!-- NAVBAR -->
  <nav class="navbar" id="main-navbar">
    <div class="navbar-logo" onclick="navigatePage('index.php')">
      <i class="bi bi-leaf-fill leaf"></i>
      Agri<span>Trace+</span>
    </div>
    
    <?php if (!isLoggedIn()): ?>
    <!-- Guest Navigation -->
    <ul class="navbar-links" id="desktop-nav">
      <li><a href="index.php" onclick="navigatePage('index.php'); return false;">Home</a></li>
      <li><a href="about.php" onclick="navigatePage('about.php'); return false;">About</a></li>
      <li><a href="contact.php" onclick="navigatePage('contact.php'); return false;">Contact</a></li>
      <li><a href="login.php" class="btn-nav">Login</a></li>
    </ul>
    <?php else: ?>
    <!-- Authenticated Navigation -->
    <ul class="navbar-links" id="desktop-nav-auth">
      <li><a href="dashboard.php" class="btn-nav">
        <i class="bi bi-speedometer2 me-1"></i>Dashboard
      </a></li>
      <li><a href="logout.php">
        <i class="bi bi-power me-1"></i>Logout
      </a></li>
    </ul>
    <?php endif; ?>
    
    <!-- Mobile Hamburger -->
    <button class="nav-hamburger" onclick="toggleMobileMenu()" id="hamburger">
      <i class="bi bi-list"></i>
    </button>
  </nav>

  <!-- MOBILE MENU -->
  <div class="mobile-menu" id="mobile-menu">
    <a href="index.php" onclick="navigatePage('index.php'); closeMobileMenu(); return false;">
      <i class="bi bi-house me-2"></i>Home
    </a>
    <a href="about.php" onclick="navigatePage('about.php'); closeMobileMenu(); return false;">
      <i class="bi bi-info-circle me-2"></i>About
    </a>
    <a href="contact.php" onclick="navigatePage('contact.php'); closeMobileMenu(); return false;">
      <i class="bi bi-envelope me-2"></i>Contact
    </a>
    <a href="public-report.php" onclick="navigatePage('public-report.php'); closeMobileMenu(); return false;">
      <i class="bi bi-file-earmark-text me-2"></i>Public Report
    </a>
    <?php if (!isLoggedIn()): ?>
    <a href="login.php" onclick="navigatePage('login.php'); closeMobileMenu(); return false;">
      <i class="bi bi-box-arrow-in-right me-2"></i>Login
    </a>
    <a href="register.php" onclick="navigatePage('register.php'); closeMobileMenu(); return false;">
      <i class="bi bi-person-plus me-2"></i>Register
    </a>
    <?php else: ?>
    <a href="dashboard.php">
      <i class="bi bi-speedometer2 me-2"></i>Dashboard
    </a>
    <a href="logout.php">
      <i class="bi bi-power me-2"></i>Logout
    </a>
    <?php endif; ?>
  </div>

  <!-- PAGE CONTAINER (for SPA-like navigation) -->
  <div id="page-container">