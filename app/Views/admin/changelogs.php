<?php /** @var array $items */ ?>
<div class="container py-4">
    <h1 class="h3 mb-4">Changelogs</h1>

    <div class="card shadow-sm mb-4">
        <div class="card-header"><strong>Neuer Changelog</strong></div>
        <div class="card-body">
            <form method="post" action="/admin/changelogs/store" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Version</label>
                    <input type="text" name="version" class="form-control" required>
                </div>
                <div class="col-md-5">
                    <label class="form-label">Titel</label>
                    <input type="text" name="title" class="form-control" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Typ</label>
                    <select name="change_type" class="form-select">
                        <option value="Added">Added</option>
                        <option value="Changed">Changed</option>
                        <option value="Fixed">Fixed</option>
                        <option value="Removed">Removed</option>
                        <option value="Security">Security</option>
                    </select>
                </div>
                <div class="col-md-8">
                    <label class="form-label">Inhalt</label>
                    <textarea name="content" class="form-control" rows="4"></textarea>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Sichtbarkeit</label>
                    <select name="visibility" class="form-select">
                        <option value="public">public</option>
                        <option value="internal">internal</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Release-Datum</label>
                    <input type="datetime-local" name="released_at" class="form-control" value="<?= date('Y-m-d\TH:i') ?>">
                </div>
                <div class="col-12">
                    <button class="btn btn-primary">Changelog speichern</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header"><strong>Vorhandene Changelogs</strong></div>
        <div class="card-body">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Version</th>
                        <th>Titel</th>
                        <th>Typ</th>
                        <th>Release</th>
                        <th style="width:220px;">Aktionen</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($items as $item): ?>
                    <tr>
                        <td><?= htmlspecialchars((string)($item['version'] ?? '')) ?></td>
                        <td><?= htmlspecialchars((string)($item['title'] ?? '')) ?></td>
                        <td><?= htmlspecialchars((string)($item['change_type'] ?? '')) ?></td>
                        <td><?= htmlspecialchars((string)($item['released_at'] ?? '')) ?></td>
                        <td>
                            <a href="/admin/changelogs/edit/<?= (int)$item['id'] ?>" class="btn btn-sm btn-warning">Bearbeiten</a>
                            <form method="post" action="/admin/changelogs/delete/<?= (int)$item['id'] ?>" class="d-inline" onsubmit="return confirm('Changelog wirklich löschen?');">
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
