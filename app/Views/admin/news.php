<h1 class="mb-4">News System</h1>
<div class="row g-4">
  <div class="col-xl-5">
    <div class="card sh-card"><div class="card-body">
      <form method="post" action="<?= e(base_url('/admin/news/store')) ?>">
        <?= csrf_field() ?>
        <div class="mb-3"><label class="form-label">Titel</label><input class="form-control" name="title" required></div>
        <div class="mb-3"><label class="form-label">Slug</label><input class="form-control" name="slug" required></div>
        <div class="mb-3"><label class="form-label">Teaser</label><textarea class="form-control" name="teaser"></textarea></div>
        <div class="mb-3"><label class="form-label">Inhalt</label><textarea class="form-control" name="content" rows="8" required></textarea></div>
        <div class="row g-3">
          <div class="col-md-6"><label class="form-label">Status</label><select class="form-select" name="status"><option>draft</option><option>published</option></select></div>
          <div class="col-md-6"><label class="form-label">Publish Datum</label><input class="form-control" type="datetime-local" name="published_at"></div>
        </div>
        <button class="btn btn-danger w-100 mt-3">News speichern</button>
      </form>
    </div></div>
  </div>
  <div class="col-xl-7">
    <div class="card sh-card"><div class="card-body"><div class="table-responsive"><table class="table table-dark">
      <thead><tr><th>Titel</th><th>Slug</th><th>Status</th><th>Publish</th></tr></thead>
      <tbody><?php foreach ($items as $item): ?><tr><td><?= e($item['title']) ?></td><td><?= e($item['slug']) ?></td><td><?= e($item['status']) ?></td><td><?= e($item['published_at']) ?></td></tr><?php endforeach; ?></tbody>
    </table></div></div></div>
  </div>
</div>
