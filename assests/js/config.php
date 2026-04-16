<?php
// Serve JavaScript with config values baked in from config.json

// Load error handler for consistent error logging
require_once __DIR__ . '/../../includes/error-handler.php';

header('Content-Type: application/javascript; charset=utf-8');
header('Cache-Control: no-cache, no-store, must-revalidate');

// Load config from JSON file
$configPath = __DIR__ . '/../../config/config.json';
$config = [];

if (file_exists($configPath)) {
    $configData = file_get_contents($configPath);
    $config = json_decode($configData, true) ?: [];
}

// Default values (fallback if config.json doesn't exist or is empty)
$defaults = [
    'previewMode' => false,
    'adminPassword' => 'admin123',
    'apiKey' => 'YOUR_API_KEY_HERE',
    'apiBaseUrl' => 'https://upgrader.cc/api',
    'branding' => [
        'siteName' => 'Spotify Premium Upgrades',
        'tagline' => 'Upgrade Your Spotify Account to Premium',
        'supportEmail' => 'support@yourdomain.com',
        'logo' => './assets/images/logo.png',
        'favicon' => './assets/images/favicon.png'
    ],
    'deliveryInstructions' => 'You will receive an upgrade key. Copy this key and paste it in Step 2 below.',
    'colors' => [
        'primary' => '#1DB954',
        'primaryDark' => '#1ed760',
        'background' => '#ffffff',
        'surface' => '#ffffff',
        'text' => '#191414',
        'textSecondary' => '#666666'
    ],
    'countries' => [],
    'features' => [
        ['title' => 'Instant Activation', 'description' => 'Get Premium access within 5-10 minutes of submission'],
        ['title' => 'Secure Service', 'description' => 'Bank-level encryption and secure payment processing'],
        ['title' => '24/7 Support', 'description' => 'Round-the-clock customer support for all your needs'],
        ['title' => 'Free Replacements', 'description' => 'Unlimited free replacements if any issues occur'],
        ['title' => 'No Ads', 'description' => 'Enjoy uninterrupted music without any advertisements'],
        ['title' => 'Offline Listening', 'description' => 'Download and listen to your favorite tracks offline'],
        ['title' => 'Unlimited Skips', 'description' => 'Skip as many songs as you want, anytime'],
        ['title' => 'High Quality Audio', 'description' => 'Stream in high-quality audio up to 320kbps']
    ],
    'packages' => [
        [
            'id' => '1-key',
            'name' => '1 Key',
            'quantity' => 1,
            'description' => 'Perfect for a single account upgrade',
            'price' => '14.99',
            'enabled' => true,
            'paymentLinks' => ['stripe' => '', 'paypal' => '', 'crypto' => '']
        ],
        [
            'id' => '5-keys',
            'name' => '5 Keys',
            'quantity' => 5,
            'description' => 'Best for small groups or resellers',
            'price' => '59.99',
            'popular' => true,
            'enabled' => true,
            'paymentLinks' => ['stripe' => '', 'paypal' => '', 'crypto' => '']
        ],
        [
            'id' => '10-keys',
            'name' => '10 Keys',
            'quantity' => 10,
            'description' => 'Popular choice for resellers',
            'price' => '99.99',
            'enabled' => true,
            'paymentLinks' => ['stripe' => '', 'paypal' => '', 'crypto' => '']
        ]
    ]
];

// Merge config with defaults (config values override defaults)
function array_merge_recursive_distinct(array $array1, array $array2) {
    $merged = $array1;
    foreach ($array2 as $key => $value) {
        if (is_array($value) && isset($merged[$key]) && is_array($merged[$key])) {
            // For numeric arrays (like features, packages), replace entirely
            if (array_keys($value) === range(0, count($value) - 1)) {
                $merged[$key] = $value;
            } else {
                $merged[$key] = array_merge_recursive_distinct($merged[$key], $value);
            }
        } else {
            $merged[$key] = $value;
        }
    }
    return $merged;
}

$finalConfig = array_merge_recursive_distinct($defaults, $config);

// Add isConfigured flag before removing sensitive data
$finalConfig['isConfigured'] = !empty($finalConfig['apiKey']) && $finalConfig['apiKey'] !== 'YOUR_API_KEY_HERE';

// SECURITY: Remove sensitive data before exposing to frontend JavaScript
// API key is used server-side only (in proxy.php)
// Admin password should never be exposed to browser
unset($finalConfig['apiKey']);
unset($finalConfig['adminPassword']);

// Output JavaScript
?>
/* eslint-disable @typescript-eslint/no-unused-vars */
// Configuration file for the Reseller Website
// Values loaded from config.json via PHP

// Package Version - Do not modify this
const PACKAGE_VERSION = '1.2.0';
const VERSION_CHECK_URL = 'https://upgrader.cc/api/reseller-version';

const CONFIG = <?php echo json_encode($finalConfig, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES); ?>;

function getApiBasePath() {
  // Preview mode on upgrader.cc
  if (window.location.pathname.includes('/reseller-website/')) {
    return '/reseller-website/api';
  }
  // Production: always use /api from root (works from any page like /admin/)
  return '/api';
}

// Load config is now a no-op since config is already baked in
// Kept for backwards compatibility
async function loadConfig() {
  return CONFIG;
}

// Alias for backwards compatibility
async function loadConfigFromServer() {
  return true;
}

