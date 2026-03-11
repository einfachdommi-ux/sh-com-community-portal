<h1 class="mb-4">DB Tools</h1>
<div class="row g-4">
  <div class="col-xl-4">
    <div class="card sh-card"><div class="card-body">
      <form method="post" action="<?= e(base_url('/admin/db-tools/run')) ?>">
        <?= csrf_field() ?>
        <div class="mb-3"><label class="form-label">Aktion</label><select class="form-select" name="action"><option value="select">SELECT</option><option value="insert">INSERT</option><option value="update">UPDATE</option><option value="delete">DELETE</option></select></div>
        <div class="mb-3"><label class="form-label">Tabelle</label><select class="form-select" name="table"><?php foreach($tables as $table): ?><option><?= e($table) ?></option><?php endforeach; ?></select></div>
        <div class="mb-3"><label class="form-label">ID (für UPDATE/DELETE)</label><input class="form-control" name="id" type="number"></div>
        <div class="mb-3"><label class="form-label">JSON Daten (für INSERT/UPDATE)</label><textarea class="form-control" name="json" rows="8" placeholder='{"title":"Beispiel"}'></textarea></div>
        <button class="btn btn-danger w-100">Ausführen</button>
      </form>
    </div></div>
  </div>
  <div class="col-xl-8">
    <div class="card sh-card"><div class="card-body">
      <h2 class="h5 mb-3">Ergebnis</h2>
      <?php if ($result): ?>
        <?php if ($result['type'] === 'table'): ?>
          <div class="table-responsive"><table class="table table-dark table-sm">
            <thead><tr><?php foreach(array_keys($result['rows'][0] ?? []) as $col): ?><th><?= e($col) ?></th><?php endforeach; ?></tr></thead>
            <tbody><?php foreach($result['rows'] as $row): ?><tr><?php foreach($row as $val): ?><td><?= e((string)$val) ?></td><?php endforeach; ?></tr><?php endforeach; ?></tbody>
          </table></div>
        <?php else: ?>
          <div class="alert alert-<?= $result['type'] === 'error' ? 'danger' : 'success' ?>"><?= e($result['message']) ?></div>
        <?php endif; ?>
      <?php else: ?>
        <p class="text-muted mb-0">Noch keine Aktion ausgeführt.</p>
      <?php endif; ?>
    </div></div>
  </div>
</div>
