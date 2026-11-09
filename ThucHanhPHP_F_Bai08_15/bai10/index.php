<?php
require __DIR__ . '/../db.php';
openSession();
$error = '';
if (posted()) {
    checkCsrf();
    switch (field('action', $_POST)) {
        case 'create':
            $_SESSION['demo_name'] = 'hocweb.com.vn';
            $_SESSION['demo_age'] = 120;
            redirect();
        case 'unset':
            unset($_SESSION['demo_name']);
            redirect();
        case 'clear':
            $_SESSION = [];
            session_destroy();
            redirect();
        case 'count':
            $_SESSION['demo_count'] = ($_SESSION['demo_count'] ?? 0) + 1;
            redirect();
        case 'login':
            $stmt = db()->prepare('SELECT username, password_hash FROM nguoidung WHERE username = ?');
            $stmt->execute([field('username', $_POST)]);
            $user = $stmt->fetch();
            $password = $_POST['password'] ?? '';
            if ($user && is_string($password) && password_verify($password, $user['password_hash'])) {
                session_regenerate_id(true);
                $_SESSION['demo_user'] = $user['username'];
                redirect('quantri.php');
            }
            $error = 'Tên đăng nhập hoặc mật khẩu không đúng.';
            break;
        case 'logout':
            unset($_SESSION['demo_user']);
            session_regenerate_id(true);
            redirect();
    }
}
page('Bài 10 · Session trong PHP', 'Session lưu trạng thái trên máy chủ giữa các lần gửi yêu cầu.');
?>
<h2>10.1 · Tạo, đọc và hủy Session</h2>
<p>Tên: <?= e($_SESSION['demo_name'] ?? 'chưa đặt') ?> · Tuổi: <?= e($_SESSION['demo_age'] ?? 'chưa đặt') ?></p>
<form method="post">
    <?php csrfField() ?>
    <button name="action" value="create">Khởi tạo tên và tuổi</button>
    <button name="action" value="unset">Xóa biến tên</button>
    <button name="action" value="clear">Hủy toàn bộ Session bài thực hành</button>
</form>
<h2>10.2 · Bộ đếm</h2>
<p>Lượt đếm: <?= e($_SESSION['demo_count'] ?? 0) ?></p>
<form method="post">
    <?php csrfField() ?>
    <button name="action" value="count">Tăng lượt đếm</button>
</form>
<h2>10.3 · Đăng nhập</h2>
<p><a href="quantri.php">Thử mở trang quản trị</a></p>
<?php message($error, true) ?>
<?php if (!isset($_SESSION['demo_user'])): ?>
    <form method="post">
        <?php csrfField() ?>
        <?php input('Tên đăng nhập', 'username', field('username', $_POST)) ?>
        <?php input('Mật khẩu', 'password', '', 'password') ?>
        <button name="action" value="login">Đăng nhập</button>
    </form>
<?php else: ?>
    <form method="post">
        <?php csrfField() ?>
        <button name="action" value="logout">Đăng xuất</button>
    </form>
<?php endif; endPage();

