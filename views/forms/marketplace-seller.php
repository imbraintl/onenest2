<?php
$title = 'Marketplace Seller - OneNest';

ob_start();
?>

<div class="dashboard-container">
    <div class="page-header">
        <h1>Marketplace Seller Registration</h1>
        <p>List your second-hand products on the OneNest marketplace</p>
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

        <form method="POST" action="/register/marketplace-seller" enctype="multipart/form-data">
            <!-- Seller Information -->
            <div class="dashboard-card">
                <div class="card-header">
                    <h3 class="card-title">1. Seller Information</h3>
                </div>
                <div class="card-content">
                    <div class="results-grid" style="grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
                        <div class="form-group">
                            <label for="full_name">Full Name *</label>
                            <input type="text" id="full_name" name="full_name" class="form-input" required 
                                   value="<?= htmlspecialchars($_POST['full_name'] ?? '') ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="business_name">Business Name</label>
                            <input type="text" id="business_name" name="business_name" class="form-input" 
                                   placeholder="If applicable" value="<?= htmlspecialchars($_POST['business_name'] ?? '') ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="location">Location *</label>
                            <input type="text" id="location" name="location" class="form-input" required 
                                   placeholder="e.g., Gaborone, Block 8" value="<?= htmlspecialchars($_POST['location'] ?? '') ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="phone_numbers">Phone Number(s) *</label>
                            <input type="tel" id="phone_numbers" name="phone_numbers" class="form-input" required 
                                   placeholder="e.g., 71234567" value="<?= htmlspecialchars($_POST['phone_numbers'] ?? '') ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="email_address">Email Address</label>
                            <input type="email" id="email_address" name="email_address" class="form-input" 
                                   placeholder="your@email.com" value="<?= htmlspecialchars($_POST['email_address'] ?? '') ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="shop_type">Shop Type *</label>
                            <select id="shop_type" name="shop_type" class="form-input" required>
                                <option value="">Select Type</option>
                                <option value="physical">Physical Shop</option>
                                <option value="online">Online Only</option>
                                <option value="both">Both Physical & Online</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="facebook">Facebook Page</label>
                            <input type="url" id="facebook" name="facebook" class="form-input" 
                                   placeholder="https://facebook.com/yourpage" value="<?= htmlspecialchars($_POST['facebook'] ?? '') ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="instagram">Instagram Handle</label>
                            <input type="text" id="instagram" name="instagram" class="form-input" 
                                   placeholder="@yourhandle" value="<?= htmlspecialchars($_POST['instagram'] ?? '') ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="delivery_areas">Delivery Areas</label>
                            <input type="text" id="delivery_areas" name="delivery_areas" class="form-input" 
                                   placeholder="Where do you deliver?" value="<?= htmlspecialchars($_POST['delivery_areas'] ?? '') ?>">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Product Entry Section -->
            <div class="dashboard-card">
                <div class="card-header">
                    <h3 class="card-title">2. Product Details</h3>
                    <button type="button" class="btn btn-secondary btn-small" onclick="addProductEntry()">
                        <i data-lucide="plus"></i>
                        Add Another Product
                    </button>
                </div>
                <div class="card-content">
                    <div id="product-entries">
                        <!-- Product Entry Template -->
                        <div class="product-entry" style="border: 1px solid #eee; padding: 20px; border-radius: 8px; margin-bottom: 20px;">
                            <h4 style="margin-bottom: 15px; color: var(--primary-color);">Product #1</h4>
                            <div class="results-grid" style="grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 15px;">
                                <div class="form-group">
                                    <label for="product_name_1">Product Name *</label>
                                    <input type="text" id="product_name_1" name="products[0][name]" class="form-input" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="product_category_1">Category *</label>
                                    <select id="product_category_1" name="products[0][category]" class="form-input" required>
                                        <option value="">Select Category</option>
                                        <option value="clothing">Clothing</option>
                                        <option value="accessories">Accessories</option>
                                        <option value="toys">Toys</option>
                                        <option value="beauty">Beauty</option>
                                        <option value="electronics">Electronics</option>
                                        <option value="furniture">Furniture</option>
                                        <option value="baby_products">Baby Products</option>
                                        <option value="household">Household Items</option>
                                    </select>
                                </div>
                                
                                <div class="form-group">
                                    <label for="product_price_1">Price (P) *</label>
                                    <input type="number" id="product_price_1" name="products[0][price]" class="form-input" required min="0" step="0.01">
                                </div>
                                
                                <div class="form-group">
                                    <label for="product_quantity_1">Quantity Available</label>
                                    <input type="number" id="product_quantity_1" name="products[0][quantity]" class="form-input" min="1" value="1">
                                </div>
                                
                                <div class="form-group">
                                    <label for="product_delivery_1">Delivery Options</label>
                                    <select id="product_delivery_1" name="products[0][delivery_options]" class="form-input">
                                        <option value="pickup">Pickup Only</option>
                                        <option value="delivery">Delivery Only</option>
                                        <option value="both">Both Pickup & Delivery</option>
                                    </select>
                                </div>
                                
                                <div class="form-group">
                                    <label for="product_handmade_1">Item Type</label>
                                    <select id="product_handmade_1" name="products[0][is_handmade]" class="form-input">
                                        <option value="0">Manufactured</option>
                                        <option value="1">Handmade</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="product_description_1">Product Description *</label>
                                <textarea id="product_description_1" name="products[0][description]" class="form-input" rows="3" required 
                                          placeholder="Detailed description of the product condition, features, etc."></textarea>
                            </div>
                            
                            <div class="form-group">
                                <label for="product_photo_1">Product Photo *</label>
                                <input type="file" id="product_photo_1" name="products[0][photo]" class="form-input" 
                                       accept=".jpg,.jpeg,.png" required>
                                <small>Clear photo of this product</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Information -->
            <div class="dashboard-card">
                <div class="card-header">
                    <h3 class="card-title">3. Additional Information</h3>
                </div>
                <div class="card-content">
                    <div class="form-group">
                        <label for="return_policy">Return or Refund Policy</label>
                        <textarea id="return_policy" name="return_policy" class="form-input" rows="3" 
                                  placeholder="Describe your return/refund policy if you have one"><?= htmlspecialchars($_POST['return_policy'] ?? '') ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <div style="display: flex; align-items: center; padding: 15px; background: #f8f9fa; border-radius: 8px;">
                            <input type="checkbox" id="event_promotions" name="event_promotions" value="1" 
                                   style="margin-right: 10px;">
                            <label for="event_promotions" style="margin: 0; cursor: pointer;">
                                <strong>Interested in promotions and events</strong><br>
                                <small>Would you like to feature your products at events/fairs?</small>
                            </label>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="additional_info">Other Information</label>
                        <textarea id="additional_info" name="additional_info" class="form-input" rows="3" 
                                  placeholder="Any other information you'd like us to know"><?= htmlspecialchars($_POST['additional_info'] ?? '') ?></textarea>
                    </div>
                </div>
            </div>

            <!-- Submit -->
            <div class="dashboard-card">
                <div class="card-content">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4>Ready to start selling?</h4>
                            <p>Your products will be reviewed and approved within 24-48 hours</p>
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

