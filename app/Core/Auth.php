<?php
namespace App\Core;

use App\Models\User;

class Auth
{
    public static function user(): ?array
    {
        if (empty($_SESSION['user_id'])) {
            return null;
        }
        return (new User())->find((int) $_SESSION['user_id']);
    }

    public static function id(): ?int
    {
        return $_SESSION['user_id'] ?? null;
    }

    public static function check(): bool
    {
        return !empty($_SESSION['user_id']);
    }

    public static function attempt(string $email, string $password): bool
    {
        $user = (new User())->findByEmail($email);
        if (!$user || !$user['is_active'] || !$user['is_verified']) {
            return false;
        }

        if (!password_verify($password, $user['password_hash'])) {
            return false;
        }

        session_regenerate_id(true);
        $_SESSION['user_id'] = (int) $user['id'];
        (new User())->touchLogin((int) $user['id']);
        return true;
    }

    public static function logout(): void
    {
        unset($_SESSION['user_id']);
        session_regenerate_id(true);
    }

    public static function hasPermission(string $slug): bool
    {
        $user = self::user();
        if (!$user) {
            return false;
        }
        return (new User())->hasPermission((int)$user['id'], $slug);
    }

    public static function hasRole(string $slug): bool
    {
        $user = self::user();
        if (!$user) {
            return false;
        }
        return (new User())->hasRole((int)$user['id'], $slug);
    }
}
