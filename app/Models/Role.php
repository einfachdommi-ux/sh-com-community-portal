<?php
namespace App\Models;

use App\Core\Database;

class Role extends BaseModel
{
    protected string $table = 'roles';

    public function create(array $data): int
    {
        Database::query('INSERT INTO roles (name, slug, description, created_at, updated_at) VALUES (?, ?, ?, ?, ?)', [
            $data['name'], $data['slug'], $data['description'] ?? null, now(), now()
        ]);
        return (int) Database::lastInsertId();
    }

    public function allWithPermissions(): array
    {
        return Database::query('SELECT * FROM roles ORDER BY name')->fetchAll();
    }
}
