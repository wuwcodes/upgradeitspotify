<?php
$pageTitle = "Check Key Status";
$pageDescription = "Check your Spotify Premium upgrade key status.";
require_once __DIR__ . "/includes/header.php";
?>
  <main style="max-width: 1000px; margin: 0 auto; padding: 3rem 1rem; min-height: calc(100vh - 72px);">
    <div style="text-align: center; margin-bottom: 3rem;" class="animate-fade-in">
      <div style="width: 80px; height: 80px; background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-primary-dark) 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem;">
        <i data-lucide="search" style="width: 40px; height: 40px; color: white;"></i>
      </div>
      <h1 style="font-size: 2.5rem; font-weight: 800; color: var(--color-neutral-900); margin-bottom: 0.5rem;">
        Check Key Status
      </h1>
      <p style="font-size: 1.125rem; color: var(--color-neutral-600);">
        Track your upgrade or renewal progress in real-time
      </p>
    </div>

    <div id="alertContainer" style="margin-bottom: 1.5rem;"></div>

    <div class="card" style="margin-bottom: 2rem;" class="animate-fade-in">
      <form id="keyInfoForm">
        <div class="form-group" style="margin-bottom: 0;">
          <label class="form-label">Enter Your Key</label>
          <div style="display: flex; gap: 1rem;">
            <input
              type="text"
              id="keyInput"
              name="key"
              required
              placeholder="Enter your upgrade key"
              class="form-input"
              style="flex: 1;"
            >
            <button type="submit" class="btn btn-primary">
              <i data-lucide="search" style="width: 20px; height: 20px;"></i>
              Check Status
            </button>
          </div>
        </div>
      </form>
    </div>

    <div id="keyInfoDisplay" style="display: none;">
      <div class="card animate-fade-in">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.5rem;">
          <h2 style="font-size: 1.5rem; font-weight: 700; color: var(--color-neutral-900);">Key Status</h2>
          <div id="statusBadge"></div>
        </div>

        <div id="explanationContent" style="margin-bottom: 2rem;"></div>

        <div id="keyDetailsSection" style="border-top: 1px solid var(--color-neutral-200); padding-top: 1.5rem;">
          <p style="font-size: 0.875rem; font-weight: 600; color: var(--color-neutral-500); margin-bottom: 1rem; text-transform: uppercase; letter-spacing: 0.05em;">Key Details</p>

          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1rem;">
            <div id="usernameContainer" style="display: none;">
              <p style="font-size: 0.75rem; font-weight: 600; color: var(--color-neutral-500); margin-bottom: 0.25rem;">
                <i data-lucide="user" style="width: 12px; height: 12px; display: inline; vertical-align: middle; margin-right: 0.25rem;"></i>
                Spotify Username
              </p>
              <p style="font-size: 1rem; font-weight: 600; color: var(--color-neutral-900); word-break: break-all;" id="displayUsername">-</p>
            </div>
            <div>
              <p style="font-size: 0.75rem; font-weight: 600; color: var(--color-neutral-500); margin-bottom: 0.25rem;">Key Used</p>
              <p style="font-size: 1rem; font-weight: 600;" id="displayUsed">-</p>
            </div>
          </div>

          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1rem;">
            <div>
              <p style="font-size: 0.75rem; font-weight: 600; color: var(--color-neutral-500); margin-bottom: 0.25rem;">
                <i data-lucide="calendar" style="width: 12px; height: 12px; display: inline; vertical-align: middle; margin-right: 0.25rem;"></i>
                Purchase Date
              </p>
              <p style="font-size: 1rem; font-weight: 600; color: var(--color-neutral-900);" id="displayPurchaseDate">-</p>
            </div>
            <div id="usedDateContainer" style="display: none;">
              <p style="font-size: 0.75rem; font-weight: 600; color: var(--color-neutral-500); margin-bottom: 0.25rem;">
                <i data-lucide="clock" style="width: 12px; height: 12px; display: inline; vertical-align: middle; margin-right: 0.25rem;"></i>
                Used Date
              </p>
              <p style="font-size: 1rem; font-weight: 600; color: var(--color-neutral-900);" id="displayUsedDate">-</p>
            </div>
          </div>

          <div id="inviteContainer" style="display: none; margin-bottom: 1rem;">
            <p style="font-size: 0.75rem; font-weight: 600; color: var(--color-neutral-500); margin-bottom: 0.25rem;">
              <i data-lucide="ticket" style="width: 12px; height: 12px; display: inline; vertical-align: middle; margin-right: 0.25rem;"></i>
              Invite Token
            </p>
            <div style="display: flex; align-items: center; gap: 0.5rem;">
              <p style="font-size: 1rem; font-family: monospace; color: var(--color-primary); font-weight: 600;" id="displayInvite">-</p>
              <button id="copyInviteBtn" onclick="copyInvite()" style="background: none; border: none; cursor: pointer; padding: 0.25rem; color: var(--color-neutral-500); transition: color 0.2s;">
                <i data-lucide="copy" style="width: 16px; height: 16px;"></i>
              </button>
            </div>
          </div>

          <div id="addressContainer" style="display: none; margin-bottom: 1rem;">
            <p style="font-size: 0.75rem; font-weight: 600; color: var(--color-neutral-500); margin-bottom: 0.25rem;">
              <i data-lucide="mail" style="width: 12px; height: 12px; display: inline; vertical-align: middle; margin-right: 0.25rem;"></i>
              Invite Address
            </p>
            <p style="font-size: 1rem; font-weight: 600; color: var(--color-neutral-900);" id="displayAddress">-</p>
          </div>

          <div id="verifyContainer" style="display: none;">
            <p style="font-size: 0.75rem; font-weight: 600; color: var(--color-neutral-500); margin-bottom: 0.25rem;">
              <i data-lucide="shield-check" style="width: 12px; height: 12px; display: inline; vertical-align: middle; margin-right: 0.25rem;"></i>
              Verification Address
              <span style="font-weight: 400; font-size: 0.65rem;">(sometimes asked by Spotify)</span>
            </p>
            <div style="display: flex; align-items: center; gap: 0.5rem;">
              <p style="font-size: 1rem; font-weight: 600; color: var(--color-neutral-900);" id="displayVerify">-</p>
              <button id="copyVerifyBtn" onclick="copyVerifyAddress()" style="background: none; border: none; cursor: pointer; padding: 0.25rem; color: var(--color-neutral-500); transition: color 0.2s;">
                <i data-lucide="copy" style="width: 16px; height: 16px;"></i>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

  </main>

