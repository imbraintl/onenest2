<?php
$title = 'Service Provider Registration - OneNest';

ob_start();
?>

<div class="dashboard-container">
    <div class="page-header">
        <h1>Service Provider Registration</h1>
        <p>Register your business to offer services on the OneNest platform</p>
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

        <form method="POST" action="/register/service-provider" enctype="multipart/form-data">
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
                            <label for="service_types">Type of Service *</label>
                            <select id="service_types" name="service_types[]" class="form-input" multiple required>
                                <option value="catering">Catering</option>
                                <option value="entertainment">Entertainment</option>
                                <option value="cleaning">Cleaning</option>
                                <option value="healthcare">Healthcare</option>
                                <option value="tutoring">Tutoring</option>
                                <option value="childcare">Childcare</option>
                                <option value="elderly_care">Elderly Care</option>
                                <option value="home_maintenance">Home Maintenance</option>
                                <option value="transportation">Transportation</option>
                                <option value="security">Security</option>
                                <option value="photography">Photography</option>
                                <option value="event_planning">Event Planning</option>
                            </select>
                            <small>Hold Ctrl/Cmd to select multiple services</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="location_text">Business Location *</label>
                            <input type="text" id="location_text" name="location_text" class="form-input" required 
                                   placeholder="e.g., CBD Gaborone" value="<?= htmlspecialchars($_POST['location_text'] ?? '') ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="service_area">Service Area</label>
                            <input type="text" id="service_area" name="service_area" class="form-input" 
                                   placeholder="Where do you operate?" value="<?= htmlspecialchars($_POST['service_area'] ?? '') ?>">
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
                            <label for="registration_number">CIPA Registration Number</label>
                            <input type="text" id="registration_number" name="registration_number" class="form-input" 
                                   placeholder="Business registration number" value="<?= htmlspecialchars($_POST['registration_number'] ?? '') ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="years_in_operation">Years in Operation</label>
                            <input type="number" id="years_in_operation" name="years_in_operation" class="form-input" 
                                   min="0" max="60" value="<?= htmlspecialchars($_POST['years_in_operation'] ?? '') ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="employees_count">Number of Employees</label>
                            <input type="number" id="employees_count" name="employees_count" class="form-input" 
                                   min="0" value="<?= htmlspecialchars($_POST['employees_count'] ?? '') ?>">
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
                            <label for="website">Website</label>
                            <input type="url" id="website" name="website" class="form-input" 
                                   placeholder="https://yourbusiness.com" value="<?= htmlspecialchars($_POST['website'] ?? '') ?>">
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
                    </div>
                </div>
            </div>

            <!-- Service Details -->
            <div class="dashboard-card">
                <div class="card-header">
                    <h3 class="card-title">3. Service Details</h3>
                </div>
                <div class="card-content">
                    <div class="form-group">
                        <label for="service_description">Service Description *</label>
                        <textarea id="service_description" name="service_description" class="form-input" rows="4" required 
                                  placeholder="Describe the services you offer in detail. Include pricing structure, packages, and specialties."><?= htmlspecialchars($_POST['service_description'] ?? '') ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="pricing_structure">Pricing Structure</label>
                        <textarea id="pricing_structure" name="pricing_structure" class="form-input" rows="3" 
                                  placeholder="e.g., Hourly rates, package deals, consultation fees"><?= htmlspecialchars($_POST['pricing_structure'] ?? '') ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="specialties">Service Specialties</label>
                        <textarea id="specialties" name="specialties" class="form-input" rows="2" 
                                  placeholder="What makes your service unique?"><?= htmlspecialchars($_POST['specialties'] ?? '') ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <div style="display: flex; align-items: center; padding: 15px; background: #e3f2fd; border-radius: 8px;">
                            <input type="checkbox" id="team_interviews" name="team_interviews" value="1" 
                                   style="margin-right: 10px;">
                            <label for="team_interviews" style="margin: 0; cursor: pointer;">
                                <strong>Have our team conduct interviews for you</strong><br>
                                <small>Our professional team can handle the interview process for hiring staff</small>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- File Uploads -->
            <div class="dashboard-card">
                <div class="card-header">
                    <h3 class="card-title">4. Additional Files</h3>
                </div>
                <div class="card-content">
                    <div class="results-grid" style="grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
                        <div class="form-group">
                            <label for="business_license">Business License</label>
                            <input type="file" id="business_license" name="business_license" class="form-input" 
                                   accept=".pdf,.jpg,.jpeg,.png">
                            <small>PDF, JPG, or PNG format</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="company_profile">Company Profile/Brochure</label>
                            <input type="file" id="company_profile" name="company_profile" class="form-input" 
                                   accept=".pdf,.jpg,.jpeg,.png">
                            <small>PDF, JPG, or PNG format</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="portfolio">Service Photos/Portfolio</label>
                            <input type="file" id="portfolio" name="portfolio[]" class="form-input" 
                                   accept=".jpg,.jpeg,.png" multiple>
                            <small>Multiple images allowed</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="promotional">Promotional Materials</label>
                            <input type="file" id="promotional" name="promotional[]" class="form-input" 
                                   accept=".pdf,.jpg,.jpeg,.png" multiple>
                            <small>Flyers, brochures, etc.</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit -->
            <div class="dashboard-card">
                <div class="card-content">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4>Ready to register your business?</h4>
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