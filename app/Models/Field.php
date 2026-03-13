<?php
namespace App\Models;

use App\Core\Database;
use PDO;

class Field
{
    public function all(array $filters = []): array
    {
        $sql = "SELECT * FROM fields WHERE 1=1";
        $params = [];

        if (!empty($filters['farm_id'])) {
            $sql .= " AND farm_id = :farm_id";
            $params['farm_id'] = $filters['farm_id'];
        }

        $sql .= " ORDER BY field_name ASC";

        $stmt = Database::query($sql, $params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find(int $id): ?array
    {
        $stmt = Database::query(
            "SELECT * FROM fields WHERE id = :id LIMIT 1",
            ['id' => $id]
        );

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ?: null;
    }

    public function create(array $data): bool
    {
        Database::query("
            INSERT INTO fields (
                field_name,
                current_crop,
                planned_crop,
                planned_sowing_date,
                pending_work,
                fertilization_stage_1,
                fertilization_stage_2,
                plowed,
                lime,
                rolled,
                yield_bonus,
                notes,
                farm_id
            ) VALUES (
                :field_name,
                :current_crop,
                :planned_crop,
                :planned_sowing_date,
                :pending_work,
                :fertilization_stage_1,
                :fertilization_stage_2,
                :plowed,
                :lime,
                :rolled,
                :yield_bonus,
                :notes,
                :farm_id
            )
        ", $data);

        return true;
    }

    public function update(int $id, array $data): bool
    {
        $data['id'] = $id;

        Database::query("
            UPDATE fields SET
                field_name = :field_name,
                current_crop = :current_crop,
                planned_crop = :planned_crop,
                planned_sowing_date = :planned_sowing_date,
                pending_work = :pending_work,
                fertilization_stage_1 = :fertilization_stage_1,
                fertilization_stage_2 = :fertilization_stage_2,
                plowed = :plowed,
                lime = :lime,
                rolled = :rolled,
                yield_bonus = :yield_bonus,
                notes = :notes,
                farm_id = :farm_id,
                updated_at = NOW()
            WHERE id = :id
        ", $data);

        return true;
    }

    public function delete(int $id): bool
    {
        Database::query(
            "DELETE FROM fields WHERE id = :id",
            ['id' => $id]
        );

        return true;
    }
}