<?= $this->extend('layouts/main') ?>

<?= $this->section('styles') ?>
<style>
    /* Mobile Optimization */
    @media (max-width: 767.98px) {
        /* General Mobile Styles */
        body {
            font-size: 15px;
        }
        
        .container {
            padding-left: 15px;
            padding-right: 15px;
        }
        
        /* Header */
        .page-header {
            margin: -20px -15px 15px -15px;
            padding: 1.5rem 0;
        }
        
        /* Sidebar */
        .sidebar {
            background: #fff;
            border-radius: 0.5rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            margin-bottom: 1rem;
        }
        
        .sidebar .nav-link {
            padding: 0.75rem 1.25rem;
            border-radius: 0.25rem;
            margin-bottom: 0.25rem;
            font-size: 0.95rem;
        }
        
        /* Card Layout */
        .card {
            margin-bottom: 1rem;
            border-radius: 0.5rem;
            overflow: hidden;
        }
        
        .card-header {
            padding: 0.75rem 1rem;
        }
        
        .card-body {
            padding: 1rem;
        }
        
        /* Buttons */
        .btn {
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
        }
        
        /* Order Items */
        .order-item {
            padding: 0.75rem 0;
            border-bottom: 1px solid #eee;
        }
        
        .order-item:last-child {
            border-bottom: none;
        }
        
        /* Status Badges */
        .status-badge {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
        }
        
        /* Timeline */
        .timeline {
            padding-left: 20px;
        }
        
        .timeline::before {
            left: 8px;
        }
        
        .timeline-item {
            margin-bottom: 1rem;
        }
        
        .timeline-item::before {
            left: -20px;
            width: 8px;
            height: 8px;
        }
        
        /* Tables */
        .table-responsive {
            border: 0;
        }
        
        .table th, 
        .table td {
            padding: 0.5rem;
        }
    }
    body {
        background-color: #f8f9fa;
    }
    .page-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 2rem 0;
        margin: -20px -15px 20px -15px;
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
    .status-badge {
        font-size: 0.8rem;
        padding: 0.35rem 0.75rem;
        border-radius: 20px;
    }
    .order-info-card {
        border-left: 4px solid #007bff;
    }
    .item-card {
        transition: box-shadow 0.2s;
        margin-bottom: 1rem;
    }
    .item-card:hover {
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    .timeline {
        position: relative;
        padding-left: 30px;
    }
    .timeline::before {
        content: '';
        position: absolute;
        left: 10px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #dee2e6;
    }
    .timeline-item {
        position: relative;
        margin-bottom: 1.5rem;
    }
    .timeline-item::before {
        content: '';
        position: absolute;
        left: -25px;
        top: 5px;
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: #007bff;
        border: 2px solid white;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('title') ?>Order Details #<?= esc($order['order_number']) ?> - <?= env('site_name') ?><?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container py-4">
        <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3">
            <!-- Mobile Menu Toggle Button -->
            <button class="btn btn-outline-secondary w-100 d-md-none mb-3" type="button" data-bs-toggle="collapse" data-bs-target="#accountMenuCollapse" aria-expanded="false" aria-controls="accountMenuCollapse">
                <i class="fas fa-bars me-2"></i> Account Menu
            </button>
            
            <div class="sidebar">
                <div class="collapse d-md-block" id="accountMenuCollapse">
                    <div class="p-3">
                        <h6 class="text-muted mb-3 d-none d-md-block">Account Menu</h6>
                    <nav class="nav nav-pills flex-column">
                        <a href="<?= site_url('user/order-history') ?>" class="nav-link">
                            <i class="fas fa-list me-2"></i>Order History
                        </a>
                        <a href="<?= site_url('user/track-order') ?>" class="nav-link">
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

            <!-- Order Status Card -->
        </div>

        <!-- Main Content -->
        <div class="col-12 col-md-9">
                <!-- Order Information -->
                <div class="card order-info-card mb-3">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fs-5">
                            <i class="fas fa-info-circle me-2"></i>Order Information
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6>Order Details</h6>
                                <table class="table table-sm">
                                    <tr>
                                        <td><strong>Order Number:</strong></td>
                                        <td><?= esc($order['order_number']) ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Order Date:</strong></td>
                                        <td><?= date('M d, Y H:i', strtotime($order['created_at'])) ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Payment Method:</strong></td>
                                        <td><?= ucfirst($order['payment_method']) ?></td>
                                    </tr>
                                    <tr>
                                        
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <h6>Customer Information</h6>
                                <table class="table table-sm">
                                    <tr>
                                        <td><strong>Name:</strong></td>
                                        <td><?= esc($order['customer_name']) ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Phone:</strong></td>
                                        <td><?= esc($order['customer_phone']) ?></td>
                                    </tr>
                                    <td><strong>Status:</strong></td>
                                        <td>
                                            <span class="badge bg-<?= getOrderStatusColor($order['status']) ?>">
                                                <?= ucfirst($order['status']) ?>
                                            </span>
                                        </td>
                                    
                                    <?php if (!empty($order['address'])): ?>
                                    <tr>
                                        <td><strong>Address:</strong></td>
                                        <td><?= esc($order['address']) ?></td>
                                    </tr>
                                    <?php endif; ?>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Items -->
                <div class="card mb-4">
                    <div class="card-header bg-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 fs-5">
                                <i class="fas fa-shopping-bag me-2"></i>Order Items
                            </h5>
                            <span class="badge bg-secondary"><?= count($orderItems) ?> items</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <?php foreach ($orderItems as $item): ?>
                            <div class="item-card card border">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-md-6">
                                            <h6 class="mb-1"><?= esc($item['product_name']) ?></h6>
                                            <small class="text-muted">Product ID: #<?= $item['product_id'] ?></small>
                                        </div>
                                        <div class="col-md-2">
                                            <span class="badge bg-light text-dark">Qty: <?= $item['quantity'] ?></span>
                                        </div>
                                        <div class="col-md-2 text-end">
                                            <small class="text-muted">Price each</small>
                                            <h6 class="mb-0">Rp <?= number_format($item['price'], 0, ',', '.') ?></h6>
                                        </div>
                                        <div class="col-md-2 text-end">
                                            <small class="text-muted">Subtotal</small>
                                            <h5 class="mb-0 text-primary">Rp <?= number_format($item['subtotal'], 0, ',', '.') ?></h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Order Summary -->
                <!-- <div class="card">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">
                            <i class="fas fa-calculator me-2"></i>Order Summary
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <table class="table">
                                    <tr>
                                        <td><strong>Subtotal:</strong></td>
                                        <td class="text-end">Rp <?= number_format($order['subtotal'], 0, ',', '.') ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Shipping:</strong></td>
                                        <td class="text-end">Free</td>
                                    </tr>
                                    <tr class="table-active">
                                        <td><h5 class="mb-0">Total:</h5></td>
                                        <td class="text-end">
                                            <h5 class="mb-0 text-primary">Rp <?= number_format($order['subtotal'], 0, ',', '.') ?></h5>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-4">
                                <div class="alert alert-info">
                                    <h6><i class="fas fa-info-circle me-2"></i>Order Status</h6>
                                    <p class="mb-2">
                                        <strong><?= ucfirst($order['status']) ?></strong>
                                    </p>
                                    <small class="text-muted">
                                        Last updated: <?= date('M d, Y H:i', strtotime($order['updated_at'] ?? $order['created_at'])) ?>
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> -->


                <!-- Payment Information -->
                <?php if (!empty($order['payment_method'])): ?>
                <div class="card mt-4 hidden" style="display: none;">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">
                            <i class="fas fa-credit-card me-2"></i>Payment Information
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Payment Method:</strong> <?= ucfirst($order['payment_method']) ?></p>
                                <?php if ($order['payment_method'] === 'direct'): ?>
                                    <p class="text-muted">Please transfer the total amount to our bank account.</p>
                                <?php else: ?>
                                    <p class="text-muted">Please complete payment through <?= ucfirst($order['payment_method']) ?>.</p>
                                <?php endif; ?>
                            </div>
                            <!-- <div class="col-md-6">
                                <div class="alert alert-success">
                                    <i class="fas fa-check-circle me-2"></i>
                                    <strong>Payment Required</strong><br>
                                    <small>Please complete your payment to process this order.</small>
                                </div>
                            </div> -->
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Payment History -->
                <div class="card mt-3">
                    <div class="card-header bg-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 fs-5">
                                <i class="fas fa-history me-2"></i>Payment History
                            </h5>
                            
                        </div>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($paymentHistory)): ?>
                            <?php foreach ($paymentHistory as $payment): ?>
                                <div class="border-bottom pb-3 mb-3">
                                    <div class="row align-items-center">
                                        <div class="col-md-2">
                                            <small class="text-muted d-block">Date</small>
                                            <strong><?= date('M d, Y H:i', strtotime($payment['created_at'])) ?></strong>
                                        </div>
                                        <div class="col-md-2">
                                            <small class="text-muted d-block">Amount</small>
                                            <strong>Rp <?= number_format($payment['payment_amount'], 0, ',', '.') ?></strong>
                                        </div>
                                        <div class="col-md-2">
                                            <small class="text-muted d-block">Method</small>
                                            <strong><?= ucfirst($payment['payment_method']) ?></strong>
                                        </div>
                                        <!-- <div class="col-md-2">
                                            <small class="text-muted d-block">Status</small>
                                            <span class="status-badge badge bg-<?= getPaymentStatusColor($payment['status']) ?>">
                                                <?= ucfirst($payment['status']) ?>
                                            </span>
                                        </div> -->
                                        <div class="col-md-2">
                                            <small class="text-muted d-block">Payment Date</small>
                                            <strong><?= date('M d, Y', strtotime($payment['payment_date'])) ?></strong>
                                        </div>
                                        <div class="col-md-2">
                                            <?php if ($payment['payment_proof_image']): ?>
                                                <a href="<?= site_url('uploads/payment_proofs/' . $payment['payment_proof_image']) ?>" 
                                                   target="_blank" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye me-1"></i>View Proof
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <?php if ($payment['notes']): ?>
                                        <div class="mt-2">
                                            <small class="text-muted">Notes:</small>
                                            <p class="mb-0"><?= esc($payment['notes']) ?></p>
                                        </div>
                                    <?php endif; ?>
                                    <?php if ($payment['admin_notes']): ?>
                                        <div class="mt-2">
                                            <small class="text-muted">Admin Notes:</small>
                                            <p class="mb-0"><?= esc($payment['admin_notes']) ?></p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="text-center py-4">
                                <i class="fas fa-credit-card fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No payment proofs uploaded yet.</p>
                                <a href="<?= site_url('user/order/' . $order['id'] . '/upload-payment') ?>" class="btn btn-primary">
                                    <i class="fas fa-upload me-2"></i>Upload Bukti Pembayaran
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?php
// Helper functions
function getOrderStatusColor($status) {
    $colors = [
        'pending' => 'warning',
        'processing' => 'info',
        'completed' => 'success',
        'cancelled' => 'danger'
    ];
    return $colors[$status] ?? 'secondary';
}

function getStatusIcon($status) {
    $icons = [
        'pending' => 'clock',
        'processing' => 'spinner',
        'completed' => 'check-circle',
        'cancelled' => 'times-circle'
    ];
    return $icons[$status] ?? 'question-circle';
}

function getPaymentStatusColor($status) {
    $colors = [
        'pending' => 'warning',
        'verified' => 'success',
        'rejected' => 'danger'
    ];
    return $colors[$status] ?? 'secondary';
}
?>
