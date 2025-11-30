<?php

namespace App\Controllers;

use App\Models\OrderModel;
use App\Models\OrderItemModel;
use CodeIgniter\Encryption\Encryption;

class Cart extends BaseController
{
    /**
     * Display the shopping cart
     */
    public function index()
    {
        $cartItems = $this->cart->contents();
        $cartSummary = $this->cart->summary();
        
        // Debug logging
        log_message('debug', 'Cart Items: ' . json_encode($cartItems));
        log_message('debug', 'Cart Summary: ' . json_encode($cartSummary));
        
        // Calculate total manually as fallback
        $totalPrice = 0;
        foreach ($cartItems as $item) {
            $totalPrice += $item['price'] * $item['quantity'];
        }
        
        $data = [
            'title' => 'Shopping Cart',
            'cartItems' => $cartItems,
            'cartSummary' => $cartSummary,
            'subtotal' => $totalPrice,
            'status' => 'pending'
        ];
        
        return view('cart/index', $data);
    }

    /**
     * Get cart item count
     */
    public function count()
    {
        try {
            $cartSummary = $this->cart->summary();
            $response = [
                'success' => true,
                'count' => $cartSummary['total_items'] ?? 0
            ];
            
            if ($this->request->isAJAX()) {
                return $this->response->setJSON($response);
            }
            
            // For non-AJAX requests, just return the count as plain text
            return (string)($cartSummary['total_items'] ?? 0);
        } catch (\Exception $e) {
            log_message('error', 'Error in Cart::count(): ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'count' => 0,
                'message' => 'Error retrieving cart count'
            ]);
        }
    }
    

    /**
     * Add item to cart
     */
    public function add()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(405)->setJSON([
                'success' => false,
                'message' => 'Method not allowed'
            ]);
        }
        
        $productId = $this->request->getPost('product_id');
        $quantity = (int) $this->request->getPost('quantity') ?: 1;
        
        // Validate product exists and is in stock
        $product = $this->productModel->find($productId);
        if (!$product || $product['stock'] < 1) {
            return $this->jsonResponse([
                'success' => false,
                'message' => 'Product not available or out of stock'
            ], 400);
        }
        
        // Add to cart
        if ($this->cart->add($productId, $quantity)) {
            return $this->jsonResponse([
                'success' => true,
                'message' => 'Product added to cart',
                'cart_summary' => $this->cart->summary()
            ]);
        } else {
            return $this->jsonResponse([
                'success' => false,
                'message' => 'Failed to add product to cart. Not enough stock.'
            ], 400);
        }
    }
    
    /**
     * Update cart item quantity
     */
    public function update()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(405)->setJSON([
                'success' => false,
                'message' => 'Method not allowed'
            ]);
        }
        
        $itemId = $this->request->getPost('item_id');
        $quantity = (int) $this->request->getPost('quantity');
        
        if ($quantity < 1) {
            return $this->jsonResponse([
                'success' => false,
                'message' => 'Invalid quantity'
            ], 400);
        }
        
        // Update cart item
        if ($this->cart->update($itemId, $quantity)) {
            return $this->jsonResponse([
                'success' => true,
                'message' => 'Cart updated',
                'cart_summary' => $this->cart->summary()
            ]);
        } else {
            return $this->jsonResponse([
                'success' => false,
                'message' => 'Failed to update cart. Not enough stock.'
            ], 400);
        }
    }
    
    /**
     * Remove item from cart
     */
    public function remove($itemId)
    {
        if ($this->cart->remove($itemId)) {
            return $this->redirectWithMessage('cart', 'Item removed from cart');
        } else {
            return $this->redirectWithMessage('cart', 'Failed to remove item from cart', 'danger');
        }
    }
    
    /**
     * Clear cart
     */
    public function clear()
    {
        $this->cart->destroy();
        return $this->redirectWithMessage('cart', 'Cart cleared');
    }
    
    /**
     * Checkout page
     */
    public function checkout()
    {
        // Check if user is logged in
        if (!session()->get('logged_in')) {
            // Store intended URL for redirect after login
            session()->set('intended_url', 'cart/checkout');
            return redirect()->to('login')->with('error', 'Please login to proceed with checkout');
        }
        
        // Check if cart is empty
        $cartItems = $this->cart->contents();
        if (empty($cartItems)) {
            return $this->redirectWithMessage('cart', 'Keranjang belanja Anda kosong', 'warning');
        }
        
        // Check product stock before checkout
        $outOfStock = [];
        foreach ($cartItems as $itemId => $item) {
            $product = $this->productModel->find($item['id']);
            if (!$product || $product['stock'] < $item['quantity']) {
                $outOfStock[] = $item['name'];
            }
        }
        
        if (!empty($outOfStock)) {
            $this->cart->destroy();
            return $this->redirectWithMessage('cart', 'Beberapa produk kehabisan stok: ' . implode(', ', $outOfStock), 'danger');
        }
        
        // Pre-fill user data from session
        $firstName = session()->get('first_name') ?? '';
        $lastName = session()->get('last_name') ?? '';
        $fullName = trim($firstName . ' ' . $lastName);
        
        $userData = [
            'name' => $fullName ?: (session()->get('user_name') ?? ''),
            'email' => session()->get('user_email') ?? '',
            'phone' => session()->get('user_phone') ?? ''
        ];
        
        $data = [
            'cart_items' => $cartItems,
            'cart_summary' => $this->cart->summary(),
            'validation' => \Config\Services::validation(),
            'user_data' => $userData
        ];
        
        return view('cart/checkout', $data);
    }
    
    /**
     * Process checkout
     */
    public function processCheckout()
    {
        // Check if user is logged in
        if (!session()->get('logged_in')) {
            return redirect()->to('login')->with('error', 'Please login to proceed with checkout');
        }
        
        // Validate form
        $rules = [
            'customer_name' => 'required|min_length[3]|max_length[100]',
            'customer_phone' => 'required|max_length[20]',
            'payment_method' => 'required|in_list[toped,shopee,direct]',
        ];
        
        // Add address validation if payment method is direct
        if ($this->request->getPost('payment_method') === 'direct') {
            $rules['address'] = 'required|min_length[10]|max_length[500]';
        }
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }
        
        // Check cart not empty
        $cartItems = $this->cart->contents();
        if (empty($cartItems)) {
            return $this->redirectWithMessage('cart', 'Keranjang belanja Anda kosong', 'warning');
        }
        
        // Verify stock again before processing
        foreach ($cartItems as $item) {
            $product = $this->productModel->find($item['id']);
            if (!$product || $product['stock'] < $item['quantity']) {
                return $this->redirectWithMessage('cart', 'Maaf, stok untuk ' . $item['name'] . ' tidak mencukupi', 'danger');
            }
        }
        
        // Start database transaction
        $db = \Config\Database::connect();
        $db->transStart();
        
        try {
            // Create order
            $orderModel = new OrderModel();
            $orderData = [
                'order_number' => 'TORI-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -4)),
                'customer_name' => $this->request->getPost('customer_name'),
                'customer_email' => session()->get('user_email'), // Use logged-in user's email
                'customer_phone' => $this->request->getPost('customer_phone'),
                'payment_method' => $this->request->getPost('payment_method'),
                'address' => $this->request->getPost('address') ?? '',
                'subtotal' => $this->cart->totalPrice(),
                'status' => 'pending',
                'created_at' => date('Y-m-d H:i:s')
            ];
            
            $orderId = $orderModel->insert($orderData);
            
            if (!$orderId) {
                throw new \RuntimeException('Gagal membuat pesanan');
            }
            
            // Add order items
            $orderItemModel = new OrderItemModel();
            foreach ($cartItems as $item) {
                $subtotal = $item['price'] * $item['quantity'];
                $orderItemData = [
                    'order_id' => $orderId,
                    'product_id' => $item['id'],
                    'product_name' => $item['name'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $subtotal
                ];
                
                if (!$orderItemModel->insert($orderItemData)) {
                    throw new \RuntimeException('Gagal menambahkan item pesanan');
                }
                
                // Update product stock
                $this->productModel->set('stock', 'stock - ' . $item['quantity'], false)
                                  ->where('id', $item['id'])
                                  ->update();
            }
            
            // Clear cart
            $this->cart->destroy();
            
            $db->transComplete();
            
            if ($db->transStatus() === false) {
                throw new \RuntimeException('Transaksi gagal diproses');
            }
            
            // Redirect to thank you page
            return redirect()->to('checkout/success/' . $orderId);
            
        } catch (\Exception $e) {
            $db->transRollback();
            $errorMessage = 'Terjadi kesalahan saat memproses pesanan. Silakan coba lagi.';
            log_message('error', 'Checkout Error: ' . $e->getMessage());
            
            // Debug: Check session before setting flashdata
            log_message('debug', 'Before setting flashdata - Session ID: ' . session_id());
            
            // Set flashdata directly to ensure it's set
            $this->session->setFlashdata('error', $errorMessage);
            
            // Debug: Check if flashdata was set
            log_message('debug', 'After setting flashdata - Error flashdata: ' . print_r($this->session->getFlashdata('error'), true));
            
            return redirect()->to('cart/checkout');
        }
    }
    
    /**
     * Generate a secure token for order tracking
     */
    protected function generateOrderToken($orderId, $customerPhone)
    {
        $encrypter = \Config\Services::encrypter();
        $data = json_encode([
            'order_id' => $orderId,
            'phone' => $customerPhone,
            'exp' => time() + (30 * 24 * 60 * 60) // 30 days expiration
        ]);
        return bin2hex($encrypter->encrypt($data));
    }
    
    /**
     * Checkout success page
     */
    public function success($orderId = null)
    {
        try {
            log_message('debug', 'Success method called with order ID: ' . $orderId);
            
            if (!$orderId) {
                log_message('error', 'No order ID provided');
                return redirect()->to('/');
            }
            
            $orderModel = new OrderModel();
            $order = $orderModel->find($orderId);
            
            if (!$order) {
                log_message('error', 'Order not found for ID: ' . $orderId);
                return $this->redirectWithMessage('/', 'Pesanan tidak ditemukan', 'danger');
            }
            
            log_message('debug', 'Order found: ' . print_r($order, true));
            
            // Generate tracking token
            try {
                $trackingToken = $this->generateOrderToken($orderId, $order['customer_phone']);
                $trackingUrl = base_url("track/order/{$orderId}/{$trackingToken}");
                log_message('debug', 'Generated tracking URL: ' . $trackingUrl);
            } catch (\Exception $e) {
                log_message('error', 'Error generating tracking token: ' . $e->getMessage());
                $trackingUrl = base_url("track/order/{$orderId}");
            }
            
            $orderItemModel = new OrderItemModel();
            $orderItems = $orderItemModel->where('order_id', $orderId)->findAll();
            log_message('debug', 'Found ' . count($orderItems) . ' order items');
            
            $data = [
                'order' => $order,
                'orderItems' => $orderItems,
                'trackingUrl' => $trackingUrl
            ];
            
            log_message('debug', 'Rendering success view with data: ' . print_r($data, true));
            return view('checkout/success', $data);
            
        } catch (\Exception $e) {
            log_message('error', 'Error in success method: ' . $e->getMessage());
            log_message('error', $e->getTraceAsString());
            return $this->redirectWithMessage('/', 'Terjadi kesalahan saat memuat halaman konfirmasi pesanan', 'danger');
        }
    }
    
    /**
     * Track order without login
     */
    public function trackOrder($orderId, $token)
    {
        try {
            $encrypter = \Config\Services::encrypter();
            $data = json_decode($encrypter->decrypt(hex2bin($token)), true);
            
            // Verify token is not expired and matches the order
            if ($data['exp'] < time() || $data['order_id'] != $orderId) {
                throw new \Exception('Invalid or expired tracking link');
            }
            
            $orderModel = new OrderModel();
            $order = $orderModel->find($orderId);
            
            if (!$order || $order['customer_phone'] != $data['phone']) {
                throw new \Exception('Order not found');
            }
            
            $orderItemModel = new OrderItemModel();
            $orderItems = $orderItemModel->where('order_id', $orderId)->findAll();
            
            $data = [
                'order' => $order,
                'orderItems' => $orderItems,
                'isGuestView' => true
            ];
            
            return $this->render('orders/view', $data);
            
        } catch (\Exception $e) {
            return $this->redirectWithMessage('/', 'Link tracking tidak valid atau sudah kadaluarsa', 'danger');
        }
    }
    
    /**
     * Handle shortened tracking URLs
     */
    public function trackOrderShort($code)
    {
        try {
            // Decode the tracking code
            $data = base64_decode($code);
            
            // Validate the format: track:order_id:hash
            if (!preg_match('/^track:(\d+):([a-f0-9]+)$/', $data, $matches)) {
                throw new \Exception('Invalid tracking code');
            }
            
            $orderId = $matches[1];
            $hash = $matches[2];
            
            // Verify the hash
            $orderModel = new OrderModel();
            $order = $orderModel->find($orderId);
            
            if (!$order) {
                throw new \Exception('Order not found');
            }
            
            $expectedHash = substr(md5($order['customer_phone'] . env('encryption.key')), 0, 10);
            
            if ($hash !== $expectedHash) {
                throw new \Exception('Invalid tracking code');
            }
            
            // If valid, redirect to the full tracking URL
            $token = $this->generateOrderToken($orderId, $order['customer_phone']);
            return redirect()->to("track/order/{$orderId}/{$token}");
            
        } catch (\Exception $e) {
            return $this->redirectWithMessage('/', 'Link tracking tidak valid atau sudah kadaluarsa', 'danger');
        }
    }
    
    /**
     * Send order confirmation email
     */
    protected function sendOrderConfirmationEmail($orderId)
    {
        $orderModel = new OrderModel();
        $order = $orderModel->getOrderWithItems($orderId);
        
        if (!$order) {
            return false;
        }
        
        $email = \Config\Services::email();
        
        $email->setTo($order['customer_email']);
        $email->setFrom(getenv('email_from') ?: 'noreply@example.com', getenv('site_name') ?: 'E-Commerce Store');
        $email->setSubject('Order Confirmation - #' . $order['order_number']);
        
        $data = ['order' => $order];
        $message = view('emails/order_confirmation', $data);
        
        $email->setMessage($message);
        
        return $email->send();
    }
}
