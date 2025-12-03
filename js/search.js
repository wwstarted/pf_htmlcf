class ProxySearch {
  constructor() {
    this.API_URL = 'https://proxyflowpxp.com/wp-json/wp/v2/providers';
    this.state = {
      query: '',
      results: [],
      allProviders: [],
      isLoading: false,
      isOpen: false,
      selectedIndex: -1,
      error: null,
      cache: {}
    };
    
    this.debounceTimer = null;
    this.MIN_CHARS = 3;
    this.MAX_RESULTS = 5;
    this.DEBOUNCE_TIME = 500;
    this.init();
  }

  // init()
  async init() {
    this.cacheElements();
    this.attachEvents();
    await this.fetchAllProviders();
  }

  // dom element
  cacheElements() {
    this.searchInput = document.querySelector('.search-input');
    this.searchForm = document.querySelector('.search-form');
    this.searchButton = document.querySelector('.search-button');
    
    // input dropdown
    this.createDropdown();
  }

  // dropdown html
  createDropdown() {
    const dropdown = document.createElement('div');
    dropdown.className = 'search-dropdown';
    dropdown.innerHTML = `
      <div class="search-dropdown-content">
        <div class="search-loading">
          <i class="fa-solid fa-spinner fa-spin"></i>
          <span>Searching...</span>
        </div>
        <div class="search-results"></div>
        <div class="search-empty">
          <i class="fa-solid fa-magnifying-glass"></i>
          <p>No proxies found</p>
          <span>Try different keywords</span>
        </div>
        <div class="search-error">
          <i class="fa-solid fa-triangle-exclamation"></i>
          <p>Something went wrong</p>
        </div>
      </div>
    `;
    
    this.searchForm.style.position = 'relative';
    this.searchForm.appendChild(dropdown);
    this.dropdown = dropdown;
    this.resultsContainer = dropdown.querySelector('.search-results');
  }

  // Fetch all provider
  async fetchAllProviders() {
    try {
      const response = await fetch(this.API_URL);
      if (!response.ok) throw new Error('Failed to fetch providers');
      
      this.state.allProviders = await response.json();
      console.log('Loaded providers:', this.state.allProviders.length);
    } catch (error) {
      console.error('Error fetching providers:', error);
      this.state.error = error.message;
    }
  }
  // Attach event listeners
  attachEvents() {
    // Input events
    this.searchInput.addEventListener('input', (e) => this.handleInput(e));
    this.searchInput.addEventListener('focus', () => this.handleFocus());
    
    // Form submit
    this.searchForm.addEventListener('submit', (e) => this.handleSubmit(e));
    
    // Keyboard navigation
    this.searchInput.addEventListener('keydown', (e) => this.handleKeyboard(e));
    
    // Click outside to close
    document.addEventListener('click', (e) => this.handleClickOutside(e));
  }

  // Xử lý input
  handleInput(e) {
    const query = e.target.value.trim();
    this.state.query = query;

    // Clear previous timer
    clearTimeout(this.debounceTimer);

    // Reset selected index
    this.state.selectedIndex = -1;

    // Nếu dưới MIN_CHARS thì đóng dropdown
    if (query.length < this.MIN_CHARS) {
      this.closeDropdown();
      return;
    }

    // Hiển thị loading
    this.showLoading();

    // Debounce search
    this.debounceTimer = setTimeout(() => {
      this.performSearch(query);
    }, this.DEBOUNCE_TIME);
  }

  // Xử lý focus
  handleFocus() {
    if (this.state.query.length >= this.MIN_CHARS && this.state.results.length > 0) {
      this.openDropdown();
    }
  }

  // Xử lý submit form
  handleSubmit(e) {
    e.preventDefault();
    
    if (this.state.selectedIndex >= 0 && this.state.results[this.state.selectedIndex]) {
      // Nếu đang select item thì navigate đến item đó
      this.selectResult(this.state.results[this.state.selectedIndex]);
    } else if (this.state.query.length >= this.MIN_CHARS) {
      // Nếu không có item nào được select thì search
      this.performSearch(this.state.query);
    }
  }

  // Xử lý keyboard navigation
  handleKeyboard(e) {
    if (!this.state.isOpen || this.state.results.length === 0) return;

    switch(e.key) {
      case 'ArrowDown':
        e.preventDefault();
        this.navigateDown();
        break;
      case 'ArrowUp':
        e.preventDefault();
        this.navigateUp();
        break;
      case 'Enter':
        e.preventDefault();
        if (this.state.selectedIndex >= 0) {
          this.selectResult(this.state.results[this.state.selectedIndex]);
        }
        break;
      case 'Escape':
        this.closeDropdown();
        this.searchInput.blur();
        break;
    }
  }

  // Navigate down
  navigateDown() {
    if (this.state.selectedIndex < this.state.results.length - 1) {
      this.state.selectedIndex++;
      this.updateSelectedItem();
    }
  }

  // Navigate up
  navigateUp() {
    if (this.state.selectedIndex > 0) {
      this.state.selectedIndex--;
      this.updateSelectedItem();
    }
  }

  // Update selected item visual
  updateSelectedItem() {
    const items = this.resultsContainer.querySelectorAll('.search-result-item');
    items.forEach((item, index) => {
      if (index === this.state.selectedIndex) {
        item.classList.add('selected');
        item.scrollIntoView({ block: 'nearest', behavior: 'smooth' });
      } else {
        item.classList.remove('selected');
      }
    });
  }

  // Click outside to close
  handleClickOutside(e) {
    if (!this.searchForm.contains(e.target)) {
      this.closeDropdown();
    }
  }

  // Perform search
  performSearch(query) {
    // Check cache first
    if (this.state.cache[query]) {
      console.log('📦 Using cached results for:', query);
      this.state.results = this.state.cache[query];
      this.renderResults();
      return;
    }

    // Filter providers
    const queryLower = query.toLowerCase();
    const filtered = this.state.allProviders.filter(provider => {
      const title = provider.title?.rendered?.toLowerCase() || '';
      const excerpt = provider.excerpt?.rendered?.toLowerCase() || '';
      
      return title.includes(queryLower) || excerpt.includes(queryLower);
    });

    // Limit results
    this.state.results = filtered.slice(0, this.MAX_RESULTS);
    
    // Cache results
    this.state.cache[query] = this.state.results;

    // Render
    this.renderResults();
  }

  // Render results
  renderResults() {
    this.state.isLoading = false;
    
    if (this.state.results.length === 0) {
      this.showEmpty();
      return;
    }

    this.openDropdown();
    this.hideLoading();
    this.hideEmpty();
    this.hideError();

    // Render each result
    const html = this.state.results.map((provider, index) => {
      const title = provider.title?.rendered || 'Untitled';
      const summary = provider.provider_data?.summary || '';
      const link = provider.link || '#';
      const logo = provider.provider_data?.logo || '';
      const rating = provider.provider_data?.rating || 0;
      const price = provider.provider_data?.price || 0;
      
      // Highlight matched text
      const highlightedTitle = this.highlightText(title, this.state.query);

      return `
        <div class="search-result-item" data-index="${index}" data-link="${link}">
          <div class="search-result-image">
            ${logo ? `<img src="${logo}" alt="${title}">` : `<div class="search-result-placeholder"><i class="fa-solid fa-server"></i></div>`}
          </div>
          <div class="search-result-content">
            <div class="search-result-header">
              <h4 class="search-result-title">${highlightedTitle}</h4>
              ${rating > 0 ? `<div class="search-result-rating"><i class="fa-solid fa-star"></i> ${rating}</div>` : ''}
            </div>
            <p class="search-result-excerpt">${summary}</p>
            ${price > 0 ? `<div class="search-result-price">From ${price}/mo</div>` : ''}
          </div>
          <div class="search-result-arrow">
            <i class="fa-solid fa-arrow-right"></i>
          </div>
        </div>
      `;
    }).join('');

    this.resultsContainer.innerHTML = html;

    // Attach click events
    this.resultsContainer.querySelectorAll('.search-result-item').forEach(item => {
      item.addEventListener('click', () => {
        const link = item.dataset.link;
        window.location.href = link;
      });
    });
  }

  // Select result
  selectResult(provider) {
    window.location.href = provider.link;
  }

  // Highlight text
  highlightText(text, query) {
    if (!query) return text;
    
    const regex = new RegExp(`(${query})`, 'gi');
    return text.replace(regex, '<mark>$1</mark>');
  }

  // Strip HTML tags
  stripHtml(html) {
    const tmp = document.createElement('div');
    tmp.innerHTML = html;
    return tmp.textContent || tmp.innerText || '';
  }

  // Show/Hide states
  showLoading() {
    this.state.isLoading = true;
    this.openDropdown();
    this.dropdown.classList.add('loading');
    this.dropdown.classList.remove('empty', 'error');
  }

  hideLoading() {
    this.dropdown.classList.remove('loading');
  }

  showEmpty() {
    this.openDropdown();
    this.dropdown.classList.add('empty');
    this.dropdown.classList.remove('loading', 'error');
  }

  hideEmpty() {
    this.dropdown.classList.remove('empty');
  }

  showError() {
    this.openDropdown();
    this.dropdown.classList.add('error');
    this.dropdown.classList.remove('loading', 'empty');
  }

  hideError() {
    this.dropdown.classList.remove('error');
  }

  openDropdown() {
    this.state.isOpen = true;
    this.dropdown.classList.add('active');
  }

  closeDropdown() {
    this.state.isOpen = false;
    this.state.selectedIndex = -1;
    this.dropdown.classList.remove('active');
  }
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
  new ProxySearch();
});