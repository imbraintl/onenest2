<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Mummycare - Trusted Local Marketplace' ?></title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- CSS Files -->
    <link rel="stylesheet" href="/css/base.css">
    <link rel="stylesheet" href="/css/layout.css">
    <link rel="stylesheet" href="/css/components.css">
    
    <?= $additionalCSS ?? '' ?>
</head>
<body>
    <!-- Top Bar -->
    <div class="top-bar">
        <div class="container">
            <div class="top-bar-content">
                <div class="top-bar-left">
                    <div class="top-bar-contact">
                        <i data-lucide="phone"></i>
                        <span>+267 123 4567</span>
                    </div>
                    <div class="top-bar-contact">
                        <i data-lucide="mail"></i>
                        <span>hello@mummycare.co.bw</span>
                    </div>
                </div>
                <div class="top-bar-right">
                    <div class="top-bar-social">
                        <a href="#"><i data-lucide="facebook"></i></a>
                        <a href="#"><i data-lucide="instagram"></i></a>
                        <a href="#"><i data-lucide="twitter"></i></a>
                    </div>
                    <div class="top-bar-announcement">
                        <?= $topBarMessage ?? 'Welcome to Mummycare!' ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Header -->
    <header class="header header-with-topbar">
        <div class="container">
            <nav class="navbar">
                <a href="/" class="logo">
                    <i data-lucide="heart"></i>
                    Mummycare
                </a>
                <ul class="nav-links">
                    <li><a href="/jobs">Hire Professional</a></li>
                    <li><a href="/marketplace">Shop Goods</a></li>
                    <li><a href="/businesses">Find Businesses</a></li>
                    <li><a href="/properties">Properties</a></li>
                    <li><a href="/how-it-works">How It Works</a></li>
                </ul>
                <div class="nav-actions">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="/dashboard/user" class="btn btn-secondary">Dashboard</a>
                        <a href="/logout" class="btn btn-primary">Logout</a>
                    <?php else: ?>
                        <a href="/login" class="btn btn-secondary">Login</a>
                        <a href="/register" class="btn btn-primary">Join Now</a>
                    <?php endif; ?>
                </div>
                <button class="mobile-menu-toggle" title="Toggle mobile menu">
                    <i data-lucide="menu"></i>
                </button>
            </nav>
        </div>
    </header>

    <main>
        <?= $content ?>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-col">
                    <a class="logo" href="/">
                        <i data-lucide="heart"></i> Mummycare
                    </a>
                    <p>Trusted Local Marketplace for Botswana</p>
                    <div class="social-links">
                        <a href="#"><i data-lucide="twitter"></i></a>
                        <a href="#"><i data-lucide="instagram"></i></a>
                        <a href="#"><i data-lucide="linkedin"></i></a>
                    </div>
                </div>
                <div class="footer-col">
                    <h4>Explore</h4>
                    <ul>
                        <li><a href="/jobs">Hire Professional</a></li>
                        <li><a href="/marketplace">Shop Goods</a></li>
                        <li><a href="/businesses">Find Businesses</a></li>
                        <li><a href="/properties">Properties</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4>About</h4>
                    <ul>
                        <li><a href="/about">About Us</a></li>
                        <li><a href="#">Careers</a></li>
                        <li><a href="#">Press</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4>Support</h4>
                    <ul>
                        <li><a href="/how-it-works">Help & FAQ</a></li>
                        <li><a href="/contact">Contact Us</a></li>
                        <li><a href="#">Terms of Service</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2024 Mummycare. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Lucide Icons Script -->
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Initialize Lucide Icons
            lucide.createIcons();

            const header = document.querySelector('.header');
            const mobileMenuToggle = document.querySelector('.mobile-menu-toggle');
            const navLinksContainer = document.querySelector('.nav-links');
            let lastScrollTop = 0;

            // Enhanced scroll behavior for sticky header
            function handleScroll() {
                const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
                
                if (scrollTop > 10) {
                    header.style.boxShadow = '0 4px 20px rgba(0,0,0,0.1)';
                    header.style.backgroundColor = 'rgba(255, 255, 255, 0.98)';
                } else {
                    header.style.boxShadow = '0 2px 10px rgba(0,0,0,0.05)';
                    header.style.backgroundColor = 'rgba(255, 255, 255, 0.95)';
                }
                
                if (scrollTop > lastScrollTop && scrollTop > 100) {
                    header.style.transform = 'translateY(-100%)';
                } else {
                    header.style.transform = 'translateY(0)';
                }
                
                lastScrollTop = scrollTop;
            }

            // Mobile menu toggle functionality
            function toggleMobileMenu() {
                navLinksContainer.classList.toggle('mobile-active');
                const isOpen = navLinksContainer.classList.contains('mobile-active');
                mobileMenuToggle.setAttribute('aria-expanded', isOpen);
            }

            if (mobileMenuToggle) {
                mobileMenuToggle.addEventListener('click', toggleMobileMenu);
            }

            navLinksContainer.querySelectorAll('a').forEach(link => {
                link.addEventListener('click', () => {
                    if (navLinksContainer.classList.contains('mobile-active')) {
                        navLinksContainer.classList.remove('mobile-active');
                        mobileMenuToggle.setAttribute('aria-expanded', 'false');
                    }
                });
            });

            window.addEventListener('scroll', handleScroll);
        });
    </script>
    
    <?= $additionalJS ?? '' ?>
</body>
</html>