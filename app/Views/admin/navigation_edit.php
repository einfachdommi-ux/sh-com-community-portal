<div class="container py-4">
    <h1 class="h3 mb-4">Navigationseintrag bearbeiten</h1>
    <div class="card shadow-sm">
        <div class="card-body">
            <form method="post" action="/admin/navigation/update/<?= (int)$item['id'] ?>" enctype="multipart/form-data" class="row g-3">

                <div class="col-md-4">
                    <label class="form-label">Bereich</label>
                    <select name="area" class="form-select">
                        <option value="frontend" <?= (($item['area'] ?? '') === 'frontend') ? 'selected' : '' ?>>Frontend</option>
                        <option value="backend" <?= (($item['area'] ?? '') === 'backend') ? 'selected' : '' ?>>Backend</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Label</label>
                    <input type="text" name="label" class="form-control" value="<?= htmlspecialchars($item['label'] ?? '') ?>" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Route / URL</label>
                    <input type="text" name="route" class="form-control" value="<?= htmlspecialchars($item['route'] ?? '') ?>" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Icon</label>
                    <input type="text" name="icon" class="form-control" value="<?= htmlspecialchars($item['icon'] ?? '') ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Permission Slug</label>
                    <input type="text" name="permission_slug" class="form-control" value="<?= htmlspecialchars($item['permission_slug'] ?? '') ?>">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Sortierung</label>
                    <input type="number" name="sort_order" class="form-control" value="<?= (int)($item['sort_order'] ?? 0) ?>">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Parent ID</label>
                    <input type="number" name="parent_id" class="form-control" value="<?= htmlspecialchars((string)($item['parent_id'] ?? '')) ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label d-block">Aktiv</label>
                    <div class="form-check mt-2">
                        <input type="checkbox" name="is_active" value="1" class="form-check-input" id="nav_active" <?= ((int)($item['is_active'] ?? 0) === 1) ? 'checked' : '' ?>>
                        <label class="form-check-label" for="nav_active">Sichtbar</label>
                    </div>
                </div>

                <div class="col-12">
                    <button type="submit" class="btn btn-primary">Speichern</button>
                    <a href="/admin/navigation" class="btn btn-outline-secondary">Zurück</a>
                </div>
            </form>
        </div>
    </div>
</div>
