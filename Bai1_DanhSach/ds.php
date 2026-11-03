<?php
header('Content-Type: text/html; charset=utf-8');
header('Cache-Control: no-store');

$lop = $_GET['lop'] ?? '';
if (!is_string($lop) || trim($lop) === '' || mb_strlen($lop, 'UTF-8') > 4) {
    http_response_code(400);
    exit('Lớp không hợp lệ.');
}
$lop = trim($lop);
require __DIR__ . '/inc/connect.inc';

try {
    // Dấu ? giữ dữ liệu nhập vào tách biệt với câu lệnh SQL.
    $stmt = mysqli_prepare($con, 'SELECT masv, hoten, diachi FROM sinhvien WHERE lop = ? ORDER BY masv');
    mysqli_stmt_bind_param($stmt, 's', $lop);
    mysqli_stmt_execute($stmt);
    $rs = mysqli_stmt_get_result($stmt);
} catch (mysqli_sql_exception $e) {
    http_response_code(500);
    exit('Không đọc được danh sách sinh viên. Hãy kiểm tra bảng sinhvien.');
}

$str = '<table><thead><tr class="hd">'
     . '<th scope="col">TT</th><th scope="col">Mã số</th>'
     . '<th scope="col">Họ tên</th><th scope="col">Địa chỉ</th>'
     . '</tr></thead><tbody>';
$tt = 1;
while ($row = mysqli_fetch_assoc($rs)) {
    $masv = htmlspecialchars($row['masv'], ENT_QUOTES, 'UTF-8');
    $hoten = htmlspecialchars($row['hoten'] ?? '', ENT_QUOTES, 'UTF-8');
    $diachi = htmlspecialchars($row['diachi'] ?? '', ENT_QUOTES, 'UTF-8');
    $str .= "<tr><td>{$tt}</td><td>{$masv}</td><td>{$hoten}</td><td>{$diachi}</td></tr>";
    $tt++;
}
if ($tt === 1) {
    $str .= '<tr><td colspan="4">Không có sinh viên trong lớp này.</td></tr>';
}
$str .= '</tbody></table>';
echo $str;
