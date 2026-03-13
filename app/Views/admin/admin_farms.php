<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
  <div>
    <h1 class="h3 mb-1">Höfe</h1>
    <div class="text-muted small">Verwalte alle Höfe</div>
  </div>
  <a class="btn btn-dark" href="<?= e(base_path('/admin/farms/create')) ?>">+ Hof anlegen</a>
</div>

<div class="card border-0 shadow-sm">
  <div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
      <thead class="table-light">
        <tr>
          <th style="width:70px;">#</th>
          <th>Name</th>
          <th>Slug</th>
          <th>Beschreibung</th>
          <th class="text-end">Aktionen</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach (($farms ?? []) as $f): ?>
          <tr>
            <td><?= (int)$f['id'] ?></td>
            <td class="fw-semibold"><?= e((string)$f['name']) ?></td>
            <td class="text-muted small"><?= e((string)$f['slug']) ?></td>
            <td class="text-muted small"><?= e((string)($f['description'] ?? '')) ?></td>
            <td class="text-end">
              <a class="btn btn-sm btn-outline-dark" href="<?= e(base_path('/admin/farms/edit?id='.(int)$f['id'])) ?>">Bearbeiten</a>
              <form class="d-inline" method="post" action="<?= e(base_path('/admin/farms/delete')) ?>" onsubmit="return confirm('Hof wirklich löschen? (Mitglieder werden automatisch entkoppelt)');">
                <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">
                <input type="hidden" name="id" value="<?= (int)$f['id'] ?>">
                <button class="btn btn-sm btn-outline-danger">Löschen</button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>

        <?php if (empty($farms)): ?>
          <tr><td colspan="5" class="text-center text-muted py-4">Keine Höfe vorhanden.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
