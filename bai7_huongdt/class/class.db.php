<?php
class DB {
    private $link;

    function __construct() {
        global $config;
        $this->link = mysqli_connect($config['host'], $config['user'], $config['pass'], $config['data']) 
                or die("Không thể kết nối CSDL");
        mysqli_query($this->link, "set names 'utf8'");
    }

    // Thực thi câu lệnh SQL
    public function db_query($query) {
        $result = mysqli_query($this->link, $query);
        return $result;
    }

    // Trích xuất dòng kết quả dưới dạng mảng kết hợp
    public function db_fetch($result) {
        $row = mysqli_fetch_assoc($result);
        return $row;
    }
}
?>
