<?php require_once __DIR__ . '/../includes/config-loader.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Panel - <?= e($siteName) ?></title>

  <!-- Favicon -->
  <?php if ($favicon): ?>
  <link rel="icon" type="image/x-icon" href="<?= e($favicon) ?>">
  <?php endif; ?>

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

  <script src="https://cdn.tailwindcss.com"></script>

  <script src="https://unpkg.com/lucide@latest" defer></script>

  <!-- Config PHP - For JS CONFIG object (editing/saving) -->
  <script src="../assets/js/config.php" defer></script>

  <link rel="stylesheet" href="../assets/css/style.css">

  <style>
    /* Tab Navigation */
    .tab-nav {
      display: flex;
      gap: 0.5rem;
      border-bottom: 2px solid var(--color-neutral-200);
      margin-bottom: 2rem;
      padding-bottom: 0;
    }

    .tab-button {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      padding: 1rem 1.5rem;
      background: transparent;
      border: none;
      border-bottom: 3px solid transparent;
      cursor: pointer;
      font-weight: 600;
      font-size: 0.9375rem;
      color: var(--color-neutral-600);
      transition: all var(--transition-base);
      white-space: nowrap;
      margin-bottom: -2px;
    }

    .tab-button:hover {
      color: var(--color-primary);
      background: color-mix(in srgb, var(--color-primary) 5%, transparent);
    }

    .tab-button.active {
      color: var(--color-primary);
      border-bottom-color: var(--color-primary);
    }

    .tab-content {
      display: none;
      animation: fadeIn 0.3s ease-out;
    }

    .tab-content.active {
      display: block;
    }

    /* Toggle Switch */
    .toggle-switch {
      position: relative;
      width: 48px;
      height: 24px;
      background-color: var(--color-neutral-300);
      border-radius: var(--radius-full);
      cursor: pointer;
      transition: background-color var(--transition-base);
    }

    .toggle-switch.active {
      background-color: var(--color-primary);
    }

    .toggle-switch-handle {
      position: absolute;
      top: 2px;
      left: 2px;
      width: 20px;
      height: 20px;
      background: white;
      border-radius: 50%;
      transition: transform var(--transition-base);
      box-shadow: var(--shadow-sm);
    }

    .toggle-switch.active .toggle-switch-handle {
      transform: translateX(24px);
    }

    /* Package Card */
    .package-card {
      border: 1px solid var(--color-neutral-200);
      border-radius: var(--radius-lg);
      padding: 1rem;
      transition: all var(--transition-base);
      margin-bottom: 1rem;
      background: white;
    }

    .package-card.disabled {
      opacity: 0.6;
      background-color: var(--color-neutral-50);
    }

    .package-card.enabled {
      border-color: var(--color-primary);
      box-shadow: 0 0 0 1px color-mix(in srgb, var(--color-primary) 10%, transparent);
    }

    /* Color Picker */
    .color-input-group {
      display: flex;
      gap: 0.75rem;
      align-items: center;
      padding: 0.5rem 0.75rem;
      background: white;
      border-radius: var(--radius-lg);
      border: 1px solid var(--color-neutral-200);
      transition: all var(--transition-base);
    }

    .color-input-group:hover {
      border-color: var(--color-primary);
    }

    .color-input-group input[type="color"] {
      -webkit-appearance: none;
      -moz-appearance: none;
      appearance: none;
      width: 48px;
      height: 48px;
      border: none;
      border-radius: var(--radius-lg);
      cursor: pointer;
      background: transparent;
      transition: all var(--transition-base);
      position: relative;
      flex-shrink: 0;
    }

    .color-input-group input[type="color"]::-webkit-color-swatch-wrapper {
      padding: 0;
      border-radius: var(--radius-lg);
    }

    .color-input-group input[type="color"]::-webkit-color-swatch {
      border: 2px solid var(--color-neutral-200);
      border-radius: var(--radius-lg);
      box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
      transition: all var(--transition-base);
    }

    .color-input-group input[type="color"]::-moz-color-swatch {
      border: 2px solid var(--color-neutral-200);
      border-radius: var(--radius-lg);
      box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
      transition: all var(--transition-base);
    }

    .color-input-group input[type="color"]:hover::-webkit-color-swatch {
      border-color: var(--color-primary);
      box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05), 0 0 0 3px color-mix(in srgb, var(--color-primary) 10%, transparent);
      transform: scale(1.05);
    }

    .color-input-group input[type="color"]:hover::-moz-color-swatch {
      border-color: var(--color-primary);
      box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05), 0 0 0 3px color-mix(in srgb, var(--color-primary) 10%, transparent);
      transform: scale(1.05);
    }

    .color-input-group input[type="text"] {
      border: none;
      background: transparent;
      font-family: 'SF Mono', 'Monaco', 'Consolas', monospace;
      font-weight: 600;
      font-size: 0.875rem;
      color: var(--color-neutral-900);
      flex: 0 0 90px;
      padding: 0.25rem 0.5rem;
    }

    .color-input-group input[type="text"]:focus {
      outline: none;
    }

    .color-input-group .color-hint {
      font-size: 0.8125rem;
      color: var(--color-neutral-500);
      margin-left: auto;
      white-space: nowrap;
    }

    /* Custom Checkbox */
    input[type="checkbox"] {
      appearance: none;
      width: 20px;
      height: 20px;
      border: 2px solid var(--color-neutral-300);
      border-radius: var(--radius-sm);
      cursor: pointer;
      position: relative;
      transition: all var(--transition-base);
      background: white;
    }

    input[type="checkbox"]:hover {
      border-color: var(--color-primary);
    }

    input[type="checkbox"]:checked {
      background: var(--color-primary);
      border-color: var(--color-primary);
    }

    input[type="checkbox"]:checked::after {
      content: '';
      position: absolute;
      left: 6px;
      top: 2px;
      width: 5px;
      height: 10px;
      border: solid white;
      border-width: 0 2px 2px 0;
      transform: rotate(45deg);
    }

    @keyframes shake {
      0%, 100% { transform: translateX(0); }
      10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
      20%, 40%, 60%, 80% { transform: translateX(5px); }
    }

    /* Stat Card */
    .stat-mini {
      background: linear-gradient(135deg, color-mix(in srgb, var(--color-primary) 10%, transparent) 0%, color-mix(in srgb, var(--color-primary) 5%, transparent) 100%);
      border: 1px solid color-mix(in srgb, var(--color-primary) 20%, transparent);
      border-radius: var(--radius-lg);
      padding: 1rem 1.25rem;
      display: flex;
      align-items: center;
      gap: 0.75rem;
    }

    .stat-mini-icon {
      width: 40px;
      height: 40px;
      background: var(--color-primary);
      border-radius: var(--radius-lg);
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
    }

    .stat-mini-value {
      font-size: 1.5rem;
      font-weight: 700;
      color: var(--color-neutral-900);
      line-height: 1;
    }

    .stat-mini-label {
      font-size: 0.75rem;
      color: var(--color-neutral-600);
      text-transform: uppercase;
      letter-spacing: 0.05em;
      font-weight: 600;
    }

    /* Neutral Stat Variant */
    .stat-mini-neutral {
      background: linear-gradient(135deg, rgba(107, 114, 128, 0.08) 0%, rgba(107, 114, 128, 0.04) 100%);
      border: 1px solid rgba(107, 114, 128, 0.15);
    }

    .stat-mini-icon-neutral {
      background: var(--color-neutral-500);
    }

    /* Clickable Stat */
    a.stat-mini:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }

    a.stat-mini-neutral:hover {
      border-color: var(--color-neutral-400);
    }

    /* Preview delete overlay */
    .preview-wrapper {
      cursor: pointer;
    }
    .delete-overlay {
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: rgba(0, 0, 0, 0.6);
      border-radius: 8px;
      display: flex;
      align-items: center;
      justify-content: center;
      opacity: 0;
      transition: opacity 0.2s ease;
      cursor: pointer;
    }
    .preview-wrapper:hover .delete-overlay {
      opacity: 1;
    }
    .preview-wrapper.has-image .delete-overlay {
      display: flex;
    }
  </style>
