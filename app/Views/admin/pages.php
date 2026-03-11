<h1 class="mb-4">Seiten</h1>
<div class="row g-4">
  <div class="col-xl-5">
    <div class="card sh-card"><div class="card-body">
      <form method="post" action="<?= e(base_url('/admin/pages/store')) ?>">
        <?= csrf_field() ?>
        <div class="mb-3"><label class="form-label">Titel</label><input class="form-control" name="title" required></div>
        <div class="mb-3"><label class="form-label">Slug</label><input class="form-control" name="slug" required></div>
        <div class="mb-3"><label class="form-label">Meta Title</label><input class="form-control" name="meta_title"></div>
        <div class="mb-3"><label class="form-label">Meta Description</label><textarea class="form-control" name="meta_description"></textarea></div>
        <div class="mb-3"><label class="form-label">Inhalt</label><textarea class="form-control" name="content" rows="8" required></textarea></div>
        <div class="row g-3">
          <div class="col-md-6"><label class="form-label">Sichtbarkeit</label><select class="form-select" name="visibility"><option>public</option><option>members</option><option>admin</option></select></div>
          <div class="col-md-6"><label class="form-label">Status</label><select class="form-select" name="status"><option>draft</option><option>published</option></select></div>
        </div>
        <button class="btn btn-danger w-100 mt-3">Seite speichern</button>
      </form>
    </div></div>
  </div>
  <div class="col-xl-7">
    <div class="card sh-card"><div class="card-body"><div class="table-responsive"><table class="table table-dark">
      <thead><tr><th>Titel</th><th>Slug</th><th>Status</th><th>Sichtbarkeit</th></tr></thead>
      <tbody><?php foreach ($pages as $item): ?><tr><td><?= e($item['title']) ?></td><td><?= e($item['slug']) ?></td><td><?= e($item['status']) ?></td><td><?= e($item['visibility']) ?></td></tr><?php endforeach; ?></tbody>
    </table></div></div></div>
  </div>
</div>
