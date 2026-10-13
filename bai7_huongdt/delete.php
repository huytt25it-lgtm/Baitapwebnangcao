<?php
include 'config.php';
include 'class/class.db.theloai.php';

if (isset($_GET['id'])) {
    $db_theloai = new DB_TheLoai();
    $id = $_GET['id'];

    $result = $db_theloai->xoa_theloai($id);
    if ($result) {
        echo '<script>alert("Xóa thành công!"); window.location="theloai.php";</script>';
    } else {
        echo '<script>alert("Xóa không thành công!"); window.location="theloai.php";</script>';
    }
}
?>