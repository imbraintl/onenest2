<?php

namespace App\Models;

use App\Core\Database;

class JobSeeker
{
    public static function create(array $data): string
    {
        $data['skills_json'] = json_encode($data['skills_json'] ?? []);
        
        return Database::insert('job_seekers', $data);
    }

    public static function findByUserId(string $userId): ?array
    {
        $jobSeeker = Database::fetch(
            'SELECT js.*, jp.* 
             FROM job_seekers js 
             LEFT JOIN job_preferences jp ON js.id = jp.job_seeker_id 
             WHERE js.user_id = ? AND js.deleted_at IS NULL',
            [$userId]
        );
        
        if ($jobSeeker) {
            $jobSeeker['skills_json'] = json_decode($jobSeeker['skills_json'], true);
            $jobSeeker['job_types_json'] = json_decode($jobSeeker['job_types_json'], true);
            $jobSeeker['tags'] = json_decode($jobSeeker['tags'], true);
        }
        
        return $jobSeeker;
    }

    public static function updatePreferences(string $jobSeekerId, array $data): bool
    {
        // Handle JSON fields
        if (isset($data['job_types_json'])) {
            $data['job_types_json'] = json_encode($data['job_types_json']);
        }
        if (isset($data['tags'])) {
            $data['tags'] = json_encode($data['tags']);
        }
        
        // Check if preferences exist
        $existing = Database::fetch(
            'SELECT id FROM job_preferences WHERE job_seeker_id = ?',
            [$jobSeekerId]
        );
        
        if ($existing) {
            return Database::update('job_preferences', $data, 'job_seeker_id = ?', [$jobSeekerId]);
        } else {
            $data['job_seeker_id'] = $jobSeekerId;
            Database::insert('job_preferences', $data);
            return true;
        }
    }

    public static function getApplications(string $jobSeekerId): array
    {
        return Database::fetchAll(
            'SELECT ja.*, jp.title, jp.city, jp.salary_min_bwp, jp.salary_max_bwp, 
                    u.name as recruiter_name, b.name as business_name 
             FROM job_applications ja 
             JOIN job_posts jp ON ja.job_post_id = jp.id 
             JOIN users u ON jp.user_id = u.id 
             LEFT JOIN businesses b ON jp.business_id = b.id 
             WHERE ja.job_seeker_id = ? AND ja.deleted_at IS NULL 
             ORDER BY ja.created_at DESC',
            [$jobSeekerId]
        );
    }

    public static function applyToJob(string $jobSeekerId, string $jobPostId, string $coverNote = ''): string
    {
        return Database::insert('job_applications', [
            'job_post_id' => $jobPostId,
            'job_seeker_id' => $jobSeekerId,
            'cover_note' => $coverNote,
            'status' => 'submitted'
        ]);
    }
}