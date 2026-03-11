<?php /** @var array $items */ ?>
<div class="container py-4">
    <h1 class="h3 mb-4">News System</h1>

    <div class="card shadow-sm mb-4">
        <div class="card-header"><strong>Neue News</strong></div>
        <div class="card-body">
            <script src="https://cdn.tiny.cloud/1/u9xub32inhd9dyvr5bw19t4b2jvfb4fg5e1tjz7ky1dgil4t/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script src="/assets/js/editor.js"></script>
            <form method="post" action="/admin/news/store" enctype="multipart/form-data" class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Titel</label>
                    <input type="text" name="title" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Slug</label>
                    <input type="text" name="slug" class="form-control" required>
                </div>
                <div class="col-md-8">
                    <label class="form-label">Teaser</label>
                    <input type="text" name="teaser" class="form-control">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Vorschaubild</label>
                    <input type="file" name="featured_image_file" class="form-control" accept=".jpg,.jpeg,.png,.webp">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="draft">draft</option>
                        <option value="published">published</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Publish Datum</label>
                    <input type="datetime-local" name="published_at" class="form-control">
                </div>
                <div class="col-12">
                    <label class="form-label">Inhalt</label>
                    <textarea name="content" class="form-control editor" rows="12"></textarea>
                </div>
                <div class="col-12">
                    <button class="btn btn-primary">News speichern</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header"><strong>Vorhandene News</strong></div>
        <div class="card-body">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Titel</th>
                        <th>Slug</th>
                        <th>Status</th>
                        <th>Publish</th>
                        <th style="width:220px;">Aktionen</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($items as $item): ?>
                    <tr>
                        <td><?= htmlspecialchars((string)($item['title'] ?? '')) ?></td>
                        <td><?= htmlspecialchars((string)($item['slug'] ?? '')) ?></td>
                        <td><span class="badge text-bg-secondary"><?= htmlspecialchars((string)($item['status'] ?? '')) ?></span></td>
                        <td><?= htmlspecialchars((string)($item['published_at'] ?? '')) ?></td>
                        <td>
                            <a href="/admin/news/edit/<?= (int)$item['id'] ?>" class="btn btn-sm btn-warning">Bearbeiten</a>
                            <form method="post" action="/admin/news/delete/<?= (int)$item['id'] ?>" class="d-inline" onsubmit="return confirm('News wirklich löschen?');">
                                <button class="btn btn-sm btn-danger">Löschen</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
