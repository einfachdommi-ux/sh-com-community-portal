<h1 class="mb-4">Mail Log</h1>
<div class="card sh-card"><div class="card-body"><div class="table-responsive"><table class="table table-dark table-sm">
  <thead><tr><th>Zeit</th><th>Typ</th><th>Empfänger</th><th>Betreff</th><th>Status</th><th>Fehler</th></tr></thead>
  <tbody><?php foreach($items as $item): ?><tr><td><?= e($item['created_at']) ?></td><td><?= e($item['mail_type']) ?></td><td><?= e($item['recipient_email']) ?></td><td><?= e($item['subject']) ?></td><td><?= e($item['status']) ?></td><td><?= e($item['error_message']) ?></td></tr><?php endforeach; ?></tbody>
</table></div></div></div>
