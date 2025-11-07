<?php get_header(); ?>

<main class="reviews-archive">
  <div class="content">
    <!-- Header content -->
    <div class="head_content">
      <div class="wrapper_archive_review">
        <!-- Breadcrumb -->
        <div class="text-line-clamp breadcrumb">
          <a href="<?php echo home_url(`/index.php`); ?>">ProxyFlow</a> <span>/</span>
          <span>Blog</span>
        </div>

        <!-- Updated Badge -->
        <div class="updated-badge">
          <i class="fa-solid fa-up-right-from-square"></i> Updated Daily
        </div>

        <!-- Main Heading -->
        <h1 class="main-heading">
          Find your perfect <span class="proxy-text">proxy</span><br>
          <span class="solution-text">solution</span>
        </h1>

        <!-- Description -->
        <p class="description">
          Compare the best proxy providers with detailed reviews, performance metrics, and real user feedback. Make informed decisions for your business.
        </p>

        <!-- Search Box -->
        <div class="search-container">
          <input
            type="text"
            class="search-box"
            placeholder="Search providers, features, or use cases...">
        </div>
      </div>
    </div>

    <!-- Main content -->
    <div class="main_content">
      <div class="wrapper_archive_review">
        <div class="main_top">
          <!-- Filter Section -->
          <div class="filter-section">
            <button class="filter-toggle" aria-label="Toggle filters">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h18M3 8h18M3 12h18M3 16h18M3 20h18" />
              </svg>
            </button>

            <div class="filter-buttons">
              <!-- fetch data here -->
            </div>
          </div>

          <!-- Sort Section -->
          <div class="sort-section">
            <label class="sort-label">Sort by:</label>
            <div class="sort-dropdown">
              <select class="sort-select">
                <option value="highest-rated">Highest Rated</option>
                <option value="lowest-price">Lowest Price</option>
                <option value="highest-price">Highest Price</option>
                <option value="most-popular">Most Popular</option>
                <option value="newest">Newest</option>
              </select>
            </div>
          </div>
        </div>

        <div class="main_box">
          <div class="results-count">Showing 6 providers</div>

          <div class="providers-grid">
            <!-- Card 1: Oxylabs -->
            <div class="provider-card">
              <span class="badge editor-choice">Editor's Choice</span>

              <div class="card-header">
                <div class="provider-logo">
                  <img src="https://placehold.co/48x48/449df0/white?text=O" alt="Oxylabs">
                </div>
                <div class="provider-info">
                  <h3 class="provider-name">Oxylabs</h3>
                  <div class="provider-category">Premium</div>
                </div>
              </div>

              <div class="rating-section">
                <div class="stars">
                  <span class="star">★</span>
                  <span class="star">★</span>
                  <span class="star">★</span>
                  <span class="star">★</span>
                  <span class="star">★</span>
                </div>
                <span class="rating-score">4.9</span>
                <span class="review-count">(2,847 reviews)</span>
              </div>

              <p class="description">
                Enterprise-grade proxy solutions with advanced targeting and 99.9% uptime guarantee
              </p>

              <div class="stats-grid">
                <div class="stat-item">
                  <div class="stat-label">Success Rate</div>
                  <div class="stat-value">99.5%</div>
                </div>
                <div class="stat-item">
                  <div class="stat-label">Response Time</div>
                  <div class="stat-value">0.42s</div>
                </div>
              </div>

              <div class="features">
                <span class="feature-tag">100M+ IPs</span>
                <span class="feature-tag">99.9% Uptime</span>
                <span class="feature-tag">24/7 Support</span>
                <span class="feature-tag">+4 more</span>
              </div>

              <div class="card-footer">
                <div class="pricing">
                  <div class="starting-label">Starting at</div>
                  <div class="price">From $300/mo</div>
                </div>
                <button class="view-details-btn">
                  View Details
                  <span>→</span>
                </button>
              </div>
            </div>

            <!-- Card 2: Bright Data -->
            <div class="provider-card">
              <span class="badge best-budget">Best Budget</span>

              <div class="card-header">
                <div class="provider-logo">
                  <img src="https://placehold.co/48x48/68b5f5/white?text=B" alt="Bright Data">
                </div>
                <div class="provider-info">
                  <h3 class="provider-name">Bright Data</h3>
                  <div class="provider-category">Mid-Range</div>
                </div>
              </div>

              <div class="rating-section">
                <div class="stars">
                  <span class="star">★</span>
                  <span class="star">★</span>
                  <span class="star">★</span>
                  <span class="star">★</span>
                  <span class="star">★</span>
                </div>
                <span class="rating-score">4.8</span>
                <span class="review-count">(3,126 reviews)</span>
              </div>

              <p class="description">
                Worlds #1 proxy network with advanced targeting and unlimited bandwidth
              </p>

              <div class="stats-grid">
                <div class="stat-item">
                  <div class="stat-label">Success Rate</div>
                  <div class="stat-value">99.2%</div>
                </div>
                <div class="stat-item">
                  <div class="stat-label">Response Time</div>
                  <div class="stat-value">0.52s</div>
                </div>
              </div>

              <div class="features">
                <span class="feature-tag">72M+ IPs</span>
                <span class="feature-tag">Unlimited Bandwidth</span>
                <span class="feature-tag">Advanced Targeting</span>
                <span class="feature-tag">+4 more</span>
              </div>

              <div class="card-footer">
                <div class="pricing">
                  <div class="starting-label">Starting at</div>
                  <div class="price">From $500/mo</div>
                </div>
                <button class="view-details-btn">
                  View Details
                  <span>→</span>
                </button>
              </div>
            </div>

            <!-- Card 3: Smartproxy -->
            <div class="provider-card">
              <span class="badge best-value">Best Value</span>

              <div class="card-header">
                <div class="provider-logo">
                  <img src="https://placehold.co/48x48/449df0/white?text=S" alt="Smartproxy">
                </div>
                <div class="provider-info">
                  <h3 class="provider-name">Smartproxy</h3>
                  <div class="provider-category">Budget</div>
                </div>
              </div>

              <div class="rating-section">
                <div class="stars">
                  <span class="star">★</span>
                  <span class="star">★</span>
                  <span class="star">★</span>
                  <span class="star">★</span>
                  <span class="star">★</span>
                </div>
                <span class="rating-score">4.7</span>
                <span class="review-count">(1,794 reviews)</span>
              </div>

              <p class="description">
                Premium residential proxies with excellent performance and easy integration
              </p>

              <div class="stats-grid">
                <div class="stat-item">
                  <div class="stat-label">Success Rate</div>
                  <div class="stat-value">98.8%</div>
                </div>
                <div class="stat-item">
                  <div class="stat-label">Response Time</div>
                  <div class="stat-value">0.58s</div>
                </div>
              </div>

              <div class="features">
                <span class="feature-tag">40M+ IPs</span>
                <span class="feature-tag">Easy Integration</span>
                <span class="feature-tag">Pay as you go</span>
                <span class="feature-tag">+4 more</span>
              </div>

              <div class="card-footer">
                <div class="pricing">
                  <div class="starting-label">Starting at</div>
                  <div class="price">From $75/mo</div>
                </div>
                <button class="view-details-btn">
                  View Details
                  <span>→</span>
                </button>
              </div>
            </div>

            <!-- Card 4: GeoSurf -->
            <div class="provider-card">
              <div class="card-header">
                <div class="provider-logo">
                  <img src="https://placehold.co/48x48/68b5f5/white?text=G" alt="GeoSurf">
                </div>
                <div class="provider-info">
                  <h3 class="provider-name">GeoSurf</h3>
                  <div class="provider-category">Premium</div>
                </div>
              </div>

              <div class="rating-section">
                <div class="stars">
                  <span class="star">★</span>
                  <span class="star">★</span>
                  <span class="star">★</span>
                  <span class="star">★</span>
                  <span class="star">★</span>
                </div>
                <span class="rating-score">4.7</span>
                <span class="review-count">(1,654 reviews)</span>
              </div>

              <p class="description">
                Premium residential proxies with precise geo-targeting capabilities
              </p>

              <div class="stats-grid">
                <div class="stat-item">
                  <div class="stat-label">Success Rate</div>
                  <div class="stat-value">99.1%</div>
                </div>
                <div class="stat-item">
                  <div class="stat-label">Response Time</div>
                  <div class="stat-value">0.48s</div>
                </div>
              </div>

              <div class="features">
                <span class="feature-tag">2M+ IPs</span>
                <span class="feature-tag">Geo-Targeting</span>
                <span class="feature-tag">High Quality</span>
                <span class="feature-tag">+4 more</span>
              </div>

              <div class="card-footer">
                <div class="pricing">
                  <div class="starting-label">Starting at</div>
                  <div class="price">From $450/mo</div>
                </div>
                <button class="view-details-btn">
                  View Details
                  <span>→</span>
                </button>
              </div>
            </div>

            <!-- Card 5: SOAX -->
            <div class="provider-card">
              <div class="card-header">
                <div class="provider-logo">
                  <img src="https://placehold.co/48x48/449df0/white?text=SO" alt="SOAX">
                </div>
                <div class="provider-info">
                  <h3 class="provider-name">SOAX</h3>
                  <div class="provider-category">Mid-Range</div>
                </div>
              </div>

              <div class="rating-section">
                <div class="stars">
                  <span class="star">★</span>
                  <span class="star">★</span>
                  <span class="star">★</span>
                  <span class="star">★</span>
                  <span class="star">★</span>
                </div>
                <span class="rating-score">4.6</span>
                <span class="review-count">(4,612 reviews)</span>
              </div>

              <p class="description">
                Flexible proxy solutions with rotating IPs and competitive pricing
              </p>

              <div class="stats-grid">
                <div class="stat-item">
                  <div class="stat-label">Success Rate</div>
                  <div class="stat-value">98.5%</div>
                </div>
                <div class="stat-item">
                  <div class="stat-label">Response Time</div>
                  <div class="stat-value">0.62s</div>
                </div>
              </div>

              <div class="features">
                <span class="feature-tag">5.5M+ IPs</span>
                <span class="feature-tag">Rotating IPs</span>
                <span class="feature-tag">Flexible Plans</span>
                <span class="feature-tag">+4 more</span>
              </div>

              <div class="card-footer">
                <div class="pricing">
                  <div class="starting-label">Starting at</div>
                  <div class="price">From $99/mo</div>
                </div>
                <button class="view-details-btn">
                  View Details
                  <span>→</span>
                </button>
              </div>
            </div>

            <!-- Card 6: Proxy-Seller -->
            <div class="provider-card">
              <div class="card-header">
                <div class="provider-logo">
                  <img src="https://placehold.co/48x48/68b5f5/white?text=PS" alt="Proxy-Seller">
                </div>
                <div class="provider-info">
                  <h3 class="provider-name">Proxy-Seller</h3>
                  <div class="provider-category">Budget</div>
                </div>
              </div>

              <div class="rating-section">
                <div class="stars">
                  <span class="star">★</span>
                  <span class="star">★</span>
                  <span class="star">★</span>
                  <span class="star">★</span>
                  <span class="star">★</span>
                </div>
                <span class="rating-score">4.5</span>
                <span class="review-count">(987 reviews)</span>
              </div>

              <p class="description">
                Budget-friendly proxy service with reliable performance and quick setup
              </p>

              <div class="stats-grid">
                <div class="stat-item">
                  <div class="stat-label">Success Rate</div>
                  <div class="stat-value">97.8%</div>
                </div>
                <div class="stat-item">
                  <div class="stat-label">Response Time</div>
                  <div class="stat-value">0.75s</div>
                </div>
              </div>

              <div class="features">
                <span class="feature-tag">5M+ IPs</span>
                <span class="feature-tag">Budget Friendly</span>
                <span class="feature-tag">Quick Setup</span>
                <span class="feature-tag">+4 more</span>
              </div>

              <div class="card-footer">
                <div class="pricing">
                  <div class="starting-label">Starting at</div>
                  <div class="price">From $39/mo</div>
                </div>
                <button class="view-details-btn">
                  View Details
                  <span>→</span>
                </button>
              </div>
            </div>
          </div>

          <!-- Pagination -->
          <div class="pagination" id="pagination">
            <button class="prev" data-page="prev"><span>Previous</span></button>
            <div class="page-numbers"></div>
            <button class="next" data-page="next"><span>Next</span></button>
          </div>
        </div>
      </div>
    </div>

    <!-- Footer content -->
    <div class="footer_content">
      <div class="wrapper_archive_reivew">
        <div class="footer_box">
          <!-- Badge -->
          <div class="help-badge">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
            </svg>
            Need Help Choosing?
          </div>

          <!-- Main Heading -->
          <h2 class="help-heading">
            Not sure which proxy is right for you?
          </h2>

          <!-- Description -->
          <p class="help-description">
            Our experts can help you find the perfect proxy solution for your specific needs and budget.
          </p>

          <!-- CTA Buttons -->
          <div class="cta-buttons">
            <button class="btn btn-primary">
              Get Expert Advice
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
              </svg>
            </button>

            <a href="#" class="btn btn-secondary">
              Compare All Features
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
  </div>
</main>

<?php get_footer(); ?>