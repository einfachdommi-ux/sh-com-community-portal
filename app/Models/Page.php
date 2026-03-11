<?php
namespace App\Models;

use App\Core\Database;

class Page extends BaseModel
{
    protected string $table = 'pages';

    public function create(array $data): int
    {
        Database::query('INSERT INTO pages (title, slug, content, meta_title, meta_description, visibility, status, created_by, updated_by, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', [
            $data['title'], $data['slug'], $data['content'], $data['meta_title'] ?? null, $data['meta_description'] ?? null,
            $data['visibility'] ?? 'public', $data['status'] ?? 'draft', $data['created_by'] ?? null, $data['updated_by'] ?? null, now(), now()
        ]);
        return (int) Database::lastInsertId();
    }

    public function findBySlug(string $slug): ?array
    {
        $row = Database::query('SELECT * FROM pages WHERE slug = ? AND status = "published"', [$slug])->fetch();
        return $row ?: null;
    }
}
