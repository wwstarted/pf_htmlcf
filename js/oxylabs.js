
    // Cuộn mượt khi click
    document.querySelectorAll('.review-sidebar a[href^="#"]').forEach(link => {
       link.addEventListener('click', function(e) {
          e.preventDefault();
          const target = document.querySelector(this.getAttribute('href'));
          if (target) {
             window.scrollTo({
                top: target.offsetTop - 100, // trừ đi chiều cao header cố định nếu có
                behavior: 'smooth'
             });
          }
       });
    });

    // Scroll spy: tự highlight khi cuộn
    const sections = document.querySelectorAll('section[id]');
    const navLinks = document.querySelectorAll('.review-sidebar a');

    window.addEventListener('scroll', () => {
       let current = '';
       sections.forEach(section => {
          const sectionTop = section.offsetTop - 120;
          const sectionHeight = section.offsetHeight;
          if (scrollY >= sectionTop && scrollY < sectionTop + sectionHeight) {
             current = section.getAttribute('id');
          }
       });

       navLinks.forEach(link => {
          link.classList.remove('active-reviews');
          if (link.getAttribute('href') === `#${current}`) {
             link.classList.add('active-reviews');
          }
       });
    });

    
// ================================== fetch rest api ===============================
// ====================fetch card wrapper=====================

document.addEventListener("DOMContentLoaded", async () => {
  const API_BASE = "http://localhost/PF_HTMLCF/wordpress/wp-json/wp/v2";
   let sharedowId = null;

   async function getSharedOverviewId() {
    if (sharedowId) return sharedowId;
    const params = new URLSearchParams(window.location.search);
    let postId = params.get("provider_id");
    sharedowId = postId;
    console.log("✅ Post ID dùng chung:", sharedowId);
    return sharedowId;
  }

  const owId = await getSharedOverviewId();

  const proxy_best = document.querySelector(".card-wrapper");

  try {
    const providers = await fetch(`${API_BASE}/providers/${owId}`);
    const pvd = await providers.json();

    
   const pd = pvd.provider_data || {};

      // Lấy data từ provider_data
   const logo = pd?.logo;
    const title = pvd.title?.rendered || "No title";
    const summary = pd?.summary;
    const rating = Number(pd.rating);
    const thumbnail = pd?.thumbnail;
    const advanced = pd?.advanced;

    let ad = [];
    if (advanced && typeof advanced === "object") {
      ad = Object.values(advanced);
    }

    const adHTML = ad
      .filter(Boolean)
      .slice(0, 3)
      .map((item) => {
        const [value, ...rest] = item.split(" ");
        const label = rest.join(" ");

        return `
          <div class="stat-item">
            <div class="stat-value">${value}</div>
            <div class="stat-label">${label}</div>
          </div>
        `;
      })
      .join("");

       // Tạo stars HTML
    const fullStars = Math.floor(rating);
    const emptyStars = 5 - fullStars;
    const starsHTML = 
      '<span class="star">★</span>'.repeat(fullStars) + 
      '<span class="star empty">★</span>'.repeat(emptyStars);


    const bannerHTML = `
      <div class="left-section">
        <div class="badges">
          <div class="badge badge-editor">
            <span>🏆</span>
            <span>Editor's Choice</span>
          </div>
          <div class="badge badge-score">
            Editor Score: 9.5/10
          </div>
        </div>

        <div class="product-header">
          <div class="logo-container">
            <img src="${logo}" alt="${title}">
          </div>
          <div class="product-info">
            <h1>${title}</h1>
            <p>${summary}</p>
          </div>
        </div>

        <div class="rating-section">
          <div class="stars">
            ${starsHTML}
          </div>
         <span class="rating-number">${rating.toFixed(1)}</span>
          <span class="review-count">Based on 1,250 reviews</span>
        </div>

        <div class="stats-grid">
          ${adHTML}
        </div>

        <div class="action-buttons">
          <button class="btn btn-primary">
            Visit Oxylabs
            <i class="fa-solid fa-arrow-up-right-from-square"></i>
          </button>
          <button class="btn btn-secondary">
            Start Free Trial
          </button>
        </div>
      </div>

      <div class="right-section">
        <div class="right-section-image">
          <img src="${thumbnail}" alt="${title}">
        </div>
      </div>
    `;

      proxy_best.insertAdjacentHTML("beforeend", bannerHTML);
  } catch (error) {
    console.error("Lỗi khi fetch providers:", error);
    proxy_best.innerHTML = `<p style="color: red;">Không thể tải dữ liệu providers. Vui lòng kiểm tra API.</p>`;
  }
});

