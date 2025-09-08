<?php
$title = 'Property Listing - OneNest';

ob_start();
?>

<div class="dashboard-container">
    <div class="page-header">
        <h1>Property Listing</h1>
        <p>List your property for rent or sale on OneNest</p>
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

        <form method="POST" action="/register/property-listing" enctype="multipart/form-data">
            <!-- Property Owner/Agent Information -->
            <div class="dashboard-card">
                <div class="card-header">
                    <h3 class="card-title">1. Property Owner/Agent Information</h3>
                </div>
                <div class="card-content">
                    <div class="results-grid" style="grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
                        <div class="form-group">
                            <label for="full_name">Full Name *</label>
                            <input type="text" id="full_name" name="full_name" class="form-input" required 
                                   value="<?= htmlspecialchars($_POST['full_name'] ?? '') ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="contact_numbers">Contact Number(s) *</label>
                            <input type="tel" id="contact_numbers" name="contact_numbers" class="form-input" required 
                                   placeholder="e.g., 71234567" value="<?= htmlspecialchars($_POST['contact_numbers'] ?? '') ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="email_address">Email Address</label>
                            <input type="email" id="email_address" name="email_address" class="form-input" 
                                   placeholder="your@email.com" value="<?= htmlspecialchars($_POST['email_address'] ?? '') ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="owner_type">Are you the owner or agent? *</label>
                            <select id="owner_type" name="owner_type" class="form-input" required>
                                <option value="">Select Type</option>
                                <option value="owner">Property Owner</option>
                                <option value="agent">Property Agent</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="business_name">Business Name</label>
                            <input type="text" id="business_name" name="business_name" class="form-input" 
                                   placeholder="If applicable" value="<?= htmlspecialchars($_POST['business_name'] ?? '') ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="whatsapp_number">WhatsApp Number</label>
                            <input type="tel" id="whatsapp_number" name="whatsapp_number" class="form-input" 
                                   placeholder="For direct inquiries" value="<?= htmlspecialchars($_POST['whatsapp_number'] ?? '') ?>">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Property Type -->
            <div class="dashboard-card">
                <div class="card-header">
                    <h3 class="card-title">2. Property Type</h3>
                </div>
                <div class="card-content">
                    <div class="form-group">
                        <label>What are you listing? *</label>
                        <div class="results-grid" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-top: 10px;">
                            <div style="display: flex; align-items: center; padding: 15px; background: #f8f9fa; border-radius: 8px;">
                                <input type="radio" id="house_rent" name="property_type" value="house_rent" required style="margin-right: 10px;">
                                <label for="house_rent" style="margin: 0; cursor: pointer;">House for Rent</label>
                            </div>
                            <div style="display: flex; align-items: center; padding: 15px; background: #f8f9fa; border-radius: 8px;">
                                <input type="radio" id="apartment_rent" name="property_type" value="apartment_rent" required style="margin-right: 10px;">
                                <label for="apartment_rent" style="margin: 0; cursor: pointer;">Apartment for Rent</label>
                            </div>
                            <div style="display: flex; align-items: center; padding: 15px; background: #f8f9fa; border-radius: 8px;">
                                <input type="radio" id="room_rent" name="property_type" value="room_rent" required style="margin-right: 10px;">
                                <label for="room_rent" style="margin: 0; cursor: pointer;">Room for Rent</label>
                            </div>
                            <div style="display: flex; align-items: center; padding: 15px; background: #f8f9fa; border-radius: 8px;">
                                <input type="radio" id="house_sale" name="property_type" value="house_sale" required style="margin-right: 10px;">
                                <label for="house_sale" style="margin: 0; cursor: pointer;">House for Sale</label>
                            </div>
                            <div style="display: flex; align-items: center; padding: 15px; background: #f8f9fa; border-radius: 8px;">
                                <input type="radio" id="plot_sale" name="property_type" value="plot_sale" required style="margin-right: 10px;">
                                <label for="plot_sale" style="margin: 0; cursor: pointer;">Plot for Sale</label>
                            </div>
                            <div style="display: flex; align-items: center; padding: 15px; background: #f8f9fa; border-radius: 8px;">
                                <input type="radio" id="commercial" name="property_type" value="commercial" required style="margin-right: 10px;">
                                <label for="commercial" style="margin: 0; cursor: pointer;">Commercial Property</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Property Details -->
            <div class="dashboard-card">
                <div class="card-header">
                    <h3 class="card-title">3. Property Details</h3>
                </div>
                <div class="card-content">
                    <div class="results-grid" style="grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
                        <div class="form-group">
                            <label for="city">Location (Town/City) *</label>
                            <input type="text" id="city" name="city" class="form-input" required 
                                   placeholder="e.g., Gaborone" value="<?= htmlspecialchars($_POST['city'] ?? '') ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="area">Area/Plot Number</label>
                            <input type="text" id="area" name="area" class="form-input" 
                                   placeholder="e.g., Block 8, Plot 12345" value="<?= htmlspecialchars($_POST['area'] ?? '') ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="price_bwp">Monthly Rent / Sale Price (P) *</label>
                            <input type="number" id="price_bwp" name="price_bwp" class="form-input" required 
                                   min="0" step="0.01" value="<?= htmlspecialchars($_POST['price_bwp'] ?? '') ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="bedrooms">Number of Bedrooms</label>
                            <input type="number" id="bedrooms" name="bedrooms" class="form-input" 
                                   min="0" max="20" value="<?= htmlspecialchars($_POST['bedrooms'] ?? '') ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="bathrooms">Number of Bathrooms</label>
                            <input type="number" id="bathrooms" name="bathrooms" class="form-input" 
                                   min="0" max="20" value="<?= htmlspecialchars($_POST['bathrooms'] ?? '') ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="available_from">Availability Date</label>
                            <input type="date" id="available_from" name="available_from" class="form-input" 
                                   value="<?= htmlspecialchars($_POST['available_from'] ?? '') ?>">
                        </div>
                    </div>
                    
                    <!-- Property Features -->
                    <div class="form-group">
                        <label>Property Features:</label>
                        <div class="results-grid" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 10px; margin-top: 10px;">
                            <div style="display: flex; align-items: center; padding: 10px; background: #f8f9fa; border-radius: 6px;">
                                <input type="checkbox" id="has_living_room" name="has_living_room" value="1" style="margin-right: 8px;">
                                <label for="has_living_room" style="margin: 0; cursor: pointer;">Living Room</label>
                            </div>
                            <div style="display: flex; align-items: center; padding: 10px; background: #f8f9fa; border-radius: 6px;">
                                <input type="checkbox" id="has_kitchen" name="has_kitchen" value="1" style="margin-right: 8px;">
                                <label for="has_kitchen" style="margin: 0; cursor: pointer;">Kitchen</label>
                            </div>
                            <div style="display: flex; align-items: center; padding: 10px; background: #f8f9fa; border-radius: 6px;">
                                <input type="checkbox" id="is_furnished" name="is_furnished" value="1" style="margin-right: 8px;">
                                <label for="is_furnished" style="margin: 0; cursor: pointer;">Fully Furnished</label>
                            </div>
                            <div style="display: flex; align-items: center; padding: 10px; background: #f8f9fa; border-radius: 6px;">
                                <input type="checkbox" id="has_gate" name="has_gate" value="1" style="margin-right: 8px;">
                                <label for="has_gate" style="margin: 0; cursor: pointer;">Fencing/Gate</label>
                            </div>
                            <div style="display: flex; align-items: center; padding: 10px; background: #f8f9fa; border-radius: 6px;">
                                <input type="checkbox" id="has_parking" name="has_parking" value="1" style="margin-right: 8px;">
                                <label for="has_parking" style="margin: 0; cursor: pointer;">Parking Available</label>
                            </div>
                            <div style="display: flex; align-items: center; padding: 10px; background: #f8f9fa; border-radius: 6px;">
                                <input type="checkbox" id="pet_friendly" name="pet_friendly" value="1" style="margin-right: 8px;">
                                <label for="pet_friendly" style="margin: 0; cursor: pointer;">Pet Friendly</label>
                            </div>
                            <div style="display: flex; align-items: center; padding: 10px; background: #f8f9fa; border-radius: 6px;">
                                <input type="checkbox" id="utilities_included" name="utilities_included" value="1" style="margin-right: 8px;">
                                <label for="utilities_included" style="margin: 0; cursor: pointer;">Water & Electricity Included</label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="results-grid" style="grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
                        <div class="form-group">
                            <label for="yard_type">Yard/Compound</label>
                            <select id="yard_type" name="yard_type" class="form-input">
                                <option value="none">None</option>
                                <option value="private">Private</option>
                                <option value="shared">Shared</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="special_features">Special Features</label>
                            <input type="text" id="special_features" name="special_features" class="form-input" 
                                   placeholder="e.g., WiFi, air conditioning, garden, ensuite" value="<?= htmlspecialchars($_POST['special_features'] ?? '') ?>">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Photo Submission -->
            <div class="dashboard-card">
                <div class="card-header">
                    <h3 class="card-title">4. Photo Submission</h3>
                </div>
                <div class="card-content">
                    <p class="mb-3">Please attach clear photos of:</p>
                    <div class="results-grid" style="grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
                        <div class="form-group">
                            <label for="exterior_photos">Exterior Photos *</label>
                            <input type="file" id="exterior_photos" name="exterior_photos[]" class="form-input" 
                                   accept=".jpg,.jpeg,.png" multiple required>
                            <small>Exterior of the property</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="bedroom_photos">Bedroom Photos</label>
                            <input type="file" id="bedroom_photos" name="bedroom_photos[]" class="form-input" 
                                   accept=".jpg,.jpeg,.png" multiple>
                            <small>All bedrooms</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="bathroom_photos">Bathroom Photos</label>
                            <input type="file" id="bathroom_photos" name="bathroom_photos[]" class="form-input" 
                                   accept=".jpg,.jpeg,.png" multiple>
                            <small>All bathrooms</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="kitchen_photos">Kitchen Photos</label>
                            <input type="file" id="kitchen_photos" name="kitchen_photos[]" class="form-input" 
                                   accept=".jpg,.jpeg,.png" multiple>
                            <small>Kitchen area</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="yard_photos">Yard Photos</label>
                            <input type="file" id="yard_photos" name="yard_photos[]" class="form-input" 
                                   accept=".jpg,.jpeg,.png" multiple>
                            <small>Yard/compound if any</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="feature_photos">Special Feature Photos</label>
                            <input type="file" id="feature_photos" name="feature_photos[]" class="form-input" 
                                   accept=".jpg,.jpeg,.png" multiple>
                            <small>Pool, fireplace, balcony, etc.</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Information -->
            <div class="dashboard-card">
                <div class="card-header">
                    <h3 class="card-title">5. Additional Information</h3>
                </div>
                <div class="card-content">
                    <div class="results-grid" style="grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
                        <div class="form-group">
                            <label for="viewing_times">Preferred Viewing Times</label>
                            <input type="text" id="viewing_times" name="viewing_times" class="form-input" 
                                   placeholder="e.g., Weekends 10 AM - 4 PM" value="<?= htmlspecialchars($_POST['viewing_times'] ?? '') ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="currently_occupied">Currently Occupied?</label>
                            <select id="currently_occupied" name="currently_occupied" class="form-input">
                                <option value="0">No</option>
                                <option value="1">Yes</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="negotiation_allowed">Negotiation Allowed?</label>
                            <select id="negotiation_allowed" name="negotiation_allowed" class="form-input">
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="tenant_conditions">Conditions for Tenants/Buyers</label>
                        <textarea id="tenant_conditions" name="tenant_conditions" class="form-input" rows="3" 
                                  placeholder="e.g., no pets, couples only, cash buyers, etc."><?= htmlspecialchars($_POST['tenant_conditions'] ?? '') ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="other_notes">Other Notes</label>
                        <textarea id="other_notes" name="other_notes" class="form-input" rows="3" 
                                  placeholder="Any additional information"><?= htmlspecialchars($_POST['other_notes'] ?? '') ?></textarea>
                    </div>
                </div>
            </div>

            <!-- Submit -->
            <div class="dashboard-card">
                <div class="card-content">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4>Ready to list your property?</h4>
                            <p>Your listing will be reviewed and approved within 24-48 hours</p>
                        </div>
                        <div class="action-buttons">
                            <a href="/" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Submit Listing</button>
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