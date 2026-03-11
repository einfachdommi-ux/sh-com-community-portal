<h1 class="mb-4">Rollen & Permissions</h1>
<div class="row g-4">
  <div class="col-xl-4">
    <div class="card sh-card"><div class="card-body">
      <h2 class="h5">Rolle anlegen</h2>
      <form method="post" action="<?= e(base_url('/admin/roles/store')) ?>">
        <?= csrf_field() ?>
        <div class="mb-3"><label class="form-label">Name</label><input class="form-control" name="name" required></div>
        <div class="mb-3"><label class="form-label">Slug</label><input class="form-control" name="slug" required></div>
        <div class="mb-3"><label class="form-label">Beschreibung</label><textarea class="form-control" name="description"></textarea></div>
        <button class="btn btn-danger w-100">Speichern</button>
      </form>
    </div></div>
  </div>
  <div class="col-xl-8">
    <?php foreach ($roles as $role): ?>
      <div class="card sh-card mb-4"><div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3"><h2 class="h5 mb-0"><?= e($role['name']) ?></h2><span class="badge text-bg-secondary"><?= e($role['slug']) ?></span></div>
        <form method="post" action="<?= e(base_url('/admin/roles/permissions')) ?>">
          <?= csrf_field() ?>
          <input type="hidden" name="role_id" value="<?= e((string)$role['id']) ?>">
          <div class="row g-2">
            <?php foreach ($permissions as $permission): ?>
              <div class="col-md-6">
                <label class="form-check">
                  <input class="form-check-input" type="checkbox" name="permission_ids[]" value="<?= e((string)$permission['id']) ?>" <?= in_array($permission['id'], $selected[$role['id']] ?? [], true) ? 'checked' : '' ?>>
                  <span class="form-check-label"><?= e($permission['name']) ?> <small class="text-muted">(<?= e($permission['slug']) ?>)</small></span>
                </label>
              </div>
            <?php endforeach; ?>
          </div>
          <button class="btn btn-outline-light mt-3">Permissions speichern</button>
        </form>
      </div></div>
    <?php endforeach; ?>
  </div>
</div>
