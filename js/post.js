// Toggle Menu
document.addEventListener("DOMContentLoaded", () => {
  const menuToggle = document.querySelector(".menu-toggle");
  const headerCenter = document.querySelector(".header-center");

  if (menuToggle && headerCenter) {
    menuToggle.addEventListener("click", () => {
      headerCenter.classList.toggle("active");
    });
  }

  // Toggle TOC mobile
  const tocBtn = document.querySelector(".toc-toggle");
  const tocContent = document.querySelector(".toc-content");

  if (tocBtn && tocContent) {
    tocBtn.addEventListener("click", () => {
      tocContent.classList.toggle("show");
      const isVisible = tocContent.classList.contains("show");
      tocBtn.innerHTML = isVisible
        ? '<i class="fa-solid fa-list"></i> Hide Table of Contents'
        : '<i class="fa-solid fa-list"></i> Show Table of Contents';
    });
  }
});

// =============================================== //
// PHẦN FETCH POST ID + NỘI DUNG BÀI VIẾT
// =============================================== //
document.addEventListener("DOMContentLoaded", async () => {
  const API_BASE = "http://localhost/PF_HTMLCF/wordpress/wp-json/wp/v2";
  let sharedPostId = null;

  async function getSharedPostId() {
    if (sharedPostId) return sharedPostId;
    const params = new URLSearchParams(window.location.search);
    let postId = params.get("post_id");
    sharedPostId = postId;
    console.log("Post ID dùng chung:", sharedPostId);
    return sharedPostId;
  }

  const postId = await getSharedPostId();

  /**====================== Fetch header ===============*/
  const single_header = document.querySelector(".single-header");

  try {
    const post = await fetch(`${API_BASE}/cpt_posts/${postId}`);
    const p = await post.json();
    const meta = p.meta || {};
    const title = p.title?.rendered || "No title";
    const date = meta.post_date || p.date || "";
    const tags = Array.isArray(meta._post_tag)
      ? meta._post_tag
      : (meta._post_tag || "")
          .split(",")
          .map((t) => t.trim())
          .filter(Boolean);
    const author = meta.post_author || "Admin";

    const adsHTML = `
      ${tags.length > 0
        ? `<div class="tag-list">${tags
            .map((tag) => `<div class="article-tag">${tag}</div>`)
            .join("")}</div>`
        : ""}
      <h1 class="article-title">${title}</h1>
      <div class="meta">
        <span class="author"><i class="fa-regular fa-user"></i> ${author}</span>
        <span class="date"><i class="fa-regular fa-calendar"></i> ${date}</span>
        <span class="read-time"><i class="fa-regular fa-clock"></i> 8 min read</span>
      </div>
    `;
    single_header.insertAdjacentHTML("beforeend", adsHTML);
  } catch (error) {
    console.error("Lỗi khi fetch header:", error);
  }

});


document.addEventListener("DOMContentLoaded", async () => { 
    const API_BASE = "http://localhost/PF_HTMLCF/wordpress/wp-json/wp/v2";
  let sharedPostId = null;

  async function getSharedPostId() {
    if (sharedPostId) return sharedPostId;
    const params = new URLSearchParams(window.location.search);
    let postId = params.get("post_id");
    sharedPostId = postId;
    console.log("Post ID dùng chung:", sharedPostId);
    return sharedPostId;
  }

  const postId = await getSharedPostId();

    /**==================== Fetch nội dung bài viết =====================*/
  const singlepost = document.querySelector(".article-body");
  try {
    const post = await fetch(`${API_BASE}/cpt_posts/${postId}`);
    const p = await post.json();
    const meta = p.meta;
    const desc = meta._post_desc || "<p>Không có nội dung.</p>";
    singlepost.innerHTML = desc;
    
  } catch (error) {
    console.error("Lỗi khi fetch nội dung:", error);
  }

    /**==================== Generate TOC (từ desc đã render) =====================*/
  function generateTOC() {
    const tocListDesktop = document.querySelector(".toc ul");
    const tocListMobile = document.querySelector(".toc-mobile .toc-content ul");
    const article = document.querySelector(".article-body");

    if (!article || !tocListDesktop || !tocListMobile) return;

    const headings = article.querySelectorAll("h2, h3");

    if (headings.length === 0) {
      tocListDesktop.innerHTML = "<li>No headings found</li>";
      tocListMobile.innerHTML = "<li>No headings found</li>";
      return;
    }

    // Clear cũ
    tocListDesktop.innerHTML = "";
    tocListMobile.innerHTML = "";

    headings.forEach((heading, index) => {
      const level = heading.tagName.toLowerCase();
      const text = heading.textContent.trim();

      const id =
        heading.id ||
        text.toLowerCase().replace(/\s+/g, "-").replace(/[^\w\-]+/g, "") +
          "-" +
          index;
      heading.id = id;

      const li = document.createElement("li");
      li.className = level === "h3" ? "toc-sub" : "";
      li.innerHTML = `<a href="#${id}">${text}</a>`;

      // render vào cả hai toc
      tocListDesktop.appendChild(li.cloneNode(true));
      tocListMobile.appendChild(li);
    });

    console.log("✅ TOC generated:", headings.length, "headings found.");
  }

    // Gọi sau khi nội dung đã render
  setTimeout(generateTOC, 100);



});

