<?php
require_once '../classes/Order.php';
require_once '../classes/Inventory.php';
require_once '../classes/FileStorage.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize inputs
    $customerName = isset($_POST['customerName']) ? htmlspecialchars($_POST['customerName']) : '';
    $products = isset($_POST['products']) ? $_POST['products'] : [];

    // Basic validation
    if (empty($customerName)) {
        echo json_encode(['error' => 'Customer name is required']);
        exit;
    }

    if (empty($products)) {
        echo json_encode(['error' => 'At least one product must be selected']);
        exit;
    }

    $inventory = new Inventory();
    $order = new Order($customerName);

    try {
        foreach ($products as $productId => $quantity) {
            // Ensure quantity is a positive integer
            $quantity = (int) $quantity;

            if ($quantity > 0) {
                $product = $inventory->fetchProduct($productId);
                if ($product) {
                    // Add the product to the order and update inventory
                    $order->addProduct($productId, $quantity, $product['price']);
                    $updatedQuantity = $product['quantity'] - $quantity;
                    if ($updatedQuantity >= 0) {
                        $inventory->updateStock($productId, $updatedQuantity);
                    } else {
                        echo json_encode(['error' => 'Not enough stock for product ID ' . $productId]);
                        exit;
                    }
                } else {
                    echo json_encode(['error' => 'Product with ID ' . $productId . ' not found']);
                    exit;
                }
            }
        }

        // Save the order
        $order->save();
        header('Location: ../forms/place_order.php?success=1');
        exit;

    } catch (Exception $e) {
        echo json_encode(['error' => 'An error occurred: ' . $e->getMessage()]);
        exit;
    }
}
?>
