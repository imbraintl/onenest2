<?php
$title = 'Find Businesses - Mummycare';

ob_start();
?>
<section id="page-hire" class="page active">
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
                    <img src="https://images.pexels.com/photos/1181396/pexels-photo-1181396.jpeg?auto=compress&cs=tinysrgb&w=800" alt="Business">
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
                <!-- Sample businesses for demo -->
                <div class="card">
                    <img src="https://images.pexels.com/photos/1181396/pexels-photo-1181396.jpeg?auto=compress&cs=tinysrgb&w=800" alt="Restaurant">
                    <div class="card-content">
                        <span class="card-tag" style="background-color: #B2DFDB;">Restaurant</span>
                        <h4 class="card-title">The Gaborone Grill</h4>
                        <p class="card-subtitle">CBD, Gaborone</p>
                        <div class="card-footer">
                            <div class="rating">
                                <i data-lucide="star" class="star-filled"></i>
                                4.7 (150 reviews)
                            </div>
                            <a class="btn btn-secondary" style="padding: 8px 16px;">View Details</a>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <img src="https://images.pexels.com/photos/5668858/pexels-photo-5668858.jpeg?auto=compress&cs=tinysrgb&w=800" alt="Law Firm">
                    <div class="card-content">
                        <span class="card-tag" style="background-color: #B2DFDB;">Professional Services</span>
                        <h4 class="card-title">Armstrongs Attorneys</h4>
                        <p class="card-subtitle">Corporate Law - Gaborone</p>
                        <div class="card-footer">
                            <div class="rating">
                                <i data-lucide="star" class="star-filled"></i>
                                4.9 (Verified)
                            </div>
                            <a class="btn btn-secondary" style="padding: 8px 16px;">View Details</a>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <img src="https://images.pexels.com/photos/338504/pexels-photo-338504.jpeg?auto=compress&cs=tinysrgb&w=800" alt="Lodge">
                    <div class="card-content">
                        <span class="card-tag" style="background-color: #B2DFDB;">Tourism & Lodges</span>
                        <h4 class="card-title">Chobe Game Lodge</h4>
                        <p class="card-subtitle">Kasane, Chobe National Park</p>
                        <div class="card-footer">
                            <div class="rating">
                                <i data-lucide="star" class="star-filled"></i>
                                5.0 (432 reviews)
                            </div>
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