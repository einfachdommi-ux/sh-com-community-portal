<?php
namespace App\Models;

use App\Core\Database;

class Changelog extends BaseModel
{
    protected string $table = 'changelogs';

    public function latest(int $limit = 20): array
    {
        $limit = max(1, (int)$limit);
        return Database::query(
            "SELECT * FROM changelogs ORDER BY released_at DESC, id DESC LIMIT {$limit}"
        )->fetchAll();
    }

    public function create(array $data): int
    {
        Database::query(
            'INSERT INTO changelogs (version, title, change_type, content, visibility, released_at, created_by, created_at, updated_at)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)',
            [
                $data['version'],
                $data['title'],
                $data['change_type'],
                $data['content'],
                $data['visibility'] ?? 'public',
                $data['released_at'] ?? now(),
                $data['created_by'] ?? null,
                now(),
                now(),
            ]
        );

        return (int) Database::lastInsertId();
    }

    public function update(int $id, array $data): void
    {
        Database::query(
            'UPDATE changelogs
             SET version = ?, title = ?, change_type = ?, content = ?, visibility = ?, released_at = ?, updated_at = ?
             WHERE id = ?',
            [
                $data['version'],
                $data['title'],
                $data['change_type'],
                $data['content'],
                $data['visibility'] ?? 'public',
                $data['released_at'] ?? now(),
                now(),
                $id,
            ]
        );
    }

    public function countAll(): int
    {
        return (int) Database::query('SELECT COUNT(*) c FROM changelogs')->fetch()['c'];
    }
}
