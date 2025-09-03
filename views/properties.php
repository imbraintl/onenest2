<?php
$title = 'Properties - Mummycare';

ob_start();
?>
<section id="page-hire" class="page active">
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
                    <img src="https://images.pexels.com/photos/106399/pexels-photo-106399.jpeg?auto=compress&cs=tinysrgb&w=800" alt="Property">
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
                <!-- Sample properties for demo -->
                <div class="card">
                    <img src="https://images.pexels.com/photos/106399/pexels-photo-106399.jpeg?auto=compress&cs=tinysrgb&w=800" alt="House">
                    <div class="card-content">
                        <span class="card-tag" style="background-color: #B2DFDB;">For Rent</span>
                        <h4 class="card-title">3BR House - Gaborone West</h4>
                        <p class="card-subtitle">Modern family home with garden</p>
                        <div class="card-footer">
                            <span class="price">P 8,500/month</span>
                            <a class="btn btn-secondary" style="padding: 8px 16px;">View Details</a>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <img src="https://images.pexels.com/photos/1396122/pexels-photo-1396122.jpeg?auto=compress&cs=tinysrgb&w=800" alt="Apartment">
                    <div class="card-content">
                        <span class="card-tag" style="background-color: #B2DFDB;">For Rent</span>
                        <h4 class="card-title">2BR Apartment - Block 8</h4>
                        <p class="card-subtitle">Secure complex with parking</p>
                        <div class="card-footer">
                            <span class="price">P 4,200/month</span>
                            <a class="btn btn-secondary" style="padding: 8px 16px;">View Details</a>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <img src="https://images.pexels.com/photos/1396132/pexels-photo-1396132.jpeg?auto=compress&cs=tinysrgb&w=800" alt="House for Sale">
                    <div class="card-content">
                        <span class="card-tag" style="background-color: #B2DFDB;">For Sale</span>
                        <h4 class="card-title">4BR House - Phakalane</h4>
                        <p class="card-subtitle">Executive home in golf estate</p>
                        <div class="card-footer">
                            <span class="price">P 1,850,000</span>
                            <a class="btn btn-secondary" style="padding: 8px 16px;">View Details</a>
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