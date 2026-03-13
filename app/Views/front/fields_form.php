<?php

use App\Core\Database;

$isEdit = !empty($field['id']);
$action = $isEdit
    ? '/fields/update/' . (int)$field['id']
    : '/fields/store';

$crops = Database::query("
    SELECT name
    FROM field_data_crops
    ORDER BY name ASC
")->fetchAll(PDO::FETCH_ASSOC);

$monthOptions = Database::query("
    SELECT value
    FROM field_data_
    WHERE type = 'planned_sowing_date'
")->fetchAll(PDO::FETCH_ASSOC);

$pendingOptions = Database::query("
    SELECT value
    FROM field_data_
    WHERE type = 'pending_work'
    ORDER BY value ASC
")->fetchAll(PDO::FETCH_ASSOC);

?>

<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
  <div>
    <h1 class="h3 mb-0"><?= $isEdit ? 'Feld bearbeiten' : 'Feld anlegen' ?></h1>
    <div class="text-muted small">Precision Farming</div>
  </div>
  <a class="btn btn-outline-secondary" href="/fields">Zur Übersicht</a>
</div>

<form class="card border-0 shadow-sm" method="post" action="<?= e($action) ?>">
  <div class="card-body">
    <?= csrf_field() ?>

    <?php if ($isEdit): ?>
      <input type="hidden" name="id" value="<?= (int)($field['id'] ?? 0) ?>">
    <?php endif; ?>

    <div class="row g-3">
      <div class="col-12 col-md-6">
        <label class="form-label">Feld</label>
        <input
          class="form-control"
          name="field_name"
          required
          value="<?= e((string)($field['field_name'] ?? '')) ?>"
        >
      </div>

      <div class="col-12 col-md-6">
        <label class="form-label">Geplante Aussaat</label>
        <select class="form-select" name="planned_sowing_date">
          <option value="">—</option>
          <?php foreach ($monthOptions as $o): ?>
            <?php $value = (string)($o['value'] ?? ''); ?>
            <option value="<?= e($value) ?>" <?= ((string)($field['planned_sowing_date'] ?? '') === $value) ? 'selected' : '' ?>>
              <?= e($value) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="col-12 col-md-6">
        <label class="form-label">Aktuelle Feldfrucht</label>
        <select class="form-select" name="current_crop">
          <option value="">—</option>
          <?php foreach ($crops as $o): ?>
            <?php $value = (string)($o['name'] ?? ''); ?>
            <option value="<?= e($value) ?>" <?= ((string)($field['current_crop'] ?? '') === $value) ? 'selected' : '' ?>>
              <?= e($value) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="col-12 col-md-6">
        <label class="form-label">Geplante Feldfrucht</label>
        <select class="form-select" name="planned_crop">
          <option value="">—</option>
          <?php foreach ($crops as $o): ?>
            <?php $value = (string)($o['name'] ?? ''); ?>
            <option value="<?= e($value) ?>" <?= ((string)($field['planned_crop'] ?? '') === $value) ? 'selected' : '' ?>>
              <?= e($value) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="col-12">
        <label class="form-label">Anliegende Arbeiten</label>
        <select class="form-select" name="pending_work">
          <option value="">—</option>
          <?php foreach ($pendingOptions as $o): ?>
            <?php $value = (string)($o['value'] ?? ''); ?>
            <option value="<?= e($value) ?>" <?= ((string)($field['pending_work'] ?? '') === $value) ? 'selected' : '' ?>>
              <?= e($value) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="col-12 col-md-6">
        <label class="form-label">Ertragsbonus</label>
        <input
          class="form-control"
          name="yield_bonus"
          value="<?= e((string)($field['yield_bonus'] ?? '')) ?>"
        >
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
    <a class="btn btn-outline-secondary" href="/fields">Abbrechen</a>
    <button class="btn btn-dark"><?= $isEdit ? 'Speichern' : 'Anlegen' ?></button>
  </div>
</form>