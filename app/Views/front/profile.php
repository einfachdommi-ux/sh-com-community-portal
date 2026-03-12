<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-xl-10">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h2 mb-1">Mein Profil</h1>
                    <p class="text-muted mb-0">Verwalte hier deine Profildaten und dein Passwort.</p>
                </div>
                <a href="/" class="btn btn-outline-secondary">Zur Startseite</a>
            </div>

            <?php if ($msg = flash('success')): ?>
                <div class="alert alert-success"><?= e($msg) ?></div>
            <?php endif; ?>

            <?php if ($msg = flash('error')): ?>
                <div class="alert alert-danger"><?= e($msg) ?></div>
            <?php endif; ?>

            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="card shadow-sm border-0">
                        <div class="card-body p-4">
                            <h2 class="h4 mb-3">Profildaten</h2>
                            <form method="post" action="/profile/update" class="row g-3">
                                <input type="hidden" name="_csrf" value="<?= e($_SESSION['_csrf'] ?? '') ?>">

                                <div class="col-md-6">
                                    <label class="form-label">Benutzername</label>
                                    <input type="text" name="username" class="form-control" value="<?= e($user['username'] ?? '') ?>" required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">E-Mail</label>
                                    <input type="email" name="email" class="form-control" value="<?= e($user['email'] ?? '') ?>" required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Vorname</label>
                                    <input type="text" name="first_name" class="form-control" value="<?= e($user['first_name'] ?? '') ?>">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Nachname</label>
                                    <input type="text" name="last_name" class="form-control" value="<?= e($user['last_name'] ?? '') ?>">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Discord</label>
                                    <input type="text" name="discord" class="form-control" value="<?= e($user['discord'] ?? '') ?>" placeholder="z. B. shcom_user">
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Bio</label>
                                    <textarea name="bio" rows="6" class="form-control" placeholder="Erzähl etwas über dich."><?= e($user['bio'] ?? '') ?></textarea>
                                </div>

                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary">Profil speichern</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-body p-4">
                            <h2 class="h4 mb-3">Account</h2>
                            <p class="mb-2"><strong>Status:</strong>
                                <?php if (!empty($user['is_verified'])): ?>
                                    <span class="badge text-bg-success">Verifiziert</span>
                                <?php else: ?>
                                    <span class="badge text-bg-warning">Nicht verifiziert</span>
                                <?php endif; ?>
                            </p>
                            <p class="mb-2"><strong>Benutzer-ID:</strong> <?= (int) ($user['id'] ?? 0) ?></p>
                            <p class="mb-0"><strong>Letzter Login:</strong> <?= e($user['last_login_at'] ?? '—') ?></p>
                        </div>
                    </div>

                    <div class="card shadow-sm border-0">
                        <div class="card-body p-4">
                            <h2 class="h4 mb-3">Passwort ändern</h2>
                            <form method="post" action="/profile/password" class="row g-3">
                                <input type="hidden" name="_csrf" value="<?= e($_SESSION['_csrf'] ?? '') ?>">

                                <div class="col-12">
                                    <label class="form-label">Aktuelles Passwort</label>
                                    <input type="password" name="current_password" class="form-control" required>
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Neues Passwort</label>
                                    <input type="password" name="new_password" class="form-control" required>
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Neues Passwort wiederholen</label>
                                    <input type="password" name="confirm_password" class="form-control" required>
                                </div>

                                <div class="col-12">
                                    <button type="submit" class="btn btn-outline-primary">Passwort ändern</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
