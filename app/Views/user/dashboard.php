<?= $this->extend('layouts/main') ?>

<?= $this->section('styles') ?>
<style>
    .dashboard-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 2rem 0;
        margin: -20px -15px 20px -15px;
    }
    .stats-card {
        transition: transform 0.2s;
    }
    .stats-card:hover {
        transform: translateY(-5px);
    }
    .order-card {
        transition: box-shadow 0.2s;
        margin-bottom: 1rem;
    }
    .order-card:hover {
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    .status-badge {
        font-size: 0.8rem;
        padding: 0.25rem 0.75rem;
    }
    .sidebar {
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    .nav-link {
        color: #6c757d;
        border-radius: 8px;
        margin: 0.25rem 0;
    }
    .nav-link:hover, .nav-link.active {
        background-color: #f8f9fa;
        color: #007bff;
    }
    .nav-link i {
        width: 20px;
    }
    .empty-state {
        text-align: center;
        padding: 3rem;
    }
    .empty-state i {
        font-size: 4rem;
        color: #dee2e6;
        margin-bottom: 1rem;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container py-4">
    <!-- Dashboard Header -->
    <div class="dashboard-header rounded mb-4">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h1 class="mb-0">Welcome back</h1>
                    <p class="mb-0 opacity-75">Manage your orders and account settings</p>
                </div>
                <div class="col-md-6 text-end">
                    <div class="d-inline-flex align-items-center">
                        <i class="fas fa-user-circle fa-3x me-3"></i>
                        <div>
                            <h5 class="mb-0"><?= esc(session()->get('user_name') ?? $user['email']) ?></h5>
                            <small class="opacity-75">Member since <?= date('M Y', strtotime($user['created_at'])) ?></small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3">
            <div class="sidebar">
                <div class="card-body">
                    <h6 class="text-muted mb-3">Account Menu</h6>
                    <nav class="nav nav-pills flex-column">
                        <a href="<?= site_url('user/dashboard') ?>" class="nav-link active">
                            <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                        </a>
                        <a href="<?= site_url('user/order-history') ?>" class="nav-link">
                            <i class="fas fa-list me-2"></i>Order History
                        </a>
                         <a href="<?= site_url('user/address') ?>" class="nav-link">
                            <i class="fas fa-map-marker-alt me-2"></i>Address Book
                        </a>
                        <a href="<?= site_url('user/track-order') ?>" class="nav-link">
                            <i class="fas fa-search me-2"></i>Track Order
                        </a>
                        <a href="<?= site_url('user/profile') ?>" class="nav-link">
                            <i class="fas fa-user me-2"></i>Profile Settings
                        </a>
                       
                        <hr>
                        <a href="<?= site_url('cart') ?>" class="nav-link">
                            <i class="fas fa-shopping-cart me-2"></i>Shopping Cart
                        </a>
                        <a href="<?= site_url('/') ?>" class="nav-link">
                            <i class="fas fa-home me-2"></i>Back to Shop
                        </a>
                    </nav>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-md-9">
            <!-- Recent Orders -->
            <div class="card">
                <div class="card-header bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-clock me-2"></i>Recent Orders
                        </h5>
                        <a href="<?= site_url('user/order-history') ?>" class="btn btn-sm btn-outline-primary">
                            View All
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <?php if (empty($orders)): ?>
                        <div class="empty-state">
                            <i class="fas fa-shopping-bag fa-3x text-muted mb-3"></i>
                            <p class="text-muted">You haven't placed any orders yet.</p>
                            <a href="<?= site_url('/') ?>" class="btn btn-primary">
                                <i class="fas fa-shopping-cart me-2"></i>Start Shopping
                            </a>
                        </div>
                    <?php else: ?>
                        <?php $recentOrders = array_slice($orders, 0, 5); ?>
                        <?php foreach ($recentOrders as $order): ?>
                            <div class="order-card card border">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-md-4">
                                            <h6 class="mb-1"><?= esc($order['order_number']) ?></h6>
                                            <small class="text-muted">
                                                <i class="fas fa-calendar me-1"></i>
                                                <?= date('M d, Y H:i', strtotime($order['created_at'])) ?>
                                            </small>
                                        </div>
                                        <div class="col-md-3">
                                            <span class="status-badge badge bg-<?= getOrderStatusColor($order['status']) ?>">
                                                <?= ucfirst($order['status']) ?>
                                            </span>
                                        </div>
                                        <div class="col-md-3">
                                            <h6 class="mb-0">Rp <?= number_format($order['subtotal'], 0, ',', '.') ?></h6>
                                            <small class="text-muted"><?= count($order['items']) ?> items</small>
                                        </div>
                                        <div class="col-md-2 text-end">
                                            <a href="<?= site_url('user/order/' . $order['id']) ?>" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye me-1"></i>View
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
// Add any dashboard-specific JavaScript here
</script>
<?= $this->endSection() ?>

<?php
// Helper function for status colors
function getOrderStatusColor($status) {
    $colors = [
        'pending' => 'warning',
        'processing' => 'info',
        'completed' => 'success',
        'delivered' => 'success',
        'cancelled' => 'danger'
    ];
    return $colors[$status] ?? 'secondary';
}
?>
