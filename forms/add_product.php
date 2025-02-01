<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product - Inventory</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="container py-5">

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success text-center">Product added successfully!</div>
    <?php endif; ?>

    <div class="card shadow-sm p-4">
        <h3 class="text-center mb-4">Add New Product</h3>
        <form action="./process/process_product.php" method="POST" onsubmit="return validateForm()">
            <div class="mb-3">
                <label for="productName" class="form-label">Product Name:</label>
                <input type="text" class="form-control" id="productName" name="productName" required>
            </div>
            <div class="mb-3">
                <label for="productPrice" class="form-label">Price (in USD):</label>
                <input type="number" class="form-control" id="productPrice" name="price" step="0.01" min="0" required>
            </div>
            <div class="mb-3">
                <label for="productQuantity" class="form-label">Initial Quantity:</label>
                <input type="number" class="form-control" id="productQuantity" name="quantity" min="1" required>
            </div>
            <div class="d-grid gap-2 mt-4">
                <button type="submit" class="btn btn-success btn-lg">Add Product</button>
            </div>
        </form>
    </div>

    <script>
        function validateForm() {
            const productPrice = parseFloat(document.getElementById('productPrice').value);
            const productQuantity = parseInt(document.getElementById('productQuantity').value);

            if (productPrice < 0) {
                alert('Price cannot be negative!');
                return false;
            }

            if (productQuantity <= 0) {
                alert('Quantity must be greater than zero!');
                return false;
            }

            return true;
        }
    </script>
</body>

</html>
