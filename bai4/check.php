<meta charset="utf-8">
<?php
session_start();
include("connect.php");

$us = $_GET["user"];
$pw = $_GET["pass"];

$stmt = mysqli_prepare($con, "SELECT * FROM sinhvien WHERE masv=? AND matkhau=?");
mysqli_stmt_bind_param($stmt, "ss", $us, $pw);
mysqli_stmt_execute($stmt);
$rs = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($rs) > 0) {
    $row = mysqli_fetch_array($rs);
    $_SESSION["code"] = $row['masv'];
    $_SESSION["info"] = $us . " - " . $row["hoten"] . " - " . $row["lop"];
    echo $us . " - " . $row['hoten'] . "&nbsp;|&nbsp;<a href='QLSV.php'>Next</a>";
} else {
    echo "Bạn nhập thông tin sai!";
}

mysqli_free_result($rs);
mysqli_close($con);
?>