<?php
namespace App\Models;

use App\Core\Database;

class Page extends BaseModel
{
    protected string $table = 'pages';

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

    public function findBySlug(string $slug): ?array
    {
        $row = Database::query('SELECT * FROM pages WHERE slug = ? LIMIT 1', [$slug])->fetch();
        return $row ?: null;
    }
}
