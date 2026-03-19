<?php
use App\Models\NavigationItem;
use App\Core\Auth;

$navItems = (new NavigationItem())->tree('frontend');
$user = Auth::user();
?>
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
      <span class="fw-bold text-white">Schleswig-Holstein Community</span>
    </a>
    <button class="navbar-toggler text-white border-0" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav"><span class="navbar-toggler-icon"></span></button>
    <div class="collapse navbar-collapse" id="mainNav">
    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
    <?php foreach ($navItems as $item): ?>
        <?php
        $hasChildren = !empty($item['children']);
        $label = htmlspecialchars((string)$item['label']);
        $route = htmlspecialchars((string)($item['route'] ?? '#'));
        $icon  = !empty($item['icon']) ? '<i class="' . htmlspecialchars((string)$item['icon']) . ' me-1"></i>' : '';
        ?>

        <?php if ($hasChildren): ?>
            <li class="nav-item dropdown">
                <a
                    class="nav-link dropdown-toggle text-white"
                    href="#"
                    id="navDropdown<?= (int)$item['id'] ?>"
                    role="button"
                    data-bs-toggle="dropdown"
                    aria-expanded="false"
                >
                    <?= $icon ?><?= $label ?>
                </a>

                <ul class="dropdown-menu" aria-labelledby="navDropdown<?= (int)$item['id'] ?>">
                    <?php foreach ($item['children'] as $child): ?>
                        <?php
                        $childLabel = htmlspecialchars((string)$child['label']);
                        $childRoute = htmlspecialchars((string)($child['route'] ?? '#'));
                        $childIcon  = !empty($child['icon']) ? '<i class="' . htmlspecialchars((string)$child['icon']) . ' me-1"></i>' : '';
                        ?>
                        <li>
                            <a class="dropdown-item" href="<?= $childRoute ?>">
                                <?= $childIcon ?><?= $childLabel ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </li>
        <?php else: ?>
            <li class="nav-item">
                <a class="nav-link text-white" href="<?= $route ?>">
                    <?= $icon ?><?= $label ?>
                </a>
            </li>
        <?php endif; ?>
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
    <div><small><a class="text-decoration-none text-white" href="/page/imprint">Impressum</a>  |  <a class="text-decoration-none text-white" href="/page/datenschutz">Datenschutz</a></small></div>
    <div><small>Build: 1.6.2 Beta | Copyright &copy; <?= date('Y') ?> <a class="text-decoration-none text-white" href="https://www.sh-com.de">Schleswig-Holstein Community</a></small></div>
  </div>
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= e(base_url('/assets/js/app.js')) ?>"></script>
</body>
</html>
