<?php

namespace App\Controllers;

use App\Models\Business;
use App\Models\MarketplaceItem;
use App\Models\Property;
use App\Models\JobPost;
use Exception;

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

    public function howItWorks(): void
    {
        include __DIR__ . '/../../views/how-it-works.php';
    }

    public function businesses(): void
    {
        try {
            $businesses = Business::getAll(['status' => 'approved', 'limit' => 20]);
        } catch (Exception $e) {
            $businesses = [];
        }
        include __DIR__ . '/../../views/businesses.php';
    }

    public function marketplace(): void
    {
        try {
            $items = MarketplaceItem::getAll(['status' => 'approved', 'limit' => 20]);
        } catch (Exception $e) {
            $items = [];
        }
        include __DIR__ . '/../../views/marketplace.php';
    }

    public function properties(): void
    {
        try {
            $properties = Property::getAll(['status' => 'approved', 'limit' => 20]);
        } catch (Exception $e) {
            $properties = [];
        }
        include __DIR__ . '/../../views/properties.php';
    }

    public function jobs(): void
    {
        try {
            $jobs = JobPost::getAll(['status' => 'open', 'limit' => 20]);
        } catch (Exception $e) {
            $jobs = [];
        }
        include __DIR__ . '/../../views/jobs.php';
    }
}