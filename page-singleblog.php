<?php
/**
 * Template Name: Posts Page
 */
    get_header();
?>

    <!-- Main content -->
    <section class="main-content">
      <div class="container">
        <!-- Table of Contents -->
        <aside class="left-content">
          <div class="toc">
            <h3>
              <i class="fa-solid fa-list" style="color: blue"></i
              >&nbsp;&nbsp;Table of Contents
            </h3>
            <ul>
              <!-- fetch data here -->
            </ul>
          </div>
        </aside>
        

        <!-- Article -->
        <article class="right-content">
          <!-- TOC Toggle -->

          <div class="breadcrumb" id="bd_post">
            <!-- fetch data here -->
          </div>

          <div class="single-header">
              <!--fetch data here-->
          </div>

          <div class="share-buttons">
            <button>
              <i class="fa-brands fa-facebook-f"></i
              ><span>&nbsp;&nbsp;&nbsp;Share Article</span>
            </button>
          </div>

          <div class="toc-mobile">
            <button class="toc-toggle">
              <i class="fa-solid fa-list"></i> Show Table of Contents
            </button>
            <div class="toc-content">
              <ul>
                <!-- fetch data here -->
              </ul>
            </div>
          </div>

          <div class="article-body">
            <!-- fetch data here -->
          </div>

          <div class="back-btn">
            <a href="<?php echo home_url('/blog'); ?>" class="btn-back"
              ><i class="fa-solid fa-arrow-left"></i>&nbsp;&nbsp;&nbsp;Back to
              Blog</a
            >
          </div>
        </article>
      </div>
    </section>

    <!-- Related Content -->
    <section class="related-articles">
      <h2>Related Articles</h2>
      <p class="section-desc">
        Continue exploring our insights on proxies, VPNs, and web scraping
      </p>

      <div class="articles-grid">
        
          <!-- fetch data -->
        
      </div>
    </section>

    <!-- Footer -->
    <?php 
        get_footer();
    ?>
