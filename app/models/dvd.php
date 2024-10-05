<?php
//require_once($_SERVER['DOCUMENT_ROOT'] . '/Scandiweb/app/Models/product.php');
namespace App\Models;

use App\Models\Product;
class DVD extends Product{
    private $size;

    public function __construct($sku, $name, $price, $size) {
        parent::__construct($sku, $name, $price, 'D');
        $this->size = $size;
    }

    public function getSpecialAttribute() {
        return $this->getSize() . ' MB';
    }

    public function getSize() {
        return $this->size;
    }

    public function display() {
        return "DVD - SKU: {$this->getSku()}, Name: {$this->getName()}, Price: {$this->getPrice()}, Size: {$this->getSpecialAttribute()}";
    }

    public function save($conn) {
        $stmt = $conn->prepare("INSERT INTO products (sku, name, price, type, size) VALUES (?, ?, ?, 'D', ?)");
        $stmt->bind_param("ssdi", $this->sku, $this->name, $this->price, $this->size);
        return $stmt->execute();
    }
}
