<?php
// Must include config-loader.php before this file
if (!isset($config)) {
    require_once __DIR__ . '/config-loader.php';
}
$pageTitle = $pageTitle ?? $siteName;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="<?= e($pageDescription ?? $tagline) ?>">
  <title><?= e($pageTitle) ?><?= $pageTitle !== $siteName ? ' - ' . e($siteName) : '' ?></title>

  <!-- Canonical URL -->
  <link rel="canonical" href="<?= e($baseUrl . '/' . basename($_SERVER['PHP_SELF']) . ($_SERVER['QUERY_STRING'] ? '?' . $_SERVER['QUERY_STRING'] : '')) ?>">

  <!-- Resource Hints for Performance -->
  <link rel="preconnect" href="https://cdn.tailwindcss.com">
  <link rel="preconnect" href="https://unpkg.com">
  <link rel="preconnect" href="https://cdn.jsdelivr.net">
  <link rel="dns-prefetch" href="https://cdn.tailwindcss.com">
  <link rel="dns-prefetch" href="https://unpkg.com">
  <link rel="dns-prefetch" href="https://cdn.jsdelivr.net">

  <!-- Open Graph / Social Media Tags -->
  <meta property="og:type" content="website">
  <meta property="og:title" content="<?= e($pageTitle) ?><?= $pageTitle !== $siteName ? ' - ' . e($siteName) : '' ?>">
  <meta property="og:description" content="<?= e($pageDescription ?? $tagline) ?>">
  <meta property="og:site_name" content="<?= e($siteName) ?>">
  <meta name="twitter:card" content="summary">
  <meta name="twitter:title" content="<?= e($pageTitle) ?>">
  <meta name="twitter:description" content="<?= e($pageDescription ?? $tagline) ?>">

  <!-- JSON-LD Structured Data -->
  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": "Organization",
    "name": "<?= e($siteName) ?>",
    "description": "<?= e($tagline) ?>",
    "email": "<?= e($supportEmail) ?>"
  }
  </script>
  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": "Service",
    "name": "Spotify Premium Upgrade Service",
    "description": "<?= e($tagline) ?>",
    "provider": {
      "@type": "Organization",
      "name": "<?= e($siteName) ?>"
    },
    "serviceType": "Digital Account Upgrade"
  }
  </script>

  <?php if ($favicon): ?>
  <link rel="icon" type="image/x-icon" href="<?= e($favicon) ?>">
  <?php endif; ?>

  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://unpkg.com/lucide@latest" defer></script>
  <script src="./assets/js/config.php" defer></script>
  <script src="./assets/js/api.js" defer></script>
  <link rel="stylesheet" href="./assets/css/style.css">

  <!-- Custom colors from config - must be AFTER style.css to override defaults -->
  <style>
    :root {
      --color-primary: <?= e($primaryColor) ?>;
      --color-primary-dark: <?= e($primaryDark) ?>;
      --color-primary-light: <?= e($primaryDark) ?>;
      --color-surface: <?= e($surfaceColor) ?>;
      --color-neutral-50: <?= e($bgColor) ?>;
      --color-neutral-900: <?= e($textColor) ?>;
      --color-neutral-600: <?= e($textSecondary) ?>;
    }
  </style>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/lipis/flag-icons@7.0.0/css/flag-icons.min.css">
  <?php if (isset($extraCss)): ?>
  <?php foreach ((array)$extraCss as $css): ?>
  <link rel="stylesheet" href="<?= e($css) ?>">
  <?php endforeach; ?>
  <?php endif; ?>
