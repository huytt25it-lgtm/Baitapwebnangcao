<?php
require __DIR__ . '/../db.php';
$news = db()->query('SELECT title, link, description FROM news ORDER BY id')->fetchAll();
page('Bài 13 · Nguồn tin RSS', 'PHP lấy dữ liệu MySQL và xuất RSS 2.0 dưới dạng XML.');
?>
<p><a href="rss.php">Mở nguồn tin RSS</a></p>
<ul><?php foreach ($news as $item): ?>
    <li><a href="../<?= e($item['link']) ?>"><?= e($item['title']) ?></a> · <?= e($item['description']) ?></li>
<?php endforeach ?></ul>
<?php endPage();

