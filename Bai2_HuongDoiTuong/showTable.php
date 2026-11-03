<?php
header('Content-Type: text/html; charset=utf-8');
header('Cache-Control: no-store');

$op = $_GET['chon'] ?? '';
if (!is_string($op)) {
    http_response_code(400);
    exit('Lựa chọn không hợp lệ.');
}
if ($op === '') {
    exit;
}

// Chỉ cho phép ba truy vấn cố định, không ghép tên bảng từ URL vào SQL.
$tables = [
    'giaovien' => [
        'header' => 'DANH SÁCH GIÁO VIÊN',
        'sql' => 'SELECT magv AS `Mã giáo viên`, hoten AS `Họ tên` FROM giaovien ORDER BY magv'
    ],
    'sinhvien' => [
        'header' => 'DANH SÁCH SINH VIÊN',
        'sql' => 'SELECT masv AS `Mã sinh viên`, hoten AS `Họ tên`, lop AS `Lớp`, diachi AS `Địa chỉ` FROM sinhvien ORDER BY masv'
    ],
    'hocphan' => [
        'header' => 'DANH SÁCH HỌC PHẦN',
        'sql' => 'SELECT mahp AS `Mã học phần`, tenhp AS `Tên học phần`, tinchi AS `Số tín chỉ`, tienquyet AS `Tiên quyết`, hocky AS `Học kỳ` FROM hocphan ORDER BY mahp'
    ]
];
if (!isset($tables[$op])) {
    http_response_code(400);
    exit('Chỉ được chọn Giáo viên, Sinh viên hoặc Học phần.');
}

require __DIR__ . '/database.class.php';
$user = getenv('DB_USER') ?: 'root';
$pass = getenv('DB_PASS') === false ? '' : getenv('DB_PASS');
$db = getenv('DB_NAME') ?: 'udn';

try {
    $dao = new Dao($user, $pass, $db);
    $dao->table($tables[$op]['sql'], $tables[$op]['header']);
} catch (mysqli_sql_exception $e) {
    http_response_code(503);
    echo 'Không đọc được dữ liệu. Hãy bật MySQL, kiểm tra cấu hình kết nối và nhập udn.sql vào cơ sở dữ liệu.';
}
