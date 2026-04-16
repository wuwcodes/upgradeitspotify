/**
 * Country Dropdown Component (Vanilla JS)
 * Similar to the main website's CountryDropdown React component
 */

class CountryDropdown {
  constructor(selectElement, options = {}) {
    this.selectElement = selectElement;
    this.options = {
      placeholder: options.placeholder || 'Select a country',
      searchPlaceholder: options.searchPlaceholder || 'Search countries...',
      colorScheme: options.colorScheme || 'green',
      ...options
    };

    this.isOpen = false;
    this.searchTerm = '';
    this.countries = [];
    this.displayValue = null; // null = placeholder, 'ANY' = random, or country code

    this.init();
  }

  init() {
    this.countries = CONFIG.countries || [];

    this.createDropdown();

    this.selectElement.style.display = 'none';

    this.setupEventListeners();

    if (typeof initIcons === 'function') {
      initIcons();
    } else if (typeof lucide !== 'undefined') {
      lucide.createIcons();
    }
  }

  createDropdown() {
    this.wrapper = document.createElement('div');
    this.wrapper.className = 'country-dropdown-wrapper';
    this.wrapper.style.position = 'relative';


    this.button = document.createElement('button');
    this.button.type = 'button';
    this.button.className = 'country-dropdown-button';
    this.button.innerHTML = `
      <div class="country-dropdown-icon">
        <i data-lucide="globe" style="width: 20px; height: 20px;"></i>
      </div>
      <div class="country-dropdown-value">
        <span>Anywhere</span>
      </div>
      <i data-lucide="chevron-down" class="country-dropdown-chevron" style="width: 20px; height: 20px;"></i>
    `;

    this.displayValue = 'ANY';


    this.menu = document.createElement('div');
    this.menu.className = 'country-dropdown-menu';
    this.menu.style.display = 'none';
    this.menu.innerHTML = `
      <div class="country-dropdown-search">
        <div style="position: relative;">
          <i data-lucide="search" style="position: absolute; left: 0.75rem; top: 50%; transform: translateY(-50%); width: 16px; height: 16px; color: var(--color-neutral-400);"></i>
          <input
            type="text"
            class="country-dropdown-search-input"
            placeholder="${this.options.searchPlaceholder}"
          >
        </div>
      </div>
      <div class="country-dropdown-list"></div>
    `;

    this.wrapper.appendChild(this.button);
    this.wrapper.appendChild(this.menu);

    this.selectElement.parentNode.insertBefore(this.wrapper, this.selectElement.nextSibling);

    this.searchInput = this.menu.querySelector('.country-dropdown-search-input');
    this.listContainer = this.menu.querySelector('.country-dropdown-list');


    this.renderCountries();
  }

  renderCountries() {
    const filteredCountries = this.countries.filter(country =>
      country.name.toLowerCase().includes(this.searchTerm.toLowerCase())
    );

    this.listContainer.innerHTML = '';

    if (!this.searchTerm || 'anywhere'.includes(this.searchTerm.toLowerCase())) {
      const anywhereItem = document.createElement('button');
      anywhereItem.type = 'button';
      anywhereItem.className = 'country-dropdown-item';
      anywhereItem.dataset.code = 'ANY';

      const isAnySelected = this.displayValue === 'ANY';
      if (isAnySelected) {
        anywhereItem.classList.add('selected');
      }

      anywhereItem.innerHTML = `
        <div style="display: flex; align-items: center; gap: 0.75rem;">
          <i data-lucide="globe" style="width: 24px; height: 18px; color: var(--color-neutral-400);"></i>
          <span class="country-dropdown-code">ANY</span>
          <span>Anywhere</span>
        </div>
      `;

      anywhereItem.addEventListener('click', () => this.selectRandom());
      this.listContainer.appendChild(anywhereItem);
    }

    if (filteredCountries.length === 0 && this.searchTerm) {
      const emptyDiv = document.createElement('div');
      emptyDiv.className = 'country-dropdown-empty';
      emptyDiv.innerHTML = `
        <i data-lucide="globe" style="width: 32px; height: 32px; opacity: 0.5; margin-bottom: 0.5rem;"></i>
        <p style="font-size: 0.875rem;">No countries found</p>
      `;
      this.listContainer.appendChild(emptyDiv);
    } else {
      filteredCountries.forEach(country => {
        const item = document.createElement('button');
        item.type = 'button';
        item.className = 'country-dropdown-item';
        item.dataset.code = country.code;

        const isSelected = this.selectElement.value === country.code && this.displayValue !== 'ANY';
        if (isSelected) {
          item.classList.add('selected');
        }

        item.innerHTML = `
          <div style="display: flex; align-items: center; gap: 0.75rem;">
            <span class="fi fi-${country.code.toLowerCase()}" style="font-size: 1rem; border-radius: 2px;"></span>
            <span class="country-dropdown-code">${country.code}</span>
            <span>${country.name}</span>
          </div>
        `;

        item.addEventListener('click', () => this.selectCountry(country));
        this.listContainer.appendChild(item);
      });
    }

    if (typeof initIcons === 'function') {
      initIcons();
    } else if (typeof lucide !== 'undefined') {
      lucide.createIcons();
    }
  }

