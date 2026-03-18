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
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="row g-4 align-items-start">
                    <div class="col-md-3 text-center">
                        <img
                            src="<?= htmlspecialchars($profileUser['avatar'] ?: '/assets/img/default-avatar.png') ?>"
                            alt="Avatar"
                            class="img-fluid rounded-circle border"
                            style="width: 180px; height: 180px; object-fit: cover;"
                        >
                    </div>

                    <div class="col-md-9">
                        <h2 class="h4 mb-3">
                            <?= htmlspecialchars(trim(($profileUser['first_name'] ?? '') . ' ' . ($profileUser['last_name'] ?? '')) ?: ($profileUser['username'] ?? 'Profil')) ?>
                        </h2>

                        <p><strong>Benutzername:</strong> <?= htmlspecialchars($profileUser['username'] ?? '') ?></p>

                        <?php if (!empty($profileUser['location'])): ?>
                            <p><strong>Standort:</strong> <?= htmlspecialchars($profileUser['location']) ?></p>
                        <?php endif; ?>

                        <?php if (!empty($profileUser['discord'])): ?>
                            <p><strong>Discord:</strong> <?= htmlspecialchars($profileUser['discord']) ?></p>
                        <?php endif; ?>

                        <?php if (!empty($profileUser['website'])): ?>
                            <p><strong>Website:</strong> <a href="<?= htmlspecialchars($profileUser['website']) ?>" target="_blank" rel="noopener noreferrer"><?= htmlspecialchars($profileUser['website']) ?></a></p>
                        <?php endif; ?>

                        <?php if (!empty($profileUser['show_email_public']) && !empty($profileUser['email'])): ?>
                            <p><strong>E-Mail:</strong> <?= htmlspecialchars($profileUser['email']) ?></p>
                        <?php endif; ?>

                        <?php if (!empty($profileUser['bio'])): ?>
                            <hr>
                            <h3 class="h6">Bio</h3>
                            <p class="mb-0"><?= nl2br(htmlspecialchars($profileUser['bio'])) ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
