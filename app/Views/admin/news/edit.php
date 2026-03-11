<?php /** @var array $item */ ?>
<div class="container py-4">
    <h1 class="h3 mb-4">News bearbeiten</h1>
    <div class="card shadow-sm">
        <div class="card-body">
            <script src="https://cdn.tiny.cloud/1/u9xub32inhd9dyvr5bw19t4b2jvfb4fg5e1tjz7ky1dgil4t/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script src="/assets/js/editor.js"></script>
            <form method="post" action="/admin/news/update/<?= (int)$item['id'] ?>" enctype="multipart/form-data" class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Titel</label>
                    <input type="text" name="title" class="form-control" value="<?= htmlspecialchars((string)$item['title']) ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Slug</label>
                    <input type="text" name="slug" class="form-control" value="<?= htmlspecialchars((string)$item['slug']) ?>" required>
                </div>
                <div class="col-md-8">
                    <label class="form-label">Teaser</label>
                    <input type="text" name="teaser" class="form-control" value="<?= htmlspecialchars((string)($item['teaser'] ?? '')) ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Neues Vorschaubild</label>
                    <input type="file" name="featured_image_file" class="form-control" accept=".jpg,.jpeg,.png,.webp">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="draft" <?= (($item['status'] ?? '') === 'draft') ? 'selected' : '' ?>>draft</option>
                        <option value="published" <?= (($item['status'] ?? '') === 'published') ? 'selected' : '' ?>>published</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Publish Datum</label>
                    <input type="datetime-local" name="published_at" class="form-control" value="<?= !empty($item['published_at']) ? htmlspecialchars(date('Y-m-d\TH:i', strtotime((string)$item['published_at']))) : '' ?>">
                </div>
                <div class="col-12">
                    <label class="form-label">Aktuelles Vorschaubild</label><br>
                    <?php if (!empty($item['featured_image'])): ?>
                        <img src="<?= htmlspecialchars((string)$item['featured_image']) ?>" alt="" style="max-width:220px;border-radius:12px;">
                    <?php else: ?>
                        <span class="text-muted">Kein Vorschaubild vorhanden</span>
                    <?php endif; ?>
                </div>
                <div class="col-12">
                    <label class="form-label">Inhalt</label>
                    <textarea name="content" class="form-control editor" rows="14"><?= htmlspecialchars((string)($item['content'] ?? '')) ?></textarea>
                </div>
                <div class="col-12">
                    <button class="btn btn-primary">Änderungen speichern</button>
                    <a href="/admin/news" class="btn btn-outline-secondary">Zurück</a>
                </div>
            </form>
        </div>
    </div>
</div>
