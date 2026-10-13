<?php
include 'config.php';
include 'class/class.db.theloai.php';

if (isset($_POST['submit'])) {
    $db_theloai = new DB_TheLoai();
    $ten_theloai = isset($_POST['ten_theloai']) ? $_POST['ten_theloai'] : "";
    $thu_tu = isset($_POST['thu_tu']) ? $_POST['thu_tu'] : 0;
    $an_hien = isset($_POST['an_hien']) ? $_POST['an_hien'] : 1;

    $result = $db_theloai->them_theloai($ten_theloai, $thu_tu, $an_hien);
    if ($result) {
        echo '<script>alert("Thêm thành công!"); window.location="theloai.php";</script>';
    } else {
        echo '<script>alert("Thêm không thành công!"); window.location="theloai.php";</script>';
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>THÊM THỂ LOẠI | QUẢN TRỊ</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-4">
        <h1>Thêm thể loại</h1>
        <form name="them_theloai" method="POST" class="mt-3">
            <div class="form-group">
                <label>Tên thể loại:</label>
                <input type="text" name="ten_theloai" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Thứ tự:</label>
                <input type="number" name="thu_tu" value="0" class="form-control">
            </div>
            <div class="form-group">
                <label>Ẩn / Hiện:</label>
                <select name="an_hien" class="form-control">
                    <option value="1" selected>Hiện</option>
                    <option value="0">Ẩn</option>
                </select>
            </div>
            <button type="submit" name="submit" class="btn btn-success">Thêm thể loại</button>
            <a href="theloai.php" class="btn btn-secondary">Quay lại</a>
        </form>
    </div>
</body>
</html>