<!doctype html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OOP with PHP</title>
    <link rel="icon" href="data:,">
    <link href="style.css" rel="stylesheet">
    <script>
        let request = null;

        function ajax() {
            const obj = document.getElementById('info');
            const value = document.getElementById('chon').value;
            if (request) request.abort();
            if (value === '') {
                obj.textContent = '';
                obj.style.display = 'none';
                return;
            }
            obj.style.display = 'block';
            obj.textContent = 'Đang tải dữ liệu...';
            const xml = new XMLHttpRequest();
            request = xml;
            xml.onreadystatechange = function () {
                if (xml.readyState === 4 && request === xml) {
                    if (xml.status === 200) {
                        obj.innerHTML = xml.responseText;
                    } else {
                        obj.textContent = 'Không tải được dữ liệu. Hãy kiểm tra MySQL và cấu hình kết nối, rồi chọn lại danh sách.';
                    }
                }
            };
            xml.open('GET', 'showTable.php?chon=' + encodeURIComponent(value), true);
            xml.timeout = 10000;
            xml.ontimeout = function () {
                if (request === xml) obj.textContent = 'Máy chủ phản hồi quá lâu. Hãy chọn lại danh sách để thử lại.';
            };
            xml.send();
        }
    </script>
</head>
<body>
    <h1>OOP with PHP</h1>
    <label for="chon">Chọn danh sách:</label>
    <select id="chon" onchange="ajax()">
        <option value="">-- Chọn --</option>
        <option value="giaovien">Giáo viên</option>
        <option value="sinhvien">Sinh viên</option>
        <option value="hocphan">Học phần</option>
    </select>
    <hr>
    <div id="info" style="display:none;" aria-live="polite"></div>
</body>
</html>
