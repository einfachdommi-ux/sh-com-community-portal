<?php
declare(strict_types=1);

define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');
define('STORAGE_PATH', BASE_PATH . '/storage');

require APP_PATH . '/Helpers/functions.php';

spl_autoload_register(function (string $class): void {
    $prefix = 'App\\';
    if (!str_starts_with($class, $prefix)) return;
    $relative = substr($class, strlen($prefix));
    $file = APP_PATH . '/' . str_replace('\\', '/', $relative) . '.php';
    if (file_exists($file)) require $file;
});

if (!file_exists(APP_PATH . '/Config/app.php') || !file_exists(APP_PATH . '/Config/database.php') || !file_exists(APP_PATH . '/Config/mail.php')) {
    exit('Bitte zuerst setup.php ausführen.');
}

date_default_timezone_set(config('app.timezone', 'Europe/Berlin'));
session_name(config('app.session_name', 'shcom_session'));
session_start();

use App\Core\Router;
use App\Controllers\Front\HomeController;
use App\Controllers\Front\AuthController;
use App\Controllers\Front\NewsController;
use App\Controllers\Front\ChangelogController;
use App\Controllers\Front\TeamController;
use App\Controllers\Front\PageController;
use App\Controllers\Admin\DashboardController;
use App\Controllers\Admin\UserController;
use App\Controllers\Admin\RoleController;
use App\Controllers\Admin\PermissionController;
use App\Controllers\Admin\ContentController;
use App\Controllers\Admin\LogController;
use App\Controllers\Admin\DbToolsController;
use App\Middleware\AdminMiddleware;

$router = new Router();

// Frontend
$router->get('/', [HomeController::class, 'index']);
$router->get('/login', [AuthController::class, 'login']);
$router->post('/login', [AuthController::class, 'login']);
$router->get('/register', [AuthController::class, 'register']);
$router->post('/register', [AuthController::class, 'register']);
$router->get('/verify-email/{token}', [AuthController::class, 'verifyEmail']);
$router->get('/logout', [AuthController::class, 'logout']);
$router->get('/news', [NewsController::class, 'index']);
$router->get('/news/{slug}', [NewsController::class, 'show']);
$router->get('/changelog', [ChangelogController::class, 'index']);
$router->get('/team', [TeamController::class, 'index']);
$router->get('/members', [TeamController::class, 'members']);
$router->get('/page/{slug}', [PageController::class, 'show']);

// Admin
$router->get('/admin', [DashboardController::class, 'index'], [AdminMiddleware::class]);
$router->get('/admin/users', [UserController::class, 'index'], [AdminMiddleware::class]);
$router->post('/admin/users/store', [UserController::class, 'store'], [AdminMiddleware::class]);

$router->get('/admin/roles', [RoleController::class, 'index'], [AdminMiddleware::class]);
$router->post('/admin/roles/store', [RoleController::class, 'store'], [AdminMiddleware::class]);
$router->post('/admin/roles/permissions', [RoleController::class, 'permissions'], [AdminMiddleware::class]);

$router->get('/admin/permissions', [PermissionController::class, 'index'], [AdminMiddleware::class]);
$router->post('/admin/permissions/store', [PermissionController::class, 'store'], [AdminMiddleware::class]);

$router->get('/admin/pages', [ContentController::class, 'pages'], [AdminMiddleware::class]);
$router->post('/admin/pages/store', [ContentController::class, 'pageStore'], [AdminMiddleware::class]);
$router->get('/admin/news', [ContentController::class, 'news'], [AdminMiddleware::class]);
$router->post('/admin/news/store', [ContentController::class, 'newsStore'], [AdminMiddleware::class]);
$router->get('/admin/changelogs', [ContentController::class, 'changelogs'], [AdminMiddleware::class]);
$router->post('/admin/changelogs/store', [ContentController::class, 'changelogStore'], [AdminMiddleware::class]);
$router->get('/admin/team', [ContentController::class, 'team'], [AdminMiddleware::class]);
$router->post('/admin/team/store', [ContentController::class, 'teamStore'], [AdminMiddleware::class]);
$router->get('/admin/navigation', [ContentController::class, 'navigation'], [AdminMiddleware::class]);
$router->post('/admin/navigation/store', [ContentController::class, 'navigationStore'], [AdminMiddleware::class]);

$router->get('/admin/audit-logs', [LogController::class, 'audit'], [AdminMiddleware::class]);
$router->get('/admin/mail-logs', [LogController::class, 'mail'], [AdminMiddleware::class]);
$router->get('/admin/db-tools', [DbToolsController::class, 'index'], [AdminMiddleware::class]);
$router->post('/admin/db-tools/run', [DbToolsController::class, 'run'], [AdminMiddleware::class]);

$router->dispatch($_SERVER['REQUEST_METHOD'] ?? 'GET', $_SERVER['REQUEST_URI'] ?? '/');
