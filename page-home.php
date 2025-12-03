<?php
/*
Template Name: Home Page
*/
?>
<!-- Gọi header đã tách (function hộ trợ từ WP) -->
<?php get_header(); ?>

<!-- HERO SECTION -->
<section class="hero">
    <div class="container-home">
        <div class="hero-content">
            <div class="hero-left">
                <div class="hero-tag">
                    <i class="icon-start fa-regular fa-star"></i>
                    175M+ Residential Proxies — Trusted by 4000+ Clients
                    <i class="icon-bolt fa-solid fa-bolt"></i>
                </div>
                <h1 class="text-xs">Your Trusted Guide to All Things Proxy</h1>
                <p>
                    Make the right proxy decisions with our <a href="#">honest provider reviews</a>,
                    data-driven research, and in-depth educational content on everything related to proxy servers.
                </p>
                <div class="hero-buttons">
                    <button class="btn-primary animate-glow-ring">
                        Get Started Free
                        <i class="fa-solid fa-arrow-right"></i>
                    </button>
                    <button class="btn-home btn-outline">View Proxy Reviews</button>
                </div>
                <div class="hero-stats">
                    <div class="hero-stat">
                        <i class="fa-solid fa-circle-check"></i>
                        99.9% Success Rate
                    </div>
                    <div class="hero-stat">
                        <i class="fa-solid fa-globe"></i>
                        195 Countries
                    </div>
                    <div class="hero-stat">
                        <i class="fa-solid fa-headset"></i>
                        24/7 Support
                    </div>
                </div>
            </div>

            <div class="hero-right">
                <div class="hero-card">
                    <img src="https://poxies122.vercel.app/images/design-mode/col_vps_nmve.webp" alt="Intel Xeon Proxy"
                        class="hero-card-img">

                    <div class="hero-badge live">
                        <i class="fa-solid fa-circle"></i> LIVE NETWORK<i class="fa-solid fa-ellipsis"></i>
                    </div>

                    <div class="hero-stats-bottom">
                        <div class="stat-box">
                            <span class="stat-label">UPTIME</span>
                            <span class="stat-value">99.9%</span>
                        </div>
                        <div class="stat-box">
                            <span class="stat-label">NODES</span>
                            <span class="stat-value">175M+</span>
                        </div>
                        <div class="stat-box">
                            <span class="stat-label">LATENCY</span>
                            <span class="stat-value">~120ms</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- BEST PROXY PROVIDERS -->
<section class="best-proxy">
    <div class="container-home">
        <div class="section-header">
            <span class="section-tag"><i class="fa-solid fa-chart-line"></i>Top Rated Providers</span>
            <h2>Best Proxy Providers <span>2025</span></h2>
            <p>Compare the top-rated proxy providers based on performance, features, and customer reviews.</p>
        </div>

        <div class="proxy-list">
            <!-- Provider 1 -->
            <!-- fetch data here -->
        </div>

        <div class="view-all">
            <button class="btn-view-all">
                View All Providers
                <i class="fa-solid fa-arrow-up-right-from-square"></i>
            </button>
        </div>
    </div>
</section>

