<?php

namespace App\Models;

use App\Core\Database;

class JobPost
{
    public static function create(array $data): string
    {
        $data['duties_json'] = json_encode($data['duties_json'] ?? []);
        $data['requirements_json'] = json_encode($data['requirements_json'] ?? []);
        
        return Database::insert('job_posts', $data);
    }

    public static function findById(string $id): ?array
    {
        $job = Database::fetch(
            'SELECT jp.*, u.name as recruiter_name, b.name as business_name 
             FROM job_posts jp 
             JOIN users u ON jp.user_id = u.id 
             LEFT JOIN businesses b ON jp.business_id = b.id 
             WHERE jp.id = ? AND jp.deleted_at IS NULL',
            [$id]
        );
        
        if ($job) {
            $job['duties_json'] = json_decode($job['duties_json'], true);
            $job['requirements_json'] = json_decode($job['requirements_json'], true);
        }
        
        return $job;
    }

    public static function getAll(array $filters = []): array
    {
        $sql = 'SELECT jp.*, u.name as recruiter_name, b.name as business_name 
                FROM job_posts jp 
                JOIN users u ON jp.user_id = u.id 
                LEFT JOIN businesses b ON jp.business_id = b.id 
                WHERE jp.deleted_at IS NULL';
        $params = [];

        if (!empty($filters['status'])) {
            $sql .= ' AND jp.status = ?';
            $params[] = $filters['status'];
        }

        if (!empty($filters['city'])) {
            $sql .= ' AND jp.city LIKE ?';
            $params[] = '%' . $filters['city'] . '%';
        }

        if (!empty($filters['employment_type'])) {
            $sql .= ' AND jp.employment_type = ?';
            $params[] = $filters['employment_type'];
        }

        if (!empty($filters['search'])) {
            $sql .= ' AND (jp.title LIKE ? OR jp.description LIKE ?)';
            $searchTerm = '%' . $filters['search'] . '%';
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }

        $sql .= ' ORDER BY jp.created_at DESC';

        if (!empty($filters['limit'])) {
            $sql .= ' LIMIT ?';
            $params[] = (int)$filters['limit'];
        }

        $jobs = Database::fetchAll($sql, $params);
        
        // Decode JSON fields
        foreach ($jobs as &$job) {
            $job['duties_json'] = json_decode($job['duties_json'], true);
            $job['requirements_json'] = json_decode($job['requirements_json'], true);
        }
        
        return $jobs;
    }

    public static function getApplications(string $jobId): array
    {
        return Database::fetchAll(
            'SELECT ja.*, js.*, u.name as applicant_name, p.city, p.home_village 
             FROM job_applications ja 
             JOIN job_seekers js ON ja.job_seeker_id = js.id 
             JOIN users u ON js.user_id = u.id 
             LEFT JOIN profiles p ON u.id = p.user_id 
             WHERE ja.job_post_id = ? AND ja.deleted_at IS NULL 
             ORDER BY ja.created_at DESC',
            [$jobId]
        );
    }
}