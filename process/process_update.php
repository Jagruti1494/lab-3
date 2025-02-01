<?php
require_once '../classes/Inventory.php';
require_once '../classes/FileStorage.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $inventory = new Inventory();

    $productId = isset($_POST['product']) ? htmlspecialchars($_POST['product']) : null;
    $newQuantity = isset($_POST['newQuantity']) ? filter_var($_POST['newQuantity'], FILTER_VALIDATE_INT) : null;

    if ($productId === null || $newQuantity === null || $newQuantity < 0) {
        header('Location: ../forms/update_inventory.php?error=1');
        exit;
    }

    try {
        $inventory->updateStock($productId, $newQuantity);
        header('Location: ../forms/update_inventory.php?success=1');
        exit;
    } catch (Exception $e) {
        header('Location: ../forms/update_inventory.php?error=2');
        exit;
    }
}
?>
