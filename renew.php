<?php
$pageTitle = "Renew Premium";
$pageDescription = "Renew your Spotify Premium account and keep your playlists.";
$extraCss = ["./assets/css/country-dropdown.css"];
require_once __DIR__ . "/includes/header.php";
?>
  <main style="padding: 4rem 0; background: var(--color-neutral-50); min-height: calc(100vh - 72px);">
    <div class="container" style="max-width: 960px;">
      <div style="text-align: center; margin-bottom: 3rem;">
        <div style="display: inline-flex; align-items: center; justify-content: center; width: 64px; height: 64px; background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-primary-dark) 100%); border-radius: var(--radius-2xl); margin-bottom: 1.5rem; box-shadow: var(--shadow-lg);">
          <i data-lucide="refresh-cw" style="width: 32px; height: 32px; color: white;"></i>
        </div>
        <h1 style="font-size: 2.5rem; font-weight: 800; margin-bottom: 0.75rem; color: var(--color-neutral-900);">
          Renew Premium
        </h1>
        <p style="font-size: 1.125rem; color: var(--color-neutral-600);">
          Reset your key for another account or create a new account with all your music imported
        </p>
      </div>

      <div id="alertContainer"></div>

      <div class="card">
        <div style="margin-bottom: 1.5rem;">
          <h2 style="font-size: 1.5rem; font-weight: 700; color: var(--color-neutral-900); margin-bottom: 0.5rem;">
            Renew Your Premium
          </h2>
          <p style="color: var(--color-neutral-600);">Free renewal with your existing key - choose to reset your key for manual use or let us create a new account and import all your data automatically</p>
        </div>

        <form id="renewForm">
          <div style="display: grid; grid-template-columns: 1fr; gap: 2rem;">
            <div>
              <div class="form-group">
                <label for="renewalKey" class="form-label">
                  <i data-lucide="key" style="width: 16px; height: 16px; display: inline; vertical-align: middle; margin-right: 0.25rem;"></i>
                  Your Upgrade Key *
                </label>
                <input
                  type="text"
                  id="renewalKey"
                  name="key"
                  required
                  placeholder="Enter your upgrade key (Ex: XXXX-XXXX-XXXX-XXXX)"
                  class="form-input"
                >
                <div id="keyFormatHint" style="display: none;"></div>
              </div>

              <div id="renewFields" class="fields-container" style="display: none;">
                <div style="margin-top: 2rem; padding-top: 2rem; border-top: 1px solid var(--color-neutral-200);">
                  <h3 style="font-size: 1.125rem; font-weight: 700; color: var(--color-neutral-900); margin-bottom: 1rem;">
                    <i data-lucide="user" style="width: 18px; height: 18px; display: inline; vertical-align: middle; margin-right: 0.5rem;"></i>
                    Account Details
                  </h3>

                  <!-- Spotify Email/Username -->
                  <div class="form-group">
                    <label for="spotifyLogin" class="form-label">
                      Spotify Email or Username *
                    </label>
                    <input
                      type="text"
                      id="spotifyLogin"
                      name="login"
                      required
                      placeholder="your.email@example.com"
                      class="form-input"
                      autocomplete="username"
                    >
                  </div>

                  <!-- Spotify Password -->
                  <div class="form-group">
                    <label for="spotifyPassword" class="form-label">
                      Current Spotify Password *
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
                </div>

                <!-- New Account Section - can be disabled by admin -->
                <div id="newAccountSection" style="margin-top: 2rem; padding: 1.5rem; background: color-mix(in srgb, var(--color-info) 12%, #181818); border: 2px solid color-mix(in srgb, var(--color-info) 30%, #282828); border-radius: var(--radius-xl);">
                  <div style="display: flex; align-items: flex-start; gap: 1rem; margin-bottom: 1rem;">
                    <i data-lucide="sparkles" id="newAccountIcon" style="width: 24px; height: 24px; color: #7cb8ff; flex-shrink: 0;"></i>
                    <div>
                      <h3 id="newAccountTitle" style="font-size: 1.125rem; font-weight: 700; color: #7cb8ff; margin-bottom: 0.5rem;">Create New Account (Optional)</h3>
                      <p id="newAccountDescription" style="font-size: 0.875rem; color: #93bbf5;">
                        Spotify limits account renewals. Automatically create & upgrade a new account with data transfer.
                      </p>
                    </div>
                  </div>

                  <div id="newAccountFields">
                    <div class="form-group" style="margin-bottom: 1rem;">
                      <label for="newEmail" class="form-label" style="color: #7cb8ff;">
                        <i data-lucide="mail" style="width: 16px; height: 16px; display: inline; vertical-align: middle; margin-right: 0.25rem;"></i>
                        New Account Email
                      </label>
                      <div style="display: flex; gap: 0.5rem;">
                        <input
                          type="email"
                          id="newEmail"
                          name="new_email"
                          placeholder="new.account@example.com"
                          class="form-input"
                          style="flex: 1;"
                        >
                        <button type="button" id="generateEmailBtn" class="btn btn-secondary" style="white-space: nowrap; padding: 0.75rem 1rem;" title="Generate random email">
                          <i data-lucide="shuffle" style="width: 18px; height: 18px;"></i>
                          <span style="display: none;" class="md-show">Random</span>
                        </button>
                      </div>
                      <span class="form-hint" style="color: #93bbf5;">Email can be random and doesn't need to be valid. Update later in Spotify settings if needed.</span>
                    </div>

                    <div class="form-group" style="margin-bottom: 0;">
                      <label for="country" class="form-label" style="color: #7cb8ff;">
                        <i data-lucide="globe" style="width: 16px; height: 16px; display: inline; vertical-align: middle; margin-right: 0.25rem;"></i>
                        Country
                      </label>
                      <select
                        id="country"
                        name="country"
                        class="form-select"
                        data-country-dropdown
                      >
                        <option value="">Select country for new account</option>
                      </select>
                      <span class="form-hint" style="color: #93bbf5;">Country is required when creating a new account with email change.</span>
                    </div>
                  </div>
                </div>

                <div style="margin-top: 1.5rem; margin-bottom: 1.5rem;">
                  <label style="display: flex; align-items: flex-start; gap: 0.75rem; cursor: pointer;">
                    <input
                      type="checkbox"
                      id="terms"
                      required
                      style="margin-top: 0.125rem;"
                    >
                    <span style="font-size: 0.875rem; color: var(--color-neutral-700); line-height: 1.6;">
                      I agree that my account details are correct and I understand that incorrect information may result in a failed renewal. *
                    </span>
                  </label>
                </div>

                <button
                  type="submit"
                  class="btn btn-primary btn-lg"
                  style="width: 100%;"
                >
                  <i data-lucide="check-circle" style="width: 20px; height: 20px;"></i>
                  <span>Renew Premium</span>
                </button>
              </div>
            </div>
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
              What are my renewal options?
            </h3>
            <p style="font-size: 0.875rem; color: var(--color-neutral-600); line-height: 1.6; padding-left: 1.75rem;">
              Renewal is FREE (lifetime warranty) with two options:<br>
              <strong>1. Reset Key Only:</strong> Just enter your key and old account details (skip "Create New Account" section). Your key will be reset so you can manually use it on another Spotify account you already have.<br>
              <strong>2. Automatic New Account:</strong> Fill in the "Create New Account" section and the system will create a fresh Premium account and automatically import all your playlists, liked songs, followed artists, and listening history.
            </p>
          </div>

          <div>
            <h3 style="font-size: 1rem; font-weight: 600; margin-bottom: 0.5rem; color: var(--color-neutral-900);">
              <i data-lucide="calendar" style="width: 16px; height: 16px; display: inline; vertical-align: middle; margin-right: 0.5rem; color: var(--color-primary);"></i>
              Why do I need a different account?
            </h3>
            <p style="font-size: 0.875rem; color: var(--color-neutral-600); line-height: 1.6; padding-left: 1.75rem;">
              Spotify allows an account to be upgraded only once per year. If your last upgrade was less than a year ago, you'll need to use a different account. You can either use an existing account you have (Option 1) or let us create a new one for you (Option 2). If it's been more than a year, you can upgrade the same account on the <a href="upgrade.php" style="text-decoration: underline; color: var(--color-primary); font-weight: 600;">Upgrade</a> page.
            </p>
          </div>

          <div>
            <h3 style="font-size: 1rem; font-weight: 600; margin-bottom: 0.5rem; color: var(--color-neutral-900);">
              <i data-lucide="shield-check" style="width: 16px; height: 16px; display: inline; vertical-align: middle; margin-right: 0.5rem; color: var(--color-primary);"></i>
              Will I lose my playlists if I create a new account?
            </h3>
            <p style="font-size: 0.875rem; color: var(--color-neutral-600); line-height: 1.6; padding-left: 1.75rem;">
              No! If you use the "Create New Account" option, the system automatically imports everything: all playlists, saved songs, followed artists, podcasts, listening history, and preferences. You can also update the new account's email to your original one in Spotify settings after renewal. If you just reset your key (Option 1), you'll use it on an account you already have.
            </p>
          </div>

          <div>
            <h3 style="font-size: 1rem; font-weight: 600; margin-bottom: 0.5rem; color: var(--color-neutral-900);">
              <i data-lucide="clock" style="width: 16px; height: 16px; display: inline; vertical-align: middle; margin-right: 0.5rem; color: var(--color-primary);"></i>
              How long does renewal take?
            </h3>
            <p style="font-size: 0.875rem; color: var(--color-neutral-600); line-height: 1.6; padding-left: 1.75rem;">
              Both renewal types typically take 5-10 minutes:<br>
              <strong>Reset Key Only:</strong> Your key is reset and you can use it on another account via the <a href="upgrade.php" style="text-decoration: underline; color: var(--color-primary); font-weight: 600;">Upgrade</a> page.<br>
              <strong>Automatic New Account:</strong> The system creates your new account, transfers all data, and activates Premium.
            </p>
          </div>

          <div>
            <h3 style="font-size: 1rem; font-weight: 600; margin-bottom: 0.5rem; color: var(--color-neutral-900);">
              <i data-lucide="alert-circle" style="width: 16px; height: 16px; display: inline; vertical-align: middle; margin-right: 0.5rem; color: var(--color-primary);"></i>
              What if my account never had Premium?
            </h3>
            <p style="font-size: 0.875rem; color: var(--color-neutral-600); line-height: 1.6; padding-left: 1.75rem;">
              Renewal is only for accounts that previously had Premium with us. If you're getting Premium for the first time, use the <a href="upgrade.php" style="text-decoration: underline; color: var(--color-primary); font-weight: 600;">Upgrade</a> page instead.
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

    const apiKeyWarning = checkApiKey();
    if (apiKeyWarning) {
      document.getElementById('alertContainer').appendChild(apiKeyWarning);
    }

    // Check feature status for new account section
    let isNewAccountDisabled = false;
    let newAccountDisabledMessage = '';
    try {
      const featuresData = await api.getFeatures();
      if (featuresData.status === 'success' && featuresData.features) {
        const renewDisabled = featuresData.features.renew?.disabled || false;
        const newAccountDisabled = featuresData.features.renew_new_account?.disabled || false;

        if (renewDisabled || newAccountDisabled) {
          isNewAccountDisabled = true;
          newAccountDisabledMessage = renewDisabled
            ? (featuresData.features.renew?.message || 'Renewals are temporarily disabled.')
            : (featuresData.features.renew_new_account?.message || 'New account creation is temporarily disabled.');

          // Update UI to show disabled state
          const section = document.getElementById('newAccountSection');
          const fields = document.getElementById('newAccountFields');
          const title = document.getElementById('newAccountTitle');
          const description = document.getElementById('newAccountDescription');
          const icon = document.getElementById('newAccountIcon');

          if (section && fields && title && description) {
            // Change styling to disabled/gray
            section.style.background = 'var(--color-neutral-100)';
            section.style.borderColor = 'var(--color-neutral-300)';

            // Update icon
            icon.setAttribute('data-lucide', 'alert-circle');
            icon.style.color = 'var(--color-neutral-500)';

            // Update title and description
            title.textContent = 'New Account Creation Unavailable';
            title.style.color = 'var(--color-neutral-700)';
            description.textContent = newAccountDisabledMessage;
            description.style.color = 'var(--color-neutral-600)';

            // Hide the form fields
            fields.style.display = 'none';

            // Re-init icons to update the icon
            initIcons();
          }
        }
      }
    } catch (e) {
      // Silently fail - default to enabled
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
    const renewalKeyInput = document.getElementById('renewalKey');
    const renewFields = document.getElementById('renewFields');
    const keyFormatHint = document.getElementById('keyFormatHint');

    renewalKeyInput.addEventListener('input', () => {
      const keyValue = renewalKeyInput.value.trim();

      if (isValidKeyFormat(keyValue)) {
        renewFields.style.display = 'block';
        keyFormatHint.style.display = 'none';
        initIcons();
      } else {
        renewFields.style.display = 'none';

        if (keyValue.length > 0) {
          keyFormatHint.style.display = 'block';
          keyFormatHint.className = 'key-format-error';
          keyFormatHint.textContent = 'Key format should be XXXX-XXXX-XXXX-XXXX';
        } else {
          keyFormatHint.style.display = 'none';
        }
      }
    });

    function generateRandomEmail() {
      const chars = 'abcdefghijklmnopqrstuvwxyz0123456789';
      const domains = ['gmail.com', 'outlook.com', 'yahoo.com'];
      let name = '';
      for (let i = 0; i < 10; i++) {
        name += chars.charAt(Math.floor(Math.random() * chars.length));
      }
      const domain = domains[Math.floor(Math.random() * domains.length)];
      return `${name}@${domain}`;
    }

    document.getElementById('generateEmailBtn').addEventListener('click', () => {
      const emailInput = document.getElementById('newEmail');
      emailInput.value = generateRandomEmail();
      const countrySelect = document.getElementById('country');
      if (!countrySelect.value && countrySelect.options.length > 1) {
        // Country field flash to indicate it needs selection
        countrySelect.style.borderColor = 'var(--color-warning)';
        setTimeout(() => {
          countrySelect.style.borderColor = '';
        }, 2000);
      }
    });

    document.getElementById('renewForm').addEventListener('submit', async (e) => {
      e.preventDefault();

      const form = e.target;
      const button = form.querySelector('button[type="submit"]');
      const alertContainer = document.getElementById('alertContainer');

      // Preview mode check
      if (CONFIG.previewMode === true || !CONFIG.isConfigured) {
        scrollToElement(alertContainer);
        return;
      }

      clearAlerts(alertContainer);

      const formData = {
        key: document.getElementById('renewalKey').value.trim(),
        login: document.getElementById('spotifyLogin').value.trim(),
        password: document.getElementById('spotifyPassword').value,
        new_email: document.getElementById('newEmail').value.trim() || undefined,
        country: document.getElementById('country').value
      };

      const requiredFields = ['key', 'login', 'password'];
      const errors = validateForm(formData, requiredFields);

      if (formData.new_email && !formData.country) {
        errors.push('Please select a country when creating a new account');
      }

      if (errors.length > 0) {
        showError(errors.join('<br>'), alertContainer);
        return;
      }

      showLoading(button);

      try {
        const response = await api.processRenewal(formData);

        if (response.status === 'error') {
          showError(response.message || 'This key cannot be used for renewal.', alertContainer);
        } else if (response.status === 'success' || response.status === 'in_queue' || response.status === 'renew_processing') {
          showProcessingStatus(formData.key, alertContainer, 'renewal');

          form.reset();
        } else if (response.status === 'already_processing') {
          showWarning('This key is already being processed. Check the status page for updates.', alertContainer);

          setTimeout(() => {
            const infoLink = document.createElement('div');
            infoLink.style.marginTop = '1rem';
            infoLink.style.textAlign = 'center';
            infoLink.innerHTML = `
              <a href="info.php?key=${encodeURIComponent(formData.key)}" class="btn btn-secondary">
                <i data-lucide="search" style="width: 20px; height: 20px;"></i>
                Check Renewal Status
              </a>
            `;
            alertContainer.appendChild(infoLink);
            initIcons();
          }, 500);
        } else {
          showError(response.message || 'Renewal failed. Please check your details and try again.', alertContainer);
        }
      } catch (error) {
        if (error.isMaintenance) {
          showMaintenance(error.message, alertContainer);
        } else {
          showError(error.message || 'An error occurred. Please try again or contact support.', alertContainer);
        }
      } finally {
        hideLoading(button);
      }
    });

    function showProcessingStatus(key, container, type = 'upgrade') {
      const title = type === 'renewal' ? 'Renewing Your Account' : 'Upgrading Your Account';
      const processingDiv = document.createElement('div');
      processingDiv.className = 'processing-status';
      processingDiv.style.cssText = 'background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%); border: 2px solid #509bf5; border-radius: 1rem; padding: 2rem; text-align: center; margin-bottom: 1.5rem;';
      processingDiv.innerHTML = `
        <div style="margin-bottom: 1.5rem;">
          <div style="width: 64px; height: 64px; background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-primary-dark) 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto;">
            <svg class="animate-spin" style="width: 32px; height: 32px; color: white;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
          </div>
        </div>
        <h3 style="font-size: 1.5rem; font-weight: 700; color: #7cb8ff; margin-bottom: 0.5rem;">${title}</h3>
        <p style="color: #93bbf5; margin-bottom: 1.5rem;">Your renewal is being processed. This usually takes 5-10 minutes.</p>
        <div style="display: flex; flex-direction: column; gap: 1rem; align-items: center;">
          <a href="info.php?key=${encodeURIComponent(key)}" class="btn btn-primary" style="display: inline-flex; align-items: center; gap: 0.5rem;">
            <i data-lucide="search" style="width: 20px; height: 20px;"></i>
            Check Status
          </a>
          <p style="font-size: 0.875rem; color: #7cb8ff;">You can close this page - the renewal will continue in the background</p>
        </div>
      `;
      container.prepend(processingDiv);
      scrollToElement(container);
      initIcons();
    }

    function showWarning(message, container) {
      const alert = document.createElement('div');
      alert.className = 'bg-yellow-50 border border-yellow-200 text-yellow-800 px-6 py-4 rounded-lg mb-6';
      alert.innerHTML = `
        <div class="flex items-start">
          <svg class="w-6 h-6 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
          </svg>
          <div>
            <h3 class="font-semibold mb-1">Already Processing</h3>
            <p>${message}</p>
          </div>
        </div>
      `;

      if (container) {
        container.prepend(alert);
        scrollToElement(container);
      }
    }

    function openMobileNav() {
      const mobileNav = document.getElementById('mobileNav');
      mobileNav.classList.add('active');
      document.body.style.overflow = 'hidden';
      initIcons();
    }

    function closeMobileNav(event) {
      if (event && event.target !== event.currentTarget) return;
      const mobileNav = document.getElementById('mobileNav');
      mobileNav.classList.remove('active');
      document.body.style.overflow = '';
    }

    (function() {
      const dropdown = document.querySelector('.nav-dropdown');
      const toggle = document.querySelector('.nav-dropdown-toggle');
      const menu = document.querySelector('.nav-dropdown-menu');
      const items = menu ? menu.querySelectorAll('.nav-dropdown-item') : [];
      let currentIndex = -1;

      function openDropdown() {
        dropdown.classList.add('open');
        toggle.setAttribute('aria-expanded', 'true');
        items.forEach(item => item.setAttribute('tabindex', '0'));
      }

      function closeDropdown() {
        dropdown.classList.remove('open');
        toggle.setAttribute('aria-expanded', 'false');
        items.forEach(item => item.setAttribute('tabindex', '-1'));
        currentIndex = -1;
      }

      function focusItem(index) {
        if (index >= 0 && index < items.length) {
          currentIndex = index;
          items[index].focus();
        }
      }

      if (toggle) {
        toggle.addEventListener('click', function(e) {
          e.preventDefault();
          if (dropdown.classList.contains('open')) {
            closeDropdown();
          } else {
            openDropdown();
            if (items.length > 0) focusItem(0);
          }
        });

        toggle.addEventListener('keydown', function(e) {
          if (e.key === 'Enter' || e.key === ' ' || e.key === 'ArrowDown') {
            e.preventDefault();
            openDropdown();
            if (items.length > 0) focusItem(0);
          } else if (e.key === 'Escape') {
            closeDropdown();
            toggle.focus();
          }
        });
      }

      items.forEach((item, index) => {
        item.addEventListener('keydown', function(e) {
          if (e.key === 'ArrowDown') {
            e.preventDefault();
            focusItem((index + 1) % items.length);
          } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            focusItem((index - 1 + items.length) % items.length);
          } else if (e.key === 'Escape') {
            e.preventDefault();
            closeDropdown();
            toggle.focus();
          } else if (e.key === 'Tab') {
            closeDropdown();
          }
        });
      });

      document.addEventListener('click', function(e) {
        if (dropdown && !dropdown.contains(e.target)) closeDropdown();
      });
    })();
});
</script>
