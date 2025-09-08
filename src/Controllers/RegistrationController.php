<?php

namespace App\Controllers;

use App\Models\User;
use App\Models\Business;
use App\Models\MarketplaceItem;
use App\Models\Property;
use App\Models\JobSeeker;
use App\Models\Profile;
use App\Core\Database;
use Exception;

class RegistrationController
{
    public function showServiceProviderForm(): void
    {
        include __DIR__ . '/../../views/forms/service-provider-registration.php';
    }

    public function showProductSellerForm(): void
    {
        include __DIR__ . '/../../views/forms/product-seller-registration.php';
    }

    public function showMarketplaceSellerForm(): void
    {
        include __DIR__ . '/../../views/forms/marketplace-seller.php';
    }

    public function showPropertyListingForm(): void
    {
        include __DIR__ . '/../../views/forms/property-listing.php';
    }

    public function showJobSeekerForm(): void
    {
        include __DIR__ . '/../../views/forms/job-seeker-profile.php';
    }

    public function registerServiceProvider(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->showServiceProviderForm();
            return;
        }

        $this->requireAuth();
        $userId = $_SESSION['user_id'];

        // Validate input
        $validation = $this->validateServiceProviderData($_POST);
        if (!empty($validation['errors'])) {
            $errors = $validation['errors'];
            include __DIR__ . '/../../views/forms/service-provider-registration.php';
            return;
        }

