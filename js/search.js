// ============================================
// PROXYFLOW SEARCH SYSTEM - MULTI ENDPOINT
// ============================================

class ProxySearch {
  constructor() {
    this.API_PROVIDERS = 'https://proxyflowpxp.com/wp-json/wp/v2/providers';
    this.API_BLOG = 'https://proxyflowpxp.com/wp-json/wp/v2/cpt_posts';
    
    this.state = {
      query: '',
      providerResults: [],
      blogResults: [],
      allProviders: [],
      allBlogs: [],
      isLoading: false,
      isOpen: false,
      selectedIndex: -1,
      error: null,
      cache: {}
    };
    
    this.debounceTimer = null;
    this.MIN_CHARS = 3;
    this.MAX_RESULTS_PER_SECTION = 5;
    this.DEBOUNCE_TIME = 500;
    
    this.init();
  }

  // Khởi tạo
  async init() {
    this.cacheElements();
    this.attachEvents();
    await this.fetchAllData();
  }

  // Cache các DOM elements
  cacheElements() {
    this.searchInput = document.querySelector('.search-input');
    this.searchForm = document.querySelector('.search-form');
    this.searchButton = document.querySelector('.search-button');
    
    // Tạo dropdown box
    this.createDropdown();
  }

  // Tạo dropdown HTML
  createDropdown() {
    const dropdown = document.createElement('div');
    dropdown.className = 'search-dropdown';
    dropdown.innerHTML = `
      <div class="search-dropdown-content">
        <div class="search-loading">
          <i class="fa-solid fa-spinner fa-spin"></i>
          <span>Searching...</span>
        </div>
        <div class="search-results">
          <!-- Providers Section -->
          <div class="search-section" id="providers-section">
            <div class="search-section-header">
              <i class="fa-solid fa-server"></i>
              <span>PROVIDERS</span>
            </div>
            <div class="search-section-results" id="providers-results"></div>
          </div>
          
          <!-- Blog Section -->
          <div class="search-section" id="blog-section">
            <div class="search-section-header">
              <i class="fa-solid fa-newspaper"></i>
              <span>ARTICLES</span>
            </div>
            <div class="search-section-results" id="blog-results"></div>
          </div>
        </div>
        <div class="search-empty">
          <i class="fa-solid fa-magnifying-glass"></i>
          <p>No results found</p>
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
    this.providersContainer = dropdown.querySelector('#providers-results');
    this.blogContainer = dropdown.querySelector('#blog-results');
    this.providersSection = dropdown.querySelector('#providers-section');
    this.blogSection = dropdown.querySelector('#blog-section');
  }

  // Fetch tất cả data từ 2 APIs
  async fetchAllData() {
    try {
      const [providersRes, blogsRes] = await Promise.all([
        fetch(this.API_PROVIDERS),
        fetch(this.API_BLOG)
      ]);
      
      if (!providersRes.ok || !blogsRes.ok) {
        throw new Error('Failed to fetch data');
      }
      
      this.state.allProviders = await providersRes.json();
      this.state.allBlogs = await blogsRes.json();
      
      console.log('✅ Loaded providers:', this.state.allProviders.length);
      console.log('✅ Loaded blogs:', this.state.allBlogs.length);
    } catch (error) {
      console.error('❌ Error fetching data:', error);
      this.state.error = error.message;
    }
  }

  // Attach event listeners
  attachEvents() {
    this.searchInput.addEventListener('input', (e) => this.handleInput(e));
    this.searchInput.addEventListener('focus', () => this.handleFocus());
    this.searchForm.addEventListener('submit', (e) => this.handleSubmit(e));
    this.searchInput.addEventListener('keydown', (e) => this.handleKeyboard(e));
    document.addEventListener('click', (e) => this.handleClickOutside(e));
  }

  // Xử lý input
  handleInput(e) {
    const query = e.target.value.trim();
    this.state.query = query;
    clearTimeout(this.debounceTimer);
    this.state.selectedIndex = -1;

    if (query.length < this.MIN_CHARS) {
      this.closeDropdown();
      return;
    }

    this.showLoading();
    this.debounceTimer = setTimeout(() => {
      this.performSearch(query);
    }, this.DEBOUNCE_TIME);
  }

  handleFocus() {
    if (this.state.query.length >= this.MIN_CHARS) {
      const hasResults = this.state.providerResults.length > 0 || this.state.blogResults.length > 0;
      if (hasResults) this.openDropdown();
    }
  }

  handleSubmit(e) {
    e.preventDefault();
    if (this.state.query.length >= this.MIN_CHARS) {
      this.performSearch(this.state.query);
    }
  }

  handleKeyboard(e) {
    if (!this.state.isOpen) return;

    const totalResults = this.state.providerResults.length + this.state.blogResults.length;
    if (totalResults === 0) return;

    switch(e.key) {
      case 'ArrowDown':
        e.preventDefault();
        this.navigateDown(totalResults);
        break;
      case 'ArrowUp':
        e.preventDefault();
        this.navigateUp(totalResults);
        break;
      case 'Enter':
        e.preventDefault();
        if (this.state.selectedIndex >= 0) {
          this.selectCurrentItem();
        }
        break;
      case 'Escape':
        this.closeDropdown();
        this.searchInput.blur();
        break;
    }
  }

  navigateDown(total) {
    if (this.state.selectedIndex < total - 1) {
      this.state.selectedIndex++;
      this.updateSelectedItem();
    }
  }

  navigateUp(total) {
    if (this.state.selectedIndex > 0) {
      this.state.selectedIndex--;
      this.updateSelectedItem();
    }
  }

  updateSelectedItem() {
    const allItems = this.dropdown.querySelectorAll('.search-result-item');
    allItems.forEach((item, index) => {
      if (index === this.state.selectedIndex) {
        item.classList.add('selected');
        item.scrollIntoView({ block: 'nearest', behavior: 'smooth' });
      } else {
        item.classList.remove('selected');
      }
    });
  }

  selectCurrentItem() {
    const allItems = this.dropdown.querySelectorAll('.search-result-item');
    const selectedItem = allItems[this.state.selectedIndex];
    if (selectedItem) {
      const link = selectedItem.dataset.link;
      window.location.href = link;
    }
  }

  handleClickOutside(e) {
    if (!this.searchForm.contains(e.target)) {
      this.closeDropdown();
    }
  }

  // Perform search
  performSearch(query) {
    const cacheKey = query.toLowerCase();
    
    if (this.state.cache[cacheKey]) {
      console.log('Using cached results');
      const cached = this.state.cache[cacheKey];
      this.state.providerResults = cached.providers;
      this.state.blogResults = cached.blogs;
      this.renderResults();
      return;
    }

    const queryLower = query.toLowerCase();
    
    // Filter providers
    const filteredProviders = this.state.allProviders.filter(provider => {
      const title = provider.title?.rendered?.toLowerCase() || '';
      const summary = provider.provider_data?.summary?.toLowerCase() || '';
      return title.includes(queryLower) || summary.includes(queryLower);
    }).slice(0, this.MAX_RESULTS_PER_SECTION);

    // Filter blogs
    const filteredBlogs = this.state.allBlogs.filter(blog => {
      const title = blog.title?.rendered?.toLowerCase() || '';
      const desc = blog.meta?.post_sdesc?.toLowerCase() || '';
      return title.includes(queryLower) || desc.includes(queryLower);
    }).slice(0, this.MAX_RESULTS_PER_SECTION);

    this.state.providerResults = filteredProviders;
    this.state.blogResults = filteredBlogs;
    
    // Cache results
    this.state.cache[cacheKey] = {
      providers: filteredProviders,
      blogs: filteredBlogs
    };

    this.renderResults();
  }

  // Render results
  renderResults() {
    this.state.isLoading = false;
    const hasProviders = this.state.providerResults.length > 0;
    const hasBlog = this.state.blogResults.length > 0;

    if (!hasProviders && !hasBlog) {
      this.showEmpty();
      return;
    }

    this.openDropdown();
    this.hideLoading();
    this.hideEmpty();
    this.hideError();

    // Render providers
    if (hasProviders) {
      this.providersSection.style.display = 'block';
      this.renderProviders();
    } else {
      this.providersSection.style.display = 'none';
    }

    // Render blogs
    if (hasBlog) {
      this.blogSection.style.display = 'block';
      this.renderBlogs();
    } else {
      this.blogSection.style.display = 'none';
    }
  }

  // Render providers
  renderProviders() {
    const html = this.state.providerResults.map((provider, index) => {
      const title = provider.title?.rendered || 'Untitled';
      const summary = provider.provider_data?.summary || '';
      const link = provider.link || '#';
      const logo = provider.provider_data?.logo || '';
      const rating = provider.provider_data?.rating || 0;
      const price = provider.provider_data?.price || 0;
      const highlightedTitle = this.highlightText(title, this.state.query);

      const rate = Number(rating);

      return `
        <div class="search-result-item" data-index="${index}" data-link="${link}">
          <div class="search-result-image">
            ${logo ? `<img src="${logo}" alt="${title}">` : `<div class="search-result-placeholder"><i class="fa-solid fa-server"></i></div>`}
          </div>
          <div class="search-result-content">
            <div class="search-result-header">
              <h4 class="search-result-title">${highlightedTitle}</h4>
              ${rate > 0 ? `<div class="search-result-rating"><i class="fa-solid fa-star"></i> ${rate.toFixed(1)}</div>` : ''}
            </div>
            <p class="search-result-excerpt">${summary}</p>
            ${price > 0 ? `<div class="search-result-price">From $${price}/mo</div>` : ''}
          </div>
          <div class="search-result-arrow">
            <i class="fa-solid fa-arrow-right"></i>
          </div>
        </div>
      `;
    }).join('');

    this.providersContainer.innerHTML = html;
    this.attachClickEvents(this.providersContainer);
  }

  // Render blogs
  renderBlogs() {
    const html = this.state.blogResults.map((blog, index) => {
      const title = blog.title?.rendered || 'Untitled';
      const desc = blog.meta?.post_sdesc || '';
      const link = blog.link || '#';
      const image = blog.meta?.post_image || '';
      const author = blog.meta?.post_author || '';
      const date = blog.meta?.post_date || '';
      const highlightedTitle = this.highlightText(title, this.state.query);

      return `
        <div class="search-result-item" data-index="${this.state.providerResults.length + index}" data-link="${link}">
          <div class="search-result-image">
            ${image ? `<img src="${image}" alt="${title}">` : `<div class="search-result-placeholder"><i class="fa-solid fa-newspaper"></i></div>`}
          </div>
          <div class="search-result-content">
            <h4 class="search-result-title">${highlightedTitle}</h4>
            <p class="search-result-excerpt">${this.truncate(desc, 80)}</p>
            ${author ? `<div class="search-result-meta"><i class="fa-solid fa-user"></i> ${author} ${date ? `• ${date}` : ''}</div>` : ''}
          </div>
          <div class="search-result-arrow">
            <i class="fa-solid fa-arrow-right"></i>
          </div>
        </div>
      `;
    }).join('');

    this.blogContainer.innerHTML = html;
    this.attachClickEvents(this.blogContainer);
  }

  attachClickEvents(container) {
    container.querySelectorAll('.search-result-item').forEach(item => {
      item.addEventListener('click', () => {
        window.location.href = item.dataset.link;
      });
    });
  }

  // Highlight text
  highlightText(text, query) {
    if (!query) return text;
    const regex = new RegExp(`(${this.escapeRegex(query)})`, 'gi');
    return text.replace(regex, '<mark>$1</mark>');
  }

  escapeRegex(str) {
    return str.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
  }

  truncate(text, length) {
    return text.length > length ? text.substring(0, length) + '...' : text;
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