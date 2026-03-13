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
use App\Controllers\Front\ProfileController;
use App\Middleware\AuthMiddleware;
use App\Controllers\Front\HofController;
use App\Controllers\Front\HofDashboardController;
use App\Controllers\Front\FieldsController;
use App\Controllers\Admin\AdminFarmsController;

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
$router->get('/profile', [ProfileController::class, 'index'], [AuthMiddleware::class]);
$router->post('/profile/update', [ProfileController::class, 'update'], [AuthMiddleware::class]);
$router->post('/profile/security', [ProfileController::class, 'security'], [AuthMiddleware::class]);
$router->get('/profile/{username}', [ProfileController::class, 'public']);
// ===========================
// LS 25 Portal
// ===========================

$router->get('/hof', [HofController::class, 'index'], [AuthMiddleware::class]);
$router->post('/hof/join', [HofController::class, 'join'], [AuthMiddleware::class]);
$router->get('/hof/leave-confirm', [HofController::class, 'leaveConfirm'], [AuthMiddleware::class]);
$router->post('/hof/leave', [HofController::class, 'leave'], [AuthMiddleware::class]);
$router->post('/hof/password', [HofController::class, 'setPassword'], [AuthMiddleware::class]);
$router->post('/hof/invite/create', [HofController::class, 'createInvite'], [AuthMiddleware::class]);
$router->get('/hof/invite/{token}', [HofController::class, 'invite'], [AuthMiddleware::class]);
$router->post('/hof/member/remove', [HofController::class, 'removeMember'], [AuthMiddleware::class]);
$router->get('/hof/dashboard', [HofDashboardController::class, 'index'], [AuthMiddleware::class]);

$router->get('/fields', [FieldsController::class, 'index'], [AuthMiddleware::class]);
$router->get('/fields/create', [FieldsController::class, 'create'], [AuthMiddleware::class]);
$router->post('/fields/store', [FieldsController::class, 'store'], [AuthMiddleware::class]);
$router->get('/fields/edit/{id}', [FieldsController::class, 'edit'], [AuthMiddleware::class]);
$router->post('/fields/update/{id}', [FieldsController::class, 'update'], [AuthMiddleware::class]);
$router->post('/fields/delete/{id}', [FieldsController::class, 'delete'], [AuthMiddleware::class]);

// Admin-Routen ergänzen
$router->get('/admin/farms', [AdminFarmsController::class, 'index'], [AdminMiddleware::class]);
$router->get('/admin/farms/create', [AdminFarmsController::class, 'create'], [AdminMiddleware::class]);
$router->post('/admin/farms/store', [AdminFarmsController::class, 'store'], [AdminMiddleware::class]);
$router->get('/admin/farms/edit/{id}', [AdminFarmsController::class, 'edit'], [AdminMiddleware::class]);
$router->post('/admin/farms/update/{id}', [AdminFarmsController::class, 'update'], [AdminMiddleware::class]);
$router->post('/admin/farms/delete/{id}', [AdminFarmsController::class, 'delete'], [AdminMiddleware::class]);


// Admin
$router->get('/admin', [DashboardController::class, 'index'], [AdminMiddleware::class]);
$router->get('/admin/users', [UserController::class, 'index'], [AdminMiddleware::class]);
$router->post('/admin/users/store', [UserController::class, 'store'], [AdminMiddleware::class]);

$router->get('/admin/roles', [RoleController::class, 'index'], [AdminMiddleware::class]);
$router->post('/admin/roles/store', [RoleController::class, 'store'], [AdminMiddleware::class]);
$router->get('/admin/roles/edit/{id}', [RoleController::class, 'edit'], [AdminMiddleware::class]);
$router->post('/admin/roles/update/{id}', [RoleController::class, 'update'], [AdminMiddleware::class]);
$router->post('/admin/roles/delete/{id}', [RoleController::class, 'delete'], [AdminMiddleware::class]);
$router->post('/admin/roles/permissions', [RoleController::class, 'permissions'], [AdminMiddleware::class]);