        try {
            // Create business
            $businessData = [
                'user_id' => $userId,
                'name' => $_POST['business_name'],
                'type' => 'service_provider',
                'registration_number' => $_POST['registration_number'] ?: null,
                'years_in_operation' => $_POST['years_in_operation'] ?: null,
                'employees_count' => $_POST['employees_count'] ?: null,
                'location_text' => $_POST['location_text'],
                'open_time' => $_POST['open_time'] ?: null,
                'close_time' => $_POST['close_time'] ?: null,
                'service_area_text' => $_POST['service_area'] ?: null,
                'profile_json' => [
                    'description' => $_POST['service_description'],
                    'pricing' => $_POST['pricing_structure'] ?: '',
                    'specialties' => $_POST['specialties'] ?: '',
                    'team_interviews' => isset($_POST['team_interviews'])
                ]
            ];

            $businessId = Business::create($businessData);

            // Create contact
            $this->createContact('App\\Models\\Business', $businessId, [
                'person_name' => $_POST['contact_person'],
                'phones_json' => [$_POST['contact_phone']],
                'email' => $_POST['contact_email'] ?: null,
                'website' => $_POST['website'] ?: null,
                'socials_json' => [
                    'facebook' => $_POST['facebook'] ?: null,
                    'instagram' => $_POST['instagram'] ?: null
                ]
            ]);

            // Handle file uploads
            $this->handleFileUploads('App\\Models\\Business', $businessId, $_FILES);

            // Update user role
            $this->addUserRole($userId, 'business');

            header('Location: /dashboard/business?success=registration');
            exit;

        } catch (Exception $e) {
            error_log('Service provider registration failed: ' . $e->getMessage());
            $errors = ['Registration failed. Please try again.'];
            include __DIR__ . '/../../views/forms/service-provider-registration.php';
        }
    }

    public function registerJobSeeker(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->showJobSeekerForm();
            return;
        }

        $this->requireAuth();
        $userId = $_SESSION['user_id'];

        // Validate input
        $validation = $this->validateJobSeekerData($_POST);
        if (!empty($validation['errors'])) {
            $errors = $validation['errors'];
            include __DIR__ . '/../../views/forms/job-seeker-profile.php';
            return;
        }

        try {
            // Update/create profile
            $profileData = [
                'user_id' => $userId,
                'gender' => $_POST['gender'] ?: null,
                'dob' => $_POST['dob'] ?: null,
                'nationality' => $_POST['nationality'] ?: null,
                'id_number' => $_POST['id_number'] ?: null,
                'whatsapp_phone' => $_POST['whatsapp_phone'],
                'city' => $_POST['city'],
                'area' => $_POST['area'] ?: null,
                'home_village' => $_POST['home_village'],
                'language' => $_POST['language'] ?: 'en'
            ];

            $existingProfile = Profile::findByUserId($userId);
            if ($existingProfile) {
                Profile::updateByUserId($userId, $profileData);
            } else {
                Profile::create($profileData);
            }

            // Create job seeker profile
            $jobSeekerData = [
                'user_id' => $userId,
                'bio' => $_POST['bio'] ?: '',
                'experience_years' => $_POST['experience_years'] ?: 0,
                'skills_json' => $_POST['job_types'] ?? []
            ];

            $jobSeekerId = JobSeeker::create($jobSeekerData);

            // Create job preferences
            $preferencesData = [
                'job_seeker_id' => $jobSeekerId,
                'job_types_json' => $_POST['job_types'] ?? [],
                'availability_type' => $_POST['availability_type'],
                'living_preference' => $_POST['living_preference'],
                'salary_min_bwp' => (int)$_POST['salary_min_bwp'],
                'salary_max_bwp' => (int)$_POST['salary_max_bwp'],
                'current_employment_status' => $_POST['current_employment_status'],
                'available_from' => $_POST['available_from'] ?: null,
                'work_pattern' => $_POST['work_pattern'] ?: null,
                'request_assisted_interview' => isset($_POST['assisted_interview'])
            ];

            JobSeeker::updatePreferences($jobSeekerId, $preferencesData);

            // Handle file uploads
            $this->handleFileUploads('App\\Models\\JobSeeker', $jobSeekerId, $_FILES);

            // Update user role
            $this->addUserRole($userId, 'job_seeker');

            header('Location: /dashboard/job-seeker?success=profile');
            exit;

        } catch (Exception $e) {
            error_log('Job seeker registration failed: ' . $e->getMessage());
            $errors = ['Registration failed. Please try again.'];
            include __DIR__ . '/../../views/forms/job-seeker-profile.php';
        }
    }

    private function validateServiceProviderData(array $data): array
    {
        $errors = [];

        if (empty($data['business_name'])) $errors[] = 'Business name is required';
        if (empty($data['location_text'])) $errors[] = 'Business location is required';
        if (empty($data['contact_person'])) $errors[] = 'Contact person name is required';
        if (empty($data['contact_phone'])) $errors[] = 'Contact phone is required';
        if (empty($data['service_description'])) $errors[] = 'Service description is required';

        // Validate time format if provided
        if (!empty($data['open_time']) && !empty($data['close_time'])) {
            if (strtotime($data['open_time']) >= strtotime($data['close_time'])) {
                $errors[] = 'Closing time must be after opening time';
            }
        }

        return ['errors' => $errors];
    }

    private function validateJobSeekerData(array $data): array
    {
        $errors = [];

        if (empty($data['full_name'])) $errors[] = 'Full name is required';
        if (empty($data['whatsapp_phone'])) $errors[] = 'Phone number is required';
        if (empty($data['city'])) $errors[] = 'Current city is required';
        if (empty($data['home_village'])) $errors[] = 'Home village is required';
        if (empty($data['availability_type'])) $errors[] = 'Employment type preference is required';
        if (empty($data['living_preference'])) $errors[] = 'Living arrangement preference is required';
        if (empty($data['salary_min_bwp'])) $errors[] = 'Minimum salary is required';
        if (empty($data['salary_max_bwp'])) $errors[] = 'Maximum salary is required';
        if (empty($data['current_employment_status'])) $errors[] = 'Current employment status is required';
        if (empty($data['available_from'])) $errors[] = 'Available start date is required';
        if (empty($data['job_types'])) $errors[] = 'Please select at least one job type you are interested in';

        // Validate salary range
        if (!empty($data['salary_min_bwp']) && !empty($data['salary_max_bwp'])) {
            if ((int)$data['salary_min_bwp'] > (int)$data['salary_max_bwp']) {
                $errors[] = 'Minimum salary cannot be higher than maximum salary';
            }
        }

        return ['errors' => $errors];
    }

    private function createContact(string $type, string $id, array $contactData): void
    {
        $contactData['contactable_type'] = $type;
        $contactData['contactable_id'] = $id;
        $contactData['phones_json'] = json_encode($contactData['phones_json']);
        $contactData['socials_json'] = json_encode($contactData['socials_json']);

        Database::insert('contacts', $contactData);
    }

    private function handleFileUploads(string $ownerType, string $ownerId, array $files): void
    {
        $uploadDir = __DIR__ . '/../../uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        foreach ($files as $fieldName => $fileData) {
            if (is_array($fileData['name'])) {
                // Multiple files
                for ($i = 0; $i < count($fileData['name']); $i++) {
                    if ($fileData['error'][$i] === UPLOAD_ERR_OK) {
                        $this->saveFile($ownerType, $ownerId, [
                            'name' => $fileData['name'][$i],
                            'tmp_name' => $fileData['tmp_name'][$i],
                            'size' => $fileData['size'][$i],
                            'type' => $fileData['type'][$i]
                        ], $fieldName);
                    }
                }
            } else {
                // Single file
                if ($fileData['error'] === UPLOAD_ERR_OK) {
                    $this->saveFile($ownerType, $ownerId, $fileData, $fieldName);
                }
            }
        }
    }

    private function saveFile(string $ownerType, string $ownerId, array $fileData, string $fieldName): void
    {
        $uploadDir = __DIR__ . '/../../uploads/';
        $fileName = uniqid() . '_' . $fileData['name'];
        $filePath = $uploadDir . $fileName;

        if (move_uploaded_file($fileData['tmp_name'], $filePath)) {
            Database::insert('files', [
                'owner_type' => $ownerType,
                'owner_id' => $ownerId,
                'kind' => $this->getFileKind($fieldName),
                'path' => 'uploads/' . $fileName,
                'original_name' => $fileData['name'],
                'mime' => $fileData['type'],
                'size_bytes' => $fileData['size']
            ]);
        }
    }

    private function getFileKind(string $fieldName): string
    {
        $kindMap = [
            'business_license' => 'license',
            'company_profile' => 'brochure',
            'portfolio' => 'portfolio',
            'promotional' => 'other',
            'product_photos' => 'product_photo',
            'exterior_photos' => 'property_photo',
            'bedroom_photos' => 'property_photo',
            'bathroom_photos' => 'property_photo',
            'kitchen_photos' => 'property_photo',
            'yard_photos' => 'property_photo',
            'feature_photos' => 'property_photo',
            'portfolio_photos' => 'portfolio',
            'resume' => 'resume',
            'id_document' => 'id_card',
            'certifications' => 'other'
        ];

        return $kindMap[$fieldName] ?? 'other';
    }

    private function addUserRole(string $userId, string $role): void
    {
        $user = User::findById($userId);
        $roles = json_decode($user['role_flags'], true) ?? [];
        
        if (!in_array($role, $roles)) {
            $roles[] = $role;
            Database::update('users', ['role_flags' => json_encode($roles)], 'id = ?', [$userId]);
            $_SESSION['role_flags'] = $roles;
        }
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