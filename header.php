<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>ProxyFlow</title>
    <?php wp_head(); ?>
</head>

<body>
    <!-- Header -->
    <header>
        <div class="wrapper">
            <div class="header">
                <a href="http://localhost/PF_HTMLCF/wordpress/home/" class="header-left">
                    <div class="header-logo"></div>
                    <div class="header-name">ProxyFlow</div>
                </a>
                <div class="header-menu">
                    <nav>
                        <ul>
                            <li><a href="#">proxies</a></li>
                            <li><a href="#">solutions</a></li>
                            <li><a href="#">guides</a></li>
                            <li><a href="#">reviews</a></li>
                            <li><a href="http://localhost/PF_HTMLCF/wordpress/blog/">blog</a></li>
                            <li><a href="#">pricing</a></li>
                        </ul>
                    </nav>
                </div>
                <div class="header-right">
                    <a href="#" class="btn btn-sign-in">sign in</a>
                    <a href="#" class="btn btn-start-free">Start Free Trial</a>
                </div>

                <!-- Mobile Menu Icon -->
                <div class="icon-menu-mobile" onclick="toggleMobileMenu()">
                    <i class="menu-icon fa-solid fa-bars"></i>
                    <i class="close-icon fa-solid fa-xmark"></i>
                </div>
            </div>
        </div>

        <!-- Mobile Menu Sidebar -->
        <div class="mobile-menu" id="mobileMenu">
            <nav>
                <ul>
                    <li><a onclick="closeMobileMenu()" href="#">proxies</a></li>
                    <li><a onclick="closeMobileMenu()" href="#">solutions</a></li>
                    <li><a onclick="closeMobileMenu()" href="#">guides</a></li>
                    <li><a onclick="closeMobileMenu()" href="#">reviews</a></li>
                    <li><a onclick="closeMobileMenu()" href="http://localhost/PF_HTMLCF/wordpress/blog">blog</a></li>
                    <li><a onclick="closeMobileMenu()" href="#">pricing</a></li>
                </ul>
            </nav>
            <div class="mobile-menu-buttons">
                <a href="#" class="btn btn-sign-in">sign in</a>
                <a href="#" class="btn btn-start-free">Start Free Trial</a>
            </div>
        </div>
    </header>