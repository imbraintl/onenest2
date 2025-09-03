<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'OneNest - Trusted Local Marketplace' ?></title>
    
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
                        <?= $topBarMessage ?? 'Welcome to OneNest!' ?>
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
                    OneNest
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
                        <a class="btn btn-secondary" data-page="page-login">Login</a>
                        <a class="btn btn-primary" data-page="page-join">Join Now</a>
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

    <!-- Login Modal -->
    <div id="loginModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Welcome Back</h2>
                <span class="modal-close">&times;</span>
            </div>
            <div class="modal-body">
                <?php if (isset($_GET['error'])): ?>
                    <div style="background: #fee; color: #c33; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                        <?= htmlspecialchars($_GET['error']) ?>
                    </div>
                <?php endif; ?>
                
                <form method="POST" action="/login">
                    <div class="form-group">
                        <label for="modal-login-email">Email Address</label>
                        <input type="email" id="modal-login-email" name="email" class="form-input" required>
                    </div>
                    <div class="form-group">
                        <label for="modal-login-password">Password</label>
                        <input type="password" id="modal-login-password" name="password" class="form-input" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Login</button>
                </form>
                
                <div class="auth-link">
                    <a href="#" onclick="showForgotPassword()">Forgot Password?</a>
                </div>
                <div class="auth-link">
                    Don't have an account? <a href="#" onclick="switchToRegister()">Join Now</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Register Modal -->
    <div id="registerModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Join OneNest</h2>
                <span class="modal-close">&times;</span>
            </div>
            <div class="modal-body">
                <?php if (isset($_GET['errors'])): ?>
                    <div style="background: #fee; color: #c33; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                        <ul style="margin: 0; padding-left: 20px;">
                            <?php foreach (explode(',', $_GET['errors']) as $error): ?>
                                <li><?= htmlspecialchars($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
                
                <form method="POST" action="/register">
                    <div class="form-group">
                        <label for="modal-join-name">Full Name</label>
                        <input type="text" id="modal-join-name" name="name" class="form-input" required placeholder="Your full name">
                    </div>
                    <div class="form-group">
                        <label for="modal-join-email">Email Address</label>
                        <input type="email" id="modal-join-email" name="email" class="form-input" required placeholder="your@email.com">
                    </div>
                    <div class="form-group">
                        <label for="modal-join-phone">Phone Number (Optional)</label>
                        <input type="tel" id="modal-join-phone" name="phone" class="form-input" placeholder="e.g., 71234567">
                    </div>
                    <div class="form-group">
                        <label for="modal-join-password">Create Password</label>
                        <input type="password" id="modal-join-password" name="password" class="form-input" required>
                    </div>
                    <div class="form-group">
                        <label for="modal-join-confirm-password">Confirm Password</label>
                        <input type="password" id="modal-join-confirm-password" name="confirm_password" class="form-input" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Create Account</button>
                </form>
                
                <div class="auth-link">
                    Already have an account? <a href="#" onclick="switchToLogin()">Login</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-col">
                    <a class="logo" href="/">
                        <i data-lucide="heart"></i> OneNest
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
                <p>&copy; 2024 OneNest. All rights reserved.</p>
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
    
    <!-- Modal JavaScript -->
    <script>
        // Modal functionality
        const loginModal = document.getElementById('loginModal');
        const registerModal = document.getElementById('registerModal');
        
        // Show login modal
        function showLoginModal() {
            loginModal.style.display = 'block';
            document.body.style.overflow = 'hidden';
        }
        
        // Show register modal
        function showRegisterModal() {
            registerModal.style.display = 'block';
            document.body.style.overflow = 'hidden';
        }
        
        // Close modals
        function closeModals() {
            loginModal.style.display = 'none';
            registerModal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }
        
        // Switch between modals
        function switchToRegister() {
            closeModals();
            showRegisterModal();
        }
        
        function switchToLogin() {
            closeModals();
            showLoginModal();
        }
        
        // Add event listeners for login/register buttons
        document.addEventListener('DOMContentLoaded', () => {
            // Handle login buttons
            document.querySelectorAll('a[data-page="page-login"], .btn-secondary').forEach(btn => {
                if (btn.textContent.trim() === 'Login') {
                    btn.addEventListener('click', (e) => {
                        e.preventDefault();
                        showLoginModal();
                    });
                }
            });
            
            // Handle register buttons
            document.querySelectorAll('a[data-page="page-join"], .btn-primary').forEach(btn => {
                if (btn.textContent.trim() === 'Join Now' || btn.textContent.trim() === 'Get Started Today' || btn.textContent.trim() === 'Create Your Free Account') {
                    btn.addEventListener('click', (e) => {
                        e.preventDefault();
                        showRegisterModal();
                    });
                }
            });
            
            // Close modal when clicking X or outside
            document.querySelectorAll('.modal-close').forEach(closeBtn => {
                closeBtn.addEventListener('click', closeModals);
            });
            
            // Close modal when clicking outside
            window.addEventListener('click', (e) => {
                if (e.target.classList.contains('modal')) {
                    closeModals();
                }
            });
            
            // Handle escape key
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') {
                    closeModals();
                }
            });
        });
    </script>
    
    <?= $additionalJS ?? '' ?>
</body>
</html>