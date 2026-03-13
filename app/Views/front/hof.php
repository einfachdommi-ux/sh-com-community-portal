<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Mein Hof</h1>
        <a href="/hof/dashboard" class="btn btn-primary">Dashboard</a>
    </div>

    <?php if (!empty($_SESSION['flash_success'])): ?>
        <div class="alert alert-success"><?= htmlspecialchars($_SESSION['flash_success']) ?></div>
        <?php unset($_SESSION['flash_success']); ?>
    <?php endif; ?>
    <?php if (!empty($_SESSION['flash_error'])): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['flash_error']) ?></div>
        <?php unset($_SESSION['flash_error']); ?>
    <?php endif; ?>

    <?php if (!empty($_SESSION['farm_invite_link'])): ?>
        <div class="alert alert-info">Einladungslink: <code><?= htmlspecialchars($_SESSION['farm_invite_link']) ?></code></div>
        <?php unset($_SESSION['farm_invite_link']); ?>
    <?php endif; ?>

    <?php if (!empty($farm)): ?>
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h2 class="h5"><?= htmlspecialchars($farm['name'] ?? 'Hof') ?></h2>
                <p class="text-muted mb-0">Verwalte Mitglieder, Einladungen und das Hof-Passwort.</p>
            </div>
        </div>

        <?php if (!empty($isOwner)): ?>
            <div class="row g-4 mb-4">
                <div class="col-lg-6">
                    <div class="card shadow-sm">
                        <div class="card-header"><strong>Einladung erstellen</strong></div>
                        <div class="card-body">
                            <form method="post" action="/hof/invite/create">
                                <button class="btn btn-primary">Einladungslink erzeugen</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card shadow-sm">
                        <div class="card-header"><strong>Hof-Passwort</strong></div>
                        <div class="card-body">
                            <form method="post" action="/hof/password" class="row g-3">
                                <div class="col-12">
                                    <input type="text" name="password" class="form-control" placeholder="Neues Hof-Passwort">
                                </div>
                                <div class="col-12">
                                    <button class="btn btn-outline-primary">Speichern</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <div class="card shadow-sm mb-4">
            <div class="card-header"><strong>Mitglieder</strong></div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm align-middle">
                        <thead>
                            <tr>
                                <th>Benutzername</th>
                                <th>E-Mail</th>
                                <th>Rolle</th>
                                <?php if (!empty($isOwner)): ?><th>Aktion</th><?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($members as $member): ?>
                            <tr>
                                <td><?= htmlspecialchars($member['username'] ?? '') ?></td>
                                <td><?= htmlspecialchars($member['email'] ?? '') ?></td>
                                <td><?= htmlspecialchars($member['farm_role'] ?? 'member') ?></td>
                                <?php if (!empty($isOwner)): ?>
                                    <td>
                                        <?php if ((int)$member['id'] !== (int)$user['id']): ?>
                                            <form method="post" action="/hof/member/remove" class="d-inline">
                                                <input type="hidden" name="member_id" value="<?= (int)$member['id'] ?>">
                                                <button class="btn btn-sm btn-danger" onclick="return confirm('Mitglied wirklich entfernen?')">Entfernen</button>
                                            </form>
                                        <?php endif; ?>
                                    </td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($members)): ?>
                            <tr><td colspan="<?= !empty($isOwner) ? 4 : 3 ?>" class="text-center text-muted">Keine Mitglieder vorhanden.</td></tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header"><strong>Hof verlassen</strong></div>
            <div class="card-body">
                <a href="/hof/leave-confirm" class="btn btn-outline-danger">Hof verlassen</a>
            </div>
        </div>
    <?php else: ?>
        <div class="card shadow-sm">
            <div class="card-header"><strong>Hof beitreten</strong></div>
            <div class="card-body">
                <form method="post" action="/hof/join" class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Hof</label>
                        <select name="farm_id" class="form-select">
                            <option value="">Bitte wählen</option>
                            <?php foreach ((new \App\Models\Farm())->all() as $farmItem): ?>
                                <option value="<?= (int)$farmItem['id'] ?>"><?= htmlspecialchars($farmItem['name'] ?? ('Hof #' . $farmItem['id'])) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Hof-Passwort</label>
                        <input type="text" name="farm_password" class="form-control">
                    </div>
                    <div class="col-12">
                        <button class="btn btn-primary">Beitreten</button>
                    </div>
                </form>
            </div>
        </div>
    <?php endif; ?>
</div>
