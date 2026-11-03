<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Có thể cấu hình qua biến môi trường khi MySQL dùng tài khoản/cổng khác.
$server = getenv('DB_HOST') ?: '127.0.0.1';
$user = getenv('DB_USER') ?: 'root';
$pass = getenv('DB_PASS') === false ? '' : getenv('DB_PASS');
$db = getenv('DB_NAME') ?: 'udn';
$port = (int) (getenv('DB_PORT') ?: 3306);

try {
    $con = mysqli_connect($server, $user, $pass, $db, $port);
    mysqli_set_charset($con, 'utf8mb4');
} catch (mysqli_sql_exception $e) {
    http_response_code(503);
    exit('Không kết nối được cơ sở dữ liệu. Hãy bật MySQL, nhập udn.sql và kiểm tra cấu hình kết nối.');
}
