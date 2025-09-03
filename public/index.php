<?php

// Check if vendor directory exists
$vendorPath = file_exists(__DIR__ . '/../vendor/autoload.php') 
    ? __DIR__ . '/../vendor/autoload.php'
    : __DIR__ . '/../../vendor/autoload.php'; // For root index.php setup

if (!file_exists($vendorPath)) {
    die('Composer dependencies not installed. Please run: composer install');
}

require_once $vendorPath;

// Load environment variables
if (class_exists('Dotenv\Dotenv')) {
    $envPath = file_exists(__DIR__ . '/..') && file_exists(__DIR__ . '/../.env')
        ? __DIR__ . '/..'
        : __DIR__ . '/../..'; // For root index.php setup
    
    $dotenv = Dotenv\Dotenv::createImmutable($envPath);
    $dotenv->safeLoad();
}

// Set timezone
date_default_timezone_set($_ENV['APP_TIMEZONE'] ?? 'Africa/Gaborone');

// Start session
session_start();

// Error handling
if ($_ENV['APP_DEBUG'] ?? false) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

use App\Core\Router;
use App\Controllers\HomeController;
use App\Controllers\AuthController;
use App\Controllers\DashboardController;

$router = new Router();

// Public routes
$router->get('/', [HomeController::class, 'index']);
$router->get('/home', [HomeController::class, 'index']);
$router->get('/about', [HomeController::class, 'about']);
$router->get('/contact', [HomeController::class, 'contact']);
$router->get('/how-it-works', [HomeController::class, 'howItWorks']);

// Auth routes
$router->get('/login', [AuthController::class, 'showLogin']);
$router->post('/login', [AuthController::class, 'login']);
$router->get('/register', [AuthController::class, 'showRegister']);
$router->post('/register', [AuthController::class, 'register']);
$router->get('/logout', [AuthController::class, 'logout']);

// Dashboard routes
$router->get('/dashboard/user', [DashboardController::class, 'userDashboard']);
$router->get('/dashboard/business', [DashboardController::class, 'businessDashboard']);
$router->get('/dashboard/recruiter', [DashboardController::class, 'recruiterDashboard']);
$router->get('/dashboard/job-seeker', [DashboardController::class, 'jobSeekerDashboard']);
$router->get('/dashboard/property-owner', [DashboardController::class, 'propertyOwnerDashboard']);
$router->get('/dashboard/property-agent', [DashboardController::class, 'propertyAgentDashboard']);
$router->get('/dashboard/seller', [DashboardController::class, 'sellerDashboard']);
$router->get('/dashboard/admin', [DashboardController::class, 'adminDashboard']);

// Public listing routes (using HomeController for now)
$router->get('/businesses', [HomeController::class, 'businesses']);
$router->get('/marketplace', [HomeController::class, 'marketplace']);
$router->get('/properties', [HomeController::class, 'properties']);
$router->get('/jobs', [HomeController::class, 'jobs']);

try {
    $router->dispatch();
} catch (Exception $e) {
    if ($_ENV['APP_DEBUG'] ?? false) {
        echo '<pre>Error: ' . $e->getMessage() . '</pre>';
        echo '<pre>' . $e->getTraceAsString() . '</pre>';
    } else {
        http_response_code(500);
        include __DIR__ . '/../views/errors/500.php';
    }
}