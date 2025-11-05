<?php get_header(); ?>

<main class="reviews-archive">
  <div class="content">
    <!-- Header content -->
    <div class="head_content">
      <div class="wrapper_archive_review">
        <!-- Breadcrumb -->
        <div class="text-line-clamp breadcrumb">
          <a href="<?php echo home_url(); ?>" style="color: black; text-decoration: none;">ProxyFlow</a> <span>/</span>
          <span style="color: black; text-decoration: none;">Reviews</span>
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

            <div class="filter-buttons" id="filterButtons">
             <!-- fetch data here -->
            </div>
          </div>

          <!-- Sort Section -->
          <div class="sort-section" id="sortSelect">
            <label class="sort-label">Sort by:</label>
            <div class="sort-dropdown">
              <select class="sort-select">
                <option value="highest-rated">Highest Rated</option>
                <option value="lowest-price">Lowest Price</option>
                <option value="highest-price">Highest Price</option>
              </select>
            </div>
          </div>
        </div>

        <div class="main_box">
          <div class="results-count" id="resultsCount">Showing 6 providers</div>

          <div class="providers-grid" id="providersGrid">
            <!-- Card 1: Oxylabs -->
           <!-- fetch data here -->
          </div>

          <!-- Pagination -->
          <div class="pagination" id="pagination">
            <button class="prev" id="prevBtn" data-page="prev"><span>Previous</span></button>
            <div class="page-numbers" id="pageNumbers"></div>
            <button class="next" id="nextBtn" data-page="next"><span>Next</span></button>
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