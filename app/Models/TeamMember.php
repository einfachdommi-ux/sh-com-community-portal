<?php
namespace App\Models;

use App\Core\Database;

class TeamMember extends BaseModel
{
    protected string $table = 'team_members';

    public function create(array $data): int
    {
        Database::query('INSERT INTO team_members (user_id, display_name, team_role, bio, image_path, social_links, sort_order, is_active, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', [
            $data['user_id'] ?? null, $data['display_name'], $data['team_role'], $data['bio'] ?? null, $data['image_path'] ?? null,
            !empty($data['social_links']) ? json_encode($data['social_links'], JSON_UNESCAPED_UNICODE) : null,
            $data['sort_order'] ?? 0, $data['is_active'] ?? 1, now(), now()
        ]);
        return (int) Database::lastInsertId();
    }

    public function active(): array
    {
        return Database::query('SELECT * FROM team_members WHERE is_active = 1 ORDER BY sort_order ASC, id ASC')->fetchAll();
    }

    public function countAll(): int
    {
        return (int) Database::query('SELECT COUNT(*) c FROM team_members')->fetch()['c'];
    }
}
