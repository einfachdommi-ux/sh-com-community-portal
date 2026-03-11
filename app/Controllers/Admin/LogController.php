<?php
namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Database;

class LogController extends Controller
{
    public function audit(): void
    {
        $items = Database::query('SELECT a.*, u.username FROM audit_logs a LEFT JOIN users u ON u.id = a.admin_user_id ORDER BY a.id DESC LIMIT 250')->fetchAll();
        $this->view('admin/audit_logs', compact('items'), 'backend');
    }

    public function mail(): void
    {
        $items = Database::query('SELECT * FROM mail_logs ORDER BY id DESC LIMIT 250')->fetchAll();
        $this->view('admin/mail_logs', compact('items'), 'backend');
    }
}
