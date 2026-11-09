<?php
require __DIR__ . '/../db.php';
openSession();
$pdo = db();
$poll = $pdo->query('SELECT * FROM binhchon WHERE idBC = 1')->fetch();
$options = $pdo->query('SELECT * FROM phuongan WHERE idBC = 1 ORDER BY idPA')->fetchAll();
$error = '';

if (posted()) {
    checkCsrf();
    $option = integer(field('idPA', $_POST), 1, 4294967295);
    if (!$poll || $option === false || !in_array($option, array_column($options, 'idPA'), true)) {
        $error = 'Hãy chọn một phương án hợp lệ.';
    } else {
        try {
            $pdo->beginTransaction();
            $stmt = $pdo->prepare('UPDATE phuongan SET SoLanChon = SoLanChon + 1 WHERE idBC = 1 AND idPA = ?');
            $stmt->execute([$option]);
            if ($stmt->rowCount() !== 1) {
                throw new RuntimeException('Phương án không còn tồn tại.');
            }
            $updated = $pdo->exec('UPDATE binhchon SET SoLanChon = SoLanChon + 1 WHERE idBC = 1');
            if ($updated !== 1) {
                throw new RuntimeException('Câu hỏi không còn tồn tại.');
            }
            $pdo->commit();
            $_SESSION['poll_notice'] = 'Đã ghi nhận bình chọn.';
            redirect('?ketqua=1');
        } catch (RuntimeException $exception) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            $error = 'Chưa ghi nhận được bình chọn. Hãy thử lại.';
        }
    }
}

page('Bài 15 · Bình chọn', 'Chọn một phương án để xem số lượt và tỷ lệ bình chọn.');
message($_SESSION['poll_notice'] ?? '');
unset($_SESSION['poll_notice']);
message($error, true);
if (!$poll || !$options):
    message('Chưa có dữ liệu bình chọn.', true);
elseif (field('ketqua', $_GET) === '1'):
    $total = array_sum(array_column($options, 'SoLanChon'));
    ?>
    <h2><?= e($poll['MoTa']) ?></h2>
    <div class="table"><table>
        <thead><tr><th>Phương án</th><th>Số lượt</th><th>Tỷ lệ</th></tr></thead>
        <tbody>
        <?php foreach ($options as $row):
            $percent = $total > 0 ? round(100 * $row['SoLanChon'] / $total, 2) : 0;
            ?>
            <tr><td><?= e($row['MoTa']) ?></td><td><?= e($row['SoLanChon']) ?></td>
                <td><progress aria-label="<?= e($row['MoTa']) ?>" max="100" value="<?= e($percent) ?>"></progress> <?= e($percent) ?>%</td></tr>
        <?php endforeach ?>
        </tbody>
    </table></div>
    <p>Tổng số lượt: <?= e($total) ?></p>
    <p><a href="./">Quay lại bình chọn</a></p>
<?php else: ?>
    <h2><?= e($poll['MoTa']) ?></h2>
    <form method="post" action="./">
        <?php csrfField() ?>
        <?php foreach ($options as $index => $row): ?>
            <label><input type="radio" name="idPA" value="<?= e($row['idPA']) ?>" <?= $index === 0 ? 'checked' : '' ?> required>
                <?= e($row['MoTa']) ?></label>
        <?php endforeach ?>
        <button>Bình chọn</button>
        <a href="?ketqua=1">Xem kết quả</a>
    </form>
<?php endif; endPage();

