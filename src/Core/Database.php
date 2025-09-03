<?php

namespace App\Core;

use PDO;
use PDOException;

class Database
{
    private static ?PDO $instance = null;
    private array $config;

    public function __construct()
    {
        $this->config = require __DIR__ . '/../../config/database.php';
    }

    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            $db = new self();
            self::$instance = $db->connect();
        }
        return self::$instance;
    }

    private function connect(): PDO
    {
        try {
            $dsn = sprintf(
                'mysql:host=%s;port=%d;dbname=%s;charset=%s',
                $this->config['host'],
                $this->config['port'],
                $this->config['database'],
                $this->config['charset']
            );

            $pdo = new PDO($dsn, $this->config['username'], $this->config['password'], $this->config['options']);
            
            // Set timezone
            $pdo->exec("SET time_zone = '+02:00'"); // Africa/Gaborone
            
            return $pdo;
        } catch (PDOException $e) {
            throw new PDOException('Database connection failed: ' . $e->getMessage());
        }
    }

    public static function query(string $sql, array $params = []): \PDOStatement
    {
        $pdo = self::getInstance();
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    public static function fetch(string $sql, array $params = []): ?array
    {
        $stmt = self::query($sql, $params);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public static function fetchAll(string $sql, array $params = []): array
    {
        $stmt = self::query($sql, $params);
        return $stmt->fetchAll();
    }

    public static function insert(string $table, array $data): string
    {
        // Generate UUID if id not provided
        if (!isset($data['id'])) {
            $data['id'] = self::generateUuid();
        }
        
        // Add timestamps
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        
        // Clean up empty values that might cause MySQL issues
        foreach ($data as $key => $value) {
            if ($value === '') {
                $data[$key] = null;
            }
        }
        
        $columns = implode(',', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));
        
        $sql = "INSERT INTO {$table} ({$columns}) VALUES ({$placeholders})";
        
        self::query($sql, $data);
        return $data['id'];
    }

    public static function update(string $table, array $data, string $where, array $whereParams = []): bool
    {
        $setParts = [];
        foreach (array_keys($data) as $column) {
            $setParts[] = "{$column} = :{$column}";
        }
        $setClause = implode(', ', $setParts);
        
        $sql = "UPDATE {$table} SET {$setClause}, updated_at = NOW() WHERE {$where}";
        
        $params = array_merge($data, $whereParams);
        $stmt = self::query($sql, $params);
        
        return $stmt->rowCount() > 0;
    }

    public static function delete(string $table, string $where, array $params = []): bool
    {
        $sql = "UPDATE {$table} SET deleted_at = NOW() WHERE {$where} AND deleted_at IS NULL";
        $stmt = self::query($sql, $params);
        return $stmt->rowCount() > 0;
    }

    public static function generateUuid(): string
    {
        return \Ramsey\Uuid\Uuid::uuid4()->toString();
    }
}