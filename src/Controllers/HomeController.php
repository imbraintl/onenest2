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
        $featuredBusinesses = Business::getAll(['status' => 'approved', 'limit' => 6]);
        $featuredItems = MarketplaceItem::getAll(['status' => 'approved', 'limit' => 6]);
        $featuredProperties = Property::getAll(['status' => 'approved', 'limit' => 6]);
        $featuredJobs = JobPost::getAll(['status' => 'open', 'limit' => 6]);

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
}