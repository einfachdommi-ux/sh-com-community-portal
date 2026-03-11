<?php
namespace App\Models;

use App\Core\Database;

class Changelog extends BaseModel
{
    protected string $table = 'changelogs';

    public function create(array $data): int
    {
        Database::query('INSERT INTO changelogs (version, title, change_type, content, visibility, released_at, created_by, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)', [
            $data['version'], $data['title'], $data['change_type'], $data['content'], $data['visibility'] ?? 'public',
            $data['released_at'], $data['created_by'] ?? null, now(), now()
        ]);
        return (int) Database::lastInsertId();
    }

    public function latest(): array
    {
        return Database::query('SELECT c.*, u.username creator_name FROM changelogs c LEFT JOIN users u ON u.id = c.created_by ORDER BY c.released_at DESC, c.id DESC')->fetchAll();
    }

    public function countAll(): int
    {
        return (int) Database::query('SELECT COUNT(*) c FROM changelogs')->fetch()['c'];
    }
}
