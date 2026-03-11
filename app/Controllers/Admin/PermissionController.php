<?php
namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Logger;
use App\Models\Permission;

class PermissionController extends Controller
{
    public function index(): void
    {
        $permissions = (new Permission())->all('name ASC');
        $this->view('admin/permissions', compact('permissions'), 'backend');
    }

    public function store(): void
    {
        $this->requirePost();
        $id = (new Permission())->create([
            'name' => $_POST['name'],
            'slug' => $_POST['slug'],
            'description' => $_POST['description'] ?? null,
        ]);
        Logger::audit('permissions', 'create', 'permission', $id);
        flash('success', 'Permission angelegt.');
        redirect('/admin/permissions');
    }
}
