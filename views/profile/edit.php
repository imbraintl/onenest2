<?php
$title = 'My Profile - OneNest';

ob_start();
?>

<div class="dashboard-container">
    <div class="page-header">
        <h1>My Profile</h1>
        <p>Complete your profile to get better matches and opportunities</p>
    </div>

    <div class="container">
        <?php if (isset($_GET['success'])): ?>
            <div style="background: #e8f5e8; color: #4CAF50; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                Profile updated successfully!
            </div>
        <?php endif; ?>

        <?php if (isset($errors) && !empty($errors)): ?>
            <div style="background: #fee; color: #c33; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                <ul style="margin: 0; padding-left: 20px;">
                    <?php foreach ($errors as $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="POST" action="/profile/update" enctype="multipart/form-data">
            <div class="dashboard-card">
                <div class="card-header">
                    <h3 class="card-title">Personal Information</h3>
                </div>
                <div class="card-content">
                    <div class="results-grid" style="grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
                        <div class="form-group">
                            <label for="gender">Gender</label>
                            <select id="gender" name="gender" class="form-input">
                                <option value="">Select Gender</option>
                                <option value="male" <?= ($profile['gender'] ?? '') === 'male' ? 'selected' : '' ?>>Male</option>
                                <option value="female" <?= ($profile['gender'] ?? '') === 'female' ? 'selected' : '' ?>>Female</option>
                                <option value="other" <?= ($profile['gender'] ?? '') === 'other' ? 'selected' : '' ?>>Other</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="dob">Date of Birth</label>
                            <input type="date" id="dob" name="dob" class="form-input" 
                                   value="<?= htmlspecialchars($profile['dob'] ?? '') ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="nationality">Nationality</label>
                            <input type="text" id="nationality" name="nationality" class="form-input" 
                                   placeholder="e.g., Motswana" value="<?= htmlspecialchars($profile['nationality'] ?? '') ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="id_number">ID or Passport Number</label>
                            <input type="text" id="id_number" name="id_number" class="form-input" 
                                   placeholder="e.g., 123456789" value="<?= htmlspecialchars($profile['id_number'] ?? '') ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="whatsapp_phone">WhatsApp Number</label>
                            <input type="tel" id="whatsapp_phone" name="whatsapp_phone" class="form-input" 
                                   placeholder="e.g., 71234567" value="<?= htmlspecialchars($profile['whatsapp_phone'] ?? '') ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="language">Preferred Language</label>
                            <select id="language" name="language" class="form-input">
                                <option value="">Select Language</option>
                                <option value="en" <?= ($profile['language'] ?? '') === 'en' ? 'selected' : '' ?>>English</option>
                                <option value="tn" <?= ($profile['language'] ?? '') === 'tn' ? 'selected' : '' ?>>Setswana</option>
                                <option value="other" <?= ($profile['language'] ?? '') === 'other' ? 'selected' : '' ?>>Other</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="dashboard-card">
                <div class="card-header">
                    <h3 class="card-title">Location Details</h3>
                </div>
                <div class="card-content">
                    <div class="results-grid" style="grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
                        <div class="form-group">
                            <label for="city">Current City/Town</label>
                            <input type="text" id="city" name="city" class="form-input" 
                                   placeholder="e.g., Gaborone" value="<?= htmlspecialchars($profile['city'] ?? '') ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="area">Residential Area</label>
                            <input type="text" id="area" name="area" class="form-input" 
                                   placeholder="e.g., Block 8, Phakalane" value="<?= htmlspecialchars($profile['area'] ?? '') ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="home_village">Home Village</label>
                            <input type="text" id="home_village" name="home_village" class="form-input" 
                                   placeholder="e.g., Mochudi" value="<?= htmlspecialchars($profile['home_village'] ?? '') ?>">
                        </div>
                    </div>
                </div>
            </div>

            <div class="dashboard-card">
                <div class="card-header">
                    <h3 class="card-title">Interests & Preferences</h3>
                </div>
                <div class="card-content">
                    <p class="mb-3">Would you like to receive special offers from:</p>
                    <div class="results-grid" style="grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 15px;">
                        <?php 
                        $notifications = $profile['notifications_json'] ?? [];
                        $notificationOptions = [
                            'restaurants' => 'Restaurants & Food Deals',
                            'events' => 'Family Events & Fairs',
                            'tutoring' => 'School Programs & Tutoring',
                            'nanny_training' => 'Nanny Training/Workshops',
                            'health_safety' => 'Health & Safety Services',
                            'local_sellers' => 'Local Product Sellers'
                        ];
                        ?>
                        
                        <?php foreach ($notificationOptions as $key => $label): ?>
                        <div style="display: flex; align-items: center; padding: 10px; background: #f8f9fa; border-radius: 8px;">
                            <input type="checkbox" id="notification_<?= $key ?>" name="notifications[<?= $key ?>]" 
                                   <?= ($notifications[$key] ?? false) ? 'checked' : '' ?> 
                                   style="margin-right: 10px;">
                            <label for="notification_<?= $key ?>" style="margin: 0; cursor: pointer;"><?= $label ?></label>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- Job Seeker Section -->
            <div class="dashboard-card">
                <div class="card-header">
                    <h3 class="card-title">Job Seeker Profile (Optional)</h3>
                    <div style="display: flex; align-items: center;">
                        <input type="checkbox" id="is_job_seeker" name="is_job_seeker" value="1" 
                               style="margin-right: 10px;">
                        <label for="is_job_seeker" style="margin: 0;">I am looking for employment</label>
                    </div>
                </div>
                <div class="card-content" id="job-seeker-section" style="display: none;">
                    <div class="results-grid" style="grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
                        <div class="form-group">
                            <label for="availability_type">Employment Type</label>
                            <select id="availability_type" name="availability_type" class="form-input">
                                <option value="both">Open to Both</option>
                                <option value="part_time">Part-time Only</option>
                                <option value="full_time">Full-time Only</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="living_preference">Living Arrangement</label>
                            <select id="living_preference" name="living_preference" class="form-input">
                                <option value="both">Flexible</option>
                                <option value="stay_in">Stay-in Only</option>
                                <option value="stay_out">Stay-out Only</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="salary_min">Minimum Salary (P)</label>
                            <input type="number" id="salary_min" name="salary_min" class="form-input" 
                                   placeholder="e.g., 2500" min="0">
                        </div>
                        
                        <div class="form-group">
                            <label for="salary_max">Maximum Salary (P)</label>
                            <input type="number" id="salary_max" name="salary_max" class="form-input" 
                                   placeholder="e.g., 4500" min="0">
                        </div>
                        
                        <div class="form-group">
                            <label for="employment_status">Current Employment Status</label>
                            <select id="employment_status" name="employment_status" class="form-input">
                                <option value="unemployed">Unemployed</option>
                                <option value="employed">Currently Employed</option>
                                <option value="notice">On Notice Period</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="available_from">Available From</label>
                            <input type="date" id="available_from" name="available_from" class="form-input">
                        </div>
                        
                        <div class="form-group">
                            <label for="work_pattern">Work Pattern Preference</label>
                            <select id="work_pattern" name="work_pattern" class="form-input">
                                <option value="mon_sat">Monday - Saturday</option>
                                <option value="mon_fri_weekends_2in2out">Mon-Fri + 2 weekends in/out</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Jobs I'm Interested In:</label>
                        <div class="results-grid" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 10px; margin-top: 10px;">
                            <?php 
                            $jobTypes = [
                                'nanny' => 'Nanny',
                                'babysitter' => 'Babysitter',
                                'cleaner' => 'Cleaner',
                                'housekeeper' => 'Housekeeper',
                                'yard_cleaner' => 'Yard Cleaner',
                                'elderly_care' => 'Elderly Care',
                                'driver' => 'Driver',
                                'cook' => 'Cook',
                                'tutor' => 'Tutor'
                            ];
                            ?>
                            
                            <?php foreach ($jobTypes as $key => $label): ?>
                            <div style="display: flex; align-items: center; padding: 8px; background: #f8f9fa; border-radius: 6px;">
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

            <div class="dashboard-card">
                <div class="card-content">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4>Ready to save your profile?</h4>
                            <p>Make sure all information is accurate for better matches</p>
                        </div>
                        <div class="action-buttons">
                            <a href="/dashboard/user" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Save Profile</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const jobSeekerCheckbox = document.getElementById('is_job_seeker');
    const jobSeekerSection = document.getElementById('job-seeker-section');
    
    jobSeekerCheckbox.addEventListener('change', () => {
        jobSeekerSection.style.display = jobSeekerCheckbox.checked ? 'block' : 'none';
    });
});
</script>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/app.php';
?>