// =========================== fetch overview ================================

document.addEventListener("DOMContentLoaded", async () => {

  const API_BASE = "http://localhost/PF_HTMLCF/wordpress/wp-json/wp/v2";
   let sharedowId = null;

   async function getSharedOverviewId() {
    if (sharedowId) return sharedowId;
    const params = new URLSearchParams(window.location.search);
    let postId = params.get("provider_id");
    sharedowId = postId;
    console.log("✅ Post ID dùng chung:", sharedowId);
    return sharedowId;
  }

  const owId = await getSharedOverviewId();
  
  const banner = document.querySelector("#overview");
  

  try {
    const post = await fetch(`${API_BASE}/providers/${owId}`);
    const p = await post.json();

    const ov = p.provider_data?.description || {};
    const overview = ov?.overview;
    const ourverdict = ov?.our_verdict;
    const bestfor = ov?.best_for;
    const notfor = ov?.not_ideal_for;

    let bf = [];
    if (bestfor && typeof bestfor === "object") {
      bf = Object.values(bestfor);
    }

    const bfHTML = bf
        .map(
          (f) => `
          <li><i class="fa-regular fa-circle-check"></i>${f}</li>
        `
        )
        .join("");

    let nf = [];
    if (notfor && typeof notfor === "object") {
      nf = Object.values(notfor);
    }

      const nfHTML = nf
        .map(
          (f) => `
           <li><i class="fa-regular fa-circle-xmark"></i> ${f}</li>
        `
        )
        .join("");

    const adsHTML = `
            <div class="review-summary-box">
                <p>
                   ${overview}
                </p>

                <div class="review-verdict-inner">
                   <h4>Our Verdict</h4>
                   <p>
                      ${ourverdict}
                   </p>
                </div>
             </div>

             <div class="review-two-cols">
                <div class="review-box best-for">
                   <h4><i class="fa-regular fa-circle-check"></i> Best For</h4>
                   <ul>
                        ${bfHTML}
                   </ul>
                </div>

                <div class="review-box not-ideal">
                   <h4><i class="fa-regular fa-circle-xmark"></i> Not Ideal For</h4>
                   <ul>
                     ${nfHTML}
                   </ul>
                </div>
             </div>
    `;

    banner.insertAdjacentHTML("beforeend", adsHTML);

  } catch (err) {
    console.error("Lỗi fetch header:", err);
  }

});

// ===========================fetch details ratings =======================
document.addEventListener("DOMContentLoaded", async () => {

 const API_BASE = "http://localhost/PF_HTMLCF/wordpress/wp-json/wp/v2";
   let sharedowId = null;

   async function getSharedOverviewId() {
    if (sharedowId) return sharedowId;
    const params = new URLSearchParams(window.location.search);
    let postId = params.get("provider_id");
    sharedowId = postId;
    console.log("✅ Post ID dùng chung:", sharedowId);
    return sharedowId;
  }

  const owId = await getSharedOverviewId();
  
  try {
    const post = await fetch(`${API_BASE}/providers/${owId}`);
    const p = await post.json();
    const acf = p.provider_data?.description;

    const detailedRatings = acf?.detailed_ratings;
    console.log(detailedRatings);
    let dr = [];

    if (detailedRatings && typeof detailedRatings === "object") {
      dr = Object.entries(detailedRatings);
    }

    const drHTML = dr
      .map(([key, val]) => {
      //   const title = key.replace(/_/g, " ").replace(/\b\w/g, (c) => c.toUpperCase());
        const title = val?.title;
        const summary = val?.summary || "";
        const rating = Number(val?.rating) || 0;
        const percent = Math.min(Math.floor((rating / 5) * 100), 100);

        return `
          <div class="oxyl-rating-row">
            <div class="oxyl-rating-header">
              <div class="oxyl-rating-meta">
                <strong>${title}</strong>
                <span class="oxyl-small">${summary}</span>
              </div>
              <div class="oxyl-rating-number">${rating.toFixed(1)} <span class="oxyl-rating-total">/5.0</span></div>
            </div>
            <div class="oxyl-rating-bar" aria-hidden="true">
              <span class="oxyl-rating-fill" style="width:${percent}%"></span>
            </div>
          </div>
        `;
      })
      .join("");

    const ratingContainer = document.querySelector(".oxyl-ratings-grid");

    if (ratingContainer) {
      ratingContainer.insertAdjacentHTML("beforeend", drHTML);
    }

  } catch (err) {
    console.error("Lỗi fetch header:", err);
  }


});

