// Toggle Menu
document.addEventListener("DOMContentLoaded", () => {
  const menuToggle = document.querySelector(".menu-toggle");
  const headerCenter = document.querySelector(".header-center");

  if (menuToggle && headerCenter) {
    menuToggle.addEventListener("click", () => {
      headerCenter.classList.toggle("active");
    });
  }

  // Toggle TOC
  const tocBtn = document.querySelector(".toc-toggle");
  const tocContent = document.querySelector(".toc-content");

  if (tocBtn && tocContent) {
    tocBtn.addEventListener("click", () => {
      tocContent.classList.toggle("show");

      // Kiểm tra xem TOC đang hiển thị hay không
      const isVisible = tocContent.classList.contains("show");

      // Đổi nội dung nút
      tocBtn.innerHTML = isVisible
        ? '<i class="fa-solid fa-list"></i> Hide Table of Contents'
        : '<i class="fa-solid fa-list"></i> Show Table of Contents';
    });
  }
});
