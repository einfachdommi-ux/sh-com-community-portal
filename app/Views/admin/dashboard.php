<h1 class="mb-4">Dashboard</h1>
<div class="row g-4 mb-4">
  <?php foreach ($stats as $label => $value): ?>
    <div class="col-md-6 col-xl-3">
      <div class="card sh-card h-100"><div class="card-body"><div class="text-muted text-uppercase small"><?= e($label) ?></div><div class="display-6 fw-bold"><?= e((string)$value) ?></div></div></div>
    </div>
  <?php endforeach; ?>
</div>
<div class="row g-4">
  <div class="col-xl-6">
    <div class="card sh-card"><div class="card-body">
      <h2 class="h5 mb-3">Letzte Audit-Aktivitäten</h2>
      <div class="table-responsive"><table class="table table-sm table-dark align-middle">
        <thead><tr><th>User</th><th>Modul</th><th>Aktion</th><th>Zeit</th></tr></thead>
        <tbody><?php foreach ($auditLogs as $log): ?><tr><td><?= e($log['username'] ?? 'System') ?></td><td><?= e($log['module']) ?></td><td><?= e($log['action']) ?></td><td><?= e($log['created_at']) ?></td></tr><?php endforeach; ?></tbody>
      </table></div>
    </div></div>
  </div>
  <div class="col-xl-6">
    <div class="card sh-card"><div class="card-body">
      <h2 class="h5 mb-3">Letzte Mail-Aktivitäten</h2>
      <div class="table-responsive"><table class="table table-sm table-dark align-middle">
        <thead><tr><th>Typ</th><th>Empfänger</th><th>Status</th><th>Zeit</th></tr></thead>
        <tbody><?php foreach ($mailLogs as $log): ?><tr><td><?= e($log['mail_type']) ?></td><td><?= e($log['recipient_email']) ?></td><td><?= e($log['status']) ?></td><td><?= e($log['created_at']) ?></td></tr><?php endforeach; ?></tbody>
      </table></div>
    </div></div>
  </div>
</div>