// =================================fetch pricing =================================
// Thêm hàm này vào file JavaScript của bạn
document.addEventListener("DOMContentLoaded", async () => {
  const API_BASE = "http://localhost/PF_HTMLCF/wordpress/wp-json/wp/v2";
  
  async function getSharedOverviewId() {
    const params = new URLSearchParams(window.location.search);
    let postId = params.get("provider_id");
    console.log("✅ Post ID:", postId);
    return postId;
  }

  const owId = await getSharedOverviewId();
  const pricingContainer = document.querySelector(".oxyl-pricing-grid");


  try {
    console.log("🔄 Fetching pricing plans...");
    const response = await fetch(`${API_BASE}/providers/${owId}`);
    
    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }
    
    const pvd = await response.json();
    const pricingPlans = pvd.provider_data?.description?.pricing_plans || [];
    
    console.log("✅ Pricing plans data:", pricingPlans);

    if (pricingPlans.length === 0) {
      pricingContainer.innerHTML = `<p style="text-align: center; color: #666;">Chưa có pricing plans nào.</p>`;
      return;
    }

    // Clear container
    pricingContainer.innerHTML = '';

    // Render từng plan
    pricingPlans.forEach((plan, index) => {
      const title = plan.title || "Untitled Plan";
      const price = plan.price || "$0";
      const min = plan.min || "N/A";
      const features = plan.features || [];

      // Tạo HTML cho features list
      const featuresHTML = features
        .map(feature => `<li><i class="fa-regular fa-circle-check"></i> ${feature}</li>`)
        .join('');

      // Xác định class và badge cho plan
      let planClass = 'oxyl-plan';
      let badgeHTML = '';
      let buttonClass = 'oxyl-btn-outline';
      let buttonText = 'Get Started';

      // Plan giữa (index 1) sẽ là Recommended
      if (index === 1 && pricingPlans.length >= 3) {
        planClass += ' oxyl-plan-professional';
        badgeHTML = '<div class="oxyl-plan-badge">Recommended</div>';
        buttonClass = 'oxyl-btn-primary';
      } else if (index === 0) {
        planClass += ' oxyl-plan-starter';
      } else {
        planClass += ' oxyl-plan-enterprise';
        // Nếu là plan cuối và có "custom" hoặc "enterprise" trong title
        if (title.toLowerCase().includes('custom') || title.toLowerCase().includes('enterprise')) {
          buttonText = 'Contact Sales';
        }
      }

      // Tạo HTML cho plan
      const planHTML = `
        <article class="${planClass}">
          ${badgeHTML}
          <h4 class="oxyl-plan-name">${title}</h4>
          <div class="oxyl-plan-price">
            <span class="oxyl-price-amount">$${price}</span>
            <span class="oxyl-price-unit">/ per GB</span>
          </div>
          <p class="oxyl-plan-min">Min: ${min}GB</p>

          <ul class="oxyl-plan-feat">
            ${featuresHTML}
          </ul>

          <a class="oxyl-btn ${buttonClass}" href="#">${buttonText}</a>
        </article>
      `;

      pricingContainer.insertAdjacentHTML('beforeend', planHTML);
    });

    console.log("✅ Render pricing plans thành công!");

  } catch (error) {
    console.error("Lỗi khi fetch pricing plans:", error);
    pricingContainer.innerHTML = `
      <div style="padding: 20px; color: red; border: 1px solid red; border-radius: 8px; background: #fff0f0;">
        <h3>Không thể tải pricing plans</h3>
        <p><strong>Lỗi:</strong> ${error.message}</p>
        <p>Vui lòng kiểm tra Console để xem chi tiết</p>
      </div>
    `;
  }
});

