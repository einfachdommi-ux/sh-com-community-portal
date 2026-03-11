<?php /** @var array $page */ ?>
<div class="container py-4">
    <h1 class="h3 mb-4">Seite bearbeiten</h1>
    <div class="card shadow-sm">
        <div class="card-body">
            <form method="post" action="/admin/pages/update/<?= (int)$page['id'] ?>" class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Titel</label>
                    <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($page['title']) ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Slug</label>
                    <input type="text" name="slug" class="form-control" value="<?= htmlspecialchars($page['slug']) ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Meta Title</label>
                    <input type="text" name="meta_title" class="form-control" value="<?= htmlspecialchars((string)($page['meta_title'] ?? '')) ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Meta Description</label>
                    <input type="text" name="meta_description" class="form-control" value="<?= htmlspecialchars((string)($page['meta_description'] ?? '')) ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Sichtbarkeit</label>
                    <select name="visibility" class="form-select">
                        <?php foreach (['public', 'members', 'admin'] as $visibility): ?>
                            <option value="<?= $visibility ?>" <?= (($page['visibility'] ?? 'public') === $visibility) ? 'selected' : '' ?>><?= $visibility ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <?php foreach (['draft', 'published'] as $status): ?>
                            <option value="<?= $status ?>" <?= (($page['status'] ?? 'draft') === $status) ? 'selected' : '' ?>><?= $status ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label">Inhalt</label>
                    <textarea name="content" class="form-control html-editor" rows="14"><?= htmlspecialchars((string)($page['content'] ?? '')) ?></textarea>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">Änderungen speichern</button>
                    <a href="/admin/pages" class="btn btn-outline-secondary">Zurück</a>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script src="/assets/js/editor.js"></script>