<!-- DIRECTORY SECTION -->
<section class="directory">
    <div class="container-home">
        <div class="section-header">
            <span class="section-tag"><i class="fa-solid fa-globe"></i></i>Complete Directory</span>
            <h2>Proxy Services <span>Directory</span></h2>
            <p>Browse our comprehensive directory of proxy services organized by category and use case.</p>
        </div>

        <div class="directory-grid">
            <!-- Card 1 -->
            <div class="directory-card">
                <div class="directory-icon">
                    <i class="fa-solid fa-gift"></i>
                </div>
                <h3>Free Residential Proxies</h3>
                <p>Discover the best free residential proxy services for basic web scraping and browsing needs.</p>
                <ul class="directory-list">
                    <li>ProxyScrape</li>
                    <li>FreeProxyList</li>
                    <li>HideMy.name</li>
                </ul>
                <div class="directory-tags">
                    <span class="tag">Free</span>
                    <span class="tag">Residential</span>
                    <span class="tag">Basic</span>
                </div>
                <button class="btn-load">Load 45 Sites <i class="fa-solid fa-arrow-trend-up"></i></button>
            </div>

            <!-- Card 2 -->
            <div class="directory-card">
                <div class="directory-icon">
                    <i class="fa-solid fa-crown"></i>
                </div>
                <h3>Premium Residential Proxies</h3>
                <p>High-quality residential proxies with large IP pools and advanced targeting capabilities.</p>
                <ul class="directory-list">
                    <li>Oxylabs</li>
                    <li>Bright Data</li>
                    <li>Smartproxy</li>
                </ul>
                <div class="directory-tags">
                    <span class="tag">Premium</span>
                    <span class="tag">High-Speed</span>
                    <span class="tag">24/7 Support</span>
                </div>
                <button class="btn-load">Load 38 Sites <i class="fa-solid fa-arrow-trend-up"></i></button>
            </div>

            <!-- Card 3 -->
            <div class="directory-card">
                <div class="directory-icon">
                    <i class="fa-solid fa-database"></i>
                </div>
                <h3>Datacenter Proxies</h3>
                <p>Fast and affordable datacenter proxies perfect for high-volume scraping and automation.</p>
                <ul class="directory-list">
                    <li>MyPrivateProxy</li>
                    <li>HighProxies</li>
                    <li>Proxy6.net</li>
                </ul>
                <div class="directory-tags">
                    <span class="tag">Fast</span>
                    <span class="tag">Affordable</span>
                    <span class="tag">High Volume</span>
                </div>
                <button class="btn-load">Load 29 Sites <i class="fa-solid fa-arrow-trend-up"></i></button>
            </div>

            <!-- Card 4 -->
            <div class="directory-card">
                <div class="directory-icon">
                    <i class="fa-solid fa-network-wired"></i>
                </div>
                <h3>ISP Proxies</h3>
                <p>Static residential proxies combining datacenter speed with legitimacy of residential IPs.</p>
                <ul class="directory-list">
                    <li>Oxylabs ISP</li>
                    <li>Smartproxy ISP</li>
                    <li>Soax ISP</li>
                </ul>
                <div class="directory-tags">
                    <span class="tag">Static</span>
                    <span class="tag">Fast</span>
                    <span class="tag">Reliable</span>
                </div>
                <button class="btn-load">Load 18 Sites <i class="fa-solid fa-arrow-trend-up"></i></button>
            </div>

            <!-- Card 5 -->
            <div class="directory-card">
                <div class="directory-icon">
                    <i class="fa-solid fa-mobile-screen"></i>
                </div>
                <h3>Mobile Proxies</h3>
                <p>Mobile device IPs for app testing, automation, and mobile-specific browsing activities.</p>
                <ul class="directory-list">
                    <li>Bright Data Mobile</li>
                    <li>Soax Mobile</li>
                    <li>IPRoyal Mobile</li>
                </ul>
                <div class="directory-tags">
                    <span class="tag">Mobile</span>
                    <span class="tag">4G</span>
                    <span class="tag">Dynamic</span>
                </div>
                <button class="btn-load">Load 22 Sites <i class="fa-solid fa-arrow-trend-up"></i></button>
            </div>

            <!-- Card 6 -->
            <div class="directory-card">
                <div class="directory-icon">
                    <i class="fa-solid fa-piggy-bank"></i>
                </div>
                <h3>Budget-Friendly Proxies</h3>
                <p>Affordable proxy solutions that balance performance, uptime, and price.</p>
                <ul class="directory-list">
                    <li>Proxy-Cheap</li>
                    <li>Webshare</li>
                    <li>ProxyScrape</li>
                </ul>
                <div class="directory-tags">
                    <span class="tag">Budget</span>
                    <span class="tag">Stable</span>
                    <span class="tag">Shared</span>
                </div>
                <button class="btn-load">Load 15 Sites <i class="fa-solid fa-arrow-trend-up"></i></button>
            </div>
        </div>
    </div>
</section>

