<?php /** @var array $page */ ?>
<div class="container py-5">
    <article class="mx-auto" style="max-width: 900px;">
        <h1 class="mb-4"><?= htmlspecialchars($page['title']) ?></h1>
        <div class="content-area">
            <?= $page['content'] ?>
        </div>
    </article>
</div>
