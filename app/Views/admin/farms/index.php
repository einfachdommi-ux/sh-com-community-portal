<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">Höfe</h1>
    <a href="/admin/farms/create" class="btn btn-primary">Hof anlegen</a>
  </div>
  <div class="table-responsive"><table class="table align-middle"><thead><tr><th>ID</th><th>Name</th><th>Slug</th><th>Beschreibung</th><th>Aktionen</th></tr></thead><tbody><?php foreach (($farms ?? []) as $farm): ?><tr><td><?= (int)$farm['id'] ?></td><td><?= htmlspecialchars($farm['name']) ?></td><td><?= htmlspecialchars($farm['slug']) ?></td><td><?= htmlspecialchars((string)($farm['description'] ?? '')) ?></td><td><a href="/admin/farms/edit/<?= (int)$farm['id'] ?>" class="btn btn-sm btn-warning">Bearbeiten</a> <form method="post" action="/admin/farms/delete/<?= (int)$farm['id'] ?>" class="d-inline" onsubmit="return confirm('Hof löschen?');"><button class="btn btn-sm btn-outline-danger">Löschen</button></form></td></tr><?php endforeach; ?><?php if (empty($farms)): ?><tr><td colspan="5" class="text-muted">Keine Höfe vorhanden.</td></tr><?php endif; ?></tbody></table></div>
</div>
