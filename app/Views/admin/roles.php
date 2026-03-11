<?php /** @var array $roles */ /** @var array $permissions */ /** @var array $selected */ ?>
<div class="container py-4">
    <h1 class="h3 mb-4">Rollen</h1>

    <div class="card shadow-sm mb-4">
        <div class="card-header"><strong>Neue Rolle</strong></div>
        <div class="card-body">
            <form method="post" action="/admin/roles/store" class="row g-3">
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
                    <button class="btn btn-primary">Rolle speichern</button>
                </div>
            </form>
        </div>
    </div>

    <?php foreach ($roles as $role): ?>
        <div class="card shadow-sm mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <strong><?= htmlspecialchars((string)$role['name']) ?></strong>
                <div>
                    <a href="/admin/roles/edit/<?= (int)$role['id'] ?>" class="btn btn-sm btn-warning">Bearbeiten</a>
                    <form method="post" action="/admin/roles/delete/<?= (int)$role['id'] ?>" class="d-inline" onsubmit="return confirm('Rolle wirklich löschen?');">
                        <button class="btn btn-sm btn-danger">Löschen</button>
                    </form>
                </div>
            </div>
            <div class="card-body">
                <p class="mb-2"><strong>Slug:</strong> <?= htmlspecialchars((string)($role['slug'] ?? '')) ?></p>
                <p class="text-muted"><?= htmlspecialchars((string)($role['description'] ?? '')) ?></p>
                <form method="post" action="/admin/roles/permissions">
                    <input type="hidden" name="role_id" value="<?= (int)$role['id'] ?>">
                    <div class="row">
                        <?php foreach ($permissions as $permission): ?>
                            <div class="col-md-4 mb-2">
                                <div class="form-check">
                                    <input
                                        class="form-check-input"
                                        type="checkbox"
                                        name="permission_ids[]"
                                        value="<?= (int)$permission['id'] ?>"
                                        id="perm_<?= (int)$role['id'] ?>_<?= (int)$permission['id'] ?>"
                                        <?= in_array((int)$permission['id'], $selected[$role['id']] ?? [], true) ? 'checked' : '' ?>
                                    >
                                    <label class="form-check-label" for="perm_<?= (int)$role['id'] ?>_<?= (int)$permission['id'] ?>">
                                        <?= htmlspecialchars((string)$permission['name']) ?>
                                        <small class="text-muted d-block"><?= htmlspecialchars((string)($permission['slug'] ?? '')) ?></small>
                                    </label>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <button class="btn btn-outline-primary mt-3">Permissions speichern</button>
                </form>
            </div>
        </div>
    <?php endforeach; ?>
</div>
