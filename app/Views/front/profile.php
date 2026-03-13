<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Mein Profil</h1>
        <a href="/profile/<?= urlencode($user['username'] ?? '') ?>" class="btn btn-outline-secondary">Öffentliches Profil ansehen</a>
    </div>

    <?php if (!empty($_SESSION['flash_success'])): ?>
        <div class="alert alert-success"><?= htmlspecialchars($_SESSION['flash_success']) ?></div>
        <?php unset($_SESSION['flash_success']); ?>
    <?php endif; ?>

    <?php if (!empty($_SESSION['flash_error'])): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['flash_error']) ?></div>
        <?php unset($_SESSION['flash_error']); ?>
    <?php endif; ?>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header"><strong>Einstellungen</strong></div>
                <div class="card-body">
                    <form method="post" action="/profile/update" enctype="multipart/form-data" class="row g-3">
                        <div class="col-md-4 text-center">
                            <img
                                src="<?= htmlspecialchars($user['avatar'] ?: '/assets/img/default-avatar.png') ?>"
                                alt="Avatar"
                                id="avatarPreview"
                                class="img-fluid rounded-circle border mb-3"
                                style="width: 160px; height: 160px; object-fit: cover;"
                            >
                            <input type="file" name="avatar_file" id="avatarInput" class="form-control" accept=".jpg,.jpeg,.png,.webp">
                            <div class="form-text">JPG, PNG oder WEBP</div>
                        </div>

                        <div class="col-md-8">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Vorname</label>
                                    <input type="text" name="first_name" class="form-control" value="<?= htmlspecialchars($user['first_name'] ?? '') ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Nachname</label>
                                    <input type="text" name="last_name" class="form-control" value="<?= htmlspecialchars($user['last_name'] ?? '') ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Benutzername</label>
                                    <input type="text" name="username" class="form-control" required value="<?= htmlspecialchars($user['username'] ?? '') ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">E-Mail</label>
                                    <input type="email" name="email" class="form-control" required value="<?= htmlspecialchars($user['email'] ?? '') ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Discord</label>
                                    <input type="text" name="discord" class="form-control" value="<?= htmlspecialchars($user['discord'] ?? '') ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Website</label>
                                    <input type="url" name="website" class="form-control" value="<?= htmlspecialchars($user['website'] ?? '') ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Standort</label>
                                    <input type="text" name="location" class="form-control" value="<?= htmlspecialchars($user['location'] ?? '') ?>">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Bio</label>
                                    <textarea name="bio" class="form-control" rows="5"><?= htmlspecialchars($user['bio'] ?? '') ?></textarea>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check mt-2">
                                        <input class="form-check-input" type="checkbox" value="1" id="is_public_profile" name="is_public_profile" <?= !empty($user['is_public_profile']) ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="is_public_profile">Öffentliches Profil aktivieren</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check mt-2">
                                        <input class="form-check-input" type="checkbox" value="1" id="show_email_public" name="show_email_public" <?= !empty($user['show_email_public']) ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="show_email_public">E-Mail öffentlich anzeigen</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">Einstellungen speichern</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header"><strong>Sicherheitsoptionen</strong></div>
                <div class="card-body">
                    <form method="post" action="/profile/security" class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Aktuelles Passwort</label>
                            <input type="password" name="current_password" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Neues Passwort</label>
                            <input type="password" name="new_password" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Neues Passwort wiederholen</label>
                            <input type="password" name="confirm_password" class="form-control" required>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-outline-danger">Sicherheitsoptionen speichern</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-header"><strong>Account</strong></div>
                <div class="card-body">
                    <p><strong>Benutzer:</strong> <?= htmlspecialchars($user['username'] ?? '') ?></p>
                    <p><strong>Status:</strong>
                        <?php if (!empty($user['is_verified'])): ?>
                            <span class="badge text-bg-success">Verifiziert</span>
                        <?php else: ?>
                            <span class="badge text-bg-warning">Nicht verifiziert</span>
                        <?php endif; ?>
                    </p>
                    <p><strong>Öffentlich:</strong>
                        <?= !empty($user['is_public_profile']) ? '<span class="badge text-bg-primary">Ja</span>' : '<span class="badge text-bg-secondary">Nein</span>' ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="/assets/js/profile-avatar-preview.js"></script>
