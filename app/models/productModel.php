<?php
//require_once($_SERVER['DOCUMENT_ROOT'] . '/Scandiweb/app/factories/ProductFactory.php'); // Ensure the factory is included
namespace App\Models;

use App\Factories\ProductFactory;
class ProductModel {
    private $conn;

    public function __construct($connection) {
        $this->conn = $connection;
    }

    public function fetchAllProducts() {
        $products = [];
        $query = "SELECT * FROM products"; // Adjust the query as necessary
        $result = $this->conn->query($query);

        if ($result) {
            while ($row = $result->fetch_assoc()) {
                // Use the ProductFactory to create the product object
                $products[] = ProductFactory::create($row['type'], $row); // Assuming 'type' holds 'D', 'B', or 'F'
            }
        }

        return $products; // Return the array of products
    }

    public function saveProduct($product) {
        // Prepare the SQL query
        $stmt = $this->conn->prepare("INSERT INTO products (sku, name, price, type, size, weight, length, width, height) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    
        // Get the product properties
        $sku = $product->getSku();
        $name = $product->getName();
        $price = $product->getPrice();
        $type = $product->getType();
        
        // Set size, weight, length, width, height to null if not applicable
        $size = $type === 'D' ? $product->getSpecialAttribute() : null; // Size for DVD
        $weight = $type === 'B' ? $product->getSpecialAttribute() : null; // Weight for Book
        $length = $type === 'F' ? $product->getLength() : null;
        $width = $type === 'F' ? $product->getWidth() : null;
        $height = $type === 'F' ? $product->getHeight() : null;
    
        // Bind the parameters
        $stmt->bind_param("ssdsiiiii", $sku, $name, $price, $type, $size, $weight, $length, $width, $height);
    
        // Execute the statement and check for errors
        if ($stmt->execute()) {
            return true; // Success
        } 
        else {
            // Log the error for debugging
            error_log("Database error: " . $stmt->error);
            return false; // Failure
        }
    }
}
