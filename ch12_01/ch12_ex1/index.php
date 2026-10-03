<?php
// Cấu hình thời gian sống của Session Cookie là 3 năm (Exercise 12-1: Yêu cầu 6)
$lifetime = 60 * 60 * 24 * 365 * 3;
session_set_cookie_params($lifetime, '/');
session_start();

// Tạo danh sách sản phẩm mẫu
$products = array(
    'MHO-1010' => array('name' => 'Flute', 'cost' => '149.50'),
    'MHO-2020' => array('name' => 'Clarinet', 'cost' => '199.50'),
    'MHO-3030' => array('name' => 'Trumpet', 'cost' => '299.50'),
    'MHO-4040' => array('name' => 'Saxophone', 'cost' => '399.50')
);

// Lấy action từ request
$action = filter_input(INPUT_POST, 'action');
if ($action === NULL) {
    $action = filter_input(INPUT_GET, 'action');
    if ($action === NULL) {
        $action = 'show_add_item';
    }
}

// Khởi tạo giỏ hàng trong Session nếu chưa tồn tại
if (empty($_SESSION['cart12'])) {
    $_SESSION['cart12'] = array();
}

switch ($action) {
    case 'add':
        $product_key = filter_input(INPUT_POST, 'productkey');
        $item_qty = filter_input(INPUT_POST, 'itemqty', FILTER_VALIDATE_INT);

        if ($product_key !== NULL && $item_qty !== FALSE && $item_qty > 0) {
            if (isset($_SESSION['cart12'][$product_key])) {
                $_SESSION['cart12'][$product_key] += $item_qty;
            } else {
                $_SESSION['cart12'][$product_key] = $item_qty;
            }
        }
        include('cart_view.php');
        break;

    case 'update':
        $new_qty_list = filter_input(INPUT_POST, 'newqty', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
        if ($new_qty_list !== NULL) {
            foreach ($new_qty_list as $key => $qty) {
                if ($_SESSION['cart12'][$key] != $qty) {
                    if ($qty == 0) {
                        unset($_SESSION['cart12'][$key]);
                    } else {
                        $_SESSION['cart12'][$key] = $qty;
                    }
                }
            }
        }
        include('cart_view.php');
        break;

    case 'show_cart':
        include('cart_view.php');
        break;

    case 'show_add_item':
        include('add_item_view.php');
        break;

    case 'empty_cart':
        // Xóa các mặt hàng trong giỏ hàng nhưng giữ nguyên Session ID
        $_SESSION['cart12'] = array();
        include('cart_view.php');
        break;

    // Yêu cầu 9: Xóa Session và Cookie
    case 'end_session':
        // 1. Xóa tất cả dữ liệu session khỏi bộ nhớ
        $_SESSION = array();

        // 2. Xóa Cookie của Session trên trình duyệt
        $name = session_name();
        $expire = time() - 42000;
        $params = session_get_cookie_params();
        $path = $params['path'];
        $domain = $params['domain'];
        $secure = $params['secure'];
        $httponly = $params['httponly'];
        setcookie($name, '', $expire, $path, $domain, $secure, $httponly);

        // 3. Hủy Session ID
        session_destroy();

        // Chuyển hướng về trang chủ
        header("Location: .");
        break;
}
?>