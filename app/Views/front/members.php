<section class="container py-5">
  <h1 class="mb-4">Member</h1>
  <div class="card sh-card">
    <div class="table-responsive">
      <table class="table table-dark table-hover align-middle mb-0">
        <thead><tr><th>Name</th><th>Rolle</th></tr></thead>
        <tbody>
          <?php foreach ($members as $member): ?>
          <tr>
            <td><?= e($member['username']) ?></td>
            <td><?= htmlspecialchars($member['role_names'] ?? $member['role_name'] ?? 'Gast') ?></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</section>
