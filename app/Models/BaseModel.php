<?php
namespace App\Models;

use App\Core\Database;

abstract class BaseModel
{
    protected string $table;

    public function all(string $orderBy = 'id DESC'): array
    {
        return Database::query("SELECT * FROM {$this->table} ORDER BY {$orderBy}")->fetchAll();
    }

    public function find(int $id): ?array
    {
        $row = Database::query("SELECT * FROM {$this->table} WHERE id = ?", [$id])->fetch();
        return $row ?: null;
    }

    public function delete(int $id): void
    {
        Database::query("DELETE FROM {$this->table} WHERE id = ?", [$id]);
    }
}
