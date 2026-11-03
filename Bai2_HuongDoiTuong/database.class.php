<?php
class Dao
{
    private $con;

    public function __construct($user, $pass, $db)
    {
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        $host = getenv('DB_HOST') ?: '127.0.0.1';
        $port = (int) (getenv('DB_PORT') ?: 3306);
        $this->con = mysqli_connect($host, $user, $pass, $db, $port);
        mysqli_set_charset($this->con, 'utf8mb4');
    }

    public function query($sql)
    {
        return mysqli_query($this->con, $sql);
    }

    public function table($sql, $header)
    {
        $rs = $this->query($sql);
        // Tên cột lấy từ kết quả truy vấn, kể cả tên được đặt bằng AS.
        $fieldinfo = mysqli_fetch_fields($rs);
        $str = '<table><thead><tr>';
        foreach ($fieldinfo as $val) {
            $name = htmlspecialchars($val->name, ENT_QUOTES, 'UTF-8');
            $str .= '<th scope="col">' . $name . '</th>';
        }
        $str .= '</tr></thead><tbody>';

        while ($r = mysqli_fetch_assoc($rs)) {
            $str .= '<tr>';
            foreach ($fieldinfo as $val) {
                $value = htmlspecialchars((string) ($r[$val->name] ?? ''), ENT_QUOTES, 'UTF-8');
                $str .= '<td>' . $value . '</td>';
            }
            $str .= '</tr>';
        }
        if (mysqli_num_rows($rs) === 0) {
            $str .= '<tr><td colspan="' . count($fieldinfo) . '">Chưa có dữ liệu.</td></tr>';
        }
        $str .= '</tbody></table>';
        mysqli_free_result($rs);

        echo '<h3>' . htmlspecialchars($header, ENT_QUOTES, 'UTF-8') . '</h3>';
        echo $str;
    }

    public function __destruct()
    {
        if ($this->con instanceof mysqli) {
            mysqli_close($this->con);
        }
    }
}
