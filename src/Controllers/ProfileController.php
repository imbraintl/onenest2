<?php

namespace App\Controllers;

use App\Models\User;
use App\Models\Profile;
use App\Models\JobSeeker;

class ProfileController
{
    public function showProfile(): void
    {
        $this->requireAuth();
        
        $userId = $_SESSION['user_id'];
        $user = User::findById($userId);
        $profile = Profile::findByUserId($userId);
        
        include __DIR__ . '/../../views/profile/edit.php';
    }

    public function updateProfile(): void
    {
        $this->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->showProfile();
            return;
        }

        $userId = $_SESSION['user_id'];
        $data = $this->validateProfileData($_POST);
        
        if (!empty($data['errors'])) {
            $errors = $data['errors'];
            include __DIR__ . '/../../views/profile/edit.php';
            return;
        }

        // Update or create profile
        $existingProfile = Profile::findByUserId($userId);
        
        if ($existingProfile) {
            Profile::updateByUserId($userId, $data['profile']);
        } else {
            $data['profile']['user_id'] = $userId;
            Profile::create($data['profile']);
        }

        // Handle job seeker data if provided
        if (!empty($data['job_seeker'])) {
            $this->handleJobSeekerData($userId, $data['job_seeker']);
        }

        header('Location: /profile?success=1');
        exit;
    }

    private function validateProfileData(array $input): array
    {
        $errors = [];
        $profile = [];
        $jobSeeker = [];

        // Basic profile validation
        if (!empty($input['gender']) && !in_array($input['gender'], ['male', 'female', 'other'])) {
            $errors[] = 'Invalid gender selection';
        } else {
            $profile['gender'] = $input['gender'] ?? null;
        }

        if (!empty($input['dob'])) {
            $dob = date('Y-m-d', strtotime($input['dob']));
            if ($dob === '1970-01-01') {
                $errors[] = 'Invalid date of birth';
            } else {
                $profile['dob'] = $dob;
            }
        }

        $profile['nationality'] = $input['nationality'] ?? null;
        $profile['id_number'] = $input['id_number'] ?? null;
        $profile['whatsapp_phone'] = $input['whatsapp_phone'] ?? null;
        $profile['city'] = $input['city'] ?? null;
        $profile['area'] = $input['area'] ?? null;
        $profile['home_village'] = $input['home_village'] ?? null;
        $profile['language'] = $input['language'] ?? null;

        // Handle notifications preferences
        $notifications = [];
        $notificationTypes = [
            'restaurants', 'events', 'tutoring', 'nanny_training', 
            'health_safety', 'local_sellers'
        ];
        
        foreach ($notificationTypes as $type) {
            $notifications[$type] = isset($input['notifications'][$type]);
        }
        $profile['notifications_json'] = $notifications;

        // Handle job seeker specific data
        if (isset($input['is_job_seeker']) && $input['is_job_seeker']) {
            $jobSeeker['job_types'] = $input['job_types'] ?? [];
            $jobSeeker['availability_type'] = $input['availability_type'] ?? 'both';
            $jobSeeker['living_preference'] = $input['living_preference'] ?? 'both';
            $jobSeeker['salary_min_bwp'] = !empty($input['salary_min']) ? (int)$input['salary_min'] : null;
            $jobSeeker['salary_max_bwp'] = !empty($input['salary_max']) ? (int)$input['salary_max'] : null;
            $jobSeeker['current_employment_status'] = $input['employment_status'] ?? null;
            $jobSeeker['available_from'] = !empty($input['available_from']) ? date('Y-m-d', strtotime($input['available_from'])) : null;
            $jobSeeker['work_pattern'] = $input['work_pattern'] ?? null;
            $jobSeeker['request_assisted_interview'] = isset($input['assisted_interview']);
        }

        return [
            'errors' => $errors,
            'profile' => $profile,
            'job_seeker' => $jobSeeker
        ];
    }

    private function handleJobSeekerData(string $userId, array $jobSeekerData): void
    {
        // Check if job seeker profile exists
        $existingJobSeeker = Database::fetch(
            'SELECT id FROM job_seekers WHERE user_id = ? AND deleted_at IS NULL',
            [$userId]
        );

        if ($existingJobSeeker) {
            $jobSeekerId = $existingJobSeeker['id'];
        } else {
            // Create job seeker profile
            $jobSeekerId = JobSeeker::create([
                'user_id' => $userId,
                'bio' => $jobSeekerData['bio'] ?? '',
                'skills_json' => $jobSeekerData['skills'] ?? [],
                'experience_years' => $jobSeekerData['experience_years'] ?? 0
            ]);
        }

        // Update job preferences
        JobSeeker::updatePreferences($jobSeekerId, $jobSeekerData);
    }

    private function requireAuth(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }
    }
}