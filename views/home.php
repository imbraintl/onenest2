<?php
$title = 'Mummycare - Trusted Local Marketplace';
$topBarMessage = 'Join 10,000+ verified members!';

ob_start();
?>

<main id="page-container">
    <!-- ======================= -->
    <!--      HOME PAGE          -->
    <!-- ======================= -->
    <section id="page-home" class="page active">
        <!-- Hero Section -->
        <div class="hero" style="padding-top: 80px;">
            <div class="container">
                <div class="hero-content">
                    <h1 class="hero-title">The Trusted Local Marketplace</h1>
                    <p class="hero-subtitle">Connecting verified professionals, goods, and businesses across Botswana. Safe, secure, and simple.</p>
                    <div class="hero-cta">
                        <a class="btn btn-accent btn-large" data-page="page-join">Get Started Today</a>
                        <a class="learn-more-link" href="/how-it-works">
                            Learn More <i data-lucide="arrow-right" class="icon-small"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="hero-bg"></div>
        </div>

        <!-- Trust Bar Section -->
        <div class="trust-bar" style="background-color: var(--white); padding: 80px 0;">
            <div class="container">
                <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 40px; text-align: center;">
                    <div class="trust-item">
                        <i data-lucide="shield-check"></i>
                        <h3>ID-Verified Members</h3>
                        <p>Every member is verified using Omang or a valid ID for your safety.</p>
                    </div>
                    <div class="trust-item">
                        <i data-lucide="check-square"></i>
                        <h3>Admin-Curated Listings</h3>
                        <p>Our team reviews listings to ensure quality and authenticity.</p>
                    </div>
                    <div class="trust-item">
                        <i data-lucide="users"></i>
                        <h3>Secure Community</h3>
                        <p>Engage and transact with confidence in a trusted environment.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pillars Section -->
        <div class="pillars" style="padding: 80px 0; background-color: var(--light-gray);">
            <div class="container">
                <h2 class="section-title">One Platform, Endless Possibilities</h2>
                <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 30px;">
                    <div class="pillar-card">
                        <div class="pillar-icon-wrapper">
                            <i data-lucide="briefcase"></i>
                        </div>
                        <h3>Hire a Professional</h3>
                        <p>Find vetted and reliable experts for any task, from home repairs in Gaborone to safari guides in Maun.</p>
                        <a class="btn btn-primary" href="/jobs">Find a Pro</a>
                    </div>
                    <div class="pillar-card">
                        <div class="pillar-icon-wrapper">
                            <i data-lucide="shopping-basket"></i>
                        </div>
                        <h3>Shop Curated Goods</h3>
                        <p>Buy and sell quality, second-hand items within a community you can trust.</p>
                        <a class="btn btn-primary" href="/marketplace">Browse Goods</a>
                    </div>
                    <div class="pillar-card">
                        <div class="pillar-icon-wrapper">
                            <i data-lucide="building-2"></i>
                        </div>
                        <h3>Discover Businesses</h3>
                        <p>Explore a directory of verified local businesses, from law firms to restaurants in Francistown.</p>
                        <a class="btn btn-primary" href="/businesses">Explore Directory</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Featured Content -->
        <?php if (!empty($featuredBusinesses) || !empty($featuredItems)): ?>
        <section style="padding: 80px 0;">
            <div class="container">
                <h2 class="section-title">Featured This Week</h2>
                
                <?php if (!empty($featuredBusinesses)): ?>
                <h3 style="margin-bottom: 30px;">Trusted Businesses</h3>
                <div class="results-grid" style="margin-bottom: 60px;">
                    <?php foreach (array_slice($featuredBusinesses, 0, 3) as $business): ?>
                    <div class="card">
                        <div class="card-content">
                            <span class="card-tag" style="background-color: var(--secondary-color);">
                                <?= ucfirst(str_replace('_', ' ', $business['type'])) ?>
                            </span>
                            <h4 class="card-title"><?= htmlspecialchars($business['name']) ?></h4>
                            <p class="card-subtitle"><?= htmlspecialchars($business['location_text'] ?? 'Botswana') ?></p>
                            <div class="card-footer">
                                <div class="rating">
                                    <i data-lucide="star" class="star-filled"></i>
                                    <span>Verified Business</span>
                                </div>
                                <a class="btn btn-secondary" href="/businesses/<?= $business['id'] ?>">View Details</a>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <?php if (!empty($featuredItems)): ?>
                <h3 style="margin-bottom: 30px;">Marketplace Finds</h3>
                <div class="results-grid">
                    <?php foreach (array_slice($featuredItems, 0, 3) as $item): ?>
                    <div class="card">
                        <div class="card-content">
                            <span class="card-tag" style="background-color: #E0F2F1;">
                                <?= htmlspecialchars($item['category_name'] ?? 'General') ?>
                            </span>
                            <h4 class="card-title"><?= htmlspecialchars($item['name']) ?></h4>
                            <p class="card-subtitle">by <?= htmlspecialchars($item['seller_name']) ?></p>
                            <div class="card-footer">
                                <span class="price">P <?= number_format($item['price_bwp']) ?></span>
                                <a class="btn btn-secondary" href="/marketplace/<?= $item['id'] ?>">View Item</a>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
        </section>
        <?php endif; ?>

        <!-- Final CTA -->
        <div class="final-cta" style="background-color: var(--dark-color); color: var(--white); padding: 80px 0; text-align: center;">
            <div class="container">
                <h2>Ready to Join a Trusted Motswana Community?</h2>
                <p>Become a member today and start connecting with confidence.</p>
                <a class="btn btn-accent btn-large" data-page="page-join">Create Your Free Account</a>
            </div>
        </div>
    </section>

    <!-- ======================= -->
    <!--        LOGIN PAGE         -->
    <!-- ======================= -->
    <section id="page-login" class="page">
        <div class="auth-container">
            <div class="auth-card">
                <h1>Welcome Back</h1>
                
                <?php if (isset($_GET['error'])): ?>
                    <div style="background: #fee; color: #c33; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                        <?= htmlspecialchars($_GET['error']) ?>
                    </div>
                <?php endif; ?>
                
                <form method="POST" action="/login">
                    <div class="form-group">
                        <label for="login-email">Email Address</label>
                        <input type="email" id="login-email" name="email" class="form-input" required>
                    </div>
                    <div class="form-group">
                        <label for="login-password">Password</label>
                        <input type="password" id="login-password" name="password" class="form-input" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Login</button>
                </form>
                <div class="auth-link">
                    <a href="#" onclick="showPage('page-home')">Forgot Password?</a>
                </div>
                <div class="auth-link">
                    Don't have an account? <a href="#" data-page="page-join">Join Now</a>
                </div>
            </div>
        </div>
    </section>
    
    <!-- ======================= -->
    <!--       JOIN NOW PAGE       -->
    <!-- ======================= -->
    <section id="page-join" class="page">
        <div class="auth-container">
            <div class="auth-card">
                <h1>Join Mummycare</h1>
                
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
                        <label for="join-name">Full Name</label>
                        <input type="text" id="join-name" name="name" class="form-input" required placeholder="Your full name">
                    </div>
                    <div class="form-group">
                        <label for="join-email">Email Address</label>
                        <input type="email" id="join-email" name="email" class="form-input" required placeholder="your@email.com">
                    </div>
                    <div class="form-group">
                        <label for="join-phone">Phone Number (Optional)</label>
                        <input type="tel" id="join-phone" name="phone" class="form-input" placeholder="e.g., 71234567">
                    </div>
                    <div class="form-group">
                        <label for="join-password">Create Password</label>
                        <input type="password" id="join-password" name="password" class="form-input" required>
                    </div>
                    <div class="form-group">
                        <label for="join-confirm-password">Confirm Password</label>
                        <input type="password" id="join-confirm-password" name="confirm_password" class="form-input" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Create Account</button>
                </form>
                <div class="auth-link">
                    Already have an account? <a href="#" data-page="page-login">Login</a>
                </div>
            </div>
        </div>
    </section>
