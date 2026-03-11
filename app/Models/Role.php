<?php
namespace App\Models;

use App\Core\Database;

class Role extends BaseModel
{
    protected string $table = 'roles';

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

    public function countAll(): int
    {
        return (int) Database::query('SELECT COUNT(*) c FROM roles')->fetch()['c'];
    }
}
