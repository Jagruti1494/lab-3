<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Inventory</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/navbar.css">
</head>

<body class="container mt-5">
    <?php
    require_once '../navbar.php';
    require_once '../classes/Inventory.php';
    require_once '../classes/FileStorage.php';
    $inventory = new Inventory();
    $products = $inventory->listProducts();
    ?>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success text-center">Inventory updated successfully!</div>
    <?php endif; ?>

    <h2 class="text-center mb-4">Update Inventory</h2>
    <form action="../process/process_update.php" method="POST" class="mt-3" onsubmit="return validateUpdateForm()">
        <div class="mb-3">
            <label for="product" class="form-label">Select Product:</label>
            <select class="form-select" id="product" name="product" required>
                <option value="">Choose product...</option>
                <?php foreach ($products as $id => $product): ?>
                    <option value="<?php echo htmlspecialchars($id); ?>">
                        <?php echo htmlspecialchars($product['name']); ?>
                        (Current: <?php echo $product['quantity']; ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="newQuantity" class="form-label">New Quantity:</label>
            <input type="number" class="form-control" id="newQuantity" name="newQuantity" min="0" required>
            <small id="quantityHelp" class="form-text text-muted">Enter the new quantity for the selected product.</small>
        </div>

        <button type="submit" class="btn btn-success w-100">Update Quantity</button>
    </form>

    <script>
        function validateUpdateForm() {
            const quantity = document.getElementById('newQuantity').value;

            // Ensure quantity is a valid number
            if (quantity < 0 || isNaN(quantity)) {
                alert('Please enter a valid quantity (positive number).');
                return false;
            }

            return true;
        }
    </script>

</body>

</html>
