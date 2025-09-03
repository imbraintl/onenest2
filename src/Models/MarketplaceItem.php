<?php

namespace App\Models;

use App\Core\Database;

class MarketplaceItem
{
    public static function create(array $data): string
    {
        $data['locations_json'] = json_encode($data['locations_json'] ?? []);
        $data['meta_json'] = json_encode($data['meta_json'] ?? []);
        
        return Database::insert('marketplace_items', $data);
    }

    public static function findById(string $id): ?array
    {
        $item = Database::fetch(
            'SELECT mi.*, u.name as seller_name, pc.name as category_name 
             FROM marketplace_items mi 
             JOIN users u ON mi.user_id = u.id 
             LEFT JOIN product_categories pc ON mi.category_id = pc.id 
             WHERE mi.id = ? AND mi.deleted_at IS NULL',
            [$id]
        );
        
        if ($item) {
            $item['locations_json'] = json_decode($item['locations_json'], true);
            $item['meta_json'] = json_decode($item['meta_json'], true);
        }
        
        return $item;
    }

    public static function getAll(array $filters = []): array
    {
        $sql = 'SELECT mi.*, u.name as seller_name, pc.name as category_name 
                FROM marketplace_items mi 
                JOIN users u ON mi.user_id = u.id 
                LEFT JOIN product_categories pc ON mi.category_id = pc.id 
                WHERE mi.deleted_at IS NULL';
        $params = [];

        if (!empty($filters['status'])) {
            $sql .= ' AND mi.status = ?';
            $params[] = $filters['status'];
        }

        if (!empty($filters['category_id'])) {
            $sql .= ' AND mi.category_id = ?';
            $params[] = $filters['category_id'];
        }

        if (!empty($filters['search'])) {
            $sql .= ' AND (mi.name LIKE ? OR mi.description LIKE ?)';
            $searchTerm = '%' . $filters['search'] . '%';
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }

        if (!empty($filters['price_min'])) {
            $sql .= ' AND mi.price_bwp >= ?';
            $params[] = (int)$filters['price_min'];
        }

        if (!empty($filters['price_max'])) {
            $sql .= ' AND mi.price_bwp <= ?';
            $params[] = (int)$filters['price_max'];
        }

        $sql .= ' ORDER BY mi.created_at DESC';

        if (!empty($filters['limit'])) {
            $sql .= ' LIMIT ?';
            $params[] = (int)$filters['limit'];
        }

        $items = Database::fetchAll($sql, $params);
        
        // Decode JSON fields
        foreach ($items as &$item) {
            $item['locations_json'] = json_decode($item['locations_json'], true);
            $item['meta_json'] = json_decode($item['meta_json'], true);
        }
        
        return $items;
    }

    public static function updateStatus(string $id, string $status): bool
    {
        return Database::update('marketplace_items', ['status' => $status], 'id = ?', [$id]);
    }
}