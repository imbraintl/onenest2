<?php

namespace App\Controllers;

use App\Models\User;
use App\Models\Business;
use App\Models\MarketplaceItem;
use App\Models\Property;
use App\Models\JobPost;
use App\Core\Database;

class DashboardController
{
    public function userDashboard(): void
    {
        $this->requireAuth();
        
        $userId = $_SESSION['user_id'];
        $user = User::findById($userId);
        
        // Get user's saved items
        $savedItems = Database::fetchAll(
            'SELECT * FROM saved_items WHERE user_id = ? AND is_cart = 0',
            [$userId]
        );
        
        // Get cart items
        $cartItems = Database::fetchAll(
            'SELECT * FROM saved_items WHERE user_id = ? AND is_cart = 1',
            [$userId]
        );

        include __DIR__ . '/../../views/dashboards/user.php';
    }

    public function businessDashboard(): void
    {
        $this->requireAuth(['business']);
        
        $userId = $_SESSION['user_id'];
        $businesses = Business::getAll(['user_id' => $userId]);
        
        include __DIR__ . '/../../views/dashboards/business.php';
    }

    public function recruiterDashboard(): void
    {
        $this->requireAuth(['recruiter']);
        
        $userId = $_SESSION['user_id'];
        $jobPosts = JobPost::getAll(['user_id' => $userId]);
        
        include __DIR__ . '/../../views/dashboards/recruiter.php';
    }

    public function jobSeekerDashboard(): void
    {
        $this->requireAuth(['job_seeker']);
        
        $userId = $_SESSION['user_id'];
        
        // Get job seeker profile
        $jobSeeker = Database::fetch(
            'SELECT js.*, jp.* FROM job_seekers js 
             LEFT JOIN job_preferences jp ON js.id = jp.job_seeker_id 
             WHERE js.user_id = ?',
            [$userId]
        );
        
        // Get applications
        $applications = Database::fetchAll(
            'SELECT ja.*, jp.title, jp.city, u.name as recruiter_name 
             FROM job_applications ja 
             JOIN job_posts jp ON ja.job_post_id = jp.id 
             JOIN users u ON jp.user_id = u.id 
             WHERE ja.job_seeker_id = ? 
             ORDER BY ja.created_at DESC',
            [$jobSeeker['id'] ?? '']
        );

        include __DIR__ . '/../../views/dashboards/job-seeker.php';
    }

    public function propertyOwnerDashboard(): void
    {
        $this->requireAuth(['property_owner']);
        
        $userId = $_SESSION['user_id'];
        $properties = Property::getAll(['user_id' => $userId]);
        
        include __DIR__ . '/../../views/dashboards/property-owner.php';
    }

    public function propertyAgentDashboard(): void
    {
        $this->requireAuth(['property_agent']);
        
        $userId = $_SESSION['user_id'];
        
        // Get agent's accessed properties
        $accessedProperties = Database::fetchAll(
            'SELECT p.*, u.name as owner_name 
             FROM properties p 
             JOIN users u ON p.user_id = u.id 
             WHERE p.status = "approved" 
             ORDER BY p.created_at DESC 
             LIMIT 20'
        );

        include __DIR__ . '/../../views/dashboards/property-agent.php';
    }

    public function sellerDashboard(): void
    {
        $this->requireAuth(['seller']);
        
        $userId = $_SESSION['user_id'];
        $items = MarketplaceItem::getAll(['user_id' => $userId]);
        
        include __DIR__ . '/../../views/dashboards/seller.php';
    }

    public function adminDashboard(): void
    {
        $this->requireAuth(['admin']);
        
        // Get statistics
        $stats = [
            'total_users' => Database::fetch('SELECT COUNT(*) as count FROM users WHERE deleted_at IS NULL')['count'],
            'total_businesses' => Database::fetch('SELECT COUNT(*) as count FROM businesses WHERE deleted_at IS NULL')['count'],
            'pending_approvals' => Database::fetch('SELECT COUNT(*) as count FROM businesses WHERE status = "pending"')['count'],
            'total_properties' => Database::fetch('SELECT COUNT(*) as count FROM properties WHERE deleted_at IS NULL')['count'],
        ];

        include __DIR__ . '/../../views/dashboards/admin.php';
    }

    private function requireAuth(array $requiredRoles = []): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        if (!empty($requiredRoles)) {
            $userRoles = $_SESSION['role_flags'] ?? [];
            $hasRequiredRole = !empty(array_intersect($requiredRoles, $userRoles));
            
            if (!$hasRequiredRole) {
                http_response_code(403);
                include __DIR__ . '/../../views/errors/403.php';
                exit;
            }
        }
    }
}