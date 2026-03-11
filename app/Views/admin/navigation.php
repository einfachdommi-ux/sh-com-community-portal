<h1 class="mb-4">Navigation</h1>
<div class="row g-4">
  <div class="col-xl-5">
    <div class="card sh-card"><div class="card-body">
      <form method="post" action="<?= e(base_url('/admin/navigation/store')) ?>">
        <?= csrf_field() ?>
        <div class="row g-3">
          <div class="col-md-6"><label class="form-label">Bereich</label><select class="form-select" name="area"><option>frontend</option><option>backend</option></select></div>
          <div class="col-md-6"><label class="form-label">Sortierung</label><input class="form-control" type="number" name="sort_order" value="0"></div>
        </div>
        <div class="mb-3 mt-3"><label class="form-label">Label</label><input class="form-control" name="label" required></div>
        <div class="mb-3"><label class="form-label">Route</label><input class="form-control" name="route" required placeholder="/news"></div>
        <div class="mb-3"><label class="form-label">Icon</label><input class="form-control" name="icon"></div>
        <div class="mb-3"><label class="form-label">Permission Slug</label><input class="form-control" name="permission_slug" placeholder="dashboard.view"></div>
        <div class="form-check mb-3"><input class="form-check-input" type="checkbox" name="is_active" checked><label class="form-check-label">Aktiv</label></div>
        <button class="btn btn-danger w-100">Eintrag speichern</button>
      </form>
    </div></div>
  </div>
  <div class="col-xl-7">
    <div class="card sh-card"><div class="card-body"><div class="table-responsive"><table class="table table-dark">
      <thead><tr><th>Bereich</th><th>Label</th><th>Route</th><th>Permission</th><th>Sort</th></tr></thead>
      <tbody><?php foreach ($items as $item): ?><tr><td><?= e($item['area']) ?></td><td><?= e($item['label']) ?></td><td><?= e($item['route']) ?></td><td><?= e($item['permission_slug']) ?></td><td><?= e((string)$item['sort_order']) ?></td></tr><?php endforeach; ?></tbody>
    </table></div></div></div>
  </div>
</div>
