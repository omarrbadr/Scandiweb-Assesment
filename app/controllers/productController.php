<?php
//require_once($_SERVER['DOCUMENT_ROOT'] . '/Scandiweb/app/Models/productModel.php');
//require_once($_SERVER['DOCUMENT_ROOT'] . '/Scandiweb/app/Models/product.php');
//require_once($_SERVER['DOCUMENT_ROOT'] . '/Scandiweb/app/Models/dvd.php');
//require_once($_SERVER['DOCUMENT_ROOT'] . '/Scandiweb/app/Models/book.php');
//require_once($_SERVER['DOCUMENT_ROOT'] . '/Scandiweb/app/Models/furniture.php');
//require_once($_SERVER['DOCUMENT_ROOT'] . '/Scandiweb/app/factories/ProductFactory.php'); 

namespace App\Controllers;
//require_once __DIR__ . '/../../config/db_connect.php';  
use Config\Database;
use App\Models\ProductModel;
use App\Factories\ProductFactory;

class ProductController {
    private $connection;
    private $model;

    public function __construct($connection) {
        $this->connection = $connection;
        $this->model = new ProductModel($this->connection);
    }

    public function showProductList() {
        $products = $this->getProducts();
        require_once __DIR__ . '/../Views/product_list.php'; //require_once($_SERVER['DOCUMENT_ROOT'] . '/app/Views/product_list.php'); 
    }

    public function showAddProductForm() {
        require_once __DIR__ . '/../Views/add_product.php'; //require_once($_SERVER['DOCUMENT_ROOT'] . '/app/Views/add_product.php'); 
    }

    public function addProduct() {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $sku = $_POST['sku'];
            $name = $_POST['name'];
            $price = $_POST['price'];
            $type = $_POST['type'];
    
            $product = null;
            $errorMessage = "";
    
            // Validate required fields based on product type
            switch ($type) {
                case 'D':
                    $size = $_POST['size'];
                    if (empty($size) || $size <= 0) {
                        $errorMessage = "Please enter a valid Size";
                    } else {
                        $product = ProductFactory::create('D', [
                            'sku' => $sku,
                            'name' => $name,
                            'price' => $price,
                            'size' => $size
                        ]);
                    }
                    break;
    
                case 'B':
                    $weight = $_POST['weight'];
                    if (empty($weight) || $weight <= 0) {
                        $errorMessage = "Please enter a valid Weight";
                    } else {
                        $product = ProductFactory::create('B', [
                            'sku' => $sku,
                            'name' => $name,
                            'price' => $price,
                            'weight' => $weight
                        ]);
                    }
                    break;
    
                case 'F':
                    $length = $_POST['length'];
                    $width = $_POST['width'];
                    $height = $_POST['height'];
                    if (empty($length) || empty($width) || empty($height) || $length <= 0 || $width <= 0 || $height <= 0) {
                        $errorMessage = "Please enter valid Dimentions";
                    } else {
                        $product = ProductFactory::create('F', [
                            'sku' => $sku,
                            'name' => $name,
                            'price' => $price,
                            'length' => $length,
                            'width' => $width,
                            'height' => $height
                        ]);
                    }
                    break;
    
                default:
                    $errorMessage = "Invalid product type!";
                    break;
            }

            if (empty($price) || $price <= 0) {
                $errorMessage = "Price must be greater than zero.";
            }
    
            // If there's no error and a product has been created, save it
            if (empty($errorMessage) && $product) {
                if ($this->model->saveProduct($product)) {
                    echo json_encode(['success' => true, 'message' => 'Product saved successfully.']);
                    exit;
                } else {
                    $errorMessage = "Error saving product to database.";
                }
            }
    
            // Return any error message as JSON
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => $errorMessage]);
            exit;
        }
    
        // If not a POST request, show the form
        require_once($_SERVER['DOCUMENT_ROOT'] . '/app/views/add_product.php'); 
    }
    

    public function deleteProduct() {
        ob_start(); // Start output buffering
        header('Content-Type: application/json'); // Ensure JSON response
    
        $data = json_decode(file_get_contents("php://input"), true);
    
        if (isset($data['delete_ids']) && is_array($data['delete_ids'])) {
            foreach ($data['delete_ids'] as $sku) {
                $stmt = $this->connection->prepare("DELETE FROM products WHERE sku = ?");
                $stmt->bind_param("s", $sku);
                $stmt->execute();
            }
            echo json_encode(['success' => true, 'message' => 'Products deleted successfully.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'No valid product IDs provided.']);
        }
    
        ob_end_flush(); // Send the buffered output
        exit(); // Prevent further output
    }
    
    

    public function getProducts() {
        return $this->model->fetchAllProducts(); 
    }
}