// ===================================== fetch features overview  ============================
document.addEventListener("DOMContentLoaded", async () => {

  const API_BASE = "http://localhost/PF_HTMLCF/wordpress/wp-json/wp/v2";
  let sharedowId = null;

  async function getSharedOverviewId() {
    if (sharedowId) return sharedowId;
    const params = new URLSearchParams(window.location.search);
    let postId = params.get("provider_id");
    sharedowId = postId;
    console.log("✅ Post ID dùng chung:", sharedowId);
    return sharedowId;
  }

  const owId = await getSharedOverviewId();

  try {
    const res = await fetch(`${API_BASE}/providers/${owId}`);
    const p = await res.json();

    const featuresOverview = p?.provider_data?.description?.features_overview;


    const container = document.querySelector(".oxyl-features-grid");
    

    // Clear existing content
    container.innerHTML = "";

    let out = "";

    // Loop qua từng feature group trong array
    featuresOverview.forEach((featureGroup) => {

      const groupTitle = featureGroup.title || "Untitled";
      const items = featureGroup.items || [];

      if (!Array.isArray(items) || items.length === 0) {
        console.warn("No items in group:", groupTitle);
        return;
      }

      let itemsHtml = "";

      // Loop qua từng item trong group
      items.forEach((item) => {
        const title = item.title || "";
        const summary = item.summary || "";

        itemsHtml += `
          <li class="oxyl-feature-item">
            <div class="oxyl-feature-item-header">
              <i class="fa-regular fa-circle-check"></i>
              <span class="oxyl-feature-item-title">${title}</span>
            </div>
            <p class="oxyl-feature-item-desc">${summary}</p>
          </li>
        `;
      });

      // Tạo feature box cho group này
      out += `
        <div class="oxyl-feature-box">
          <h3>${groupTitle}</h3>
          <ul class="oxyl-feature-list">
            ${itemsHtml}
          </ul>
        </div>
      `;
    });

    // Insert vào container
    container.innerHTML = out;
    
    console.log("Features overview loaded successfully");
    
  } catch (err) {
    console.error("Error loading features overview:", err);
  }

});

// ==================================fetch perfect for ========================================== 
document.addEventListener("DOMContentLoaded", async () => {

 const API_BASE = "http://localhost/PF_HTMLCF/wordpress/wp-json/wp/v2";
  let sharedowId = null;

  async function getSharedOverviewId() {
    if (sharedowId) return sharedowId;
    const params = new URLSearchParams(window.location.search);
    let postId = params.get("provider_id");
    sharedowId = postId;
    console.log("✅ Post ID dùng chung:", sharedowId);
    return sharedowId;
  }

  const owId = await getSharedOverviewId();

  function renderPerfectFor(acf) {
    const perfectFor = acf?.description?.perfect_for;
    const container = document.querySelector(".oxyl-perfect-grid");

    let html = "";

    Object.values(perfectFor).forEach((item) => {
      if (!item) return;

      const iconClass = item.icon  || "";
      const title = item.title || "";
      const summary = item.summary || "";
      const desc = item.desc || "";

      html += `
        <article class="oxyl-use-case">
          <div class="oxyl-use-case-icon">
            <i class="${iconClass}"></i>
          </div>
          <h3 class="oxyl-use-case-title">${title}</h3>
          <p class="oxyl-use-case-desc">${summary}</p>
          <p class="oxyl-use-case-detail">${desc}</p>
        </article>
      `;
    });

    container.insertAdjacentHTML("beforeend", html);
  }

  try {
    const res = await fetch(`${API_BASE}/providers/${owId}`);
    const provider = await res.json();

    const acf = provider?.provider_data;

    // Render
    renderPerfectFor(acf);

  } catch (err) {
    console.error("Lỗi khi fetch provider:", err);
  }


});

