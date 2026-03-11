<?php
namespace App\Models;

use App\Core\Auth;

class NavigationItem extends BaseModel
{
    protected string $table = 'navigation_items';

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

    public function forArea(string $area): array
    {
        $items = $this->all('sort_order ASC, id ASC');

        return array_values(array_filter($items, function (array $item) use ($area): bool {
            if (($item['area'] ?? '') !== $area) {
                return false;
            }

            if (empty($item['permission_slug'])) {
                return true;
            }

            return Auth::check() && Auth::hasPermission((string) $item['permission_slug']);
        }));
    }
}
