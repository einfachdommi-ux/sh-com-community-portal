<?php
namespace App\Models;

use App\Core\Database;
use PDO;

class NavigationItem
{
    public function all(): array
    {
        $stmt = Database::query("
            SELECT *
            FROM navigation_items
            ORDER BY area ASC, parent_id ASC, sort_order ASC, id ASC
        ");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find(int $id): ?array
    {
        $stmt = Database::query("
            SELECT *
            FROM navigation_items
            WHERE id = :id
            LIMIT 1
        ", [
            'id' => $id
        ]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public function create(array $data): bool
    {
        Database::query("
            INSERT INTO navigation_items (
                parent_id,
                area,
                label,
                route,
                icon,
                permission_slug,
                sort_order,
                is_active,
                created_at,
                updated_at
            ) VALUES (
                :parent_id,
                :area,
                :label,
                :route,
                :icon,
                :permission_slug,
                :sort_order,
                :is_active,
                NOW(),
                NOW()
            )
        ", [
            'parent_id' => $this->normalizeParentId($data['parent_id'] ?? null),
            'area' => trim((string)($data['area'] ?? 'frontend')),
            'label' => trim((string)($data['label'] ?? '')),
            'route' => trim((string)($data['route'] ?? '')),
            'icon' => trim((string)($data['icon'] ?? '')),
            'permission_slug' => trim((string)($data['permission_slug'] ?? '')),
            'sort_order' => (int)($data['sort_order'] ?? 0),
            'is_active' => (int)($data['is_active'] ?? 0),
        ]);

        return true;
    }

    public function update(int $id, array $data): bool
    {
        Database::query("
            UPDATE navigation_items
            SET
                parent_id = :parent_id,
                area = :area,
                label = :label,
                route = :route,
                icon = :icon,
                permission_slug = :permission_slug,
                sort_order = :sort_order,
                is_active = :is_active,
                updated_at = NOW()
            WHERE id = :id
        ", [
            'id' => $id,
            'parent_id' => $this->normalizeParentId($data['parent_id'] ?? null),
            'area' => trim((string)($data['area'] ?? 'frontend')),
            'label' => trim((string)($data['label'] ?? '')),
            'route' => trim((string)($data['route'] ?? '')),
            'icon' => trim((string)($data['icon'] ?? '')),
            'permission_slug' => trim((string)($data['permission_slug'] ?? '')),
            'sort_order' => (int)($data['sort_order'] ?? 0),
            'is_active' => (int)($data['is_active'] ?? 0),
        ]);

        return true;
    }

    public function delete(int $id): bool
    {
        Database::query("
            DELETE FROM navigation_items
            WHERE id = :id
        ", [
            'id' => $id
        ]);

        return true;
    }

    public function forArea(string $area = 'frontend'): array
    {
        $stmt = Database::query("
            SELECT *
            FROM navigation_items
            WHERE area = :area
              AND is_active = 1
            ORDER BY sort_order ASC, id ASC
        ", [
            'area' => $area
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function byArea(string $area = 'frontend'): array
    {
        return $this->forArea($area);
    }

    public function tree(string $area = 'frontend'): array
    {
        $items = $this->forArea($area);

        $indexed = [];
        foreach ($items as $item) {
            $item['children'] = [];
            $indexed[(int)$item['id']] = $item;
        }

        $tree = [];

        foreach ($indexed as $id => $item) {
            $parentId = $item['parent_id'];

            if ($parentId === null || $parentId === '' || (int)$parentId === 0) {
                $tree[$id] = &$indexed[$id];
                continue;
            }

            $parentId = (int)$parentId;

            if (isset($indexed[$parentId])) {
                $indexed[$parentId]['children'][] = &$indexed[$id];
            } else {
                $tree[$id] = &$indexed[$id];
            }
        }

        return array_values($tree);
    }

    private function normalizeParentId($parentId): ?int
    {
        if ($parentId === null || $parentId === '' || (int)$parentId === 0) {
            return null;
        }

        return (int)$parentId;
    }
}