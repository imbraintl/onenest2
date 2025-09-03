<?php
$title = 'Hire Professional - Mummycare';

ob_start();
?>
<section id="page-hire" class="page active">
    <div class="page-header">
        <h1>Hire a Professional</h1>
        <p>Find trusted and skilled professionals across Botswana for any job, big or small.</p>
    </div>

    <div class="container">
        <div class="filter-bar">
            <div class="filter-group">
                <input type="text" placeholder="e.g., Nanny, Cleaner, Driver">
            </div>
            <div class="filter-group">
                <select>
                    <option>All Job Types</option>
                    <option>Nanny</option>
                    <option>Cleaner</option>
                    <option>Babysitter</option>
                    <option>Driver</option>
                    <option>Housekeeper</option>
                    <option>Elderly Care</option>
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
                <select>
                    <option>All Employment Types</option>
                    <option>Full-time</option>
                    <option>Part-time</option>
                    <option>Temporary</option>
                </select>
            </div>
        </div>
        
        <div class="results-grid">
            <?php if (!empty($jobs)): ?>
                <?php foreach ($jobs as $job): ?>
                <div class="card">
                    <img src="https://images.pexels.com/photos/1181396/pexels-photo-1181396.jpeg?auto=compress&cs=tinysrgb&w=800" alt="Job">
                    <div class="card-content">
                        <span class="card-tag" style="background-color: var(--secondary-color);">
                            <?= ucfirst(str_replace('_', ' ', $job['employment_type'])) ?>
                        </span>
                        <h4 class="card-title"><?= htmlspecialchars($job['title']) ?></h4>
                        <p class="card-subtitle"><?= htmlspecialchars($job['city']) ?> • by <?= htmlspecialchars($job['recruiter_name']) ?></p>
                        <?php if ($job['salary_min_bwp'] || $job['salary_max_bwp']): ?>
                        <div style="margin: 10px 0;">
                            <strong>Salary: </strong>
                            <?php if ($job['salary_min_bwp'] && $job['salary_max_bwp']): ?>
                                P<?= number_format($job['salary_min_bwp']) ?> - P<?= number_format($job['salary_max_bwp']) ?>
                            <?php elseif ($job['salary_min_bwp']): ?>
                                From P<?= number_format($job['salary_min_bwp']) ?>
                            <?php else: ?>
                                Up to P<?= number_format($job['salary_max_bwp']) ?>
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>
                        <div class="card-footer">
                            <div class="rating">
                                <i data-lucide="calendar" style="width: 16px; height: 16px;"></i>
                                <span><?= date('M j', strtotime($job['created_at'])) ?></span>
                            </div>
                            <a class="btn btn-secondary" href="/jobs/<?= $job['id'] ?>">Apply Now</a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <!-- Sample jobs for demo -->
                <div class="card">
                    <img src="https://images.pexels.com/photos/1181396/pexels-photo-1181396.jpeg?auto=compress&cs=tinysrgb&w=800" alt="Nanny Job">
                    <div class="card-content">
                        <span class="card-tag" style="background-color: var(--secondary-color); color: var(--primary-color);">Full-time</span>
                        <h4 class="card-title">Live-in Nanny</h4>
                        <p class="card-subtitle">Gaborone • Family of 2 children</p>
                        <div style="margin: 10px 0;">
                            <strong>Salary: </strong>P3,000 - P4,500/month
                        </div>
                        <div class="card-footer">
                            <div class="rating">
                                <i data-lucide="calendar" style="width: 16px; height: 16px;"></i>
                                <span>Jan 15</span>
                            </div>
                            <a class="btn btn-secondary" style="padding: 8px 16px;">Apply Now</a>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <img src="https://images.pexels.com/photos/1181396/pexels-photo-1181396.jpeg?auto=compress&cs=tinysrgb&w=800" alt="Cleaner Job">
                    <div class="card-content">
                        <span class="card-tag" style="background-color: var(--secondary-color); color: var(--primary-color);">Part-time</span>
                        <h4 class="card-title">Weekend Cleaner</h4>
                        <p class="card-subtitle">Gaborone • Professional family</p>
                        <div style="margin: 10px 0;">
                            <strong>Rate: </strong>P150/hour
                        </div>
                        <div class="card-footer">
                            <div class="rating">
                                <i data-lucide="calendar" style="width: 16px; height: 16px;"></i>
                                <span>Jan 14</span>
                            </div>
                            <a class="btn btn-secondary" style="padding: 8px 16px;">Apply Now</a>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <img src="https://images.pexels.com/photos/1181396/pexels-photo-1181396.jpeg?auto=compress&cs=tinysrgb&w=800" alt="Driver Job">
                    <div class="card-content">
                        <span class="card-tag" style="background-color: var(--secondary-color); color: var(--primary-color);">Full-time</span>
                        <h4 class="card-title">Family Driver</h4>
                        <p class="card-subtitle">Gaborone • School runs & errands</p>
                        <div style="margin: 10px 0;">
                            <strong>Salary: </strong>P2,800/month
                        </div>
                        <div class="card-footer">
                            <div class="rating">
                                <i data-lucide="calendar" style="width: 16px; height: 16px;"></i>
                                <span>Jan 13</span>
                            </div>
                            <a class="btn btn-secondary" style="padding: 8px 16px;">Apply Now</a>
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