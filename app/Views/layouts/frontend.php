<?php use App\Core\Auth; use App\Models\NavigationItem; $nav = (new NavigationItem())->byArea('frontend'); ?>
<!doctype html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e(config('app.app_name', 'SH-COM Portal')) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= e(base_url('/assets/css/app.css')) ?>" rel="stylesheet">
</head>
<body class="bg-body-tertiary">
<nav class="navbar navbar-expand-lg sh-navbar sticky-top">
  <div class="container">
    <a class="navbar-brand d-flex align-items-center gap-2" href="<?= e(base_url('/')) ?>">
      <img src="<?= e(base_url('/assets/img/logo.png')) ?>" alt="Logo" height="42">
      <span class="fw-bold text-white">SH-COM</span>
    </a>
    <button class="navbar-toggler text-white border-0" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav"><span class="navbar-toggler-icon"></span></button>
    <div class="collapse navbar-collapse" id="mainNav">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <?php foreach ($nav as $item): ?>
          <li class="nav-item"><a class="nav-link text-white" href="<?= e(base_url($item['route'])) ?>"><?= e($item['label']) ?></a></li>
        <?php endforeach; ?>
      </ul>
      <div class="d-flex gap-2">
        <?php if (Auth::check()): ?>
            <a class="btn btn-outline-light btn-sm" href="<?= e(base_url('/admin')) ?>">Admin</a>
            <a class="btn btn-danger btn-sm" href="<?= e(base_url('/logout')) ?>">Logout</a>
        <?php else: ?>
            <a class="btn btn-outline-light btn-sm" href="<?= e(base_url('/login')) ?>">Login</a>
            <a class="btn btn-danger btn-sm" href="<?= e(base_url('/register')) ?>">Registrieren</a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</nav>

<main>
<?php if ($msg = flash('success')): ?><div class="container mt-3"><div class="alert alert-success"><?= e($msg) ?></div></div><?php endif; ?>
<?php if ($msg = flash('error')): ?><div class="container mt-3"><div class="alert alert-danger"><?= e($msg) ?></div></div><?php endif; ?>
<?= $content ?>
</main>

<footer class="py-5 mt-5 sh-footer text-white">
  <div class="container d-flex flex-column flex-lg-row justify-content-between gap-3">
    <div><strong>Schleswig-Holstein Community</strong><br><small>Gaming, Community und News.</small></div>
    <div><small>&copy; <?= date('Y') ?> SH-COM</small></div>
  </div>
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= e(base_url('/assets/js/app.js')) ?>"></script>
</body>
</html>
