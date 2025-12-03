<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

// Order tracking routes (placed at top for priority)
$routes->get('o/(:segment)', 'Cart::trackOrderShort/$1');
$routes->get('track/order/(:num)/(:segment)', 'Cart::trackOrder/$1/$2');

// Test route for debugging
$routes->get('test-tracking', function() {
    return 'Tracking routes are working!';
});

// Auth routes
$routes->get('register', 'Auth::register');
$routes->post('register', 'Auth::register');
$routes->get('auth/register', 'Auth::register');
$routes->post('auth/register', 'Auth::register');
$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::login');
$routes->get('auth/login', 'Auth::login');
$routes->post('auth/login', 'Auth::login');
$routes->get('logout', 'Auth::logout');

// User routes (require authentication)
$routes->group('user', ['filter' => 'auth'], function($routes) {
    $routes->get('dashboard', 'User::dashboard');
    $routes->get('order-history', 'User::orderHistory');
    $routes->get('order/(:num)', 'User::viewOrder/$1');
    $routes->get('order/(:num)/upload-payment', 'User::uploadPayment/$1');
    $routes->post('order/(:num)/upload-payment', 'User::uploadPayment/$1');
    $routes->get('track-order', 'User::trackOrder');
    $routes->post('track-order', 'User::trackOrder');
    $routes->get('profile', 'User::profile');
    $routes->post('profile/update', 'User::updateProfile');
});

// Direct user routes (alternative paths)
$routes->get('orders', 'User::orderHistory', ['filter' => 'auth']);
$routes->get('orders/(:num)', 'User::viewOrder/$1', ['filter' => 'auth']);

// Products
$routes->get('products', 'Products::index');
$routes->get('products/(:segment)', 'Products::detail/$1');
// Categories
$routes->get('categories', 'Categories::index');
$routes->get('category/(:segment)', 'Categories::view/$1');
$routes->group('cart', function($routes) {
    // View cart
    $routes->get('/', 'Cart::index');
    
    // Cart actions
    $routes->post('add', 'Cart::add');
    $routes->post('update', 'Cart::update');
    $routes->get('remove/(:segment)', 'Cart::remove/$1');
    $routes->get('clear', 'Cart::clear');
    $routes->get('count', 'Cart::count');  // Add this line for cart count
    
    // Checkout process (requires authentication)
    $routes->get('checkout', 'Cart::checkout', ['filter' => 'auth']);
    $routes->post('process-checkout', 'Cart::processCheckout', ['filter' => 'auth']);
    $routes->get('success/(:num)', 'Cart::success/$1');
    
    // Order tracking
    $routes->get('track/order/(:num)/(:segment)', 'Cart::trackOrder/$1/$2');
});

// Checkout success route (alternative path)
$routes->get('checkout/success/(:num)', 'Cart::success/$1');

$routes->group('cart', function($routes) {
    // View cart
    $routes->get('/', 'Cart::index');
    
    // Cart actions
    $routes->post('add', 'Cart::add');
    $routes->post('update', 'Cart::update');
    $routes->get('remove/(:segment)', 'Cart::remove/$1');
    $routes->get('clear', 'Cart::clear');
    $routes->get('count', 'Cart::count');  // Add this line for cart count
    
    // Checkout process (requires authentication)
    $routes->get('checkout', 'Cart::checkout', ['filter' => 'auth']);
    $routes->post('process-checkout', 'Cart::processCheckout', ['filter' => 'auth']);
    $routes->get('success/(:num)', 'Cart::success/$1');
});

// Serve uploaded files
$routes->get('uploads/payment_proofs/(:segment)', 'User::servePaymentProof/$1');

// Debug Route - Remove in production
$routes->get('debug/products', 'Products::debug');

// Admin routes
$routes->group('admin', ['namespace' => 'App\Controllers\Admin'], function($routes) {
    // Root admin URL redirects to login
    $routes->get('/', 'Auth::login');
    
    // Auth routes (not filtered)
    $routes->get('login', 'Auth::login');
    $routes->post('login', 'Auth::processLogin');
    $routes->get('logout', 'Auth::logout');
    $routes->get('forgot-password', 'Auth::forgotPassword');
    $routes->post('forgot-password', 'Auth::processForgotPassword');
    $routes->get('reset-password/(:any)', 'Auth::resetPassword/$1');
    $routes->post('reset-password', 'Auth::processResetPassword');
    
    // Protected routes (require admin_auth filter)
    $routes->group('', ['filter' => 'admin_auth'], function($routes) {
        $routes->get('dashboard', 'Dashboard::index');
        $routes->get('products', 'Products::index');
        $routes->post('products', 'Products::index');
        $routes->get('products/create', 'Products::create');
        $routes->post('products/save', 'Products::save');
        $routes->get('products/edit/(:num)', 'Products::edit/$1');
        $routes->get('products/delete/(:num)', 'Products::delete/$1');
        
        // Featured Products Management
        $routes->get('products/featured', 'Products::featured', ['as' => 'admin.products.featured']);
        $routes->post('products/toggle-featured/(:num)', 'Products::toggleFeatured/$1', ['as' => 'admin.products.toggle_featured']);
        
        // Categories Management
        $routes->get('categories', 'Categories::index');
        $routes->post('categories', 'Categories::index');
        $routes->get('categories/create', 'Categories::create');
        $routes->post('categories/save', 'Categories::save');
        $routes->get('categories/edit/(:num)', 'Categories::edit/$1');
        $routes->get('categories/delete/(:num)', 'Categories::delete/$1');
        
        // Customer Management
        $routes->get('customers', 'Customers::index');
        $routes->get('customers/detail/(:num)', 'Customers::detail/$1');
        $routes->get('customers/edit/(:num)', 'Customers::edit/$1');
        $routes->post('customers/update/(:num)', 'Customers::update/$1');
        $routes->get('customers/delete/(:num)', 'Customers::delete/$1');
        
        // Order Management
        $routes->get('orders', 'Orders::index');
        $routes->get('orders/view/(:num)', 'Orders::view/$1');
        $routes->get('orders/upload-payment/(:num)', 'Orders::uploadPayment/$1');
        $routes->post('orders/upload-payment/(:num)', 'Orders::uploadPayment/$1');
        $routes->post('orders/approve-payment/(:num)', 'Orders::approvePayment/$1');
        $routes->post('orders/reject-payment/(:num)', 'Orders::rejectPayment/$1');
        $routes->post('orders/update-status/(:num)', 'Orders::updateStatus/$1');
        $routes->post('orders/update-payment-status/(:num)', 'Orders::updatePaymentStatus/$1');
        $routes->post('orders/update-item-status/(:num)', 'Orders::updateItemStatus/$1');
        $routes->get('orders/(:segment)', 'Orders::index/$1'); // Filter by status - moved to end
        $routes->get('orders/delete/(:num)', 'Orders::delete/$1');
        $routes->post('orders/export', 'Orders::export');
    }); // Close the protected routes group
}); // Close the admin group

// 404 - Keep this as the last route
$routes->set404Override(function() {
    return view('errors/html/error_404');
});
