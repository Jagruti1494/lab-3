<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Inventory</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/show_products.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body class="container mt-3">
    <?php
    require_once './Classes/Inventory.php';

    $inventory = new Inventory();
    $products = $inventory->listProducts();
    ?>

    <h2 class="text-center mb-3 mt-5">Product Inventory</h2>

    <?php if (empty($products)): ?>
        <div class="alert alert-info text-center">
            No products available to show.
        </div>
    <?php else: ?>
        <div class="row g-4 justify-content-center">
            <?php foreach ($products as $id => $product): ?>
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="product-card" id="product-<?php echo $id; ?>">
                        <div class="card-header text-center bg-primary text-white">
                            <?php echo htmlspecialchars($product['name']); ?>
                        </div>
                        <div class="product-details text-center p-3">
                            <p><strong>ID:</strong> <?php echo htmlspecialchars($id); ?></p>
                            <p><strong>Price:</strong> $<?php echo number_format($product['price'], 2); ?></p>
                            <p><strong>Quantity:</strong> <?php echo intval($product['quantity']); ?></p>
                            <?php if (intval($product['quantity']) > 0): ?>
                                <span class="status-badge in-stock">In Stock</span>
                            <?php else: ?>
                                <span class="status-badge out-of-stock">Out of Stock</span>
                            <?php endif; ?>
                        </div>
                        <div class="text-center">
                            <button class="remove-btn btn btn-danger mt-3" onclick="deleteProduct('<?php echo $id; ?>')">
                                Remove
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <script>
        function deleteProduct(productId) {
            if (confirm("Are you sure you want to delete this product?")) {
                $.ajax({
                    url: "/Lab3/process/delete_product.php", // Update path if needed
                    type: "POST",
                    data: { id: productId },
                    success: function (response) {
                        if (response.trim() === "success") {
                            $("#product-" + productId).fadeOut(500, function () {
                                $(this).remove();
                                // If no products left, reload the page
                                if ($('.product-card').length === 0) {
                                    location.reload();
                                }
                            });
                        } else {
                            alert("Failed to delete product.");
                        }
                    },
                    error: function () {
                        alert("Error deleting product.");
                    }
                });
            }
        }
    </script>
    
</body>

</html>
