<?php
function get_category_name($category_id) {
    global $db;
    try {
        $statement = $db->prepare('SELECT categoryName FROM categories
                                  WHERE categoryID = :category_id');
        $statement->bindValue(':category_id', $category_id, PDO::PARAM_INT);
        $statement->execute();
        $name = $statement->fetchColumn();
        $statement->closeCursor();
        return $name === false ? '' : $name;
    } catch (PDOException $e) {
        display_db_error($e->getMessage());
    }
}

function get_products_by_category($category_id) {
    global $db;
    $query = 'SELECT * FROM products
              WHERE categoryID = :category_id
              ORDER BY productID';
    try {
        $statement = $db->prepare($query);
        $statement->bindValue(':category_id', $category_id);
        $statement->execute();
        $result = $statement->fetchAll();
        $statement->closeCursor();
        return $result;
    } catch (PDOException $e) {
        $error_message = $e->getMessage();
        display_db_error($error_message);
    }
}

function get_product($product_id) {
    global $db;
    $query = 'SELECT *
              FROM products
              WHERE productID = :product_id';
    try {
        $statement = $db->prepare($query);
        $statement->bindValue(':product_id', $product_id);
        $statement->execute();
        $result = $statement->fetch();
        $statement->closeCursor();
        return $result;
    } catch (PDOException $e) {
        $error_message = $e->getMessage();
        display_db_error($error_message);
    }
}

// Step 6: get a product (as an array) by its name; returns false if not found
function get_product_by_name($product_name) {
    global $db;
    $query = 'SELECT * FROM products
              WHERE productName = :product_name';
    try {
        $statement = $db->prepare($query);
        $statement->bindValue(':product_name', $product_name);
        $statement->execute();
        $product = $statement->fetch();
        $statement->closeCursor();
        return $product;
    } catch (PDOException $e) {
        display_db_error($e->getMessage());
    }
}

// Step 5: add_product lets PDOException bubble up so index.php can detect a duplicate code
function add_product($category_id, $code, $name, $description,
        $price, $discount_percent) {
    global $db;
    $query = 'INSERT INTO products
                 (categoryID, productCode, productName, description,
                  listPrice, discountPercent, dateAdded)
              VALUES
                 (:category_id, :code, :name, :description, :price,
                  :discount_percent, NOW())';
                  
    $statement = $db->prepare($query);
    $statement->bindValue(':category_id', $category_id);
    $statement->bindValue(':code', $code);
    $statement->bindValue(':name', $name);
    $statement->bindValue(':description', $description);
    $statement->bindValue(':price', $price);
    $statement->bindValue(':discount_percent', $discount_percent);
    $statement->execute();
    $statement->closeCursor();

    $product_id = $db->lastInsertId();
    return $product_id;
}

function update_product($product_id, $code, $name, $description,
                        $price, $discount_percent, $category_id) {
    global $db;
    $query = 'UPDATE products
              SET productName = :name,
                  productCode = :code,
                  description = :description,
                  listPrice = :price,
                  discountPercent = :discount_percent,
                  categoryID = :category_id
              WHERE productID = :product_id';
    try {
        $statement = $db->prepare($query);
        $statement->bindValue(':name', $name);
        $statement->bindValue(':code', $code);
        $statement->bindValue(':description', $description);
        $statement->bindValue(':price', $price);
        $statement->bindValue(':discount_percent', $discount_percent);
        $statement->bindValue(':category_id', $category_id);
        $statement->bindValue(':product_id', $product_id);
        $statement->execute();
        $row_count = $statement->rowCount();
        $statement->closeCursor();
        return $row_count;
    } catch (PDOException $e) {
        $error_message = $e->getMessage();
        display_db_error($error_message);
    }
}

function delete_product($product_id) {
    global $db;
    $query = 'DELETE FROM products WHERE productID = :product_id';
    $statement = $db->prepare($query);
    $statement->bindValue(':product_id', $product_id);
    $statement->execute();
    $row_count = $statement->rowCount();
    $statement->closeCursor();
    return $row_count;
}
?>