// ============================= fetch security ======================
document.addEventListener("DOMContentLoaded", async () => {

  const API_BASE = "http://localhost/PF_HTMLCF/wordpress/wp-json/wp/v2";
  let sharedowId = null;

  async function getSharedOverviewId() {
    if (sharedowId) return sharedowId;
    const params = new URLSearchParams(window.location.search);
    let postId = params.get("provider_id");
    sharedowId = postId;
    console.log("✅ Post ID dùng chung:", sharedowId);
    return sharedowId;
  }

  const owId = await getSharedOverviewId();


   const banner = document.querySelector(".oxyl-security-grid");
  

  try {
    const post = await fetch(`${API_BASE}/providers/${owId}`);

    const p = await post.json();

    const sec = p?.provider_data?.description?.security|| {};

    const encryption = sec?.encryption;
    const compliance = sec?.compliance || "No title";
    const authentication = sec?.authentication;
    const privacy = sec?.privacy;

    let en = [];
    if (encryption && typeof encryption === "object") {
      en = Object.values(encryption);
    }

    
      const enHTML = en
        .map(
          (f) => `
          <div class="oxyl-security-content">${f}</div>     
        `
        )
        .join("");

    let co = [];
    if (compliance && typeof compliance === "object") {
      co = Object.values(compliance);
    }

    
      const coHTML = co
        .map(
          (f) => `
          <span class="oxyl-tag">${f}</span>     
        `
        )
        .join("");
      //   

        let au = [];
    if (authentication && typeof authentication === "object") {
      au = Object.values(authentication);
    }

    
      const auHTML = au
        .map(
          (f) => `
                        <li class="oxyl-auth-item">
                            <i class="fa-regular fa-circle-check"></i>
                            <span>${f}</span>
                         </li>
        `
        )
        .join("");

        let pr= [];
    if (privacy && typeof privacy === "object") {
      pr = Object.values(privacy);
    }

    
      const prHTML = pr
        .map(
          (f) => `
          <p class="oxyl-privacy-item-1">${f}</p>
          
        `
        )
        .join("");

    const adsHTML = `
                   <section class="oxyl-security-section">
                      <h3 class="oxyl-security-title">Encryption</h3>
                      ${enHTML}
                   </section>

                   <!-- Compliance -->
                   <section class="oxyl-security-section">
                      <h3 class="oxyl-security-title">Compliance</h3>
                      <div class="oxyl-compliance-tags">
                         ${coHTML}
                      </div>
                   </section>

                   <!-- Authentication -->
                   <section class="oxyl-security-section">
                      <h3 class="oxyl-security-title">Authentication</h3>
                      <ul class="oxyl-auth-list">
                        ${auHTML}
                      </ul>
                   </section>

                   <!-- Privacy -->
                   <section class="oxyl-security-section">
                      <h3 class="oxyl-security-title">Privacy</h3>
                      <div class="oxyl-privacy-text">
                         ${prHTML}
                      </div>
                   </section>
    `;

    banner.insertAdjacentHTML("beforeend", adsHTML);

  } catch (err) {
    console.error("Lỗi fetch header:", err);
  }


});


// ============================= fetch supports ======================
document.addEventListener("DOMContentLoaded", async () => {

    const API_BASE = "http://localhost/PF_HTMLCF/wordpress/wp-json/wp/v2";
  let sharedowId = null;

  async function getSharedOverviewId() {
    if (sharedowId) return sharedowId;
    const params = new URLSearchParams(window.location.search);
    let postId = params.get("provider_id");
    sharedowId = postId;
    console.log("✅ Post ID dùng chung:", sharedowId);
    return sharedowId;
  }

  const owId = await getSharedOverviewId();


   const banner = document.querySelector(".oxyl-support-grid");
  

  try {
    const post = await fetch(`${API_BASE}/providers/${owId}`);

    const p = await post.json();

    const sec = p?.provider_data?.description?.support || {};

    const availability = sec?.availability;
    const support_channels = sec?.support_channels || "No title";
    const languages = sec?.languages;
    const resources = sec?.resources;

    let co = [];
    if (availability && typeof availability === "object") {
      co = Object.values(availability);
    }

    
      const coHTML = co
        .map(
          (f) => `
           <p class="oxyl-availability-main">${f}</p>    
        `
        )
        .join("");
      //   

        let au = [];
    if (support_channels && typeof support_channels === "object") {
      au = Object.values(support_channels);
    }

    
      const auHTML = au
        .map(
          (f) => `
                        <li class="oxyl-channel-item">
                            <i class="fa-regular fa-circle-check"></i>
                            <span>${f}</span>
                         </li>
               
        `
        )
        .join("");

        let pr= [];
    if (languages && typeof languages === "object") {
      pr = Object.values(languages);
    }

    
      const prHTML = pr
        .map(
          (f) => `
          <span class="oxyl-lang-tag">${f}</span>
        `
        )
        .join("");

        let re= [];
    if (resources && typeof resources === "object") {
      re = Object.values(resources);
    }

    
      const reHTML = re
        .map(
          (f) => `
           <p class="oxyl-resources-item-1">${f}</p>
        `
        )
        .join("");

    const adsHTML = `
                   <section class="oxyl-support-section">
                      <h3 class="oxyl-support-title">Availability</h3>
                      <div class="oxyl-availability-text">
                         ${coHTML}
                      </div>
                   </section>

                   <!-- Support Channels -->
                   <section class="oxyl-support-section">
                      <h3 class="oxyl-support-title">Support Channels</h3>
                      <ul class="oxyl-channels-list">
                         ${auHTML}
                         
                      </ul>
                   </section>

                   <!-- Languages -->
                   <section class="oxyl-support-section">
                      <h3 class="oxyl-support-title">Languages</h3>
                      <div class="oxyl-languages-tags">
                         ${prHTML}
                      </div>
                   </section>

                   <!-- Resources -->
                   <section class="oxyl-support-section">
                      <h3 class="oxyl-support-title">Resources</h3>
                      <div class="oxyl-resources-text">
                        ${reHTML}
                      </div>
                   </section>
    `;

    banner.insertAdjacentHTML("beforeend", adsHTML);

  } catch (err) {
    console.error("Lỗi fetch header:", err);
  }


});

