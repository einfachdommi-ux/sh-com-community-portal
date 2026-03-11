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
            'name' => trim((string) ($_POST['name'] ?? '')),
            'slug' => trim((string) ($_POST['slug'] ?? '')),
            'description' => trim((string) ($_POST['description'] ?? '')),
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

        $this->view('admin/permissions/edit', compact('permission'), 'backend');
    }

    public function update(int $id): void
    {
        $this->requirePost();

        $model = new Permission();
        $old = $model->find($id);

        if (!$old) {
            flash('error', 'Permission nicht gefunden.');
            redirect('/admin/permissions');
        }

        $data = [
            'name' => trim((string) ($_POST['name'] ?? '')),
            'slug' => trim((string) ($_POST['slug'] ?? '')),
            'description' => trim((string) ($_POST['description'] ?? '')),
        ];

        $model->update($id, $data);
        Logger::audit('permissions', 'update', 'permission', $id, $old, $data);

        flash('success', 'Permission aktualisiert.');
        redirect('/admin/permissions');
    }

    public function delete(int $id): void
    {
        $this->requirePost();

        $model = new Permission();
        $old = $model->find($id);

        if (!$old) {
            flash('error', 'Permission nicht gefunden.');
            redirect('/admin/permissions');
        }

        $model->delete($id);
        Logger::audit('permissions', 'delete', 'permission', $id, $old, null);

        flash('success', 'Permission gelöscht.');
        redirect('/admin/permissions');
    }
}
