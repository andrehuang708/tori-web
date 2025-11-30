<?php

namespace App\Controllers;

use App\Models\OrderModel;
use App\Models\OrderItemModel;
use App\Models\UserModel;
use App\Models\PaymentHistoryModel;

class User extends BaseController
{
    protected $orderModel;
    protected $orderItemModel;
    protected $userModel;
    protected $paymentHistoryModel;

    public function __construct()
    {
        $this->orderModel = new OrderModel();
        $this->orderItemModel = new OrderItemModel();
        $this->userModel = new UserModel();
        $this->paymentHistoryModel = new PaymentHistoryModel();
    }

    /**
     * User dashboard
     */
    public function dashboard()
    {
        // Check if user is logged in
        if (!session()->get('logged_in')) {
            return redirect()->to('login')->with('error', 'Please login to access your dashboard');
        }

        $userId = session()->get('user_id');
        $userEmail = session()->get('user_email');
        $userPhone = session()->get('user_phone');

        // Get user information
        $user = $this->userModel->find($userId);

        // Get orders by phone number (primary method)
        $orders = $this->orderModel->where('customer_phone', $userPhone)
                                  ->orderBy('created_at', 'DESC')
                                  ->findAll();

        // Also get orders by email as backup
        $emailOrders = $this->orderModel->where('customer_email', $userEmail)
                                        ->orderBy('created_at', 'DESC')
                                        ->findAll();

        // Merge and deduplicate orders
        $allOrders = [];
        $orderIds = [];

        // Add phone orders
        foreach ($orders as $order) {
            if (!in_array($order['id'], $orderIds)) {
                $orderIds[] = $order['id'];
                $allOrders[] = $order;
            }
        }

        // Add email orders (if not already added)
        foreach ($emailOrders as $order) {
            if (!in_array($order['id'], $orderIds)) {
                $orderIds[] = $order['id'];
                $allOrders[] = $order;
            }
        }

        // Get order items for each order
        foreach ($allOrders as &$order) {
            $order['items'] = $this->orderItemModel->where('order_id', $order['id'])->findAll();
        }

        $data = [
            'user' => $user,
            'orders' => $allOrders,
            'total_orders' => count($allOrders)
        ];

        return view('user/dashboard', $data);
    }

    /**
     * Order history page
     */
    public function orderHistory()
    {
        // Check if user is logged in
        if (!session()->get('logged_in')) {
            return redirect()->to('login')->with('error', 'Please login to view your order history');
        }

        $userPhone = session()->get('user_phone');
        $userEmail = session()->get('user_email');

        // Get orders by phone number (primary key)
        $orders = $this->orderModel->where('customer_phone', $userPhone)
                                  ->orderBy('created_at', 'DESC')
                                  ->findAll();

        // Also check by email as backup
        $emailOrders = $this->orderModel->where('customer_email', $userEmail)
                                        ->orderBy('created_at', 'DESC')
                                        ->findAll();

        // Merge and deduplicate
        $allOrders = [];
        $seenIds = [];

        foreach ($orders as $order) {
            if (!in_array($order['id'], $seenIds)) {
                $seenIds[] = $order['id'];
                $allOrders[] = $order;
            }
        }

        foreach ($emailOrders as $order) {
            if (!in_array($order['id'], $seenIds)) {
                $seenIds[] = $order['id'];
                $allOrders[] = $order;
            }
        }

        // Get order items for each order
        foreach ($allOrders as &$order) {
            $order['items'] = $this->orderItemModel->where('order_id', $order['id'])->findAll();
        }

        $data = [
            'orders' => $allOrders,
            'user_phone' => $userPhone
        ];

        return view('user/order_history', $data);
    }

    /**
     * View specific order details
     */
    public function viewOrder($orderId)
    {
        // Check if user is logged in
        if (!session()->get('logged_in')) {
            return redirect()->to('login')->with('error', 'Please login to view order details');
        }

        $userPhone = session()->get('user_phone');
        $userEmail = session()->get('user_email');

        // Get order and verify ownership by phone number or email
        $order = $this->orderModel->where('id', $orderId)
                                  ->groupStart()
                                      ->where('customer_phone', $userPhone)
                                      ->orWhere('customer_email', $userEmail)
                                  ->groupEnd()
                                  ->first();

        if (!$order) {
            return redirect()->to('user/order-history')->with('error', 'Order not found or access denied');
        }

        // Get order items
        $orderItems = $this->orderItemModel->where('order_id', $orderId)->findAll();

        // Get payment history
        $paymentHistory = $this->paymentHistoryModel->getPaymentHistoryByOrder($orderId);

        $data = [
            'order' => $order,
            'orderItems' => $orderItems,
            'paymentHistory' => $paymentHistory
        ];

        return view('user/order_detail', $data);
    }

