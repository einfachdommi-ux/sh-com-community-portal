<div class="container py-5">
    <div class="card shadow-sm border-0">
        <div class="card-body p-4 p-lg-5">
            <div class="row g-4 align-items-start">
                <div class="col-md-3 text-center">
                    <img
                        src="<?= htmlspecialchars($user['avatar'] ?: '/assets/img/default-avatar.png') ?>"
                        alt="Avatar"
                        class="img-fluid rounded-circle border"
                        style="width: 180px; height: 180px; object-fit: cover;"
                    >
                </div>
                <div class="col-md-9">
                    <h1 class="h2 mb-1"><?= htmlspecialchars($user['username'] ?? '') ?></h1>
                    <?php if (!empty($user['location'])): ?>
                        <p class="text-muted mb-2"><?= htmlspecialchars($user['location']) ?></p>
                    <?php endif; ?>

                    <?php if (!empty($user['bio'])): ?>
                        <p class="mb-3"><?= nl2br(htmlspecialchars($user['bio'])) ?></p>
                    <?php endif; ?>

                    <div class="d-flex flex-wrap gap-2">
                        <?php if (!empty($user['discord'])): ?>
                            <span class="badge text-bg-primary">Discord: <?= htmlspecialchars($user['discord']) ?></span>
                        <?php endif; ?>
                        <?php if (!empty($user['website'])): ?>
                            <a class="btn btn-outline-secondary btn-sm" href="<?= htmlspecialchars($user['website']) ?>" target="_blank" rel="noopener">Website</a>
                        <?php endif; ?>
                        <?php if (!empty($user['show_email_public']) && !empty($user['email'])): ?>
                            <a class="btn btn-outline-secondary btn-sm" href="mailto:<?= htmlspecialchars($user['email']) ?>">E-Mail</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
