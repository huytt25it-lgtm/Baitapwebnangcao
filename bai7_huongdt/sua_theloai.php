<?php
include 'config.php';
include 'class/class.db.theloai.php';

if (isset($_GET['id'])) {
    $db_theloai = new DB_TheLoai();
    $theloai_arr = $db_theloai->get_theloai($_GET['id']);
    
    if (count($theloai_arr) > 0) {
        $ten_theloai = $theloai_arr[0]['TenTL'];
        $thu_tu = $theloai_arr[0]['ThuTu'];
        $an_hien = $theloai_arr[0]['AnHien'];
    }

    if (isset($_POST['submit'])) {
        $id = isset($_POST['id']) ? $_POST['id'] : -1;
        $ten_theloai = isset($_POST['ten_theloai']) ? $_POST['ten_theloai'] : "";
        $thu_tu = isset($_POST['thu_tu']) ? $_POST['thu_tu'] : 0;
        $an_hien = isset($_POST['an_hien']) ? $_POST['an_hien'] : 1;

        $result = $db_theloai->sua_theloai($id, $ten_theloai, $thu_tu, $an_hien);
        if ($result) {
            echo '<script>alert("Cập nhật thành công!"); window.location="theloai.php";</script>';
        } else {
            echo '<script>alert("Cập nhật không thành công!"); window.location="theloai.php";</script>';
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>CẬP NHẬT THỂ LOẠI | QUẢN TRỊ</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-4">
        <h1>Cập nhật thể loại</h1>
        <form name="sua_theloai" method="POST" class="mt-3">
            <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
            <div class="form-group">
                <label>Tên thể loại:</label>
                <input type="text" name="ten_theloai" value="<?php echo $ten_theloai; ?>" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Thứ tự:</label>
                <input type="number" name="thu_tu" value="<?php echo $thu_tu; ?>" class="form-control">
            </div>
            <div class="form-group">
                <label>Ẩn / Hiện:</label>
                <select name="an_hien" class="form-control">
                    <option value="1" <?php echo ($an_hien == 1) ? 'selected' : ''; ?>>Hiện</option>
                    <option value="0" <?php echo ($an_hien == 0) ? 'selected' : ''; ?>>Ẩn</option>
                </select>
            </div>
            <button type="submit" name="submit" class="btn btn-primary">Cập nhật thể loại</button>
            <a href="theloai.php" class="btn btn-secondary">Quay lại</a>
        </form>
    </div>
</body>
</html>