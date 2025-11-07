// ============================= FETCH PROVIDER LISTING DATA ======================
document.addEventListener("DOMContentLoaded", async () => {
  
  const API_BASE = "http://localhost/PF_HTMLCF/wordpress/wp-json/wp/v2";
  const ITEMS_PER_PAGE = 6;
  
  let searchQuery = ""; 
  let allProviders = [];
  let allCategories = [];
  let currentFilter = "all";
  let currentSort = "rating-desc";
  let currentPage = 1;
  // ============================= FETCH CATEGORIES ======================
  async function fetchCategories() {
    try {
      const res = await fetch(`${API_BASE}/provider_category`);
      const data = await res.json();
      
      allCategories = data;
      
      renderFilterButtons();
    } catch (error) {
      console.error("Error fetching categories:", error);
    }
  }

  // ============================= FETCH PROVIDERS ======================
  async function fetchProviders() {
    try {
      const res = await fetch(`${API_BASE}/providers`);
      const data = await res.json();
      
      allProviders = data;
      
      renderProviders();
    } catch (error) {
      console.error("Error fetching providers:", error);
      const grid = document.querySelector(".providers-grid");
      if (grid) {
        grid.innerHTML = '<div class="loading">Error loading providers</div>';
      }
    }
  }

  // ============================= RENDER FILTER BUTTONS ======================
  function renderFilterButtons() {
    const container = document.querySelector(".filter-buttons");
    container.innerHTML = "";

    let out = '<button class="filter-btn active" data-category="all">All</button>';

    allCategories.forEach((cat) => {
      out += `<button class="filter-btn" data-category="${cat.id}">${cat.name}</button>`;
    });

    container.innerHTML = out;

    // Add event listeners to filter buttons
    container.querySelectorAll(".filter-btn").forEach((btn) => {
      btn.addEventListener("click", function () {
        container.querySelectorAll(".filter-btn").forEach((b) => b.classList.remove("active"));
        this.classList.add("active");
        
        currentFilter = this.dataset.category;
        currentPage = 1;
        renderProviders();
      });
    });

    console.log("Filter buttons rendered");
  }

  // ============================= FILTER PROVIDERS ======================
  function getFilteredProviders() {
  let filtered = allProviders;

  if (currentFilter !== "all") {
    filtered = filtered.filter((provider) => {
      const categoryIds = provider.provider_category || [];
      return categoryIds.includes(parseInt(currentFilter));
    });
  }

  if (searchQuery.trim() !== "") {
    const query = searchQuery.toLowerCase().trim();
    filtered = filtered.filter((provider) => {
      const title = provider.title?.rendered?.toLowerCase() || "";
      const summary = provider.provider_data?.summary?.toLowerCase() || "";
      
      return title.includes(query) || summary.includes(query);
    });
  }

  return filtered;
}

  // ============================= SORT PROVIDERS ======================
  function getSortedProviders(providers) {
    const sorted = [...providers];

    switch (currentSort) {
      case "rating-desc":
      case "highest-rated":
        return sorted.sort((a, b) => {
          const ratingA = parseFloat(a.provider_data?.rating || 0);
          const ratingB = parseFloat(b.provider_data?.rating || 0);
          return ratingB - ratingA;
        });
        
      case "price-asc":
      case "lowest-price":
        return sorted.sort((a, b) => {
          const priceA = parseFloat(a.provider_data?.price || 0);
          const priceB = parseFloat(b.provider_data?.price || 0);
          return priceA - priceB;
        });
        
      case "price-desc":
      case "highest-price":
        return sorted.sort((a, b) => {
          const priceA = parseFloat(a.provider_data?.price || 0);
          const priceB = parseFloat(b.provider_data?.price || 0);
          return priceB - priceA;
        });
        
      default:
        return sorted;
    }
  }

  // ============================= PAGINATION ======================
  function getPaginatedProviders(providers) {
    const start = (currentPage - 1) * ITEMS_PER_PAGE;
    const end = start + ITEMS_PER_PAGE;
    return providers.slice(start, end);
  }

  // ============================= RENDER PROVIDERS ======================
  function renderProviders() {
    const filtered = getFilteredProviders();
    const sorted = getSortedProviders(filtered);
    const paginated = getPaginatedProviders(sorted);

    // Update results count
    const resultsCount = document.querySelector(".results-count");
    resultsCount.innerHTML = "";
    if (resultsCount) {
      resultsCount.textContent = `Showing ${paginated.length} of ${sorted.length} providers`;
    }

    // Render provider cards
    const grid = document.querySelector(".providers-grid");
    grid.innerHTML = "";

    if (paginated.length === 0) {
      grid.innerHTML = '<div class="loading">No providers found</div>';
      return;
    }

    let out = "";
    paginated.forEach((provider) => {
      out += createProviderCard(provider);
    });

    grid.innerHTML = out;

    // Render pagination
    renderPagination(sorted.length);

    console.log("Providers rendered:", paginated.length);
  }

  // ============================= CREATE PROVIDER CARD ======================
  function createProviderCard(provider) {
    const data = provider.provider_data || {};
    
    console.log("Creating card for:", provider.title?.rendered, data);
    
    // Basic info
    const logo = data.logo || "https://placehold.co/48x48/449df0/white?text=P";
    
    // Tags là array, lấy phần tử đầu tiên
    const tags = data.tags || [];
    const tag = tags.length > 0 ? tags[0] : "";
    
    const name = provider.title?.rendered || "Provider";
    const rating = Number(data.rating) || "0.0";
    const reviewCount = Math.floor(Math.random() * 5000) + 500;
    const summary = data.summary || "No description available";

    // Get category name
    const categoryId = provider.provider_category?.[0];
    const category = allCategories.find((c) => c.id === categoryId);
    const categoryName = category?.name || "Uncategorized";

    // Get performance metrics - LẤY SUCCESS RATE VÀ AVG RESPONSE TIME
    const metrics = data.description.performance_metrics || [];
    
    const successRateMetric = metrics.find((m) => 
      m.title?.toLowerCase().includes("success")
    );
    
    const responseTimeMetric = metrics.find((m) => 
      m.title?.toLowerCase().includes("response") || 
      m.title?.toLowerCase().includes("avg response")
    );

    const successRate = successRateMetric?.value || "N/A";
    const responseTime = responseTimeMetric?.value || "N/A";

    // Get features (first 3 + count remaining) từ ADVANCED
    const advanced = data.advanced || [];
    const displayFeatures = advanced.slice(0, 3);
    const remainingCount = advanced.length - 3;

    let featuresTags = "";
    displayFeatures.forEach((f) => {
      const featureTitle = f.title || f;
      featuresTags += `<span class="feature-tag">${featureTitle}</span>`;
    });

    if (remainingCount > 0) {
      featuresTags += `<span class="feature-tag">+${remainingCount} more</span>`;
    }

    // Get price - ĐÂY LÀ NUMBER
    const priceValue = data.price || 0;
    const price = priceValue > 0 ? `From $${priceValue}/mo` : "Contact for pricing";

    // Badge class mapping
    let badgeClass = "";
    const tagLower = tag.toLowerCase();
    
    if (tagLower.includes("editor") || tagLower.includes("overall")) {
      badgeClass = "editor-choice";
    } else if (tagLower.includes("budget")) {
      badgeClass = "best-budget";
    } else if (tagLower.includes("value")) {
      badgeClass = "best-value";
    } else{
        badgeClass = "best-value";
    }

    const badgeHTML = tag ? `<span class="badgerv ${badgeClass}">${tag}</span>` : "";

    // Generate stars (5 stars)
    const fullStars = Math.floor(rating);
    const starsHTML = "★".repeat(fullStars) + "☆".repeat(5 - fullStars);
    // const starsHTML = '<span class="star">★</span>'.repeat(5);
    const url = `${WP_HOME}/reviews/${encodeURIComponent(provider.slug)}`;

    return `
      <div class="provider-card">
        ${badgeHTML}
        
        <div class="card-header">
          <div class="provider-logo">
            <img src="${logo}" alt="${name}">
          </div>
          <div class="provider-info">
            <h3 class="provider-name">${name}</h3>
            <div class="provider-category">${categoryName}</div>
          </div>
        </div>

        <div class="rating-section">
          <div class="stars">${starsHTML}</div>
          <span class="rating-score">${rating.toFixed(1)}</span>
          <span class="review-count">(${reviewCount.toLocaleString()} reviews)</span>
        </div>

        <p class="description">${summary}</p>

        <div class="stats-grid">
          <div class="stat-item">
            <div class="stat-label">Success Rate</div>
            <div class="stat-value">${successRate}</div>
          </div>
          <div class="stat-item">
            <div class="stat-label">Response Time</div>
            <div class="stat-value">${responseTime}</div>
          </div>
        </div>

        <div class="features">
          ${featuresTags}
        </div>

        <div class="card-footer">
          <div class="pricing">
            <div class="starting-label">Starting at</div>
            <div class="price">${price}</div>
          </div>
          <a href="${url}">
          <button class="view-details-btn">
            View Details
            <span>→</span>
          </button>
          </a>
        </div>
      </div>
    `;
  }

  // ============================= RENDER PAGINATION ======================
  function renderPagination(totalItems) {
    const totalPages = Math.ceil(totalItems / ITEMS_PER_PAGE);
    const pageNumbers = document.querySelector(".page-numbers");
    const prevBtn = document.getElementById("prevBtn");
    const nextBtn = document.getElementById("nextBtn");

    prevBtn.disabled = currentPage === 1;
    nextBtn.disabled = currentPage === totalPages || totalPages === 0;

    let pages = [];

    if (totalPages <= 7) {
      pages = Array.from({ length: totalPages }, (_, i) => i + 1);
    } else {
      if (currentPage <= 4) {
        pages = [1, 2, 3, 4, 5, "...", totalPages];
      } else if (currentPage >= totalPages - 3) {
        pages = [
          1,
          "...",
          totalPages - 4,
          totalPages - 3,
          totalPages - 2,
          totalPages - 1,
          totalPages
        ];
      } else {
        pages = [
          1,
          "...",
          currentPage - 1,
          currentPage,
          currentPage + 1,
          "...",
          totalPages
        ];
      }
    }

    let out = "";
    pages.forEach((page) => {
      if (page === "...") {
        out += "<button disabled>...</button>";
      } else {
        const activeClass = page === currentPage ? "active" : "";
        out += `<button class="${activeClass}" data-page="${page}">${page}</button>`;
      }
    });

    pageNumbers.innerHTML = out;

    // Add event listeners to page buttons
    pageNumbers.querySelectorAll("button:not([disabled])").forEach((btn) => {
      btn.addEventListener("click", function () {
        currentPage = parseInt(this.dataset.page);
        renderProviders();
        window.scrollTo({ top: 0, behavior: "smooth" });
      });
    });

    // Prev/Next button listeners
    prevBtn.onclick = () => {
      if (currentPage > 1) {
        currentPage--;
        renderProviders();
        window.scrollTo({ top: 0, behavior: "smooth" });
      }
    };

    nextBtn.onclick = () => {
      if (currentPage < totalPages) {
        currentPage++;
        renderProviders();
        window.scrollTo({ top: 0, behavior: "smooth" });
      }
    };
  }

  // ============================= SORT CHANGE LISTENER ======================
  const sortSelect = document.querySelector(".sort-select");
  if (sortSelect) {
    sortSelect.addEventListener("change", function () {
      currentSort = this.value;
      currentPage = 1;
      renderProviders();
    });
  } else {
    console.warn("Sort select not found");
  }

  // ============================= MOBILE FILTER TOGGLE ======================
  const filterToggle = document.querySelector(".filter-toggle");
  if (filterToggle) {
    filterToggle.addEventListener("click", () => {
      const filterButtons = document.querySelector(".filter-buttons");
      if (filterButtons) {
        filterButtons.classList.toggle("show");
      }
    });
  }

  // ============================= SEARCH FUNCTIONALITY ======================
const searchBox = document.querySelector(".search-box");
if (searchBox) {
  let searchTimeout;
  
  searchBox.addEventListener("input", function () {
    clearTimeout(searchTimeout);
    
    searchTimeout = setTimeout(() => {
      searchQuery = this.value;
      currentPage = 1;
      renderProviders();
    }, 100);
  });
  
  console.log("Search functionality initialized");
}

  // ============================= INITIALIZE ======================
  
  await fetchCategories();
  await fetchProviders();

  console.log("Provider listing initialized successfully");
});