<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\OrderModel;
use App\Models\ProductModel;
use App\Models\CategoryModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class Dashboard extends BaseController
{
    protected $orderModel;
    protected $productModel;
    protected $categoryModel;
    
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        
        // Load models
        $this->orderModel = new OrderModel();
        $this->productModel = new ProductModel();
        $this->categoryModel = new CategoryModel();
    }


    
    /**
     * Display the admin dashboard
     */
    public function index()
    {
        // Get date range for the last 30 days
        $endDate = date('Y-m-d');
        $startDate = date('Y-m-d', strtotime('-30 days'));
        
        // Get statistics
        $data = [
            'total_orders' => $this->orderModel->countAllResults(),
            'total_products' => $this->productModel->countAllResults(),
            'total_categories' => $this->categoryModel->countAllResults(),
            'recent_orders' => $this->orderModel->orderBy('created_at', 'DESC')->findAll(5),
            'low_stock_products' => $this->productModel
                ->select('products.*, categories.name as category_name')
                ->join('categories', 'categories.id = products.category_id')
                ->where('stock <', 10)
                ->orderBy('stock', 'ASC')
                ->findAll(5),
            'sales_data' => $this->getSalesData($startDate, $endDate),
            'top_products' => $this->getTopProducts(),
            'order_statuses' => $this->getOrderStatuses()
        ];
        
        return view('admin/dashboard/index', $data);
    }
    
    /**
     * Get sales data for the dashboard chart
     */
    protected function getSalesData($startDate, $endDate)
    {
        // Get sales data grouped by date
        $db = \Config\Database::connect();
        $builder = $db->table('orders')
            ->select('DATE(created_at) as date, COUNT(*) as order_count, SUM(total_amount) as total_sales')
            ->where('DATE(created_at) >=', $startDate)
            ->where('DATE(created_at) <=', $endDate)
            ->where('status !=', 'cancelled')
            ->groupBy('DATE(created_at)')
            ->orderBy('date', 'ASC')
            ->get();
        
        $salesData = [];
        $dates = [];
        $orderCounts = [];
        $salesTotals = [];
        
        // Initialize all dates in range with zero values
        $period = new \DatePeriod(
            new \DateTime($startDate),
            new \DateInterval('P1D'),
            new \DateTime($endDate)
        );
        
        foreach ($period as $date) {
            $dateStr = $date->format('Y-m-d');
            $dates[] = $date->format('M j');
            $salesData[$dateStr] = [
                'order_count' => 0,
                'total_sales' => 0
            ];
        }
        
        // Fill in actual data
        foreach ($builder->getResult() as $row) {
            if (isset($salesData[$row->date])) {
                $salesData[$row->date] = [
                    'order_count' => (int)$row->order_count,
                    'total_sales' => (float)$row->total_sales
                ];
            }
        }
        
        // Prepare data for chart
        foreach ($salesData as $day) {
            $orderCounts[] = $day['order_count'];
            $salesTotals[] = $day['total_sales'];
        }
        
        return [
            'dates' => $dates,
            'order_counts' => $orderCounts,
            'sales_totals' => $salesTotals,
            'total_sales' => array_sum($salesTotals),
            'total_orders' => array_sum($orderCounts)
        ];
    }
    
    /**
     * Get top selling products
     */
    protected function getTopProducts($limit = 5)
    {
        $db = \Config\Database::connect();
        return $db->table('order_items')
            ->select('products.id, products.name, categories.name as category_name, SUM(order_items.quantity) as total_sold, SUM(order_items.quantity * order_items.price) as revenue')
            ->join('products', 'products.id = order_items.product_id')
            ->join('categories', 'categories.id = products.category_id')
            ->join('orders', 'orders.id = order_items.order_id')
            ->where('orders.status !=', 'cancelled')
            ->groupBy('products.id, products.name, categories.name')
            ->orderBy('total_sold', 'DESC')
            ->limit($limit)
            ->get()
            ->getResultArray();
    }
    
    /**
     * Get order status counts
     */
    protected function getOrderStatuses()
    {
        $db = \Config\Database::connect();
        $result = $db->table('orders')
            ->select('status, COUNT(*) as count')
            ->groupBy('status')
            ->get()
            ->getResultArray();
        
        $statuses = [
            'pending' => 0,
            'processing' => 0,
            'completed' => 0,
            'cancelled' => 0
        ];
        
        foreach ($result as $row) {
            if (isset($statuses[$row['status']])) {
                $statuses[$row['status']] = (int)$row['count'];
            }
        }
        
        return $statuses;
    }
    
    /**
     * Get recent activities for the dashboard
     */
    public function getRecentActivities()
    {
        $activities = [];
        
        // Get recent orders
        $recentOrders = $this->orderModel->orderBy('created_at', 'DESC')->findAll(5);
        foreach ($recentOrders as $order) {
            $activities[] = [
                'type' => 'order',
                'title' => 'New Order #' . $order['order_number'],
                'description' => 'Order placed by ' . $order['customer_name'],
                'time' => $order['created_at'],
                'icon' => 'shopping-cart',
                'color' => 'primary'
            ];
        }
        
        // Get new customers
        $newCustomers = $this->customerModel->orderBy('created_at', 'DESC')->findAll(3);
        foreach ($newCustomers as $customer) {
            $activities[] = [
                'type' => 'customer',
                'title' => 'New Customer',
                'description' => $customer['name'] . ' registered',
                'time' => $customer['created_at'],
                'icon' => 'user',
                'color' => 'success'
            ];
        }
        
        // Sort activities by time (newest first)
        usort($activities, function($a, $b) {
            return strtotime($b['time']) - strtotime($a['time']);
        });
        
        // Return only the 5 most recent activities
        return array_slice($activities, 0, 5);
    }
    
    /**
     * Get sales statistics for the dashboard
     */
    public function getSalesStats()
    {
        $today = date('Y-m-d');
        $yesterday = date('Y-m-d', strtotime('-1 day'));
        $thisMonth = date('Y-m');
        $lastMonth = date('Y-m', strtotime('-1 month'));
        
        $db = \Config\Database::connect();
        
        // Today's sales
        $todaySales = $db->table('orders')
            ->select('COUNT(*) as order_count, COALESCE(SUM(total_amount), 0) as total_sales')
            ->where('DATE(created_at)', $today)
            ->where('status !=', 'cancelled')
            ->get()
            ->getRowArray();
        
        // Yesterday's sales
        $yesterdaySales = $db->table('orders')
            ->select('COUNT(*) as order_count, COALESCE(SUM(total_amount), 0) as total_sales')
            ->where('DATE(created_at)', $yesterday)
            ->where('status !=', 'cancelled')
            ->get()
            ->getRowArray();
        
        // This month's sales
        $thisMonthSales = $db->table('orders')
            ->select('COUNT(*) as order_count, COALESCE(SUM(total_amount), 0) as total_sales')
            ->where("DATE_FORMAT(created_at, '%Y-%m')", $thisMonth)
            ->where('status !=', 'cancelled')
            ->get()
            ->getRowArray();
        
        // Last month's sales
        $lastMonthSales = $db->table('orders')
            ->select('COUNT(*) as order_count, COALESCE(SUM(total_amount), 0) as total_sales')
            ->where("DATE_FORMAT(created_at, '%Y-%m')", $lastMonth)
            ->where('status !=', 'cancelled')
            ->get()
            ->getRowArray();
        
        // Calculate percentage changes
        $todayChange = $yesterdaySales['total_sales'] > 0 
            ? (($todaySales['total_sales'] - $yesterdaySales['total_sales']) / $yesterdaySales['total_sales']) * 100 
            : 0;
            
        $monthlyChange = $lastMonthSales['total_sales'] > 0 
            ? (($thisMonthSales['total_sales'] - $lastMonthSales['total_sales']) / $lastMonthSales['total_sales']) * 100 
            : 0;
        
        return [
            'today' => [
                'orders' => (int)$todaySales['order_count'],
                'sales' => (float)$todaySales['total_sales'],
                'change' => round($todayChange, 2)
            ],
            'month' => [
                'orders' => (int)$thisMonthSales['order_count'],
                'sales' => (float)$thisMonthSales['total_sales'],
                'change' => round($monthlyChange, 2)
            ]
        ];
    }
}
