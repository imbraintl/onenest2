<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$title = 'My Dashboard - OneNest';
$topBarMessage = 'Welcome back, ' . ($_SESSION['user_name'] ?? 'User') . '!';

ob_start();
?>

<!-- Mobile Sidebar Toggle -->
<button class="mobile-sidebar-toggle" title="Toggle sidebar menu">
    <i data-lucide="menu"></i>
</button>

<!-- Dashboard Layout -->
<div class="dashboard-layout">
    <!-- Sidebar -->
    <aside class="dashboard-sidebar">
        <div class="sidebar-header">
            <div class="logo">
                <i data-lucide="heart"></i>
                My OneNest
            </div>
        </div>
        
        <div class="sidebar-user-info">
            <div class="user-avatar"><?= strtoupper(substr($_SESSION['user_name'] ?? 'U', 0, 2)) ?></div>
            <div class="user-details">
                <h4><?= htmlspecialchars($_SESSION['user_name'] ?? 'User') ?></h4>
                <p><?= htmlspecialchars($_SESSION['user_email'] ?? '') ?></p>
            </div>
        </div>

        <nav class="sidebar-nav">
            <div class="nav-section">
                <div class="nav-section-title">My Account</div>
                <a href="#overview" class="nav-item active">
                    <i data-lucide="home"></i>
                    Overview
                </a>
                <a href="#profile" class="nav-item">
                    <i data-lucide="user"></i>
                    My Profile
                </a>
                <a href="#family" class="nav-item">
                    <i data-lucide="users"></i>
                    Family Details
                </a>
            </div>
            
            <div class="nav-section">
                <div class="nav-section-title">Services</div>
                <a href="#hired-services" class="nav-item">
                    <i data-lucide="user-check"></i>
                    Hired Services
                </a>
                <a href="#favorites" class="nav-item">
                    <i data-lucide="heart"></i>
                    Saved Providers
                </a>
                <a href="#requests" class="nav-item">
                    <i data-lucide="clock"></i>
                    Service Requests
                </a>
            </div>

            <div class="nav-section">
                <div class="nav-section-title">Shopping</div>
                <a href="#cart" class="nav-item">
                    <i data-lucide="shopping-cart"></i>
                    My Cart
                    <span class="badge badge-info" style="margin-left: auto;"><?= count($cartItems ?? []) ?></span>
                </a>
                <a href="#orders" class="nav-item">
                    <i data-lucide="package"></i>
                    My Orders
                </a>
                <a href="#wishlist" class="nav-item">
                    <i data-lucide="bookmark"></i>
                    Wishlist
                </a>
            </div>

            <div class="nav-section">
                <div class="nav-section-title">Communication</div>
                <a href="#messages" class="nav-item">
                    <i data-lucide="message-circle"></i>
                    Messages
                    <span class="badge badge-warning" style="margin-left: auto;">2</span>
                </a>
                <a href="#notifications" class="nav-item">
                    <i data-lucide="bell"></i>
                    Notifications
                </a>
            </div>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="dashboard-main">
        <div class="dashboard-header">
            <h1 class="dashboard-title">Welcome back, <?= htmlspecialchars($_SESSION['user_name'] ?? 'User') ?>!</h1>
            <p class="dashboard-subtitle">Here's what's happening with your OneNest account</p>
        </div>

        <!-- Quick Stats -->
        <div class="stat-cards">
            <div class="stat-card">
                <div class="stat-card-header">
                    <div class="stat-icon primary">
                        <i data-lucide="user-check"></i>
                    </div>
                </div>
                <div class="stat-value">2</div>
                <div class="stat-label">Active Services</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-card-header">
                    <div class="stat-icon success">
                        <i data-lucide="shopping-bag"></i>
                    </div>
                </div>
                <div class="stat-value"><?= count($cartItems ?? []) ?></div>
                <div class="stat-label">Items in Cart</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-card-header">
                    <div class="stat-icon warning">
                        <i data-lucide="message-circle"></i>
                    </div>
                </div>
                <div class="stat-value">3</div>
                <div class="stat-label">Unread Messages</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-card-header">
                    <div class="stat-icon info">
                        <i data-lucide="heart"></i>
                    </div>
                </div>
                <div class="stat-value"><?= count($savedItems ?? []) ?></div>
                <div class="stat-label">Saved Items</div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="dashboard-card">
            <div class="card-header">
                <h3 class="card-title">Quick Actions</h3>
            </div>
            <div class="card-content">
                <div class="results-grid" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
                    <a href="/jobs" class="btn btn-accent" style="padding: 20px; height: auto; flex-direction: column; gap: 10px; text-decoration: none;">
                        <i data-lucide="search" style="width: 24px; height: 24px;"></i>
                        <span>Find Services</span>
                    </a>
                    <a href="/marketplace" class="btn btn-primary" style="padding: 20px; height: auto; flex-direction: column; gap: 10px; text-decoration: none;">
                        <i data-lucide="shopping-bag" style="width: 24px; height: 24px;"></i>
                        <span>Shop Marketplace</span>
                    </a>
                    <a href="/businesses" class="btn btn-secondary" style="padding: 20px; height: auto; flex-direction: column; gap: 10px; text-decoration: none;">
                        <i data-lucide="building" style="width: 24px; height: 24px;"></i>
                        <span>Find Businesses</span>
                    </a>
                    <a href="/properties" class="btn btn-success" style="padding: 20px; height: auto; flex-direction: column; gap: 10px; text-decoration: none;">
                        <i data-lucide="home" style="width: 24px; height: 24px;"></i>
                        <span>Browse Properties</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="dashboard-card">
            <div class="card-header">
                <h3 class="card-title">Recent Activity</h3>
                <button class="btn btn-secondary btn-small">View All</button>
            </div>
            <div class="card-content">
                <div class="activity-list">
                    <div class="activity-item d-flex align-items-center mb-3">
                        <div class="stat-icon success" style="width: 40px; height: 40px; margin-right: 15px;">
                            <i data-lucide="shopping-cart"></i>
                        </div>
                        <div class="flex-1">
                            <strong>Item added to cart</strong><br>
                            <small>Baby Stroller - P850</small>
                        </div>
                        <div class="text-right">
                            <small>2 hours ago</small>
                        </div>
                    </div>
                    <div class="activity-item d-flex align-items-center mb-3">
                        <div class="stat-icon info" style="width: 40px; height: 40px; margin-right: 15px;">
                            <i data-lucide="heart"></i>
                        </div>
                        <div class="flex-1">
                            <strong>Service provider saved</strong><br>
                            <small>Mary Tebogo - Professional Nanny</small>
                        </div>
                        <div class="text-right">
                            <small>1 day ago</small>
                        </div>
                    </div>
                    <div class="activity-item d-flex align-items-center mb-3">
                        <div class="stat-icon primary" style="width: 40px; height: 40px; margin-right: 15px;">
                            <i data-lucide="user-plus"></i>
                        </div>
                        <div class="flex-1">
                            <strong>Account created</strong><br>
                            <small>Welcome to OneNest!</small>
                        </div>
                        <div class="text-right">
                            <small>3 days ago</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<?php
$additionalJS = '
<script>
// Mobile sidebar toggle
const sidebarToggle = document.querySelector(".mobile-sidebar-toggle");
const sidebar = document.querySelector(".dashboard-sidebar");

sidebarToggle?.addEventListener("click", () => {
    sidebar.classList.toggle("mobile-active");
});

// Close sidebar when clicking outside on mobile
document.addEventListener("click", (e) => {
    if (window.innerWidth <= 992 && 
        !sidebar.contains(e.target) && 
        !sidebarToggle.contains(e.target)) {
        sidebar.classList.remove("mobile-active");
    }
});
</script>
';

$content = ob_get_clean();
include __DIR__ . '/../layouts/app.php';
?>