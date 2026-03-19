<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Öffentliches Profil</h1>
        <?php if (\App\Core\Auth::check()): ?>
            <a href="/profile" class="btn btn-outline-secondary">Zurück zu meinem Profil</a>
        <?php else: ?>
            <a href="/" class="btn btn-outline-secondary">Zurück</a>
        <?php endif; ?>
    </div>

    <?php if (empty($profileUser) || empty($profileUser['is_public_profile'])): ?>
        <div class="alert alert-warning">Dieses Profil ist nicht öffentlich sichtbar.</div>
    <?php else: ?>
        <div class="row g-4">
            <div class="col-lg-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body text-center">
                        <img
                            src="<?= htmlspecialchars($profileUser['avatar'] ?: '/assets/img/default-avatar.png') ?>"
                            alt="Avatar"
                            class="img-fluid rounded-circle border mb-3"
                            style="width: 180px; height: 180px; object-fit: cover;"
                        >

                        <h2 class="h4 mb-1"><?= htmlspecialchars($profileUser['username'] ?? '') ?></h2>

                        <div class="mb-3">
                            <span class="badge text-bg-secondary">
                                <?= htmlspecialchars($profileUser['role_names'] ?? $profileUser['role_name'] ?? 'Mitglied') ?>
                            </span>
                        </div>

                        <?php if (!empty($profileUser['show_email_public']) && !empty($profileUser['email'])): ?>
                            <p class="mb-0"><strong>E-Mail:</strong><br><?= htmlspecialchars($profileUser['email']) ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <h3 class="h5 mb-3">Profilinformationen</h3>

                        <?php if (!empty($profileUser['location'])): ?>
                            <p><strong>Standort:</strong> <?= htmlspecialchars($profileUser['location']) ?></p>
                        <?php endif; ?>

                        <?php if (!empty($profileUser['website'])): ?>
                            <p><strong>Website:</strong> <a href="<?= htmlspecialchars($profileUser['website']) ?>" target="_blank" rel="noopener noreferrer"><?= htmlspecialchars($profileUser['website']) ?></a></p>
                        <?php endif; ?>

                        <?php if (!empty($profileUser['discord'])): ?>
                            <p><strong>Discord:</strong> <?= htmlspecialchars($profileUser['discord']) ?></p>
                        <?php endif; ?>

                        <hr>
                        <h3 class="h5 mb-3">Social Media</h3>

                        <?php if (!empty($profileUser['instagram'])): ?>
                            <p><strong>Instagram:</strong> <a href="<?= htmlspecialchars($profileUser['instagram']) ?>" target="_blank" rel="noopener noreferrer"><?= htmlspecialchars($profileUser['instagram']) ?></a></p>
                        <?php endif; ?>

                        <?php if (!empty($profileUser['facebook'])): ?>
                            <p><strong>Facebook:</strong> <a href="<?= htmlspecialchars($profileUser['facebook']) ?>" target="_blank" rel="noopener noreferrer"><?= htmlspecialchars($profileUser['facebook']) ?></a></p>
                        <?php endif; ?>

                        <?php if (!empty($profileUser['snapchat'])): ?>
                            <p><strong>Snapchat:</strong> <?= htmlspecialchars($profileUser['snapchat']) ?></p>
                        <?php endif; ?>

                        <?php if (!empty($profileUser['x_profile'])): ?>
                            <p><strong>X:</strong> <a href="<?= htmlspecialchars($profileUser['x_profile']) ?>" target="_blank" rel="noopener noreferrer"><?= htmlspecialchars($profileUser['x_profile']) ?></a></p>
                        <?php endif; ?>

                        <hr>
                        <h3 class="h5 mb-3">Gaming Plattformen</h3>

                        <?php if (!empty($profileUser['epic_games'])): ?>
                            <p><strong>EPIC Games:</strong> <?= htmlspecialchars($profileUser['epic_games']) ?></p>
                        <?php endif; ?>

                        <?php if (!empty($profileUser['steam'])): ?>
                            <p><strong>Steam:</strong> <?= htmlspecialchars($profileUser['steam']) ?></p>
                        <?php endif; ?>

                        <?php if (!empty($profileUser['ea_app'])): ?>
                            <p><strong>EA App:</strong> <?= htmlspecialchars($profileUser['ea_app']) ?></p>
                        <?php endif; ?>

                        <?php if (!empty($profileUser['twitch'])): ?>
                            <p><strong>Twitch:</strong> <a href="<?= htmlspecialchars($profileUser['twitch']) ?>" target="_blank" rel="noopener noreferrer"><?= htmlspecialchars($profileUser['twitch']) ?></a></p>
                        <?php endif; ?>

                        <?php if (!empty($profileUser['youtube'])): ?>
                            <p><strong>YouTube:</strong> <a href="<?= htmlspecialchars($profileUser['youtube']) ?>" target="_blank" rel="noopener noreferrer"><?= htmlspecialchars($profileUser['youtube']) ?></a></p>
                        <?php endif; ?>

                        <?php if (!empty($profileUser['bio'])): ?>
                            <hr>
                            <h3 class="h5 mb-3">Bio</h3>
                            <p class="mb-0"><?= nl2br(htmlspecialchars($profileUser['bio'])) ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
