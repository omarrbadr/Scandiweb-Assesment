<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <style>
        .hr{
            border: none;
            border-top: 1px black; /* Adjust thickness and color */
            margin: 10px 0; /* Adjust spacing */ 
        }
        .body{
            background-color: gainsboro;
        }
        .background-color{
            background-color: gainsboro;
        }
        .container {
            max-width: 800px;
            margin-top: 30px;
            background-color: white;
            padding: 20px; /* Optional: adds padding inside the container */
            border-radius: 5px; /* Optional: rounds the corners of the container */
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); /* Optional: adds a subtle shadow */
        }
        .dynamic-field {
            display: none;
        }
        .description-label {
            font-size: 0.9em;
            color: #555;
        }
        .action-buttons {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }
        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
    </style>
</head>
<body class="background-color" >
    <div class="container">
        <form id="product_form" action="?action=addProduct" method="post">
            <div class="row">
                <div class="col-12">
                    <div class="header-container mb-4">
                        <h1 class="mb-0">Add Product</h1>
                        <div class="action-buttons">
                            <button type="submit" class="btn btn-primary">Save</button>
                            <a href="product_list.php" class="btn btn-secondary">Back</a>
                        </div>
                    </div>
                    <hr>
                    <div class="mb-3">
                        <label for="sku" class="form-label">SKU</label>
                        <input type="text" class="form-control" id="sku" name="sku" required>
                    </div>
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="price" class="form-label">Price ($)</label>
                        <input type="number" class="form-control" id="price" name="price" step="0.01" required>
                    </div>
                    <div class="mb-3">
                        <label for="productType" class="form-label">Type</label>
                        <select class="form-select" id="productType" name="type" required>
                            <option value="" disabled selected>Select Type</option>
                            <option value="D">DVD</option>
                            <option value="B">Book</option>
                            <option value="F">Furniture</option>
                        </select>
                    </div>
                    <div id="size" class="dynamic-field mb-3">
                        <label for="size" class="form-label">Size (MB)</label>
                        <input type="number" class="form-control" id="size" name="size">
                        <span id="size-description" class="description-label"></span>
                    </div>
                    <div id="weight" class="dynamic-field mb-3">
                        <label for="weight" class="form-label">Weight (KG)</label>
                        <input type="number" class="form-control" id="weight" name="weight" step="0.01">
                        <span id="weight-description" class="description-label"></span>
                    </div>
                    <div id="dimensions-field" class="dynamic-field mb-3">
                        <label for="length" class="form-label">Length (CM)</label>
                        <input type="number" class="form-control" id="length" name="length" step="0.01">
                        <label for="width" class="form-label">Width (CM)</label>
                        <input type="number" class="form-control" id="width" name="width" step="0.01">
                        <label for="height" class="form-label">Height (CM)</label>
                        <input type="number" class="form-control" id="height" name="height" step="0.01">
                        <span id="dimensions-description" class="description-label"></span>
                    </div>
                </div>
            </div>
        </form>
        <hr>
        <div class="text-center mb-3 footer-text">
            <p>Scandiweb Test assignment</p>
        </div>
    </div>

    <script>
        const typeField = document.getElementById('productType');
        const sizeField = document.getElementById('size');
        const weightField = document.getElementById('weight');
        const lengthField = document.getElementById('length');
        const widthField = document.getElementById('width');
        const heightField = document.getElementById('height');
        const dimensionsField = document.getElementById('dimensions-field');
        const sizeDescription = document.getElementById('size-description');
        const weightDescription = document.getElementById('weight-description');
        const dimensionsDescription = document.getElementById('dimensions-description');

        typeField.addEventListener('change', function() {
            sizeField.style.display = 'none';
            weightField.style.display = 'none';
            dimensionsField.style.display = 'none';
            //sizeField.required = false;
            //weightField.required = false;
            //lengthField.required = false;
            //widthField.required = false;
            //heightField.required = false;
            sizeDescription.textContent = '';
            weightDescription.textContent = '';
            dimensionsDescription.textContent = '';

            switch (typeField.value) {
                case 'D':
                    sizeField.style.display = 'block';
                    //sizeField.required = true;
                    sizeDescription.textContent = 'Enter the size of the DVD in MB.';
                    break;
                case 'B':
                    weightField.style.display = 'block';
                    //weightField.required = true;
                    weightDescription.textContent = 'Enter the weight of the book in KG.';
                    break;
                case 'F':
                    dimensionsField.style.display = 'block';
                    //lengthField.required = true; 
                    //widthField.required = true; 
                    //heightField.required = true;
                    dimensionsDescription.textContent = 'Enter the dimensions of the furniture.';
                    break;
            }
        });

        document.getElementById('product_form').addEventListener('submit', function(event) {
            event.preventDefault();
            
            const formData = new FormData(this);
            
            fetch('/index.php?action=addProduct', { 
                method: 'POST',
                body: formData
            })
            .then(response => response.json()) // Expecting JSON response
            .then(data => {
                if (data.success) {
                    window.location.href = 'product_list.php'; // Redirect on success
                } else {
                    alert(data.message); // Show error message
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Sku value already exists for another product');
            });
        });
    </script>
</body>
</html>
