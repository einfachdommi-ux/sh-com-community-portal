<?php
namespace App\Models;

use App\Core\Database;
use PDO;

class FarmInvite
{
    public function createToken(int $farmId, int $createdBy): string
    {
        $token = bin2hex(random_bytes(24));

        Database::query("
            INSERT INTO farm_invites (farm_id, token, created_by, created_at, expires_at)
            VALUES (:farm_id, :token, :created_by, NOW(), DATE_ADD(NOW(), INTERVAL 7 DAY))
        ", [
            'farm_id' => $farmId,
            'token' => $token,
            'created_by' => $createdBy,
        ]);

        return $token;
    }

    public function findValidByToken(string $token): ?array
    {
        $stmt = Database::query("
            SELECT *
            FROM farm_invites
            WHERE token = :token
              AND (expires_at IS NULL OR expires_at >= NOW())
              AND (used_at IS NULL)
            LIMIT 1
        ", ['token' => $token]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public function markUsed(int $inviteId): bool
    {
        Database::query("
            UPDATE farm_invites
            SET used_at = NOW()
            WHERE id = :id
        ", ['id' => $inviteId]);

        return true;
    }

    public function allByFarm(int $farmId): array
    {
        $stmt = Database::query("
            SELECT *
            FROM farm_invites
            WHERE farm_id = :farm_id
            ORDER BY created_at DESC, id DESC
        ", ['farm_id' => $farmId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
