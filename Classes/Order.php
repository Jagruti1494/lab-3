<?php
require_once __DIR__ . '/FileStorage.php';

class Order 
{
    private FileStorage $storage;
    private string $orderId;
    private string $customerName;
    private array $products = [];
    private float $totalPrice = 0;
    private string $orderDate;
    
    public function __construct(string $customerName) 
    {
        $this->storage = new FileStorage();
        $this->orderId = uniqid('order_');
        $this->customerName = $customerName;
        $this->orderDate = date('Y-m-d H:i:s');
    }
    
    public function addProduct(int $productId, int $quantity, float $price): void 
    {
        if ($quantity > 0 && $price > 0) {
            $this->products[$productId] = [
                'quantity' => $quantity,
                'price' => $price
            ];
            $this->updateTotal();
        }
    }
    
    private function updateTotal(): void 
    {
        $this->totalPrice = array_reduce($this->products, function ($total, $product) {
            return $total + ($product['quantity'] * $product['price']);
        }, 0);
    }
    
    public function save(): void 
    {
        $orders = $this->storage->retrieve('orders');
        $orders[$this->orderId] = $this->getSummary();
        $this->storage->store('orders', $orders);
    }
    
    public function getSummary(): array 
    {
        return [
            'orderId' => $this->orderId,
            'customerName' => $this->customerName,
            'products' => $this->products,
            'totalPrice' => $this->totalPrice,
            'orderDate' => $this->orderDate
        ];
    }
}
?>
