<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="mb-0">Mitglieder</h1>
            <p class="text-muted mb-0">Unsere Community auf einen Blick</p>
        </div>
    </div>

    <?php if (empty($members)): ?>
        <div class="alert alert-info">Aktuell sind keine Mitglieder vorhanden.</div>
    <?php else: ?>
        <div class="row g-4">
            <?php foreach ($members as $member): ?>
                <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
                    <div class="card member-card h-100 shadow-sm border-0 text-center">
                        <div class="card-body d-flex flex-column align-items-center">
                            <img
                                src="<?= htmlspecialchars($member['avatar'] ?: '/assets/img/default-avatar.png') ?>"
                                alt="Avatar von <?= htmlspecialchars($member['username'] ?? 'Mitglied') ?>"
                                class="rounded-circle border mb-3"
                                style="width: 110px; height: 110px; object-fit: cover;"
                            >

                            <h5 class="card-title mb-1">
                                <?= htmlspecialchars($member['username'] ?? 'Unbekannt') ?>
                            </h5>

                            <?php if (!empty($member['role_names']) || !empty($member['role_name'])): ?>
                                <div class="mb-3">
                                    <span class="badge text-bg-secondary">
                                        <?= htmlspecialchars($member['role_names'] ?? $member['role_name']) ?>
                                    </span>
                                </div>
                            <?php endif; ?>

                            <?php if (!empty($member['bio'])): ?>
                                <p class="card-text text-muted small mb-3">
                                    <?= htmlspecialchars(mb_strimwidth((string)$member['bio'], 0, 120, '...')) ?>
                                </p>
                            <?php else: ?>
                                <p class="card-text text-muted small mb-3">
                                    Community-Mitglied
                                </p>
                            <?php endif; ?>

                            <div class="mt-auto w-100">
                                <a href="/profile/<?= urlencode($member['username'] ?? '') ?>" class="btn btn-outline-primary btn-sm w-100">
                                    Profil ansehen
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>