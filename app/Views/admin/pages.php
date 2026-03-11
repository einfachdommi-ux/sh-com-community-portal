<?php /** @var array $pages */ ?>
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Seiten</h1>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-header"><strong>Neue Seite</strong></div>
        <div class="card-body">
            <script src="https://cdn.tiny.cloud/1/u9xub32inhd9dyvr5bw19t4b2jvfb4fg5e1tjz7ky1dgil4t/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script src="/assets/js/editor.js"></script>
            <form method="post" action="/admin/pages/store" class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Titel</label>
                    <input type="text" name="title" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Slug</label>
                    <input type="text" name="slug" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Meta Title</label>
                    <input type="text" name="meta_title" class="form-control">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Meta Description</label>
                    <input type="text" name="meta_description" class="form-control">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Sichtbarkeit</label>
                    <select name="visibility" class="form-select">
                        <option value="public">public</option>
                        <option value="members">members</option>
                        <option value="admin">admin</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="draft">draft</option>
                        <option value="published">published</option>
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label">Inhalt</label>
                    <textarea name="content" class="form-control editor" rows="12"></textarea>
                </div>
                <div class="col-12">
                    <button class="btn btn-primary">Seite speichern</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header"><strong>Vorhandene Seiten</strong></div>
        <div class="card-body">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Titel</th>
                        <th>Slug</th>
                        <th>Status</th>
                        <th>Sichtbarkeit</th>
                        <th style="width:220px;">Aktionen</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($pages as $page): ?>
                    <tr>
                        <td><?= htmlspecialchars((string)($page['title'] ?? '')) ?></td>
                        <td><?= htmlspecialchars((string)($page['slug'] ?? '')) ?></td>
                        <td><span class="badge text-bg-secondary"><?= htmlspecialchars((string)($page['status'] ?? '')) ?></span></td>
                        <td><?= htmlspecialchars((string)($page['visibility'] ?? '')) ?></td>
                        <td>
                            <a href="/admin/pages/edit/<?= (int)$page['id'] ?>" class="btn btn-sm btn-warning">Bearbeiten</a>
                            <form method="post" action="/admin/pages/delete/<?= (int)$page['id'] ?>" class="d-inline" onsubmit="return confirm('Seite wirklich löschen?');">
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
