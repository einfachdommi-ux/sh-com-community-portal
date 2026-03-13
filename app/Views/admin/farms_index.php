<div class="container py-4">
    <h1 class="h3 mb-4">Farm Verwaltung</h1>
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($farms as $farm): ?>
                        <tr>
                            <td><?= (int)$farm['id'] ?></td>
                            <td><?= htmlspecialchars($farm['name'] ?? '') ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($farms)): ?>
                        <tr><td colspan="2" class="text-center text-muted">Keine Höfe vorhanden.</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