  selectRandom() {
    const availableCountries = this.countries.filter(c => c.slots > 0);
    if (availableCountries.length > 0) {
      const randomCountry = availableCountries[Math.floor(Math.random() * availableCountries.length)];
      this.selectElement.value = randomCountry.code;
      this.displayValue = 'ANY';

      const valueContainer = this.button.querySelector('.country-dropdown-value');
      valueContainer.innerHTML = `<span>Anywhere</span>`;
    }

    this.close();

    // Trigger change event
    const event = new Event('change', { bubbles: true });
    this.selectElement.dispatchEvent(event);
  }

  selectCountry(country) {
    this.selectElement.value = country.code;
    this.displayValue = country.code;


    const valueContainer = this.button.querySelector('.country-dropdown-value');
    valueContainer.innerHTML = `
      <div style="display: flex; align-items: center; gap: 0.5rem;">
        <span class="fi fi-${country.code.toLowerCase()}" style="font-size: 1rem; border-radius: 2px;"></span>
        <span>${country.name}</span>
      </div>
    `;

    this.close();


    const event = new Event('change', { bubbles: true });
    this.selectElement.dispatchEvent(event);
  }

  open() {
    this.isOpen = true;
    this.menu.style.display = 'block';
    this.button.classList.add('open');
    this.searchInput.focus();

    if (typeof initIcons === 'function') {
      initIcons();
    } else if (typeof lucide !== 'undefined') {
      lucide.createIcons();
    }
  }

  close() {
    this.isOpen = false;
    this.menu.style.display = 'none';
    this.button.classList.remove('open');
    this.searchTerm = '';
    this.searchInput.value = '';
    this.renderCountries();
  }

  setupEventListeners() {
    this.button.addEventListener('click', (e) => {
      e.preventDefault();
      e.stopPropagation();

      if (this.isOpen) {
        this.close();
      } else {
        this.open();
      }
    });

    this.searchInput.addEventListener('input', (e) => {
      this.searchTerm = e.target.value;
      this.renderCountries();
    });

    this.searchInput.addEventListener('click', (e) => {
      e.stopPropagation();
    });

    this.handleDocumentClick = (e) => {
      if (this.isOpen && !this.wrapper.contains(e.target)) {
        this.close();
      }
    };
    document.addEventListener('click', this.handleDocumentClick);

    this.handleEscapeKey = (e) => {
      if (e.key === 'Escape' && this.isOpen) {
        this.close();
      }
    };
    document.addEventListener('keydown', this.handleEscapeKey);
  }

  destroy() {
    // Clean up event listeners to prevent memory leaks
    if (this.handleDocumentClick) {
      document.removeEventListener('click', this.handleDocumentClick);
    }
    if (this.handleEscapeKey) {
      document.removeEventListener('keydown', this.handleEscapeKey);
    }

    this.wrapper.remove();
    this.selectElement.style.display = '';
  }
}

window.countryDropdowns = [];

document.addEventListener('DOMContentLoaded', () => {
  const selects = document.querySelectorAll('[data-country-dropdown]');
  selects.forEach(select => {
    const dropdown = new CountryDropdown(select);
    window.countryDropdowns.push(dropdown);
  });
});

window.reinitCountryDropdowns = function() {
  window.countryDropdowns.forEach(dropdown => {
    dropdown.countries = CONFIG.countries || [];
    dropdown.renderCountries();

    // Auto-select random country for backend while keeping 'Anywhere' display
    if (dropdown.displayValue === 'ANY') {
      const availableCountries = dropdown.countries.filter(c => c.slots > 0);
      if (availableCountries.length > 0) {
        const randomCountry = availableCountries[Math.floor(Math.random() * availableCountries.length)];
        dropdown.selectElement.value = randomCountry.code;
      }
    }
  });
};
