<section class="container py-5">
  <h1 class="mb-4">Member</h1>
  <div class="card sh-card">
    <div class="table-responsive">
      <table class="table table-dark table-hover align-middle mb-0">
        <thead><tr><th>Name</th><th>E-Mail</th><th>Rolle</th><th>Verifiziert</th></tr></thead>
        <tbody>
          <?php foreach ($members as $member): ?>
          <tr>
            <td><?= e($member['username']) ?></td>
            <td><?= e($member['email']) ?></td>
            <td><?= e($member['role_name'] ?? '—') ?></td>
            <td><?= $member['is_verified'] ? 'Ja' : 'Nein' ?></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</section>
