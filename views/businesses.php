<?php
$title = 'Find Businesses - Mummycare';

ob_start();
?>

<div class="page-header">
    <h1>Find Verified Businesses</h1>
    <p>Discover reputable companies, restaurants, and services operating in Botswana.</p>
</div>

<div class="container">
    <div class="filter-bar">
        <div class="filter-group">
            <input type="text" placeholder="e.g., Choppies, Orange, Law Firm">
        </div>
        <div class="filter-group">
            <select>
                <option>All Industries</option>
                <option>Retail</option>
                <option>Restaurants & Cafes</option>
                <option>Professional Services</option>
                <option>Tourism & Lodges</option>
                <option>Telecommunications</option>
            </select>
        </div>
        <div class="filter-group">
            <select>
                <option>All Locations</option>
                <option>Gaborone</option>
                <option>Francistown</option>
                <option>Maun</option>
            </select>
        </div>
    </div>
    
    <div class="results-grid">
        <?php if (!empty($businesses)): ?>
            <?php foreach ($businesses as $business): ?>
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
        <?php else: ?>
            <div style="text-align: center; padding: 60px 20px; grid-column: 1 / -1;">
                <h3>No businesses found</h3>
                <p>Be the first to register your business!</p>
                <a href="/register" class="btn btn-primary">Register Business</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/layouts/app.php';
?>