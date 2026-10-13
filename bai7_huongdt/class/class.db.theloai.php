<?php
include 'class.db.php';

class DB_TheLoai extends DB {

    function __construct() {
        parent::__construct();
    }

    // Lấy danh sách thể loại (Lấy tất cả hoặc theo 1 ID cụ thể)
    public function get_theloai($id = -1) {
        $query = "SELECT * FROM theloai";
        if ($id != -1) {
            $query .= " WHERE idTL=$id";
        }
        $result = $this->db_query($query);
        $theloai = array();
        $i = 0;
        while ($row = $this->db_fetch($result)) {
            $theloai[$i++] = $row;
        }
        return $theloai;
    }

    // Thêm thể loại mới
    public function them_theloai($name, $sort = 0, $show = 1) {
        $query = "INSERT INTO theloai(TenTL, ThuTu, AnHien) VALUES('$name', '$sort', '$show')";
        $result = $this->db_query($query);
        return $result;
    }

    // Cập nhật thể loại
    public function sua_theloai($id, $name, $sort, $show) {
        $query = "UPDATE theloai SET TenTL='$name', ThuTu='$sort', AnHien='$show' WHERE idTL=$id";
        $result = $this->db_query($query);
        return $result;
    }

    // Xóa thể loại
    public function xoa_theloai($id) {
        $query = "DELETE FROM theloai WHERE idTL=$id";
        $result = $this->db_query($query);
        return $result;
    }
}
?>