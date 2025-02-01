<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Place Order - Grocery Store</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="../css/place_order.css">
</head>

<body class="container my-5">
    <?php
    require_once '../navbar.php';
    require_once '../classes/Inventory.php';
    $inventory = new Inventory();
    $products = $inventory->listProducts();

    $availableProducts = array_filter($products, function ($product) {
        return $product['quantity'] > 0;
    });
    ?>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success text-center">Order placed successfully!</div>
    <?php endif; ?>

    <h2 class="order-header text-center mb-4">Place Your Order</h2>

    <?php if (empty($availableProducts)): ?>
        <div class="alert alert-warning text-center mt-4">
            No products are currently available for order.
        </div>
    <?php else: ?>
        <form action="../process/process_order.php" method="POST" class="mt-4" onsubmit="return validateOrderForm()">
            <div class="mb-3">
                <label for="customerName" class="form-label">Customer Name:</label>
                <input type="text" class="form-control" id="customerName" name="customerName" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Delivery Option:</label>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="delivery" id="pickup" value="pickup" checked>
                    <label class="form-check-label" for="pickup">Store Pickup</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="delivery" id="delivery" value="delivery">
                    <label class="form-check-label" for="delivery">Home Delivery</label>
                </div>
            </div>

            <div class="form-section mt-4">
                <h4>Select Products:</h4>
                <div class="row row-cols-1 row-cols-md-3 g-4">
                    <?php foreach ($availableProducts as $id => $product): ?>
                        <div class="col">
                            <div class="card product-card">
                                <div class="card-body text-center">
                                    <h5 class="product-title"><?php echo htmlspecialchars($product['name']); ?></h5>
                                    <p class="product-price">$<?php echo number_format($product['price'], 2); ?></p>
                                    <p>Available: <?php echo $product['quantity']; ?> Items</p>
                                    <div class="input-group">
                                        <span class="input-group-text">Quantity:</span>
                                        <input type="number" class="form-control"
                                               name="products[<?php echo htmlspecialchars($id); ?>]" min="0"
                                               max="<?php echo $product['quantity']; ?>" value="0"
                                               data-price="<?php echo $product['price']; ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="text-end mt-4">
                    <h5>Total Amount: $<span id="totalAmount">0.00</span></h5>
                </div>
            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-primary mt-4">Place Order</button>
            </div>
        </form>
    <?php endif; ?>

    <script>
        function validateOrderForm() {
            const customerName = document.getElementById('customerName').value;
            if (!customerName.trim()) {
                alert('Please enter a customer name');
                return false;
            }

            let hasProducts = false;
            const quantityInputs = document.querySelectorAll('input[type="number"]');
            quantityInputs.forEach(input => {
                if (parseInt(input.value) > 0) {
                    hasProducts = true;
                }
            });

            if (!hasProducts) {
                alert('Please select at least one product');
                return false;
            }

            return true;
        }

        document.addEventListener('DOMContentLoaded', function () {
            const quantityInputs = document.querySelectorAll('input[type="number"]');
            const totalAmountSpan = document.getElementById('totalAmount');

            function updateTotal() {
                let total = 0;
                quantityInputs.forEach(input => {
                    const quantity = parseInt(input.value) || 0;

                    if (quantity > 0) {
                        const price = parseFloat(input.dataset.price);
                        total += quantity * price;
                    }
                });
                totalAmountSpan.textContent = total.toFixed(2);
            }

            quantityInputs.forEach(input => {
                input.addEventListener('input', function () {
                    if (this.value < 0) {
                        this.value = 0;
                    }
                    updateTotal();
                });

                input.addEventListener('change', function () {
                    if (this.value < 0) {
                        this.value = 0;
                    }
                    updateTotal();
                });
            });
        });
    </script>

</body>

</html>
