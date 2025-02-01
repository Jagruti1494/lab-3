<?php
require_once '../classes/Inventory.php';
require_once '../classes/FileStorage.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productName = isset($_POST['productName']) ? htmlspecialchars($_POST['productName']) : '';
    $price = isset($_POST['price']) ? filter_var($_POST['price'], FILTER_VALIDATE_FLOAT) : null;
    $quantity = isset($_POST['quantity']) ? filter_var($_POST['quantity'], FILTER_VALIDATE_INT) : null;

    if (empty($productName)) {
        echo json_encode(['error' => 'Product name is required']);
        exit;
    }

    if ($price === false || $price < 0) {
        echo json_encode(['error' => 'Invalid price']);
        exit;
    }

    if ($quantity === false || $quantity < 0) {
        echo json_encode(['error' => 'Invalid quantity']);
        exit;
    }

    $inventory = new Inventory();
    $productId = $inventory->generateProductId();

    try {
        $inventory->addProduct($productId, $productName, $price, $quantity);

        header('Location: ../index.php?success=1');
        exit;

    } catch (Exception $e) {
        echo json_encode(['error' => 'Error adding product: ' . $e->getMessage()]);
        exit;
    }
}
?>
