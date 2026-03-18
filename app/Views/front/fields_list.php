<?php
function fieldStatusClass(array $field): string
{
    $count = 0;
    $count += !empty($field['fertilization_stage_1']) ? 1 : 0;
    $count += !empty($field['fertilization_stage_2']) ? 1 : 0;
    $count += !empty($field['plowed']) ? 1 : 0;
    $count += !empty($field['lime']) ? 1 : 0;
    $count += !empty($field['rolled']) ? 1 : 0;

    if ($count <= 1) return 'table-danger';
    if ($count <= 3) return 'table-warning';
    return 'table-success';
}
?>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Felder</h1>
        <a href="/fields/create" class="btn btn-primary">Feld anlegen</a>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="get" action="/fields" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Suche</label>
                    <input
                        type="text"
                        name="search"
                        class="form-control"
                        value="<?= htmlspecialchars($filters['search'] ?? '') ?>"
                        placeholder="Feldname, Frucht, Notizen ..."
                    >
                </div>

                <div class="col-md-3">
                    <label class="form-label">Anliegende Arbeiten</label>
                    <select name="pending_work" class="form-select">
                        <option value="">Alle</option>
                        <?php foreach ($pendingWorks as $work): ?>
                            <option value="<?= htmlspecialchars($work['value']) ?>"
                                <?= (($filters['pending_work'] ?? '') === $work['value']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($work['value']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Hof</label>
                    <select name="farm_id" class="form-select">
                        <option value="">Alle</option>
                        <?php foreach ($farms as $farm): ?>
                            <option value="<?= (int)$farm['id'] ?>"
                                <?= ((string)($filters['farm_id'] ?? '') === (string)$farm['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($farm['name'] ?? $farm['farm_name'] ?? ('Hof #' . $farm['id'])) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Geplante Aussaat</label>
                    <select name="planned_sowing_date" class="form-select">
                        <option value="">Alle</option>
                        <?php foreach ($plannedSowingDates as $date): ?>
                            <option value="<?= htmlspecialchars($date['value']) ?>"
                                <?= (($filters['planned_sowing_date'] ?? '') === $date['value']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($date['value']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-12 d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Filtern</button>
                    <a href="/fields" class="btn btn-outline-secondary">Zurücksetzen</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Feldname</th>
                        <th>Aktuelle Feldfrucht</th>
                        <th>Geplante Feldfrucht</th>
                        <th>Geplante Aussaat</th>
                        <th>Anliegende Arbeiten</th>
                        <th>Ertragsbonus</th>
                        <th>Hof</th>
                        <th>Aktionen</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($fields as $field): ?>
                    <tr class="<?= fieldStatusClass($field) ?>">
                        <td><?= htmlspecialchars($field['field_name']) ?></td>
                        <td><?= htmlspecialchars($field['current_crop'] ?? '') ?></td>
                        <td><?= htmlspecialchars($field['planned_crop'] ?? '') ?></td>
                        <td><?= htmlspecialchars($field['planned_sowing_date'] ?? '') ?></td>
                        <td><?= htmlspecialchars($field['pending_work'] ?? '') ?></td>
                        <td><?= htmlspecialchars($field['yield_bonus'] ?? '') ?></td>
                        <td><?= htmlspecialchars((string)($field['farm_name'] ?? '')) ?></td>
                        <td>
                            <a href="/fields/edit/<?= (int)$field['id'] ?>" class="btn btn-sm btn-warning">Bearbeiten</a>
                            <form method="post" action="/fields/delete/<?= (int)$field['id'] ?>" class="d-inline" onsubmit="return confirm('Feld wirklich löschen?');">
                                <button type="submit" class="btn btn-sm btn-danger">Löschen</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>

                <?php if (empty($fields)): ?>
                    <tr>
                        <td colspan="8" class="text-center text-muted">Keine Felder gefunden.</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>

            <div class="mt-3 d-flex gap-3 small">
                <span><span class="badge text-bg-danger">Rot</span> Feld unbearbeitet</span>
                <span><span class="badge text-bg-warning">Gelb</span> Feld teilweise bearbeitet</span>
                <span><span class="badge text-bg-success">Grün</span> alles erledigt</span>
            </div>
        </div>
    </div>
</div>