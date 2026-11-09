<?php
require __DIR__ . '/../common.php';
$mode = field('mode', $_GET) === 'all' ? 'all' : 'one';
$localLibrary = is_file(__DIR__ . '/ckeditor/ckeditor.js');
page('Bài 8 · Soạn thảo với CKEditor', 'Thử khởi tạo một trình soạn thảo hoặc nhiều trình soạn thảo, rồi gửi nội dung về PHP.');
?>
<p><a href="?mode=one">Cách 1 · JavaScript replace</a> · <a href="?mode=all">Cách 2 · PHP gọi replaceAll</a></p>
<?php if ($localLibrary): ?><script src="ckeditor/ckeditor.js"></script><?php endif ?>
<p id="editor-warning" hidden>Chưa tải được CKEditor. Hãy xem hướng dẫn cài thư viện cục bộ trong README.</p>
<form method="post">
    <label for="tomtat">Tóm tắt</label>
    <textarea id="tomtat" class="editor" name="tomtat" rows="6"><?= e(field('tomtat', $_POST)) ?></textarea>
    <?php if ($mode === 'all'): ?>
        <label for="noidung">Nội dung</label>
        <textarea id="noidung" class="editor" name="noidung" rows="6"><?= e(field('noidung', $_POST)) ?></textarea>
    <?php endif ?>
    <button>Gửi về PHP</button>
</form>
<script>
    if (!window.CKEDITOR) document.getElementById('editor-warning').hidden = false;
    else CKEDITOR.config.versionCheck = false;
</script>
<?php if ($mode === 'all'): ?>
    <?php require __DIR__ . '/CKEditor.php'; (new CKEditor())->replaceAll(); ?>
<?php else: ?>
    <script>if (window.CKEDITOR) CKEDITOR.replace('tomtat');</script>
<?php endif ?>
<?php if (posted()): ?>
    <h2>Mã HTML PHP nhận được</h2>
    <h3>Tóm tắt</h3><pre><?= e(field('tomtat', $_POST)) ?></pre>
    <?php if ($mode === 'all'): ?><h3>Nội dung</h3><pre><?= e(field('noidung', $_POST)) ?></pre><?php endif ?>
<?php endif; endPage();

