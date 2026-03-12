<div class="container py-4">
    <h1 class="h3 mb-4">Permission bearbeiten</h1>
    <div class="card shadow-sm">
        <div class="card-body">
            <form method="post" action="/admin/permissions/update/<?= (int)$permission['id'] ?>" enctype="multipart/form-data" class="row g-3">

                <div class="col-md-6">
                    <label class="form-label">Name</label>
                    <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($permission['name'] ?? '') ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Slug</label>
                    <input type="text" name="slug" class="form-control" value="<?= htmlspecialchars($permission['slug'] ?? '') ?>" required>
                </div>
                <div class="col-12">
                    <label class="form-label">Beschreibung</label>
                    <textarea name="description" class="form-control" rows="5"><?= htmlspecialchars($permission['description'] ?? '') ?></textarea>
                </div>

                <div class="col-12">
                    <button type="submit" class="btn btn-primary">Speichern</button>
                    <a href="/admin/permissions" class="btn btn-outline-secondary">Zurück</a>
                </div>
            </form>
        </div>
    </div>
</div>
