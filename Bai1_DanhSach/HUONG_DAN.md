# Bài 1 — In danh sách sinh viên theo từng lớp bằng AJAX

## Chạy bằng XAMPP

1. Chép cả thư mục `Bai1_DanhSach` vào thư mục `htdocs` của XAMPP, ví dụ `D:\XAMPP\htdocs\Bai1_DanhSach`.
2. Mở XAMPP Control Panel, bật Apache và MySQL.
3. Mở `http://localhost/phpmyadmin`, tạo cơ sở dữ liệu mới tên `udn` với bảng mã `utf8mb4_unicode_ci`, rồi chọn Import và nhập tệp `udn.sql` trong bài làm. Nếu đã có `udn`, hãy kiểm tra bảng `sinhvien` trước; không nhập lại vào bảng đang có dữ liệu vì có thể trùng mã sinh viên.
4. Truy cập `http://localhost/Bai1_DanhSach/danhsach.php`.
5. Chọn lần lượt các lớp để xem danh sách. Không mở trực tiếp tệp PHP bằng cách nhấp đúp.

Kết nối mặc định dành cho XAMPP trên máy cá nhân: máy chủ `127.0.0.1`, cổng `3306`, tài khoản `root`, mật khẩu trống, cơ sở dữ liệu `udn`. Nếu máy dùng thông tin khác, cấu hình các biến môi trường `DB_HOST`, `DB_PORT`, `DB_USER`, `DB_PASS`, `DB_NAME` cho tiến trình PHP. Không đưa mật khẩu thật vào mã nguồn.

## Các tệp chính

- `danhsach.php`: đọc các lớp từ MySQL, tạo hộp chọn và đặt hàm JavaScript `sendajax()` ngay trong tệp PHP như đề yêu cầu.
- `ds.php`: nhận tham số `lop`, truy vấn bảng `sinhvien`, tạo chuỗi HTML chứa bảng rồi `echo` về trình duyệt.
- `inc/connect.inc`: kết nối MySQL bằng MySQLi và đặt bảng mã để hiển thị tiếng Việt.
- `style/style.css`: giữ CSS đã cung cấp; các kiểu riêng của bảng được đặt trong `danhsach.php` theo mẫu đề.
- `inc/library.inc`: giữ nguyên tệp hỗ trợ đã cung cấp. Bài này không cần gọi các hàm tỉnh/huyện trong tệp đó.
- `udn.sql`: bản dữ liệu mẫu nguyên gốc đã cung cấp.
- `ket-qua.png`: ảnh kết quả lớp SPT sau khi chạy thử.

## Luồng hoạt động để giải thích khi vấn đáp

1. Khi mở trang, PHP chạy `SELECT DISTINCT lop ...` để lấy mỗi lớp một lần và tạo các thẻ `<option>`.
2. Khi chọn lớp, sự kiện `onchange` gọi `sendajax()`.
3. `document.getElementById('lop').value` lấy lớp đang chọn thông qua DOM, tức cách JavaScript truy cập các phần tử HTML.
4. `XMLHttpRequest` gửi yêu cầu GET tới `ds.php?lop=SPT`. Tham số thứ ba của `open()` là `true` để gửi bất đồng bộ, cho phép trang tiếp tục hoạt động trong khi chờ phản hồi.
5. PHP dùng truy vấn `WHERE lop = ?`, gắn giá trị lớp bằng `mysqli_stmt_bind_param()`, rồi đọc từng dòng bằng vòng lặp `while`.
6. `ds.php` trả bảng có các cột TT, Mã số, Họ tên và Địa chỉ. Không đưa cột mật khẩu vào kết quả.
7. Khi `readyState === 4` và HTTP status là `200`, JavaScript gán HTML nhận được vào `div` có `id="ds"`. Chỉ vùng danh sách thay đổi, cả trang không tải lại.

AJAX không bắt buộc trả XML: đề này trả HTML để chèn trực tiếp vào trang. `encodeURIComponent()` mã hóa giá trị lớp khi đưa vào URL; `htmlspecialchars()` bảo đảm dữ liệu hiển thị dưới dạng văn bản. Truy vấn có tham số giúp dữ liệu nhập vào không bị hiểu thành câu lệnh SQL.

## Kết quả đã kiểm tra

Đã chạy bằng PHP 8.2.12 và MariaDB 10.4.32, nhập dữ liệu vào cơ sở dữ liệu thử riêng:

| Lớp | Số sinh viên |
| --- | ---: |
| 15T | 11 |
| SPT | 5 |
| TUD | 5 |
| XML | 5 |

- Các tệp PHP và INC không có lỗi cú pháp.
- Đổi cả bốn lớp trả đúng số sinh viên và không tải lại trang.
- Thiếu lớp, tham số lớp dạng mảng hoặc quá dài được trả HTTP 400.
- Lớp không có sinh viên hiển thị thông báo trong bảng.

Phiên xem thử đã mở tại `http://127.0.0.1:8087/danhsach.php`, dùng MySQL thử ở cổng `33317`. Đây là cấu hình riêng của phiên kiểm tra; mã nộp vẫn mặc định dùng MySQL cổng `3306`. Để chạy lại lâu dài, thực hiện các bước XAMPP ở trên.

## Tài liệu tham khảo do người dùng cung cấp

- Đề `BAI 1. DANH SACH.pdf`.
- [W3Schools XML](https://www.w3schools.com/xml/).
- [JavaScript HTML DOM](https://www.w3schools.com/js/js_htmldom.asp).
- [AJAX](https://www.w3schools.com/whatis/whatis_ajax.asp).
