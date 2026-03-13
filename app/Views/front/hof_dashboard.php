<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Hof Dashboard</h1>
        <a href="/hof" class="btn btn-outline-secondary">Zur Hofseite</a>
    </div>

    <?php if (!empty($farm)): ?>
        <div class="row g-3 mb-4">
            <div class="col-md-3"><div class="card shadow-sm"><div class="card-body"><div class="text-muted small">Felder</div><div class="fs-3 fw-bold"><?= (int)$stats['total_fields'] ?></div></div></div></div>
            <div class="col-md-3"><div class="card shadow-sm"><div class="card-body"><div class="text-muted small">Mitglieder</div><div class="fs-3 fw-bold"><?= (int)$stats['total_members'] ?></div></div></div></div>
            <div class="col-md-3"><div class="card shadow-sm"><div class="card-body"><div class="text-muted small">Düngung I</div><div class="fs-3 fw-bold"><?= (int)$stats['fertilization_stage_1_done'] ?></div></div></div></div>
            <div class="col-md-3"><div class="card shadow-sm"><div class="card-body"><div class="text-muted small">Düngung II</div><div class="fs-3 fw-bold"><?= (int)$stats['fertilization_stage_2_done'] ?></div></div></div></div>
        </div>

        <div class="row g-4">
            <div class="col-lg-7">
                <div class="card shadow-sm">
                    <div class="card-header"><strong>Felder</strong></div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm align-middle">
                                <thead>
                                    <tr>
                                        <th>Feld</th>
                                        <th>Aktuelle Feldfrucht</th>
                                        <th>Geplante Feldfrucht</th>
                                        <th>Arbeit</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($fields as $field): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($field['field_name'] ?? '') ?></td>
                                        <td><?= htmlspecialchars($field['current_crop'] ?? '') ?></td>
                                        <td><?= htmlspecialchars($field['planned_crop'] ?? '') ?></td>
                                        <td><?= htmlspecialchars($field['pending_work'] ?? '') ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                <?php if (empty($fields)): ?>
                                    <tr><td colspan="4" class="text-muted text-center">Keine Felder vorhanden.</td></tr>
                                <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="card shadow-sm">
                    <div class="card-header"><strong>Mitglieder</strong></div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <?php foreach ($members as $member): ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span><?= htmlspecialchars($member['username'] ?? '') ?></span>
                                    <span class="badge text-bg-secondary"><?= htmlspecialchars($member['farm_role'] ?? 'member') ?></span>
                                </li>
                            <?php endforeach; ?>
                            <?php if (empty($members)): ?>
                                <li class="list-group-item text-muted">Keine Mitglieder vorhanden.</li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="alert alert-warning">Du bist aktuell keinem Hof zugeordnet.</div>
    <?php endif; ?>
</div>
