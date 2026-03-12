<div class="container py-4">
    <h1 class="h3 mb-4">Changelog bearbeiten</h1>
    <div class="card shadow-sm">
        <div class="card-body">
            <form method="post" action="/admin/changelogs/update/<?= (int)$entry['id'] ?>" enctype="multipart/form-data" class="row g-3">

                <div class="col-md-3">
                    <label class="form-label">Version</label>
                    <input type="text" name="version" class="form-control" value="<?= htmlspecialchars($entry['version'] ?? '') ?>" required>
                </div>
                <div class="col-md-5">
                    <label class="form-label">Titel</label>
                    <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($entry['title'] ?? '') ?>" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Typ</label>
                    <select name="change_type" class="form-select">
                        <?php foreach (['Added','Changed','Fixed','Removed','Security'] as $type): ?>
                            <option value="<?= $type ?>" <?= (($entry['change_type'] ?? '') === $type) ? 'selected' : '' ?>><?= $type ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Sichtbarkeit</label>
                    <select name="visibility" class="form-select">
                        <option value="public" <?= (($entry['visibility'] ?? '') === 'public') ? 'selected' : '' ?>>Public</option>
                        <option value="internal" <?= (($entry['visibility'] ?? '') === 'internal') ? 'selected' : '' ?>>Internal</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Released At</label>
                    <input type="datetime-local" name="released_at" class="form-control" value="<?= !empty($entry['released_at']) ? date('Y-m-d\TH:i', strtotime($entry['released_at'])) : '' ?>">
                </div>
                <div class="col-12">
                    <label class="form-label">Inhalt</label>
                    <textarea name="content" class="form-control" rows="8"><?= htmlspecialchars($entry['content'] ?? '') ?></textarea>
                </div>

                <div class="col-12">
                    <button type="submit" class="btn btn-primary">Speichern</button>
                    <a href="/admin/changelogs" class="btn btn-outline-secondary">Zurück</a>
                </div>
            </form>
        </div>
    </div>
</div>
