<?php
namespace App\Models;

use App\Core\Database;
use PDO;

class User
{
    public function find(int $id): ?array
    {
        $stmt = Database::query("SELECT * FROM users WHERE id = :id LIMIT 1", ['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function findByEmail(string $email): ?array
    {
        $stmt = Database::query("SELECT * FROM users WHERE email = :email LIMIT 1", ['email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function findByUsername(string $username): ?array
    {
        $stmt = Database::query("SELECT * FROM users WHERE username = :username LIMIT 1", ['username' => $username]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function findPublicProfileByUsername(string $username): ?array
    {
        $stmt = Database::query("
            SELECT 
                u.*,
                GROUP_CONCAT(DISTINCT r.name ORDER BY r.name SEPARATOR ', ') AS role_names,
                GROUP_CONCAT(DISTINCT r.slug ORDER BY r.slug SEPARATOR ', ') AS role_slugs
            FROM users u
            LEFT JOIN user_roles ur ON ur.user_id = u.id
            LEFT JOIN roles r ON r.id = ur.role_id
            WHERE u.username = :username
            GROUP BY u.id
            LIMIT 1
        ", ['username' => $username]);

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function countAll(): int
    {
        $stmt = Database::query("SELECT COUNT(*) AS total FROM users");
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)($row['total'] ?? 0);
    }

    public function all(): array
    {
        $stmt = Database::query("SELECT * FROM users ORDER BY id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function withRole(): array
    {
        $stmt = Database::query("
            SELECT 
                u.*,
                COALESCE(GROUP_CONCAT(DISTINCT r.name ORDER BY r.name SEPARATOR ', '), 'Gast') AS role_name,
                COALESCE(GROUP_CONCAT(DISTINCT r.slug ORDER BY r.slug SEPARATOR ', '), 'gast') AS role_slug,
                COALESCE(GROUP_CONCAT(DISTINCT r.name ORDER BY r.name SEPARATOR ', '), 'Gast') AS role_names,
                COALESCE(GROUP_CONCAT(DISTINCT r.slug ORDER BY r.slug SEPARATOR ', '), 'gast') AS role_slugs
            FROM users u
            LEFT JOIN user_roles ur ON ur.user_id = u.id
            LEFT JOIN roles r ON r.id = ur.role_id
            GROUP BY u.id
            ORDER BY u.id DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create(array $data): int
    {
        Database::query("
            INSERT INTO users
            (username,email,password_hash,first_name,last_name,avatar,bio,discord,website,location,instagram,facebook,snapchat,x_profile,epic_games,steam,ea_app,twitch,youtube,is_public_profile,show_email_public,email_verification_token,is_verified,is_active,created_at,updated_at)
            VALUES
            (:username,:email,:password_hash,:first_name,:last_name,:avatar,:bio,:discord,:website,:location,:instagram,:facebook,:snapchat,:x_profile,:epic_games,:steam,:ea_app,:twitch,:youtube,:is_public_profile,:show_email_public,:token,:verified,:active,NOW(),NOW())
        ", [
            'username' => $data['username'] ?? '',
            'email' => $data['email'] ?? '',
            'password_hash' => $data['password_hash'] ?? '',
            'first_name' => $data['first_name'] ?? null,
            'last_name' => $data['last_name'] ?? null,
            'avatar' => $data['avatar'] ?? null,
            'bio' => $data['bio'] ?? null,
            'discord' => $data['discord'] ?? null,
            'website' => $data['website'] ?? null,
            'location' => $data['location'] ?? null,
            'instagram' => $data['instagram'] ?? null,
            'facebook' => $data['facebook'] ?? null,
            'snapchat' => $data['snapchat'] ?? null,
            'x_profile' => $data['x_profile'] ?? null,
            'epic_games' => $data['epic_games'] ?? null,
            'steam' => $data['steam'] ?? null,
            'ea_app' => $data['ea_app'] ?? null,
            'twitch' => $data['twitch'] ?? null,
            'youtube' => $data['youtube'] ?? null,
            'is_public_profile' => !empty($data['is_public_profile']) ? 1 : 0,
            'show_email_public' => !empty($data['show_email_public']) ? 1 : 0,
            'token' => $data['email_verification_token'] ?? null,
            'verified' => (int)($data['is_verified'] ?? 0),
            'active' => (int)($data['is_active'] ?? 1)
        ]);

        if (method_exists(Database::class, 'pdo')) {
            return (int) Database::pdo()->lastInsertId();
        }

        $stmt = Database::query("SELECT LAST_INSERT_ID() AS id");
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)($row['id'] ?? 0);
    }

    public function assignRole(int $userId, int $roleId): void
    {
        Database::query("INSERT INTO user_roles (user_id, role_id) VALUES (:user_id, :role_id)", [
            'user_id' => $userId,
            'role_id' => $roleId
        ]);
    }

    public function hasRole(int $userId, string $slug): bool
    {
        $stmt = Database::query("
            SELECT r.id
            FROM roles r
            JOIN user_roles ur ON ur.role_id = r.id
            WHERE ur.user_id = :uid AND r.slug = :slug
            LIMIT 1
        ", ['uid' => $userId, 'slug' => $slug]);
        return (bool)$stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function hasPermission(int $userId, string $slug): bool
    {
        $stmt = Database::query("
            SELECT p.id
            FROM permissions p
            JOIN role_permissions rp ON rp.permission_id = p.id
            JOIN user_roles ur ON ur.role_id = rp.role_id
            WHERE ur.user_id = :uid AND p.slug = :slug
            LIMIT 1
        ", ['uid' => $userId, 'slug' => $slug]);
        return (bool)$stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function touchLogin(int $id): void
    {
        Database::query("UPDATE users SET last_login_at = NOW() WHERE id = :id", ['id' => $id]);
    }

    public function verifyByToken(string $token): bool
    {
        $stmt = Database::query("SELECT id FROM users WHERE email_verification_token = :token LIMIT 1", ['token' => $token]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$user) {
            return false;
        }
        Database::query("
            UPDATE users
            SET is_verified = 1,
                email_verification_token = NULL,
                updated_at = NOW()
            WHERE id = :id
        ", ['id' => $user['id']]);
        return true;
    }

    public function updateProfile(int $id, array $data): bool
    {
        Database::query("
            UPDATE users
            SET
                first_name = :first_name,
                last_name = :last_name,
                username = :username,
                email = :email,
                bio = :bio,
                discord = :discord,
                website = :website,
                location = :location,
                instagram = :instagram,
                facebook = :facebook,
                snapchat = :snapchat,
                x_profile = :x_profile,
                epic_games = :epic_games,
                steam = :steam,
                ea_app = :ea_app,
                twitch = :twitch,
                youtube = :youtube,
                avatar = :avatar,
                is_public_profile = :is_public_profile,
                show_email_public = :show_email_public,
                updated_at = NOW()
            WHERE id = :id
        ", [
            'id' => $id,
            'first_name' => $data['first_name'] ?? null,
            'last_name' => $data['last_name'] ?? null,
            'username' => $data['username'] ?? '',
            'email' => $data['email'] ?? '',
            'bio' => $data['bio'] ?? null,
            'discord' => $data['discord'] ?? null,
            'website' => $data['website'] ?? null,
            'location' => $data['location'] ?? null,
            'instagram' => $data['instagram'] ?? null,
            'facebook' => $data['facebook'] ?? null,
            'snapchat' => $data['snapchat'] ?? null,
            'x_profile' => $data['x_profile'] ?? null,
            'epic_games' => $data['epic_games'] ?? null,
            'steam' => $data['steam'] ?? null,
            'ea_app' => $data['ea_app'] ?? null,
            'twitch' => $data['twitch'] ?? null,
            'youtube' => $data['youtube'] ?? null,
            'avatar' => $data['avatar'] ?? null,
            'is_public_profile' => !empty($data['is_public_profile']) ? 1 : 0,
            'show_email_public' => !empty($data['show_email_public']) ? 1 : 0
        ]);
        return true;
    }

    public function updatePassword(int $id, string $hash): bool
    {
        Database::query("
            UPDATE users
            SET password_hash = :hash,
                updated_at = NOW()
            WHERE id = :id
        ", ['id' => $id, 'hash' => $hash]);
        return true;
    }
}
