<?php
namespace App\Models;

use App\Models\Product;

class Furniture extends Product {
    private $length;
    private $width;
    private $height;

    public function __construct($sku, $name, $price, $length, $width, $height) {
        parent::__construct($sku, $name, $price, 'F');
        $this->length = $length;
        $this->width = $width;
        $this->height = $height;
    }

    public function display() {
        return "Furniture - SKU: {$this->getSku()}, Name: {$this->getName()}, Price: {$this->getPrice()}, Dimensions: {$this->getSpecialAttribute()}";
    }

    public function save($conn) {
        $stmt = $conn->prepare("INSERT INTO products (sku, name, price, type, length, width, height) VALUES (?, ?, ?, 'F', ?, ?, ?)");
        $stmt->bind_param("ssdiii", $this->sku, $this->name, $this->price, $this->length, $this->width, $this->height);
        return $stmt->execute();
    }

    public function getSpecialAttribute() {
        return "{$this->length} x {$this->width} x {$this->height}";
    }

    // Added getter methods
    public function getLength() {
        return $this->length;
    }

    public function getWidth() {
        return $this->width;
    }

    public function getHeight() {
        return $this->height;
    }
}
