<?php /** @var array $items */ ?>
<div class="container py-4">
    <h1 class="h3 mb-4">Navigation</h1>

    <div class="card shadow-sm mb-4">
        <div class="card-header"><strong>Neuer Navigationseintrag</strong></div>
        <div class="card-body">
            <form method="post" action="/admin/navigation/store" class="row g-3">
                <div class="col-md-2">
                    <label class="form-label">Parent-ID</label>
                    <input type="number" name="parent_id" class="form-control">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Bereich</label>
                    <select name="area" class="form-select">
                        <option value="frontend">frontend</option>
                        <option value="backend">backend</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Label</label>
                    <input type="text" name="label" class="form-control" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Route</label>
                    <input type="text" name="route" class="form-control" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Sortierung</label>
                    <input type="number" name="sort_order" class="form-control" value="0">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Icon</label>
                    <input type="text" name="icon" class="form-control">
                </div>
                <div class="col-md-5">
                    <label class="form-label">Permission Slug</label>
                    <input type="text" name="permission_slug" class="form-control">
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="is_active" id="navigation_active_create" checked>
                        <label class="form-check-label" for="navigation_active_create">Aktiv</label>
                    </div>
                </div>
                <div class="col-12">
                    <button class="btn btn-primary">Navigation speichern</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header"><strong>Vorhandene Navigation</strong></div>
        <div class="card-body">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Bereich</th>
                        <th>Label</th>
                        <th>Route</th>
                        <th>Permission</th>
                        <th>Sort</th>
                        <th style="width:220px;">Aktionen</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($items as $item): ?>
                    <tr>
                        <td><?= (int)$item['id'] ?></td>
                        <td><?= htmlspecialchars((string)($item['area'] ?? '')) ?></td>
                        <td><?= htmlspecialchars((string)($item['label'] ?? '')) ?></td>
                        <td><?= htmlspecialchars((string)($item['route'] ?? '')) ?></td>
                        <td><?= htmlspecialchars((string)($item['permission_slug'] ?? '')) ?></td>
                        <td><?= (int)($item['sort_order'] ?? 0) ?></td>
                        <td>
                            <a href="/admin/navigation/edit/<?= (int)$item['id'] ?>" class="btn btn-sm btn-warning">Bearbeiten</a>
                            <form method="post" action="/admin/navigation/delete/<?= (int)$item['id'] ?>" class="d-inline" onsubmit="return confirm('Eintrag wirklich löschen?');">
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
