<?php
namespace App\Core;

class Logger
{
    public static function audit(string $module, string $action, ?string $entityType = null, ?int $entityId = null, ?array $old = null, ?array $new = null): void
    {
        try {
            Database::query(
                'INSERT INTO audit_logs (admin_user_id, module, action, entity_type, entity_id, old_values, new_values, ip_address, user_agent, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
                [
                    $_SESSION['user_id'] ?? 0,
                    $module,
                    $action,
                    $entityType,
                    $entityId,
                    $old ? json_encode($old, JSON_UNESCAPED_UNICODE) : null,
                    $new ? json_encode($new, JSON_UNESCAPED_UNICODE) : null,
                    $_SERVER['REMOTE_ADDR'] ?? null,
                    substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 1000),
                    now()
                ]
            );
        } catch (\Throwable $e) {
            @file_put_contents(STORAGE_PATH . '/logs/audit_fallback.log', '[' . now() . '] ' . $e->getMessage() . PHP_EOL, FILE_APPEND);
        }
    }

    public static function mail(?int $userId, string $type, string $recipient, string $subject, string $status, ?string $providerResponse = null, ?string $error = null): void
    {
        try {
            Database::query(
                'INSERT INTO mail_logs (related_user_id, mail_type, recipient_email, subject, status, provider_response, error_message, sent_at, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)',
                [$userId, $type, $recipient, $subject, $status, $providerResponse, $error, now(), now()]
            );
        } catch (\Throwable $e) {
            @file_put_contents(STORAGE_PATH . '/logs/mail_fallback.log', '[' . now() . '] ' . $e->getMessage() . PHP_EOL, FILE_APPEND);
        }
    }
}
