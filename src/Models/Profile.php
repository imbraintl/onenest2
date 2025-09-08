<?php

namespace App\Models;

use App\Core\Database;

class Profile
{
    public static function create(array $data): string
    {
        $data['notifications_json'] = json_encode($data['notifications_json'] ?? []);
        $data['meta_json'] = json_encode($data['meta_json'] ?? []);
        
        return Database::insert('profiles', $data);
    }

    public static function findByUserId(string $userId): ?array
    {
        $profile = Database::fetch(
            'SELECT * FROM profiles WHERE user_id = ? AND deleted_at IS NULL',
            [$userId]
        );
        
        if ($profile) {
            $profile['notifications_json'] = json_decode($profile['notifications_json'], true);
            $profile['meta_json'] = json_decode($profile['meta_json'], true);
        }
        
        return $profile;
    }

    public static function updateByUserId(string $userId, array $data): bool
    {
        if (isset($data['notifications_json'])) {
            $data['notifications_json'] = json_encode($data['notifications_json']);
        }
        if (isset($data['meta_json'])) {
            $data['meta_json'] = json_encode($data['meta_json']);
        }
        
        return Database::update('profiles', $data, 'user_id = ?', [$userId]);
    }

    public static function getJobSeekerProfile(string $userId): ?array
    {
        return Database::fetch(
            'SELECT p.*, js.*, jp.* 
             FROM profiles p 
             LEFT JOIN job_seekers js ON p.user_id = js.user_id 
             LEFT JOIN job_preferences jp ON js.id = jp.job_seeker_id 
             WHERE p.user_id = ? AND p.deleted_at IS NULL',
            [$userId]
        );
    }
}