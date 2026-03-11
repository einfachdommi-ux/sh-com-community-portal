<h1 class="mb-4">Permissions</h1>
<div class="row g-4">
  <div class="col-xl-4">
    <div class="card sh-card"><div class="card-body">
      <h2 class="h5">Permission anlegen</h2>
      <form method="post" action="<?= e(base_url('/admin/permissions/store')) ?>">
        <?= csrf_field() ?>
        <div class="mb-3"><label class="form-label">Name</label><input class="form-control" name="name" required></div>
        <div class="mb-3"><label class="form-label">Slug</label><input class="form-control" name="slug" required></div>
        <div class="mb-3"><label class="form-label">Beschreibung</label><textarea class="form-control" name="description"></textarea></div>
        <button class="btn btn-danger w-100">Speichern</button>
      </form>
    </div></div>
  </div>
  <div class="col-xl-8">
    <div class="card sh-card"><div class="card-body"><div class="table-responsive"><table class="table table-dark">
      <thead><tr><th>Name</th><th>Slug</th><th>Beschreibung</th></tr></thead>
      <tbody><?php foreach ($permissions as $item): ?><tr><td><?= e($item['name']) ?></td><td><?= e($item['slug']) ?></td><td><?= e($item['description']) ?></td></tr><?php endforeach; ?></tbody>
    </table></div></div></div>
  </div>
</div>
