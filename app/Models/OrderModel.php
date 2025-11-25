<?php

namespace App\Models;

use CodeIgniter\Model;

class OrderModel extends Model
{
    protected $table = 'orders';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'order_number',
        'customer_name',
        'customer_phone',
        'payment_method',
        'address',
        'subtotal',
        'status',
        'payment_status',
        'created_at',
        'updated_at'
    ];
    
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $dateFormat = 'datetime';
    
    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_PAID = 'paid';
    const STATUS_PROCESSING = 'processing';
    const STATUS_SHIPPED = 'shipped';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';
    
    // Payment method constants
    const PAYMENT_TOPED = 'toped';
    const PAYMENT_SHOPEE = 'shopee';
    const PAYMENT_DIRECT = 'direct';
    
    /**
     * Get payment method name
     */
    public function getPaymentMethodName($method)
    {
        $methods = [
            self::PAYMENT_TOPED => 'Tokopedia',
            self::PAYMENT_SHOPEE => 'Shopee',
            self::PAYMENT_DIRECT => 'Bayar di Tempat',
        ];
        
        return $methods[$method] ?? $method;
    }
    
    /**
     * Get status badge
     */
    public function getStatusBadge($status)
    {
        $badges = [
            self::STATUS_PENDING => 'warning',
            self::STATUS_PAID => 'info',
            self::STATUS_PROCESSING => 'primary',
            self::STATUS_SHIPPED => 'info',
            self::STATUS_COMPLETED => 'success',
            self::STATUS_CANCELLED => 'danger',
        ];
        
        $statusText = ucfirst($status);
        $badgeClass = $badges[$status] ?? 'secondary';
        
        return '<span class="badge bg-' . $badgeClass . '">' . $statusText . '</span>';
    }
    
    /**
     * Get orders with their items and pagination
     * 
     * @param int $perPage Number of items per page
     * @param int $page Current page number
     * @param string|null $status Filter by status
     * @return array
     */
    public function getOrdersWithItems($perPage = 15, $page = 1, $status = null)
    {
        $offset = ($page - 1) * $perPage;
        
        $builder = $this->db->table('orders')
            ->select('orders.*')
            ->orderBy('orders.created_at', 'DESC');
            
        if ($status) {
            $builder->where('orders.status', $status);
        }
        
        // Get paginated orders
        $orders = $builder->get($perPage, $offset)->getResultArray();
        
        // Get order items for each order
        $orderIds = array_column($orders, 'id');
        $orderItems = [];
        
        if (!empty($orderIds)) {
            $items = $this->db->table('order_items')
                ->whereIn('order_id', $orderIds)
                ->get()
                ->getResultArray();
                
            foreach ($items as $item) {
                $orderItems[$item['order_id']][] = $item;
            }
        }
        
        // Attach items to orders
        foreach ($orders as &$order) {
            $order['items'] = $orderItems[$order['id']] ?? [];
        }
        
        // Get total count for pagination
        $total = $this->countAllResults();
        
        // Create pager
        $pager = service('pager');
        $pager->makeLinks($page, $perPage, $total, 'default_full');
        
        return [
            'orders' => $orders,
            'pager' => $pager
        ];
    }
    
    /**
     * Get a single order with its items
     * 
     * @param int $orderId
     * @return array|null
     */
    public function getOrderWithItems($orderId)
    {
        $order = $this->find($orderId);
        
        if (!$order) {
            return null;
        }
        
        // Get order items
        $orderItemModel = new OrderItemModel();
        $order['items'] = $orderItemModel->getItemsByOrderId($orderId);
        
        return $order;
    }
}
