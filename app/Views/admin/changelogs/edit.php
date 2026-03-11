<?php /** @var array $item */ ?>
<div class="container py-4">
    <h1 class="h3 mb-4">Changelog bearbeiten</h1>
    <div class="card shadow-sm">
        <div class="card-body">
            <form method="post" action="/admin/changelogs/update/<?= (int)$item['id'] ?>" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Version</label>
                    <input type="text" name="version" class="form-control" value="<?= htmlspecialchars((string)$item['version']) ?>" required>
                </div>
                <div class="col-md-5">
                    <label class="form-label">Titel</label>
                    <input type="text" name="title" class="form-control" value="<?= htmlspecialchars((string)$item['title']) ?>" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Typ</label>
                    <select name="change_type" class="form-select">
                        <?php foreach (['Added','Changed','Fixed','Removed','Security'] as $type): ?>
                            <option value="<?= $type ?>" <?= (($item['change_type'] ?? '') === $type) ? 'selected' : '' ?>><?= $type ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-8">
                    <label class="form-label">Inhalt</label>
                    <textarea name="content" class="form-control" rows="5"><?= htmlspecialchars((string)($item['content'] ?? '')) ?></textarea>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Sichtbarkeit</label>
                    <select name="visibility" class="form-select">
                        <option value="public" <?= (($item['visibility'] ?? '') === 'public') ? 'selected' : '' ?>>public</option>
                        <option value="internal" <?= (($item['visibility'] ?? '') === 'internal') ? 'selected' : '' ?>>internal</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Release-Datum</label>
                    <input type="datetime-local" name="released_at" class="form-control" value="<?= !empty($item['released_at']) ? htmlspecialchars(date('Y-m-d\TH:i', strtotime((string)$item['released_at']))) : '' ?>">
                </div>
                <div class="col-12">
                    <button class="btn btn-primary">Änderungen speichern</button>
                    <a href="/admin/changelogs" class="btn btn-outline-secondary">Zurück</a>
                </div>
            </form>
        </div>
    </div>
</div>
