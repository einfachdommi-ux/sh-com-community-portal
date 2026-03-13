<?php $isEdit = !empty($farm); ?>
<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
  <div>
    <h1 class="h3 mb-1"><?= $isEdit ? 'Hof bearbeiten' : 'Hof anlegen' ?></h1>
    <div class="text-muted small">Passwort optional – wird gehasht gespeichert</div>
  </div>
  <a class="btn btn-outline-dark" href="<?= e(base_path('/admin/farms')) ?>">Zurück</a>
</div>

<div class="card border-0 shadow-sm">
  <div class="card-body p-3 p-md-4">
    <form method="post" action="<?= e(base_path($isEdit ? '/admin/farms/update' : '/admin/farms/store')) ?>" class="row g-3">
      <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">
      <?php if ($isEdit): ?><input type="hidden" name="id" value="<?= (int)$farm['id'] ?>"><?php endif; ?>

      <div class="col-12 col-md-6">
        <label class="form-label">Name</label>
        <input class="form-control" name="name" value="<?= e((string)($farm['name'] ?? '')) ?>" required>
      </div>

      <div class="col-12 col-md-6">
        <label class="form-label">Slug</label>
        <input class="form-control" name="slug" value="<?= e((string)($farm['slug'] ?? '')) ?>" required>
        <div class="form-text">Nur Kleinbuchstaben/Zahlen/Bindestrich empfohlen (z.B. ls-25-hof)</div>
      </div>

      <div class="col-12">
        <label class="form-label">Beschreibung</label>
        <textarea class="form-control" name="description" rows="3"><?= e((string)($farm['description'] ?? '')) ?></textarea>
      </div>

      <div class="col-12 col-md-6">
        <label class="form-label"><?= $isEdit ? 'Neues Passwort (optional)' : 'Passwort (optional)' ?></label>
        <input class="form-control" type="password" name="password" minlength="4">
      </div>

      <div class="col-12">
        <button class="btn btn-dark"><?= $isEdit ? 'Speichern' : 'Anlegen' ?></button>
      </div>
    </form>
  </div>
</div>
