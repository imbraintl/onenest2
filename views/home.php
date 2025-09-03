<?php
$title = 'Mummycare - Trusted Local Marketplace';
$topBarMessage = 'Join 10,000+ verified members!';

ob_start();
?>

<!-- Hero Section -->
<div class="hero" style="padding-top: 80px;">
    <div class="container">
        <div class="hero-content">
            <h1 class="hero-title">The Trusted Local Marketplace</h1>
            <p class="hero-subtitle">Connecting verified professionals, goods, and businesses across Botswana. Safe, secure, and simple.</p>
            <div class="hero-cta">
                <a class="btn btn-accent btn-large" href="/register">Get Started Today</a>
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
        <a class="btn btn-accent btn-large" href="/register">Create Your Free Account</a>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/layouts/app.php';
?>