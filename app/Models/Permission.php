<?php
namespace App\Models;

use App\Core\Database;

class Permission extends BaseModel
{
    protected string $table = 'permissions';

    public function create(array $data): int
    {
        Database::query('INSERT INTO permissions (name, slug, description, created_at, updated_at) VALUES (?, ?, ?, ?, ?)', [
            $data['name'], $data['slug'], $data['description'] ?? null, now(), now()
        ]);
        return (int) Database::lastInsertId();
    }

    public function assignToRole(int $roleId, array $permissionIds): void
    {
        Database::query('DELETE FROM role_permissions WHERE role_id = ?', [$roleId]);
        foreach ($permissionIds as $permissionId) {
            Database::query('INSERT INTO role_permissions (role_id, permission_id) VALUES (?, ?)', [$roleId, $permissionId]);
        }
    }

    public function idsForRole(int $roleId): array
    {
        return array_column(Database::query('SELECT permission_id FROM role_permissions WHERE role_id = ?', [$roleId])->fetchAll(), 'permission_id');
    }
}
