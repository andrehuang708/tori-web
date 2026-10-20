<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\OrderModel;
use App\Models\OrderItemModel;

class Orders extends BaseController
{
    protected $orderModel;
    protected $orderItemModel;
    protected $perPage = 15;
    
    public function __construct()
    {
        $this->orderModel = new OrderModel();
        $this->orderItemModel = new OrderItemModel();
    }   
    
    /**
     * List all orders
     */
    public function index($status = null, $page = 1)
    {
        $where = [];
        if ($status && in_array($status, ['pending', 'processing', 'completed', 'cancelled'])) {
            $where['status'] = $status;
        }
        
        $result = $this->orderModel->getOrdersWithItems($this->perPage, $page, $status);
        
        $data = [
            'orders' => $result['orders'],
            'status' => $status,
            'pager' => $result['pager'],
            'orderModel' => $this->orderModel  // Add orderModel to the view data
        ];
        
        return view('admin/orders/index', $data);
    }
    
    /**
     * View order details
     */
    public function view($id = null)
    {
        if ($id === null) {
            return redirect()->to('admin/orders')->with('error', 'Order ID is required');
        }
        
        $order = $this->orderModel->getOrderWithItems($id);
        
        if (!$order) {
            return redirect()->to('admin/orders')->with('error', 'Order not found');
        }
        
        // Get payment history for this order
        $db = \Config\Database::connect();
        $paymentHistory = $db->table('payment_history')
                            ->where('order_id', $id)
                            ->orderBy('created_at', 'DESC')
                            ->get()
                            ->getResultArray();
        
        $data = [
            'order' => $order,
            'paymentHistory' => $paymentHistory
        ];
        
        return view('admin/orders/view', $data);
    }
    
    /**
     * Update payment status
     */
    public function updatePaymentStatus($id = null)
    {
        if ($id === null) {
            return $this->jsonResponse([
                'success' => false,
                'message' => 'Order ID is required'
            ], 400);
        }
        
        $status = $this->request->getPost('status');
        $validStatuses = ['pending', 'dp_accepted', 'full_paid'];
        
        if (!in_array($status, $validStatuses)) {
            return $this->jsonResponse([
                'success' => false,
                'message' => 'Invalid status'
            ], 400);
        }
        
        $order = $this->orderModel->find($id);
        
        if (!$order) {
            return $this->jsonResponse([
                'success' => false,
                'message' => 'Order not found'
            ], 404);
        }
        
        // Update payment status
        if ($this->orderModel->update($id, ['payment_status' => $status])) {
            // If payment status is DP Accepted or Full Paid, update order status to processing
            if (in_array($status, ['dp_accepted', 'full_paid'])) {
                $this->orderModel->update($id, ['status' => 'processing']);
            }
            
            return $this->jsonResponse([
                'success' => true,
                'message' => 'Payment status updated successfully',
                'status' => $status,
                'status_text' => $this->formatPaymentStatus($status),
                'order_status_updated' => in_array($status, ['dp_accepted', 'full_paid']),
                'new_order_status' => in_array($status, ['dp_accepted', 'full_paid']) ? 'processing' : null
            ]);
        } else {
            return $this->jsonResponse([
                'success' => false,
                'message' => 'Failed to update payment status'
            ], 500);
        }
    }
    
    /**
     * Format payment status for display
     */
    protected function formatPaymentStatus($status)
    {
        $statuses = [
            'pending' => 'Pending',
            'dp_accepted' => 'DP Accepted',
            'full_paid' => 'Full Paid'
        ];
        
        return $statuses[$status] ?? ucfirst($status);
    }
    
