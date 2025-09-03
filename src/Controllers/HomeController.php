<?php

namespace App\Controllers;

use App\Models\Business;
use App\Models\MarketplaceItem;
use App\Models\Property;
use App\Models\JobPost;

class HomeController
{
    public function index(): void
    {
        // Get featured content for homepage
        $featuredBusinesses = [];
        $featuredItems = [];
        
        // Try to get featured content, but don't fail if database isn't set up yet
        try {
            $featuredBusinesses = Business::getAll(['status' => 'approved', 'limit' => 6]);
            $featuredItems = MarketplaceItem::getAll(['status' => 'approved', 'limit' => 6]);
        } catch (Exception $e) {
            // Database not set up yet, use empty arrays
        }

        include __DIR__ . '/../../views/home.php';
    }

    public function about(): void
    {
        include __DIR__ . '/../../views/about.php';
    }

    public function contact(): void
    {
        include __DIR__ . '/../../views/contact.php';
    }

    public function howItWorks(): void
    {
        include __DIR__ . '/../../views/how-it-works.php';
    }

    public function businesses(): void
    {
        $businesses = Business::getAll(['status' => 'approved', 'limit' => 20]);
        include __DIR__ . '/../../views/businesses.php';
    }

    public function marketplace(): void
    {
        $items = MarketplaceItem::getAll(['status' => 'approved', 'limit' => 20]);
        include __DIR__ . '/../../views/marketplace.php';
    }

    public function properties(): void
    {
        $properties = Property::getAll(['status' => 'approved', 'limit' => 20]);
        include __DIR__ . '/../../views/properties.php';
    }

    public function jobs(): void
    {
        $jobs = JobPost::getAll(['status' => 'open', 'limit' => 20]);
        include __DIR__ . '/../../views/jobs.php';
    }
}