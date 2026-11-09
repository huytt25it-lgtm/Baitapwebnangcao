<?php
require __DIR__ . '/../common.php';
openSession();
header('Cache-Control: no-store');
$message = '';
$ok = false;
if (posted()) {
    checkCsrf();
    $previousId = field('challenge', $_POST);
    $expected = $_SESSION['captcha'][$previousId] ?? null;
    unset($_SESSION['captcha'][$previousId]);
    $ok = is_string($expected) && hash_equals($expected, field('input', $_POST));
    $message = $ok ? 'CAPTCHA chính xác.' : 'CAPTCHA sai hoặc đã hết hạn. Hãy nhập mã mới.';
}

$id = bin2hex(random_bytes(12));
$alphabet = '23456789ABCDEFGHJKLMNPQRSTUVWXYZabcdefghjkmnpqrstuvwxyz';
$code = '';
for ($i = 0; $i < 6; $i++) {
    $code .= $alphabet[random_int(0, strlen($alphabet) - 1)];
}
$_SESSION['captcha'][$id] = $code;
$_SESSION['captcha'] = array_slice($_SESSION['captcha'], -8, null, true);

page('Bài 14 · Kiểm tra CAPTCHA', 'Mã gồm 6 ký tự, phân biệt chữ hoa và chữ thường.');
message($message, !$ok);
?>
<form method="post">
    <?php csrfField() ?>
    <input type="hidden" name="challenge" value="<?= e($id) ?>">
    <img src="captcha.php?id=<?= e($id) ?>" width="180" height="56" alt="Ảnh CAPTCHA gồm 6 ký tự">
    <p><a href="./">Đổi mã mới</a></p>
    <?php input('Mã trong hình', 'input') ?>
    <button>Kiểm tra</button>
</form>
<?php endPage();

