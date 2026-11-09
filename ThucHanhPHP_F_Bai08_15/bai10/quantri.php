<?php
require __DIR__ . '/../common.php';
openSession();
header('Cache-Control: no-store');
if (!isset($_SESSION['demo_user'])) {
    http_response_code(403);
}
page('Bài 10 · Trang quản trị');
if (isset($_SESSION['demo_user'])) {
    message('Xin chào ' . $_SESSION['demo_user'] . '. Bạn đã đăng nhập.');
} else {
    message('Bạn cần đăng nhập để xem trang quản trị.', true);
}
?>
<p><a href="./">Quay lại trang Session và đăng nhập</a></p>
<?php endPage();

