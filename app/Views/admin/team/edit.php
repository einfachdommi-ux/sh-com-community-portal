<h1 class="mb-4">Teammitglied bearbeiten</h1>
<?php $links = ['discord' => $member['discord'] ?? '', 'x' => $member['x'] ?? '', 'instagram' => $member['instagram'] ?? '']; ?>
<div class="card sh-card"><div class="card-body">
  <form method="post" action="<?= e(base_url('/admin/team/update/' . (int)$member['id'])) ?>" enctype="multipart/form-data">
    <?= csrf_field() ?>
    <div class="row g-3">
      <div class="col-md-6"><label class="form-label">Name</label><input class="form-control" name="display_name" value="<?= e($member['display_name']) ?>" required></div>
      <div class="col-md-6"><label class="form-label">Teamrolle</label><input class="form-control" name="team_role" value="<?= e($member['team_role']) ?>" required></div>
      <div class="col-12"><label class="form-label">Bio</label><textarea class="form-control" name="bio" rows="5"><?= e($member['bio'] ?? '') ?></textarea></div>
      <div class="col-md-6"><label class="form-label">Discord</label><input class="form-control" name="discord" value="<?= e($links['discord']) ?>"></div>
      <div class="col-md-3"><label class="form-label">X</label><input class="form-control" name="x" value="<?= e($links['x']) ?>"></div>
      <div class="col-md-3"><label class="form-label">Instagram</label><input class="form-control" name="instagram" value="<?= e($links['instagram']) ?>"></div>
      <div class="col-md-4"><label class="form-label">Sortierung</label><input class="form-control" type="number" name="sort_order" value="<?= e((string)($member['sort_order'] ?? 0)) ?>"></div>
      <div class="col-md-4"><label class="form-label">Profilbild</label><input class="form-control" type="file" name="image_file" accept=".jpg,.jpeg,.png,.webp"></div>
      <div class="col-md-4 d-flex align-items-end"><div class="form-check"><input class="form-check-input" type="checkbox" name="is_active" value="1" id="edit_active" <?= ((int)($member['is_active'] ?? 0) === 1) ? 'checked' : '' ?>><label class="form-check-label" for="edit_active">Sichtbar / Aktiv</label></div></div>
      <div class="col-12"><?php if (!empty($member['image_path'])): ?><img src="<?= e(base_url($member['image_path'])) ?>" alt="" style="width:96px;height:96px;object-fit:cover;border-radius:16px;"><?php else: ?><div class="small text-muted">Kein Bild vorhanden.</div><?php endif; ?></div>
      <div class="col-12 d-flex gap-2"><button class="btn btn-danger">Änderungen speichern</button><a class="btn btn-outline-light" href="<?= e(base_url('/admin/team')) ?>">Zurück</a></div>
    </div>
  </form>
</div></div>
