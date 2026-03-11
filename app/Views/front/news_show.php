<?php /** @var array $article */ ?>
<div class="container py-5">
    <article class="mx-auto" style="max-width: 900px;">
        <p class="text-muted mb-2">
            <?= htmlspecialchars((string)($article['author_name'] ?? 'Unbekannt')) ?> ·
            <?= htmlspecialchars(!empty($article['published_at']) ? (string)$article['published_at'] : (string)($article['created_at'] ?? '')) ?>
        </p>
        <h1 class="mb-4"><?= htmlspecialchars($article['title']) ?></h1>
        <?php if (!empty($article['featured_image'])): ?>
            <img src="<?= htmlspecialchars($article['featured_image']) ?>" alt="<?= htmlspecialchars($article['title']) ?>" class="img-fluid rounded shadow-sm mb-4">
        <?php endif; ?>
        <?php if (!empty($article['teaser'])): ?>
            <p class="lead"><?= htmlspecialchars($article['teaser']) ?></p>
        <?php endif; ?>
        <div class="content-area">
            <?= $article['content'] ?>
        </div>
    </article>
</div>
