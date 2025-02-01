<?php
require_once __DIR__ . '/FileStorage.php';

class Inventory
{
    private FileStorage $storage;
    private array $products;

    public function __construct()
    {
        $this->storage = new FileStorage();
        $this->products = $this->storage->retrieve('inventory');
    }

    public function generateProductId(): int
    {
        return empty($this->products) ? 1 : max(array_keys($this->products)) + 1;
    }

    public function addProduct(string $name, float $price, int $quantity): int
    {
        $id = $this->generateProductId();
        $this->products[$id] = [
            'name' => $name,
            'price' => $price,
            'quantity' => $quantity
        ];
        $this->storage->store('inventory', $this->products);
        return $id;
    }

    public function updateStock(int $id, int $quantity): bool
    {
        if (isset($this->products[$id])) {
            $this->products[$id]['quantity'] = $quantity;
            $this->storage->store('inventory', $this->products);
            return true;
        }
        return false;
    }

    public function fetchProduct(int $id): ?array
    {
        return $this->products[$id] ?? null;
    }

    public function listProducts(): array
    {
        return $this->products;
    }
}
?>
