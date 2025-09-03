<?php
$title = 'Shop Goods - Mummycare';

ob_start();
?>

<div class="page-header">
    <h1>Shop Curated Goods</h1>
    <p>Buy and sell quality second-hand items from verified members in your community.</p>
</div>

<div class="container">
    <div class="filter-bar">
        <div class="filter-group">
            <input type="text" placeholder="e.g., Toyota Hilux, iPhone, Sofa Set">
        </div>
        <div class="filter-group">
            <select>
                <option>All Categories</option>
                <option>Electronics</option>
                <option>Vehicles</option>
                <option>Furniture & Home</option>
                <option>Fashion</option>
                <option>Local Crafts</option>
            </select>
        </div>
        <div class="filter-group">
            <input type="text" placeholder="Location (e.g., Gaborone)">
        </div>
    </div>
    
    <div class="results-grid">
        <?php if (!empty($items)): ?>
            <?php foreach ($items as $item): ?>
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
        <?php else: ?>
            <div style="text-align: center; padding: 60px 20px; grid-column: 1 / -1;">
                <h3>No items found</h3>
                <p>Be the first to list an item!</p>
                <a href="/register" class="btn btn-primary">Start Selling</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/layouts/app.php';
?>