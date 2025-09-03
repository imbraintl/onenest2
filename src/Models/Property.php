<?php

namespace App\Models;

use App\Core\Database;

class Property
{
    public static function create(array $data): string
    {
        $data['features_json'] = json_encode($data['features_json'] ?? []);
        $data['meta_json'] = json_encode($data['meta_json'] ?? []);
        
        return Database::insert('properties', $data);
    }

    public static function findById(string $id): ?array
    {
        $property = Database::fetch(
            'SELECT p.*, u.name as owner_name 
             FROM properties p 
             JOIN users u ON p.user_id = u.id 
             WHERE p.id = ? AND p.deleted_at IS NULL',
            [$id]
        );
        
        if ($property) {
            $property['features_json'] = json_decode($property['features_json'], true);
            $property['meta_json'] = json_decode($property['meta_json'], true);
        }
        
        return $property;
    }

    public static function getAll(array $filters = []): array
    {
        $sql = 'SELECT p.*, u.name as owner_name 
                FROM properties p 
                JOIN users u ON p.user_id = u.id 
                WHERE p.deleted_at IS NULL';
        $params = [];

        if (!empty($filters['type'])) {
            $sql .= ' AND p.type = ?';
            $params[] = $filters['type'];
        }

        if (!empty($filters['city'])) {
            $sql .= ' AND p.city LIKE ?';
            $params[] = '%' . $filters['city'] . '%';
        }

        if (!empty($filters['price_type'])) {
            $sql .= ' AND p.price_type = ?';
            $params[] = $filters['price_type'];
        }

        if (!empty($filters['price_min'])) {
            $sql .= ' AND p.price_bwp >= ?';
            $params[] = (int)$filters['price_min'];
        }

        if (!empty($filters['price_max'])) {
            $sql .= ' AND p.price_bwp <= ?';
            $params[] = (int)$filters['price_max'];
        }

        if (!empty($filters['bedrooms'])) {
            $sql .= ' AND p.bedrooms >= ?';
            $params[] = (int)$filters['bedrooms'];
        }

        if (!empty($filters['status'])) {
            $sql .= ' AND p.status = ?';
            $params[] = $filters['status'];
        }

        $sql .= ' ORDER BY p.created_at DESC';

        if (!empty($filters['limit'])) {
            $sql .= ' LIMIT ?';
            $params[] = (int)$filters['limit'];
        }

        $properties = Database::fetchAll($sql, $params);
        
        // Decode JSON fields
        foreach ($properties as &$property) {
            $property['features_json'] = json_decode($property['features_json'], true);
            $property['meta_json'] = json_decode($property['meta_json'], true);
        }
        
        return $properties;
    }

    public static function updateStatus(string $id, string $status): bool
    {
        return Database::update('properties', ['status' => $status], 'id = ?', [$id]);
    }
}