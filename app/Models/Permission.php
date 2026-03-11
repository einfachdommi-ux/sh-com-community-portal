<?php
namespace App\Models;

use App\Core\Database;

class Permission extends BaseModel
{
    protected string $table = 'permissions';

    public function create(array $data): int
    {
        $data['created_at'] = $data['created_at'] ?? now();
        $data['updated_at'] = $data['updated_at'] ?? now();
        return parent::create($data);
    }

    public function update(int $id, array $data): void
    {
        $data['updated_at'] = now();
        $this->updateFields($id, $data);
    }

    public function idsForRole(int $roleId): array
    {
        return array_map(
            'intval',
            array_column(
                Database::query('SELECT permission_id FROM role_permissions WHERE role_id = ?', [$roleId])->fetchAll(),
                'permission_id'
            )
        );
    }

    public function assignToRole(int $roleId, array $permissionIds): void
    {
        Database::query('DELETE FROM role_permissions WHERE role_id = ?', [$roleId]);

        foreach ($permissionIds as $permissionId) {
            Database::query(
                'INSERT INTO role_permissions (role_id, permission_id) VALUES (?, ?)',
                [$roleId, (int) $permissionId]
            );
        }
    }
}
