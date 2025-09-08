<?php
$title = 'Job Seeker Profile - OneNest';

ob_start();
?>

<div class="dashboard-container">
    <div class="page-header">
        <h1>Job Seeker Profile</h1>
        <p>Complete your profile to get matched with the right employment opportunities</p>
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

        <form method="POST" action="/register/job-seeker" enctype="multipart/form-data">
            <!-- Personal Information -->
            <div class="dashboard-card">
                <div class="card-header">
                    <h3 class="card-title">1. Personal Information</h3>
                </div>
                <div class="card-content">
                    <div class="results-grid" style="grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
                        <div class="form-group">
                            <label for="full_name">Full Name *</label>
                            <input type="text" id="full_name" name="full_name" class="form-input" required 
                                   value="<?= htmlspecialchars($_POST['full_name'] ?? '') ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="gender">Gender</label>
                            <select id="gender" name="gender" class="form-input">
                                <option value="">Select Gender</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="dob">Date of Birth</label>
                            <input type="date" id="dob" name="dob" class="form-input" 
                                   value="<?= htmlspecialchars($_POST['dob'] ?? '') ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="nationality">Nationality</label>
                            <input type="text" id="nationality" name="nationality" class="form-input" 
                                   placeholder="e.g., Motswana" value="<?= htmlspecialchars($_POST['nationality'] ?? '') ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="id_number">ID or Passport Number</label>
                            <input type="text" id="id_number" name="id_number" class="form-input" 
                                   placeholder="e.g., 123456789" value="<?= htmlspecialchars($_POST['id_number'] ?? '') ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="whatsapp_phone">Phone Number (WhatsApp) *</label>
                            <input type="tel" id="whatsapp_phone" name="whatsapp_phone" class="form-input" required 
                                   placeholder="e.g., 71234567" value="<?= htmlspecialchars($_POST['whatsapp_phone'] ?? '') ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="email_address">Email Address</label>
                            <input type="email" id="email_address" name="email_address" class="form-input" 
                                   placeholder="your@email.com" value="<?= htmlspecialchars($_POST['email_address'] ?? '') ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="language">Language Preference</label>
                            <select id="language" name="language" class="form-input">
                                <option value="en">English</option>
                                <option value="tn">Setswana</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Location Details -->
            <div class="dashboard-card">
                <div class="card-header">
                    <h3 class="card-title">2. Location Details</h3>
                </div>
                <div class="card-content">
                    <div class="results-grid" style="grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
                        <div class="form-group">
                            <label for="city">Current City/Town *</label>
                            <input type="text" id="city" name="city" class="form-input" required 
                                   placeholder="e.g., Gaborone" value="<?= htmlspecialchars($_POST['city'] ?? '') ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="area">Residential Address/Area</label>
                            <input type="text" id="area" name="area" class="form-input" 
                                   placeholder="e.g., Block 8, Phakalane" value="<?= htmlspecialchars($_POST['area'] ?? '') ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="home_village">Home Village *</label>
                            <input type="text" id="home_village" name="home_village" class="form-input" required 
                                   placeholder="e.g., Mochudi" value="<?= htmlspecialchars($_POST['home_village'] ?? '') ?>">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Employment Preferences -->
            <div class="dashboard-card">
                <div class="card-header">
                    <h3 class="card-title">3. Employment Preferences</h3>
                </div>
                <div class="card-content">
                    <div class="results-grid" style="grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
                        <div class="form-group">
                            <label for="availability_type">Employment Type *</label>
                            <select id="availability_type" name="availability_type" class="form-input" required>
                                <option value="both">Open to Both</option>
                                <option value="part_time">Part-time Only</option>
                                <option value="full_time">Full-time Only</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="living_preference">Living Arrangement *</label>
                            <select id="living_preference" name="living_preference" class="form-input" required>
                                <option value="both">Flexible</option>
                                <option value="stay_in">Stay-in Only</option>
                                <option value="stay_out">Stay-out Only</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="salary_min_bwp">Minimum Salary (P) *</label>
                            <input type="number" id="salary_min_bwp" name="salary_min_bwp" class="form-input" required 
                                   min="0" placeholder="e.g., 2500" value="<?= htmlspecialchars($_POST['salary_min_bwp'] ?? '') ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="salary_max_bwp">Maximum Salary (P) *</label>
                            <input type="number" id="salary_max_bwp" name="salary_max_bwp" class="form-input" required 
                                   min="0" placeholder="e.g., 4500" value="<?= htmlspecialchars($_POST['salary_max_bwp'] ?? '') ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="current_employment_status">Current Employment Status *</label>
                            <select id="current_employment_status" name="current_employment_status" class="form-input" required>
                                <option value="unemployed">Unemployed</option>
                                <option value="employed">Currently Employed</option>
                                <option value="notice">On Notice Period</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="available_from">Available to Start *</label>
                            <input type="date" id="available_from" name="available_from" class="form-input" required 
                                   value="<?= htmlspecialchars($_POST['available_from'] ?? '') ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="work_pattern">Work Pattern Preference</label>
                            <select id="work_pattern" name="work_pattern" class="form-input">
                                <option value="mon_sat">Monday - Saturday</option>
                                <option value="mon_fri_weekends_2in2out">Mon-Fri + 2 weekends in/out</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="experience_years">Years of Experience</label>
                            <input type="number" id="experience_years" name="experience_years" class="form-input" 
                                   min="0" max="50" value="<?= htmlspecialchars($_POST['experience_years'] ?? '') ?>">
                        </div>
                    </div>
                    
                    <!-- Job Types Interested In -->
                    <div class="form-group">
                        <label>Jobs I'm Interested In: *</label>
                        <div class="results-grid" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 10px; margin-top: 10px;">
                            <?php 
                            $jobTypes = [
                                'nanny' => 'Nanny',
                                'babysitter' => 'Babysitter',
                                'cleaner' => 'Cleaner',
                                'housekeeper' => 'Housekeeper',
                                'yard_cleaner' => 'Yard Cleaner',
                                'elderly_care' => 'Old Age Caretaker',
                                'driver' => 'Driver',
                                'cook' => 'Cook',
                                'tutor' => 'Tutor',
                                'maintenance' => 'Maintenance/Handyman'
                            ];
                            ?>
                            
                            <?php foreach ($jobTypes as $key => $label): ?>
                            <div style="display: flex; align-items: center; padding: 10px; background: #f8f9fa; border-radius: 6px;">
                                <input type="checkbox" id="job_type_<?= $key ?>" name="job_types[]" value="<?= $key ?>" 
                                       style="margin-right: 8px;">
                                <label for="job_type_<?= $key ?>" style="margin: 0; cursor: pointer; font-size: 0.9rem;"><?= $label ?></label>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <div style="display: flex; align-items: center; padding: 15px; background: #e3f2fd; border-radius: 8px;">
                            <input type="checkbox" id="assisted_interview" name="assisted_interview" value="1" 
                                   style="margin-right: 10px;">
                            <label for="assisted_interview" style="margin: 0; cursor: pointer;">
                                <strong>Have our team conduct interviews for you</strong><br>
                                <small>Our professional team can handle the interview process to find the best candidates</small>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Portfolio (for maintenance freelancers) -->
            <div class="dashboard-card">
                <div class="card-header">
                    <h3 class="card-title">4. Portfolio & Documents</h3>
                </div>
                <div class="card-content">
                    <div class="results-grid" style="grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
                        <div class="form-group">
                            <label for="portfolio_photos">Portfolio Photos</label>
                            <input type="file" id="portfolio_photos" name="portfolio_photos[]" class="form-input" 
                                   accept=".jpg,.jpeg,.png" multiple>
                            <small>For maintenance freelancers - photos of previous work</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="resume">Resume/CV</label>
                            <input type="file" id="resume" name="resume" class="form-input" 
                                   accept=".pdf,.doc,.docx">
                            <small>PDF or Word document</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="id_document">ID/Passport Copy</label>
                            <input type="file" id="id_document" name="id_document" class="form-input" 
                                   accept=".jpg,.jpeg,.png,.pdf">
                            <small>For verification purposes</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="certifications">Certifications</label>
                            <input type="file" id="certifications" name="certifications[]" class="form-input" 
                                   accept=".jpg,.jpeg,.png,.pdf" multiple>
                            <small>Any relevant certificates or qualifications</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit -->
            <div class="dashboard-card">
                <div class="card-content">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4>Ready to find your perfect job?</h4>
                            <p>Your profile will be visible to employers and recruiters</p>
                        </div>
                        <div class="action-buttons">
                            <a href="/" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Complete Profile</button>
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