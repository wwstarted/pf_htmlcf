document.addEventListener("DOMContentLoaded",async()=>{
    const API_BASE="http://localhost/PF_HTMLCF/wordpress/wp-json/wp/v2";
    const item_per_page = 9;

    let currentPage = 1;
    let currentFilter= "all";
    let allProvider = [];

    // ====================== fetch provider category========================

    async function fetchCategories(){
        try{
            const res = await fetch(`${API_BASE}/provider_category`);
            const data = await res.json();

            allCategory = data;

            renderFillButton();

        } catch(error){
            console.error("error fetching categories:",error);
        }
    };


// ============================== fetch provider ============================
    async function fetchProviders(){
        try{
            const res = await fetch(`${API_BASE}/providers`);
            const data = await res.json();

            allProvider = data;

            renderProvider();

        }catch(error){
            console.error("error fetch provider:",error)
        }
    }

// =========================== render filler button ===========================
    function renderFilterButton(){
        const container = document.querySelector(".filter-button");
        container.innerHTML="";
        
        let out='<button class="filter-btn active" data-category="all">All</button>';

        allCategory.foreach((cat)=>{
            out +=`<button class="filter-btn" data-category=${cat.id}>${cat.name}</button>`;

        });

        container.innerHTML = out;

        container.querySelectorAll(".filter-btn").forEach((btn)=>{
            btn.addEventListener("click",function(){
                container.querySelectorAll(".filter-btn").foreach((b)=>b.classList.remove("active"));
                this.classList.add("active");

                currentFilter = this.dataset.category;
                currentPage = 1;
                renderProvider();
            });

        });
    }
    // ======================= filter provider =====================

    function getFilteredProviders(){
        let filtered = allProvider;

        if(currentFilter !== "all"){
            filtered = filtered.filter((provider)=>{
                const categoryIds = provider.provider_category || [];
                return categoryIds.includes(parseInt(currentFilter));
            });
        }

        if(searchQuery.trim() !==""){
            const query = searchQuery.toLowerCase().trim();
            filtered = filtered.filter((provider)=>{
                const title = provider.title?.rendered?.toLowerCase();
                const summary = provider.provider_data?.summary?.toLowerCase();

                return title.includes(query)|| summary.includes(query);
            })
        }

        return filtered;
    }

    // ================ sort provider =======================
    function getSortedProvider(){
        const sorted = [...providers];
        
        switch (currentSort){
            case "rating-desc":
            case "hight-desc":
                return sorted.sort((a,b)=>{
                    const ratingA = parseFloat(a.provider_data?.rating);
                    const ratingB = parseFloat(b.provider_data?.rating);

                    return ratingA - ratingB;
                });

            case "rating-price":
            case "lowest-price":
                return sorted.sort((a,b)=>{
                    const ratingA = parseFloat(a.provider_data?.price);
                    const ratingB = parseFloat(b.provider_data?.price);

                    return ratingA - ratingB;
                });
            case "rating-price":
            case "highest-price":
                return sorted.sort((a,b)=>{
                    const ratingA = parseFloat(a.provider_data?.price);
                    const ratingB = parseFloat(b.provider_data.price);

                    return ratingB -ratingA;
                });
            default:
                return sorted;
        }
        
    }

    // ======================== PAGINATION ===============================
    function getPaginatedProvider(providers){
        const start = (currentPage-1)*item_per_page;
        const end = start + item_per_page;

        return providers.slice(start,end);
    }

    function renderProvider(){
        const filtered = getFilteredProviders();
        const sorted = getSortedProvider(filtered);
        const paginated = getPaginatedProvider(sorted);

        const resultsCount = document.querySelector(".results-count");
        resultsCount.innerHTML= "";
        if(resultsCount){
            resultsCount.textContent = `Showing ${paginated.length} of ${sorted.length} providers`;
        }

        //  render providers card ======================
        const grid = document.querySelector(".providers-grid");
        grid.innerHTML="";

        let out = "";

        paginated.forEach((provider)=>{
            out += createProvidercard(provider);

        });

        grid.innerHTML = out;
         
        renderPagination(sorted.length);

        // ============================ create provider card ==========================
         function createProviderCard(provider){
            const pd = provider.provider_data();


            const title = provider.title?.rendered;
            const tags = pd.tags;
            const logo = pd.logo;

            // fill data du 

            const tag = length.tags > 0 ? tags[0] : "";
            const rating = pd.rating;
            const summary = pd.summary;
            const pm = pd.performance_metrics;

            const mRating = pm.find((m)=>{
                m.title?.toLowerCase.include("success");
            })

            const mRespon = pm.find((m)=>{
                m.title?.toLowerCase.include("response");
            })

            ratingv = mRating?.value;

            responsev = mRespon?.value;

            const advanced = pd?.advanced || [];

            const displayad = advanced.slice(0,3);
            const avcount = advanced.length -3;

            let featuresTag = "";

            displayad.forEach((d)=>{
                const featurestitle = d?.title || d;
                featuresTag += `<span class="feature-tag">${featureTitle}</span>`;
            })

            if(avcount>0){
                featuresTag += `<span class="feature-tag">+${avcount} more</span>`;
            }
             
         }

        

        

    }







});