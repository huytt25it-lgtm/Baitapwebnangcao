<?php
class Product {
    private $id;
    private $category;
    private $code;
    private $name;
    private $price;

    // Constructor 0 tham số và gán giá trị mặc định
    public function __construct() {
        $this->id = 0;
        $this->category = null;
        $this->code = '';
        $this->name = '';
        $this->price = 0.0;
    }

    // ID
    public function getId() {
        return $this->id;
    }
    public function setId($value) {
        $this->id = $value;
    }

    // Category
    public function getCategory() {
        return $this->category;
    }
    public function setCategory($value) {
        $this->category = $value;
    }

    // Code
    public function getCode() {
        return $this->code;
    }
    public function setCode($value) {
        $this->code = $value;
    }

    // Name
    public function getName() {
        return $this->name;
    }
    public function setName($value) {
        $this->name = $value;
    }

    // Price
    public function getPrice() {
        return $this->price;
    }
    public function setPrice($value) {
        $this->price = $value;
    }

    // Helper methods định dạng giá sản phẩm
    public function getFormattedPrice() {
        return number_format($this->price, 2);
    }

    // Khắc phục lỗi "Call to undefined method Product::getPriceFormatted()"
    public function getPriceFormatted() {
        return $this->getFormattedPrice();
    }

    // Các tính toán liên quan đến giá giảm
    public function getDiscountPercent() {
        return 30; // Mặc định giảm giá 30%
    }

    public function getDiscountAmount() {
        $discount_percent = $this->getDiscountPercent() / 100;
        return round($this->price * $discount_percent, 2);
    }

    public function getDiscountPrice() {
        return number_format($this->price - $this->getDiscountAmount(), 2);
    }

    // Xử lý hình ảnh sản phẩm
    public function getImageFilename() {
        return $this->code . '.png';
    }

    public function getImagePath() {
        return '../images/' . $this->getImageFilename();
    }

    public function getImageAltText() {
        return 'Image: ' . $this->getImageFilename();
    }
}
?>