$router->get('/admin/permissions', [PermissionController::class, 'index'], [AdminMiddleware::class]);
$router->post('/admin/permissions/store', [PermissionController::class, 'store'], [AdminMiddleware::class]);
$router->get('/admin/permissions/edit/{id}', [PermissionController::class, 'edit'], [AdminMiddleware::class]);
$router->post('/admin/permissions/update/{id}', [PermissionController::class, 'update'], [AdminMiddleware::class]);
$router->post('/admin/permissions/delete/{id}', [PermissionController::class, 'delete'], [AdminMiddleware::class]);

$router->get('/admin/pages', [ContentController::class, 'pages'], [AdminMiddleware::class]);
$router->post('/admin/pages/store', [ContentController::class, 'pageStore'], [AdminMiddleware::class]);
$router->get('/admin/pages/edit/{id}', [ContentController::class, 'pageEdit'], [AdminMiddleware::class]);
$router->post('/admin/pages/update/{id}', [ContentController::class, 'pageUpdate'], [AdminMiddleware::class]);
$router->post('/admin/pages/delete/{id}', [ContentController::class, 'pageDelete'], [AdminMiddleware::class]);

$router->get('/admin/news', [ContentController::class, 'news'], [AdminMiddleware::class]);
$router->post('/admin/news/store', [ContentController::class, 'newsStore'], [AdminMiddleware::class]);
$router->get('/admin/news/edit/{id}', [ContentController::class, 'newsEdit'], [AdminMiddleware::class]);
$router->post('/admin/news/update/{id}', [ContentController::class, 'newsUpdate'], [AdminMiddleware::class]);
$router->post('/admin/news/delete/{id}', [ContentController::class, 'newsDelete'], [AdminMiddleware::class]);

$router->get('/admin/changelogs', [ContentController::class, 'changelogs'], [AdminMiddleware::class]);
$router->post('/admin/changelogs/store', [ContentController::class, 'changelogStore'], [AdminMiddleware::class]);
$router->get('/admin/changelogs/edit/{id}', [ContentController::class, 'changelogEdit'], [AdminMiddleware::class]);
$router->post('/admin/changelogs/update/{id}', [ContentController::class, 'changelogUpdate'], [AdminMiddleware::class]);
$router->post('/admin/changelogs/delete/{id}', [ContentController::class, 'changelogDelete'], [AdminMiddleware::class]);

$router->get('/admin/team', [ContentController::class, 'team'], [AdminMiddleware::class]);
$router->post('/admin/team/store', [ContentController::class, 'teamStore'], [AdminMiddleware::class]);
$router->get('/admin/team/edit/{id}', [ContentController::class, 'teamEdit'], [AdminMiddleware::class]);
$router->post('/admin/team/update/{id}', [ContentController::class, 'teamUpdate'], [AdminMiddleware::class]);
$router->post('/admin/team/delete/{id}', [ContentController::class, 'teamDelete'], [AdminMiddleware::class]);
$router->post('/admin/team/sort', [ContentController::class, 'teamSort'], [AdminMiddleware::class]);

$router->get('/admin/navigation', [ContentController::class, 'navigation'], [AdminMiddleware::class]);
$router->post('/admin/navigation/store', [ContentController::class, 'navigationStore'], [AdminMiddleware::class]);
$router->get('/admin/navigation/edit/{id}', [ContentController::class, 'navigationEdit'], [AdminMiddleware::class]);
$router->post('/admin/navigation/update/{id}', [ContentController::class, 'navigationUpdate'], [AdminMiddleware::class]);
$router->post('/admin/navigation/delete/{id}', [ContentController::class, 'navigationDelete'], [AdminMiddleware::class]);

$router->get('/admin/audit-logs', [LogController::class, 'audit'], [AdminMiddleware::class]);
$router->get('/admin/mail-logs', [LogController::class, 'mail'], [AdminMiddleware::class]);
$router->get('/admin/db-tools', [DbToolsController::class, 'index'], [AdminMiddleware::class]);
$router->post('/admin/db-tools/run', [DbToolsController::class, 'run'], [AdminMiddleware::class]);

$router->dispatch($_SERVER['REQUEST_METHOD'] ?? 'GET', $_SERVER['REQUEST_URI'] ?? '/');
