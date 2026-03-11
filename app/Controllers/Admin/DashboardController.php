<?php
namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Database;
use App\Models\User;
use App\Models\News;
use App\Models\Changelog;
use App\Models\TeamMember;

class DashboardController extends Controller
{
    public function index(): void
    {
        $stats = [
            'users' => (new User())->countAll(),
            'news' => (new News())->countAll(),
            'changelogs' => (new Changelog())->countAll(),
            'team' => (new TeamMember())->countAll(),
        ];
        $auditLogs = Database::query('SELECT a.*, u.username FROM audit_logs a LEFT JOIN users u ON u.id = a.admin_user_id ORDER BY a.id DESC LIMIT 10')->fetchAll();
        $mailLogs = Database::query('SELECT * FROM mail_logs ORDER BY id DESC LIMIT 10')->fetchAll();
        $this->view('admin/dashboard', compact('stats', 'auditLogs', 'mailLogs'), 'backend');
    }
}
