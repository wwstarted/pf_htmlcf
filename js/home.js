// Testimonials Slider - Scroll by groups
let currentSlide = 0;
const sliderTrack = document.getElementById("sliderTrack");
let slides = document.querySelectorAll(".slide");
let totalSlides = slides.length;
const dotsContainer = document.getElementById("sliderDots");
let autoSlideInterval;

// Detect how many slides to show based on screen size
function getSlidesToShow() {
  if (window.innerWidth < 768) return 1; // Mobile: 1 card
  if (window.innerWidth < 1024) return 1; // Tablet: 1 card
  return 2; // Desktop: 2 cards
}

// Calculate total dots (groups)
function getTotalDots() {
  const slidesToShow = getSlidesToShow();
  // Mỗi dot = 1 nhóm hiển thị
  return Math.ceil(totalSlides / slidesToShow);
}

// Create dots
function createDots() {
  dotsContainer.innerHTML = '';
  const totalDots = getTotalDots();
  
  for (let i = 0; i < totalDots; i++) {
    const dot = document.createElement("span");
    dot.className = "dot";
    if (i === 0) dot.classList.add("active");
    dot.addEventListener("click", () => goToSlide(i));
    dotsContainer.appendChild(dot);
  }
}

function updateSlider() {
  const slidesToShow = getSlidesToShow();
  
  // Tính toán width
  const slideWidth = slides[0].getBoundingClientRect().width;
  const gap = window.innerWidth >= 1024 ? 28 : 0;
  const moveDistance = slideWidth + gap;
  
  const translateValue = currentSlide * moveDistance;
  sliderTrack.style.transform = `translateX(-${translateValue}px)`;
  
  // Update dots
  let activeDot;
  if (window.innerWidth >= 1024) {
    // Desktop: tính dot dựa trên vị trí nhóm
    const remainder = totalSlides % 2;
    const isLastCard = (remainder === 1 && currentSlide === totalSlides - 1);
    
    if (isLastCard) {
      // Đang ở card lẻ cuối → dot cuối cùng
      activeDot = Math.ceil(totalSlides / 2) - 1;
    } else {
      // Đang ở nhóm 2 cards
      activeDot = Math.floor(currentSlide / 2);
    }
  } else {
    // Mobile: mỗi card = 1 dot
    activeDot = currentSlide;
  }
  
  document.querySelectorAll(".dot").forEach((dot, index) => {
    dot.classList.toggle("active", index === activeDot);
  });
}

// Global function for inline onclick
window.moveSlide = function(direction) {
  const slidesToShow = getSlidesToShow();
  
  if (direction > 0) {
    // Đi tiếp
    if (window.innerWidth >= 1024) {
      // Desktop: kiểm tra xem còn đủ 2 cards không
      const remaining = totalSlides - currentSlide;
      if (remaining > slidesToShow) {
        // Còn nhiều hơn 2 cards → scroll 2
        currentSlide += 2;
      } else {
        // Đã đến gần cuối → quay về đầu
        currentSlide = 0;
      }
    } else {
      // Mobile: scroll 1
      currentSlide += 1;
      if (currentSlide >= totalSlides) {
        currentSlide = 0;
      }
    }
  } else {
    // Đi lui
    if (window.innerWidth >= 1024) {
      // Desktop
      if (currentSlide === 0) {
        // Từ đầu → nhảy đến nhóm cuối
        // Nếu lẻ thì đến card lẻ, nếu chẵn thì đến nhóm 2 cards cuối
        const remainder = totalSlides % 2;
        if (remainder === 1) {
          currentSlide = totalSlides - 1; // Card lẻ cuối
        } else {
          currentSlide = totalSlides - 2; // Nhóm 2 cards cuối
        }
      } else {
        // Scroll lui 2 cards
        currentSlide -= 2;
        if (currentSlide < 0) currentSlide = 0;
      }
    } else {
      // Mobile: scroll lui 1
      currentSlide -= 1;
      if (currentSlide < 0) {
        currentSlide = totalSlides - 1;
      }
    }
  }
  
  updateSlider();
  
  // Reset auto slide
  startAutoSlide();
}

function goToSlide(dotIndex) {
  if (window.innerWidth >= 1024) {
    // Desktop
    const remainder = totalSlides % 2;
    const totalDots = Math.ceil(totalSlides / 2);
    
    // Nếu click vào dot cuối cùng và có card lẻ
    if (dotIndex === totalDots - 1 && remainder === 1) {
      currentSlide = totalSlides - 1; // Đến card lẻ
    } else {
      currentSlide = dotIndex * 2; // Đến nhóm 2 cards
    }
  } else {
    // Mobile: mỗi dot = 1 card
    currentSlide = dotIndex;
  }
  
  updateSlider();
  
  // Reset auto slide
  startAutoSlide();
}

