<?php
$isEdit = ($mode ?? '') === 'edit';
$field = $field ?? [];
$action = $isEdit
    ? '/fields/update/' . (int)($field['id'] ?? 0)
    : '/fields/store';
?>

<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
  <div>
    <h1 class="h3 mb-0"><?= $isEdit ? 'Feld bearbeiten' : 'Feld anlegen' ?></h1>
    <div class="text-muted small">Precision Farming</div>
  </div>
  <a class="btn btn-outline-secondary" href="<?= e(base_url('/fields')) ?>">Zur Übersicht</a>
</div>

<form class="card border-0 shadow-sm" method="post" action="<?= e(base_url($action)) ?>">
  <div class="card-body">
    <?= csrf_field() ?>
    <?php if ($isEdit): ?>
      <input type="hidden" name="id" value="<?= (int)($field['id'] ?? 0) ?>">
    <?php endif; ?>

    <div class="row g-3">
      <div class="col-12 col-md-6">
        <label class="form-label">Feld</label>
        <input class="form-control" name="field_name" required value="<?= e((string)($field['field_name'] ?? '')) ?>">
      </div>

      <div class="col-12 col-md-6">
        <label class="form-label">Hofzuweisung</label>
        <select class="form-select" name="farm_id">
          <option value="">— Kein Hof —</option>
          <?php foreach (($farms ?? []) as $farm): ?>
            <?php $farmId = (string)($farm['id'] ?? ''); ?>
            <option value="<?= e($farmId) ?>" <?= ((string)($field['farm_id'] ?? '') === $farmId) ? 'selected' : '' ?>>
              <?= e((string)($farm['name'] ?? ('Hof #' . $farmId))) ?>
            </option>
          <?php endforeach; ?>
        </select>
        <div class="form-text">Hier kannst du das Feld einem Hof aus der Datenbank zuweisen.</div>
      </div>

      <div class="col-12 col-md-6">
        <label class="form-label">Geplante Aussaat</label>
        <select class="form-select" name="planned_sowing_date">
          <option value="">—</option>
          <?php foreach (($monthOptions ?? []) as $o): ?>
            <option value="<?= e((string)$o['value']) ?>" <?= ((string)($field['planned_sowing_date'] ?? '') === (string)$o['value']) ? 'selected' : '' ?>>
              <?= e((string)$o['label']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="col-12 col-md-6">
        <label class="form-label">Aktuelle Feldfrucht</label>
        <select class="form-select" name="current_crop">
          <option value="">—</option>
          <?php foreach (($crops ?? []) as $o): ?>
            <option value="<?= e((string)$o['value']) ?>" <?= ((string)($field['current_crop'] ?? '') === (string)$o['value']) ? 'selected' : '' ?>>
              <?= e((string)$o['label']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="col-12 col-md-6">
        <label class="form-label">Geplante Feldfrucht</label>
        <select class="form-select" name="planned_crop">
          <option value="">—</option>
          <?php foreach (($crops ?? []) as $o): ?>
            <option value="<?= e((string)$o['value']) ?>" <?= ((string)($field['planned_crop'] ?? '') === (string)$o['value']) ? 'selected' : '' ?>>
              <?= e((string)$o['label']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="col-12">
        <label class="form-label">Anliegende Arbeiten</label>
        <select class="form-select" name="pending_work">
          <option value="">—</option>
          <?php foreach (($pendingOptions ?? []) as $o): ?>
            <option value="<?= e((string)$o['value']) ?>" <?= ((string)($field['pending_work'] ?? '') === (string)$o['value']) ? 'selected' : '' ?>>
              <?= e((string)$o['label']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="col-12 col-md-6">
        <label class="form-label">Ertragsbonus</label>
        <input class="form-control" name="yield_bonus" value="<?= e((string)($field['yield_bonus'] ?? '')) ?>">
      </div>

      <div class="col-12">
        <label class="form-label">Anmerkungen</label>
        <textarea class="form-control" name="notes" rows="4"><?= e((string)($field['notes'] ?? '')) ?></textarea>
      </div>

      <div class="col-12">
        <div class="row g-2">
          <div class="col-6 col-md-2">
            <div class="form-check">
              <input type="hidden" name="fertilization_stage_1" value="0">
              <input class="form-check-input" type="checkbox" name="fertilization_stage_1" value="1" <?= !empty($field['fertilization_stage_1']) ? 'checked' : '' ?>>
              <label class="form-check-label">Düngung I</label>
            </div>
          </div>

          <div class="col-6 col-md-2">
            <div class="form-check">
              <input type="hidden" name="fertilization_stage_2" value="0">
              <input class="form-check-input" type="checkbox" name="fertilization_stage_2" value="1" <?= !empty($field['fertilization_stage_2']) ? 'checked' : '' ?>>
              <label class="form-check-label">Düngung II</label>
            </div>
          </div>

          <div class="col-6 col-md-2">
            <div class="form-check">
              <input type="hidden" name="plowed" value="0">
              <input class="form-check-input" type="checkbox" name="plowed" value="1" <?= !empty($field['plowed']) ? 'checked' : '' ?>>
              <label class="form-check-label">Gepflügt</label>
            </div>
          </div>

          <div class="col-6 col-md-2">
            <div class="form-check">
              <input type="hidden" name="lime" value="0">
              <input class="form-check-input" type="checkbox" name="lime" value="1" <?= !empty($field['lime']) ? 'checked' : '' ?>>
              <label class="form-check-label">Kalk</label>
            </div>
          </div>

          <div class="col-6 col-md-2">
            <div class="form-check">
              <input type="hidden" name="rolled" value="0">
              <input class="form-check-input" type="checkbox" name="rolled" value="1" <?= !empty($field['rolled']) ? 'checked' : '' ?>>
              <label class="form-check-label">Gewalzt</label>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="card-footer bg-white border-0 d-flex justify-content-end gap-2">
    <a class="btn btn-outline-secondary" href="<?= e(base_url('/fields')) ?>">Abbrechen</a>
    <button class="btn btn-dark"><?= $isEdit ? 'Speichern' : 'Anlegen' ?></button>
  </div>
</form>
