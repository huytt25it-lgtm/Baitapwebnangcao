<?php
require __DIR__ . '/../db.php';
$pdo = db();
$perPage = 10;
$total = (int) $pdo->query('SELECT COUNT(*) FROM tintuc')->fetchColumn();
$totalPages = max(1, (int) ceil($total / $perPage));
$requested = integer(field('page', $_GET), 1, PHP_INT_MAX);
$current = min($requested === false ? 1 : $requested, $totalPages);
$offset = ($current - 1) * $perPage;
$stmt = $pdo->prepare('SELECT id, title FROM tintuc ORDER BY id LIMIT ? OFFSET ?');
$stmt->bindValue(1, $perPage, PDO::PARAM_INT);
$stmt->bindValue(2, $offset, PDO::PARAM_INT);
$stmt->execute();
page('Bài 11 · Phân trang tin tức', 'Mỗi trang lấy tối đa 10 tin bằng LIMIT và OFFSET.');
?>
<p>Trang <?= e($current) ?>/<?= e($totalPages) ?> · <?= e($total) ?> tin</p>
<ol start="<?= e($offset + 1) ?>">
    <?php foreach ($stmt as $row): ?><li><?= e($row['title']) ?></li><?php endforeach ?>
</ol>
<?php if ($total === 0): ?><p>Chưa có tin tức.</p><?php endif ?>
<nav class="paging" aria-label="Phân trang">
    <?php if ($current > 1): ?><a href="?page=<?= e($current - 1) ?>">‹ Trước</a><?php endif ?>
    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
        <a href="?page=<?= e($i) ?>" <?= $i === $current ? 'aria-current="page"' : '' ?>><?= e($i) ?></a>
    <?php endfor ?>
    <?php if ($current < $totalPages): ?><a href="?page=<?= e($current + 1) ?>">Sau ›</a><?php endif ?>
</nav>
<?php endPage();

