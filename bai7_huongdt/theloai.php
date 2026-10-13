<?php
include 'config.php';
include 'class/class.db.theloai.php';

$db_theloai = new DB_TheLoai();
$theloai_arr = $db_theloai->get_theloai();
$theloai_html = "";

if (count($theloai_arr) == 0) {
    $theloai_html = '<div class="alert alert-warning">Chưa có thể loại nào. <a href="theloai_them.php" class="btn btn-sm btn-primary">THÊM THỂ LOẠI</a></div>';
} else {
    $theloai_html = '<h1>Quản trị thể loại</h1>
    <table class="table table-bordered table-striped mt-3">
    <thead class="thead-dark">
        <tr>
            <th>Tên thể loại</th>
            <th>Thứ tự</th>
            <th>Ẩn / Hiện</th>
            <th colspan="2" class="text-center"><a href="theloai_them.php" class="btn btn-sm btn-success">Thêm Thể Loại</a></th>
        </tr>
    </thead>
    <tbody>';
    foreach ($theloai_arr as $item) {
        $anhien_text = ($item['AnHien'] == 1) ? 'Hiện' : 'Ẩn';
        $theloai_html .= '<tr>';
        $theloai_html .= '<td>' . $item['TenTL'] . '</td>';
        $theloai_html .= '<td>' . $item['ThuTu'] . '</td>';
        $theloai_html .= '<td>' . $anhien_text . '</td>';
        $theloai_html .= '<td class="text-center"><a href="sua_theloai.php?id=' . $item['idTL'] . '" class="btn btn-sm btn-primary">Sửa</a></td>';
        $theloai_html .= '<td class="text-center"><a href="delete.php?id=' . $item['idTL'] . '" onclick="return confirm(\'Bạn có thực sự muốn xóa?\');" class="btn btn-sm btn-danger">Xóa</a></td>';
        $theloai_html .= '</tr>';
    }
    $theloai_html .= '</tbody></table>';
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>THỂ LOẠI | QUẢN TRỊ</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-4">
        <?php echo $theloai_html; ?>
    </div>
</body>
</html>