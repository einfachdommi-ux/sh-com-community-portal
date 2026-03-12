<?php /* TinyMCE */ ?>
<script src="https://cdn.tiny.cloud/1/u9xub32inhd9dyvr5bw19t4b2jvfb4fg5e1tjz7ky1dgil4t/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script src="/assets/js/editor.js"></script>
<div class="container py-4">
    <h1 class="h3 mb-4">News bearbeiten</h1>
    <div class="card shadow-sm">
        <div class="card-body">
            <form method="post" action="/admin/news/update/<?= (int)$article['id'] ?>" enctype="multipart/form-data" class="row g-3">

                <div class="col-md-6">
                    <label class="form-label">Titel</label>
                    <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($article['title'] ?? '') ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Slug</label>
                    <input type="text" name="slug" class="form-control" value="<?= htmlspecialchars($article['slug'] ?? '') ?>" required>
                </div>
                <div class="col-md-8">
                    <label class="form-label">Teaser</label>
                    <textarea name="teaser" class="form-control" rows="3"><?= htmlspecialchars($article['teaser'] ?? '') ?></textarea>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="draft" <?= (($article['status'] ?? '') === 'draft') ? 'selected' : '' ?>>Draft</option>
                        <option value="published" <?= (($article['status'] ?? '') === 'published') ? 'selected' : '' ?>>Published</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Published At</label>
                    <input type="datetime-local" name="published_at" class="form-control" value="<?= !empty($article['published_at']) ? date('Y-m-d\TH:i', strtotime($article['published_at'])) : '' ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Vorschaubild</label>
                    <input type="file" name="featured_image_file" class="form-control" accept=".jpg,.jpeg,.png,.webp,.gif">
                    <?php if (!empty($article['featured_image'])): ?>
                        <div class="mt-2"><img src="<?= htmlspecialchars($article['featured_image']) ?>" alt="" style="max-width:180px;height:auto;border-radius:10px;"></div>
                    <?php endif; ?>
                </div>
                <div class="col-12">
                    <label class="form-label">Inhalt</label>
                    <textarea name="content" class="form-control editor" rows="12"><?= htmlspecialchars($article['content'] ?? '') ?></textarea>
                </div>

                <div class="col-12">
                    <button type="submit" class="btn btn-primary">Speichern</button>
                    <a href="/admin/news" class="btn btn-outline-secondary">Zurück</a>
                </div>
            </form>
        </div>
    </div>
</div>
