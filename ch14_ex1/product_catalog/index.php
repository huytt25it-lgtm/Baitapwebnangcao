<?php
require_once('../model/database.php');
require_once('../model/category.php');
require_once('../model/category_db.php');
require_once('../model/product.php');
require_once('../model/product_db.php');

// BƯỚC 15: Khởi tạo các đối tượng CategoryDB và ProductDB
$categoryDB = new CategoryDB();
$productDB = new ProductDB();

$action = filter_input(INPUT_POST, 'action');
if ($action == NULL) {
    $action = filter_input(INPUT_GET, 'action');
    if ($action == NULL) {
        $action = 'list_products';
    }
}

if ($action == 'list_products') {
    $category_id = filter_input(INPUT_GET, 'category_id', FILTER_VALIDATE_INT);
    if ($category_id == NULL || $category_id == FALSE) {
        $category_id = 1;
    }

    // BƯỚC 15: Thay thế gọi hàm Static bằng các phương thức từ đối tượng ($categoryDB, $productDB)
    $categories = $categoryDB->getCategories();
    $current_category = $categoryDB->getCategory($category_id);
    $products = $productDB->getProductsByCategory($category_id);

    include('product_list.php');

} else if ($action == 'view_product') {
    $product_id = filter_input(INPUT_GET, 'product_id', FILTER_VALIDATE_INT);

    // BƯỚC 15: Thay thế gọi hàm Static
    $categories = $categoryDB->getCategories();
    $product = $productDB->getProduct($product_id);

    include('product_view.php');
}
?>