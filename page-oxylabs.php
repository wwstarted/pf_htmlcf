 <?php
   /*
 Template Name: Reviews Page
 */
   ?>
 <!-- Gọi header đã tách (function hộ trợ từ WP) -->
 <?php get_header(); ?>

 <!-- Main content -->
 <!-- header content -->
 <div class="banner-content">
    <div class="wrapper">
       <nav class="breadcrumb" id="bd_ol">
         <!-- fetch data here -->
       </nav>

       <div class="card-wrapper">
          <!-- fetch data here -->
       </div>
    </div>
 </div>

 <!-- content -->
 <main class="review-summary">
    <div class="review-container">
       <!-- Sidebar -->
       <aside class="review-sidebar">
          <h4>On this page</h4>
          <ul>
             <li><a href="#overview" class="active-reviews"><i class="fa-regular fa-file"></i> Overview</a></li>
             <li><a href="#ratings"><i class="fa-regular fa-star"></i> Ratings</a></li>
             <li><a href="#pricing"><i class="fa-solid fa-dollar-sign"></i> Pricing</a></li>
             <li><a href="#features"><i class="fa-solid fa-bolt"></i> Features</a></li>
             <li><a href="#performance"><i class="fa-solid fa-chart-line"></i> Performance</a></li>
             <li><a href="#security"><i class="fa-solid fa-lock"></i> Security</a></li>
             <li><a href="#support"><i class="fa-solid fa-headset"></i> Support</a></li>
             <li><a href="#reviews"><i class="fa-regular fa-message"></i> Reviews</a></li>
             <li><a href="#faq"><i class="fa-regular fa-circle-question"></i> FAQ</a></li>
          </ul>
       </aside>

       <!-- Content -->
       <div class="content-right">
          <!-- Review content -->
          <section id="overview" class="review-content">
             <h2><span class="icon"><i class="fa-regular fa-file"></i></span>Executive Summary</h2>
         <!-- fetch data here -->
          </section>

          <!-- Detailed Ratings -->
          <section id="ratings" class="oxyl-detailed-ratings oxyl-card">
             <h2><span class="icon"><i class="fa-regular fa-star"></i></span>Detailed Ratings</h2>

             <div class="oxyl-ratings-grid">
                <!-- fetch data here -->
             </div>
          </section>

          <!-- Pricing Plans -->
          <section id="pricing" class="oxyl-pricing oxyl-card">
             <h2><span class="icon"><i class="fa-solid fa-dollar-sign"></i></span>Pricing Plans</h2>
             <p class="oxyl-pricing-desc">Choose the plan that fits your needs. All plans include core features with varying bandwidth limits.</p>

             <div class="oxyl-pricing-grid">
                <!-- Starter Plan -->
               <!-- fetch data here -->
             </div>
          </section>

          <!-- Pros & Cons -->
          <section id="" class="oxyl-proscons oxyl-card">
             <h2><span class="icon"></span>Pros & Cons</h2>

             <div class="review-two-cols">
                <div class="review-box best-for">
                   <h4><i class="fa-regular fa-circle-check"></i> Advantages</h4>
                   <ul>
                      <li><i class="fa-regular fa-circle-check"></i> Largest residential IP pool with 100M+ IPs across 195+ countries</li>
                      <li><i class="fa-regular fa-circle-check"></i> Exceptional success rates (99.5%) and reliability</li>
                      <li><i class="fa-regular fa-circle-check"></i> Advanced targeting options including city, ASN, and ZIP code level</li>
                      <li><i class="fa-regular fa-circle-check"></i> 24/7 customer support with industry-leading response times</li>
                      <li><i class="fa-regular fa-circle-check"></i> Comprehensive API with excellent documentation</li>
                      <li><i class="fa-regular fa-circle-check"></i> No bandwidth limits on higher-tier plans</li>
                      <li><i class="fa-regular fa-circle-check"></i> Supports all major proxy types (residential, datacenter, ISP, mobile)</li>
                      <li><i class="fa-regular fa-circle-check"></i> Excellent for enterprise-scale operations</li>
                   </ul>
                </div>

                <div class="review-box not-ideal">
                   <h4><i class="fa-regular fa-circle-xmark"></i> Disadvantages</h4>
                   <ul>
                      <li><i class="fa-regular fa-circle-xmark"></i> Premium pricing may be prohibitive for small businesses or individuals</li>
                      <li><i class="fa-regular fa-circle-xmark"></i> Minimum commitment required for best rates</li>
                      <li><i class="fa-regular fa-circle-xmark"></i> Learning curve for advanced features and configurations</li>
                      <li><i class="fa-regular fa-circle-xmark"></i> Overkill for simple, low-volume use cases</li>
                   </ul>
                </div>
             </div>
          </section>

          <!-- Features Overview -->
          <section id="features" class="oxyl-features oxyl-card">
             <h2><span class="icon"><i class="fa-solid fa-bolt"></i></span>Features Overview</h2>

             <div class="oxyl-features-grid">
                <!-- Proxy Types -->
               <!-- fetch data here -->
             </div>
          </section>

          <!-- Perfect For -->
          <section id="" class="oxyl-perfect oxyl-card">
             <h2><span class="icon"></span>Perfect For</h2>

             <div class="oxyl-perfect-grid">
               <!-- fetch data here -->
             </div>
          </section>

          <!-- Performance Metrics -->
          <section id="performance" class="oxyl-performance oxyl-card">
             <h2><span class="icon"><i class="fa-solid fa-chart-line"></i></span>Performance Metrics</h2>

             <div class="oxyl-stats-grid">
                <!-- Success Rate -->
                <article class="oxyl-stat-card">
                   <h3 class="oxyl-stat-value">99.5%</h3>
                   <p class="oxyl-stat-label">Success Rate</p>
                   <div class="oxyl-stat-bar"></div>
                </article>

                <!-- Avg Response Time -->
                <article class="oxyl-stat-card no-bar">
                   <h3 class="oxyl-stat-value">0.45s</h3>
                   <p class="oxyl-stat-label">Avg Response Time</p>
                   <div class="oxyl-stat-bar"></div>
                </article>

                <!-- Uptime -->
                <article class="oxyl-stat-card">
                   <h3 class="oxyl-stat-value">99.9%</h3>
                   <p class="oxyl-stat-label">Uptime</p>
                   <div class="oxyl-stat-bar"></div>
                </article>

                <!-- Concurrent Sessions -->
                <article class="oxyl-stat-card no-bar">
                   <h3 class="oxyl-stat-value">10,000</h3>
                   <p class="oxyl-stat-label">Concurrent Sessions</p>
                   <div class="oxyl-stat-bar"></div>
                </article>

                <!-- Average Speed -->
                <article class="oxyl-stat-card no-bar">
                   <h3 class="oxyl-stat-value">&lt; 0.5s</h3>
                   <p class="oxyl-stat-label">Average Speed</p>
                   <div class="oxyl-stat-bar"></div>
                </article>

                <!-- Data Accuracy -->
                <article class="oxyl-stat-card">
                   <h3 class="oxyl-stat-value">99.8%</h3>
                   <p class="oxyl-stat-label">Data Accuracy</p>
                   <div class="oxyl-stat-bar"></div>
                </article>
             </div>
          </section>

          <!-- Security & Compliance -->
          <section id="security" class="oxyl-security oxyl-card">
             <h2><span class="icon"><i class="fa-solid fa-lock"></i></span>Security & Compliance</h2>

             <div class="oxyl-security-box">
                <div class="oxyl-security-grid">
                   <!-- fetch data here -->
                </div>
             </div>
          </section>

          <!-- Customer Support -->
          <section id="support" class="oxyl-support oxyl-card">
             <h2><span class="icon"><i class="fa-solid fa-headset"></i></span>Customer Support</h2>

             <div class="oxyl-support-box">
                <div class="oxyl-support-grid">
                   <!-- fetch data here -->
                </div>
             </div>
          </section>

          <!-- User Reviews -->
          <section id="reviews" class="oxyl-user-reviews oxyl-card">
             <h2><span class="icon"><i class="fa-regular fa-message"></i></span>User Reviews</h2>

             <div class="oxyl-review-list">
               <!-- fetch data here -->
             </div>
          </section>

          <!-- FAQ -->
          <section id="faq" class="oxyl-faq oxyl-card">
             <h2><span class="icon"><i class="fa-regular fa-circle-question"></i></span>Frequently Asked Questions</h2>

             <div class="oxyl-faq-list">
                <!-- fetch data here -->
             </div>
          </section>

          <!-- CTA: Ready to get started -->
          <section class="oxyl-cta oxyl-card">
             <h2 class="oxyl-cta-title">Ready to get started with Oxylabs?</h2>
             <p class="oxyl-cta-subtitle">Join thousands of satisfied customers and experience the best proxy service in the industry</p>

             <div class="oxyl-cta-buttons">
                <a href="#" class="oxyl-btn oxyl-btn-primary">
                   <span>Start Free Trial</span>
                   <i class="fas fa-external-link-alt"></i>
                </a>
                <a href="#" class="oxyl-btn oxyl-btn-secondary">
                   <span>Contact Sales</span>
                </a>
             </div>
          </section>
       </div>
    </div>
 </main>

 <!-- Gọi footer đã tách (function hộ trợ từ WP) -->
 <?php get_footer(); ?>