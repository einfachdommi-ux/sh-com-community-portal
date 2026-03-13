<?php
namespace App\Models;

use App\Core\Database;
use PDO;

class Farm
{
    public function find(int $id): ?array
    {
        $stmt = Database::query("SELECT * FROM farms WHERE id = :id LIMIT 1", ['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public function all(): array
    {
        $stmt = Database::query("SELECT * FROM farms ORDER BY name ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findByUserId(int $userId): ?array
    {
        $stmt = Database::query("
            SELECT f.*
            FROM farms f
            INNER JOIN users u ON u.farm_id = f.id
            WHERE u.id = :user_id
            LIMIT 1
        ", ['user_id' => $userId]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public function getMembers(int $farmId): array
    {
        $stmt = Database::query("
            SELECT id, username, email, first_name, last_name, farm_role
            FROM users
            WHERE farm_id = :farm_id
            ORDER BY COALESCE(first_name, username) ASC, username ASC
        ", ['farm_id' => $farmId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getOwner(int $farmId): ?array
    {
        $stmt = Database::query("
            SELECT id, username, email, first_name, last_name, farm_role
            FROM users
            WHERE farm_id = :farm_id
              AND farm_role = 'owner'
            LIMIT 1
        ", ['farm_id' => $farmId]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public function isOwner(int $farmId, int $userId): bool
    {
        $stmt = Database::query("
            SELECT id
            FROM users
            WHERE id = :user_id
              AND farm_id = :farm_id
              AND farm_role = 'owner'
            LIMIT 1
        ", [
            'user_id' => $userId,
            'farm_id' => $farmId,
        ]);

        return (bool)$stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function join(int $userId, int $farmId): bool
    {
        Database::query("
            UPDATE users
            SET farm_id = :farm_id,
                farm_role = COALESCE(NULLIF(farm_role, ''), 'member')
            WHERE id = :user_id
        ", [
            'farm_id' => $farmId,
            'user_id' => $userId
        ]);
        return true;
    }

    public function leave(int $userId): bool
    {
        Database::query("
            UPDATE users
            SET farm_id = NULL,
                farm_role = NULL
            WHERE id = :user_id
        ", ['user_id' => $userId]);

        return true;
    }

    public function removeMember(int $farmId, int $memberId): bool
    {
        Database::query("
            UPDATE users
            SET farm_id = NULL,
                farm_role = NULL
            WHERE id = :member_id
              AND farm_id = :farm_id
        ", [
            'member_id' => $memberId,
            'farm_id' => $farmId
        ]);

        return true;
    }

    public function setPassword(int $farmId, string $password): bool
    {
        Database::query("
            UPDATE farms
            SET password_hash = :password_hash
            WHERE id = :id
        ", [
            'password_hash' => password_hash($password, PASSWORD_DEFAULT),
            'id' => $farmId
        ]);

        return true;
    }

    public function verifyPassword(int $farmId, string $password): bool
    {
        $farm = $this->find($farmId);
        if (!$farm || empty($farm['password_hash'])) {
            return false;
        }

        return password_verify($password, $farm['password_hash']);
    }

    public function getStats(int $farmId): array
    {
        $fieldsStmt = Database::query("
            SELECT
                COUNT(*) AS total_fields,
                SUM(CASE WHEN fertilization_stage_1 = 1 THEN 1 ELSE 0 END) AS fertilization_stage_1_done,
                SUM(CASE WHEN fertilization_stage_2 = 1 THEN 1 ELSE 0 END) AS fertilization_stage_2_done,
                SUM(CASE WHEN plowed = 1 THEN 1 ELSE 0 END) AS plowed_done,
                SUM(CASE WHEN lime = 1 THEN 1 ELSE 0 END) AS lime_done,
                SUM(CASE WHEN rolled = 1 THEN 1 ELSE 0 END) AS rolled_done
            FROM fields
            WHERE farm_id = :farm_id
        ", ['farm_id' => $farmId]);
        $fieldStats = $fieldsStmt->fetch(PDO::FETCH_ASSOC) ?: [];

        $membersStmt = Database::query("
            SELECT COUNT(*) AS total_members
            FROM users
            WHERE farm_id = :farm_id
        ", ['farm_id' => $farmId]);
        $memberStats = $membersStmt->fetch(PDO::FETCH_ASSOC) ?: [];

        return [
            'total_fields' => (int)($fieldStats['total_fields'] ?? 0),
            'fertilization_stage_1_done' => (int)($fieldStats['fertilization_stage_1_done'] ?? 0),
            'fertilization_stage_2_done' => (int)($fieldStats['fertilization_stage_2_done'] ?? 0),
            'plowed_done' => (int)($fieldStats['plowed_done'] ?? 0),
            'lime_done' => (int)($fieldStats['lime_done'] ?? 0),
            'rolled_done' => (int)($fieldStats['rolled_done'] ?? 0),
            'total_members' => (int)($memberStats['total_members'] ?? 0),
        ];
    }
}
