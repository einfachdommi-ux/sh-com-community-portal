<section class="container py-5">
  <h1 class="mb-4">Changelog</h1>
  <div class="card sh-card"><div class="card-body">
    <?php foreach ($items as $item): ?>
    <div class="border-bottom py-3">
      <div class="d-flex justify-content-between flex-wrap gap-2">
        <h2 class="h5 mb-0"><?= e($item['version']) ?> · <?= e($item['title']) ?></h2>
        <span class="badge text-bg-secondary"><?= e($item['change_type']) ?></span>
      </div>
      <small class="text-muted d-block mb-2"><?= e($item['released_at']) ?></small>
      <div><?= nl2br(e($item['content'])) ?></div>
    </div>
    <?php endforeach; ?>
  </div></div>
</section>
