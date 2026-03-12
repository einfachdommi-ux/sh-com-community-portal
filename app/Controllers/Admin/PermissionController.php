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

    public function edit(int $id): void
    {
        $permission = (new Permission())->find($id);
        if (!$permission) {
            flash('error', 'Permission nicht gefunden.');
            redirect('/admin/permissions');
        }

        $this->view('admin/permission_edit', compact('permission'), 'backend');
    }

    public function update(int $id): void
    {
        $this->requirePost();

        $model = new Permission();
        $permission = $model->find($id);

        if (!$permission) {
            flash('error', 'Permission nicht gefunden.');
            redirect('/admin/permissions');
        }

        $data = [
            'name' => trim((string)($_POST['name'] ?? '')),
            'slug' => trim((string)($_POST['slug'] ?? '')),
            'description' => trim((string)($_POST['description'] ?? '')) ?: null,
        ];

        $model->updateFields($id, $data);
        Logger::audit('permissions', 'update', 'permission', $id, $permission, $data);
        flash('success', 'Permission aktualisiert.');
        redirect('/admin/permissions');
    }

    public function delete(int $id): void
    {
        $this->requirePost();

        $model = new Permission();
        $permission = $model->find($id);

        if (!$permission) {
            flash('error', 'Permission nicht gefunden.');
            redirect('/admin/permissions');
        }

        $model->delete($id);
        Logger::audit('permissions', 'delete', 'permission', $id, $permission, null);
        flash('success', 'Permission gelöscht.');
        redirect('/admin/permissions');
    }
}
