<?php
declare(strict_types=1);

define('BASE_PATH', __DIR__);
define('APP_PATH', BASE_PATH . '/app');

function write_config(string $file, array $data): void {
    $content = "<?php\nreturn " . var_export($data, true) . ";\n";
    file_put_contents($file, $content);
}

$message = null;
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $app = [
            'app_name' => $_POST['app_name'],
            'base_url' => rtrim($_POST['base_url'], '/'),
            'timezone' => $_POST['timezone'] ?: 'Europe/Berlin',
            'debug' => false,
            'mail_from' => $_POST['mail_from'],
            'session_name' => 'shcom_session',
        ];

        $db = [
            'host' => $_POST['db_host'],
            'port' => $_POST['db_port'],
            'database' => $_POST['db_name'],
            'username' => $_POST['db_user'],
            'password' => $_POST['db_pass'],
            'charset' => 'utf8mb4',
        ];

        $mail = [
            'transport' => 'smtp',
            'host' => $_POST['smtp_host'],
            'port' => (int)$_POST['smtp_port'],
            'username' => $_POST['smtp_user'],
            'password' => $_POST['smtp_pass'],
            'encryption' => $_POST['smtp_encryption'],
            'from_email' => $_POST['mail_from'],
            'from_name' => $_POST['mail_from_name'],
        ];

        write_config(APP_PATH . '/Config/app.php', $app);
        write_config(APP_PATH . '/Config/database.php', $db);
        write_config(APP_PATH . '/Config/mail.php', $mail);

        $dsn = sprintf('mysql:host=%s;port=%s;charset=utf8mb4', $db['host'], $db['port']);
        $pdo = new PDO($dsn, $db['username'], $db['password'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);
        $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$db['database']}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        $pdo->exec("USE `{$db['database']}`");
        $schema = file_get_contents(BASE_PATH . '/database/schema.sql');
        $pdo->exec($schema);

        $adminUser = trim($_POST['admin_user']);
        $adminEmail = trim($_POST['admin_email']);
        $adminPass = password_hash($_POST['admin_pass'], PASSWORD_DEFAULT);

        $check = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $check->execute([$adminEmail]);
        if (!$check->fetch()) {
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password_hash, is_active, is_verified, created_at, updated_at) VALUES (?, ?, ?, 1, 1, NOW(), NOW())");
            $stmt->execute([$adminUser, $adminEmail, $adminPass]);
            $userId = (int)$pdo->lastInsertId();

            $roleId = (int)$pdo->query("SELECT id FROM roles WHERE slug = 'projektleitung' LIMIT 1")->fetchColumn();
            $stmt = $pdo->prepare("INSERT INTO user_roles (user_id, role_id) VALUES (?, ?)");
            $stmt->execute([$userId, $roleId]);
        }

        @copy(BASE_PATH . '/app.example.htaccess', BASE_PATH . '/.htaccess');

        $message = 'Installation erfolgreich. Öffne jetzt /public bzw. die konfigurierte Domain.';
    } catch (Throwable $e) {
        $error = $e->getMessage();
    }
}
?>
<!doctype html>
<html lang="de">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>SH-COM Setup</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body{background:#eef2f7}.card{border-radius:24px;box-shadow:0 20px 60px rgba(0,0,0,.08)}.header{background:linear-gradient(135deg,#081f39,#0b2f55);color:#fff;border-radius:24px;padding:32px}
</style>
</head>
<body>
<div class="container py-5">
  <div class="header mb-4 text-center">
    <img src="public/assets/img/logo.png" alt="Logo" height="80">
    <h1 class="h2 mt-3">SH-COM Setup</h1>
    <p class="mb-0">Installer für PHP 8.3 / MySQL / SMTP ohne Composer</p>
  </div>
  <?php if ($message): ?><div class="alert alert-success"><?= htmlspecialchars($message) ?></div><?php endif; ?>
  <?php if ($error): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>
  <div class="card"><div class="card-body p-4 p-lg-5">
    <form method="post">
      <h2 class="h4">App</h2>
      <div class="row g-3 mb-4">
        <div class="col-md-4"><label class="form-label">App Name</label><input class="form-control" name="app_name" value="SH-COM Portal" required></div>
        <div class="col-md-4"><label class="form-label">Domain / Base URL</label><input class="form-control" name="base_url" value="https://www.sh-com.de" required></div>
        <div class="col-md-4"><label class="form-label">Zeitzone</label><input class="form-control" name="timezone" value="Europe/Berlin" required></div>
      </div>

      <h2 class="h4">MySQL</h2>
      <div class="row g-3 mb-4">
        <div class="col-md-3"><label class="form-label">Host</label><input class="form-control" name="db_host" value="127.0.0.1" required></div>
        <div class="col-md-2"><label class="form-label">Port</label><input class="form-control" name="db_port" value="3306" required></div>
        <div class="col-md-3"><label class="form-label">Datenbank</label><input class="form-control" name="db_name" value="shcom" required></div>
        <div class="col-md-2"><label class="form-label">User</label><input class="form-control" name="db_user" required></div>
        <div class="col-md-2"><label class="form-label">Passwort</label><input class="form-control" type="password" name="db_pass"></div>
      </div>

      <h2 class="h4">SMTP</h2>
      <div class="row g-3 mb-4">
        <div class="col-md-3"><label class="form-label">Host</label><input class="form-control" name="smtp_host" required></div>
        <div class="col-md-2"><label class="form-label">Port</label><input class="form-control" name="smtp_port" value="587" required></div>
        <div class="col-md-2"><label class="form-label">TLS/SSL</label><select class="form-select" name="smtp_encryption"><option value="tls">tls</option><option value="ssl">ssl</option><option value="">none</option></select></div>
        <div class="col-md-2"><label class="form-label">SMTP User</label><input class="form-control" name="smtp_user" required></div>
        <div class="col-md-3"><label class="form-label">SMTP Passwort</label><input class="form-control" type="password" name="smtp_pass" required></div>
        <div class="col-md-6"><label class="form-label">Absender E-Mail</label><input class="form-control" name="mail_from" value="noreply@sh-com.de" required></div>
        <div class="col-md-6"><label class="form-label">Absender Name</label><input class="form-control" name="mail_from_name" value="SH-COM" required></div>
      </div>

      <h2 class="h4">Admin</h2>
      <div class="row g-3 mb-4">
        <div class="col-md-4"><label class="form-label">Admin Benutzername</label><input class="form-control" name="admin_user" required></div>
        <div class="col-md-4"><label class="form-label">Admin E-Mail</label><input class="form-control" type="email" name="admin_email" required></div>
        <div class="col-md-4"><label class="form-label">Admin Passwort</label><input class="form-control" type="password" name="admin_pass" required></div>
      </div>

      <button class="btn btn-danger btn-lg w-100">Installation starten</button>
    </form>
  </div></div>
</div>
</body>
</html>
