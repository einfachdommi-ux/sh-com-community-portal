<?php
namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Logger;
use App\Models\User;
use App\Models\Role;

class UserController extends Controller
{
    public function index(): void
    {
        $users = (new User())->withRole();
        $roles = (new Role())->all('name ASC');
        $this->view('admin/users', compact('users', 'roles'), 'backend');
    }

    public function store(): void
    {
        $this->requirePost();
        if (!hash_equals($_SESSION['_csrf'] ?? '', $_POST['_csrf'] ?? '')) {
            flash('error', 'Ungültiges CSRF-Token.');
            redirect('/admin/users');
        }

        $userModel = new User();
        $userId = $userModel->create([
            'username' => trim($_POST['username']),
            'email' => trim($_POST['email']),
            'password_hash' => password_hash($_POST['password'], PASSWORD_DEFAULT),
            'is_verified' => isset($_POST['is_verified']) ? 1 : 0,
            'is_active' => isset($_POST['is_active']) ? 1 : 0,
        ]);
        if (!empty($_POST['role_id'])) {
            $userModel->assignRole($userId, (int)$_POST['role_id']);
        }

        Logger::audit('users', 'create', 'user', $userId, null, ['email' => $_POST['email']]);
        flash('success', 'Benutzer angelegt.');
        redirect('/admin/users');
    }
}
