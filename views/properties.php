<?php
$title = 'Properties - Mummycare';

ob_start();
?>

<div class="page-header">
    <h1>Property Listings</h1>
    <p>Find your perfect home or investment property in Botswana.</p>
</div>

<div class="container">
    <div class="filter-bar">
        <div class="filter-group">
            <select>
                <option>All Property Types</option>
                <option>House for Rent</option>
                <option>Apartment for Rent</option>
                <option>Room for Rent</option>
                <option>House for Sale</option>
                <option>Plot for Sale</option>
                <option>Commercial</option>
            </select>
        </div>
        <div class="filter-group">
            <select>
                <option>All Locations</option>
                <option>Gaborone</option>
                <option>Francistown</option>
                <option>Maun</option>
                <option>Kasane</option>
            </select>
        </div>
        <div class="filter-group">
            <input type="number" placeholder="Min Price (P)">
        </div>
        <div class="filter-group">
            <input type="number" placeholder="Max Price (P)">
        </div>
    </div>
    
    <div class="results-grid">
        <?php if (!empty($properties)): ?>
            <?php foreach ($properties as $property): ?>
            <div class="card">
                <div class="card-content">
                    <span class="card-tag" style="background-color: var(--secondary-color);">
                        <?= ucfirst(str_replace('_', ' ', $property['type'])) ?>
                    </span>
                    <h4 class="card-title"><?= htmlspecialchars($property['city']) ?> - <?= $property['bedrooms'] ?? 'N/A' ?> BR</h4>
                    <p class="card-subtitle"><?= htmlspecialchars($property['area'] ?? '') ?></p>
                    <div class="card-footer">
                        <span class="price">P <?= number_format($property['price_bwp']) ?><?= $property['price_type'] === 'rent' ? '/month' : '' ?></span>
                        <a class="btn btn-secondary" href="/properties/<?= $property['id'] ?>">View Details</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div style="text-align: center; padding: 60px 20px; grid-column: 1 / -1;">
                <h3>No properties found</h3>
                <p>Be the first to list a property!</p>
                <a href="/register" class="btn btn-primary">List Property</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/layouts/app.php';
?>