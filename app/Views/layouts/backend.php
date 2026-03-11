<?php use App\Models\NavigationItem; $nav = (new NavigationItem())->byArea('backend'); ?>
<!doctype html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin - <?= e(config('app.app_name', 'SH-COM Portal')) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= e(base_url('/assets/css/app.css')) ?>" rel="stylesheet">
    <script src="https://cdn.tiny.cloud/1/u9xub32inhd9dyvr5bw19t4b2jvfb4fg5e1tjz7ky1dgil4t/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
</head>
<body class="admin-body">
<div class="container-fluid">
  <div class="row min-vh-100">
    <aside class="col-12 col-lg-3 col-xl-2 sh-sidebar p-4">
      <a class="d-flex align-items-center gap-2 text-decoration-none mb-4" href="<?= e(base_url('/admin')) ?>">
        <img src="<?= e(base_url('/assets/img/logo.png')) ?>" alt="Logo" height="40">
        <span class="fw-bold text-white">Admin</span>
      </a>
      <nav class="nav flex-column gap-1">
        <?php foreach ($nav as $item): ?>
            <a class="nav-link sh-side-link" href="<?= e(base_url($item['route'])) ?>"><?= e($item['label']) ?></a>
        <?php endforeach; ?>
      </nav>
    </aside>
    <section class="col-12 col-lg-9 col-xl-10 p-4 p-lg-5">
      <?php if ($msg = flash('success')): ?><div class="alert alert-success"><?= e($msg) ?></div><?php endif; ?>
      <?php if ($msg = flash('error')): ?><div class="alert alert-danger"><?= e($msg) ?></div><?php endif; ?>
      <?= $content ?>
    </section>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