// Save config to server (token required for authenticated requests)
async function saveConfig(newConfig, adminToken) {
  try {
    const headers = {
      'Content-Type': 'application/json',
    };
    if (adminToken) {
      headers['X-Admin-Token'] = adminToken;
    }

    const response = await fetch(`${getApiBasePath()}/save-config.php`, {
      method: 'POST',
      headers: headers,
      credentials: 'include',
      body: JSON.stringify(newConfig)
    });

    const result = await response.json();

    if (response.ok && result.success) {
      Object.assign(CONFIG, newConfig);
      // Bump config version to invalidate cache on other pages
      localStorage.setItem('configVersion', Date.now().toString());
      // Reload API instance with new config
      if (typeof api !== 'undefined' && api.reloadConfig) {
        api.reloadConfig();
      }
      return { success: true, message: 'Configuration saved successfully' };
    }

    // Return server error message if available
    if (result.error) {
      return { success: false, message: result.error };
    }
  } catch (error) {
    // Server save failed
  }

  return {
    success: false,
    message: 'Failed to save configuration. PHP must be enabled on your hosting.'
  };
}

// Apply colors from config to CSS variables
function applyColors() {
  if (CONFIG.colors) {
    const root = document.documentElement;

    // Apply customizable colors
    if (CONFIG.colors.primary) {
      root.style.setProperty('--color-primary', CONFIG.colors.primary);
    }
    if (CONFIG.colors.primaryDark) {
      root.style.setProperty('--color-primary-light', CONFIG.colors.primaryDark);
      root.style.setProperty('--color-primary-dark', CONFIG.colors.primaryDark);
    }

    // Apply background and surface colors
    if (CONFIG.colors.background) {
      document.body.style.backgroundColor = CONFIG.colors.background;
      root.style.setProperty('--color-neutral-50', CONFIG.colors.background);
    }
    if (CONFIG.colors.surface) {
      root.style.setProperty('--color-surface', CONFIG.colors.surface);
    }

    // Apply text colors
    if (CONFIG.colors.text) {
      root.style.setProperty('--color-neutral-900', CONFIG.colors.text);
    }
    if (CONFIG.colors.textSecondary) {
      root.style.setProperty('--color-neutral-600', CONFIG.colors.textSecondary);
    }

  }
}

// Apply favicon from config
function applyFavicon() {
  // Get favicon path - use favicon if set, otherwise try logo
  let faviconPath = CONFIG.branding.favicon;

  // If favicon is empty, try using logo instead
  if (!faviconPath) {
    faviconPath = CONFIG.branding.logo;
  }

  // If we have a path, set it
  if (faviconPath) {
    // Adjust path if we're in admin folder
    const isInAdmin = window.location.pathname.includes('/admin/');
    if (isInAdmin && faviconPath.startsWith('./')) {
      faviconPath = '..' + faviconPath.substring(1);
    }

    const link = document.querySelector("link[rel*='icon']") || document.createElement('link');
    link.type = 'image/x-icon';
    link.rel = 'icon';
    link.href = faviconPath + '?v=' + Date.now(); // Cache bust
    if (!link.parentNode) {
      document.getElementsByTagName('head')[0].appendChild(link);
    }
  }
}

// Hide loader and show page content
function showPage() {
  const loader = document.getElementById('page-loader');
  const content = document.getElementById('page-content');

  if (loader) {
    loader.classList.add('hidden');
  }
  if (content) {
    content.classList.add('loaded');
  }
}

// Apply CSS variables and title immediately (documentElement exists even in <head>)
// This prevents flash of default colors/content
(function applyEarly() {
  if (typeof CONFIG === 'undefined') return;

  // Set page title immediately
  if (CONFIG.branding && CONFIG.branding.siteName && CONFIG.branding.siteName.trim() !== '') {
    const siteName = CONFIG.branding.siteName;
    const currentTitle = document.title;
    if (currentTitle === 'Loading...') {
      document.title = siteName;
    } else if (!currentTitle.includes(siteName)) {
      document.title = currentTitle + ' - ' + siteName;
    }
  }

  // Set CSS variables
  if (CONFIG.colors) {
    const root = document.documentElement;
    if (CONFIG.colors.primary) {
      root.style.setProperty('--color-primary', CONFIG.colors.primary);
    }
    if (CONFIG.colors.primaryDark) {
      root.style.setProperty('--color-primary-light', CONFIG.colors.primaryDark);
      root.style.setProperty('--color-primary-dark', CONFIG.colors.primaryDark);
    }
    if (CONFIG.colors.background) {
      root.style.setProperty('--color-neutral-50', CONFIG.colors.background);
    }
    if (CONFIG.colors.surface) {
      root.style.setProperty('--color-surface', CONFIG.colors.surface);
    }
    if (CONFIG.colors.text) {
      root.style.setProperty('--color-neutral-900', CONFIG.colors.text);
    }
    if (CONFIG.colors.textSecondary) {
      root.style.setProperty('--color-neutral-600', CONFIG.colors.textSecondary);
    }
  }
})();

// Initialize everything else when DOM is ready
if (typeof window !== 'undefined') {
  function initPage() {
    // Apply remaining colors that need document.body
    if (CONFIG.colors && CONFIG.colors.background && document.body) {
      document.body.style.backgroundColor = CONFIG.colors.background;
    }
    applyFavicon();
    showPage();
    window.dispatchEvent(new CustomEvent('configLoaded', { detail: CONFIG }));
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initPage);
  } else {
    initPage();
  }
}
