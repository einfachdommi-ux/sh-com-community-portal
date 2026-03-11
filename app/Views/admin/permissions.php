<?php /** @var array $permissions */ ?>
<div class="container py-4">
    <h1 class="h3 mb-4">Permissions</h1>

    <div class="card shadow-sm mb-4">
        <div class="card-header"><strong>Neue Permission</strong></div>
        <div class="card-body">
            <form method="post" action="/admin/permissions/store" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Name</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Slug</label>
                    <input type="text" name="slug" class="form-control" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Beschreibung</label>
                    <input type="text" name="description" class="form-control">
                </div>
                <div class="col-12">
                    <button class="btn btn-primary">Permission speichern</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header"><strong>Vorhandene Permissions</strong></div>
        <div class="card-body">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Slug</th>
                        <th>Beschreibung</th>
                        <th style="width:220px;">Aktionen</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($permissions as $permission): ?>
                    <tr>
                        <td><?= htmlspecialchars((string)$permission['name']) ?></td>
                        <td><?= htmlspecialchars((string)$permission['slug']) ?></td>
                        <td><?= htmlspecialchars((string)($permission['description'] ?? '')) ?></td>
                        <td>
                            <a href="/admin/permissions/edit/<?= (int)$permission['id'] ?>" class="btn btn-sm btn-warning">Bearbeiten</a>
                            <form method="post" action="/admin/permissions/delete/<?= (int)$permission['id'] ?>" class="d-inline" onsubmit="return confirm('Permission wirklich löschen?');">
                                <button class="btn btn-sm btn-danger">Löschen</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
