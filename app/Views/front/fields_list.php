<?php
$filters = $filters ?? [];
?>
<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
  <div>
    <h1 class="h3 mb-0">Felder</h1>
    <div class="text-muted small">Precision Farming Übersicht</div>
  </div>
  <a class="btn btn-dark" href="<?= e(base_url('/fields/create')) ?>">Feld anlegen</a>
</div>

<?php if (!empty($_SESSION['flash_success'])): ?>
  <div class="alert alert-success"><?= e((string)$_SESSION['flash_success']) ?></div>
  <?php unset($_SESSION['flash_success']); ?>
<?php endif; ?>

<?php if (!empty($_SESSION['flash_error'])): ?>
  <div class="alert alert-danger"><?= e((string)$_SESSION['flash_error']) ?></div>
  <?php unset($_SESSION['flash_error']); ?>
<?php endif; ?>

<form class="card border-0 shadow-sm mb-4" method="get" action="<?= e(base_url('/fields')) ?>">
  <div class="card-body">
    <div class="row g-3">
      <div class="col-12 col-md-3">
        <label class="form-label">Suche</label>
        <input class="form-control" type="text" name="search" value="<?= e((string)($filters['search'] ?? '')) ?>" placeholder="Feldname oder Anmerkung">
      </div>

      <div class="col-12 col-md-3">
        <label class="form-label">Anliegende Arbeiten</label>
        <select class="form-select" name="pending_work">
          <option value="">— Alle —</option>
          <?php foreach (($pendingWorks ?? []) as $option): ?>
            <?php $value = (string)($option['value'] ?? ''); ?>
            <option value="<?= e($value) ?>" <?= ((string)($filters['pending_work'] ?? '') === $value) ? 'selected' : '' ?>>
              <?= e($value) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="col-12 col-md-3">
        <label class="form-label">Hof</label>
        <select class="form-select" name="farm_id">
          <option value="">— Alle —</option>
          <?php foreach (($farms ?? []) as $farm): ?>
            <?php $farmId = (string)($farm['id'] ?? ''); ?>
            <option value="<?= e($farmId) ?>" <?= ((string)($filters['farm_id'] ?? '') === $farmId) ? 'selected' : '' ?>>
              <?= e((string)($farm['name'] ?? ('Hof #' . $farmId))) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="col-12 col-md-3">
        <label class="form-label">Geplante Aussaat</label>
        <select class="form-select" name="planned_sowing_date">
          <option value="">— Alle —</option>
          <?php foreach (($plannedSowingDates ?? []) as $option): ?>
            <?php $value = (string)($option['value'] ?? ''); ?>
            <option value="<?= e($value) ?>" <?= ((string)($filters['planned_sowing_date'] ?? '') === $value) ? 'selected' : '' ?>>
              <?= e($value) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>

    <div class="mt-3 d-flex gap-2">
      <button class="btn btn-primary">Filtern</button>
      <a class="btn btn-outline-secondary" href="<?= e(base_url('/fields')) ?>">Zurücksetzen</a>
    </div>
  </div>
</form>

<div class="card border-0 shadow-sm">
  <div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
      <thead class="table-light">
        <tr>
          <th>Feld</th>
          <th>Hof</th>
          <th>Aktuelle Feldfrucht</th>
          <th>Geplante Feldfrucht</th>
          <th>Arbeiten</th>
          <th>Aussaat</th>
          <th>Aktionen</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach (($fields ?? []) as $field): ?>
          <tr>
            <td>
              <div class="fw-semibold"><?= e((string)($field['field_name'] ?? '')) ?></div>
              <?php if (!empty($field['notes'])): ?>
                <div class="small text-muted mt-1">
                  <?= e(mb_strimwidth((string)$field['notes'], 0, 120, '...')) ?>
                </div>
              <?php endif; ?>
            </td>
            <td><?= e((string)($field['farm_name'] ?? '—')) ?></td>
            <td><?= e((string)($field['current_crop'] ?? '—')) ?></td>
            <td><?= e((string)($field['planned_crop'] ?? '—')) ?></td>
            <td><?= e((string)($field['pending_work'] ?? '—')) ?></td>
            <td><?= e((string)($field['planned_sowing_date'] ?? '—')) ?></td>
            <td>
              <div class="d-flex gap-2">
                <a class="btn btn-sm btn-outline-primary" href="<?= e(base_url('/fields/edit/' . (int)$field['id'])) ?>">Bearbeiten</a>
                <form method="post" action="<?= e(base_url('/fields/delete/' . (int)$field['id'])) ?>" onsubmit="return confirm('Feld wirklich löschen?')">
                  <?= csrf_field() ?>
                  <button class="btn btn-sm btn-outline-danger">Löschen</button>
                </form>
              </div>
            </td>
          </tr>
        <?php endforeach; ?>

        <?php if (empty($fields)): ?>
          <tr>
            <td colspan="7" class="text-center text-muted py-4">Keine Felder gefunden.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
