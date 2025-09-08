<?php
$title = 'Product Seller Registration - OneNest';

ob_start();
?>

<div class="dashboard-container">
    <div class="page-header">
        <h1>Product Seller Registration</h1>
        <p>Register your business to sell products on the OneNest marketplace</p>
    </div>

    <div class="container">
        <?php if (isset($errors) && !empty($errors)): ?>
            <div style="background: #fee; color: #c33; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                <ul style="margin: 0; padding-left: 20px;">
                    <?php foreach ($errors as $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="POST" action="/register/product-seller" enctype="multipart/form-data">
            <!-- Business Information -->
            <div class="dashboard-card">
                <div class="card-header">
                    <h3 class="card-title">1. Business Information</h3>
                </div>
                <div class="card-content">
                    <div class="results-grid" style="grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
                        <div class="form-group">
                            <label for="business_name">Business Name *</label>
                            <input type="text" id="business_name" name="business_name" class="form-input" required 
                                   value="<?= htmlspecialchars($_POST['business_name'] ?? '') ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="product_category">Product Category *</label>
                            <select id="product_category" name="product_category" class="form-input" required>
                                <option value="">Select Category</option>
                                <option value="clothing">Clothing</option>
                                <option value="toys">Toys</option>
                                <option value="beauty">Beauty</option>
                                <option value="food">Food</option>
                                <option value="electronics">Electronics</option>
                                <option value="furniture">Furniture</option>
                                <option value="baby_products">Baby Products</option>
                                <option value="household">Household Items</option>
                                <option value="local_crafts">Local Crafts</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="location_text">Business Location *</label>
                            <input type="text" id="location_text" name="location_text" class="form-input" required 
                                   placeholder="e.g., Main Mall, Gaborone" value="<?= htmlspecialchars($_POST['location_text'] ?? '') ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="open_time">Opening Time</label>
                            <input type="time" id="open_time" name="open_time" class="form-input" 
                                   value="<?= htmlspecialchars($_POST['open_time'] ?? '') ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="close_time">Closing Time</label>
                            <input type="time" id="close_time" name="close_time" class="form-input" 
                                   value="<?= htmlspecialchars($_POST['close_time'] ?? '') ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="registration_number">Business Registration Number</label>
                            <input type="text" id="registration_number" name="registration_number" class="form-input" 
                                   placeholder="If applicable" value="<?= htmlspecialchars($_POST['registration_number'] ?? '') ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="years_in_operation">Years in Operation</label>
                            <input type="number" id="years_in_operation" name="years_in_operation" class="form-input" 
                                   min="0" max="60" value="<?= htmlspecialchars($_POST['years_in_operation'] ?? '') ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="sales_mode">Sales Mode *</label>
                            <select id="sales_mode" name="sales_mode" class="form-input" required>
                                <option value="">Select Mode</option>
                                <option value="physical">Physical Shop Only</option>
                                <option value="online">Online Only</option>
                                <option value="both">Both Physical & Online</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="delivery_areas">Delivery Areas</label>
                            <input type="text" id="delivery_areas" name="delivery_areas" class="form-input" 
                                   placeholder="Where do you deliver?" value="<?= htmlspecialchars($_POST['delivery_areas'] ?? '') ?>">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="dashboard-card">
                <div class="card-header">
                    <h3 class="card-title">2. Contact Information</h3>
                </div>
                <div class="card-content">
                    <div class="results-grid" style="grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
                        <div class="form-group">
                            <label for="contact_person">Contact Person Name *</label>
                            <input type="text" id="contact_person" name="contact_person" class="form-input" required 
                                   value="<?= htmlspecialchars($_POST['contact_person'] ?? '') ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="contact_phone">Phone Number(s) *</label>
                            <input type="tel" id="contact_phone" name="contact_phone" class="form-input" required 
                                   placeholder="e.g., 71234567" value="<?= htmlspecialchars($_POST['contact_phone'] ?? '') ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="contact_email">Email Address</label>
                            <input type="email" id="contact_email" name="contact_email" class="form-input" 
                                   placeholder="business@email.com" value="<?= htmlspecialchars($_POST['contact_email'] ?? '') ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="facebook">Facebook Page</label>
                            <input type="url" id="facebook" name="facebook" class="form-input" 
                                   placeholder="https://facebook.com/yourbusiness" value="<?= htmlspecialchars($_POST['facebook'] ?? '') ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="instagram">Instagram Handle</label>
                            <input type="text" id="instagram" name="instagram" class="form-input" 
                                   placeholder="@yourbusiness" value="<?= htmlspecialchars($_POST['instagram'] ?? '') ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="website">Website</label>
                            <input type="url" id="website" name="website" class="form-input" 
                                   placeholder="https://yourbusiness.com" value="<?= htmlspecialchars($_POST['website'] ?? '') ?>">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Product Details -->
            <div class="dashboard-card">
                <div class="card-header">
                    <h3 class="card-title">3. Product Details</h3>
                </div>
                <div class="card-content">
                    <div class="form-group">
                        <label for="product_description">Product Description *</label>
                        <textarea id="product_description" name="product_description" class="form-input" rows="4" required 
                                  placeholder="Describe your products in detail. Include types, prices, packaging options, and certifications."><?= htmlspecialchars($_POST['product_description'] ?? '') ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="pricing_info">Pricing Information</label>
                        <textarea id="pricing_info" name="pricing_info" class="form-input" rows="3" 
                                  placeholder="List your product prices and any package deals"><?= htmlspecialchars($_POST['pricing_info'] ?? '') ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="packaging_options">Packaging Options</label>
                        <input type="text" id="packaging_options" name="packaging_options" class="form-input" 
                               placeholder="e.g., Individual, Bulk, Gift wrapping" value="<?= htmlspecialchars($_POST['packaging_options'] ?? '') ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="certifications">Certifications & Features</label>
                        <input type="text" id="certifications" name="certifications" class="form-input" 
                               placeholder="e.g., Organic, Handmade, ISO certified" value="<?= htmlspecialchars($_POST['certifications'] ?? '') ?>">
                    </div>
                </div>
            </div>

            <!-- File Uploads -->
            <div class="dashboard-card">
                <div class="card-header">
                    <h3 class="card-title">4. Product Photos & Files</h3>
                </div>
                <div class="card-content">
                    <div class="results-grid" style="grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
                        <div class="form-group">
                            <label for="product_photos">Product Photos *</label>
                            <input type="file" id="product_photos" name="product_photos[]" class="form-input" 
                                   accept=".jpg,.jpeg,.png" multiple required>
                            <small>Clear photos of each product with name, price, and description</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="product_catalogue">Product Catalogue</label>
                            <input type="file" id="product_catalogue" name="product_catalogue" class="form-input" 
                                   accept=".pdf,.jpg,.jpeg,.png">
                            <small>PDF or image format</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="promotional_materials">Promotional Materials</label>
                            <input type="file" id="promotional_materials" name="promotional_materials[]" class="form-input" 
                                   accept=".pdf,.jpg,.jpeg,.png" multiple>
                            <small>Flyers, brochures, etc.</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="certifications_files">Certification Documents</label>
                            <input type="file" id="certifications_files" name="certifications_files[]" class="form-input" 
                                   accept=".pdf,.jpg,.jpeg,.png" multiple>
                            <small>Organic, handmade, or other certifications</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit -->
            <div class="dashboard-card">
                <div class="card-content">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4>Ready to register as a product seller?</h4>
                            <p>Your registration will be reviewed by our team within 24-48 hours</p>
                        </div>
                        <div class="action-buttons">
                            <a href="/" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Submit Registration</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/app.php';
?>