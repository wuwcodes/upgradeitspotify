  <!-- Footer -->
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
            <li style="margin-bottom: 0.5rem;"><a href="index.php#features" style="color: #b3b3b3; text-decoration: none; font-size: 0.875rem;">Features</a></li>
            <li style="margin-bottom: 0.5rem;"><a href="index.php#pricing" style="color: #b3b3b3; text-decoration: none; font-size: 0.875rem;">Pricing</a></li>
            <li style="margin-bottom: 0.5rem;"><a href="index.php#how-it-works" style="color: #b3b3b3; text-decoration: none; font-size: 0.875rem;">How It Works</a></li>
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
              <a href="mailto:<?= e($supportEmail) ?>" style="color: #b3b3b3; text-decoration: none; font-size: 0.875rem; display: inline-flex; align-items: center; gap: 0.5rem;">
                <i data-lucide="mail" style="width: 14px; height: 14px;"></i><?= e($supportEmail) ?>
              </a>
            </li>
          </ul>
        </div>
      </div>

      <div style="border-top: 1px solid #282828; padding-top: 2rem; text-align: center;">
        <p style="font-size: 0.875rem;">&copy; <?= date('Y') ?> <?= e($siteName) ?>. All rights reserved.</p>
      </div>
    </div>
  </footer>

  <script>
    // Safe icon initialization - use this for dynamic content updates
    function initIcons() {
      if (typeof lucide !== 'undefined') {
        lucide.createIcons();
      }
    }

    // Mobile navigation functions
    function openMobileNav() {
      document.getElementById('mobileNav').classList.add('active');
      document.body.style.overflow = 'hidden';
    }
    function closeMobileNav(event) {
      if (event && event.target !== event.currentTarget) return;
      document.getElementById('mobileNav').classList.remove('active');
      document.body.style.overflow = '';
    }

    // Initialize after all deferred scripts have loaded
    document.addEventListener('DOMContentLoaded', function() {
      initIcons();
      // Dispatch event for page-specific initialization
      window.dispatchEvent(new Event('app:ready'));
    });
  </script>
  </div>
</body>
</html>