// ========================================== fetch related section ======================

document.addEventListener("DOMContentLoaded", async () => { 
    const API_BASE = "http://localhost/PF_HTMLCF/wordpress/wp-json/wp/v2";
  let sharedPostId = null;

  async function getSharedPostId() {
    if (sharedPostId) return sharedPostId;
    const params = new URLSearchParams(window.location.search);
    let postId = params.get("post_id");
    sharedPostId = postId;
    console.log("Post ID dùng chung:", sharedPostId);
    return sharedPostId;
  }

  const postId = await getSharedPostId();

    /* ================fetch related================== */
  const related = document.querySelector(".articles-grid");
  try {

    function truncateWords(text, limit) {
      const words = text.split(" ");
      if (words.length > limit) {
        return words.slice(0, limit).join(" ") + "...";
      }
      return text;
    }

    const post = await fetch(`${API_BASE}/cpt_posts?per_page=100`);
    const relate = await post.json();

    const filteredPosts = relate.filter(post => post.id !== postId);

    function getRandomPosts(posts, count = 3) {
      const shuffled = posts.sort(() => 0.5 - Math.random());
      return shuffled.slice(0, count);
    }

    const relatedPosts = getRandomPosts(filteredPosts, 3);

    relatedPosts.forEach((p) => {
        const meta = p.meta || {};
        const title = p.title?.rendered || "No title";
        const date = meta.post_date || p.date || "";
        const tags = Array.isArray(meta._post_tag)
          ? meta._post_tag
          : (meta._post_tag || "")
              .split(",")
              .map((t) => t.trim())
              .filter(Boolean);
        const author = meta.post_author || "Admin";
        const image = meta.post_image;
        const sdesc = meta.post_sdesc;
        const shortDesc = truncateWords(sdesc, 25);

        const bannerHTML = `
        <a href="http://localhost/PF_HTMLCF/wordpress/singleblog/?post_id=${p.id}">
        <div class="article-card">
          <div class="article-image">
            <img
              src="${image}"
              alt="${title}"
            />
            ${tags.length > 0
                ? `<div class="tag-list">${tags
                    .map((tag) => `<span class="tag">${tag}</span>`)
                    .join("")}</div>`
                : ""}
          </div>
          <div class="article-content">
            <h3>${title}</h3>
            <p>
              ${shortDesc}
            </p>
            <span class="date"
              ><i class="fa-regular fa-calendar"></i> ${date}</span
            >
            <span class="read-time"
              ><i class="fa-regular fa-clock"></i> 8 min read</span
            >
          </div>
        </div>
        </a>
        `;
        related.insertAdjacentHTML("beforeend", bannerHTML);
      });
  } catch (error) {
    console.error("Lỗi khi fetch nội dung:", error);
  }


});

// ==================== fetch breakcrumb ==============================
document.addEventListener("DOMContentLoaded", async () => { 
  const API_BASE = "http://localhost/PF_HTMLCF/wordpress/wp-json/wp/v2";
  let sharedPostId = null;

  async function getSharedPostId() {
    if (sharedPostId) return sharedPostId;
    const params = new URLSearchParams(window.location.search);
    let postId = params.get("post_id");
    sharedPostId = postId;
    console.log("Post ID dùng chung:", sharedPostId);
    return sharedPostId;
  }

  const owId = await getSharedPostId();


  
  try {
    const res = await fetch(`${API_BASE}/cpt_posts/${owId}`);
    const p = await res.json();

    console.log("ppp:",p);

    const container = document.querySelector("#bd_post");
    
     container.innerHTML = "";

    let out = "";
     
      const title = p.title?.rendered || "";

      out += `
            <a href="http://localhost/PF_HTMLCF/wordpress/" style="color: black; text-decoration: none;" >Home</a> /
            <a href="http://localhost/PF_HTMLCF/wordpress/blog" style="color: black; text-decoration: none;">Blog</a> /
            <span style="color: blue"
              >${title}</span
            >
      `;
    

    // Insert vào container
    container.innerHTML = out;
    
    console.log("✅ User reviews loaded successfully");
    
  } catch (err) {
    console.error("❌ Error loading user reviews:", err);
  }




});



