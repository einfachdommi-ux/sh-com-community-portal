<?php /** @var array $news */ ?>
<div class="container py-5">
    <h1 class="mb-4">News</h1>
    <div class="row g-4">
        <?php foreach ($news as $item): ?>
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm">
                    <?php if (!empty($item['featured_image'])): ?>
                        <img src="<?= htmlspecialchars($item['featured_image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($item['title']) ?>" style="height:220px;object-fit:cover;">
                    <?php endif; ?>
                    <div class="card-body d-flex flex-column">
                        <h2 class="h5"><?= htmlspecialchars($item['title']) ?></h2>
                        <p class="text-muted small mb-2">
                            <?= htmlspecialchars((string)($item['author_name'] ?? 'Unbekannt')) ?> ·
                            <?= htmlspecialchars(!empty($item['published_at']) ? (string)$item['published_at'] : (string)($item['created_at'] ?? '')) ?>
                        </p>
                        <?php if (!empty($item['teaser'])): ?>
                            <p class="flex-grow-1"><?= htmlspecialchars($item['teaser']) ?></p>
                        <?php endif; ?>
                        <a href="/news/<?= urlencode($item['slug']) ?>" class="btn btn-primary mt-auto">Details</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