<?php require_once __DIR__ . "/includes/footer.php"; ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    initIcons();

    const apiKeyWarning = checkApiKey();
    if (apiKeyWarning) {
      document.getElementById('alertContainer').appendChild(apiKeyWarning);
    }

    const urlParams = new URLSearchParams(window.location.search);
    const keyParam = urlParams.get('key');
    if (keyParam) {
      document.getElementById('keyInput').value = keyParam;
      setTimeout(() => {
        document.getElementById('keyInfoForm').dispatchEvent(new Event('submit'));
      }, 500);
    }

    document.getElementById('keyInfoForm').addEventListener('submit', async (e) => {
      e.preventDefault();

      const form = e.target;
      const button = form.querySelector('button[type="submit"]');
      const alertContainer = document.getElementById('alertContainer');
      const keyInfoDisplay = document.getElementById('keyInfoDisplay');

      if (CONFIG.previewMode === true || !CONFIG.isConfigured) {
        scrollToElement(alertContainer);
        return;
      }

      clearAlerts(alertContainer);
      keyInfoDisplay.style.display = 'none';

      const key = document.getElementById('keyInput').value.trim();

      if (!key) {
        showError('Please enter a key', alertContainer);
        return;
      }

      showLoading(button);

      try {
        const response = await api.getKeyInfo(key);
        displayKeyInfo(response);
        keyInfoDisplay.style.display = 'block';
        scrollToElement(keyInfoDisplay);
      } catch (error) {
        if (error.isMaintenance) {
          showMaintenance(error.message, alertContainer);
        } else {
          showError(error.message || 'Unable to retrieve key information. Please check your key and try again.', alertContainer);
        }
      } finally {
        hideLoading(button);
      }
    });

    function displayKeyInfo(data) {
      const statusBadge = document.getElementById('statusBadge');
      const status = data.status || 'unknown';
      const used = data.used;
      const message = data.message;

      let badgeClass = 'badge badge-neutral';
      let badgeIcon = 'help-circle';
      let badgeText = status;

      const isProcessing = ['upgrade_processing', 'renew_processing', 'in_queue'].includes(status);

      const isBanned = status === 'banned';

      const isKeyReady = (!used && status === 'failed' && message === 'none') || (status === 'none' && !used);

      const isResetKey = status === 'failed_upgrade' && !used;

      const isAlreadyPremium = status === 'failed_upgrade' && !used && message && message.toLowerCase().includes('already premium');

      const is12MonthRestriction = status === 'failed_upgrade' && !used && message && (message.toLowerCase().includes('12 month') || message.toLowerCase().includes('year'));

      const isAccountDetailsInvalid = status === 'failed_renew' && message && (message.toLowerCase().includes('invalid') || message.toLowerCase().includes('details'));

      const isAccountMismatch = (status === 'failed_renew' && !isAccountDetailsInvalid) || (message && message.includes('not been upgraded by this key'));

      if (status === 'success' || status === 'success_renew') {
        badgeClass = 'badge badge-success';
        badgeIcon = 'check-circle';
        badgeText = status === 'success_renew' ? 'Renewed' : 'Success';
      } else if (isBanned) {
        badgeClass = 'badge badge-warning';
        badgeIcon = 'ban';
        badgeText = 'Suspended';
      } else if (isProcessing) {
        badgeClass = 'badge badge-warning';
        badgeIcon = 'clock';
        badgeText = 'Processing';
      } else if (isKeyReady) {
        badgeClass = 'badge badge-success';
        badgeIcon = 'key';
        badgeText = 'Ready to Use';
      } else if (isAlreadyPremium) {
        badgeClass = 'badge badge-warning';
        badgeIcon = 'alert-triangle';
        badgeText = 'Already Premium';
      } else if (is12MonthRestriction) {
        badgeClass = 'badge badge-warning';
        badgeIcon = 'alert-triangle';
        badgeText = '12 Month Limit';
      } else if (isResetKey) {
        badgeClass = 'badge badge-warning';
        badgeIcon = 'alert-circle';
        badgeText = 'Reset - Try Again';
      } else if (isAccountDetailsInvalid) {
        badgeClass = 'badge badge-warning';
        badgeIcon = 'alert-triangle';
        badgeText = 'Details Invalid';
      } else if (isAccountMismatch) {
        badgeClass = 'badge badge-warning';
        badgeIcon = 'alert-triangle';
        badgeText = 'Account Mismatch';
      } else if (status === 'failed' || status === 'failed_upgrade') {
        badgeClass = 'badge badge-error';
        badgeIcon = 'x-circle';
        badgeText = 'Failed';
      } else if (status === 'not_exist' || status === 'error') {
        badgeClass = 'badge badge-neutral';
        badgeIcon = 'help-circle';
        badgeText = 'Not Found';
      } else if (used) {
        badgeClass = 'badge badge-neutral';
        badgeIcon = 'check';
        badgeText = 'Used';
      }

      statusBadge.className = badgeClass;
      statusBadge.innerHTML = `<i data-lucide="${badgeIcon}" style="width: 16px; height: 16px;"></i> ${badgeText}`;
      initIcons();

      const usedEl = document.getElementById('displayUsed');
      if (isBanned) {
        usedEl.textContent = 'Suspended';
        usedEl.style.color = '#f59e0b';
      } else if (isProcessing) {
        usedEl.textContent = 'Processing...';
        usedEl.style.color = '#f59e0b';
      } else if (used) {
        usedEl.textContent = 'Yes';
        usedEl.style.color = '#6b7280';
      } else {
        usedEl.textContent = 'No (Available)';
        usedEl.style.color = '#10b981';
      }

      document.getElementById('displayPurchaseDate').textContent = formatDate(data.purchase_date);

      // Reset optional containers
      document.getElementById('usernameContainer').style.display = 'none';
      document.getElementById('usedDateContainer').style.display = 'none';
      document.getElementById('inviteContainer').style.display = 'none';
      document.getElementById('addressContainer').style.display = 'none';
      document.getElementById('verifyContainer').style.display = 'none';

      if (data.username) {
        document.getElementById('usernameContainer').style.display = 'block';
        document.getElementById('displayUsername').textContent = data.username;
      }

      if (data.used_date && data.used_date !== 'Never Used') {
        document.getElementById('usedDateContainer').style.display = 'block';
        document.getElementById('displayUsedDate').textContent = formatDate(data.used_date);
      }

      if (data.invite) {
        document.getElementById('inviteContainer').style.display = 'block';
        document.getElementById('displayInvite').textContent = data.invite;
        window.inviteToken = data.invite;
      }

      if (data.address) {
        document.getElementById('addressContainer').style.display = 'block';
        document.getElementById('displayAddress').textContent = data.address;
      }

      if (data.verify_address && data.verify_address.trim() !== '') {
        document.getElementById('verifyContainer').style.display = 'block';
        document.getElementById('displayVerify').textContent = data.verify_address;
        window.verifyAddress = data.verify_address;
      }

      displayStatusExplanation(status, message, used, isProcessing, isKeyReady, isResetKey, isAccountDetailsInvalid, isAccountMismatch, isAlreadyPremium, is12MonthRestriction, isBanned);
      initIcons();
    }

    function displayStatusExplanation(status, message, used, isProcessing, isKeyReady, isResetKey, isAccountDetailsInvalid, isAccountMismatch, isAlreadyPremium, is12MonthRestriction, isBanned) {
      const container = document.getElementById('explanationContent');
      let content = '';

      if (isBanned) {
        content = `
          <div class="alert alert-warning" style="margin-bottom: 0;">
            <i data-lucide="ban" class="alert-icon"></i>
            <div class="alert-content">
              <strong>Key Suspended</strong><br>
              ${message || 'This key has been suspended due to a chargeback dispute. Please contact support for assistance.'}
            </div>
          </div>
        `;
      } else if (isProcessing) {
        content = `
          <div class="alert alert-warning" style="margin-bottom: 0;">
            <i data-lucide="clock" class="alert-icon"></i>
            <div class="alert-content">
              <strong>Processing Your Request</strong><br>
              ${status === 'renew_processing' ? 'Your renewal' : 'Your upgrade'} is currently being processed. This usually takes 5-10 minutes.
            </div>
          </div>
        `;
      } else if (status === 'success' || status === 'success_renew') {
        content = `
          <div class="alert alert-success" style="margin-bottom: 0;">
            <i data-lucide="check-circle" class="alert-icon"></i>
            <div class="alert-content">
              <strong>Great news!</strong> Your account has been successfully ${status === 'success_renew' ? 'renewed' : 'upgraded'} to Premium. You can now enjoy all Premium features including offline listening, ad-free music, and high-quality audio.
            </div>
          </div>
        `;
      } else if (isKeyReady) {
        content = `
          <div class="alert alert-success" style="margin-bottom: 0;">
            <i data-lucide="key" class="alert-icon"></i>
            <div class="alert-content">
              <strong>Key Ready to Use!</strong><br>
              This key is valid and ready to be used for upgrading a Spotify account to Premium. Visit the <a href="upgrade.php" style="color: var(--color-primary); font-weight: 600; text-decoration: underline;">Upgrade page</a> to use this key.
            </div>
          </div>
        `;
      } else if (isAlreadyPremium) {
        content = `
          <div class="alert alert-warning" style="margin-bottom: 0;">
            <i data-lucide="alert-triangle" class="alert-icon"></i>
            <div class="alert-content">
              <strong>Account Already Premium</strong><br>
              The Spotify account you tried to upgrade already has an active Premium subscription. Your key has been automatically reset - please use a different account that does not have Premium, or wait for the current subscription to expire.<br>
              <span style="margin-top: 0.5rem; display: block;">Go to the <a href="upgrade.php" style="color: var(--color-warning); font-weight: 600; text-decoration: underline;">Upgrade page</a> to try with a different account.</span>
            </div>
          </div>
        `;
      } else if (is12MonthRestriction) {
        content = `
          <div class="alert alert-warning" style="margin-bottom: 0;">
            <i data-lucide="alert-triangle" class="alert-icon"></i>
            <div class="alert-content">
              <strong>12 Month Restriction</strong><br>
              This Spotify account has joined a family plan in the last 12 months. Spotify only allows one family join per year. Your key has been automatically reset - please use a different account.<br>
              <span style="margin-top: 0.5rem; display: block;">Go to the <a href="upgrade.php" style="color: var(--color-warning); font-weight: 600; text-decoration: underline;">Upgrade page</a> to try with a different account.</span>
            </div>
          </div>
        `;
      } else if (isResetKey) {
        // Check for specific error reasons
        let resetMessage = 'The previous upgrade attempt failed, but your key has been automatically reset. You can try again with different account credentials.';
        const msgLower = message ? message.toLowerCase() : '';

        if (msgLower.includes('stock') || msgLower.includes('location')) {
          resetMessage = 'The selected country was out of stock when you tried to upgrade. Your key has been automatically reset - please try again with a different country.';
        } else if (msgLower.includes('invalid') || msgLower.includes('details') || msgLower.includes('credentials')) {
          resetMessage = 'The account details provided were invalid. Your key has been automatically reset - please verify your Spotify login and password and try again.';
        } else if (msgLower.includes('locked') || msgLower.includes('banned')) {
          resetMessage = 'Your Spotify account appears to be temporarily locked. Your key has been automatically reset - please wait a few hours or try with a different account.';
        }

        content = `
          <div class="alert alert-warning" style="margin-bottom: 0;">
            <i data-lucide="alert-circle" class="alert-icon"></i>
            <div class="alert-content">
              <strong>Previous Upgrade Failed - Key Reset</strong><br>
              ${resetMessage}<br>
              <span style="margin-top: 0.5rem; display: block;">Go to the <a href="upgrade.php" style="color: var(--color-warning); font-weight: 600; text-decoration: underline;">Upgrade page</a> to try again.</span>
            </div>
          </div>
        `;
      } else if (isAccountDetailsInvalid) {
        content = `
          <div class="alert alert-warning" style="margin-bottom: 0;">
            <i data-lucide="alert-triangle" class="alert-icon"></i>
            <div class="alert-content">
              <strong>Account Details Invalid</strong><br>
              The account details provided (username/password) are invalid. Please make sure you're entering the correct Spotify credentials and try again.
            </div>
          </div>
        `;
      } else if (isAccountMismatch) {
        content = `
          <div class="alert alert-warning" style="margin-bottom: 0;">
            <i data-lucide="alert-triangle" class="alert-icon"></i>
            <div class="alert-content">
              <strong>Account Mismatch</strong><br>
              This key was used to upgrade a different Spotify account. To renew, you must use the same account that was originally upgraded with this key.
            </div>
          </div>
        `;
      } else if (status === 'failed' || status === 'failed_upgrade') {
        content = `
          <div class="alert alert-error" style="margin-bottom: 0;">
            <i data-lucide="x-circle" class="alert-icon"></i>
            <div class="alert-content">
              <strong>Upgrade Failed</strong><br>
              ${message && message !== 'none' ? message : 'The upgrade process encountered an error.'}<br>
              <span style="margin-top: 0.5rem; display: block;">Please contact support if you need assistance.</span>
            </div>
          </div>
        `;
      } else if (status === 'not_exist' || status === 'error') {
        content = `
          <div class="alert alert-info" style="margin-bottom: 0;">
            <i data-lucide="help-circle" class="alert-icon"></i>
            <div class="alert-content">
              <strong>Key Not Found</strong><br>
              This key was not found in our database. Please make sure you entered it correctly, including all dashes and characters.
            </div>
          </div>
        `;
      } else if (used) {
        content = `
          <div class="alert alert-info" style="margin-bottom: 0;">
            <i data-lucide="info" class="alert-icon"></i>
            <div class="alert-content">
              <strong>Key Already Used</strong><br>
              This key has already been used. If your account was downgraded, visit the <a href="renew.php" style="color: var(--color-primary); font-weight: 600; text-decoration: underline;">Renew page</a> to restore your Premium features.
            </div>
          </div>
        `;
      } else {
        content = `
          <div class="alert alert-info" style="margin-bottom: 0;">
            <i data-lucide="info" class="alert-icon"></i>
            <div class="alert-content">
              <strong>Status: ${status}</strong><br>
              ${message && message !== 'none' ? message : 'Check the status above for more details.'}
            </div>
          </div>
        `;
      }

      container.innerHTML = content;
      initIcons();
    }

    function copyInvite() {
      if (window.inviteToken) {
        navigator.clipboard.writeText(window.inviteToken).then(() => {
          showCopySuccess('copyInviteBtn');
        }).catch(() => {
          // Fallback for browsers without Clipboard API
          const el = document.getElementById('displayInvite');
          if (el) {
            const range = document.createRange();
            range.selectNodeContents(el);
            window.getSelection().removeAllRanges();
            window.getSelection().addRange(range);
          }
        });
      }
    }

    function copyVerifyAddress() {
      if (window.verifyAddress) {
        navigator.clipboard.writeText(window.verifyAddress).then(() => {
          showCopySuccess('copyVerifyBtn');
        }).catch(() => {
          // Fallback for browsers without Clipboard API
          const el = document.getElementById('displayVerify');
          if (el) {
            const range = document.createRange();
            range.selectNodeContents(el);
            window.getSelection().removeAllRanges();
            window.getSelection().addRange(range);
          }
        });
      }
    }

    function showCopySuccess(btnId) {
      const btn = document.getElementById(btnId);
      if (btn) {
        btn.innerHTML = '<i data-lucide="check" style="width: 16px; height: 16px; color: var(--color-success);"></i>';
        btn.style.color = 'var(--color-success)';
        initIcons();
        setTimeout(() => {
          btn.innerHTML = '<i data-lucide="copy" style="width: 16px; height: 16px;"></i>';
          btn.style.color = 'var(--color-neutral-500)';
          initIcons();
        }, 2000);
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
