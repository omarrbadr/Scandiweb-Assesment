<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <style>
        .hr{
            border: none;
            border-top: 1px black; /* Adjust thickness and color */
            margin: 10px 0; /* Adjust spacing */ 
        }
        .body{
            background-color: azure;
        }
        .row {
            display: flex; 
            flex-wrap: wrap; 
        }
        .container {
            background-color: white;
        }
        .product-card {
            margin-bottom: 20px;
            border-width: 2px;
            border: 2px solid #000;
            border-radius: 10px;
            position: relative;
        }
        .product-card .card-body {
            padding: 1rem;
            text-align: center;
        }
        .delete-checkbox {
            position: absolute;
            top: 10px;
            left: 10px;
            transform: scale(1.5); 
        }
        .action-buttons {
            display: flex;
            gap: 10px;
        }
        .background-color{
            background-color: gainsboro;
        }
    </style>
</head>
<body class="background-color" >
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mt-3 mb-4">
                    <h1>Product List</h1>
                    <div class="action-buttons">
                        <a href="add_product.php" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Add 
                        </a>
                        <button type="button" class="btn btn-danger" id="mass-delete-btn" disabled onclick="handleMassDelete()">
                            <i class="fas fa-trash"></i> Mass Delete
                        </button>
                    </div>
                </div>
                <hr class="hr">
                <div class="row">
                    <?php
                    //require_once($_SERVER['DOCUMENT_ROOT'] . '/Scandiweb/app/Models/productModel.php');
                    //require_once($_SERVER['DOCUMENT_ROOT'] . '/Scandiweb/app/Controllers/ProductController.php');
                    //require_once(__DIR__ . '/config/db_connect.php');
                    //use Omarbadr\Scandiweb\Models\ProductModel;
                    //require_once($_SERVER['DOCUMENT_ROOT'] . '/config/db_connect.php');
                    //require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';
                    //require $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';
                    require_once __DIR__ . '/../../vendor/autoload.php';
                    //require __DIR__ . '/../../config/db_connect.php'; 
                    use Config\Database;
                    use App\Controllers\ProductController;

                    // Get the database connection
                    $connection = Database::getConnection();

                    if (!$connection) {
                        die("Database connection is not established.");
                    }

                    // Initialize ProductController with DB connection
                    $controller = new ProductController($connection);

                    // Fetch products using the controller
                    $products = $controller->getProducts();

                    if (!empty($products)) {
                        foreach ($products as $product) {
                            echo '<div class="col-md-3">
                                    <div class="card product-card">
                                        <div class="card-body">
                                            <input type="checkbox" name="delete_ids[]" value="' . htmlspecialchars($product->getSku()) . '" class="delete-checkbox" onclick="toggleDeleteButton()">
                                            <h6 class="card-title">' . htmlspecialchars($product->getSku()) . '</h6>
                                            <p class="card-text">
                                                <strong>' . htmlspecialchars($product->getName()) . '</strong><br>
                                                $' . htmlspecialchars($product->getPrice()) . '<br>';
                            echo htmlspecialchars($product->getSpecialAttribute());
                            echo        '</p>
                                        </div>
                                    </div>
                                  </div>';
                        }
                    } 
                    else {
                        echo '<div class="col-12">
                                <div class="alert alert-info" role="alert">
                                    No products found.
                                </div>
                              </div>';
                    }
                    ?>
                </div>
            </div>
            <hr class="hr">
            <div class="text-center mb-3">
                <p>Scandiweb Test assignment</p>
            </div>
        </hr>
        
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleDeleteButton() {
            const checkboxes = document.querySelectorAll('.delete-checkbox');
            const deleteBtn = document.getElementById('mass-delete-btn');
            deleteBtn.disabled = !Array.from(checkboxes).some(checkbox => checkbox.checked);
        }

        function handleMassDelete() {
                const deleteIds = []; // Collect selected product SKUs
                document.querySelectorAll('.delete-checkbox:checked').forEach(checkbox => {
                    deleteIds.push(checkbox.value);
                });

                // Add this console log to debug the IDs being collected
                console.log("Attempting to delete products:", deleteIds);

                fetch('/index.php?action=deleteProduct', {
                method: 'POST', 
                body: JSON.stringify({ delete_ids: deleteIds }),
                headers: {
                    'Content-Type': 'application/json'
                }
            })
            .then(response => {
                return response.text(); // Get the response as text
            })
            .then(text => {
                console.log("Raw response:", text); // Log the raw response
                const data = JSON.parse(text); // Attempt to parse the JSON
                if (data.success) {
                    window.location.reload(); 
                } else {
                    alert(data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while deleting the products.');
            });

        }

    </script>
</body>
</html>