<script>
let productCount = 1;

function addProductEntry() {
    productCount++;
    const container = document.getElementById('product-entries');
    const newEntry = document.createElement('div');
    newEntry.className = 'product-entry';
    newEntry.style.cssText = 'border: 1px solid #eee; padding: 20px; border-radius: 8px; margin-bottom: 20px;';
    
    newEntry.innerHTML = `
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
            <h4 style="margin: 0; color: var(--primary-color);">Product #${productCount}</h4>
            <button type="button" class="btn btn-error btn-small" onclick="removeProductEntry(this)">
                <i data-lucide="trash-2"></i>
                Remove
            </button>
        </div>
        <div class="results-grid" style="grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 15px;">
            <div class="form-group">
                <label>Product Name *</label>
                <input type="text" name="products[${productCount-1}][name]" class="form-input" required>
            </div>
            <div class="form-group">
                <label>Category *</label>
                <select name="products[${productCount-1}][category]" class="form-input" required>
                    <option value="">Select Category</option>
                    <option value="clothing">Clothing</option>
                    <option value="accessories">Accessories</option>
                    <option value="toys">Toys</option>
                    <option value="beauty">Beauty</option>
                    <option value="electronics">Electronics</option>
                    <option value="furniture">Furniture</option>
                    <option value="baby_products">Baby Products</option>
                    <option value="household">Household Items</option>
                </select>
            </div>
            <div class="form-group">
                <label>Price (P) *</label>
                <input type="number" name="products[${productCount-1}][price]" class="form-input" required min="0" step="0.01">
            </div>
            <div class="form-group">
                <label>Quantity Available</label>
                <input type="number" name="products[${productCount-1}][quantity]" class="form-input" min="1" value="1">
            </div>
            <div class="form-group">
                <label>Delivery Options</label>
                <select name="products[${productCount-1}][delivery_options]" class="form-input">
                    <option value="pickup">Pickup Only</option>
                    <option value="delivery">Delivery Only</option>
                    <option value="both">Both Pickup & Delivery</option>
                </select>
            </div>
            <div class="form-group">
                <label>Item Type</label>
                <select name="products[${productCount-1}][is_handmade]" class="form-input">
                    <option value="0">Manufactured</option>
                    <option value="1">Handmade</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label>Product Description *</label>
            <textarea name="products[${productCount-1}][description]" class="form-input" rows="3" required 
                      placeholder="Detailed description of the product condition, features, etc."></textarea>
        </div>
        <div class="form-group">
            <label>Product Photo *</label>
            <input type="file" name="products[${productCount-1}][photo]" class="form-input" 
                   accept=".jpg,.jpeg,.png" required>
            <small>Clear photo of this product</small>
        </div>
    `;
    
    container.appendChild(newEntry);
    lucide.createIcons(); // Reinitialize icons
}

function removeProductEntry(button) {
    button.closest('.product-entry').remove();
}
</script>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/app.php';
?>