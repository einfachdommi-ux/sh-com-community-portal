<?php
namespace App\Core;

class Mailer
{
    public function send(string $to, string $subject, string $html, ?int $relatedUserId = null, string $mailType = 'generic'): bool
    {
        $cfg = require APP_PATH . '/Config/mail.php';
        $host = $cfg['host'];
        $port = (int)$cfg['port'];
        $username = $cfg['username'];
        $password = $cfg['password'];
        $encryption = strtolower((string)$cfg['encryption']);
        $fromEmail = $cfg['from_email'];
        $fromName = $cfg['from_name'];

        $remote = ($encryption === 'ssl' ? 'ssl://' : '') . $host;
        $fp = @fsockopen($remote, $port, $errno, $errstr, 20);
        if (!$fp) {
            Logger::mail($relatedUserId, $mailType, $to, $subject, 'failed', null, $errstr);
            return false;
        }

        stream_set_timeout($fp, 20);
        $this->expect($fp, [220]);
        $this->command($fp, 'EHLO ' . parse_url(config('app.base_url'), PHP_URL_HOST), [250]);

        if ($encryption === 'tls') {
            $this->command($fp, 'STARTTLS', [220]);
            stream_socket_enable_crypto($fp, true, STREAM_CRYPTO_METHOD_TLS_CLIENT);
            $this->command($fp, 'EHLO ' . parse_url(config('app.base_url'), PHP_URL_HOST), [250]);
        }

        $this->command($fp, 'AUTH LOGIN', [334]);
        $this->command($fp, base64_encode($username), [334]);
        $this->command($fp, base64_encode($password), [235]);

        $this->command($fp, 'MAIL FROM:<' . $fromEmail . '>', [250]);
        $this->command($fp, 'RCPT TO:<' . $to . '>', [250, 251]);
        $this->command($fp, 'DATA', [354]);

        $headers = [];
        $headers[] = 'Date: ' . date('r');
        $headers[] = 'From: ' . sprintf('"%s" <%s>', addslashes($fromName), $fromEmail);
        $headers[] = 'To: <' . $to . '>';
        $headers[] = 'Subject: =?UTF-8?B?' . base64_encode($subject) . '?=';
        $headers[] = 'MIME-Version: 1.0';
        $headers[] = 'Content-Type: text/html; charset=UTF-8';
        $headers[] = 'Content-Transfer-Encoding: 8bit';

        $message = implode("\r\n", $headers) . "\r\n\r\n" . $html . "\r\n.";
        $this->command($fp, $message, [250]);
        $this->command($fp, 'QUIT', [221]);

        fclose($fp);
        Logger::mail($relatedUserId, $mailType, $to, $subject, 'sent', 'SMTP ok', null);
        return true;
    }

    private function command($fp, string $command, array $expected): void
    {
        fwrite($fp, $command . "\r\n");
        $this->expect($fp, $expected);
    }

    private function expect($fp, array $codes): void
    {
        $response = '';
        while (($line = fgets($fp, 515)) !== false) {
            $response .= $line;
            if (preg_match('/^\d{3}\s/', $line)) {
                break;
            }
        }
        $code = (int)substr($response, 0, 3);
        if (!in_array($code, $codes, true)) {
            throw new \RuntimeException('SMTP error: ' . trim($response));
        }
    }
}
