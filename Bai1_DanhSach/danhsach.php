<?php
header('Content-Type: text/html; charset=utf-8');
require __DIR__ . '/inc/connect.inc';

function initClass($conn)
{
    $sql = "SELECT DISTINCT lop FROM sinhvien WHERE lop IS NOT NULL AND lop <> '' ORDER BY lop";
    $rs = mysqli_query($conn, $sql);
    while ($row = mysqli_fetch_assoc($rs)) {
        $lop = htmlspecialchars($row['lop'], ENT_QUOTES, 'UTF-8');
        echo '<option value="' . $lop . '">' . $lop . '</option>';
    }
}

try {
    ob_start();
    initClass($con);
    $options = ob_get_clean();
} catch (mysqli_sql_exception $e) {
    ob_end_clean();
    http_response_code(500);
    exit('Không đọc được danh sách lớp. Hãy kiểm tra bảng sinhvien trong cơ sở dữ liệu.');
}
?>
<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ajax - Danh sách lớp</title>
    <link href="style/style.css" rel="stylesheet">
    <style>
        body {font-size: 11pt;}
        #ds {padding: 3px 5px; font: normal 13pt Arial; overflow-x: auto;}
        table {background-color: #eaeaea;}
        td, th {font: normal 11pt Arial; border: 1px solid silver; padding: 5px 8px; text-align: left;}
        .hd {background-color: navy; color: white;}
        select {font: inherit;}
        @media (max-width: 600px) {body {margin: 24px 16px;}}
    </style>
    <script>
        let request = null;

        function sendajax() {
            const lop = document.getElementById('lop').value;
            const objds = document.getElementById('ds');
            if (request) request.abort();
            if (!lop) {
                objds.textContent = 'Chưa có lớp trong cơ sở dữ liệu.';
                return;
            }
            objds.textContent = 'Đang tải danh sách...';
            const xml = new XMLHttpRequest();
            request = xml;
            xml.onreadystatechange = function () {
                if (xml.readyState === 4 && request === xml) {
                    if (xml.status === 200) {
                        objds.innerHTML = xml.responseText;
                    } else {
                        objds.textContent = 'Không tải được danh sách. Hãy kiểm tra MySQL và kết nối rồi chọn lại lớp.';
                    }
                }
            };
            xml.open('GET', 'ds.php?lop=' + encodeURIComponent(lop), true);
            xml.timeout = 10000;
            xml.ontimeout = function () {
                if (request === xml) objds.textContent = 'Máy chủ phản hồi quá lâu. Hãy chọn lại lớp để thử lại.';
            };
            xml.send();
        }
    </script>
</head>
<body onload="sendajax()">
    <h3>In danh sách theo từng lớp</h3>
    <label for="lop">Chọn lớp:</label>
    <select id="lop" onchange="sendajax()">
        <?= $options ?>
    </select>
    <hr>
    <div id="ds" aria-live="polite">Danh sách</div>
</body>
</html>
