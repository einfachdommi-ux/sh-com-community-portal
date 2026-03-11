<h1 class="mb-4">User Verwaltung</h1>
<div class="row g-4">
  <div class="col-xl-4">
    <div class="card sh-card"><div class="card-body">
      <h2 class="h5 mb-3">Benutzer anlegen</h2>
      <form method="post" action="<?= e(base_url('/admin/users/store')) ?>">
        <?= csrf_field() ?>
        <div class="mb-3"><label class="form-label">Benutzername</label><input class="form-control" name="username" required></div>
        <div class="mb-3"><label class="form-label">E-Mail</label><input class="form-control" name="email" type="email" required></div>
        <div class="mb-3"><label class="form-label">Passwort</label><input class="form-control" name="password" type="password" required></div>
        <div class="mb-3"><label class="form-label">Rolle</label><select class="form-select" name="role_id"><?php foreach($roles as $role): ?><option value="<?= e((string)$role['id']) ?>"><?= e($role['name']) ?></option><?php endforeach; ?></select></div>
        <div class="form-check"><input class="form-check-input" type="checkbox" name="is_active" checked><label class="form-check-label">Aktiv</label></div>
        <div class="form-check mb-3"><input class="form-check-input" type="checkbox" name="is_verified"><label class="form-check-label">Verifiziert</label></div>
        <button class="btn btn-danger w-100">Speichern</button>
      </form>
    </div></div>
  </div>
  <div class="col-xl-8">
    <div class="card sh-card"><div class="card-body">
      <div class="table-responsive"><table class="table table-dark table-hover align-middle">
        <thead><tr><th>ID</th><th>Name</th><th>E-Mail</th><th>Rolle</th><th>Status</th><th>Verifiziert</th></tr></thead>
        <tbody><?php foreach ($users as $user): ?><tr><td><?= e((string)$user['id']) ?></td><td><?= e($user['username']) ?></td><td><?= e($user['email']) ?></td><td><?= e($user['role_name'] ?? '—') ?></td><td><?= $user['is_active'] ? 'Aktiv' : 'Inaktiv' ?></td><td><?= $user['is_verified'] ? 'Ja' : 'Nein' ?></td></tr><?php endforeach; ?></tbody>
      </table></div>
    </div></div>
  </div>
</div>
