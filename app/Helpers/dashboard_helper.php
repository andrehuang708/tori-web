<?php

if (!function_exists('getUserDashboardData')) {
    /**
     * Get user dashboard data for integration across the site
     */
    function getUserDashboardData() {
        $session = \Config\Services::session();
        
        if (!$session->get('logged_in')) {
            return [
                'total_orders' => 0,
                'pending_orders' => 0,
                'completed_orders' => 0,
                'total_spent' => 'Rp 0',
                'recent_orders' => []
            ];
        }
        
        $orderModel = new \App\Models\OrderModel();
        $userPhone = $session->get('user_phone');
        $userEmail = $session->get('user_email');
        
        // Get user's orders
        $orders = $orderModel->groupStart()
            ->where('customer_phone', $userPhone)
            ->orWhere('customer_email', $userEmail)
            ->groupEnd()
            ->orderBy('created_at', 'DESC')
            ->findAll(5); // Get recent 5 orders
        
        $totalOrders = count($orders);
        $pendingOrders = count(array_filter($orders, fn($o) => in_array($o['status'], ['pending', 'processing'])));
        $completedOrders = count(array_filter($orders, fn($o) => $o['status'] === 'delivered'));
        
        // Calculate total spent
        $totalSpent = 0;
        foreach ($orders as $order) {
            $totalSpent += $order['subtotal'];
        }
        
        return [
            'total_orders' => $totalOrders,
            'pending_orders' => $pendingOrders,
            'completed_orders' => $completedOrders,
            'total_spent' => 'Rp ' . number_format($totalSpent, 0, ',', '.'),
            'recent_orders' => $orders
        ];
    }
}

if (!function_exists('renderDashboardWidget')) {
    /**
     * Render dashboard widget for integration in layouts
     */
    function renderDashboardWidget($position = 'header') {
        $data = getUserDashboardData();
        $session = \Config\Services::session();
        
        if (!$session->get('logged_in') || $data['total_orders'] === 0) {
            return '';
        }
        
        if ($position === 'header') {
            return view('partials/dashboard_header_widget', $data);
        } elseif ($position === 'sidebar') {
            return view('partials/dashboard_sidebar_widget', $data);
        }
        
        return '';
    }
}
