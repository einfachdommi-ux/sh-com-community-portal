<h1 class="mb-4">Audit Log</h1>
<div class="card sh-card"><div class="card-body"><div class="table-responsive"><table class="table table-dark table-sm">
  <thead><tr><th>Zeit</th><th>User</th><th>Modul</th><th>Aktion</th><th>Entity</th><th>IP</th></tr></thead>
  <tbody><?php foreach($items as $item): ?><tr><td><?= e($item['created_at']) ?></td><td><?= e($item['username'] ?? 'System') ?></td><td><?= e($item['module']) ?></td><td><?= e($item['action']) ?></td><td><?= e(($item['entity_type'] ?? '') . '#' . ($item['entity_id'] ?? '')) ?></td><td><?= e($item['ip_address']) ?></td></tr><?php endforeach; ?></tbody>
</table></div></div></div>