</head>
<body style="background: var(--color-neutral-50); min-height: 100vh;">
  <div id="page-content" class="loaded">
  <nav class="navbar">
    <div class="container">
      <div class="navbar-container">
        <a href="../index.php" class="navbar-brand">
          <i data-lucide="settings" style="width: 28px; height: 28px; color: var(--color-primary);"></i>
          <span>Reseller Admin</span>
        </a>
        <a href="../index.php" class="btn btn-secondary">
          <i data-lucide="arrow-left" style="width: 20px; height: 20px;"></i>
          Back to Website
        </a>
      </div>
    </div>
  </nav>

  <div style="max-width: 1400px; margin: 0 auto; padding: 2rem 1rem;">
    <div id="loginSection" class="card" style="max-width: 500px; margin: 0 auto;">
      <div style="text-align: center; margin-bottom: 2rem;">
        <div style="width: 80px; height: 80px; background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-primary-dark) 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem;">
          <i data-lucide="lock" style="width: 40px; height: 40px; color: white;"></i>
        </div>
        <h2 style="font-size: 1.5rem; font-weight: 700; margin-bottom: 0.5rem; color: var(--color-neutral-900);">
          Admin Authentication
        </h2>
        <p style="color: var(--color-neutral-600);">Enter password to access configuration panel</p>
      </div>

      <div id="loginError" style="display: none; margin-bottom: 1.5rem;"></div>

      <div class="form-group">
        <label class="form-label">Admin Password</label>
        <input
          type="password"
          id="adminPassword"
          placeholder="Enter admin password"
          class="form-input"
          onkeypress="if(event.key === 'Enter') checkPassword()"
        >
        <span class="form-hint">
          Default: <code style="background: var(--color-neutral-100); padding: 0.25rem 0.5rem; border-radius: var(--radius-sm); font-family: monospace;">admin123</code>
        </span>
      </div>

      <button onclick="checkPassword()" class="btn btn-primary" style="width: 100%;">
        <i data-lucide="unlock" style="width: 20px; height: 20px;"></i>
        Access Panel
      </button>
    </div>

    <div id="configSection" style="display: none;">
      <div id="previewModeBanner" style="display: none; margin-bottom: 2rem;">
        <div style="background: color-mix(in srgb, var(--color-warning) 15%, white); border: 2px solid color-mix(in srgb, var(--color-warning) 50%, white); border-radius: var(--radius-xl); padding: 1.25rem 1.5rem;">
          <div style="display: flex; align-items: center; gap: 1rem;">
            <div style="width: 48px; height: 48px; background: linear-gradient(135deg, var(--color-warning) 0%, color-mix(in srgb, var(--color-warning) 80%, black) 100%); border-radius: var(--radius-lg); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
              <i data-lucide="eye" style="width: 24px; height: 24px; color: white;"></i>
            </div>
            <div style="flex: 1;">
              <h3 style="font-size: 1.125rem; font-weight: 700; color: color-mix(in srgb, var(--color-warning) 90%, black); margin-bottom: 0.375rem;">
                Preview Mode - Read Only
              </h3>
              <p style="font-size: 0.9375rem; color: color-mix(in srgb, var(--color-warning) 80%, black); line-height: 1.5; margin-bottom: 0;">
                You're viewing the admin panel in preview mode. Download the reseller package to enable full editing and saving capabilities.
              </p>
            </div>
            <a href="https://upgrader.cc/api/download-reseller-package" download="reseller-website.zip" style="flex-shrink: 0; display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.75rem 1.25rem; background: linear-gradient(135deg, var(--color-warning) 0%, color-mix(in srgb, var(--color-warning) 80%, black) 100%); color: white; font-weight: 600; font-size: 0.9375rem; border-radius: var(--radius-lg); text-decoration: none; transition: all var(--transition-base); box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);">
              <i data-lucide="download" style="width: 18px; height: 18px;"></i>
              Download Package
            </a>
          </div>
        </div>
      </div>

      <div id="updateBanner" style="display: none; margin-bottom: 2rem;">
        <div style="background: color-mix(in srgb, var(--color-info) 15%, white); border: 2px solid color-mix(in srgb, var(--color-info) 50%, white); border-radius: var(--radius-xl); padding: 1.25rem 1.5rem;">
          <div style="display: flex; align-items: center; gap: 1rem;">
            <div style="width: 48px; height: 48px; background: linear-gradient(135deg, var(--color-info) 0%, color-mix(in srgb, var(--color-info) 80%, black) 100%); border-radius: var(--radius-lg); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
              <i data-lucide="download-cloud" style="width: 24px; height: 24px; color: white;"></i>
            </div>
            <div style="flex: 1;">
              <h3 style="font-size: 1.125rem; font-weight: 700; color: color-mix(in srgb, var(--color-info) 90%, black); margin-bottom: 0.375rem;">
                Update Available - Version <span id="updateVersion"></span>
              </h3>
              <p style="font-size: 0.9375rem; color: color-mix(in srgb, var(--color-info) 80%, black); line-height: 1.5; margin-bottom: 0.5rem;" id="updateChangelog"></p>
              <div style="display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap;">
                <span style="font-size: 0.875rem; color: color-mix(in srgb, var(--color-info) 80%, black); font-weight: 600;">
                  <i data-lucide="calendar" style="width: 14px; height: 14px; display: inline; margin-right: 0.25rem;"></i>
                  Released: <span id="updateDate"></span>
                </span>
              </div>
            </div>
            <a href="https://upgrader.cc/api/download-reseller-package" download="reseller-website.zip" style="flex-shrink: 0; display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.75rem 1.25rem; background: linear-gradient(135deg, var(--color-info) 0%, color-mix(in srgb, var(--color-info) 80%, black) 100%); color: white; font-weight: 600; font-size: 0.9375rem; border-radius: var(--radius-lg); text-decoration: none; transition: all var(--transition-base); box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);">
              <i data-lucide="download" style="width: 18px; height: 18px;"></i>
              Download Update
            </a>
          </div>
        </div>
      </div>

      <div class="card" style="margin-bottom: 2rem;">
        <div style="display: flex; align-items: center; justify-content: space-between; gap: 1.5rem; flex-wrap: wrap;">
          <div style="display: flex; align-items: center; gap: 1rem;">
            <div style="width: 56px; height: 56px; background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-primary-dark) 100%); border-radius: var(--radius-xl); display: flex; align-items: center; justify-content: center;">
              <i data-lucide="settings" style="width: 28px; height: 28px; color: white;"></i>
            </div>
            <div>
              <h1 style="font-size: 1.75rem; font-weight: 800; color: var(--color-neutral-900); margin-bottom: 0.25rem;">
                Configuration Panel
                <span style="font-size: 0.75rem; font-weight: 600; color: var(--color-neutral-500); margin-left: 0.5rem; padding: 0.25rem 0.5rem; background: var(--color-neutral-100); border-radius: var(--radius-sm);">
                  v<span id="currentVersion">1.0.0</span>
                </span>
              </h1>
              <p style="color: var(--color-neutral-600); font-size: 0.9375rem;">Manage your reseller website settings</p>
            </div>
          </div>
          <div style="display: flex; gap: 0.75rem; flex-shrink: 0;">
            <button onclick="saveConfiguration()" class="btn btn-primary">
              <i data-lucide="save" style="width: 20px; height: 20px;"></i>
              Save Changes
            </button>
            <button onclick="logout()" class="btn btn-secondary">
              <i data-lucide="log-out" style="width: 20px; height: 20px;"></i>
              Logout
            </button>
          </div>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1rem; margin-top: 1.5rem;">
          <div class="stat-mini">
            <div class="stat-mini-icon">
              <i data-lucide="package" style="width: 20px; height: 20px; color: white;"></i>
            </div>
            <div>
              <div class="stat-mini-value"><span id="statEnabled">0</span>/<span id="statPackages">0</span></div>
              <div class="stat-mini-label">Packages (Active/Total)</div>
            </div>
          </div>
          <div class="stat-mini stat-mini-neutral" id="lastSavedIndicator">
            <div class="stat-mini-icon stat-mini-icon-neutral">
              <i data-lucide="clock" style="width: 20px; height: 20px; color: white;"></i>
            </div>
            <div>
              <div class="stat-mini-value" id="lastSavedValue" style="font-size: 0.875rem;">Never</div>
              <div class="stat-mini-label">Last Saved</div>
            </div>
          </div>
          <a href="../index.php" target="_blank" class="stat-mini stat-mini-neutral" style="text-decoration: none; color: inherit; cursor: pointer; transition: all var(--transition-base);">
            <div class="stat-mini-icon stat-mini-icon-neutral">
              <i data-lucide="external-link" style="width: 20px; height: 20px; color: white;"></i>
            </div>
            <div>
              <div class="stat-mini-value" style="font-size: 0.875rem;">Open</div>
              <div class="stat-mini-label">View Website</div>
            </div>
          </a>
        </div>
      </div>

      <div id="messageContainer" style="display: none;"></div>

      <div class="card" style="padding: 0; overflow: hidden;">
        <div class="tab-nav" style="padding: 0 2rem; margin-bottom: 0;">
          <button class="tab-button active" onclick="switchTab('general')">
            <i data-lucide="settings" style="width: 18px; height: 18px;"></i>
            General
          </button>
          <button class="tab-button" onclick="switchTab('packages')">
            <i data-lucide="package" style="width: 18px; height: 18px;"></i>
            Packages
          </button>
          <button class="tab-button" onclick="switchTab('branding')">
            <i data-lucide="palette" style="width: 18px; height: 18px;"></i>
            Branding
          </button>
          <button class="tab-button" onclick="switchTab('appearance')">
            <i data-lucide="sparkles" style="width: 18px; height: 18px;"></i>
            Appearance
          </button>
        </div>

        <div style="padding: 2rem;">
          <div id="tab-general" class="tab-content active">
            <h2 style="font-size: 1.25rem; font-weight: 700; color: var(--color-neutral-900); margin-bottom: 0.5rem;">API Configuration</h2>
            <p style="color: var(--color-neutral-600); font-size: 0.9375rem; margin-bottom: 1.5rem;">Connect your Upgrader.cc API key</p>

            <div class="form-group">
              <label class="form-label">API Key *</label>
              <input type="text" id="apiKey" placeholder="your_api_key_here" class="form-input">
              <span class="form-hint">Get your API key from Upgrader.cc dashboard → Settings → API Keys</span>
            </div>

            <hr style="border: none; border-top: 1px solid var(--color-neutral-200); margin: 2rem 0;">

            <h2 style="font-size: 1.25rem; font-weight: 700; color: var(--color-neutral-900); margin-bottom: 0.5rem;">Admin Password</h2>
            <p style="color: var(--color-neutral-600); font-size: 0.9375rem; margin-bottom: 1.5rem;">Change your admin panel password</p>

            <div class="form-group">
              <label class="form-label">Current Password</label>
              <input type="password" id="currentPassword" placeholder="Enter current password" class="form-input">
            </div>

            <div class="form-group">
              <label class="form-label">New Password</label>
              <input type="password" id="newPassword" placeholder="Enter new password" class="form-input">
              <span class="form-hint">Use a strong password with at least 8 characters</span>
            </div>

            <div class="form-group">
              <label class="form-label">Confirm New Password</label>
              <input type="password" id="confirmPassword" placeholder="Confirm new password" class="form-input">
            </div>

            <div id="passwordStatus" style="margin-bottom: 1rem; display: none;"></div>

            <button onclick="changePassword()" class="btn btn-secondary">
              <i data-lucide="key" style="width: 18px; height: 18px;"></i>
              Change Password
            </button>
          </div>

          <div id="tab-packages" class="tab-content">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
              <div>
                <h2 style="font-size: 1.25rem; font-weight: 700; color: var(--color-neutral-900); margin-bottom: 0.5rem;">Key Packages</h2>
                <p style="color: var(--color-neutral-600); font-size: 0.9375rem;">Configure pricing and payment links</p>
              </div>
              <button onclick="addPackage()" class="btn btn-primary">
                <i data-lucide="plus" style="width: 20px; height: 20px;"></i>
                Add Package
              </button>
            </div>

            <div id="packagesContainer"></div>
          </div>

          <div id="tab-branding" class="tab-content">
            <h2 style="font-size: 1.25rem; font-weight: 700; color: var(--color-neutral-900); margin-bottom: 0.5rem;">Brand Identity</h2>
            <p style="color: var(--color-neutral-600); font-size: 0.9375rem; margin-bottom: 1.5rem;">Customize your website branding</p>

            <div class="form-group">
              <label class="form-label">Site Name</label>
              <input type="text" id="siteName" placeholder="Spotify Premium Upgrades" class="form-input">
            </div>

            <div class="form-group">
              <label class="form-label">Tagline</label>
              <input type="text" id="tagline" placeholder="Upgrade Your Spotify Account to Premium" class="form-input">
            </div>

            <div class="form-group">
              <label class="form-label">Support Email</label>
              <input type="email" id="supportEmail" placeholder="support@yourdomain.com" class="form-input">
            </div>

            <h3 style="font-size: 1rem; font-weight: 600; color: var(--color-neutral-900); margin: 1.5rem 0 1rem 0;">Favicon</h3>
            <p style="color: var(--color-neutral-600); font-size: 0.875rem; margin-bottom: 1rem;">Upload a custom favicon for your website</p>

            <div class="form-group" id="faviconUploadSection">
              <div style="display: flex; align-items: center; gap: 1rem;">
                <div id="faviconPreviewWrapper" class="preview-wrapper" style="position: relative; width: 48px; height: 48px;">
                  <div id="faviconPreview" style="width: 48px; height: 48px; border: 2px dashed var(--color-neutral-300); border-radius: 8px; display: flex; align-items: center; justify-content: center; overflow: hidden; background: var(--color-neutral-100);">
                    <i data-lucide="image" style="width: 24px; height: 24px; color: var(--color-neutral-400);"></i>
                  </div>
                  <div id="faviconDeleteOverlay" class="delete-overlay" onclick="deleteFavicon()" style="display: none;">
                    <i data-lucide="trash-2" style="width: 20px; height: 20px; color: white;"></i>
                  </div>
                </div>
                <div style="flex: 1;">
                  <input type="file" id="faviconInput" accept=".png,.ico,.jpg,.jpeg,.gif,.svg,image/png,image/x-icon,image/jpeg,image/gif,image/svg+xml" style="display: none;">
                  <button type="button" id="faviconUploadBtn" class="btn btn-secondary" style="margin-bottom: 0.5rem;" onclick="document.getElementById('faviconInput').click()">
                    <i data-lucide="upload" style="width: 16px; height: 16px;"></i>
                    Choose Favicon
                  </button>
                  <p style="font-size: 0.75rem; color: var(--color-neutral-500); margin: 0;">PNG, ICO, JPG, GIF or SVG. Max 1MB.</p>
                </div>
              </div>
              <div id="faviconStatus" style="margin-top: 0.75rem; display: none;"></div>
            </div>

            <h3 style="font-size: 1rem; font-weight: 600; color: var(--color-neutral-900); margin: 1.5rem 0 1rem 0;">Logo</h3>
            <p style="color: var(--color-neutral-600); font-size: 0.875rem; margin-bottom: 1rem;">Upload a custom logo for your website navbar</p>

            <div class="form-group" id="logoUploadSection">
              <div style="display: flex; align-items: center; gap: 1rem;">
                <div id="logoPreviewWrapper" class="preview-wrapper" style="position: relative; width: 120px; height: 48px;">
                  <div id="logoPreview" style="width: 120px; height: 48px; border: 2px dashed var(--color-neutral-300); border-radius: 8px; display: flex; align-items: center; justify-content: center; overflow: hidden; background: var(--color-neutral-100);">
                    <i data-lucide="image" style="width: 24px; height: 24px; color: var(--color-neutral-400);"></i>
                  </div>
                  <div id="logoDeleteOverlay" class="delete-overlay" onclick="deleteLogo()" style="display: none;">
                    <i data-lucide="trash-2" style="width: 20px; height: 20px; color: white;"></i>
                  </div>
                </div>
                <div style="flex: 1;">
                  <input type="file" id="logoInput" accept=".png,.jpg,.jpeg,.gif,.svg,.webp,image/png,image/jpeg,image/gif,image/svg+xml,image/webp" style="display: none;">
                  <button type="button" id="logoUploadBtn" class="btn btn-secondary" style="margin-bottom: 0.5rem;" onclick="document.getElementById('logoInput').click()">
                    <i data-lucide="upload" style="width: 16px; height: 16px;"></i>
                    Choose Logo
                  </button>
                  <p style="font-size: 0.75rem; color: var(--color-neutral-500); margin: 0;">PNG, JPG, GIF, SVG or WebP. Max 2MB.</p>
                </div>
              </div>
              <div id="logoStatus" style="margin-top: 0.75rem; display: none;"></div>
            </div>

            <h3 style="font-size: 1rem; font-weight: 600; color: var(--color-neutral-900); margin: 1.5rem 0 1rem 0;">Delivery Instructions</h3>
            <p style="color: var(--color-neutral-600); font-size: 0.875rem; margin-bottom: 1rem;">Customize the message shown to customers after payment on the upgrade page</p>

            <div class="form-group" style="margin-bottom: 0;">
              <label class="form-label">After Payment Message</label>
              <textarea id="deliveryUpgrade" placeholder="You will receive an upgrade key. Copy this key and paste it in Step 2 below." class="form-input" rows="2" style="resize: vertical; font-family: inherit;"></textarea>
              <span style="font-size: 0.75rem; color: var(--color-neutral-500); margin-top: 0.25rem; display: block;">Shown on the upgrade page after payment buttons.</span>
            </div>
          </div>

          <div id="tab-appearance" class="tab-content">
            <h2 style="font-size: 1.25rem; font-weight: 700; color: var(--color-neutral-900); margin-bottom: 0.5rem;">Color Scheme</h2>
            <p style="color: var(--color-neutral-600); font-size: 0.9375rem; margin-bottom: 1.5rem;">Customize colors to match your brand</p>

            <div style="display: grid; gap: 1rem;">
              <div>
                <label class="form-label">Primary Color</label>
                <div class="color-input-group">
                  <input type="color" id="colorPrimary" value="#8B5CF6">
                  <input type="text" id="colorPrimaryText" value="#8B5CF6" placeholder="#8B5CF6">
                  <span class="color-hint">Buttons & accents</span>
                </div>
              </div>

              <div>
                <label class="form-label">Primary Dark</label>
                <div class="color-input-group">
                  <input type="color" id="colorPrimaryDark" value="#7C3AED">
                  <input type="text" id="colorPrimaryDarkText" value="#7C3AED" placeholder="#7C3AED">
                  <span class="color-hint">Gradients</span>
                </div>
              </div>

              <div>
                <label class="form-label">Background Color</label>
                <div class="color-input-group">
                  <input type="color" id="colorBackground" value="#ffffff">
                  <input type="text" id="colorBackgroundText" value="#ffffff" placeholder="#ffffff">
                  <span class="color-hint">Page background</span>
                </div>
              </div>

              <div>
                <label class="form-label">Text Color</label>
                <div class="color-input-group">
                  <input type="color" id="colorText" value="#191414">
                  <input type="text" id="colorTextText" value="#191414" placeholder="#191414">
                  <span class="color-hint">Primary text</span>
                </div>
              </div>

              <div>
                <label class="form-label">Secondary Text Color</label>
                <div class="color-input-group">
                  <input type="color" id="colorTextSecondary" value="#666666">
                  <input type="text" id="colorTextSecondaryText" value="#666666" placeholder="#666666">
                  <span class="color-hint">Descriptions</span>
                </div>
              </div>
            </div>

            <div class="alert alert-info" style="margin-top: 1.5rem; margin-bottom: 0;">
              <i data-lucide="info" class="alert-icon"></i>
              <div class="alert-content">
                Changes apply across all pages. Reload your website after saving to see updates.
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>

  <script>
    // Safe icon initialization
    function initIcons() {
      if (typeof lucide !== 'undefined') lucide.createIcons();
    }

    // Session management - duration must match server-side timeout in admin-auth.php
    const SESSION_KEY = 'reseller_admin_session';
    const SESSION_DURATION = 2 * 60 * 60 * 1000; // 2 hours (matches server)

    let packages = [];
    let packageCounter = 0;
    let currentTab = 'general';

    // SECURITY: Validates session using both client timestamp and server expiry
    function isSessionValid() {
      const session = localStorage.getItem(SESSION_KEY);
      if (!session) return false;
      try {
        const { timestamp, token, expiry } = JSON.parse(session);
        // Check both client-side timestamp and server expiry
        const isValid = (Date.now() - timestamp) < SESSION_DURATION &&
                        token &&
                        (!expiry || expiry * 1000 > Date.now());
        if (!isValid) localStorage.removeItem(SESSION_KEY);
        return isValid;
      } catch (e) {
        localStorage.removeItem(SESSION_KEY);
        return false;
      }
    }

    function getAdminToken() {
      const session = localStorage.getItem(SESSION_KEY);
      if (!session) return null;
      try {
        const { token } = JSON.parse(session);
        return token || null;
      } catch (e) {
        return null;
      }
    }

    // SECURITY: Stores server-provided token and expiry in localStorage
    function saveSession(token, expiry) {
      localStorage.setItem(SESSION_KEY, JSON.stringify({
        timestamp: Date.now(),
        authenticated: true,
        token: token,
        expiry: expiry
      }));
    }

    function logout() {
      localStorage.removeItem(SESSION_KEY);
      document.getElementById('loginSection').style.display = 'block';
      document.getElementById('configSection').style.display = 'none';
      document.getElementById('adminPassword').value = '';

      const errorContainer = document.getElementById('loginError');
      errorContainer.style.display = 'flex';
      errorContainer.className = 'alert alert-info';
      errorContainer.style.alignItems = 'flex-start';
      errorContainer.style.gap = 'var(--space-3)';
      errorContainer.innerHTML = `
        <i data-lucide="info" style="width: 20px; height: 20px; flex-shrink: 0;"></i>
        <div style="flex: 1;">
          <strong>Logged Out</strong><br>
          Successfully logged out of admin panel.
        </div>
      `;
      initIcons();
      setTimeout(() => errorContainer.style.display = 'none', 3000);
    }

    function autoLogin() {
      if (isSessionValid()) {
        document.getElementById('loginSection').style.display = 'none';
        document.getElementById('configSection').style.display = 'block';
        loadCurrentConfig();
        checkPreviewMode();
        initFaviconUpload();
        initLogoUpload();
        loadNavbarLogo();
        checkForUpdates();
      }
    }

    // Preview mode disables editing - allows exploring admin without backend
    function checkPreviewMode() {
      const banner = document.getElementById('previewModeBanner');

      if (CONFIG.previewMode === true) {
        // Show preview mode banner
        if (banner) {
          banner.style.display = 'block';
        }

        // Modify save button behavior
        const saveButtons = document.querySelectorAll('[onclick="saveConfiguration()"]');
        saveButtons.forEach(button => {
          button.onclick = function(e) {
            e.preventDefault();
            alert('Preview Mode: Saving is disabled. Download the package to enable full functionality.');
            return false;
          };
          button.style.opacity = '0.7';
          button.style.cursor = 'not-allowed';
        });

        // Disable favicon upload in preview mode
        const faviconUploadBtn = document.getElementById('faviconUploadBtn');
        if (faviconUploadBtn) {
          faviconUploadBtn.disabled = true;
          faviconUploadBtn.style.opacity = '0.5';
          faviconUploadBtn.style.cursor = 'not-allowed';
          faviconUploadBtn.onclick = function(e) {
            e.preventDefault();
            alert('Preview Mode: Favicon upload is disabled. Download the package to enable this feature.');
          };
        }

        // Disable logo upload in preview mode
        const logoUploadBtn = document.getElementById('logoUploadBtn');
        if (logoUploadBtn) {
          logoUploadBtn.disabled = true;
          logoUploadBtn.style.opacity = '0.5';
          logoUploadBtn.style.cursor = 'not-allowed';
          logoUploadBtn.onclick = function(e) {
            e.preventDefault();
            alert('Preview Mode: Logo upload is disabled. Download the package to enable this feature.');
          };
        }
      } else {
        // Hide banner in production mode
        if (banner) {
          banner.style.display = 'none';
        }
      }
    }

    // Favicon upload - adjusts paths for admin subfolder (../ prefix)
    function initFaviconUpload() {
      const faviconInput = document.getElementById('faviconInput');
      const faviconPreview = document.getElementById('faviconPreview');
      const faviconStatus = document.getElementById('faviconStatus');

      if (!faviconInput) return;

      faviconInput.addEventListener('change', async function(e) {
        const file = e.target.files[0];
        if (!file) return;

        // Validate file size (1MB max)
        if (file.size > 1024 * 1024) {
          showFaviconStatus('File too large. Maximum size is 1MB.', 'error');
          return;
        }

        // Show uploading status
        showFaviconStatus('Uploading...', 'info');

        // Create FormData and upload
        const formData = new FormData();
        formData.append('favicon', file);

        try {
          const apiPath = typeof getApiBasePath === 'function' ? getApiBasePath() : '../api';
          const token = getAdminToken();
          const response = await fetch(`${apiPath}/upload-favicon.php`, {
            method: 'POST',
            headers: token ? { 'X-Admin-Token': token } : {},
            credentials: 'include',
            body: formData
          });

          const result = await response.json();

          if (result.success) {
            showFaviconStatus('Favicon uploaded successfully!', 'success');

            // Update preview - adjust path for admin folder
            let previewPath = result.path;
            // Adjust relative path for admin subfolder
            if (previewPath.startsWith('./')) {
              previewPath = '..' + previewPath.substring(1);
            }
            const img = document.createElement('img');
            img.src = previewPath + '?t=' + Date.now(); // Cache bust
            img.style.width = '100%';
            img.style.height = '100%';
            img.style.objectFit = 'contain';
            faviconPreview.innerHTML = '';
            faviconPreview.appendChild(img);
            faviconPreview.style.border = '2px solid var(--color-primary)';

            // Show delete overlay on hover
            const wrapper = document.getElementById('faviconPreviewWrapper');
            const overlay = document.getElementById('faviconDeleteOverlay');
            if (wrapper) wrapper.classList.add('has-image');
            if (overlay) overlay.style.display = 'flex';

            // Update config
            CONFIG.branding = CONFIG.branding || {};
            CONFIG.branding.favicon = result.path;
          } else {
            showFaviconStatus(result.error || 'Upload failed', 'error');
          }
        } catch (error) {
          showFaviconStatus('Upload failed. Make sure PHP is enabled on your server.', 'error');
        }
      });

      // Load existing favicon if available - adjust path for admin folder
      if (CONFIG.branding?.favicon) {
        let faviconPath = CONFIG.branding.favicon;
        // Adjust relative path for admin folder
        if (faviconPath.startsWith('./')) {
          faviconPath = '..' + faviconPath.substring(1);
        }

        const img = document.createElement('img');
        img.src = faviconPath;
        img.style.width = '100%';
        img.style.height = '100%';
        img.style.objectFit = 'contain';
        img.onload = function() {
          faviconPreview.innerHTML = '';
          faviconPreview.appendChild(img);
          faviconPreview.style.border = '2px solid var(--color-primary)';
          // Show delete overlay on hover
          const wrapper = document.getElementById('faviconPreviewWrapper');
          const overlay = document.getElementById('faviconDeleteOverlay');
          if (wrapper) wrapper.classList.add('has-image');
          if (overlay) overlay.style.display = 'flex';
        };
        img.onerror = function() {
          // Reset to default icon if image fails to load
          faviconPreview.innerHTML = '<i data-lucide="image" style="width: 24px; height: 24px; color: var(--color-neutral-400);"></i>';
          if (typeof lucide !== 'undefined') initIcons();
          // Hide delete overlay
          const wrapper = document.getElementById('faviconPreviewWrapper');
          const overlay = document.getElementById('faviconDeleteOverlay');
          if (wrapper) wrapper.classList.remove('has-image');
          if (overlay) overlay.style.display = 'none';
        };
      }
    }

    async function deleteFavicon() {
      if (!confirm('Are you sure you want to delete the favicon?')) return;

      const faviconPreview = document.getElementById('faviconPreview');
      const wrapper = document.getElementById('faviconPreviewWrapper');
      const overlay = document.getElementById('faviconDeleteOverlay');

      try {
        const response = await fetch(`${getApiBasePath()}/delete-asset.php`, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ type: 'favicon' })
        });

        const result = await response.json();

        if (result.success) {
          // Reset preview
          faviconPreview.innerHTML = '<i data-lucide="image" style="width: 24px; height: 24px; color: var(--color-neutral-400);"></i>';
          faviconPreview.style.border = '2px dashed var(--color-neutral-300)';
          if (typeof lucide !== 'undefined') initIcons();

          // Hide delete overlay
          if (wrapper) wrapper.classList.remove('has-image');
          if (overlay) overlay.style.display = 'none';

          // Update config
          CONFIG.branding.favicon = '';
          showFaviconStatus('Favicon deleted successfully', 'success');
        } else {
          showFaviconStatus(result.error || 'Failed to delete favicon', 'error');
        }
      } catch (error) {
        showFaviconStatus('Failed to delete favicon', 'error');
      }
    }

    function showFaviconStatus(message, type) {
      showUploadStatus('faviconStatus', message, type);
    }

    function showLogoStatus(message, type) {
      showUploadStatus('logoStatus', message, type);
    }

    function showUploadStatus(elementId, message, type) {
      const statusEl = document.getElementById(elementId);
      if (!statusEl) return;

      // Get primary color from CSS variable
      const primaryColor = getComputedStyle(document.documentElement).getPropertyValue('--color-primary').trim() || '#1DB954';

      const colors = {
        success: { bg: `color-mix(in srgb, ${primaryColor} 15%, white)`, border: `color-mix(in srgb, ${primaryColor} 50%, white)`, text: `color-mix(in srgb, ${primaryColor} 80%, black)` },
        error: { bg: '#fee2e2', border: '#fca5a5', text: '#991b1b' },
        info: { bg: '#dbeafe', border: '#93c5fd', text: '#1e40af' }
      };

      const color = colors[type] || colors.info;
      statusEl.style.display = 'block';
      statusEl.style.padding = '0.5rem 0.75rem';
      statusEl.style.borderRadius = '6px';
      statusEl.style.fontSize = '0.875rem';
      statusEl.style.backgroundColor = color.bg;
      statusEl.style.border = '1px solid ' + color.border;
      statusEl.style.color = color.text;
      statusEl.textContent = message;

      if (type !== 'error') {
        setTimeout(() => {
          statusEl.style.display = 'none';
        }, 3000);
      }
    }

    function initLogoUpload() {
      const logoInput = document.getElementById('logoInput');
      const logoPreview = document.getElementById('logoPreview');

      if (!logoInput) return;

      logoInput.addEventListener('change', async function(e) {
        const file = e.target.files[0];
        if (!file) return;

        if (file.size > 2 * 1024 * 1024) {
          showLogoStatus('File too large. Maximum size is 2MB.', 'error');
          return;
        }

        showLogoStatus('Uploading...', 'info');

        const formData = new FormData();
        formData.append('logo', file);

        try {
          const apiPath = typeof getApiBasePath === 'function' ? getApiBasePath() : '../api';
          const token = getAdminToken();
          const response = await fetch(`${apiPath}/upload-logo.php`, {
            method: 'POST',
            headers: token ? { 'X-Admin-Token': token } : {},
            credentials: 'include',
            body: formData
          });

          const result = await response.json();

          if (result.success) {
            showLogoStatus('Logo uploaded successfully!', 'success');

            // Adjust path for admin folder
            let previewPath = result.path;
            if (previewPath.startsWith('./')) {
              previewPath = '..' + previewPath.substring(1);
            }
            const img = document.createElement('img');
            img.src = previewPath + '?t=' + Date.now();
            img.style.maxWidth = '100%';
            img.style.maxHeight = '100%';
            img.style.objectFit = 'contain';
            logoPreview.innerHTML = '';
            logoPreview.appendChild(img);
            logoPreview.style.border = '2px solid var(--color-primary)';

            // Show delete overlay on hover
            const wrapper = document.getElementById('logoPreviewWrapper');
            const overlay = document.getElementById('logoDeleteOverlay');
            if (wrapper) wrapper.classList.add('has-image');
            if (overlay) overlay.style.display = 'flex';

            CONFIG.branding = CONFIG.branding || {};
            CONFIG.branding.logo = result.path;
          } else {
            showLogoStatus(result.error || 'Upload failed', 'error');
          }
        } catch (error) {
          showLogoStatus('Upload failed. Make sure PHP is enabled on your server.', 'error');
        }
      });

      // Load existing logo if available - adjust path for admin folder
      if (CONFIG.branding?.logo) {
        let logoPath = CONFIG.branding.logo;
        // Adjust relative path for admin folder
        if (logoPath.startsWith('./')) {
          logoPath = '..' + logoPath.substring(1);
        }

        const img = document.createElement('img');
        img.src = logoPath;
        img.style.maxWidth = '100%';
        img.style.maxHeight = '100%';
        img.style.objectFit = 'contain';
        img.onload = function() {
          logoPreview.innerHTML = '';
          logoPreview.appendChild(img);
          logoPreview.style.border = '2px solid var(--color-primary)';
          // Show delete overlay on hover
          const wrapper = document.getElementById('logoPreviewWrapper');
          const overlay = document.getElementById('logoDeleteOverlay');
          if (wrapper) wrapper.classList.add('has-image');
          if (overlay) overlay.style.display = 'flex';
        };
        img.onerror = function() {
          logoPreview.innerHTML = '<i data-lucide="image" style="width: 24px; height: 24px; color: var(--color-neutral-400);"></i>';
          if (typeof lucide !== 'undefined') initIcons();
          // Hide delete overlay
          const wrapper = document.getElementById('logoPreviewWrapper');
          const overlay = document.getElementById('logoDeleteOverlay');
          if (wrapper) wrapper.classList.remove('has-image');
          if (overlay) overlay.style.display = 'none';
        };
      }
    }

    async function deleteLogo() {
      if (!confirm('Are you sure you want to delete the logo?')) return;

      const logoPreview = document.getElementById('logoPreview');
      const wrapper = document.getElementById('logoPreviewWrapper');
      const overlay = document.getElementById('logoDeleteOverlay');

      try {
        const response = await fetch(`${getApiBasePath()}/delete-asset.php`, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ type: 'logo' })
        });

        const result = await response.json();

        if (result.success) {
          // Reset preview
          logoPreview.innerHTML = '<i data-lucide="image" style="width: 24px; height: 24px; color: var(--color-neutral-400);"></i>';
          logoPreview.style.border = '2px dashed var(--color-neutral-300)';
          if (typeof lucide !== 'undefined') initIcons();

          // Hide delete overlay
          if (wrapper) wrapper.classList.remove('has-image');
          if (overlay) overlay.style.display = 'none';

          // Update config
          CONFIG.branding.logo = '';
          showLogoStatus('Logo deleted successfully', 'success');

          // Update navbar to show fallback
          loadNavbarLogo();
        } else {
          showLogoStatus(result.error || 'Failed to delete logo', 'error');
        }
      } catch (error) {
        showLogoStatus('Failed to delete logo', 'error');
      }
    }

    function loadNavbarLogo() {
      // Load logo into navbar if available
      if (CONFIG.branding?.logo) {
        let logoPath = CONFIG.branding.logo;
        // Adjust relative path for admin folder
        if (logoPath.startsWith('./')) {
          logoPath = '..' + logoPath.substring(1);
        }

        const navLogo = document.getElementById('navLogo');
        const navLogoFallback = document.getElementById('navLogoFallback');

        if (navLogo && navLogoFallback) {
          const testImg = new Image();
          testImg.onload = function() {
            navLogo.src = logoPath;
            navLogo.style.display = 'block';
            navLogoFallback.style.display = 'none';
          };
          testImg.src = logoPath;
        }
      }
    }

    // Checks remote API for newer package versions, silently fails if unavailable
    async function checkForUpdates() {
      try {
        // Display current version
        const currentVersionEl = document.getElementById('currentVersion');
        if (currentVersionEl && typeof PACKAGE_VERSION !== 'undefined') {
          currentVersionEl.textContent = PACKAGE_VERSION;
        }

        // Check for updates from API
        const response = await fetch(VERSION_CHECK_URL);
        if (!response.ok) {
          console.log('Unable to check for updates');
          return;
        }

        const versionInfo = await response.json();
        const latestVersion = versionInfo.version;
        const currentVersion = PACKAGE_VERSION || '1.0.0';

        // Compare versions
        if (isNewerVersion(latestVersion, currentVersion)) {
          // Show update banner
          const updateBanner = document.getElementById('updateBanner');
          const updateVersion = document.getElementById('updateVersion');
          const updateChangelog = document.getElementById('updateChangelog');
          const updateDate = document.getElementById('updateDate');

          if (updateBanner && updateVersion && updateChangelog && updateDate) {
            updateBanner.style.display = 'block';
            updateVersion.textContent = latestVersion;

            // Show first 2 changelog items
            const changelogText = versionInfo.changelog.slice(0, 2).join(' • ');
            updateChangelog.textContent = changelogText;

            updateDate.textContent = formatDate(versionInfo.releaseDate);

            // Recreate icons after adding content
            if (typeof lucide !== 'undefined') {
              initIcons();
            }
          }
        }
      } catch (error) {
        console.log('Error checking for updates:', error);
      }
    }

    function isNewerVersion(latest, current) {
      const latestParts = latest.split('.').map(Number);
      const currentParts = current.split('.').map(Number);

      for (let i = 0; i < 3; i++) {
        if (latestParts[i] > currentParts[i]) return true;
        if (latestParts[i] < currentParts[i]) return false;
      }
      return false;
    }

    function formatDate(dateString) {
      const date = new Date(dateString);
      const options = { year: 'numeric', month: 'short', day: 'numeric' };
      return date.toLocaleDateString('en-US', options);
    }

    // SECURITY: All authentication is server-side, password never validated client-side
    async function checkPassword() {
      const password = document.getElementById('adminPassword').value;
      const errorContainer = document.getElementById('loginError');
      errorContainer.style.display = 'none';

      // Always use server-side authentication via admin-auth.php
      // (CONFIG.adminPassword is removed from frontend for security)
      let isValid = false;
      let authToken = null;
      let authExpiry = null;

      try {
        const apiPath = typeof getApiBasePath === 'function' ? getApiBasePath() : '../api';
        const response = await fetch(`${apiPath}/admin-auth.php`, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          credentials: 'include',
          body: JSON.stringify({ password })
        });
        const result = await response.json();
        isValid = result.success === true;
        if (isValid) {
          authToken = result.token;
          authExpiry = result.expiry;
        } else if (result.error) {
          errorContainer.style.display = 'flex';
          errorContainer.className = 'alert alert-error';
          errorContainer.innerHTML = `
            <i data-lucide="x-circle" style="width: 20px; height: 20px; flex-shrink: 0;"></i>
            <div style="flex: 1;"><strong>Error</strong><br>${result.error}</div>
          `;
          initIcons();
          return;
        }
      } catch (e) {
        isValid = false;
      }

      if (isValid) {
        saveSession(authToken, authExpiry);
        document.getElementById('loginSection').style.display = 'none';
        document.getElementById('configSection').style.display = 'block';
        loadCurrentConfig();
        checkPreviewMode();
        initFaviconUpload();
        initLogoUpload();
        loadNavbarLogo();
        checkForUpdates();
      } else {
        errorContainer.style.display = 'flex';
        errorContainer.className = 'alert alert-error';
        errorContainer.style.alignItems = 'flex-start';
        errorContainer.style.gap = 'var(--space-3)';
        errorContainer.innerHTML = `
          <i data-lucide="x-circle" style="width: 20px; height: 20px; flex-shrink: 0;"></i>
          <div style="flex: 1;">
            <strong>Authentication Failed</strong><br>
            Incorrect password. Please try again.
          </div>
        `;
        initIcons();

        const passwordInput = document.getElementById('adminPassword');
        passwordInput.style.animation = 'shake 0.5s';
        setTimeout(() => passwordInput.style.animation = '', 500);
      }
    }

    function switchTab(tabName) {
      currentTab = tabName;
      document.querySelectorAll('.tab-button').forEach(btn => btn.classList.remove('active'));
      event.target.closest('.tab-button').classList.add('active');
      document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));
      document.getElementById(`tab-${tabName}`).classList.add('active');
      initIcons();
    }

    function updateStats() {
      const totalPackages = packages.length;
      const enabledPackages = packages.filter(p => p.enabled).length;
      document.getElementById('statPackages').textContent = totalPackages;
      document.getElementById('statEnabled').textContent = enabledPackages;
    }

    function updateLastSaved() {
      const lastSaved = localStorage.getItem('reseller_last_saved');
      const valueEl = document.getElementById('lastSavedValue');

      if (lastSaved) {
        const date = new Date(parseInt(lastSaved));
        const now = new Date();
        const diffMs = now - date;
        const diffMins = Math.floor(diffMs / 60000);
        const diffHours = Math.floor(diffMs / 3600000);
        const diffDays = Math.floor(diffMs / 86400000);

        if (diffMins < 1) {
          valueEl.textContent = 'Just now';
        } else if (diffMins < 60) {
          valueEl.textContent = `${diffMins}m ago`;
        } else if (diffHours < 24) {
          valueEl.textContent = `${diffHours}h ago`;
        } else if (diffDays === 1) {
          valueEl.textContent = 'Yesterday';
        } else if (diffDays < 7) {
          valueEl.textContent = `${diffDays}d ago`;
        } else {
          valueEl.textContent = date.toLocaleDateString();
        }
      } else {
        valueEl.textContent = 'Never';
      }
    }

    // SECURITY: Loads full config including sensitive fields, requires valid token
    async function loadCurrentConfig() {
      // In admin panel, load full config including sensitive fields
      if (!CONFIG.previewMode) {
        try {
          const apiPath = typeof getApiBasePath === 'function' ? getApiBasePath() : '../api';
          const token = getAdminToken();
          const response = await fetch(`${apiPath}/get-admin-config.php`, {
            method: 'GET',
            headers: token ? { 'X-Admin-Token': token } : {},
            credentials: 'include'
          });
          if (response.ok) {
            const adminConfig = await response.json();
            if (adminConfig.error) {
              console.error('Admin config error:', adminConfig.error);
              logout();
              return;
            }
            Object.assign(CONFIG, adminConfig);
          } else if (response.status === 401) {
            logout();
            return;
          }
        } catch (e) {
          console.error('Failed to load admin config:', e);
        }
      }

      document.getElementById('apiKey').value = CONFIG.apiKey || '';
      document.getElementById('siteName').value = CONFIG.branding?.siteName || '';
      document.getElementById('tagline').value = CONFIG.branding?.tagline || '';
      document.getElementById('supportEmail').value = CONFIG.branding?.supportEmail || '';

      document.getElementById('deliveryUpgrade').value = CONFIG.deliveryInstructions || 'You will receive an upgrade key. Copy this key and paste it in Step 2 below.';

      const colors = CONFIG.colors || {
        primary: '#8B5CF6',
        primaryDark: '#7C3AED',
        background: '#F9FAFB',
        text: '#111827',
        textSecondary: '#6B7280'
      };

      document.getElementById('colorPrimary').value = colors.primary;
      document.getElementById('colorPrimaryText').value = colors.primary;
      document.getElementById('colorPrimaryDark').value = colors.primaryDark;
      document.getElementById('colorPrimaryDarkText').value = colors.primaryDark;
      document.getElementById('colorBackground').value = colors.background;
      document.getElementById('colorBackgroundText').value = colors.background;
      document.getElementById('colorText').value = colors.text;
      document.getElementById('colorTextText').value = colors.text;
      document.getElementById('colorTextSecondary').value = colors.textSecondary;
      document.getElementById('colorTextSecondaryText').value = colors.textSecondary;

      if (CONFIG.packages && CONFIG.packages.length > 0) {
        packages = JSON.parse(JSON.stringify(CONFIG.packages));
      } else {
        packages = [{
          id: '1-key',
          name: '1 Key',
          quantity: 1,
          description: 'Perfect for a single account upgrade',
          enabled: true,
          prices: {
            stripe: { url: '', price: '14.99' },
            paypal: { url: '', price: '14.99' },
            crypto: { url: '', price: '14.99' }
          }
        }];
      }

      packageCounter = packages.length;
      renderPackages();
      updateStats();
      updateLastSaved();
      initIcons();
    }

    function renderPackages() {
      const container = document.getElementById('packagesContainer');
      container.innerHTML = '';

      if (packages.length === 0) {
        container.innerHTML = `
          <div style="text-align: center; padding: 3rem 1rem; color: var(--color-neutral-500);">
            <i data-lucide="package-x" style="width: 48px; height: 48px; margin: 0 auto 1rem; opacity: 0.5;"></i>
            <p style="font-size: 1.125rem; font-weight: 600; margin-bottom: 0.5rem;">No packages configured</p>
            <p style="font-size: 0.9375rem;">Click "Add Package" to create your first package</p>
          </div>
        `;
        initIcons();
        return;
      }

      packages.forEach((pkg, index) => {
        const card = document.createElement('div');
        card.className = `package-card ${pkg.enabled ? 'enabled' : 'disabled'}`;

        card.innerHTML = `
          <div style="display: flex; align-items: start; justify-content: space-between; gap: 1rem; margin-bottom: ${pkg.enabled ? '1rem' : '0'};">
            <div style="flex: 1;">
              <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.75rem;">
                <i data-lucide="${pkg.quantity === 1 ? 'key' : pkg.quantity >= 10 ? 'package-open' : 'package'}" style="width: 20px; height: 20px; color: var(--color-primary);"></i>
                <input type="text" value="${pkg.name}" placeholder="Package Name" onchange="updatePackage(${index}, 'name', this.value)" class="form-input" style="font-weight: 600; font-size: 1rem;">
              </div>

              <div style="display: grid; grid-template-columns: 100px 1fr; gap: 0.5rem;">
                <div>
                  <label class="form-label" style="font-size: 0.75rem; margin-bottom: 0.25rem;">Quantity</label>
                  <input type="number" value="${pkg.quantity}" onchange="updatePackage(${index}, 'quantity', parseInt(this.value))" class="form-input" min="1" style="font-size: 0.875rem;">
                </div>
                <div>
                  <label class="form-label" style="font-size: 0.75rem; margin-bottom: 0.25rem;">Description</label>
                  <input type="text" value="${pkg.description || ''}" placeholder="Optional description" onchange="updatePackage(${index}, 'description', this.value)" class="form-input" style="font-size: 0.875rem;">
                </div>
              </div>
            </div>

            <div style="display: flex; align-items: center; gap: 0.5rem; flex-shrink: 0;">
              <label style="display: flex; align-items: center; gap: 0.375rem; cursor: pointer; padding: 0.375rem 0.625rem; background: var(--color-neutral-50); border-radius: var(--radius-md);">
                <input type="checkbox" ${pkg.popular ? 'checked' : ''} onchange="updatePackage(${index}, 'popular', this.checked)">
                <span style="font-size: 0.8125rem; font-weight: 600; color: var(--color-neutral-700);">Popular</span>
              </label>
              <div class="toggle-switch ${pkg.enabled ? 'active' : ''}" onclick="togglePackage(${index})">
                <div class="toggle-switch-handle"></div>
              </div>
              <button onclick="removePackage(${index})" class="btn btn-secondary" style="background: #fef2f2; border-color: #fecaca; color: #dc2626; padding: 0.5rem;">
                <i data-lucide="trash-2" style="width: 16px; height: 16px;"></i>
              </button>
            </div>
          </div>

          ${pkg.enabled ? `
            <div style="display: grid; gap: 0.75rem; background: var(--color-neutral-50); border-radius: var(--radius-md); padding: 1rem;">
              <div>
                <label class="form-label" style="display: flex; align-items: center; gap: 0.375rem; font-size: 0.8125rem; margin-bottom: 0.375rem;">
                  <i data-lucide="dollar-sign" style="width: 14px; height: 14px; color: var(--color-primary);"></i>
                  Price
                </label>
                <input type="text" value="${pkg.price}" placeholder="14.99" onchange="updatePackage(${index}, 'price', this.value)" class="form-input" style="width: 150px; font-size: 0.875rem;">
              </div>

              <div>
                <label class="form-label" style="display: flex; align-items: center; gap: 0.375rem; font-size: 0.8125rem; margin-bottom: 0.375rem;">
                  <i data-lucide="credit-card" style="width: 14px; height: 14px; color: #635bff;"></i>
                  Stripe Payment Link
                </label>
                <input type="url" value="${pkg.paymentLinks.stripe}" placeholder="https://buy.stripe.com/..." onchange="updatePaymentLink(${index}, 'stripe', this.value)" class="form-input" style="font-size: 0.875rem;">
              </div>

              <div>
                <label class="form-label" style="display: flex; align-items: center; gap: 0.375rem; font-size: 0.8125rem; margin-bottom: 0.375rem;">
                  <i data-lucide="wallet" style="width: 14px; height: 14px; color: #0070ba;"></i>
                  PayPal Payment Link
                </label>
                <input type="url" value="${pkg.paymentLinks.paypal}" placeholder="https://www.paypal.com/..." onchange="updatePaymentLink(${index}, 'paypal', this.value)" class="form-input" style="font-size: 0.875rem;">
              </div>

              <div>
                <label class="form-label" style="display: flex; align-items: center; gap: 0.375rem; font-size: 0.8125rem; margin-bottom: 0.375rem;">
                  <i data-lucide="bitcoin" style="width: 14px; height: 14px; color: #f7931a;"></i>
                  Crypto Payment Link
                </label>
                <input type="url" value="${pkg.paymentLinks.crypto}" placeholder="https://pay.cryptomus.com/..." onchange="updatePaymentLink(${index}, 'crypto', this.value)" class="form-input" style="font-size: 0.875rem;">
              </div>
            </div>
          ` : ''}
        `;

        container.appendChild(card);
      });

      updateStats();
      initIcons();
    }

    function togglePackage(index) {
      packages[index].enabled = !packages[index].enabled;
      renderPackages();
    }

    function updatePackage(index, field, value) {
      packages[index][field] = value;
      if (field === 'quantity') renderPackages();
    }

    function updatePaymentLink(index, method, value) {
      packages[index].paymentLinks[method] = value;
    }

    function addPackage() {
      packageCounter++;
      packages.push({
        id: `package-${packageCounter}`,
        name: `${packageCounter} Keys`,
        quantity: packageCounter,
        description: '',
        price: '14.99',
        enabled: true,
        paymentLinks: {
          stripe: '',
          paypal: '',
          crypto: ''
        }
      });
      renderPackages();

      if (currentTab !== 'packages') {
        document.querySelector('.tab-button:nth-child(2)').click();
      }
    }

    function removePackage(index) {
      if (confirm('Are you sure you want to remove this package?')) {
        packages.splice(index, 1);
        renderPackages();
      }
    }

    // Saves config to server, reloads page to apply color changes
    async function saveConfiguration() {
      const button = event.target;
      const originalText = button.innerHTML;
      button.disabled = true;
      button.innerHTML = '<i data-lucide="loader" style="width: 20px; height: 20px; animation: spin 1s linear infinite;"></i> Saving...';

      const newConfig = {
        apiKey: document.getElementById('apiKey').value,
        // Preserve existing password hash - changes handled by changePassword()
        adminPassword: CONFIG.adminPassword || 'admin123',
        apiBaseUrl: 'https://upgrader.cc/api',
        branding: {
          siteName: document.getElementById('siteName').value,
          tagline: document.getElementById('tagline').value,
          supportEmail: document.getElementById('supportEmail').value,
          logo: CONFIG.branding?.logo || './assets/images/logo.png',
          favicon: CONFIG.branding?.favicon || './assets/images/favicon.png'
        },
        deliveryInstructions: document.getElementById('deliveryUpgrade').value,
        colors: {
          primary: document.getElementById('colorPrimary').value,
          primaryDark: document.getElementById('colorPrimaryDark').value,
          background: document.getElementById('colorBackground').value,
          text: document.getElementById('colorText').value,
          textSecondary: document.getElementById('colorTextSecondary').value
        },
        countries: CONFIG.countries,
        features: CONFIG.features,
        packages: packages
      };

      try {
        const token = getAdminToken();
        const result = await saveConfig(newConfig, token);
        const messageContainer = document.getElementById('messageContainer');
        messageContainer.style.display = 'block';

        if (result.success) {
          messageContainer.innerHTML = `
            <div class="alert alert-success">
              <i data-lucide="check-circle" class="alert-icon"></i>
              <div class="alert-content">
                <strong>Success!</strong> ${result.message} Reloading...
              </div>
            </div>
          `;
          localStorage.setItem('reseller_last_saved', Date.now().toString());
          updateLastSaved();
          // Reload page after short delay to apply color changes
          setTimeout(() => window.location.reload(), 1500);
        } else {
          messageContainer.innerHTML = `
            <div class="alert alert-error">
              <i data-lucide="x-circle" class="alert-icon"></i>
              <div class="alert-content">
                <strong>Error!</strong> ${result.message}
              </div>
            </div>
          `;
        }

        initIcons();
        setTimeout(() => messageContainer.style.display = 'none', 5000);
        window.scrollTo({ top: 0, behavior: 'smooth' });
      } catch (error) {
        const messageContainer = document.getElementById('messageContainer');
        messageContainer.style.display = 'block';
        messageContainer.innerHTML = `
          <div class="alert alert-error">
            <i data-lucide="x-circle" class="alert-icon"></i>
            <div class="alert-content">
              <strong>Error!</strong> Failed to save: ${error.message}
            </div>
          </div>
        `;
        initIcons();
      } finally {
        button.disabled = false;
        button.innerHTML = originalText;
        initIcons();
      }
    }

    function setupColorSync() {
      const colorInputs = [
        { picker: 'colorPrimary', text: 'colorPrimaryText' },
        { picker: 'colorPrimaryDark', text: 'colorPrimaryDarkText' },
        { picker: 'colorBackground', text: 'colorBackgroundText' },
        { picker: 'colorText', text: 'colorTextText' },
        { picker: 'colorTextSecondary', text: 'colorTextSecondaryText' }
      ];

      colorInputs.forEach(({ picker, text }) => {
        const pickerEl = document.getElementById(picker);
        const textEl = document.getElementById(text);

        pickerEl.addEventListener('input', (e) => {
          textEl.value = e.target.value.toUpperCase();
        });

        textEl.addEventListener('input', (e) => {
          if (/^#[0-9A-F]{6}$/i.test(e.target.value)) {
            pickerEl.value = e.target.value;
          }
        });

        textEl.addEventListener('blur', (e) => {
          if (!/^#[0-9A-F]{6}$/i.test(e.target.value)) {
            e.target.value = pickerEl.value.toUpperCase();
          }
        });
      });
    }

    // SECURITY: Validates current password server-side, enforces 8+ chars, forces logout
    async function changePassword() {
      const currentPassword = document.getElementById('currentPassword').value;
      const newPassword = document.getElementById('newPassword').value;
      const confirmPassword = document.getElementById('confirmPassword').value;
      const statusEl = document.getElementById('passwordStatus');

      // Validation
      if (!currentPassword || !newPassword || !confirmPassword) {
        showPasswordStatus('Please fill in all fields', 'error');
        return;
      }

      if (newPassword.length < 8) {
        showPasswordStatus('New password must be at least 8 characters', 'error');
        return;
      }

      if (newPassword !== confirmPassword) {
        showPasswordStatus('New passwords do not match', 'error');
        return;
      }

      if (newPassword === currentPassword) {
        showPasswordStatus('New password must be different from current password', 'error');
        return;
      }

      try {
        const token = getAdminToken();
        const response = await fetch(`${getApiBasePath()}/change-password.php`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-Admin-Token': token || ''
          },
          credentials: 'include',
          body: JSON.stringify({ currentPassword, newPassword })
        });

        const result = await response.json();

        if (result.success) {
          showPasswordStatus('Password changed successfully! Please log in again.', 'success');
          // Clear form
          document.getElementById('currentPassword').value = '';
          document.getElementById('newPassword').value = '';
          document.getElementById('confirmPassword').value = '';
          // Log out after 2 seconds
          setTimeout(() => {
            logout();
          }, 2000);
        } else {
          showPasswordStatus(result.error || 'Failed to change password', 'error');
        }
      } catch (error) {
        showPasswordStatus('Failed to change password. Make sure PHP is enabled.', 'error');
      }
    }

    function showPasswordStatus(message, type) {
      const statusEl = document.getElementById('passwordStatus');
      if (!statusEl) return;

      const colors = {
        success: { bg: 'color-mix(in srgb, var(--color-primary) 15%, white)', border: 'color-mix(in srgb, var(--color-primary) 50%, white)', text: 'color-mix(in srgb, var(--color-primary) 80%, black)' },
        error: { bg: '#fee2e2', border: '#fca5a5', text: '#991b1b' }
      };

      const color = colors[type] || colors.error;
      statusEl.style.display = 'block';
      statusEl.style.padding = '0.75rem 1rem';
      statusEl.style.borderRadius = '8px';
      statusEl.style.fontSize = '0.875rem';
      statusEl.style.backgroundColor = color.bg;
      statusEl.style.border = '1px solid ' + color.border;
      statusEl.style.color = color.text;
      statusEl.textContent = message;
    }

    // Initialize when DOM is ready
    document.addEventListener('DOMContentLoaded', function() {
      initIcons();
      autoLogin();
      setupColorSync();
    });
  </script>
  </div><!-- End page-content -->
</body>
</html>
