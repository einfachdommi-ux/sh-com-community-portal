<?php
namespace App\Models;

use App\Core\Auth;
use App\Core\Database;

class NavigationItem extends BaseModel
{
    protected string $table = 'navigation_items';

    public function create(array $data): int
    {
        Database::query('INSERT INTO navigation_items (parent_id, area, label, route, icon, permission_slug, sort_order, is_active, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', [
            $data['parent_id'] ?? null, $data['area'], $data['label'], $data['route'], $data['icon'] ?? null,
            $data['permission_slug'] ?? null, $data['sort_order'] ?? 0, $data['is_active'] ?? 1, now(), now()
        ]);
        return (int) Database::lastInsertId();
    }

    public function byArea(string $area): array
    {
        $items = Database::query('SELECT * FROM navigation_items WHERE area = ? AND is_active = 1 ORDER BY sort_order ASC, id ASC', [$area])->fetchAll();
        return array_values(array_filter($items, function ($item) use ($area) {
            if (empty($item['permission_slug'])) {
                return true;
            }
            return Auth::check() && Auth::hasPermission($item['permission_slug']);
        }));
    }
}
