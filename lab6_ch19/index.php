<?php
require_once(__DIR__ . '/util/main.php');
require_once(__DIR__ . '/model/database.php');
require_once(__DIR__ . '/model/product_db.php');

// Step 4: display the products in the Guitars category (ID = 1)
$category_id = 1;
$category_name = get_category_name($category_id);

// Step 5: add a product using the model function (sample data)
$code = 'mrs_1';
$name = 'Fender Telecaster';
$price = '699.00';
$description = 'Fender Telecaster electric guitar';
$discount_percent = 0;

try {
    add_product($category_id, $code, $name, $description,
                $price, $discount_percent);
    $insert_message = "Product '$name' was inserted.";
} catch (PDOException $e) {
    // 1062 = duplicate entry (product code must be unique)
    if (isset($e->errorInfo[1]) && (int) $e->errorInfo[1] === 1062) {
        $insert_message = "Product '$name' was not inserted. "
                        . "The product code '$code' already exists.";
    } else {
        display_db_error($e->getMessage());
    }
}

// Steps 6-7: look up "Fender Telecaster" by name, then delete it by ID
$delete_name = 'Fender Telecaster';
$product = get_product_by_name($delete_name);
if ($product) {
    try {
        $row_count = delete_product($product['productID']);
        $delete_message = $row_count === 1
            ? "Product '$delete_name' was deleted."
            : "Product '$delete_name' was not deleted.";
    } catch (PDOException $e) {
        display_db_error($e->getMessage());
    }
} else {
    $delete_message = "Product '$delete_name' was not found, so nothing was deleted.";
}

// Get the products after the changes so the list is current
$products = get_products_by_category($category_id);

include __DIR__ . '/home.php';
