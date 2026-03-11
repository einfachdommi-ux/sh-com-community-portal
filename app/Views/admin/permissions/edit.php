<?php /** @var array $permission */ ?>
<div class="container py-4">
    <h1 class="h3 mb-4">Permission bearbeiten</h1>
    <div class="card shadow-sm">
        <div class="card-body">
            <form method="post" action="/admin/permissions/update/<?= (int)$permission['id'] ?>" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Name</label>
                    <input type="text" name="name" class="form-control" value="<?= htmlspecialchars((string)$permission['name']) ?>" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Slug</label>
                    <input type="text" name="slug" class="form-control" value="<?= htmlspecialchars((string)$permission['slug']) ?>" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Beschreibung</label>
                    <input type="text" name="description" class="form-control" value="<?= htmlspecialchars((string)($permission['description'] ?? '')) ?>">
                </div>
                <div class="col-12">
                    <button class="btn btn-primary">Änderungen speichern</button>
                    <a href="/admin/permissions" class="btn btn-outline-secondary">Zurück</a>
                </div>
            </form>
        </div>
    </div>
</div>
