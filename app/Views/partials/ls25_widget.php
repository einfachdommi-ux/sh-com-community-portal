<?php if (!empty($ls25Permission) && !empty($ls25)): ?>
<div class="card shadow-sm mb-4" id="ls25-widget">
    <div class="card-header d-flex justify-content-between align-items-center">
        <strong>🚜 LS25 Server</strong>
        <span class="badge <?= !empty($ls25['online']) ? 'text-bg-success' : 'text-bg-danger' ?>" id="ls25-status-badge">
            <?= !empty($ls25['online']) ? 'Online' : 'Offline' ?>
        </span>
    </div>
    <div class="card-body">
        <p class="mb-2"><strong>Name:</strong> <span id="ls25-name"><?= htmlspecialchars($ls25['name'] ?? 'LS25 Server') ?></span></p>
        <p class="mb-2"><strong>Map:</strong> <span id="ls25-map"><?= htmlspecialchars($ls25['map'] ?? 'Deutsches-Eck 4Fach Multi') ?></span></p>
        <p class="mb-2"><strong>Spieler:</strong> <span id="ls25-players"><?= (int)($ls25['players'] ?? 0) ?></span> / <span id="ls25-maxPlayers"><?= (int)($ls25['maxPlayers'] ?? 0) ?></span></p>
        <p class="mb-2"><strong>Passwort:</strong> <span id="ls25-password"><?= !empty($ls25['hasPassword']) ? 'Ja' : 'Ja' ?></span></p>
        <p class="mb-0"><strong>Mods:</strong> <span id="ls25-mods"><?= !empty($ls25['mods']) ? 'Aktiv' : 'Keine' ?></span></p>
    </div>
</div>

<script>
setInterval(function () {
    fetch('/api/ls25-status', { credentials: 'same-origin' })
        .then(function (res) {
            if (!res.ok) throw new Error('Status nicht erreichbar');
            return res.json();
        })
        .then(function (data) {
            if (!data) return;

            var badge = document.getElementById('ls25-status-badge');
            if (badge) {
                badge.textContent = data.online ? 'Online' : 'Offline';
                badge.className = 'badge ' + (data.online ? 'text-bg-success' : 'text-bg-danger');
            }

            var setText = function (id, value) {
                var el = document.getElementById(id);
                if (el) el.textContent = value;
            };

            setText('ls25-name', data.name || 'LS25 Server');
            setText('ls25-map', data.map || '-');
            setText('ls25-players', data.players ?? 0);
            setText('ls25-maxPlayers', data.maxPlayers ?? 0);
            setText('ls25-password', data.hasPassword ? 'Ja' : 'Nein');
            setText('ls25-mods', data.mods ? 'Aktiv' : 'Keine');
        })
        .catch(function () {});
}, 30000);
</script>
<?php endif; ?>
