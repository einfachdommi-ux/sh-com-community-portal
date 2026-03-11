<h1 class="mb-4">Teamseite</h1>
<div class="row g-4">
  <div class="col-xl-5">
    <div class="card sh-card"><div class="card-body">
      <form method="post" action="<?= e(base_url('/admin/team/store')) ?>">
        <?= csrf_field() ?>
        <div class="mb-3"><label class="form-label">Name</label><input class="form-control" name="display_name" required></div>
        <div class="mb-3"><label class="form-label">Teamrolle</label><input class="form-control" name="team_role" required></div>
        <div class="mb-3"><label class="form-label">Bio</label><textarea class="form-control" name="bio" rows="4"></textarea></div>
        <div class="row g-3">
          <div class="col-md-4"><label class="form-label">Discord</label><input class="form-control" name="discord"></div>
          <div class="col-md-4"><label class="form-label">X</label><input class="form-control" name="x"></div>
          <div class="col-md-4"><label class="form-label">Instagram</label><input class="form-control" name="instagram"></div>
        </div>
        <div class="mt-3"><label class="form-label">Sortierung</label><input class="form-control" type="number" name="sort_order" value="0"></div>
        <button class="btn btn-danger w-100 mt-3">Teammitglied speichern</button>
      </form>
    </div></div>
  </div>
  <div class="col-xl-7">
    <div class="card sh-card"><div class="card-body"><div class="table-responsive"><table class="table table-dark">
      <thead><tr><th>Name</th><th>Rolle</th><th>Sort</th></tr></thead>
      <tbody><?php foreach ($items as $item): ?><tr><td><?= e($item['display_name']) ?></td><td><?= e($item['team_role']) ?></td><td><?= e((string)$item['sort_order']) ?></td></tr><?php endforeach; ?></tbody>
    </table></div></div></div>
  </div>
</div>
