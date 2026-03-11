<?php /** @var array $article */ ?>
<div class="container py-4">
    <h1 class="h3 mb-4">News bearbeiten</h1>
    <div class="card shadow-sm">
        <div class="card-body">
            <form method="post" action="/admin/news/update/<?= (int)$article['id'] ?>" enctype="multipart/form-data" class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Titel</label>
                    <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($article['title']) ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Slug</label>
                    <input type="text" name="slug" class="form-control" value="<?= htmlspecialchars($article['slug']) ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Teaser</label>
                    <input type="text" name="teaser" class="form-control" value="<?= htmlspecialchars((string)($article['teaser'] ?? '')) ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <?php foreach (['draft', 'published'] as $status): ?>
                            <option value="<?= $status ?>" <?= (($article['status'] ?? 'draft') === $status) ? 'selected' : '' ?>><?= $status ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Publish Datum</label>
                    <?php $publishedValue = !empty($article['published_at']) ? date('Y-m-d\TH:i', strtotime((string)$article['published_at'])) : ''; ?>
                    <input type="datetime-local" name="published_at" class="form-control" value="<?= htmlspecialchars($publishedValue) ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Neues Vorschaubild</label>
                    <input type="file" name="featured_image_file" class="form-control" accept=".jpg,.jpeg,.png,.webp,.gif">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Aktuelles Bild</label>
                    <div>
                        <?php if (!empty($article['featured_image'])): ?>
                            <img src="<?= htmlspecialchars($article['featured_image']) ?>" alt="" style="width:140px;height:90px;object-fit:cover;border-radius:12px;">
                        <?php else: ?>
                            <span class="text-muted">Kein Bild vorhanden</span>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-12">
                    <label class="form-label">Inhalt</label>
                    <textarea name="content" class="form-control html-editor" rows="14"><?= htmlspecialchars((string)($article['content'] ?? '')) ?></textarea>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">Änderungen speichern</button>
                    <a href="/admin/news" class="btn btn-outline-secondary">Zurück</a>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script src="/assets/js/editor.js"></script>