// Start auto slide
function startAutoSlide() {
  clearInterval(autoSlideInterval);
  autoSlideInterval = setInterval(() => {
    window.moveSlide(1);
  }, 5000);
}

// Handle window resize
let resizeTimeout;
window.addEventListener('resize', () => {
  clearTimeout(resizeTimeout);
  resizeTimeout = setTimeout(() => {
    // Reset về đầu khi resize để tránh lỗi
    currentSlide = 0;
    createDots();
    updateSlider();
  }, 250);
});

// Initialize
createDots();
updateSlider();
startAutoSlide();

// Pause auto-slide on hover (desktop only)
const sliderWrapper = document.querySelector('.slider-wrapper');
if (sliderWrapper) {
  sliderWrapper.addEventListener('mouseenter', () => {
    clearInterval(autoSlideInterval);
  });
  
  sliderWrapper.addEventListener('mouseleave', () => {
    startAutoSlide();
  });
}

// ======================================== fetch rest api =================================================

// ==================== render provider==============================

document.addEventListener("DOMContentLoaded", async () => {
  // const baseURL = window.location.origin;
  const API_BASE = "http://localhost/PF_HTMLCF/wordpress/wp-json/wp/v2";
  const proxy_best = document.querySelector(".proxy-list");

  try {
    const providers = await fetch(`${API_BASE}/providers?per_page=100`);
    const pvd = await providers.json();

    // Sắp xếp theo rating giảm dần
    pvd.sort((a, b) => {
      const ratingA = Number(a?.provider_data?.rating) || 0;
      const ratingB = Number(b?.provider_data?.rating) || 0;
      return ratingB - ratingA;
    });

    pvd.forEach((p, index) => {
      const pd = p.provider_data || {};

      // Lấy data từ provider_data
      const logo = pd.logo || "https://via.placeholder.com/150";
      const title = p.title?.rendered || "No title";
      const tags = pd.tags || [];
      const summary = pd.summary || "No description available";
      const rating = Number(pd.rating) || 0;
      const price = Number(pd.price) || 0;
      const advanced = pd.advanced || [];

      // Tạo HTML cho tags
      const tagsHTML = tags
        .map(
          (tag) => `
          <span class="badge badge-best">
            <i class="fa-solid fa-medal"></i> ${tag}
          </span>
        `
        )
        .join("");

      // Tạo HTML cho advanced features
      const advancedHTML = advanced
        .map(
          (feature) => `
          <div class="feature-item">
            <i class="fa-solid fa-circle-check"></i>
            ${feature}
          </div>
        `
        )
        .join("");

      const fullStars = Math.floor(rating);
      const starsHTML = "★".repeat(fullStars) + "☆".repeat(5 - fullStars);

      // const url =`${WP_HOME}/oxylabs/?provider_id=${p.id}`;
      const url = `${WP_HOME}/reviews/${encodeURIComponent(p.slug)}`;

      // Tạo HTML cho provider card
      const bannerHTML = `
        <div class="proxy-item">
          <div class="proxy-left">
            <div class="proxy-rank-box">
              <div class="proxy-rank-border">
                <div class="proxy-rank">#${index + 1}</div>
              </div>
              <i class="proxy-rank-box-icon icon-start fa-regular fa-star"></i>
            </div>
            <div class="proxy-logo">
              <img src="${logo}" alt="${title}">
            </div>
            <div class="proxy-details">
              <h3>${title}</h3>
              <div class="proxy-badges">
                ${tagsHTML}
              </div>
              <p class="proxy-desc">${summary}</p>
            </div>
          </div>

          <div class="proxy-middle">
            <div class="proxy-rating">
              <span class="stars">${starsHTML}</span>
              <span class="rating-score">${rating.toFixed(1)}</span>
            </div>
            <div class="rating-text">Based on reviews</div>
            <div class="proxy-features">
              ${advancedHTML}
            </div>
          </div>

          <div class="proxy-right">
            <div class="proxy-price-box">
              <div class="price-label">STARTING FROM</div>
              <div class="price-value">${price}/GB</div>
              <div class="price-period">per month</div>
            </div>
            <a href="${url}">
            <button class="btn-visit">
              Visit Provider
              <i class="fa-solid fa-arrow-up-right-from-square"></i>
            </button>
            </a>
            <div class="verified-badge">
              <i class="fa-solid fa-circle-check"></i>
              Verified Provider
            </div>
          </div>
        </div>
      `;

      proxy_best.insertAdjacentHTML("beforeend", bannerHTML);
    });
  } catch (error) {
    console.error("Lỗi khi fetch providers:", error);
    proxy_best.innerHTML = `<p style="color: red;">Không thể tải dữ liệu providers. Vui lòng kiểm tra API.</p>`;
  }
});

// ========================== render post ==========================

