<h1 class="mb-4">Changelog System</h1>
<div class="row g-4">
  <div class="col-xl-5">
    <div class="card sh-card"><div class="card-body">
      <form method="post" action="<?= e(base_url('/admin/changelogs/store')) ?>">
        <?= csrf_field() ?>
        <div class="row g-3">
          <div class="col-md-6"><label class="form-label">Version</label><input class="form-control" name="version" required></div>
          <div class="col-md-6"><label class="form-label">Typ</label><select class="form-select" name="change_type"><option>Added</option><option>Changed</option><option>Fixed</option><option>Removed</option><option>Security</option></select></div>
        </div>
        <div class="mb-3 mt-3"><label class="form-label">Titel</label><input class="form-control" name="title" required></div>
        <div class="mb-3"><label class="form-label">Inhalt</label><textarea class="form-control" name="content" rows="8" required></textarea></div>
        <div class="row g-3">
          <div class="col-md-6"><label class="form-label">Sichtbarkeit</label><select class="form-select" name="visibility"><option>public</option><option>internal</option></select></div>
          <div class="col-md-6"><label class="form-label">Release Datum</label><input class="form-control" type="datetime-local" name="released_at" required></div>
        </div>
        <button class="btn btn-danger w-100 mt-3">Changelog speichern</button>
      </form>
    </div></div>
  </div>
  <div class="col-xl-7">
    <div class="card sh-card"><div class="card-body"><div class="table-responsive"><table class="table table-dark">
      <thead><tr><th>Version</th><th>Titel</th><th>Typ</th><th>Release</th></tr></thead>
      <tbody><?php foreach ($items as $item): ?><tr><td><?= e($item['version']) ?></td><td><?= e($item['title']) ?></td><td><?= e($item['change_type']) ?></td><td><?= e($item['released_at']) ?></td></tr><?php endforeach; ?></tbody>
    </table></div></div></div>
  </div>
</div>
