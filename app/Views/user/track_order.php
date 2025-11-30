<?= $this->extend('layouts/main') ?>

<?= $this->section('styles') ?>
<style>
    .page-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 2rem 0;
        margin: -20px -15px 20px -15px;
    }
    .track-form {
        background-color: white;
        border-radius: 15px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        padding: 2rem;
    }
    .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }
    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
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
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container py-4">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3">
            <div class="sidebar">
                <div class="p-3">
                    <h6 class="text-muted mb-3">Account Menu</h6>
                    <nav class="nav nav-pills flex-column">
                        <a href="<?= site_url('user/order-history') ?>" class="nav-link">
                            <i class="fas fa-list me-2"></i>Order History
                        </a>
                        <a href="<?= site_url('user/track-order') ?>" class="nav-link active">
                            <i class="fas fa-search me-2"></i>Track Order
                        </a>
                        <a href="<?= site_url('user/profile') ?>" class="nav-link">
                            <i class="fas fa-user me-2"></i>Profile Settings
                        </a>
                    
                        <a href="<?= site_url('cart') ?>" class="nav-link">
                            <i class="fas fa-shopping-cart me-2"></i>Shopping Cart
                        </a>
                        <a href="<?= site_url('/') ?>" class="nav-link">
                            <i class="fas fa-home me-2"></i>Back to Shop
                        </a>
                          <a href="<?= site_url('logout') ?>" class="nav-link text-danger">
                            <i class="fas fa-sign-out-alt me-2"></i>Log Out
                        </a>
                        <hr>
                        
                    </nav>
                </div>
            </div>
        </div>
        <!-- Main Content -->
        <div class="col-md-9">
            <!-- Track Order Form -->
            <div class="track-form">
                <div class="text-center mb-4">
                    <i class="fas fa-search fa-3x text-primary mb-3"></i>
                    <h3>Track Your Order</h3>
                    <p class="text-muted">Enter your order number and phone number to check your order status</p>
                </div>

                <form method="post" action="<?= site_url('user/track-order') ?>">
                    <?= csrf_field() ?>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="order_number" class="form-label">
                                <i class="fas fa-hashtag me-2"></i>Order Number
                            </label>
                            <input type="text" class="form-control form-control-lg" id="order_number" 
                                   name="order_number" placeholder="Enter your order number" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="phone_number" class="form-label">
                                <i class="fas fa-phone me-2"></i>Phone Number
                            </label>
                            <input type="tel" class="form-control form-control-lg" id="phone_number" 
                                   name="phone_number" placeholder="Enter your phone number" required>
                        </div>
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-primary btn-lg px-5">
                            <i class="fas fa-search me-2"></i>Track Order
                        </button>
                    </div>
                </form>

                <?php if (isset($order)): ?>
                    <div class="mt-5">
                        <h4 class="mb-3">
                            <i class="fas fa-info-circle me-2"></i>Order Details
                        </h4>
                        <div class="card border-primary">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>Order Number:</strong> <?= esc($order['order_number']) ?></p>
                                        <p><strong>Status:</strong> 
                                            <span class="badge bg-<?= getOrderStatusColor($order['status']) ?>">
                                                <?= ucfirst($order['status']) ?>
                                            </span>
                                        </p>
                                        <p><strong>Order Date:</strong> <?= date('M d, Y H:i', strtotime($order['created_at'])) ?></p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Total Amount:</strong> Rp <?= number_format($order['subtotal'], 0, ',', '.') ?></p>
                                        <p><strong>Payment Method:</strong> <?= ucfirst($order['payment_method']) ?></p>
                                        <p><strong>Customer Phone:</strong> <?= esc($order['customer_phone']) ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if (isset($error)): ?>
                    <div class="alert alert-danger mt-4">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <?= esc($error) ?>
                    </div>
                <?php endif; ?>

                <!-- Additional Information -->
                <div class="row mt-5">
                    <div class="col-md-4">
                        <div class="card text-center h-100">
                            <div class="card-body">
                                <i class="fas fa-phone-alt fa-2x text-primary mb-3"></i>
                                <h6 class="card-title">Need Help?</h6>
                                <p class="card-text small">Contact our customer support for assistance with your order.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-center h-100">
                            <div class="card-body">
                                <i class="fas fa-clock fa-2x text-warning mb-3"></i>
                                <h6 class="card-title">Order Status</h6>
                                <p class="card-text small">Check real-time status updates for your order.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-center h-100">
                            <div class="card-body">
                                <i class="fas fa-shipping-fast fa-2x text-success mb-3"></i>
                                <h6 class="card-title">Fast Delivery</h6>
                                <p class="card-text small">Track your order from processing to delivery.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
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
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Format phone number as user types
        document.getElementById('phone_number').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.startsWith('0')) {
                value = '62' + value.substring(1);
            } else if (!value.startsWith('62') && value.length > 0) {
                value = '62' + value;
            }
            
            if (value.length > 0) {
                // Format as +62 812-3456-7890
                let formatted = '+62 ';
                if (value.length > 2) {
                    formatted += value.substring(2, 5);
                    if (value.length > 5) {
                        formatted += '-' + value.substring(5, 9);
                        if (value.length > 9) {
                            formatted += '-' + value.substring(9, 13);
                        }
                    }
                } else {
                    formatted += value.substring(2);
                }
                e.target.value = formatted;
            }
        });

        // Auto-format order number
        document.getElementById('order_number').addEventListener('input', function(e) {
            let value = e.target.value.toUpperCase();
            if (!value.startsWith('ORD-')) {
                value = 'ORD-' + value;
            }
            e.target.value = value;
        });
    </script>
</body>
</html>
