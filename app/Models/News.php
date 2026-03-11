<?php
namespace App\Models;

use App\Core\Database;

class News extends BaseModel
{
    protected string $table = 'news';

    public function create(array $data): int
    {
        Database::query('INSERT INTO news (title, slug, teaser, content, image_path, status, published_at, author_id, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', [
            $data['title'], $data['slug'], $data['teaser'] ?? null, $data['content'], $data['image_path'] ?? null,
            $data['status'] ?? 'draft', $data['published_at'] ?? null, $data['author_id'] ?? null, now(), now()
        ]);
        return (int) Database::lastInsertId();
    }

    public function published(): array
    {
        return Database::query('SELECT n.*, u.username author_name FROM news n LEFT JOIN users u ON u.id = n.author_id WHERE n.status = "published" ORDER BY COALESCE(n.published_at, n.created_at) DESC')->fetchAll();
    }

    public function findBySlug(string $slug): ?array
    {
        $row = Database::query('SELECT n.*, u.username author_name FROM news n LEFT JOIN users u ON u.id = n.author_id WHERE n.slug = ?', [$slug])->fetch();
        return $row ?: null;
    }

    public function countAll(): int
    {
        return (int) Database::query('SELECT COUNT(*) c FROM news')->fetch()['c'];
    }
}
