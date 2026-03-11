<?php /** @var array $items */ ?>
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">News System</h1>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-header"><strong>Neue News</strong></div>
        <div class="card-body">
            <form method="post" action="/admin/news/store" enctype="multipart/form-data" class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Titel</label>
                    <input type="text" name="title" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Slug</label>
                    <input type="text" name="slug" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Teaser</label>
                    <input type="text" name="teaser" class="form-control">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="draft">draft</option>
                        <option value="published">published</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Publish Datum</label>
                    <input type="datetime-local" name="published_at" class="form-control">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Vorschaubild</label>
                    <input type="file" name="featured_image_file" class="form-control" accept=".jpg,.jpeg,.png,.webp,.gif">
                </div>
                <div class="col-12">
                    <label class="form-label">Inhalt</label>
                    <textarea name="content" class="form-control html-editor" rows="10"></textarea>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">News speichern</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header"><strong>Vorhandene News</strong></div>
        <div class="card-body table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Bild</th>
                        <th>Titel</th>
                        <th>Slug</th>
                        <th>Status</th>
                        <th>Publish</th>
                        <th>Aktionen</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($items as $item): ?>
                        <tr>
                            <td><?= (int)$item['id'] ?></td>
                            <td>
                                <?php if (!empty($item['featured_image'])): ?>
                                    <img src="<?= htmlspecialchars($item['featured_image']) ?>" alt="" style="width:56px;height:40px;object-fit:cover;border-radius:8px;">
                                <?php else: ?>
                                    <span class="text-muted">Kein Bild</span>
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($item['title']) ?></td>
                            <td><?= htmlspecialchars($item['slug']) ?></td>
                            <td><?= htmlspecialchars($item['status']) ?></td>
                            <td><?= htmlspecialchars((string)($item['published_at'] ?? '')) ?></td>
                            <td>
                                <a href="/admin/news/edit/<?= (int)$item['id'] ?>" class="btn btn-sm btn-warning">Bearbeiten</a>
                                <form method="post" action="/admin/news/delete/<?= (int)$item['id'] ?>" class="d-inline" onsubmit="return confirm('News wirklich löschen?');">
                                    <button type="submit" class="btn btn-sm btn-danger">Löschen</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script src="https://cdn.tiny.cloud/1/u9xub32inhd9dyvr5bw19t4b2jvfb4fg5e1tjz7ky1dgil4t/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script src="/assets/js/editor.js"></script>
