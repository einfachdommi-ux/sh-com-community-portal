<section class="container py-5">
  <article class="card sh-card">
    <div class="card-body p-4 p-lg-5">
      <div class="mb-3 text-danger fw-semibold">News</div>
      <h1><?= e($article['title']) ?></h1>
      <div class="small text-muted mb-4"><?= e($article['published_at'] ?? $article['created_at']) ?> · <?= e($article['author_name'] ?? 'System') ?></div>
      <div class="fs-5"><?= nl2br(e($article['content'])) ?></div>
    </div>
  </article>
</section>
