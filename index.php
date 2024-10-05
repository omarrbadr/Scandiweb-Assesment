<?php

require __DIR__ . '/vendor/autoload.php';
use Config\Database;
use App\Controllers\ProductController;

// Create a new database connection using the Database class
$conn = Database::getConnection(); // Ensure this method returns a valid PDO or mysqli connection

// Instantiate the ProductController with the connection
$controller = new ProductController($conn); 

// Retrieve the action from the query string, default to 'showProductList' if not set
$action = $_GET['action'] ?? 'showProductList'; 

// Check if the method exists in the controller
// Check if the method exists in the controller
if (method_exists($controller, $action)) {
    if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
        // For actions that expect POST requests (like deleteProduct)
        if ($action === 'deleteProduct') {
            $controller->deleteProduct();
        } else {
            // Call other POST actions if any exist
            $controller->$action();
        }
    } else {
        // Call the action for GET requests
        $controller->$action();
    }
} else {
    // Handle invalid action with a 404 response
    header("HTTP/1.0 404 Not Found");
    echo "404 Not Found";
}


echo "Index file loaded successfully!";