<!-- GUIDES SECTION -->
<section class="guides">
    <div class="container-home">
        <div class="section-header">
            <span class="section-tag"><i class="fa-solid fa-book"></i></i>Learning Resources</span>
            <h2>Look for More Information in <br>Our <span>Guides</span></h2>
            <p>Everything you need to know about proxies, VPNs, and web scraping from basics to advanced techniques.</p>
        </div>

        <div class="guides-grid">
            <!-- Guide 1 -->
            <div class="guide-card">
                <img src="https://images.unsplash.com/photo-1451187580459-43490279c0fa?w=800&q=80" alt="Guide"
                    class="guide-image">
                <div class="guide-content">
                    <div class="guide-meta">
                        <span class="guide-tag">Beginner</span>
                        <span class="guide-time">6 min read</span>
                    </div>
                    <h3>A Short History of Ticketing Proxies</h3>
                    <p>Ticketing proxies are used by ticket scalpers to buy tickets in bulk. They have always been a
                        vital tool in
                        the ticketing business.</p>
                    <a href="#" class="guide-link">
                        Read guide
                        <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>
            </div>

            <!-- Guide 2 -->
            <div class="guide-card">
                <img src="https://images.unsplash.com/photo-1677442136019-21780ecad995?w=800&q=80" alt="Guide"
                    class="guide-image">
                <div class="guide-content">
                    <div class="guide-meta">
                        <span class="guide-tag">Intermediate</span>
                        <span class="guide-time">10 min read</span>
                    </div>
                    <h3>What is an AI Data Parser?</h3>
                    <p>AI data parsers use LLMs to generate well-organized data from multiple sources. See how they
                        interpret and
                        scrape flows.</p>
                    <a href="#" class="guide-link">
                        Read guide
                        <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>
            </div>

            <!-- Guide 3 -->
            <div class="guide-card">
                <img src="https://images.unsplash.com/photo-1563986768609-322da13575f3?w=800&q=80" alt="Guide"
                    class="guide-image">
                <div class="guide-content">
                    <div class="guide-meta">
                        <span class="guide-tag">Beginner</span>
                        <span class="guide-time">6 min read</span>
                    </div>
                    <h3>What is a Residential VPN?</h3>
                    <p>Understand the difference between residential and datacenter VPN connections for more privacy.
                    </p>
                    <a href="#" class="guide-link">
                        Read guide
                        <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>
            </div>

            <!-- Guide 4 -->
            <div class="guide-card">
                <img src="https://images.unsplash.com/photo-1558494949-ef010cbdcc31?w=800&q=80" alt="Guide"
                    class="guide-image">
                <div class="guide-content">
                    <div class="guide-meta">
                        <span class="guide-tag">Advanced</span>
                        <span class="guide-time">12 min read</span>
                    </div>
                    <h3>What is an MCP Server?</h3>
                    <p>MCP servers give LLMs access to real tools and APIs to perform actions and fetch live data
                        directly from
                        the internet.</p>
                    <a href="#" class="guide-link">
                        Read guide
                        <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>
            </div>

            <!-- Guide 5 -->
            <div class="guide-card">
                <img src="https://images.unsplash.com/photo-1544197150-b99a580bb7a8?w=800&q=80" alt="Guide"
                    class="guide-image">
                <div class="guide-content">
                    <div class="guide-meta">
                        <span class="guide-tag">Intermediate</span>
                        <span class="guide-time">9 min read</span>
                    </div>
                    <h3>IPv6 Proxy Guide: What You Need to Know</h3>
                    <p>IPv6 proxies support the next generation of the Internet Protocol. See why you need them and how
                        to use
                        them.</p>
                    <a href="#" class="guide-link">
                        Read guide
                        <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>
            </div>

            <!-- Guide 6 -->
            <div class="guide-card">
                <img src="https://images.unsplash.com/photo-1550751827-4bd374c3f58b?w=800&q=80" alt="Guide"
                    class="guide-image">
                <div class="guide-content">
                    <div class="guide-meta">
                        <span class="guide-tag">Beginner</span>
                        <span class="guide-time">7 min read</span>
                    </div>
                    <h3>What is a UDP Proxy? A Simple Guide</h3>
                    <p>Learn what a UDP proxy is and how it differs from TCP, and when to use it for your proxy needs.
                    </p>
                    <a href="#" class="guide-link">
                        Read guide
                        <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="view-all">
            <a href="<?php echo home_url('/blog'); ?>">
                <button class="btn-view-all">
                    See All Guides
                    <i class="fa-solid fa-arrow-right"></i>
                </button>
            </a>
        </div>
    </div>
</section>