</main>

<?php
$additionalJS = '
<script>
document.addEventListener("DOMContentLoaded", () => {
    const allPages = document.querySelectorAll(".page");
    const navLinks = document.querySelectorAll("a[data-page]");

    function showPage(pageId) {
        // Hide all pages
        allPages.forEach(page => {
            page.classList.remove("active");
        });

        // Show the target page
        const targetPage = document.getElementById(pageId);
        if (targetPage) {
            targetPage.classList.add("active");
            window.scrollTo(0, 0);
        } else {
            // Fallback to home page
            document.getElementById("page-home").classList.add("active");
        }
        updateActiveNav(pageId);
    }
    
    function updateActiveNav(activePageId) {
        const mainNavLinks = document.querySelectorAll(".nav-links a");
        mainNavLinks.forEach(link => {
            if (link.dataset.page === activePageId) {
                link.classList.add("active");
            } else {
                link.classList.remove("active");
            }
        });
    }

    // Add click listeners to all navigation links with data-page
    navLinks.forEach(link => {
        link.addEventListener("click", (event) => {
            event.preventDefault();
            const pageId = link.getAttribute("data-page");
            showPage(pageId);
        });
    });

    // Make showPage globally available
    window.showPage = showPage;

    // Check URL parameters for page switching
    const urlParams = new URLSearchParams(window.location.search);
    const page = urlParams.get("page");
    if (page) {
        showPage(page);
    } else {
        showPage("page-home");
    }
});
</script>
';

$content = ob_get_clean();
include __DIR__ . '/layouts/app.php';
?>