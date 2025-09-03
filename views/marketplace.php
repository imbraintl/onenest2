<?php
$title = 'Shop Goods - Mummycare';

ob_start();
?>

<section id="page-hire" class="page active">
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
                    <img src="https://images.pexels.com/photos/1181396/pexels-photo-1181396.jpeg?auto=compress&cs=tinysrgb&w=800" alt="Product">
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
                <!-- Sample marketplace items for demo -->
                <div class="card">
                    <img src="https://images.pexels.com/photos/1181396/pexels-photo-1181396.jpeg?auto=compress&cs=tinysrgb&w=800" alt="Toyota Hilux">
                    <div class="card-content">
                        <span class="card-tag" style="background-color: #E0F2F1;">Vehicles</span>
                        <h4 class="card-title">2018 Toyota Hilux 2.8 GD-6</h4>
                        <p class="card-subtitle">Excellent Condition - Gaborone</p>
                        <div class="card-footer">
                            <span class="price">P 350,000</span>
                            <a class="btn btn-secondary" style="padding: 8px 16px;">View Listing</a>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <img src="https://images.pexels.com/photos/404280/pexels-photo-404280.jpeg?auto=compress&cs=tinysrgb&w=800" alt="iPhone">
                    <div class="card-content">
                        <span class="card-tag" style="background-color: #E0F2F1;">Electronics</span>
                        <h4 class="card-title">iPhone 12 Pro 128GB</h4>
                        <p class="card-subtitle">Slightly Used - Francistown</p>
                        <div class="card-footer">
                            <span class="price">P 7,500</span>
                            <a class="btn btn-secondary" style="padding: 8px 16px;">View Listing</a>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <img src="https://images.pexels.com/photos/1181396/pexels-photo-1181396.jpeg?auto=compress&cs=tinysrgb&w=800" alt="Basket">
                    <div class="card-content">
                        <span class="card-tag" style="background-color: #E0F2F1;">Local Crafts</span>
                        <h4 class="card-title">Hand-woven Basket</h4>
                        <p class="card-subtitle">Authentic from Maun</p>
                        <div class="card-footer">
                            <span class="price">P 450</span>
                            <a class="btn btn-secondary" style="padding: 8px 16px;">View Listing</a>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>
<?php
$content = ob_get_clean();
include __DIR__ . '/layouts/app.php';
?>