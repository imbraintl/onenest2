<?php

require_once __DIR__ . '/../vendor/autoload.php';

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

// Set timezone
date_default_timezone_set($_ENV['APP_TIMEZONE'] ?? 'Africa/Gaborone');

use App\Core\Router;
use App\Controllers\HomeController;
use App\Controllers\AuthController;
use App\Controllers\DashboardController;
use App\Controllers\BusinessController;
use App\Controllers\MarketplaceController;
use App\Controllers\PropertyController;
use App\Controllers\JobController;

$router = new Router();

// Public routes
$router->get('/', [HomeController::class, 'index']);
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

// Business routes
$router->get('/businesses', [BusinessController::class, 'index']);
$router->get('/businesses/{id}', [BusinessController::class, 'show']);

// Marketplace routes
$router->get('/marketplace', [MarketplaceController::class, 'index']);
$router->get('/marketplace/{id}', [MarketplaceController::class, 'show']);

// Property routes
$router->get('/properties', [PropertyController::class, 'index']);
$router->get('/properties/{id}', [PropertyController::class, 'show']);

// Job routes
$router->get('/jobs', [JobController::class, 'index']);
$router->get('/jobs/{id}', [JobController::class, 'show']);

// API routes
$router->get('/api/businesses', [BusinessController::class, 'apiIndex']);
$router->get('/api/marketplace', [MarketplaceController::class, 'apiIndex']);
$router->get('/api/properties', [PropertyController::class, 'apiIndex']);
$router->get('/api/jobs', [JobController::class, 'apiIndex']);

$router->dispatch();