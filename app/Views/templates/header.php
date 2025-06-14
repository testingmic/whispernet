<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <meta name="theme-color" content="#0a1a4f">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
  <title>Xe Currency Converter</title>
  <link rel="stylesheet" href="<?php echo $baseUrl; ?>/css/styles.css">
  <!-- Select2 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
</head>
<body>
<header>
  <nav class="navbar">
    <div class="logo">XE</div>
    <button class="mobile-menu-btn" aria-label="Toggle menu">
      <span></span>
      <span></span>
      <span></span>
    </button>
    <div class="nav-content">
      <ul class="nav-links">
        <li><a href="<?php echo $baseUrl; ?>">Home</a></li>
        <li><a href="<?php echo $baseUrl; ?>/rates">Rates</a></li>
        <li><a href="<?php echo $baseUrl; ?>/tools">Tools</a></li>
      </ul>
      <div class="nav-actions">
        <button class="login-btn">Login</button>
        <button class="signup-btn">Sign Up</button>
      </div>
    </div>
  </nav>
</header>