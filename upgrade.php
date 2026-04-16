<?php
$pageTitle = "Upgrade to Premium";
$pageDescription = "Upgrade your Spotify account to Premium in minutes.";
$extraCss = ["./assets/css/country-dropdown.css"];
require_once __DIR__ . "/includes/header.php";
?>
  <main style="padding: 4rem 0; background: var(--color-neutral-50); min-height: calc(100vh - 72px);">
    <div class="container" style="max-width: 960px;">
      <div style="text-align: center; margin-bottom: 3rem;">
        <div style="display: inline-flex; align-items: center; justify-content: center; width: 64px; height: 64px; background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-primary-dark) 100%); border-radius: var(--radius-2xl); margin-bottom: 1.5rem; box-shadow: var(--shadow-lg);">
          <i data-lucide="arrow-up-circle" style="width: 32px; height: 32px; color: white;"></i>
        </div>
        <h1 style="font-size: 2.5rem; font-weight: 800; margin-bottom: 0.75rem; color: var(--color-neutral-900);">
          Upgrade to Premium
        </h1>
        <p style="font-size: 1.125rem; color: var(--color-neutral-600);">
          Transform your Spotify experience in minutes
        </p>
      </div>

      <div id="alertContainer"></div>

      <!-- Step 1: Purchase Key -->
      <div class="card" style="margin-bottom: 2rem;">
        <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem;">
          <div style="width: 40px; height: 40px; background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-primary-dark) 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
            <span style="font-size: 1.25rem; font-weight: 700; color: white;">1</span>
          </div>
          <h2 style="font-size: 1.5rem; font-weight: 700; color: var(--color-neutral-900);">
            Purchase an Upgrade Key
          </h2>
        </div>

        <div id="packageSelector" style="margin-bottom: 1.5rem;">
          <label style="display: block; font-size: 0.875rem; font-weight: 600; color: var(--color-neutral-700); margin-bottom: 0.75rem;">
            Select Package
          </label>
          <div id="packageOptions" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); gap: 0.75rem;"></div>
        </div>

        <p style="color: var(--color-neutral-600); margin-bottom: 1rem; line-height: 1.6;">
          Choose your preferred payment method:
        </p>

        <div id="paymentButtons" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 1.5rem;"></div>

        <div style="padding: 1rem; background: color-mix(in srgb, var(--color-info) 12%, #181818); border: 1px solid color-mix(in srgb, var(--color-info) 30%, #282828); border-radius: var(--radius-lg);">
          <div style="display: flex; align-items: flex-start; gap: 0.75rem;">
            <i data-lucide="info" style="width: 20px; height: 20px; color: #7cb8ff; flex-shrink: 0; margin-top: 0.125rem;"></i>
            <div style="flex: 1;">
              <p style="font-size: 0.875rem; font-weight: 600; color: #7cb8ff; margin-bottom: 0.25rem;">After payment</p>
              <p id="deliveryInstructions" style="font-size: 0.875rem; color: #7cb8ff;">You will receive an upgrade key. Copy this key and paste it in Step 2 below.</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Step 2: Enter Details -->
      <div class="card">
        <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem;">
          <div style="width: 40px; height: 40px; background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-primary-dark) 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
            <span style="font-size: 1.25rem; font-weight: 700; color: white;">2</span>
          </div>
          <h2 style="font-size: 1.5rem; font-weight: 700; color: var(--color-neutral-900);">
            Enter Your Details
          </h2>
        </div>

        <form id="upgradeForm">
          <div class="form-group">
            <label for="upgradeKey" class="form-label">
              <i data-lucide="key" style="width: 16px; height: 16px; display: inline; vertical-align: middle; margin-right: 0.25rem;"></i>
              Upgrade Key *
            </label>
            <input
              type="text"
              id="upgradeKey"
              name="key"
              required
              placeholder="Enter your upgrade key (Ex: XXXX-XXXX-XXXX-XXXX)"
              class="form-input"
            >
            <div id="keyFormatHint" style="display: none;"></div>
          </div>

          <div id="upgradeFields" class="fields-container" style="display: none;">
            <div class="form-group">
              <label for="spotifyLogin" class="form-label">
                <i data-lucide="user" style="width: 16px; height: 16px; display: inline; vertical-align: middle; margin-right: 0.25rem;"></i>
                Spotify Email or Username *
              </label>
              <input
                type="text"
                id="spotifyLogin"
                name="login"
                required
                placeholder="your@email.com or username"
                class="form-input"
                autocomplete="username"
              >
            </div>

            <!-- Spotify Password -->
            <div class="form-group">
              <label for="spotifyPassword" class="form-label">
                <i data-lucide="lock" style="width: 16px; height: 16px; display: inline; vertical-align: middle; margin-right: 0.25rem;"></i>
                Spotify Password *
              </label>
              <input
                type="password"
                id="spotifyPassword"
                name="password"
                required
                placeholder="Your Spotify password"
                class="form-input"
                autocomplete="current-password"
              >
              <span class="form-hint">Your password is encrypted and never stored</span>
            </div>

            <div class="form-group">
              <label for="country" class="form-label">
                <i data-lucide="globe" style="width: 16px; height: 16px; display: inline; vertical-align: middle; margin-right: 0.25rem;"></i>
                Country *
              </label>
              <select
                id="country"
                name="country"
                class="form-select"
                data-country-dropdown
              >
                <option value="">Select your country</option>
              </select>
              <span class="form-hint">Only specify if you have special regional requirements. Any country works for general usage.</span>
            </div>

            <div style="margin-top: 1.5rem; margin-bottom: 1.5rem; padding: 1rem 1.25rem; background: color-mix(in srgb, var(--color-warning) 12%, #181818); border: 1px solid color-mix(in srgb, var(--color-warning) 30%, #282828); border-radius: var(--radius-lg);">
              <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.75rem;">
                <i data-lucide="alert-triangle" style="width: 18px; height: 18px; color: #fbbf24;"></i>
                <span style="font-weight: 600; color: #fbbf24; font-size: 0.9375rem;">Important Notes</span>
              </div>
              <ul style="list-style: none; padding: 0; margin: 0; font-size: 0.875rem; color: #fbbf24;">
                <li style="display: flex; align-items: flex-start; gap: 0.5rem; margin-bottom: 0.5rem;">
                  <span style="color: #fbbf24;">•</span>
                  <span>Make sure your account details are correct to avoid upgrade failures</span>
                </li>
                <li style="display: flex; align-items: flex-start; gap: 0.5rem; margin-bottom: 0.5rem;">
                  <span style="color: #fbbf24;">•</span>
                  <span>Your account should NOT already be Premium (use <a href="renew.php" style="color: #fbbf24; text-decoration: underline; font-weight: 600;">Renew</a> if it is)</span>
                </li>
                <li style="display: flex; align-items: flex-start; gap: 0.5rem; margin-bottom: 0.5rem;">
                  <span style="color: #fbbf24;">•</span>
                  <span>Processing typically takes 5-10 minutes</span>
                </li>
                <li style="display: flex; align-items: flex-start; gap: 0.5rem;">
                  <span style="color: #fbbf24;">•</span>
                  <span>You can check your upgrade status using the <a href="info.php" style="color: #fbbf24; text-decoration: underline; font-weight: 600;">Check Status</a> page</span>
                </li>
              </ul>
            </div>

            <div style="margin-bottom: 1.5rem;">
              <label style="display: flex; align-items: flex-start; gap: 0.75rem; cursor: pointer;">
                <input
                  type="checkbox"
                  id="terms"
                  required
                  style="margin-top: 0.125rem;"
                >
                <span style="font-size: 0.875rem; color: var(--color-neutral-700); line-height: 1.6;">
                  I agree that my account details are correct and I understand that incorrect information may result in a failed upgrade. *
                </span>
              </label>
            </div>

            <button
              type="submit"
              class="btn btn-primary btn-lg"
              style="width: 100%;"
            >
              <i data-lucide="rocket" style="width: 20px; height: 20px;"></i>
              <span>Upgrade to Premium</span>
            </button>
          </div>
        </form>
      </div>

      <div class="card" style="margin-top: 2rem;">
        <h2 style="font-size: 1.5rem; font-weight: 700; margin-bottom: 1.5rem; color: var(--color-neutral-900);">
          Frequently Asked Questions
        </h2>

        <div style="display: flex; flex-direction: column; gap: 1.5rem;">
          <div>
            <h3 style="font-size: 1rem; font-weight: 600; margin-bottom: 0.5rem; color: var(--color-neutral-900);">
              <i data-lucide="help-circle" style="width: 16px; height: 16px; display: inline; vertical-align: middle; margin-right: 0.5rem; color: var(--color-primary);"></i>
              What if my upgrade fails?
            </h3>
            <p style="font-size: 0.875rem; color: var(--color-neutral-600); line-height: 1.6; padding-left: 1.75rem;">
              If your upgrade fails, check the error message and try again. Common issues include incorrect login details or the account already being Premium. Contact support if problems persist.
            </p>
          </div>

          <div>
            <h3 style="font-size: 1rem; font-weight: 600; margin-bottom: 0.5rem; color: var(--color-neutral-900);">
              <i data-lucide="clock" style="width: 16px; height: 16px; display: inline; vertical-align: middle; margin-right: 0.5rem; color: var(--color-primary);"></i>
              How long does it take?
            </h3>
            <p style="font-size: 0.875rem; color: var(--color-neutral-600); line-height: 1.6; padding-left: 1.75rem;">
              Most upgrades are completed within 5-10 minutes. You'll receive Premium access automatically once processing is complete.
            </p>
          </div>

          <div>
            <h3 style="font-size: 1rem; font-weight: 600; margin-bottom: 0.5rem; color: var(--color-neutral-900);">
              <i data-lucide="users" style="width: 16px; height: 16px; display: inline; vertical-align: middle; margin-right: 0.5rem; color: var(--color-primary);"></i>
              Can I use this for Family plans?
            </h3>
            <p style="font-size: 0.875rem; color: var(--color-neutral-600); line-height: 1.6; padding-left: 1.75rem;">
              Yes! Our service works with both individual and family plan accounts.
            </p>
          </div>

          <div>
            <h3 style="font-size: 1rem; font-weight: 600; margin-bottom: 0.5rem; color: var(--color-neutral-900);">
              <i data-lucide="shield-check" style="width: 16px; height: 16px; display: inline; vertical-align: middle; margin-right: 0.5rem; color: var(--color-primary);"></i>
              Is my data secure?
            </h3>
            <p style="font-size: 0.875rem; color: var(--color-neutral-600); line-height: 1.6; padding-left: 1.75rem;">
              Absolutely. All data is transmitted securely and your password is never stored on our servers.
            </p>
          </div>
        </div>
      </div>
    </div>
  </main>

