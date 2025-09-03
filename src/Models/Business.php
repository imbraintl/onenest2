<?php

namespace App\Models;

use App\Core\Database;

class Business
{
    public static function create(array $data): string
    {
        $data['delivery_info_json'] = json_encode($data['delivery_info_json'] ?? []);
        $data['profile_json'] = json_encode($data['profile_json'] ?? []);
        
        return Database::insert('businesses', $data);
    }

    public static function findById(string $id): ?array
    {
        $business = Database::fetch(
            'SELECT * FROM businesses WHERE id = ? AND deleted_at IS NULL',
            [$id]
        );
        
        if ($business) {
            $business['delivery_info_json'] = json_decode($business['delivery_info_json'], true);
            $business['profile_json'] = json_decode($business['profile_json'], true);
        }
        
        return $business;
    }

    public static function getAll(array $filters = []): array
    {
        $sql = 'SELECT b.*, u.name as owner_name FROM businesses b 
                JOIN users u ON b.user_id = u.id 
                WHERE b.deleted_at IS NULL';
        $params = [];

        if (!empty($filters['type'])) {
            $sql .= ' AND b.type = ?';
            $params[] = $filters['type'];
        }

        if (!empty($filters['status'])) {
            $sql .= ' AND b.status = ?';
            $params[] = $filters['status'];
        }

        if (!empty($filters['city'])) {
            $sql .= ' AND b.location_text LIKE ?';
            $params[] = '%' . $filters['city'] . '%';
        }

        $sql .= ' ORDER BY b.created_at DESC';

        if (!empty($filters['limit'])) {
            $sql .= ' LIMIT ?';
            $params[] = (int)$filters['limit'];
        }

        return Database::fetchAll($sql, $params);
    }

    public static function updateStatus(string $id, string $status): bool
    {
        return Database::update('businesses', ['status' => $status], 'id = ?', [$id]);
    }

    public static function getProducts(string $businessId): array
    {
        return Database::fetchAll(
            'SELECT * FROM products WHERE business_id = ? AND deleted_at IS NULL',
            [$businessId]
        );
    }
}