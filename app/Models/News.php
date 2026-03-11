<?php
namespace App\Models;

use App\Core\Database;

class News extends BaseModel
{
    protected string $table = 'news';

    public function published(): array
    {
        return Database::query(
            "SELECT n.*, u.username author_name
             FROM news n
             LEFT JOIN users u ON u.id = n.author_id
             WHERE n.status = 'published'
             ORDER BY COALESCE(n.published_at, n.created_at) DESC, n.id DESC"
        )->fetchAll();
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

    public function update(int $id, array $data): void
    {
        Database::query(
            'UPDATE news SET title = ?, slug = ?, teaser = ?, content = ?, featured_image = ?, status = ?, published_at = ?, author_id = ?, updated_at = ? WHERE id = ?',
            [
                $data['title'],
                $data['slug'],
                $data['teaser'] ?? null,
                $data['content'],
                $data['featured_image'] ?? null,
                $data['status'] ?? 'draft',
                $data['published_at'] ?? null,
                $data['author_id'] ?? null,
                now(),
                $id,
            ]
        );
    }
}
