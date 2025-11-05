const mobileMenu = document.getElementById("mobileMenu");
const menuIcon = document.querySelector(".menu-icon");
const closeIcon = document.querySelector(".close-icon");

function toggleMobileMenu() {
  if (mobileMenu.classList.contains("active-nemu-mobile")) {
    closeMobileMenu();
  } else {
    openMobileMenu();
  }
}

function openMobileMenu() {
  mobileMenu.style.display = "block";
  setTimeout(() => mobileMenu.classList.add("active-nemu-mobile"), 10);
  menuIcon.style.display = "none";
  closeIcon.style.display = "inline-block";
}

function closeMobileMenu() {
  mobileMenu.classList.remove("active-nemu-mobile");
  setTimeout(() => {
    mobileMenu.style.display = "none";
  }, 300);
  menuIcon.style.display = "inline-block";
  closeIcon.style.display = "none";
}

// Ẩn icon close khi load trang
document.addEventListener("DOMContentLoaded", () => {
  closeIcon.style.display = "none";
});

// Khi resize sang PC thì tự đóng menu mobile
window.addEventListener("resize", () => {
  if (window.innerWidth > 1024) {
    // đổi theo breakpoint của bạn
    mobileMenu.classList.remove("active-nemu-mobile");
    mobileMenu.style.display = "none";
    menuIcon.style.display = "inline-block";
    closeIcon.style.display = "none";
  }
});

// ==========================fetch data ===============================


/** ----- BLOG CATEGORY & POSTS + PAGINATION ----- **/
document.addEventListener("DOMContentLoaded", async () => {
  const API_BASE = "http://localhost/PF_HTMLCF/wordpress/wp-json/wp/v2";
  const btnContainer = document.querySelector(".content-btn");
  const blogGrid = document.querySelector(".blog-grid");
  const pagination = document.querySelector(".pagination");

  let allPosts = [];
  let currentCategory = "all";
  let currentPage = 1;
  const postsPerPage = 6;

  try {
    const cateRes = await fetch(`${API_BASE}/post_category?per_page=100`);
    const categories = await cateRes.json();

    btnContainer.innerHTML = `
      <button class="active btn-cate" data-id="all">All</button>
      ${categories
        .map(
          (c) => `<button class="btn-cate" data-id="${c.id}">${c.name}</button>`
        )
        .join("")}
    `;

    /** --- fetch bài viết từ API --- **/
    async function fetchPosts(categoryId = "all") {
      let url = `${API_BASE}/cpt_posts?_embed&per_page=100`;
      if (categoryId !== "all") url += `&post_category=${categoryId}`;
      const res = await fetch(url);
      const data = await res.json();
      allPosts = Array.isArray(data) ? data : [];
      currentPage = 1;
      renderPosts();
      renderPagination();
    }

    /** --- Render danh sách bài viết --- **/
    function renderPosts() {
      const start = (currentPage - 1) * postsPerPage;
      const end = start + postsPerPage;
      const posts = allPosts.slice(start, end);

      blogGrid.innerHTML = posts
        .map((p) => {
          const meta = p.meta || {};
          const title = p.title?.rendered || "No title";
          const date = meta.post_date || p.date || "";
          const img = meta.post_image || "";
          const tags = Array.isArray(meta._post_tag)
            ? meta._post_tag
            : (meta._post_tag || "")
                .split(",")
                .map((t) => t.trim())
                .filter(Boolean);
          const excerpt = meta.post_sdesc || "";
          const url1 = `${WP_HOME}/blog/${encodeURIComponent(p.slug)}`;
          return `
            <div class="blog-card">
               <a href="${url1}">
                <div class="blog-image">
                  <img src="${img}" alt="${title}" />
                  ${
                    tags.length > 0
                      ? `<div class="tag-list">${tags
                          .map((tag) => `<div class="tag-blog">${tag}</div>`)
                          .join("")}</div>`
                      : ""
                  }
                </div>
                <div class="blog-content">
                  <div class="blog-meta">
                    <span class="date"><i class="icon fa-regular fa-calendar"></i>${date}</span>
                    <span class="read-time"><i class="icon fa-regular fa-clock"></i>8 min read</span>
                  </div>
                  <h3 class="text-line-clamp blog-title">${title}</h3>
                  <p class="text-line-clamp blog-description">${excerpt}</p>
                  <div class="blog-footer">
                    <span class="blog-author">By ${meta.post_author || "Admin"}</span>
                    <a href="${url1}" class="read-more">Read more</a> 
                  </div>
                </div>
              </a>
            </div>
          `;
        })
        .join("");
    }

    /** --- Render pagination --- **/
    function renderPagination() {
      const totalPages = Math.ceil(allPosts.length / postsPerPage);
      pagination.innerHTML = "";

      const prevBtn = document.createElement("button");
      prevBtn.classList.add("prev");
      prevBtn.innerHTML = "<span>Previous</span>";
      prevBtn.disabled = currentPage === 1;
      prevBtn.addEventListener("click", () => {
        if (currentPage > 1) {
          currentPage--;
          renderPosts();
          renderPagination();
          window.scrollTo({ top: 0, behavior: "smooth" });
        }
      });
      pagination.appendChild(prevBtn);

      for (let i = 1; i <= totalPages; i++) {
        const pageLink = document.createElement("a");
        pageLink.href = "#";
        pageLink.textContent = i;
        if (i === currentPage) pageLink.classList.add("active");
        pageLink.addEventListener("click", (e) => {
          e.preventDefault();
          currentPage = i;
          renderPosts();
          renderPagination();
          window.scrollTo({ top: 0, behavior: "smooth" });
        });
        pagination.appendChild(pageLink);
      }

      const nextBtn = document.createElement("button");
      nextBtn.classList.add("next");
      nextBtn.innerHTML = "<span>Next</span>";
      nextBtn.disabled = currentPage === totalPages;
      nextBtn.addEventListener("click", () => {
        if (currentPage < totalPages) {
          currentPage++;
          renderPosts();
          renderPagination();
          window.scrollTo({ top: 0, behavior: "smooth" });
        }
      });
      pagination.appendChild(nextBtn);
    }

    /** --- Load lần đầu (tất cả bài viết) --- **/
    await fetchPosts();

    /** --- Khi click vào category --- **/
    btnContainer.addEventListener("click", (e) => {
      if (!e.target.classList.contains("btn-cate")) return;
      btnContainer.querySelectorAll(".btn-cate").forEach((b) => b.classList.remove("active"));
      e.target.classList.add("active");
      currentCategory = e.target.dataset.id;
      fetchPosts(currentCategory);
    });
  } catch (err) {
    console.error(" Lỗi khi load dữ liệu:", err);
  }
});



