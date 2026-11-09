<?php
require __DIR__ . '/../db.php';
openSession();
$pdo = db();
$id = integer(field('id', $_GET), 1, 4294967295);
$product = false;
$error = '';
if ($id !== false) {
    $stmt = $pdo->prepare('SELECT * FROM products WHERE id = ?');
    $stmt->execute([$id]);
    $product = $stmt->fetch();
    if (!$product) {
        http_response_code(404);
        $error = 'Không tìm thấy sản phẩm.';
    }
}

if ($product && posted()) {
    checkCsrf();
    $rate = integer(field('rate', $_POST), 1, 5);
    if ($rate === false) {
        $error = 'Hãy chọn điểm từ 1 đến 5.';
    } else {
        // Tên cột lấy từ danh sách giới hạn 1–5. Mã sản phẩm được truyền qua tham số.
        $column = 'rate_' . $rate;
        $stmt = $pdo->prepare("INSERT INTO rating_info (product_id, $column) VALUES (?, 1)
            ON DUPLICATE KEY UPDATE $column = $column + 1");
        $stmt->execute([$id]);
        $_SESSION['rating_notice'] = 'Đã ghi nhận đánh giá.';
        redirect('?id=' . $id);
    }
}

page('Bài 12 · Đánh giá sản phẩm', 'Chọn một sản phẩm rồi gửi đánh giá từ 1 đến 5 sao.');
message($_SESSION['rating_notice'] ?? '');
unset($_SESSION['rating_notice']);
message($error, true);
if (!$product):
    $products = $pdo->query('SELECT * FROM products ORDER BY id')->fetchAll();
    ?>
    <div class="grid">
        <?php foreach ($products as $item): ?>
            <a class="card" href="?id=<?= e($item['id']) ?>">
                <img src="<?= e($item['img_url']) ?>" alt="">
                <strong><?= e($item['title']) ?></strong>
            </a>
        <?php endforeach ?>
    </div>
<?php else:
    $stmt = $pdo->prepare('SELECT * FROM rating_info WHERE product_id = ?');
    $stmt->execute([$id]);
    $rating = $stmt->fetch() ?: [];
    ?>
    <p><a href="./">← Danh sách sản phẩm</a></p>
    <h2><?= e($product['title']) ?></h2>
    <img src="<?= e($product['img_url']) ?>" width="320" alt="Minh họa sản phẩm">
    <ul>
        <?php for ($i = 5; $i >= 1; $i--): ?><li><?= $i ?> sao · <?= e($rating['rate_' . $i] ?? 0) ?> lượt</li><?php endfor ?>
    </ul>
    <form method="post">
        <?php csrfField() ?>
        <label for="rate">Mức đánh giá</label>
        <select name="rate" id="rate" required>
            <option value="">Chọn số sao</option>
            <?php for ($i = 1; $i <= 5; $i++): ?><option value="<?= $i ?>"><?= $i ?> sao</option><?php endfor ?>
        </select>
        <button>Gửi đánh giá</button>
    </form>
<?php endif; endPage();

