<?php
require_once('../model/database.php');
require_once('../model/category.php');
require_once('../model/category_db.php');
require_once('../model/product.php');
require_once('../model/product_db.php');

// BƯỚC 10 & 12: Khởi tạo các đối tượng DB thay vì dùng Static
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

    // BƯỚC 10 & 12: Gọi phương thức từ các đối tượng $categoryDB và $productDB
    $categories = $categoryDB->getCategories();
    $current_category = $categoryDB->getCategory($category_id);
    $products = $productDB->getProductsByCategory($category_id);

    include('product_list.php');

} else if ($action == 'delete_product') {
    $product_code = filter_input(INPUT_POST, 'product_code');
    $category_id = filter_input(INPUT_POST, 'category_id', FILTER_VALIDATE_INT);

    // BƯỚC 12: Gọi hàm deleteProduct qua đối tượng $productDB
    $productDB->deleteProduct($product_code);

    header("Location: .?category_id=$category_id");

} else if ($action == 'show_add_form') {
    // BƯỚC 10: Gọi hàm getCategories qua đối tượng $categoryDB
    $categories = $categoryDB->getCategories();
    include('product_add.php');

} else if ($action == 'add_product') {
    $category_id = filter_input(INPUT_POST, 'category_id', FILTER_VALIDATE_INT);
    $code = filter_input(INPUT_POST, 'code');
    $name = filter_input(INPUT_POST, 'name');
    $price = filter_input(INPUT_POST, 'price', FILTER_VALIDATE_FLOAT);

    if ($category_id == NULL || $category_id == FALSE || $code == NULL || 
            $name == NULL || $price == NULL || $price == FALSE) {
        $error = "Invalid product data. Check all fields and try again.";
        include('../errors/error.php');
    } else {
        // BƯỚC 10: Gọi hàm getCategory qua $categoryDB
        $category = $categoryDB->getCategory($category_id);

        // BƯỚC 7: Khởi tạo Product bằng 0 tham số và gán dữ liệu qua các hàm Setters
        $product = new Product();
        $product->setCategory($category);
        $product->setCode($code);
        $product->setName($name);
        $product->setPrice($price);

        // BƯỚC 12: Gọi hàm addProduct qua đối tượng $productDB
        $productDB->addProduct($product);

        header("Location: .?category_id=$category_id");
    }
}
?>