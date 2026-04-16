<?php require_once __DIR__ . '/includes/config-loader.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="<?= e($tagline) ?>">
  <title><?= e($siteName) ?></title>

  <?php if ($favicon): ?>
  <link rel="icon" type="image/x-icon" href="<?= e($favicon) ?>">
  <?php endif; ?>

  <script src="https://cdn.tailwindcss.com"></script>

  <script src="https://unpkg.com/lucide@latest" defer></script>

  <script src="./assets/js/config.php" defer></script>

  <link rel="stylesheet" href="./assets/css/style.css">

  <!-- Custom CSS Variables from config - AFTER style.css to override -->
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
</head>
<body style="background-color: <?= e($bgColor) ?>;">
  <div id="page-content" class="loaded">
  <nav class="navbar">
    <div class="container">
      <div class="navbar-container">
        <a href="index.php" class="navbar-brand">
          <?php if ($logo): ?>
          <img id="navLogo" src="<?= e($logo) ?>" alt="Logo" style="height: 36px; width: auto; max-width: 150px; object-fit: contain;" onerror="this.style.display='none'; document.getElementById('navLogoFallback').style.display='block';">
          <i id="navLogoFallback" data-lucide="music" style="width: 32px; height: 32px; color: var(--color-primary); display: none;"></i>
          <?php else: ?>
          <i data-lucide="music" style="width: 32px; height: 32px; color: var(--color-primary);"></i>
          <?php endif; ?>
          <span><?= e($siteName) ?></span>
        </a>
        <ul class="navbar-nav">
          <li><a href="index.php" class="nav-link">Home</a></li>
          <li><a href="#features" class="nav-link">Features</a></li>
          <li><a href="#how-it-works" class="nav-link">How It Works</a></li>
          <li class="nav-dropdown">
            <button class="nav-link nav-dropdown-toggle" aria-expanded="false" aria-haspopup="true" aria-controls="dropdown-menu">
              Use Key
              <i data-lucide="chevron-down" style="width: 16px; height: 16px; margin-left: 4px; transition: transform 0.2s;"></i>
            </button>
            <div id="dropdown-menu" class="nav-dropdown-menu" role="menu">
              <a href="upgrade.php" class="nav-dropdown-item" role="menuitem" tabindex="-1">
                <i data-lucide="arrow-up-circle" style="width: 18px; height: 18px;"></i>
                <div>
                  <span style="font-weight: 600;">Upgrade</span>
                  <small style="display: block; color: var(--color-neutral-500); font-size: 0.75rem;">Get Premium access</small>
                </div>
              </a>
              <a href="renew.php" class="nav-dropdown-item" role="menuitem" tabindex="-1">
                <i data-lucide="refresh-cw" style="width: 18px; height: 18px;"></i>
                <div>
                  <span style="font-weight: 600;">Renew</span>
                  <small style="display: block; color: var(--color-neutral-500); font-size: 0.75rem;">Restore your Premium</small>
                </div>
              </a>
              <a href="info.php" class="nav-dropdown-item" role="menuitem" tabindex="-1">
                <i data-lucide="search" style="width: 18px; height: 18px;"></i>
                <div>
                  <span style="font-weight: 600;">Check Status</span>
                  <small style="display: block; color: var(--color-neutral-500); font-size: 0.75rem;">Track your key</small>
                </div>
              </a>
            </div>
          </li>
          <li style="margin-left: 0.5rem;">
            <a href="upgrade.php" class="btn btn-primary" style="padding: 0.5rem 1rem; font-size: 0.875rem;">
              Get Started
            </a>
          </li>
        </ul>
        <button class="mobile-menu-toggle" onclick="openMobileNav()" aria-label="Open menu">
          <i data-lucide="menu"></i>
        </button>
      </div>
    </div>
  </nav>

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
        <button class="mobile-nav-close" onclick="closeMobileNav()" aria-label="Close menu">
          <i data-lucide="x"></i>
        </button>
      </div>
      <ul class="mobile-nav-links">
        <li><a href="index.php"><i data-lucide="home"></i> Home</a></li>
        <li><a href="#features" onclick="closeMobileNav()"><i data-lucide="star"></i> Features</a></li>
        <li><a href="#how-it-works" onclick="closeMobileNav()"><i data-lucide="info"></i> How It Works</a></li>
        <li class="nav-section-title">Use Your Key</li>
        <li><a href="upgrade.php"><i data-lucide="arrow-up-circle"></i> Upgrade</a></li>
        <li><a href="renew.php"><i data-lucide="refresh-cw"></i> Renew</a></li>
        <li><a href="info.php"><i data-lucide="search"></i> Check Status</a></li>
      </ul>
      <div class="mobile-nav-cta">
        <a href="upgrade.php" class="btn btn-primary">
          <i data-lucide="zap"></i>
          Get Started
        </a>
      </div>
    </div>
  </div>

  <style>
    .nav-dropdown { position: relative; }
    .nav-dropdown-toggle { display: flex; align-items: center; background: none; border: none; cursor: pointer; font-family: inherit; font-size: inherit; }
    .nav-dropdown-toggle:focus { outline: 2px solid var(--color-primary); outline-offset: 2px; border-radius: var(--radius-md); }
    .nav-dropdown-menu { position: absolute; top: 100%; left: 50%; transform: translateX(-50%) translateY(10px); min-width: 220px; background: #242424; border-radius: var(--radius-lg); box-shadow: 0 10px 40px rgba(0,0,0,0.4); padding: 0.5rem; opacity: 0; visibility: hidden; transition: opacity 0.2s, visibility 0.2s, transform 0.2s; z-index: 100; margin-top: 0.5rem; }
    .nav-dropdown:hover .nav-dropdown-menu, .nav-dropdown.open .nav-dropdown-menu { opacity: 1; visibility: visible; transform: translateX(-50%) translateY(0); }
    .nav-dropdown:hover .nav-dropdown-toggle i, .nav-dropdown.open .nav-dropdown-toggle i { transform: rotate(180deg); }
    .nav-dropdown-item { display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem 1rem; color: var(--color-neutral-700); text-decoration: none; border-radius: var(--radius-md); transition: background 0.15s; }
    .nav-dropdown-item:hover, .nav-dropdown-item:focus { background: #333333; color: var(--color-primary); outline: none; }
    .nav-dropdown-item i { color: var(--color-primary); }
  </style>

  <section class="hero">
    <div class="container">
      <div class="hero-content" style="text-align: center; max-width: 800px; margin: 0 auto;">
        <div style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.5rem 1rem; background: color-mix(in srgb, var(--color-primary) 10%, transparent); border-radius: 9999px; margin-bottom: 2rem;">
          <div style="width: 8px; height: 8px; background: var(--color-primary); border-radius: 50%; animation: pulse 2s infinite;"></div>
          <span style="font-size: 0.875rem; font-weight: 600; color: var(--color-primary-dark);">Trusted by thousands of users worldwide</span>
        </div>

        <h1 style="font-size: 3.5rem; font-weight: 800; line-height: 1.1; margin-bottom: 1.5rem; color: var(--color-neutral-900);">
          Premium Spotify Experience<br>
          <span class="text-gradient">Without the Premium Price</span>
        </h1>

        <p style="font-size: 1.25rem; color: var(--color-neutral-600); margin-bottom: 2.5rem; line-height: 1.6;">
          <?= e($tagline) ?>
        </p>

        <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap; margin-bottom: 3rem;">
          <a href="upgrade.php" class="btn btn-primary btn-lg">
            <i data-lucide="arrow-up-circle" style="width: 20px; height: 20px;"></i>
            Upgrade Now
          </a>
          <a href="renew.php" class="btn btn-secondary btn-lg">
            <i data-lucide="refresh-cw" style="width: 20px; height: 20px;"></i>
            Renew Account
          </a>
        </div>

        <div class="trust-indicators" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); gap: 1rem; margin-top: 3rem;">
          <div style="display: flex; align-items: center; justify-content: center; gap: 0.5rem; white-space: nowrap;">
            <i data-lucide="zap" style="width: 18px; height: 18px; color: var(--color-primary); flex-shrink: 0;"></i>
            <span style="font-size: 0.8125rem; font-weight: 500; color: var(--color-neutral-600);">Instant Activation</span>
          </div>
          <div style="display: flex; align-items: center; justify-content: center; gap: 0.5rem; white-space: nowrap;">
            <i data-lucide="shield-check" style="width: 18px; height: 18px; color: var(--color-primary); flex-shrink: 0;"></i>
            <span style="font-size: 0.8125rem; font-weight: 500; color: var(--color-neutral-600);">Secure & Safe</span>
          </div>
          <div style="display: flex; align-items: center; justify-content: center; gap: 0.5rem; white-space: nowrap;">
            <i data-lucide="headphones" style="width: 18px; height: 18px; color: var(--color-primary); flex-shrink: 0;"></i>
            <span style="font-size: 0.8125rem; font-weight: 500; color: var(--color-neutral-600);">24/7 Support</span>
          </div>
          <div style="display: flex; align-items: center; justify-content: center; gap: 0.5rem; white-space: nowrap;">
            <i data-lucide="infinity" style="width: 18px; height: 18px; color: var(--color-primary); flex-shrink: 0;"></i>
            <span style="font-size: 0.8125rem; font-weight: 500; color: var(--color-neutral-600);">Unlimited Replacements</span>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section id="features" style="padding: 6rem 0; background: var(--color-surface);">
    <div class="container">
      <div style="text-align: center; margin-bottom: 4rem;">
        <h2 style="font-size: 2.5rem; font-weight: 800; margin-bottom: 1rem; color: var(--color-neutral-900);">
          Why Choose Our Service?
        </h2>
        <p style="font-size: 1.125rem; color: var(--color-neutral-600); max-width: 600px; margin: 0 auto;">
          We provide the most reliable and professional Spotify Premium upgrade service
        </p>
      </div>

      <div id="featuresGrid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 2rem;">
        <?php
        $featureIcons = [
          'Instant Activation' => 'zap',
          'Secure Service' => 'shield-check',
          '24/7 Support' => 'headphones',
          'Unlimited Replacements' => 'repeat',
          'Free Replacements' => 'repeat',
          'No Ads' => 'volume-x',
          'Offline Listening' => 'download-cloud',
          'Unlimited Skips' => 'skip-forward',
          'High Quality Audio' => 'music'
        ];
        foreach ($config['features'] as $index => $feature):
          $icon = $featureIcons[$feature['title']] ?? 'check-circle';
        ?>
        <div class="card card-feature animate-fade-in" style="animation-delay: <?= $index * 0.1 ?>s;">
          <div style="width: 56px; height: 56px; background: color-mix(in srgb, var(--color-primary) 10%, transparent); border-radius: var(--radius-xl); display: flex; align-items: center; justify-content: center; margin-bottom: 1.5rem;">
            <i data-lucide="<?= e($icon) ?>" style="width: 28px; height: 28px; color: var(--color-primary);"></i>
          </div>
          <h3 style="font-size: 1.25rem; font-weight: 700; margin-bottom: 0.75rem; color: var(--color-neutral-900);">
            <?= e($feature['title']) ?>
          </h3>
          <p style="color: var(--color-neutral-600); line-height: 1.6;">
            <?= e($feature['description']) ?>
          </p>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section id="pricing" style="padding: 6rem 0; background: var(--color-neutral-50);">
    <div class="container">
      <div style="text-align: center; margin-bottom: 4rem;">
        <h2 style="font-size: 2.5rem; font-weight: 800; margin-bottom: 1rem; color: var(--color-neutral-900);">
          Simple, Transparent Pricing
        </h2>
        <p style="font-size: 1.125rem; color: var(--color-neutral-600);">
          Choose the service that fits your needs
        </p>
      </div>

      <?php
      $enabledPackages = array_filter($config['packages'] ?? [], fn($p) => $p['enabled'] ?? false);
      $enabledPackages = array_slice($enabledPackages, 0, 4);
      $packageCount = count($enabledPackages);
      ?>

      <?php if ($packageCount > 0): ?>
      <div id="packagesGrid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 2rem; max-width: 100%; margin: 0 auto;">
        <?php foreach ($enabledPackages as $pkg):
          $icon = $pkg['quantity'] == 1 ? 'key' : ($pkg['quantity'] >= 10 ? 'package-open' : 'package');
          $price = $pkg['price'] ?? '2.99';
          $keyText = $pkg['quantity'] == 1 ? 'key' : 'keys';
          $description = $pkg['description'] ?? "Get {$pkg['quantity']} upgrade {$keyText} for Spotify Premium accounts";
          $isPopular = $pkg['popular'] ?? false;
        ?>
        <div class="card card-feature" style="text-align: center; position: relative; <?= $isPopular ? 'border: 2px solid var(--color-primary); transform: scale(1.05);' : '' ?>">
          <?php if ($isPopular): ?>
          <div style="position: absolute; top: -1px; left: -1px; right: -1px; background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-primary-dark) 100%); color: white; padding: 0.5rem; text-align: center; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; border-radius: var(--radius-2xl) var(--radius-2xl) 0 0;">
            Most Popular
          </div>
          <div style="height: 1rem;"></div>
          <?php endif; ?>
          <div style="width: 64px; height: 64px; background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-primary-dark) 100%); border-radius: var(--radius-2xl); display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; <?= $isPopular ? 'margin-top: 0.5rem;' : '' ?>">
            <i data-lucide="<?= e($icon) ?>" style="width: 32px; height: 32px; color: white;"></i>
          </div>
          <h3 style="font-size: 1.5rem; font-weight: 700; margin-bottom: 0.5rem; color: var(--color-neutral-900);">
            <?= e($pkg['name']) ?>
          </h3>
          <div style="margin: 1.5rem 0;">
            <span style="font-size: 3rem; font-weight: 800; color: var(--color-neutral-900);">$<?= e($price) ?></span>
          </div>
          <p style="color: var(--color-neutral-600); margin-bottom: 2rem; line-height: 1.6;">
            <?= e($description) ?>
          </p>
          <ul style="text-align: left; margin-bottom: 2rem; list-style: none; padding: 0;">
            <li style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.75rem;">
              <i data-lucide="check" style="width: 20px; height: 20px; color: var(--color-primary); flex-shrink: 0;"></i>
              <span style="color: var(--color-neutral-700);"><?= $pkg['quantity'] ?> Upgrade <?= $pkg['quantity'] == 1 ? 'Key' : 'Keys' ?></span>
            </li>
            <li style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.75rem;">
              <i data-lucide="check" style="width: 20px; height: 20px; color: var(--color-primary); flex-shrink: 0;"></i>
              <span style="color: var(--color-neutral-700);">Instant delivery</span>
            </li>
            <li style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.75rem;">
              <i data-lucide="check" style="width: 20px; height: 20px; color: var(--color-primary); flex-shrink: 0;"></i>
              <span style="color: var(--color-neutral-700);">Lifetime replacements</span>
            </li>
            <li style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.75rem;">
              <i data-lucide="check" style="width: 20px; height: 20px; color: var(--color-primary); flex-shrink: 0;"></i>
              <span style="color: var(--color-neutral-700);">24/7 customer support</span>
            </li>
          </ul>
          <a href="upgrade.php?package=<?= e($pkg['id']) ?>" class="btn btn-primary" style="width: 100%;">
            <i data-lucide="shopping-cart" style="width: 20px; height: 20px;"></i>
            Purchase <?= $pkg['quantity'] == 1 ? 'Key' : 'Keys' ?>
          </a>
        </div>
        <?php endforeach; ?>
      </div>
      <?php else: ?>
      <div style="text-align: center; padding: 3rem;">
        <i data-lucide="package-x" style="width: 48px; height: 48px; color: var(--color-neutral-400); margin: 0 auto 1rem;"></i>
        <p style="font-size: 1.125rem; color: var(--color-neutral-600);">No packages available at the moment</p>
      </div>
      <?php endif; ?>
    </div>
  </section>

  <section id="how-it-works" style="padding: 6rem 0; background: var(--color-surface);">
    <div class="container">
      <div style="text-align: center; margin-bottom: 4rem;">
        <h2 style="font-size: 2.5rem; font-weight: 800; margin-bottom: 1rem; color: var(--color-neutral-900);">
          How It Works
        </h2>
        <p style="font-size: 1.125rem; color: var(--color-neutral-600);">
          Get Premium in three simple steps
        </p>
      </div>

      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 3rem; max-width: 1000px; margin: 0 auto;">
        <div style="text-align: center;">
          <div style="width: 80px; height: 80px; background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-primary-light) 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; box-shadow: var(--shadow-lg);">
            <span style="font-size: 2rem; font-weight: 800; color: white; line-height: 1;">1</span>
          </div>
          <h3 style="font-size: 1.25rem; font-weight: 700; margin-bottom: 0.75rem; color: var(--color-neutral-900);">Purchase a Key</h3>
          <p style="color: var(--color-neutral-600); line-height: 1.6;">Choose your preferred payment method and purchase an upgrade or renewal key</p>
        </div>

        <div style="text-align: center;">
          <div style="width: 80px; height: 80px; background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-primary-light) 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; box-shadow: var(--shadow-lg);">
            <span style="font-size: 2rem; font-weight: 800; color: white; line-height: 1;">2</span>
          </div>
          <h3 style="font-size: 1.25rem; font-weight: 700; margin-bottom: 0.75rem; color: var(--color-neutral-900);">Submit Your Details</h3>
          <p style="color: var(--color-neutral-600); line-height: 1.6;">Enter your key and Spotify account credentials on our secure form</p>
        </div>

        <div style="text-align: center;">
          <div style="width: 80px; height: 80px; background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-primary-light) 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; box-shadow: var(--shadow-lg);">
            <span style="font-size: 2rem; font-weight: 800; color: white; line-height: 1;">3</span>
          </div>
          <h3 style="font-size: 1.25rem; font-weight: 700; margin-bottom: 0.75rem; color: var(--color-neutral-900);">Enjoy Premium</h3>
          <p style="color: var(--color-neutral-600); line-height: 1.6;">Your account will be upgraded within minutes. Start enjoying Premium features!</p>
        </div>
      </div>

      <div style="text-align: center; margin-top: 4rem;">
        <a href="upgrade.php" class="btn btn-primary btn-lg">
          <i data-lucide="rocket" style="width: 20px; height: 20px;"></i>
          Start Your Upgrade
        </a>
      </div>
    </div>
  </section>

  <footer style="background: #0a0a0a; color: #b3b3b3; padding: 3rem 0;">
    <div class="container">
      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 3rem; margin-bottom: 3rem;">
        <div>
          <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1rem;">
            <?php if ($logo): ?>
            <img src="<?= e($logo) ?>" alt="Logo" style="height: 32px; width: auto; max-width: 100px; object-fit: contain;" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
            <i data-lucide="music" style="width: 32px; height: 32px; color: var(--color-primary); display: none;"></i>
            <?php else: ?>
            <i data-lucide="music" style="width: 32px; height: 32px; color: var(--color-primary);"></i>
            <?php endif; ?>
            <span style="font-size: 1.25rem; font-weight: 700; color: white;"><?= e($siteName) ?></span>
          </div>
          <p style="font-size: 0.875rem; line-height: 1.6;">Professional Spotify Premium upgrade service. Fast, secure, and reliable.</p>
        </div>

        <div>
          <h4 style="font-size: 0.875rem; font-weight: 700; color: white; margin-bottom: 1rem; text-transform: uppercase; letter-spacing: 0.05em;">Navigation</h4>
          <ul style="list-style: none; padding: 0;">
            <li style="margin-bottom: 0.5rem;"><a href="index.php" style="color: #b3b3b3; text-decoration: none; font-size: 0.875rem;">Home</a></li>
            <li style="margin-bottom: 0.5rem;"><a href="#features" style="color: #b3b3b3; text-decoration: none; font-size: 0.875rem;">Features</a></li>
            <li style="margin-bottom: 0.5rem;"><a href="#pricing" style="color: #b3b3b3; text-decoration: none; font-size: 0.875rem;">Pricing</a></li>
            <li style="margin-bottom: 0.5rem;"><a href="#how-it-works" style="color: #b3b3b3; text-decoration: none; font-size: 0.875rem;">How It Works</a></li>
          </ul>
        </div>

        <div>
          <h4 style="font-size: 0.875rem; font-weight: 700; color: white; margin-bottom: 1rem; text-transform: uppercase; letter-spacing: 0.05em;">Use Key</h4>
          <ul style="list-style: none; padding: 0;">
            <li style="margin-bottom: 0.5rem;"><a href="upgrade.php" style="color: #b3b3b3; text-decoration: none; font-size: 0.875rem; display: inline-flex; align-items: center; gap: 0.5rem;"><i data-lucide="arrow-up-circle" style="width: 14px; height: 14px;"></i>Upgrade Account</a></li>
            <li style="margin-bottom: 0.5rem;"><a href="renew.php" style="color: #b3b3b3; text-decoration: none; font-size: 0.875rem; display: inline-flex; align-items: center; gap: 0.5rem;"><i data-lucide="refresh-cw" style="width: 14px; height: 14px;"></i>Renew Premium</a></li>
            <li style="margin-bottom: 0.5rem;"><a href="info.php" style="color: #b3b3b3; text-decoration: none; font-size: 0.875rem; display: inline-flex; align-items: center; gap: 0.5rem;"><i data-lucide="search" style="width: 14px; height: 14px;"></i>Check Key Status</a></li>
          </ul>
        </div>

        <div>
          <h4 style="font-size: 0.875rem; font-weight: 700; color: white; margin-bottom: 1rem; text-transform: uppercase; letter-spacing: 0.05em;">Support</h4>
          <ul style="list-style: none; padding: 0;">
            <li style="margin-bottom: 0.5rem;">
              <a href="mailto:<?= e($supportEmail) ?>" style="color: #b3b3b3; text-decoration: none; font-size: 0.875rem;">
                <span style="display: inline-flex; align-items: center; gap: 0.5rem;">
                  <i data-lucide="mail" style="width: 14px; height: 14px;"></i>
                  <?= e($supportEmail) ?>
                </span>
              </a>
            </li>
          </ul>
        </div>
      </div>

      <div style="border-top: 1px solid #282828; padding-top: 2rem; text-align: center;">
        <p style="font-size: 0.875rem;">
          &copy; <?= date('Y') ?> <?= e($siteName) ?>. All rights reserved.
        </p>
      </div>
    </div>
  </footer>

  <script>
    function initIcons() {
      if (typeof lucide !== 'undefined') lucide.createIcons();
    }

    function openMobileNav() {
      document.getElementById('mobileNav').classList.add('active');
      document.body.style.overflow = 'hidden';
    }

    function closeMobileNav(event) {
      if (event && event.target !== event.currentTarget) return;
      document.getElementById('mobileNav').classList.remove('active');
      document.body.style.overflow = '';
    }

    document.addEventListener('DOMContentLoaded', function() {
      initIcons();

      // WCAG 2.1 keyboard navigation for accessibility
      (function() {
      const dropdown = document.querySelector('.nav-dropdown');
      const toggle = document.querySelector('.nav-dropdown-toggle');
      const menu = document.querySelector('.nav-dropdown-menu');
      const items = menu ? menu.querySelectorAll('.nav-dropdown-item') : [];
      let currentIndex = -1;

      function openDropdown() { dropdown.classList.add('open'); toggle.setAttribute('aria-expanded', 'true'); items.forEach(item => item.setAttribute('tabindex', '0')); }
      function closeDropdown() { dropdown.classList.remove('open'); toggle.setAttribute('aria-expanded', 'false'); items.forEach(item => item.setAttribute('tabindex', '-1')); currentIndex = -1; }
      function focusItem(index) { if (index >= 0 && index < items.length) { currentIndex = index; items[index].focus(); } }

      if (toggle) {
        toggle.addEventListener('click', function(e) { e.preventDefault(); dropdown.classList.contains('open') ? closeDropdown() : (openDropdown(), items.length > 0 && focusItem(0)); });
        toggle.addEventListener('keydown', function(e) { if (['Enter', ' ', 'ArrowDown'].includes(e.key)) { e.preventDefault(); openDropdown(); items.length > 0 && focusItem(0); } else if (e.key === 'Escape') { closeDropdown(); toggle.focus(); } });
      }

      items.forEach((item, index) => {
        item.addEventListener('keydown', function(e) {
          if (e.key === 'ArrowDown') { e.preventDefault(); focusItem((index + 1) % items.length); }
          else if (e.key === 'ArrowUp') { e.preventDefault(); focusItem((index - 1 + items.length) % items.length); }
          else if (e.key === 'Escape') { e.preventDefault(); closeDropdown(); toggle.focus(); }
          else if (e.key === 'Tab') { closeDropdown(); }
        });
      });

      document.addEventListener('click', function(e) { if (dropdown && !dropdown.contains(e.target)) closeDropdown(); });
      })();
    });
  </script>
  </div>
</body>
</html>
