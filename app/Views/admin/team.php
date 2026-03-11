<h1 class="mb-4">Team-Verwaltung</h1>
<div class="row g-4">
  <div class="col-xl-5">
    <div class="card sh-card"><div class="card-body">
      <form method="post" action="<?= e(base_url('/admin/team/store')) ?>" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <div class="mb-3"><label class="form-label">Name</label><input class="form-control" name="display_name" required></div>
        <div class="mb-3"><label class="form-label">Teamrolle</label><input class="form-control" name="team_role" required></div>
        <div class="mb-3"><label class="form-label">Bio</label><textarea class="form-control" name="bio" rows="4"></textarea></div>
        <div class="mb-3"><label class="form-label">Profilbild</label><input class="form-control" type="file" name="image_file" accept=".jpg,.jpeg,.png,.webp"></div>
        <div class="row g-3">
          <div class="col-md-4"><label class="form-label">Discord</label><input class="form-control" name="discord"></div>
          <div class="col-md-4"><label class="form-label">X</label><input class="form-control" name="x"></div>
          <div class="col-md-4"><label class="form-label">Instagram</label><input class="form-control" name="instagram"></div>
        </div>
        <div class="row g-3 mt-1">
          <div class="col-md-6"><label class="form-label">Sortierung</label><input class="form-control" type="number" name="sort_order" value="0"></div>
          <div class="col-md-6 d-flex align-items-end"><div class="form-check"><input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active" checked><label class="form-check-label" for="is_active">Sichtbar / Aktiv</label></div></div>
        </div>
        <button class="btn btn-danger w-100 mt-3">Teammitglied speichern</button>
      </form>
    </div></div>
  </div>
  <div class="col-xl-7">
    <div class="card sh-card"><div class="card-body">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
          <h2 class="h5 mb-1">Vorhandene Teammitglieder</h2>
          <div class="small text-muted">Drag & Drop Sortierung aktiv. Danach auf „Sortierung speichern“ klicken.</div>
        </div>
        <button type="button" class="btn btn-outline-light btn-sm" id="save-sort-order">Sortierung speichern</button>
      </div>
      <div class="table-responsive"><table class="table table-dark align-middle">
        <thead><tr><th></th><th>Bild</th><th>Name</th><th>Rolle</th><th>Status</th><th>Sort</th><th>Aktionen</th></tr></thead>
        <tbody id="sortable-team-list"><?php foreach ($items as $item): $links = json_decode($item['social_links'] ?? '{}', true) ?: []; ?><tr draggable="true" data-id="<?= (int)$item['id'] ?>">
          <td class="text-muted">↕</td>
          <td><?php if (!empty($item['image_path'])): ?><img src="<?= e(base_url($item['image_path'])) ?>" alt="" style="width:48px;height:48px;object-fit:cover;border-radius:50%;"><?php else: ?><span class="small text-secondary">Kein Bild</span><?php endif; ?></td>
          <td><div class="fw-semibold"><?= e($item['display_name']) ?></div><?php if (!empty($links['discord'])): ?><div class="small text-secondary">Discord: <?= e($links['discord']) ?></div><?php endif; ?></td>
          <td><?php $badge='secondary'; if (($item['team_role'] ?? '')==='Projektleitung') $badge='warning'; elseif (($item['team_role'] ?? '')==='Community-Manager') $badge='primary'; elseif (($item['team_role'] ?? '')==='Community-Moderator') $badge='success'; elseif (($item['team_role'] ?? '')==='Social-Media Moderator') $badge='danger'; ?><span class="badge text-bg-<?= $badge ?>"><?= e($item['team_role']) ?></span></td>
          <td><?php if ((int)($item['is_active'] ?? 0) === 1): ?><span class="badge text-bg-success">Aktiv</span><?php else: ?><span class="badge text-bg-secondary">Inaktiv</span><?php endif; ?></td>
          <td><?= e((string)$item['sort_order']) ?></td>
          <td>
            <a href="<?= e(base_url('/admin/team/edit/' . (int)$item['id'])) ?>" class="btn btn-sm btn-warning">Bearbeiten</a>
            <form method="post" action="<?= e(base_url('/admin/team/delete/' . (int)$item['id'])) ?>" class="d-inline" onsubmit="return confirm('Teammitglied wirklich löschen?');">
              <?= csrf_field() ?>
              <button class="btn btn-sm btn-outline-danger">Löschen</button>
            </form>
          </td>
        </tr><?php endforeach; ?></tbody>
      </table></div>
    </div></div>
  </div>
</div>
<script src="<?= e(base_url('/assets/js/team-sort.js')) ?>"></script>
