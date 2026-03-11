<?php
namespace App\Models;

use App\Core\Database;

abstract class BaseModel
{
    protected string $table;

    public function create(array $data): int
    {
        $columns = array_keys($data);
        $placeholders = array_fill(0, count($columns), '?');
        $values = array_values($data);

        $sql = sprintf(
            'INSERT INTO %s (%s) VALUES (%s)',
            $this->table,
            implode(', ', $columns),
            implode(', ', $placeholders)
        );

        Database::query($sql, $values);

        return (int) Database::lastInsertId();
    }

    public function updateFields(int $id, array $data): void
    {
        if ($data === []) {
            return;
        }

        $set = [];
        $values = [];
        foreach ($data as $column => $value) {
            $set[] = $column . ' = ?';
            $values[] = $value;
        }

        $values[] = $id;

        $sql = sprintf(
            'UPDATE %s SET %s WHERE id = ?',
            $this->table,
            implode(', ', $set)
        );

        Database::query($sql, $values);
    }

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