<!-- PARTNERS SECTION -->
<section class="partners">
    <div class="container-home">
        <div class="section-header">
            <span class="section-tag"><i class="fa-solid fa-comment"></i> Customer Reviews</span>
            <h2>What Our <span>Partners Say</span></h2>
            <p>Trusted by leading proxy providers worldwide</p>
        </div>

        <div class="testimonials-slider">
            <button class="slider-nav prev" onclick="moveSlide(-1)">
                <i class="fa-solid fa-chevron-left"></i>
            </button>

            <div class="slider-wrapper">
                <div class="slider-track" id="sliderTrack">
                    <!-- Mỗi slide chỉ chứa 1 card -->
                    <div class="slide">
                        <div class="testimonial-card">
                            <div class="testimonial-stars">★★★★★</div>
                            <p class="testimonial-text">"On behalf of Oxylabs 1..."</p>
                            <div class="testimonial-author">
                                <div class="author-avatar">O</div>
                                <div class="author-info">
                                    <h4>Oxylabs</h4>
                                    <p>Leading Proxy Provider</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="slide">
                        <div class="testimonial-card">
                            <div class="testimonial-stars">★★★★★</div>
                            <p class="testimonial-text">"We would like to thank Proxyway 2..."</p>
                            <div class="testimonial-author">
                                <div class="author-avatar">B</div>
                                <div class="author-info">
                                    <h4>Bright Data</h4>
                                    <p>Global Proxy Network</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="slide">
                        <div class="testimonial-card">
                            <div class="testimonial-stars">★★★★★</div>
                            <p class="testimonial-text">"We would like to thank Proxyway 3..."</p>
                            <div class="testimonial-author">
                                <div class="author-avatar">B</div>
                                <div class="author-info">
                                    <h4>Bright Data</h4>
                                    <p>Global Proxy Network</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="slide">
                        <div class="testimonial-card">
                            <div class="testimonial-stars">★★★★★</div>
                            <p class="testimonial-text">"We would like to thank Proxyway 4..."</p>
                            <div class="testimonial-author">
                                <div class="author-avatar">B</div>
                                <div class="author-info">
                                    <h4>Bright Data</h4>
                                    <p>Global Proxy Network</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="slide">
                        <div class="testimonial-card">
                            <div class="testimonial-stars">★★★★★</div>
                            <p class="testimonial-text">"We would like to thank Proxyway 5..."</p>
                            <div class="testimonial-author">
                                <div class="author-avatar">B</div>
                                <div class="author-info">
                                    <h4>Bright Data</h4>
                                    <p>Global Proxy Network</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="slide">
                        <div class="testimonial-card">
                            <div class="testimonial-stars">★★★★★</div>
                            <p class="testimonial-text">"We would like to thank Proxyway 6..."</p>
                            <div class="testimonial-author">
                                <div class="author-avatar">B</div>
                                <div class="author-info">
                                    <h4>Bright Data</h4>
                                    <p>Global Proxy Network</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="slide">
                        <div class="testimonial-card">
                            <div class="testimonial-stars">★★★★★</div>
                            <p class="testimonial-text">"We would like to thank Proxyway 7..."</p>
                            <div class="testimonial-author">
                                <div class="author-avatar">B</div>
                                <div class="author-info">
                                    <h4>Bright Data</h4>
                                    <p>Global Proxy Network</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="slide">
                        <div class="testimonial-card">
                            <div class="testimonial-stars">★★★★★</div>
                            <p class="testimonial-text">"We would like to thank Proxyway 8..."</p>
                            <div class="testimonial-author">
                                <div class="author-avatar">B</div>
                                <div class="author-info">
                                    <h4>Bright Data</h4>
                                    <p>Global Proxy Network</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="slide">
                        <div class="testimonial-card">
                            <div class="testimonial-stars">★★★★★</div>
                            <p class="testimonial-text">"We would like to thank Proxyway 9..."</p>
                            <div class="testimonial-author">
                                <div class="author-avatar">B</div>
                                <div class="author-info">
                                    <h4>Bright Data</h4>
                                    <p>Global Proxy Network</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <button class="slider-nav next" onclick="moveSlide(1)">
                <i class="fa-solid fa-chevron-right"></i>
            </button>

            <div class="slider-dots" id="sliderDots"></div>
        </div>
    </div>
</section>

<!-- Gọi footer đã tách (function hộ trợ từ WP) -->
<?php get_footer(); ?>