<?php
namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Logger;
use App\Models\Permission;
use App\Models\Role;

class RoleController extends Controller
{
    public function index(): void
    {
        $roles = (new Role())->all('name ASC');
        $permissions = (new Permission())->all('name ASC');
        $permissionModel = new Permission();
        $selected = [];

        foreach ($roles as $role) {
            $selected[$role['id']] = $permissionModel->idsForRole((int)$role['id']);
        }

        $this->view('admin/roles', compact('roles', 'permissions', 'selected'), 'backend');
    }

    public function store(): void
    {
        $this->requirePost();

        $roleId = (new Role())->create([
            'name' => $_POST['name'],
            'slug' => $_POST['slug'],
            'description' => $_POST['description'] ?? null,
        ]);

        Logger::audit('roles', 'create', 'role', $roleId, null, ['slug' => $_POST['slug']]);
        flash('success', 'Rolle angelegt.');
        redirect('/admin/roles');
    }

    public function edit(int $id): void
    {
        $role = (new Role())->find($id);
        if (!$role) {
            flash('error', 'Rolle nicht gefunden.');
            redirect('/admin/roles');
        }

        $this->view('admin/role_edit', compact('role'), 'backend');
    }

    public function update(int $id): void
    {
        $this->requirePost();

        $model = new Role();
        $role = $model->find($id);

        if (!$role) {
            flash('error', 'Rolle nicht gefunden.');
            redirect('/admin/roles');
        }

        $data = [
            'name' => trim((string)($_POST['name'] ?? '')),
            'slug' => trim((string)($_POST['slug'] ?? '')),
            'description' => trim((string)($_POST['description'] ?? '')) ?: null,
        ];

        $model->updateFields($id, $data);
        Logger::audit('roles', 'update', 'role', $id, $role, $data);
        flash('success', 'Rolle aktualisiert.');
        redirect('/admin/roles');
    }

    public function delete(int $id): void
    {
        $this->requirePost();

        $model = new Role();
        $role = $model->find($id);

        if (!$role) {
            flash('error', 'Rolle nicht gefunden.');
            redirect('/admin/roles');
        }

        $model->delete($id);
        Logger::audit('roles', 'delete', 'role', $id, $role, null);
        flash('success', 'Rolle gelöscht.');
        redirect('/admin/roles');
    }

    public function permissions(): void
    {
        $this->requirePost();
        (new Permission())->assignToRole((int)$_POST['role_id'], array_map('intval', $_POST['permission_ids'] ?? []));
        Logger::audit('roles', 'assign_permissions', 'role', (int)$_POST['role_id']);
        flash('success', 'Permissions aktualisiert.');
        redirect('/admin/roles');
    }
}