document.addEventListener("DOMContentLoaded",async()=>{

  const API_BASE = "http://localhost/PF_HTMLCF/wordpress/wp-json/wp/v2";
  const post_grid = document.querySelector(".guides-grid");

  post_grid.innerHTML = "";

  
  try{
    const res = await fetch(`${API_BASE}/cpt_posts`);
    const post = await res.json();

    post1 = post.sort(() => Math.random() - 0.5).slice(0, 6);

    let out = "";

    post1.forEach((p)=>{
      const data = p.meta;
      const tags = data?._post_tag;
      const author = data?.post_date;
      const title = p.title?.rendered;
      const image = data?.post_image;
      const sdesc = data?.post_sdesc;
      const url1 = `${WP_HOME}/blog/${encodeURIComponent(p.slug)}`;

       const tagsHTML = tags
        .map(
          (tag) => `
          <span class="guide-tag">${tag}</span>
        `
        )
        .join("");

        
      
      out +=`
      <div class="guide-card">
          <img src="${image}" alt="Guide" class="guide-image">
          <div class="guide-content">
            <div class="guide-meta">
              ${tagsHTML}
              
              <span class="guide-time">${author}</span>
            </div>
            <h3>${title}</h3>
            <p>${sdesc}</p>
            <a href="${url1}" class="guide-link">
              Read guide
              <i class="fa-solid fa-arrow-right"></i>
            </a>
          </div>
        </div>
        
      `;
    });
  post_grid.insertAdjacentHTML('beforeend', out);


  }catch(error){
    console.error("Loi khi fetch post",error);
  }


});


//  ========================= fetch user review ==========================

document.addEventListener("DOMContentLoaded", async()=>{

  const BASE_API = `http://localhost/PF_HTMLCF/wordpress/wp-json/wp/v2/pages/6`;
  const sliderTrack = document.getElementById("sliderTrack");

  sliderTrack.innerHTML = "";
  let out= "";

  try{
    const res = await fetch(`${BASE_API}`);
    const data = await res.json(); 

    const usr = data.home_reviews_data.home_reviews.user_reviews;

    usr.forEach((u)=>{
      
      const rating = u.rating;     
      const comment = u.comment;
      const author = u.author_name;
      const role = u.author_role;
      const date = u.date;
      const stars = "★".repeat(parseInt(rating)) + "☆".repeat(5 - parseInt(rating));

      out +=`
            <div class="slide">
              <div class="testimonial-card">
                <div class="testimonial-stars">${stars}</div>
                <p class="testimonial-text">"${comment}"</p>
                <div class="testimonial-author">
                  <div class="author-avatar">${author.charAt(0)}</div>
                  <div class="author-info">
                    <h4>${author}</h4>
                    <p>${role}</p>
                  </div>
                </div>
              </div>
            </div>`
    })

    sliderTrack.innerHTML = out;

    // --- CẬP NHẬT SLIDES VÀ TỔNG SỐ ---
    slides = document.querySelectorAll(".slide");
    totalSlides = slides.length;

    // Reset currentSlide
    currentSlide = 0;

    // Tạo dot dựa trên số slide thực tế
    createDots();
    updateSlider();
    
  }catch(err){
    console.log("Fetch data khong thanh cong: ",err);
  }

});


//  ======================= fetch user pw ==============================

document.addEventListener("DOMContentLoaded", async()=>{

  const API_BASE = `http://localhost/PF_HTMLCF/wordpress/wp-json/wp/v2`;
  const rander = document.querySelector(".user_grid");
  let add = "";

  try{

    const res = await fetch(`${API_BASE}/cpt_posts/6`);
    const data = await res.json();

    const usr = data.home_reviews_data.home_reviews.user_reviews;

    usr.forEach((p)=>{

      const rating = p.rating;
      const comment = p.comment;
      const author = p.author_name;
      const role = p.author_role;
      const date = p.date;
      const stars = "★".repeat(parseInt(rating)) + "☆".repeat(5 - parseInt(rating));

      add+=`
      <div class="slide">
              <div class="testimonial-card">
                <div class="testimonial-stars">${stars}</div>
                <p class="testimonial-text">"${comment}"</p>
                <div class="testimonial-author">
                  <div class="author-avatar">${author.charAt(0)}</div>
                  <div class="author-info">
                    <h4>${author}</h4>
                    <p>${role}</p>
                  </div>
                </div>
              </div>
            </div>
      `
    });

    render.innerHTML= add;
  }catch(err){
    console.error("loi khi fetch data: ",err)
  }

})


document.addEventListener("DOMContentLoaded",async()=>{
  const API_BASE = "http://localhost/PF_HTMLCF/wordpress/wp-json/wp/v2";
  const render = document.querySelector("");

  try{
    const res = await fetch(`${API_BASE}/pages/6`);
    const data = await res.json();

    const data1 = data.sort(()=> Math.random()- 0.5).slice(0,6);

    data1.forEach((p)=>{
      
    })

  }catch(err){
    console.error("Loi khi fetch data: ", err);
  }



})