</head>
<body style="background-color: <?= e($bgColor) ?>;">
  <div id="page-content" class="loaded">

  <!-- Navigation -->
  <nav class="navbar">
    <div class="container">
      <div class="navbar-container">
        <a href="index.php" class="navbar-brand">
          <?php if ($logo): ?>
          <img src="<?= e($logo) ?>" alt="Logo" style="height: 36px; width: auto; max-width: 150px; object-fit: contain;" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
          <i data-lucide="music" style="width: 32px; height: 32px; color: var(--color-primary); display: none;"></i>
          <?php else: ?>
          <i data-lucide="music" style="width: 32px; height: 32px; color: var(--color-primary);"></i>
          <?php endif; ?>
          <span><?= e($siteName) ?></span>
        </a>
        <ul class="navbar-nav">
          <li><a href="index.php" class="nav-link">Home</a></li>
          <li><a href="index.php#features" class="nav-link">Features</a></li>
          <li><a href="index.php#how-it-works" class="nav-link">How It Works</a></li>
          <li class="nav-dropdown">
            <button class="nav-link nav-dropdown-toggle" aria-expanded="false">
              Use Key <i data-lucide="chevron-down" style="width: 16px; height: 16px; margin-left: 4px;"></i>
            </button>
            <div class="nav-dropdown-menu" role="menu">
              <a href="upgrade.php" class="nav-dropdown-item"><i data-lucide="arrow-up-circle" style="width: 18px; height: 18px;"></i><div><span style="font-weight: 600;">Upgrade</span><small style="display: block; color: var(--color-neutral-500); font-size: 0.75rem;">Get Premium access</small></div></a>
              <a href="renew.php" class="nav-dropdown-item"><i data-lucide="refresh-cw" style="width: 18px; height: 18px;"></i><div><span style="font-weight: 600;">Renew</span><small style="display: block; color: var(--color-neutral-500); font-size: 0.75rem;">Restore your Premium</small></div></a>
              <a href="info.php" class="nav-dropdown-item"><i data-lucide="search" style="width: 18px; height: 18px;"></i><div><span style="font-weight: 600;">Check Status</span><small style="display: block; color: var(--color-neutral-500); font-size: 0.75rem;">Track your key</small></div></a>
            </div>
          </li>
          <li style="margin-left: 0.5rem;"><a href="upgrade.php" class="btn btn-primary" style="padding: 0.5rem 1rem; font-size: 0.875rem;">Get Started</a></li>
        </ul>
        <button class="mobile-menu-toggle" onclick="openMobileNav()" aria-label="Open menu"><i data-lucide="menu"></i></button>
      </div>
    </div>
  </nav>

  <!-- Mobile Navigation -->
  <div id="mobileNav" class="mobile-nav" onclick="closeMobileNav(event)">
    <div class="mobile-nav-content" onclick="event.stopPropagation()">
      <div class="mobile-nav-header">
        <div class="mobile-nav-brand">
          <?php if ($logo): ?>
          <img src="<?= e($logo) ?>" alt="Logo" style="height: 32px; width: auto; max-width: 120px; object-fit: contain;" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
          <i data-lucide="music" style="width: 28px; height: 28px; color: var(--color-primary); display: none;"></i>
          <?php else: ?>
          <i data-lucide="music" style="width: 28px; height: 28px; color: var(--color-primary);"></i>
          <?php endif; ?>
          <span class="mobile-nav-title"><?= e($siteName) ?></span>
        </div>
        <button class="mobile-nav-close" onclick="closeMobileNav()" aria-label="Close menu"><i data-lucide="x"></i></button>
      </div>
      <ul class="mobile-nav-links">
        <li><a href="index.php"><i data-lucide="home"></i> Home</a></li>
        <li><a href="index.php#features" onclick="closeMobileNav()"><i data-lucide="star"></i> Features</a></li>
        <li><a href="index.php#how-it-works" onclick="closeMobileNav()"><i data-lucide="info"></i> How It Works</a></li>
        <li class="nav-section-title">Use Your Key</li>
        <li><a href="upgrade.php"><i data-lucide="arrow-up-circle"></i> Upgrade</a></li>
        <li><a href="renew.php"><i data-lucide="refresh-cw"></i> Renew</a></li>
        <li><a href="info.php"><i data-lucide="search"></i> Check Status</a></li>
      </ul>
      <div class="mobile-nav-cta"><a href="upgrade.php" class="btn btn-primary"><i data-lucide="zap"></i> Get Started</a></div>
    </div>
  </div>

  <style>
    .nav-dropdown { position: relative; }
    .nav-dropdown-toggle { display: flex; align-items: center; background: none; border: none; cursor: pointer; font-family: inherit; font-size: inherit; }
    .nav-dropdown-menu { position: absolute; top: 100%; left: 50%; transform: translateX(-50%) translateY(10px); min-width: 220px; background: #242424; border-radius: var(--radius-lg); box-shadow: 0 10px 40px rgba(0,0,0,0.4); padding: 0.5rem; opacity: 0; visibility: hidden; transition: opacity 0.2s, visibility 0.2s, transform 0.2s; z-index: 100; margin-top: 0.5rem; }
    .nav-dropdown:hover .nav-dropdown-menu, .nav-dropdown.open .nav-dropdown-menu { opacity: 1; visibility: visible; transform: translateX(-50%) translateY(0); }
    .nav-dropdown-item { display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem 1rem; color: var(--color-neutral-700); text-decoration: none; border-radius: var(--radius-md); transition: background 0.15s; }
    .nav-dropdown-item:hover { background: #333333; color: var(--color-primary); }
    .nav-dropdown-item i { color: var(--color-primary); }
  </style>
