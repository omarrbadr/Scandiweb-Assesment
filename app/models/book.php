<?php
//require_once($_SERVER['DOCUMENT_ROOT'] . '/Scandiweb/app/Models/product.php');
namespace App\Models;

use App\Models\Product;
class Book extends Product{
    private $weight;

    public function __construct($sku, $name, $price, $weight) {
        parent::__construct($sku, $name, $price, 'B');
        $this->weight = $weight;
    }

    public function getSpecialAttribute() {
        return $this->getWeight() . ' kg';
    }

    public function getWeight() {
        return $this->weight;
    }

    public function display() {
        return "Book - SKU: {$this->getSku()}, Name: {$this->getName()}, Price: {$this->getPrice()}, Weight: {$this->getSpecialAttribute()}";
    }

    public function save($conn) {
        $stmt = $conn->prepare("INSERT INTO products (sku, name, price, type, weight) VALUES (?, ?, ?, 'B', ?)");
        $stmt->bind_param("ssdi", $this->sku, $this->name, $this->price, $this->weight);
        return $stmt->execute();
    }
}