// ============================= fetch user review ======================
document.addEventListener("DOMContentLoaded", async () => {

  const API_BASE = "http://localhost/PF_HTMLCF/wordpress/wp-json/wp/v2";
  let sharedowId = null;

  async function getSharedOverviewId() {
    if (sharedowId) return sharedowId;
    const params = new URLSearchParams(window.location.search);
    let postId = params.get("provider_id");
    sharedowId = postId;
    console.log("✅ Post ID dùng chung:", sharedowId);
    return sharedowId;
  }

  const owId = await getSharedOverviewId();

  try {
    const res = await fetch(`${API_BASE}/providers/${owId}`);
    const p = await res.json();

    const userReviews = p?.provider_data?.description?.user_reviews;


    const container = document.querySelector(".oxyl-review-list");
   

    // Clear existing content
    container.innerHTML = "";

    // Function để render stars dựa trên rating
    function renderStars(rating) {
      let starsHtml = "";
      const fullStars = Math.floor(rating);
      const hasHalfStar = rating % 1 !== 0;
      
      // Full stars
      for (let i = 0; i < fullStars; i++) {
        starsHtml += '<i class="fa-solid fa-star"></i>';
      }
      
      // Half star (optional, nếu có rating như 4.5)
      if (hasHalfStar) {
        starsHtml += '<i class="fa-solid fa-star-half-stroke"></i>';
      }
      
      // Empty stars
      const emptyStars = 5 - Math.ceil(rating);
      for (let i = 0; i < emptyStars; i++) {
        starsHtml += '<i class="fa-regular fa-star empty"></i>';
      }
      
      return starsHtml;
    }

    let out = "";

    // Loop qua từng review
    userReviews.forEach((review) => {
      const rating = Number(review.rating) || 5;
      const content = review.comment || "";
      const author = review.author_name || "Anonymous";
      const role = review.author_role || "";
      const date = review.date || "";
      const isVerified = review.verified !== false; // default true nếu không có field

      out += `
        <article class="oxyl-review-card">
          <div class="oxyl-review-header">
            <div class="oxyl-review-stars">
              ${renderStars(rating)}
            </div>
            ${isVerified ? `
            <div class="oxyl-verified-badge">
              <i class="fa-regular fa-circle-check"></i>
              <span>Verified</span>
            </div>
            ` : ''}
          </div>

          <p class="oxyl-review-content">
            ${content}
          </p>

          <div class="oxyl-review-footer">
            <div class="oxyl-reviewer-info">
              <div class="oxyl-reviewer-name">${author}</div>
               <div class="oxyl-reviewer-title">${role}</div>
            </div>
             <div class="oxyl-review-time">${date}</div>
          </div>
        </article>
      `;
    });

    // Insert vào container
    container.innerHTML = out;
    
    console.log("✅ User reviews loaded successfully");
    
  } catch (err) {
    console.error("❌ Error loading user reviews:", err);
  }

});

