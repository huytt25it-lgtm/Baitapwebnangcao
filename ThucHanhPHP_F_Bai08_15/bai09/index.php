<?php
require __DIR__ . '/../common.php';
require __DIR__ . '/../upload.php';
$filename = $error = '';
if (posted()) {
    try {
        $filename = saveImage($_FILES['ufile'] ?? [], __DIR__ . '/uploads');
    } catch (RuntimeException $exception) {
        $error = $exception->getMessage();
    }
}
page('Bài 9 · Tải ảnh lên', 'Biểu mẫu multipart/form-data gửi ảnh JPG hoặc GIF, tối đa 5.000.000 byte và trong giới hạn của XAMPP.');
message($error, true);
?>
<form method="post" enctype="multipart/form-data">
    <input type="hidden" name="MAX_FILE_SIZE" value="5000000">
    <label for="ufile">Chọn ảnh</label>
    <input id="ufile" name="ufile" type="file" accept="image/jpeg,image/gif" required>
    <button>Tải ảnh lên</button>
</form>
<?php if ($filename !== ''): ?>
    <?php message('Đã tải ảnh lên thành công.') ?>
    <img src="uploads/<?= e($filename) ?>" alt="Ảnh vừa tải lên">
<?php endif; endPage();