    /**
     * Track order with phone verification
     */
    public function trackOrder()
    {
        if ($this->request->getMethod() === 'post') {
            $rules = [
                'phone_number' => 'required|min_length[10]|max_length[20]',
                'order_number' => 'required|min_length[5]|max_length[50]'
            ];

            if ($this->validate($rules)) {
                $phoneNumber = $this->request->getPost('phone_number');
                $orderNumber = $this->request->getPost('order_number');

                // Format phone number (remove special characters)
                $phoneNumber = preg_replace('/[^0-9]/', '', $phoneNumber);
                if (strlen($phoneNumber) > 10 && substr($phoneNumber, 0, 2) === '62') {
                    $phoneNumber = '+' . $phoneNumber;
                } elseif (strlen($phoneNumber) > 10 && substr($phoneNumber, 0, 1) === '0') {
                    $phoneNumber = '+62' . substr($phoneNumber, 1);
                } else {
                    $phoneNumber = '+' . $phoneNumber;
                }

                // Find order by order number and phone
                $order = $this->orderModel->where('order_number', $orderNumber)
                                          ->where('customer_phone', $phoneNumber)
                                          ->first();

                if ($order) {
                    // Get order items
                    $orderItems = $this->orderItemModel->where('order_id', $order['id'])->findAll();
                    
                    $data = [
                        'order' => $order,
                        'orderItems' => $orderItems,
                        'tracked_by_phone' => true
                    ];
                    
                    return view('user/order_detail', $data);
                } else {
                    return redirect()->back()->with('error', 'Order not found. Please check your order number and phone number.');
                }
            } else {
                $data['validation'] = $this->validator;
            }
        }

        return view('user/track_order');
    }

    /**
     * Upload payment proof for an order
     */
    public function uploadPayment($orderId)
    {
        // Check if user is logged in
        if (!session()->get('logged_in')) {
            return redirect()->to('login')->with('error', 'Please login to upload payment proof');
        }

        // Get order details
        $order = $this->orderModel->find($orderId);
        
        if (!$order) {
            return redirect()->back()->with('error', 'Order not found');
        }

        // Verify this order belongs to the logged-in user
        $userEmail = session()->get('user_email');
        $userPhone = session()->get('user_phone');
        
        if ($order['customer_email'] !== $userEmail && $order['customer_phone'] !== $userPhone) {
            return redirect()->back()->with('error', 'Unauthorized access to this order');
        }

        if (strtolower($this->request->getMethod()) === 'post') {
            $rules = [
                'payment_proof' => [
                    'label' => 'Payment Proof',
                    'rules' => 'uploaded[payment_proof]|max_size[payment_proof,2048]|is_image[payment_proof]|mime_in[payment_proof,image/jpg,image/jpeg,image/png,image/gif]'
                ],
                'payment_amount' => 'required|decimal',
                'payment_method' => 'required',
                'payment_date' => 'required',
                'notes' => 'max_length[500]'
            ];

            if ($this->validate($rules)) {
                $file = $this->request->getFile('payment_proof');
                
                if ($file->isValid() && !$file->hasMoved()) {
                    // Generate unique filename
                    $newName = $file->getRandomName();
                    
                    // Create directory if it doesn't exist
                    $uploadPath = WRITEPATH . 'uploads/payment_proofs/';
                    if (!is_dir($uploadPath)) {
                        mkdir($uploadPath, 0755, true);
                    }
                    
                    // Move file to upload directory
                    $file->move($uploadPath, $newName);
                    
                    // Save payment record
                    $paymentData = [
                        'order_id' => $orderId,
                        'customer_email' => $userEmail,
                        'customer_phone' => $userPhone,
                        'payment_proof_image' => $newName,
                        'payment_amount' => $this->request->getPost('payment_amount'),
                        'payment_method' => $this->request->getPost('payment_method'),
                        'payment_date' => $this->request->getPost('payment_date') . ' ' . date('H:i:s'),
                        'notes' => $this->request->getPost('notes'),
                        'status' => 'pending'
                    ];

                    $result = $this->paymentHistoryModel->insert($paymentData);
                    
                    if ($result) {
                        return redirect()->to('/user/order/' . $orderId)->with('success', 'Payment proof uploaded successfully!');
                    } else {
                        return redirect()->back()->with('error', 'Failed to upload payment proof. Please try again.');
                    }
                } else {
                    return redirect()->back()->with('error', 'File upload failed. Please try again.');
                }
            } else {
                $data['validation'] = $this->validator;
            }
        }

        $data['order'] = $order;
        $data['paymentHistory'] = $this->paymentHistoryModel->getPaymentHistoryByOrder($orderId);
        
        return view('user/upload_payment', $data);
    }

