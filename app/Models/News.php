<?php
namespace App\Models;

use App\Core\Database;

class News extends BaseModel
{
    protected string $table = 'news';

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
        $row = Database::query(
            'SELECT n.*, u.username author_name FROM news n LEFT JOIN users u ON u.id = n.author_id WHERE n.slug = ? LIMIT 1',
            [$slug]
        )->fetch();

        return $row ?: null;
    }

    public function countAll(): int
    {
        return (int) Database::query('SELECT COUNT(*) c FROM news')->fetch()['c'];
    }
}