    /**
     * Update order item status
     */
    public function updateItemStatus($id = null)
    {
        if ($id === null) {
            return $this->jsonResponse([
                'success' => false,
                'message' => 'Order item ID is required'
            ], 400);
        }
        
        $status = $this->request->getPost('status');
        $validStatuses = ['not fulfilled', 'shipped'];
        
        if (!in_array($status, $validStatuses)) {
            return $this->jsonResponse([
                'success' => false,
                'message' => 'Invalid status'
            ], 400);
        }
        
        $orderItem = $this->orderItemModel->find($id);
        
        if (!$orderItem) {
            return $this->jsonResponse([
                'success' => false,
                'message' => 'Order item not found'
            ], 404);
        }
        
        // Update order item status
        if ($this->orderItemModel->update($id, ['order_items_status' => $status])) {
            // Check if all items in this order are shipped
            $orderCompleted = $this->checkIfOrderCompleted($orderItem['order_id']);
            
            return $this->jsonResponse([
                'success' => true,
                'message' => 'Item status updated successfully',
                'status' => $status,
                'order_completed' => $orderCompleted
            ]);
        } else {
            return $this->jsonResponse([
                'success' => false,
                'message' => 'Failed to update item status'
            ], 500);
        }
    }
    
    /**
     * Check if all items in an order are shipped and update order status to completed
     */
    protected function checkIfOrderCompleted($orderId)
    {
        // Get all items for this order
        $orderItems = $this->orderItemModel->where('order_id', $orderId)->findAll();
        
        if (empty($orderItems)) {
            return false;
        }
        
        // Check if all items are shipped
        $allShipped = true;
        foreach ($orderItems as $item) {
            if ($item['order_items_status'] !== 'shipped') {
                $allShipped = false;
                break;
            }
        }
        
        // If all items are shipped, update order status to completed
        if ($allShipped) {
            $this->orderModel->update($orderId, ['status' => 'completed']);
            return true;
        }
        
        return false;
    }
    
    /**
     * Update order status
     */
    public function updateStatus($id = null)
    {
        if ($id === null) {
            return $this->jsonResponse([
                'success' => false,
                'message' => 'Order ID is required'
            ], 400);
        }
        
        $status = $this->request->getPost('status');
        $validStatuses = ['pending', 'processing', 'completed', 'cancelled'];
        
        if (!in_array($status, $validStatuses)) {
            return $this->jsonResponse([
                'success' => false,
                'message' => 'Invalid status'
            ], 400);
        }
        
        $order = $this->orderModel->find($id);
        
        if (!$order) {
            return $this->jsonResponse([
                'success' => false,
                'message' => 'Order not found'
            ], 404);
        }
        
        // If cancelling an order, restore product stock
        if ($status === 'cancelled' && $order['status'] !== 'cancelled') {
            $this->restoreOrderItemsStock($id);
        }
        
        // If order was cancelled and is being reactivated, reduce stock again
        if ($order['status'] === 'cancelled' && $status !== 'cancelled') {
            if (!$this->reduceOrderItemsStock($id)) {
                return $this->jsonResponse([
                    'success' => false,
                    'message' => 'Insufficient stock for some items. Cannot update status.'
                ], 400);
            }
        }
        
        // Update order status
        if ($this->orderModel->update($id, ['status' => $status])) {
            // Send status update email to customer
            $this->sendStatusUpdateEmail($id, $status);
            
            return $this->jsonResponse([
                'success' => true,
                'message' => 'Order status updated successfully',
                'status' => $status,
                'status_text' => ucfirst($status)
            ]);
        } else {
            return $this->jsonResponse([
                'success' => false,
                'message' => 'Failed to update order status'
            ], 500);
        }
    }
    
    /**
     * Delete order
     */
    public function delete($id = null)
    {
        // Destructive action: POST only (see routes). Reject GET/crawlers.
        if (strtolower($this->request->getMethod()) !== 'post') {
            return redirect()->to('admin/orders')->with('error', 'Method not allowed');
        }

        if ($id === null) {
            return redirect()->to('admin/orders')->with('error', 'Order ID is required');
        }
        
        $order = $this->orderModel->find($id);
        
        if (!$order) {
            return redirect()->to('admin/orders')->with('error', 'Order not found');
        }
        
        // Only allow deletion of cancelled orders
        if ($order['status'] !== 'cancelled') {
            return redirect()->to('admin/orders')->with('error', 'Only cancelled orders can be deleted');
        }
        
        // Start database transaction
        $db = \Config\Database::connect();
        $db->transStart();
        
        try {
            // Delete order items
            $this->orderItemModel->where('order_id', $id)->delete();
            
            // Delete order
            $this->orderModel->delete($id);
            
            // Commit transaction
            $db->transCommit();
            
            return redirect()->to('admin/orders')->with('success', 'Order deleted successfully');
            
        } catch (\Exception $e) {
            // Rollback transaction on error
            $db->transRollback();
            log_message('error', 'Order deletion error: ' . $e->getMessage());
            return redirect()->to('admin/orders')->with('error', 'Failed to delete order');
        }
    }
    
