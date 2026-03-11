<?php
namespace App\Models;

use App\Core\Database;

class Page extends BaseModel
{
    protected string $table = 'pages';

    public function findBySlug(string $slug): ?array
    {
        $row = Database::query(
            'SELECT * FROM pages WHERE slug = ? AND status = ? LIMIT 1',
            [$slug, 'published']
        )->fetch();

        return $row ?: null;
    }

    public function update(int $id, array $data): void
    {
        Database::query(
            'UPDATE pages SET title = ?, slug = ?, content = ?, meta_title = ?, meta_description = ?, visibility = ?, status = ?, updated_by = ?, updated_at = ? WHERE id = ?',
            [
                $data['title'],
                $data['slug'],
                $data['content'],
                $data['meta_title'] ?? null,
                $data['meta_description'] ?? null,
                $data['visibility'] ?? 'public',
                $data['status'] ?? 'draft',
                $data['updated_by'] ?? null,
                now(),
                $id,
            ]
        );
    }
}
