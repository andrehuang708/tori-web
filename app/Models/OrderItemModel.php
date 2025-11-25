<?php

namespace App\Models;

use CodeIgniter\Model;

class OrderItemModel extends Model
{
    protected $table = 'order_items';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'order_id',
        'product_id',
        'product_name',
        'quantity',
        'price',
        'subtotal',
        'order_items_status'
    ];
    
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $dateFormat = 'datetime';
    
    /**
     * Format price to IDR
     */
    public function formatPrice($price)
    {
        return 'Rp ' . number_format($price, 0, ',', '.');
    }
    
    /**
     * Get items by order ID
     */
    public function getItemsByOrderId($orderId)
    {
        return $this->where('order_id', $orderId)->findAll();
    }

    /**
     * Get best selling products
     * 
     * @param int $limit Number of products to return
     * @return array
     */
    public function getBestSellingProducts($limit = 5)
    {
        return $this->select('product_id, product_name, SUM(quantity) as total_sold, SUM(subtotal) as total_revenue')
                   ->select('(SELECT image FROM products WHERE id = order_items.product_id) as product_image')
                   ->groupBy('product_id, product_name')
                   ->orderBy('total_sold', 'DESC')
                   ->limit($limit)
                   ->findAll();
    }
}
