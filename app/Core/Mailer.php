<?php
namespace App\Core;

class Mailer
{
    public function send(
        string $to,
        string $subject,
        string $html,
        ?int $relatedUserId = null,
        string $mailType = 'default'
    ): bool {
        $fromEmail = config('mail.from.address', 'noreply@sh-com.de');
        $fromName  = config('mail.from.name', 'Schleswig-Holstein Community');

        $encodedSubject = '=?UTF-8?B?' . base64_encode($subject) . '?=';

        $headers = [];
        $headers[] = 'MIME-Version: 1.0';
        $headers[] = 'Content-type: text/html; charset=UTF-8';
        $headers[] = 'From: ' . $fromName . ' <' . $fromEmail . '>';
        $headers[] = 'Reply-To: ' . $fromEmail;
        $headers[] = 'X-Mailer: PHP/' . phpversion();

        $success = @mail($to, $encodedSubject, $html, implode("\r\n", $headers));

        if ($success) {
            Logger::mail(
                $relatedUserId,
                $mailType,
                $to,
                $subject,
                'sent',
                'php-mail',
                null
            );
            return true;
        }

        $lastError = error_get_last();
        Logger::mail(
            $relatedUserId,
            $mailType,
            $to,
            $subject,
            'failed',
            'php-mail',
            $lastError['message'] ?? 'mail() Versand fehlgeschlagen'
        );

        return false;
    }
}