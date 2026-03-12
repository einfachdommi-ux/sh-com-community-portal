<?php /* TinyMCE */ ?>
<script src="https://cdn.tiny.cloud/1/u9xub32inhd9dyvr5bw19t4b2jvfb4fg5e1tjz7ky1dgil4t/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script src="/assets/js/editor.js"></script>
<div class="container py-4">
    <h1 class="h3 mb-4">Seite bearbeiten</h1>
    <div class="card shadow-sm">
        <div class="card-body">
            <form method="post" action="/admin/pages/update/<?= (int)$page['id'] ?>" enctype="multipart/form-data" class="row g-3">

                <div class="col-md-6">
                    <label class="form-label">Titel</label>
                    <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($page['title'] ?? '') ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Slug</label>
                    <input type="text" name="slug" class="form-control" value="<?= htmlspecialchars($page['slug'] ?? '') ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Meta Title</label>
                    <input type="text" name="meta_title" class="form-control" value="<?= htmlspecialchars($page['meta_title'] ?? '') ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="draft" <?= (($page['status'] ?? '') === 'draft') ? 'selected' : '' ?>>Draft</option>
                        <option value="published" <?= (($page['status'] ?? '') === 'published') ? 'selected' : '' ?>>Published</option>
                    </select>
                </div>
                <div class="col-md-12">
                    <label class="form-label">Meta Description</label>
                    <textarea name="meta_description" class="form-control" rows="3"><?= htmlspecialchars($page['meta_description'] ?? '') ?></textarea>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Sichtbarkeit</label>
                    <select name="visibility" class="form-select">
                        <option value="public" <?= (($page['visibility'] ?? '') === 'public') ? 'selected' : '' ?>>Public</option>
                        <option value="private" <?= (($page['visibility'] ?? '') === 'private') ? 'selected' : '' ?>>Private</option>
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label">Inhalt</label>
                    <textarea name="content" class="form-control editor" rows="12"><?= htmlspecialchars($page['content'] ?? '') ?></textarea>
                </div>

                <div class="col-12">
                    <button type="submit" class="btn btn-primary">Speichern</button>
                    <a href="/admin/pages" class="btn btn-outline-secondary">Zurück</a>
                </div>
            </form>
        </div>
    </div>
</div>
