<div class="container py-4">
  <h1 class="h3 mb-3"><?= htmlspecialchars($title ?? 'Hof') ?></h1>
  <form method="post" action="<?= !empty($farm) ? '/admin/farms/update/' . (int)$farm['id'] : '/admin/farms/store' ?>" class="card shadow-sm p-3 row g-3">
    <div class="col-md-6"><label class="form-label">Name</label><input type="text" name="name" class="form-control" value="<?= htmlspecialchars($farm['name'] ?? '') ?>" required></div>
    <div class="col-md-6"><label class="form-label">Slug</label><input type="text" name="slug" class="form-control" value="<?= htmlspecialchars($farm['slug'] ?? '') ?>" required></div>
    <div class="col-12"><label class="form-label">Beschreibung</label><textarea name="description" class="form-control" rows="4"><?= htmlspecialchars((string)($farm['description'] ?? '')) ?></textarea></div>
    <div class="col-md-6"><label class="form-label"><?= !empty($farm) ? 'Neues Passwort (optional)' : 'Passwort' ?></label><input type="password" name="password" class="form-control"></div>
    <div class="col-12 d-flex gap-2"><button class="btn btn-primary">Speichern</button><a href="/admin/farms" class="btn btn-outline-secondary">Abbrechen</a></div>
  </form>
</div>
