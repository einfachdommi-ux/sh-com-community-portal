<?php /** @var array $page */ ?>
<div class="container py-4">
    <h1 class="h3 mb-4">Seite bearbeiten</h1>
    <div class="card shadow-sm">
        <div class="card-body">
            <script src="https://cdn.tiny.cloud/1/u9xub32inhd9dyvr5bw19t4b2jvfb4fg5e1tjz7ky1dgil4t/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script src="/assets/js/editor.js"></script>
            <form method="post" action="/admin/pages/update/<?= (int)$page['id'] ?>" class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Titel</label>
                    <input type="text" name="title" class="form-control" value="<?= htmlspecialchars((string)$page['title']) ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Slug</label>
                    <input type="text" name="slug" class="form-control" value="<?= htmlspecialchars((string)$page['slug']) ?>" required>
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
                        <option value="public" <?= (($page['visibility'] ?? '') === 'public') ? 'selected' : '' ?>>public</option>
                        <option value="members" <?= (($page['visibility'] ?? '') === 'members') ? 'selected' : '' ?>>members</option>
                        <option value="admin" <?= (($page['visibility'] ?? '') === 'admin') ? 'selected' : '' ?>>admin</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="draft" <?= (($page['status'] ?? '') === 'draft') ? 'selected' : '' ?>>draft</option>
                        <option value="published" <?= (($page['status'] ?? '') === 'published') ? 'selected' : '' ?>>published</option>
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label">Inhalt</label>
                    <textarea name="content" class="form-control editor" rows="14"><?= htmlspecialchars((string)($page['content'] ?? '')) ?></textarea>
                </div>
                <div class="col-12">
                    <button class="btn btn-primary">Änderungen speichern</button>
                    <a href="/admin/pages" class="btn btn-outline-secondary">Zurück</a>
                </div>
            </form>
        </div>
    </div>
</div>