// ============================= fetch faq ======================
document.addEventListener("DOMContentLoaded", async () => {

  const API_BASE = "http://localhost/PF_HTMLCF/wordpress/wp-json/wp/v2";
  let sharedowId = null;

  async function getSharedOverviewId() {
    if (sharedowId) return sharedowId;
    const params = new URLSearchParams(window.location.search);
    let postId = params.get("provider_id");
    sharedowId = postId;
    console.log("✅ Post ID dùng chung:", sharedowId);
    return sharedowId;
  }

  const owId = await getSharedOverviewId();

  try {
    const res = await fetch(`${API_BASE}/providers/${owId}`);
    const p = await res.json();

    const userReviews = p?.provider_data?.description?.faq;


    const container = document.querySelector(".oxyl-faq-list");
   

    // Clear existing content
    container.innerHTML = "";

    // Function để render stars dựa trên rating
    let out = "";

    // Loop qua từng review
    userReviews.forEach((review) => {
      const question = review.question || 5;
      const answer = review.answer || "";
  

      out += `
        <article class="oxyl-faq-item">
                   <h3 class="oxyl-faq-question">${question}</h3>
                   <p class="oxyl-faq-answer">${answer}</p>
         </article>
      `;
    });

    // Insert vào container
    container.innerHTML = out;
    
    console.log("✅ User reviews loaded successfully");
    
  } catch (err) {
    console.error("❌ Error loading user reviews:", err);
  }

});  

