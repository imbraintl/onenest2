<?php

namespace App\Models;

use App\Core\Database;

class User
{
    public string $id;
    public string $name;
    public string $email;
    public ?string $phone;
    public string $password;
    public ?array $role_flags;
    public bool $is_agent;
    public ?string $last_login_at;
    public ?string $created_at;
    public ?string $updated_at;

    public static function create(array $data): string
    {
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        $data['role_flags'] = json_encode($data['role_flags'] ?? []);
        
        return Database::insert('users', $data);
    }

    public static function findByEmail(string $email): ?array
    {
        return Database::fetch(
            'SELECT * FROM users WHERE email = ? AND deleted_at IS NULL',
            [$email]
        );
    }

    public static function findById(string $id): ?array
    {
        return Database::fetch(
            'SELECT * FROM users WHERE id = ? AND deleted_at IS NULL',
            [$id]
        );
    }

    public static function updateLastLogin(string $id): void
    {
        Database::update('users', ['last_login_at' => date('Y-m-d H:i:s')], 'id = ?', [$id]);
    }

    public static function verifyPassword(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }

    public function getProfile(): ?array
    {
        return Database::fetch(
            'SELECT * FROM profiles WHERE user_id = ? AND deleted_at IS NULL',
            [$this->id]
        );
    }

    public function getBusinesses(): array
    {
        return Database::fetchAll(
            'SELECT * FROM businesses WHERE user_id = ? AND deleted_at IS NULL',
            [$this->id]
        );
    }

    public function getMarketplaceItems(): array
    {
        return Database::fetchAll(
            'SELECT * FROM marketplace_items WHERE user_id = ? AND deleted_at IS NULL',
            [$this->id]
        );
    }

    public function getProperties(): array
    {
        return Database::fetchAll(
            'SELECT * FROM properties WHERE user_id = ? AND deleted_at IS NULL',
            [$this->id]
        );
    }

    public function getSavedItems(): array
    {
        return Database::fetchAll(
            'SELECT * FROM saved_items WHERE user_id = ?',
            [$this->id]
        );
    }
}