<div class="container py-4">
  <h1 class="h3 mb-3">Hof beitreten</h1>
  <p class="text-muted">Wähle einen Hof aus, dem du zugeordnet sein möchtest.</p>
  <a href="/profile" class="btn btn-outline-secondary btn-sm mb-3">Zum Profil</a>

  <?php if (!empty($_SESSION['flash_error'])): ?><div class="alert alert-danger"><?= htmlspecialchars($_SESSION['flash_error']); unset($_SESSION['flash_error']); ?></div><?php endif; ?>

  <?php if (!empty($farms)): ?>
    <form method="post" action="/hof/join" class="card shadow-sm p-3">
      <div class="mb-3"><label class="form-label">Hof</label><select name="farm_id" class="form-select" required><?php foreach ($farms as $farm): ?><option value="<?= (int)$farm['id'] ?>"><?= htmlspecialchars($farm['name']) ?> (<?= htmlspecialchars($farm['slug']) ?>)</option><?php endforeach; ?></select></div>
      <div class="mb-3"><label class="form-label">Hof Passwort</label><input type="password" name="farm_password" class="form-control" required></div>
      <button class="btn btn-primary">Beitreten</button>
    </form>
  <?php else: ?>
    <div class="alert alert-warning">Es existieren noch keine Höfe. Bitte Admin kontaktieren.</div>
  <?php endif; ?>
</div>