// ============================= fetch performance metric ======================
document.addEventListener("DOMContentLoaded", async () => {

  const API_BASE = "http://localhost/PF_HTMLCF/wordpress/wp-json/wp/v2";
  let sharedowId = null;

  async function getSharedOverviewId() {
    if (sharedowId) return sharedowId;
    const params = new URLSearchParams(window.location.search);
    let postId = params.get("provider_id");
    sharedowId = postId;
    console.log("✅ Post ID dùng chung:", sharedowId);
    return sharedowId;
  }

  const owId = await getSharedOverviewId();

  try {
    const res = await fetch(`${API_BASE}/providers/${owId}`);
    const p = await res.json();

    const performanceMetrics = p?.provider_data?.description?.performance_metrics;
    const container = document.querySelector(".oxyl-stats-grid");

    // Clear existing content
    container.innerHTML = "";

    // Helper functions
    function isPercentage(value) {
      return value && value.toString().includes('%');
    }

    function isTime(value) {
      return value && (value.toString().includes('s') || value.toString().includes('ms'));
    }

    function isLargeNumber(value) {
      const numericValue = value.toString().replace(/,/g, '');
      return !isNaN(numericValue) && parseFloat(numericValue) >= 100;
    }

    function extractNumber(value) {
      return parseFloat(value.toString().replace(/[^0-9.]/g, ''));
    }

    // Generate scatter dots SVG (giống design)
    function generateScatterDots(count) {
      const dots = [];
      const basePositions = [
        // Row 1 - top
        {x: 15, y: 20}, {x: 35, y: 15}, {x: 55, y: 22}, {x: 75, y: 18}, 
        {x: 95, y: 24}, {x: 115, y: 16}, {x: 135, y: 20}, {x: 155, y: 23},
        {x: 175, y: 18}, {x: 195, y: 21}, {x: 215, y: 19},
        // Row 2  
        {x: 25, y: 42}, {x: 45, y: 38}, {x: 65, y: 44}, {x: 85, y: 40},
        {x: 105, y: 45}, {x: 125, y: 39}, {x: 145, y: 43}, {x: 165, y: 41},
        {x: 185, y: 46}, {x: 205, y: 40},
        // Row 3
        {x: 18, y: 62}, {x: 38, y: 58}, {x: 58, y: 65}, {x: 78, y: 60},
        {x: 98, y: 66}, {x: 118, y: 59}, {x: 138, y: 63}, {x: 158, y: 61},
        {x: 178, y: 67}, {x: 198, y: 62}, {x: 218, y: 64},
        // Center big dots
        {x: 160, y: 53, size: 14, opacity: 0.7}
      ];
      
      basePositions.forEach((pos, i) => {
        if (i >= count) return;
        const size = pos.size || (2 + Math.random() * 2);
        const opacity = pos.opacity || (0.35 + Math.random() * 0.35);
        dots.push(`<circle cx="${pos.x}" cy="${pos.y}" r="${size}" fill="#60a5fa" opacity="${opacity}" />`);
      });
      
      return `<svg class="oxyl-scatter-dots" viewBox="0 0 240 80" xmlns="http://www.w3.org/2000/svg">
        ${dots.join('')}
      </svg>`;
    }

    function generateCircularProgress(value, maxValue = 1) {
      const percentage = Math.min((value / maxValue) * 75, 75);
      const radius = 50;
      const circumference = 2 * Math.PI * radius;
      const offset = circumference - (percentage / 100) * circumference;
      
      return `<div class="oxyl-circular-container">
        <svg class="oxyl-circular-progress" viewBox="0 0 140 140" xmlns="http://www.w3.org/2000/svg">
          <circle cx="70" cy="70" r="${radius}" fill="none" stroke="#e5e7eb" stroke-width="12"/>
          <circle cx="70" cy="70" r="${radius}" fill="none" stroke="#60a5fa" stroke-width="12" 
                  stroke-dasharray="${circumference}" 
                  stroke-dashoffset="${offset}"
                  stroke-linecap="round"
                  transform="rotate(135 70 70)"
                  class="oxyl-progress-circle"/>
        </svg>
        <div class="oxyl-circular-value">${value}</div>
      </div>`;
    }

    let out = "";

    // Loop qua từng metric
    performanceMetrics.forEach((metric) => {
      const icon = metric.icon || "";
      const tag = metric.tag || "";
      const title = metric.title || "";
      const value = metric.value || "";
      const subtitle = metric.subtitle || "";

      const numericValue = extractNumber(value);
      let cardClass = "oxyl-stat-card";

      if (isPercentage(value)) {
        // Card với thanh progress bar (bar ở dưới cùng)
        cardClass += " oxyl-card-bar";
        out += `
          <article class="${cardClass}">
            <div class="oxyl-stat-header">
              ${icon ? `<div class="oxyl-stat-icon"><i class="${icon}"></i></div>` : ''}
              ${tag ? `<span class="oxyl-stat-tag">${tag}</span>` : ''}
            </div>
            <h3 class="oxyl-stat-value">${value}</h3>
            <p class="oxyl-stat-label">${title}</p>
            <div class="oxyl-stat-bar">
              <div class="oxyl-stat-bar-fill" style="width: ${numericValue}%"></div>
            </div>
          </article>`;
          
      } else if (isTime(value)) {
        // Card với vòng tròn progress (circular ở giữa)
        cardClass += " oxyl-card-circular";
        out += `
          <article class="${cardClass}">
            <div class="oxyl-stat-header">
              ${icon ? `<div class="oxyl-stat-icon"><i class="${icon}"></i></div>` : ''}
              ${tag ? `<span class="oxyl-stat-tag">${tag}</span>` : ''}
            </div>
            ${generateCircularProgress(numericValue)}
            <p class="oxyl-stat-label">${title}</p>
          </article>`;
          
      } else if (isLargeNumber(value)) {
        // Card với scatter dots (dots ở dưới)
        cardClass += " oxyl-card-scatter";
        const dotCount = Math.min(Math.floor(numericValue / 300), 35);
        out += `
          <article class="${cardClass}">
            <div class="oxyl-stat-header">
              ${icon ? `<div class="oxyl-stat-icon"><i class="${icon}"></i></div>` : ''}
              ${tag ? `<span class="oxyl-stat-tag">${tag}</span>` : ''}
            </div>
            <h3 class="oxyl-stat-value">${value.toLocaleString()}</h3>
            <p class="oxyl-stat-label">${title}</p>
            ${generateScatterDots(dotCount)}
          </article>`;
      }
    });

    // Insert vào container
    container.innerHTML = out;
    
    console.log("✅ Performance metrics loaded successfully");
    
  } catch (err) {
    console.error("❌ Error loading performance metrics:", err);
  }

});
// ==================== fetch breakcrumb ==============================
document.addEventListener("DOMContentLoaded", async () => { 
  const API_BASE = "http://localhost/PF_HTMLCF/wordpress/wp-json/wp/v2";
  let sharedPostId = null;

  async function getSharedPostId() {
    if (sharedPostId) return sharedPostId;
    const params = new URLSearchParams(window.location.search);
    let postId = params.get("provider_id");
    sharedPostId = postId;
    console.log("Post ID dùng chung:", sharedPostId);
    return sharedPostId;
  }

  const owId = await getSharedPostId();


  
  try {
    const res = await fetch(`${API_BASE}/providers/${owId}`);
    const p = await res.json();

    const container = document.querySelector("#bd_ol");
    
    container.innerHTML = "";

    let out = "";
     
    const title = p.title?.rendered || "title";

    const url =`${WP_HOME}`;
    

    out += `
         <a href="${url}">ProxyFlow</a> <span>/</span>
          <a href="#">Providers</a> <span>/</span>
          <span>${title}</span>
      `;
    

    // Insert vào container
    container.innerHTML = out;
    
    console.log("✅ User reviews loaded successfully");
    
  } catch (err) {
    console.error("❌ Error loading user reviews:", err);
  }




});



