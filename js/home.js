// Testimonials Slider - Scroll by groups
let currentSlide = 0;
const sliderTrack = document.getElementById("sliderTrack");
const slides = document.querySelectorAll(".slide");
const totalSlides = slides.length;
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

