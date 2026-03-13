<?php
namespace App\Models;

use App\Core\Database;

class User extends BaseModel
{
    protected string $table = 'users';

    public function findByEmail(string $email): ?array
    {
        $row = Database::query('SELECT * FROM users WHERE email = ?', [$email])->fetch();
        return $row ?: null;
    }

    public function findByUsername(string $username): ?array
    {
        $row = Database::query('SELECT * FROM users WHERE username = ?', [$username])->fetch();
        return $row ?: null;
    }

    public function create(array $data): int
    {
        Database::query(
            'INSERT INTO users (username, email, password_hash, first_name, last_name, is_active, is_verified, email_verification_token, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
            [
                $data['username'], $data['email'], $data['password_hash'],
                $data['first_name'] ?? null, $data['last_name'] ?? null,
                $data['is_active'] ?? 1, $data['is_verified'] ?? 0,
                $data['email_verification_token'] ?? null, now(), now()
            ]
        );

        return (int) Database::lastInsertId();
    }

    public function verifyByToken(string $token): bool
    {
        $row = Database::query('SELECT id FROM users WHERE email_verification_token = ?', [$token])->fetch();
        if (!$row) {
            return false;
        }

        Database::query(
            'UPDATE users SET is_verified = 1, email_verification_token = NULL, updated_at = ? WHERE id = ?',
            [now(), $row['id']]
        );

        return true;
    }

    public function touchLogin(int $id): void
    {
        Database::query('UPDATE users SET last_login_at = ?, updated_at = ? WHERE id = ?', [now(), now(), $id]);
    }

    public function assignRole(int $userId, int $roleId): void
    {
        Database::query('DELETE FROM user_roles WHERE user_id = ?', [$userId]);
        Database::query('INSERT INTO user_roles (user_id, role_id) VALUES (?, ?)', [$userId, $roleId]);
    }

    public function hasPermission(int $userId, string $permissionSlug): bool
    {
        $row = Database::query(
            'SELECT COUNT(*) c
             FROM user_roles ur
             JOIN role_permissions rp ON rp.role_id = ur.role_id
             JOIN permissions p ON p.id = rp.permission_id
             WHERE ur.user_id = ? AND p.slug = ?',
            [$userId, $permissionSlug]
        )->fetch();

        return !empty($row['c']);
    }

    public function hasRole(int $userId, string $roleSlug): bool
    {
        $row = Database::query(
            'SELECT COUNT(*) c
             FROM user_roles ur
             JOIN roles r ON r.id = ur.role_id
             WHERE ur.user_id = ? AND r.slug = ?',
            [$userId, $roleSlug]
        )->fetch();

        return !empty($row['c']);
    }

    public function withRole(): array
    {
        return Database::query(
            'SELECT u.*, r.name AS role_name, r.slug AS role_slug
             FROM users u
             LEFT JOIN user_roles ur ON ur.user_id = u.id
             LEFT JOIN roles r ON r.id = ur.role_id
             ORDER BY u.id DESC'
        )->fetchAll();
    }

    public function countAll(): int
    {
        return (int) Database::query('SELECT COUNT(*) c FROM users')->fetch()['c'];
    }

    public function updateProfile(int $id, array $data): bool
    {
        $stmt = $this->db->prepare("
            UPDATE users
            SET first_name = ?,
                last_name = ?,
                username = ?,
                email = ?,
                bio = ?,
                discord = ?,
                avatar = ?,
                website = ?,
                location = ?,
                is_public_profile = ?,
                show_email_public = ?,
                updated_at = NOW()
            WHERE id = ?
        ");

        return $stmt->execute([
            $data['first_name'] ?? '',
            $data['last_name'] ?? '',
            $data['username'] ?? '',
            $data['email'] ?? '',
            $data['bio'] ?? '',
            $data['discord'] ?? '',
            $data['avatar'] ?? '',
            $data['website'] ?? '',
            $data['location'] ?? '',
            (int)($data['is_public_profile'] ?? 0),
            (int)($data['show_email_public'] ?? 0),
            $id,
        ]);
    }

    public function updatePassword(int $id, string $passwordHash): bool
    {
        $stmt = $this->db->prepare("UPDATE users SET password_hash = ?, updated_at = NOW() WHERE id = ?");
        return $stmt->execute([$passwordHash, $id]);
    }
    }