<?php require_once __DIR__ . "/includes/footer.php"; ?>
<script src="./assets/js/country-dropdown.js" defer></script>
<script>
document.addEventListener('DOMContentLoaded', async function() {
    initIcons();

    if (typeof CONFIG !== 'undefined' && CONFIG.deliveryInstructions) {
      const el = document.getElementById('deliveryInstructions');
      if (el) el.textContent = CONFIG.deliveryInstructions;
    }

    if (typeof checkApiKey === 'function') {
      const apiKeyWarning = checkApiKey();
      if (apiKeyWarning) {
        document.getElementById('alertContainer').appendChild(apiKeyWarning);
      }
    }

    // Check if upgrades are disabled
    try {
      const featuresData = await api.getFeatures();
      if (featuresData.status === 'success' && featuresData.features) {
        if (featuresData.features.upgrade?.disabled) {
          const message = featuresData.features.upgrade.message || 'Upgrades are temporarily disabled.';
          const alertContainer = document.getElementById('alertContainer');
          if (typeof showMaintenance === 'function') {
            showMaintenance(message, alertContainer);
          }
        }
      }
    } catch (e) {
      console.warn('Could not fetch feature status:', e);
    }

    // SECURITY: Validates key format to prevent injection
    function isValidKeyFormat(key) {
      if (!key || key.trim() === '') return false;
      const trimmed = key.trim().toUpperCase();
      const standardPattern = /^[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{4}$/;
      const brandedPattern = /^UPGRADER-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{4}$/;
      return standardPattern.test(trimmed) || brandedPattern.test(trimmed);
    }
    const upgradeKeyInput = document.getElementById('upgradeKey');
    const upgradeFields = document.getElementById('upgradeFields');
    const keyFormatHint = document.getElementById('keyFormatHint');

    if (upgradeKeyInput) {
      upgradeKeyInput.addEventListener('input', () => {
        const keyValue = upgradeKeyInput.value.trim();
        if (isValidKeyFormat(keyValue)) {
          upgradeFields.style.display = 'block';
          keyFormatHint.style.display = 'none';
          initIcons();
        } else {
          upgradeFields.style.display = 'none';
          if (keyValue.length > 0) {
            keyFormatHint.style.display = 'block';
            keyFormatHint.className = 'key-format-error';
            keyFormatHint.textContent = 'Key format should be XXXX-XXXX-XXXX-XXXX';
          } else {
            keyFormatHint.style.display = 'none';
          }
        }
      });
    }

    const packageOptions = document.getElementById('packageOptions');
    const paymentButtons = document.getElementById('paymentButtons');
    const packageSelector = document.getElementById('packageSelector');
    let selectedPackageId = null;

    function getEnabledPackages() {
      if (typeof CONFIG === 'undefined' || !CONFIG.packages) return [];
      return CONFIG.packages.filter(pkg => pkg.enabled);
    }

    function renderPackageOptions() {
      const packages = getEnabledPackages();

      if (packages.length === 0) {
        packageSelector.style.display = 'none';
        paymentButtons.innerHTML = '<div style="grid-column: 1 / -1; padding: 2rem; background: #242424; border: 1px solid var(--color-neutral-300); border-radius: var(--radius-lg); text-align: center;"><div style="display: inline-flex; align-items: center; justify-content: center; width: 48px; height: 48px; background: var(--color-neutral-200); border-radius: 50%; margin-bottom: 1rem;"><i data-lucide="package-x" style="width: 24px; height: 24px; color: var(--color-neutral-400);"></i></div><p style="font-weight: 600; color: var(--color-neutral-700); margin-bottom: 0.5rem; font-size: 1rem;">No upgrade packages available</p><p style="font-size: 0.875rem; color: var(--color-neutral-500);">Please check back later or contact support for assistance</p></div>';
        initIcons();
        return;
      }

      if (packages.length === 1) {
        packageSelector.style.display = 'none';
        selectPackage(packages[0].id);
        return;
      }

      packageOptions.innerHTML = '';

      const urlParams = new URLSearchParams(window.location.search);
      const packageParam = urlParams.get('package');
      const preselectedPkg = packageParam ? packages.find(p => p.id === packageParam) : null;

      const defaultPkg = preselectedPkg || packages[0];

      packages.forEach(pkg => {
        const isSelected = pkg.id === defaultPkg.id;
        const keyText = pkg.quantity === 1 ? 'Key' : 'Keys';

        const option = document.createElement('button');
        option.type = 'button';
        option.className = 'package-option' + (isSelected ? ' selected' : '');
        option.dataset.packageId = pkg.id;
        option.innerHTML = `
          <div style="font-weight: 700; font-size: 1rem; color: var(--color-neutral-900);">${pkg.quantity} ${keyText}</div>
          <div style="font-size: 1.25rem; font-weight: 800; color: var(--color-primary);">$${pkg.price}</div>
        `;
        option.addEventListener('click', () => selectPackage(pkg.id));
        packageOptions.appendChild(option);
      });

      selectPackage(defaultPkg.id);
    }

    function selectPackage(packageId) {
      const packages = getEnabledPackages();
      const pkg = packages.find(p => p.id === packageId);

      if (!pkg) return;

      selectedPackageId = packageId;

      // Update visual selection
      document.querySelectorAll('.package-option').forEach(opt => {
        opt.classList.toggle('selected', opt.dataset.packageId === packageId);
      });

      const url = new URL(window.location);
      url.searchParams.set('package', packageId);
      window.history.replaceState({}, '', url);

      renderPaymentButtons(pkg);
    }

    function renderPaymentButtons(pkg) {
      paymentButtons.innerHTML = '';

      if (!pkg || !pkg.paymentLinks) {
        showPaymentError();
        return;
      }

      const payments = [
        { name: 'Stripe', url: pkg.paymentLinks.stripe || '', icon: 'credit-card', color: '#635bff' },
        { name: 'PayPal', url: pkg.paymentLinks.paypal || '', icon: 'wallet', color: '#0070ba' },
        { name: 'Crypto', url: pkg.paymentLinks.crypto || '', icon: 'bitcoin', color: '#f7931a' }
      ];

      let hasPaymentLinks = false;

      payments.forEach(payment => {
        if (payment.url && payment.url.trim() !== '') {
          hasPaymentLinks = true;
          const button = document.createElement('a');
          button.href = payment.url;
          button.target = '_blank';
          button.rel = 'noopener noreferrer';
          button.className = 'btn btn-primary';
          button.style.backgroundColor = payment.color;
          button.style.borderColor = payment.color;
          button.innerHTML = '<i data-lucide="' + payment.icon + '" style="width: 20px; height: 20px;"></i> Pay with ' + payment.name;
          paymentButtons.appendChild(button);
        }
      });

      if (!hasPaymentLinks) {
        showPaymentError();
      }

      initIcons();
    }

    function showPaymentError() {
      paymentButtons.innerHTML = '<div style="grid-column: 1 / -1; padding: 1rem; background: color-mix(in srgb, var(--color-warning) 12%, #181818); border: 1px solid color-mix(in srgb, var(--color-warning) 30%, #282828); border-radius: var(--radius-lg); display: flex; align-items: center; gap: 0.75rem;"><i data-lucide="alert-triangle" style="width: 20px; height: 20px; color: #fbbf24;"></i><div><p style="font-weight: 600; color: #fbbf24; margin-bottom: 0.25rem;">Payment links not configured</p><p style="font-size: 0.875rem; color: #fbbf24;">Please configure payment links in the <a href="./admin/" style="text-decoration: underline; color: #fbbf24;">Admin Panel</a></p></div></div>';
      initIcons();
    }

    if (packageOptions && paymentButtons) {
      renderPackageOptions();
    }

    const form = document.getElementById('upgradeForm');
    if (form) {
      form.addEventListener('submit', async (e) => {
        e.preventDefault();
        const button = form.querySelector('button[type="submit"]');
        const alertContainer = document.getElementById('alertContainer');

        if (typeof CONFIG !== 'undefined' && (CONFIG.previewMode === true || !CONFIG.isConfigured)) {
          if (typeof scrollToElement === 'function') scrollToElement(alertContainer);
          return;
        }

        if (typeof clearAlerts === 'function') clearAlerts(alertContainer);

        const formData = {
          key: document.getElementById('upgradeKey').value.trim(),
          login: document.getElementById('spotifyLogin').value.trim(),
          password: document.getElementById('spotifyPassword').value,
          country: document.getElementById('country').value
        };

        if (typeof validateForm === 'function') {
          const errors = validateForm(formData, ['key', 'login', 'password', 'country']);
          if (errors.length > 0) {
            if (typeof showError === 'function') showError(errors.join('<br>'), alertContainer);
            return;
          }
        }

        if (typeof showLoading === 'function') showLoading(button);

        try {
          const response = await api.processUpgrade(formData);
          if (response.status === 'error') {
            if (typeof showError === 'function') showError(response.message || 'This key cannot be used for upgrade.', alertContainer);
          } else if (response.status === 'success' || response.status === 'in_queue' || response.status === 'upgrade_processing') {
            if (typeof showSuccess === 'function') showSuccess('Upgrade submitted successfully! Check the status page for updates.', alertContainer);
            form.reset();
          } else {
            if (typeof showError === 'function') showError(response.message || 'Upgrade failed. Please try again.', alertContainer);
          }
        } catch (error) {
          if (typeof showError === 'function') showError(error.message || 'An error occurred. Please try again.', alertContainer);
        } finally {
          if (typeof hideLoading === 'function') hideLoading(button);
        }
      });
    }

    const urlParams = new URLSearchParams(window.location.search);
    const keyFromUrl = urlParams.get('key');
    if (keyFromUrl && upgradeKeyInput) {
      upgradeKeyInput.value = keyFromUrl;
      upgradeKeyInput.dispatchEvent(new Event('input'));
    }
});
</script>