    /**
     * Serve uploaded payment proof images
     */
    public function servePaymentProof($filename)
    {
        $filePath = WRITEPATH . 'uploads/payment_proofs/' . $filename;
        
        if (!file_exists($filePath)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('File not found');
        }

        $mime = mime_content_type($filePath);
        header('Content-Type: ' . $mime);
        header('Content-Length: ' . filesize($filePath));
        readfile($filePath);
        exit;
    }

    // Helper functions for views
    public function getOrderStatusColor($status)
    {
        $colors = [
            'pending' => 'warning',
            'confirmed' => 'info',
            'processing' => 'primary',
            'shipped' => 'secondary',
            'delivered' => 'success',
            'cancelled' => 'danger'
        ];
        
        return $colors[strtolower($status)] ?? 'secondary';
    }

    public function getPaymentStatusColor($status)
    {
        $colors = [
            'pending' => 'warning',
            'paid' => 'success',
            'failed' => 'danger',
            'refunded' => 'info'
        ];
        
        return $colors[strtolower($status)] ?? 'secondary';
    }

    public function profile()
    {
        // Check if user is logged in
        if (!session()->get('logged_in')) {
            return redirect()->to('login')->with('error', 'Please login to access your profile');
        }

        $userId = session()->get('user_id');
        
        // Get user information
        $user = $this->userModel->find($userId);
        
        if (!$user) {
            return redirect()->to('user/dashboard')->with('error', 'User not found');
        }

        $data = [
            'user' => $user
        ];

        return view('user/profile', $data);
    }

    public function updateProfile()
    {
        // Check if user is logged in
        if (!session()->get('logged_in')) {
            return redirect()->to('login')->with('error', 'Please login to update your profile');
        }

        $userId = session()->get('user_id');
        
        // Get user information
        $user = $this->userModel->find($userId);
        
        if (!$user) {
            return redirect()->to('user/dashboard')->with('error', 'User not found');
        }

        // Get form data
        $firstName = $this->request->getPost('first_name');
        $lastName = $this->request->getPost('last_name');
        $currentPassword = $this->request->getPost('current_password');
        $newPassword = $this->request->getPost('new_password');
        $confirmPassword = $this->request->getPost('confirm_password');
        $emailNotifications = $this->request->getPost('email_notifications') ? 1 : 0;
        $smsNotifications = $this->request->getPost('sms_notifications') ? 1 : 0;

        // Validate input
        $rules = [
            'first_name' => 'required|min_length[2]|max_length[50]',
            'last_name' => 'required|min_length[2]|max_length[50]',
        ];

        if ($newPassword) {
            $rules['current_password'] = 'required';
            $rules['new_password'] = 'required|min_length[6]';
            $rules['confirm_password'] = 'required|matches[new_password]';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->with('errors', $this->validator->getErrors())->withInput();
        }

        // Check current password if trying to change password
        if ($newPassword) {
            // Verify current password
            if (!password_verify($currentPassword, $user['password'])) {
                return redirect()->back()->with('error', 'Current password is incorrect')->withInput();
            }
            
            // Update password
            $updateData['password'] = password_hash($newPassword, PASSWORD_DEFAULT);
        }

        // Prepare update data
        $updateData = [
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email_notifications' => $emailNotifications,
            'sms_notifications' => $smsNotifications,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        // Update user profile
        if ($this->userModel->update($userId, $updateData)) {
            // Update session with new name
            session()->set('user_name', $firstName . ' ' . $lastName);
            
            return redirect()->to('user/profile')->with('success', 'Profile updated successfully!');
        } else {
            return redirect()->back()->with('error', 'Failed to update profile')->withInput();
        }
    }

    public function getStatusIcon($status)
    {
        $icons = [
            'pending' => 'fas fa-clock',
            'confirmed' => 'fas fa-check-circle',
            'processing' => 'fas fa-cog fa-spin',
            'shipped' => 'fas fa-truck',
            'delivered' => 'fas fa-check-double',
            'cancelled' => 'fas fa-times-circle'
        ];
        
        return $icons[strtolower($status)] ?? 'fas fa-question-circle';
    }
}
