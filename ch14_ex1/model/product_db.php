<?php
class ProductDB {
    // 11. Sử dụng phương thức thường (bỏ static)
    public function getProductsByCategory($category_id) {
        $db = Database::getDB();

        $categoryDB = new CategoryDB();
        $category = $categoryDB->getCategory($category_id);

        $query = 'SELECT * FROM products
                  WHERE categoryID = :category_id
                  ORDER BY productID';
        $statement = $db->prepare($query);
        $statement->bindValue(':category_id', $category_id);
        $statement->execute();
        $rows = $statement->fetchAll();
        $statement->closeCursor();

        $products = array();
        foreach ($rows as $row) {
            // 7. Tạo đối tượng Product không tham số và gán dữ liệu qua Setter
            $product = new Product();
            $product->setId($row['productID']);
            $product->setCategory($category);
            $product->setCode($row['productCode']);
            $product->setName($row['productName']);
            $product->setPrice($row['listPrice']); // Dùng listPrice thay vì price

            $products[] = $product;
        }
        return $products;
    }

    public function getProduct($product_id) {
        $db = Database::getDB();
        $query = 'SELECT * FROM products
                  WHERE productID = :product_id';
        $statement = $db->prepare($query);
        $statement->bindValue(':product_id', $product_id);
        $statement->execute();
        $row = $statement->fetch();
        $statement->closeCursor();

        // Kiểm tra nếu không tìm thấy dữ liệu hoặc product_id bị trống
        if ($row === false || $row === null) {
            return null;
        }

        $categoryDB = new CategoryDB();
        $category = $categoryDB->getCategory($row['categoryID']);

        $product = new Product();
        $product->setId($row['productID']);
        $product->setCategory($category);
        $product->setCode($row['productCode']);
        $product->setName($row['productName']);
        $product->setPrice($row['listPrice']); 

        return $product;
    }

    public function deleteProduct($product_id) {
    $db = Database::getDB();
    // Xóa theo productID hoặc productCode để đảm bảo truyền kiểu gì cũng xóa được
    $query = 'DELETE FROM products
              WHERE productID = :product_id OR productCode = :product_id';
    $statement = $db->prepare($query);
    $statement->bindValue(':product_id', $product_id);
    $statement->execute();
    $statement->closeCursor();
}

    public function addProduct($product) {
        $db = Database::getDB();

        $query = 'INSERT INTO products
                     (categoryID, productCode, productName, listPrice)
                  VALUES
                     (:category_id, :code, :name, :price)';
        $statement = $db->prepare($query);
        $statement->bindValue(':category_id', $product->getCategory()->getId());
        $statement->bindValue(':code', $product->getCode());
        $statement->bindValue(':name', $product->getName());
        $statement->bindValue(':price', $product->getPrice());
        $statement->execute();
        $statement->closeCursor();
    }
}
?>