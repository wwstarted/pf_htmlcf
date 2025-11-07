document.addEventListener("DOMContentLoaded", async()=>{
    const API_BASE = "http://localhost/PF_HTMLCF/wp-json/wp/v2";
    const btnContainer = document.getElementById("content-btn");
    const blogGrid = document.querySelector(".blog-grid");
    const pagination = document.querySelector("pagination");

    let allPost =[];
    let currentCategory = "all";
    let currentPage= 1;
    let postPerPage = 6;

    try {
        const cateRes = await fetch(`${API_BASE}/post_category?per_page=100`);
        const categories = await cateRes.json();

        btnContainer.innerHTML=`
            <button class="active btn-cate" data-id="all">all</button>
            ${categories
                .map(
                    (c)=> `<button class="btn-cate" data-id="${c.id}">${c.name}</button>`
                )
                .join("")
            }
        `;

        async function fetchPosts(category_id="all"){
            let url = `${API_BASE}/cpt_posts?_embed&per_page=100`;
            if(category_id !== "all") url+=`&post_category=${category_id}`;
            const res = await fetch(`${url}`);
            const data = await res.json();

            allPost = Array.isArray(data) ? data : [];
            currentPage = 1;
            renderPost();
            renderPagination();
        }

        //  render Post 
        async function renderPost(){
            const start = (currentPage -1)*postPerPage;
            const end = start+postPerPage;
            const post = allPost.slice(start,end);

            blogGrid.innerHTML = posts
            .map((p)=>{
                const meta = p.meta;
                const title = p.title.rendered;
                const date = meta.post_date;
                const author = meta.post_author;
                const img = meta.image;
                const tags = Array.isArray(meta._post_tag)
                ? meta._post_tag
                :(meta._post_tag || [])
                    .split(",")
                    .map((t)=>t.trim())
                    .filter(Boolean);

                const except = meta.post_sdesc || "";

                return `
                <div class="blog-card">
               <a href="#">
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
                    <span class="blog-author">By ${author}</span>
                    <a href="${url1}" class="read-more">Read more</a> 
                  </div>
                </div>
              </a>
            </div>
                `
            })
        }

        // render pagination 
        function renderPagination(){
            const totalPages = Math.ceil(allPost.length / postPerPage);
            pagination.innerHTML= "";

            const prevBtn = document.createElement("button");
            prevBtn.classList.add("prev");
            prevBtn.innerHTML = "<span>Previous</span>";
            prevBtn.disabled = currentPage ===1;
            prevBtn.addEventListener("click",()=>{
                if(currentPage>1){
                    currentPage --;
                    renderPost();
                    renderPagination();
                    window.scrollTo({top:0, behavior:"smooth"});
                }

            });
            pagination.appendChild(prevBtn);

            for(let i =0;i<totalPages;i++){
                const pageLink = document.createElement("a");
                pageLink.href = "#";
                pageLink.textContent = i;
                if(i === currentPage) pageLink.classList.add("active");
                pageLink.addEventListener("click",(e)=>{
                    e.preventDefault();
                    currentPage = i;
                    renderPost();
                    renderPagination();
                    window.scrollTo({top:0,behavior:"smooth"});
                })
                pagination.appendChild(pageLink);
            }

            const nextBtn = document.createElement(button);
            nextBtn.classList.add("next");
            nextBtn.innerHTML = "<span>Next</span>";
            nextBtn.disabled = currentPage ===totalPages;

            nextBtn.addEventListener("click",()=>{
                if(currentPage<totalPages){
                    currentPage++;
                    renderPost();
                    renderPagination();
                    window.scrollTo({top:0,behavior:"smooth"});
                }
            });

            pagination.appendChild(nextBtn);

        }

        await renderPost();

        btnContainer.addEventListener("click",(e)=>{
            if(!e.target.classList.contains("btn-cate")) return;
            btnContainer.querySelectorAll(".btn-cate").forEach((b)=>b.classList.remove("active"));
            e.target.classList.add("active");
            currentCategory = e.target.dataset.id;
            fetchPosts(currentCategory);
        });
    }catch(err){
        console.error("loi khi fetch data: ", err);
    }
})

document.addEventListener("DOMContentLoaded", async()=>{

    const API_BASE = "http://localhost/PF_HTMLCF/wordpress/wp-json/wp/v2";

    const blog_grids= document.querySelector();

    try{
        const res = await fetch(`${API_BASE}/cpt_posts`);
        const data = await res.json();

        data.forEach((p)=>{
            const meta = p.meta;
            const title = p.title.rendered;

            const author = meta.author;
            const date = meta.date;
            const sdesc = meta.sdesc;
            const image = meta.image;
            const tags = meta._post_tag;

            const tag = tag.length > 0 ? tags[0] : "";

            const render = document.querySelector("");

            
        })

    }catch(err){
        console.error("Khong the fetch data: ",err);
    }



})