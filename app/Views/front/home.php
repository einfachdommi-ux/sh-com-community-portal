<section class="py-5 sh-hero text-white">
  <div class="container py-5">
    <div class="row align-items-center g-4">
      <div class="col-lg-7">
        <span class="badge rounded-pill text-bg-danger mb-3">Schleswig-Holstein Community</span>
        <h1 class="display-5 fw-bold">eure Multigaming Community aus dem Norden Deutschlands</h1>
        <p class="lead opacity-75">Egal ob Shooter, Simulatoren oder Koop Action. Hier seid ihr genau richtig!</p>
        <div class="d-flex gap-2 flex-wrap">
          <a class="btn btn-danger btn-lg" href="<?= e(base_url('/register')) ?>">Community beitreten</a>
          <a class="btn btn-outline-light btn-lg" href="<?= e(base_url('/news')) ?>">Neueste News</a>
        </div>
      </div>
      <div class="col-lg-5 text-center">
        <img src="<?= e(base_url('/assets/img/logo.png')) ?>" class="img-fluid hero-logo" alt="Logo">
      </div>
    </div>
  </div>
</section>

<section class="container py-5">
  <div class="d-flex justify-content-between align-items-center mb-4"><h2 class="h3 mb-0">News</h2><a href="<?= e(base_url('/news')) ?>">Alle ansehen</a></div>
  <div class="row g-4">
    <?php foreach ($news as $item): ?>
    <div class="col-md-4"><div class="card h-100 sh-card"><div class="card-body"><h3 class="h5"><?= e($item['title']) ?></h3><p><?= e($item['teaser']) ?></p><a href="<?= e(base_url('/news/' . $item['slug'])) ?>" class="btn btn-sm btn-danger">Lesen</a></div></div></div>
    <?php endforeach; ?>
  </div>
</section>


<section class="container pb-5">
  <div class="row g-4">
    <div class="col-lg-6">
      <h2 class="h3 mb-3">Changelog</h2>
      <div class="card sh-card"><div class="card-body">
        <?php foreach ($changelogs as $item): ?>
          <div class="border-bottom py-3">
            <div class="d-flex justify-content-between flex-wrap gap-2"><strong><?= e($item['version']) ?> – <?= e($item['title']) ?></strong><span class="badge text-bg-secondary"><?= e($item['change_type']) ?></span></div>
            <small class="text-muted"><?= e($item['released_at']) ?></small>
          </div>
        <?php endforeach; ?>
      </div></div>
    </div>
    <div class="col-lg-6">
      <h2 class="h3 mb-3">LS 25 Server Status</h2>
      <div class="row g-3">
        <?php include APP_PATH . '/Views/partials/ls25_widget.php'; ?>
      </div>
    </div>
  </div>
</section>
