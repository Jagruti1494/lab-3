<?php
require_once '../classes/Sales.php';
require_once '../classes/Inventory.php';
require_once '../classes/Order.php';
require_once '../classes/FileStorage.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $inventory = new Inventory();
        $order = new Order($_POST['customerName']);

        foreach ($_POST['products'] as $productId => $quantity) {
            $quantity = intval($quantity);
            if ($quantity > 0) {
                $product = $inventory->getProduct($productId);
                if ($product && $product['quantity'] >= $quantity) {
                    $order->addProduct($productId, $quantity, $product['price']);
                    $inventory->updateQuantity($productId, $product['quantity'] - $quantity);
                }
            }
        }

        $order->saveOrder();

        header('Location: ../forms/sales_form.php?success=1');
        exit;
    } catch (Exception $e) {
        header('Location: ../forms/sales_form.php?error=1');
        exit;
    }
} else {
    header('Location: ../forms/sales_form.php');
    exit;
}
?>
