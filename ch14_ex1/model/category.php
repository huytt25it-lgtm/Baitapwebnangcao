<?php

class Category {
    private $id;
    private $name;

    // 4. Constructor 0 tham số (hoặc đặt mặc định)
    public function __construct() {
        $this->id = 0;
        $this->name = '';
    }

    public function getId() { return $this->id; }
    public function setId($value) { $this->id = $value; }

    public function getName() { return $this->name; }
    public function setName($value) { $this->name = $value; }
}
?>