<section class="container py-5">
  <h1 class="mb-4">Team</h1>
  <div class="row g-4">
    <?php foreach ($team as $member): ?>
      <div class="col-md-6 col-xl-4">
        <div class="card sh-card h-100">
          <div class="card-body">
            <?php if (!empty($member['image_path'])): ?>
              <img src="<?= e(base_url($member['image_path'])) ?>" alt="<?= e($member['display_name']) ?>" class="mb-3" style="width:84px;height:84px;object-fit:cover;border-radius:18px;">
            <?php endif; ?>
            <h2 class="h5"><?= e($member['display_name']) ?></h2>
            <div class="text-danger fw-semibold mb-2"><?= e($member['team_role']) ?></div>
            <p><?= e($member['bio']) ?></p>
            <?php $links = json_decode($member['social_links'] ?? '[]', true) ?: []; ?>
            <div class="small text-muted">
              <?php foreach ($links as $key => $value): if ($value): ?><div><?= e(strtoupper($key)) ?>: <?= e($value) ?></div><?php endif; endforeach; ?>
            </div>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</section>
