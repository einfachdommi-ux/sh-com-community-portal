<section class="container py-5">
  <h1 class="mb-4">News</h1>
  <div class="row g-4">
    <?php foreach ($news as $item): ?>
      <div class="col-md-6 col-xl-4">
        <div class="card sh-card h-100">
          <div class="card-body">
            <h2 class="h5"><?= e($item['title']) ?></h2>
            <p><?= e($item['teaser']) ?></p>
            <div class="small text-muted mb-3"><?= e($item['published_at'] ?? $item['created_at']) ?> · <?= e($item['author_name'] ?? 'System') ?></div>
            <a href="<?= e(base_url('/news/' . $item['slug'])) ?>" class="btn btn-danger btn-sm">Details</a>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</section>
