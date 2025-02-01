<?php
require_once __DIR__ . '/FileStorage.php';

class Sales 
{
    private FileStorage $storage;
    private array $salesRecords;
    
    public function __construct() 
    {
        $this->storage = new FileStorage();
        $this->salesRecords = $this->storage->retrieve('orders');
    }
    
    public function getTotalSales(): float 
    {
        return array_sum(array_column($this->salesRecords, 'totalPrice'));
    }
    
    public function getSalesReport(): array 
    {
        return $this->salesRecords;
    }
}
?>
