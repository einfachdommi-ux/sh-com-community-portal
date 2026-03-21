<?php
namespace App\Models;

use App\Core\Database;
use PDO;

class Field
{
    public function all(array $filters = []): array
    {
        $sql = "
            SELECT f.*, farms.name AS farm_name
            FROM fields f
            LEFT JOIN farms ON farms.id = f.farm_id
            WHERE 1=1
        ";

        $params = [];

        if (!empty($filters['search'])) {
            $sql .= " AND (f.field_name LIKE :search OR f.notes LIKE :search)";
            $params['search'] = '%' . $filters['search'] . '%';
        }

        if (!empty($filters['pending_work'])) {
            $sql .= " AND f.pending_work = :pending_work";
            $params['pending_work'] = $filters['pending_work'];
        }

        if (!empty($filters['farm_id'])) {
            $sql .= " AND f.farm_id = :farm_id";
            $params['farm_id'] = (int)$filters['farm_id'];
        }

        if (!empty($filters['planned_sowing_date'])) {
            $sql .= " AND f.planned_sowing_date = :planned_sowing_date";
            $params['planned_sowing_date'] = $filters['planned_sowing_date'];
        }

        $sql .= "
            ORDER BY COALESCE(f.updated_at, f.created_at) DESC, f.id DESC
        ";

        $stmt = Database::query($sql, $params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find(int $id): ?array
    {
        $stmt = Database::query("
            SELECT *
            FROM fields
            WHERE id = :id
            LIMIT 1
        ", ['id' => $id]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public function create(array $data): int
    {
        Database::query("
            INSERT INTO fields (
                field_name,
                current_crop,
                planned_crop,
                fertilization_stage_1,
                plowed,
                lime,
                rolled,
                fertilization_stage_2,
                yield_bonus,
                pending_work,
                notes,
                planned_sowing_date,
                farm_id,
                created_at,
                updated_at
            ) VALUES (
                :field_name,
                :current_crop,
                :planned_crop,
                :fertilization_stage_1,
                :plowed,
                :lime,
                :rolled,
                :fertilization_stage_2,
                :yield_bonus,
                :pending_work,
                :notes,
                :planned_sowing_date,
                :farm_id,
                NOW(),
                NOW()
            )
        ", [
            'field_name' => $data['field_name'] ?? '',
            'current_crop' => $data['current_crop'] ?? null,
            'planned_crop' => $data['planned_crop'] ?? null,
            'fertilization_stage_1' => (int)($data['fertilization_stage_1'] ?? 0),
            'plowed' => (int)($data['plowed'] ?? 0),
            'lime' => (int)($data['lime'] ?? 0),
            'rolled' => (int)($data['rolled'] ?? 0),
            'fertilization_stage_2' => (int)($data['fertilization_stage_2'] ?? 0),
            'yield_bonus' => $data['yield_bonus'] ?? null,
            'pending_work' => $data['pending_work'] ?? null,
            'notes' => $data['notes'] ?? null,
            'planned_sowing_date' => $data['planned_sowing_date'] ?? null,
            'farm_id' => $data['farm_id'] ?? null,
        ]);

        if (method_exists(Database::class, 'pdo')) {
            return (int) Database::pdo()->lastInsertId();
        }

        $stmt = Database::query("SELECT LAST_INSERT_ID() AS id");
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)($row['id'] ?? 0);
    }

    public function update(int $id, array $data): bool
    {
        Database::query("
            UPDATE fields
            SET
                field_name = :field_name,
                current_crop = :current_crop,
                planned_crop = :planned_crop,
                fertilization_stage_1 = :fertilization_stage_1,
                plowed = :plowed,
                lime = :lime,
                rolled = :rolled,
                fertilization_stage_2 = :fertilization_stage_2,
                yield_bonus = :yield_bonus,
                pending_work = :pending_work,
                notes = :notes,
                planned_sowing_date = :planned_sowing_date,
                farm_id = :farm_id,
                updated_at = NOW()
            WHERE id = :id
        ", [
            'id' => $id,
            'field_name' => $data['field_name'] ?? '',
            'current_crop' => $data['current_crop'] ?? null,
            'planned_crop' => $data['planned_crop'] ?? null,
            'fertilization_stage_1' => (int)($data['fertilization_stage_1'] ?? 0),
            'plowed' => (int)($data['plowed'] ?? 0),
            'lime' => (int)($data['lime'] ?? 0),
            'rolled' => (int)($data['rolled'] ?? 0),
            'fertilization_stage_2' => (int)($data['fertilization_stage_2'] ?? 0),
            'yield_bonus' => $data['yield_bonus'] ?? null,
            'pending_work' => $data['pending_work'] ?? null,
            'notes' => $data['notes'] ?? null,
            'planned_sowing_date' => $data['planned_sowing_date'] ?? null,
            'farm_id' => $data['farm_id'] ?? null,
        ]);

        return true;
    }

    public function delete(int $id): bool
    {
        Database::query("
            DELETE FROM fields
            WHERE id = :id
        ", ['id' => $id]);

        return true;
    }
}
