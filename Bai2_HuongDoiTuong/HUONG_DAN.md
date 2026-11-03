# Bài 2 — Hướng đối tượng với PHP

## Chạy bằng XAMPP

1. Chép thư mục `Bai2_HuongDoiTuong` vào `htdocs` của XAMPP, ví dụ `D:\XAMPP\htdocs\Bai2_HuongDoiTuong`.
2. Bật Apache và MySQL trong XAMPP Control Panel.
3. Mở `http://localhost/phpmyadmin`. Nếu chưa có cơ sở dữ liệu `udn`, tạo mới với bảng mã `utf8mb4_unicode_ci`, rồi nhập tệp `udn.sql` kèm theo. Nếu đã làm Bài 1 và có các bảng `giaovien`, `sinhvien`, `hocphan`, dùng lại dữ liệu đó, không cần nhập lại.
4. Mở `http://localhost/Bai2_HuongDoiTuong/index.php`.
5. Chọn Giáo viên, Sinh viên hoặc Học phần để xem bảng. Chọn `-- Chọn --` để ẩn bảng.

Không mở trực tiếp PHP bằng cách nhấp đúp tệp. Nếu Apache dùng cổng khác 80, thêm cổng đó vào địa chỉ truy cập.

Kết nối mặc định: `127.0.0.1`, cổng `3306`, tài khoản `root`, mật khẩu trống và cơ sở dữ liệu `udn`, phù hợp XAMPP mặc định trên máy cá nhân. Khi môi trường khác, cấu hình biến môi trường cho tiến trình PHP: `DB_HOST`, `DB_PORT`, `DB_USER`, `DB_PASS`, `DB_NAME`. Không ghi mật khẩu thật vào mã nguồn.

## Đối chiếu tài liệu

Đề PDF yêu cầu `udn`, còn `HuongDan.txt` nhắc đến `dct` và `index.html`. Bài làm theo PDF: dùng `udn.sql` đã cung cấp từ Bài 1 và trang `index.php`. Yêu cầu đặt tên cột theo ý muốn trong tệp hướng dẫn được thực hiện bằng tên cột tiếng Việt.

Giữ nguyên cách tổ chức của mẫu: lớp `Dao`, thuộc tính kết nối riêng, các phương thức `query()` và `table()`, cùng ba tệp PHP chính. Giao diện giữ màu sắc và CSS của tài liệu; bảng trên điện thoại có thể cuộn ngang để đọc đầy đủ.

Mã mẫu có `SELECT *`, bao gồm cả mật khẩu mẫu trong bảng giáo viên và sinh viên. Bài làm chỉ chọn các cột thông tin cần hiển thị. `udn.sql` là dữ liệu mẫu nguyên gốc, chứa mật khẩu thực hành đơn giản; không dùng các giá trị này làm mật khẩu thật.

## Ý nghĩa từng tệp

| Tệp | Chức năng |
| --- | --- |
| `index.php` | Hiển thị hộp chọn, gọi hàm JavaScript `ajax()` và nhận bảng vào `div#info` |
| `database.class.php` | Khai báo lớp `Dao` để kết nối, truy vấn và xuất bảng HTML |
| `showTable.php` | Nhận `chon`, kiểm tra lựa chọn, tạo đối tượng `Dao` và gọi `table()` |
| `style.css` | Định dạng trang theo mẫu |
| `udn.sql` | Dữ liệu mẫu đã cung cấp |
| `ket-qua.png` | Ảnh kết quả trên máy tính |
| `ket-qua-mobile.png` | Ảnh kết quả trên màn hình nhỏ |

## Giải thích luồng xử lý

1. Người dùng chọn Sinh viên; `onchange` gọi `ajax()`.
2. JavaScript lấy giá trị `sinhvien` từ hộp chọn bằng DOM, tức cách truy cập phần tử của trang HTML.
3. `XMLHttpRequest` gửi GET đến `showTable.php?chon=sinhvien`. Tham số `true` trong `open()` giúp gửi bất đồng bộ, không giữ trang chờ tại chỗ như `false` trong mẫu.
4. `showTable.php` chỉ chấp nhận ba lựa chọn định sẵn. Tên từ URL không được ghép trực tiếp vào SQL.
5. `new Dao($user, $pass, $db)` tạo đối tượng; hàm khởi tạo `__construct()` mở kết nối và đặt bảng mã tiếng Việt.
6. `$dao->table($sql, $header)` gọi `query()`, lấy thông tin cột bằng `mysqli_fetch_fields()`, rồi dùng vòng lặp để tạo chuỗi bảng HTML.
7. PHP trả bảng về; JavaScript kiểm tra yêu cầu hoàn tất và HTTP 200, rồi đặt HTML vào `info.innerHTML`. Cả trang không tải lại.

Ví dụ đặt tên cột:

```sql
SELECT masv AS `Mã sinh viên`, hoten AS `Họ tên`,
       lop AS `Lớp`, diachi AS `Địa chỉ`
FROM sinhvien
ORDER BY masv;
```

`AS` đặt tên cho cột trong kết quả truy vấn, không đổi tên cột trong cơ sở dữ liệu. Vì `table()` đọc thông tin cột từ kết quả, tiêu đề hiển thị ngay tên tiếng Việt đã đặt.

## Các ý chính khi vấn đáp

- **Lớp và đối tượng khác nhau thế nào?** `Dao` là khuôn mẫu; `$dao` là đối tượng cụ thể tạo từ lớp đó.
- **Vì sao `$con` có `private`?** Để kết nối chỉ được sử dụng bên trong lớp, bên ngoài thao tác qua các phương thức của lớp. Đây là tính đóng gói.
- **`$this` là gì?** Đại diện cho đối tượng đang thực hiện phương thức. `$this->con` là kết nối của đối tượng đó.
- **Vì sao hàm `table()` dùng lại được?** Hàm không cố định số cột; nó đọc thông tin cột từ câu truy vấn rồi tạo tiêu đề và từng ô tương ứng.
- **`__destruct()` làm gì?** Đóng kết nối khi đối tượng được giải phóng.
- **AJAX có bắt buộc dùng XML không?** Không; bài này trả HTML để hiển thị trực tiếp.
- **`htmlspecialchars()` dùng để làm gì?** Chuyển ký tự đặc biệt thành dạng hiển thị văn bản, tránh dữ liệu bị trình duyệt hiểu thành mã HTML.

## Kiểm tra thực tế

Chạy với PHP 8.2.12 và MariaDB 10.4.32, dùng cơ sở dữ liệu thử riêng đã nhập từ `udn.sql`:

| Danh sách | Số dòng |
| --- | ---: |
| Giáo viên | 10 |
| Sinh viên | 26 |
| Học phần | 16 |

Đã kiểm tra cú pháp ba tệp PHP; cả ba bảng có tên cột tiếng Việt, không trả cột mật khẩu; đổi bảng không tải lại trang; chọn nhanh liên tiếp vẫn hiển thị lựa chọn cuối; lựa chọn rỗng ẩn kết quả; tên bảng sai hoặc tham số dạng mảng trả HTTP 400. Đã kiểm tra giao diện ở chiều rộng 390px và 1100px.

Phiên chạy thử: `http://127.0.0.1:8089/index.php`, dùng MySQL thử cổng `33317`. Cấu hình cổng thử chỉ áp dụng cho tiến trình đang chạy; mã bàn giao mặc định dùng cổng `3306`. Khi cần chạy lại, làm theo các bước XAMPP ở trên.