    /**
     * Restore product stock when an order is cancelled
     */
    protected function restoreOrderItemsStock($orderId)
    {
        $orderItems = $this->orderItemModel->where('order_id', $orderId)->findAll();
        $productModel = new \App\Models\ProductModel();
        
        foreach ($orderItems as $item) {
            $productModel->updateStock($item['product_id'], $item['quantity'], 'increase');
        }
    }
    
    /**
     * Reduce product stock when an order is reactivated from cancelled status
     */
    protected function reduceOrderItemsStock($orderId)
    {
        $orderItems = $this->orderItemModel->where('order_id', $orderId)->findAll();
        $productModel = new \App\Models\ProductModel();
        
        // First, check if all items have enough stock
        foreach ($orderItems as $item) {
            $product = $productModel->find($item['product_id']);
            if (!$product || $product['stock'] < $item['quantity']) {
                return false; // Not enough stock
            }
        }
        
        // If we get here, all items have enough stock, so reduce the stock
        foreach ($orderItems as $item) {
            $productModel->updateStock($item['product_id'], $item['quantity'], 'decrease');
        }
        
        return true;
    }
    
    /**
     * Send order status update email to customer
     */
    protected function sendStatusUpdateEmail($orderId, $status)
    {
        try {
            $order = $this->orderModel->getOrderWithItems($orderId);
            
            if (!$order || empty($order['customer_email'])) {
                return false;
            }
            
            $email = \Config\Services::email();
            
            $email->setTo($order['customer_email']);
            $email->setFrom(getenv('email_from') ?: 'noreply@example.com', getenv('site_name') ?: 'E-Commerce Store');
            $email->setSubject('Order #' . $order['order_number'] . ' Status Update');
            
            $data = [
                'order' => $order,
                'status' => $status
            ];
            
            $message = view('emails/order_status_update', $data);
            $email->setMessage($message);
            
            // Try to send email, but don't fail if email is not configured
            $result = $email->send();
            
            if (!$result) {
                // Log email error but don't fail the operation
                log_message('error', 'Failed to send order status update email: ' . $email->printDebugger());
            }
            
            return $result;
            
        } catch (\Exception $e) {
            // Log email error but don't fail the operation
            log_message('error', 'Email sending error: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Export orders to CSV
     */
    public function export($status = null, $startDate = null, $endDate = null)
    {
        $where = [];
        
        // Filter by status
        if ($status && in_array($status, ['pending', 'processing', 'completed', 'cancelled'])) {
            $where['status'] = $status;
        }
        
        // Filter by date range
        if ($startDate && $endDate) {
            $where['DATE(created_at) >='] = $startDate;
            $where['DATE(created_at) <='] = $endDate;
        }
        
        $orders = $this->orderModel->where($where)
                                 ->orderBy('created_at', 'DESC')
                                 ->findAll();
        
        if (empty($orders)) {
            return redirect()->back()->with('error', 'No orders found matching the criteria');
        }
        
        // Set CSV headers
        $filename = 'orders_' . date('Y-m-d') . '.csv';
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        
        // Add CSV headers
        fputcsv($output, [
            'Order Number',
            'Date',
            'Customer Name',
            'Customer Email',
            'Customer Phone',
            'Status',
            'Payment Method',
            'Subtotal',
            'Shipping',
            'Tax',
            'Total',
            'Items'
        ]);
        
        // Add order data
        foreach ($orders as $order) {
            $orderItems = $this->orderItemModel->where('order_id', $order['id'])->findAll();
            $items = [];
            
            foreach ($orderItems as $item) {
                $items[] = $item['product_name'] . ' x' . $item['quantity'] . ' @ ' . number_format($item['price'], 2);
            }
            
            fputcsv($output, [
                $order['order_number'],
                $order['created_at'],
                $order['customer_name'],
                $order['customer_email'],
                $order['customer_phone'] ?? '',
                ucfirst($order['status']),
                ucwords(str_replace('_', ' ', $order['payment_method'])),
                number_format($order['total_amount'] - ($order['shipping_cost'] + $order['tax_amount']), 2),
                number_format($order['shipping_cost'], 2),
                number_format($order['tax_amount'], 2),
                number_format($order['total_amount'], 2),
                implode("\n", $items)
            ]);
        }
        
        fclose($output);
        exit();
    }
    
    /**
     * Upload payment proof
     */
    public function uploadPayment($id = null)
    {
        log_message('debug', 'Upload payment method called with ID: ' . $id);
        log_message('debug', 'Request method: ' . $this->request->getMethod());
        
        // Set timezone to Indonesia/Jakarta
        date_default_timezone_set('Asia/Jakarta');
        
        if ($id === null) {
            log_message('error', 'Upload payment - No ID provided');
            return redirect()->to('admin/orders')->with('error', 'Order ID is required');
        }
        
        $order = $this->orderModel->find($id);
        if (!$order) {
            log_message('error', 'Upload payment - Order not found: ' . $id);
            return redirect()->to('admin/orders')->with('error', 'Order not found');
        }
        
        // Handle POST request
        if ($this->request->getMethod() === 'POST') {
            log_message('debug', 'Upload payment - POST request received');
            
            $paymentProof = $this->request->getFile('payment_proof');
            $paymentMethod = $this->request->getPost('payment_method');
            $paymentAmount = $this->request->getPost('payment_amount');
            $paymentDate = $this->request->getPost('payment_date');
            $notes = $this->request->getPost('notes');
            
            // Check if this is an AJAX request
            $isAjax = $this->request->getHeaderLine('X-Requested-With') === 'XMLHttpRequest';
            
            log_message('debug', 'Upload payment - AJAX: ' . ($isAjax ? 'Yes' : 'No'));
            log_message('debug', 'Upload payment - Files: ' . print_r($_FILES, true));
            
            // Always return JSON for POST requests (our embedded form)
            header('Content-Type: application/json');
            
            // Validate input
            if (!$paymentProof || !$paymentProof->isValid()) {
                log_message('error', 'Upload payment - Invalid file: ' . ($paymentProof ? $paymentProof->getError() : 'No file'));
                return $this->jsonResponse([
                    'success' => false,
                    'message' => 'Please upload a valid payment proof image'
                ], 400);
            }
            
            if (!$paymentMethod || !$paymentAmount || !$paymentDate) {
                log_message('error', 'Upload payment - Missing fields: method=' . $paymentMethod . ' amount=' . $paymentAmount . ' date=' . $paymentDate);
                return $this->jsonResponse([
                    'success' => false,
                    'message' => 'Please fill all required fields'
                ], 400);
            }
            
            // Upload file
            if ($paymentProof->isValid() && !$paymentProof->hasMoved()) {
                $newName = $paymentProof->getRandomName();
                
                log_message('debug', 'Upload payment - Moving file to: uploads/payments/' . $newName);
                
                if (!$paymentProof->move('uploads/payment_proofs/', $newName)) {
                    log_message('error', 'Upload payment - Failed to move file');
                    return $this->jsonResponse([
                        'success' => false,
                        'message' => 'Failed to upload file'
                    ], 500);
                }
                
                // Save payment record
                $paymentData = [
                    'order_id' => $id,
                    'payment_proof_image' => $newName,
                    'payment_method' => $paymentMethod,
                    'payment_amount' => $paymentAmount,
                    'payment_date' => date('Y-m-d H:i:s'), // Current datetime
                    'notes' => $notes,
                    'status' => 'verified', // Set status to verified
                    'verified_by' => session()->get('admin_id'), // Admin user ID from session
                    'verified_at' => date('Y-m-d H:i:s'), // Current datetime
                    'created_at' => date('Y-m-d H:i:s'), // Current datetime
                    'updated_at' => date('Y-m-d H:i:s')  // Current datetime
                ];
                
                $db = \Config\Database::connect();
                $result = $db->table('payment_history')->insert($paymentData);
                
                log_message('debug', 'Upload payment - Database insert result: ' . ($result ? 'Success' : 'Failed'));
                
                return $this->jsonResponse([
                    'success' => true,
                    'message' => 'Payment proof uploaded successfully'
                ]);
            } else {
                log_message('error', 'Upload payment - File validation failed: ' . $paymentProof->getErrorString());
                return $this->jsonResponse([
                    'success' => false,
                    'message' => 'File validation failed: ' . $paymentProof->getErrorString()
                ], 400);
            }
        }
        
        // For GET requests, return the view (this shouldn't happen with our current setup)
        // But keeping it for backward compatibility
        $db = \Config\Database::connect();
        $paymentHistory = $db->table('payment_history')
                            ->where('order_id', $id)
                            ->orderBy('created_at', 'DESC')
                            ->get()
                            ->getResultArray();
        
        $data = [
            'order' => $order,
            'paymentHistory' => $paymentHistory
        ];
        
        return view('admin/orders/upload_payment', $data);
    }
    
    /**
     * Approve payment
     */
    public function approvePayment($id = null)
    {
        if ($id === null) {
            return $this->jsonResponse([
                'success' => false,
                'message' => 'Payment ID is required'
            ], 400);
        }
        
        $db = \Config\Database::connect();
        $payment = $db->table('payment_history')->where('id', $id)->get()->getRowArray();
        
        if (!$payment) {
            return $this->jsonResponse([
                'success' => false,
                'message' => 'Payment not found'
            ], 404);
        }
        
        // Update payment status
        $db->table('payment_history')->where('id', $id)->update(['status' => 'approved']);
        
        // Update order payment status
        $this->orderModel->update($payment['order_id'], ['payment_status' => 'full_paid']);
        
        return $this->jsonResponse([
            'success' => true,
            'message' => 'Payment approved successfully'
        ]);
    }
    
    /**
     * Reject payment
     */
    public function rejectPayment($id = null)
    {
        if ($id === null) {
            return $this->jsonResponse([
                'success' => false,
                'message' => 'Payment ID is required'
            ], 400);
        }
        
        $db = \Config\Database::connect();
        $payment = $db->table('payment_history')->where('id', $id)->get()->getRowArray();
        
        if (!$payment) {
            return $this->jsonResponse([
                'success' => false,
                'message' => 'Payment not found'
            ], 404);
        }
        
        // Update payment status
        $db->table('payment_history')->where('id', $id)->update(['status' => 'rejected']);
        
        return $this->jsonResponse([
            'success' => true,
            'message' => 'Payment rejected successfully'
        ]);
    }
    
    /**
     * View all order items from order_items table
     */
    public function orderItems()
    {
        $db = \Config\Database::connect();
        
        // Get all order items (DataTables will handle pagination)
        $builder = $db->table('order_items oi');
        $builder->select('oi.*, o.order_number, o.customer_name, o.customer_email, o.customer_phone, p.po_price_desc, o.status as order_status, o.created_at as order_date');
        $builder->join('orders o', 'oi.order_id = o.id', 'left');
        $builder->join('products p', 'oi.product_id = p.id', 'left');
        $builder->orderBy('oi.id', 'DESC');
        
        $orderItems = $builder->get()->getResultArray();
        
        $data = [
            'orderItems' => $orderItems
        ];
        
        return view('admin/orders/order_items', $data);
    }
    
    /**
     * JSON response helper
     */
    protected function jsonResponse($data, $statusCode = 200)
    {
        return $this->response
            ->setStatusCode($statusCode)
            ->setJSON($data);
    }
}
