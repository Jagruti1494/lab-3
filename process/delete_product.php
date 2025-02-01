<?php
require_once '../classes/Inventory.php';

$inventory = new Inventory();
$id = isset($_POST['id']) ? htmlspecialchars($_POST['id']) : null;

if ($id !== null) {
    // Fetch all products
    $products = $inventory->getAllProducts();

    // Check if product ID exists in the inventory
    if (isset($products[$id])) {
        unset($products[$id]); // Remove product from the array

        // Check if the inventory file is writable
        $filePath = __DIR__ . '/../data/inventory.json';
        if (is_writable($filePath)) {
            // Save updated products list back to the file
            $result = file_put_contents($filePath, json_encode($products, JSON_PRETTY_PRINT));

            // Check if the file was successfully updated
            if ($result === false) {
                echo "Error: Could not save the updated inventory.";
                exit;
            }

            echo "success"; // Successfully deleted the product and saved the file
            exit;
        } else {
            echo "Error: Inventory file is not writable.";
            exit;
        }
    } else {
        echo "Error: Product ID does not exist.";
        exit;
    }
} else {
    echo "Error: No product ID provided.";
    exit;
}
?>
