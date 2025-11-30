<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details - <?= esc($order['order_number']) ?> - Tori Toys</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .page-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem 0;
        }
        .order-info-card {
            border-left: 4px solid #007bff;
        }
        .status-badge {
            font-size: 1rem;
            padding: 0.5rem 1rem;
            border-radius: 25px;
        }
        .item-card {
            transition: box-shadow 0.2s;
            margin-bottom: 1rem;
        }
        .item-card:hover {
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
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
</head>
<body>
    <!-- Header -->
    <div class="page-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h1 class="mb-0">Order Details</h1>
                    <p class="mb-0 opacity-75">Order #<?= esc($order['order_number']) ?></p>
                </div>
                <div class="col-md-6 text-md-end">
                    <a href="<?= site_url('user/order-history') ?>" class="btn btn-outline-light">
                        <i class="fas fa-arrow-left me-2"></i>Back to Orders
                    </a>
                    <button class="btn btn-light ms-2" onclick="window.print()">
                        <i class="fas fa-print me-2"></i>Print
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="container mt-4">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3">
                <div class="sidebar p-3 mb-4">
                    <h6 class="text-uppercase mb-3">Account</h6>
                    <nav class="nav flex-column">
                        <a href="<?= site_url('user/dashboard') ?>" class="nav-link">
                            <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                        </a>
                        <a href="<?= site_url('user/order-history') ?>" class="nav-link active">
                            <i class="fas fa-shopping-bag me-2"></i>Order History
                        </a>
                        <a href="<?= site_url('user/track-order') ?>" class="nav-link">
                            <i class="fas fa-search me-2"></i>Track Order
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

                <!-- Order Status Card -->
                <div class="card border-primary">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <i class="fas fa-<?= getStatusIcon($order['status']) ?> fa-3x text-<?= getOrderStatusColor($order['status']) ?>"></i>
                        </div>
                        <span class="status-badge badge bg-<?= getOrderStatusColor($order['status']) ?>">
                            <?= ucfirst($order['status']) ?>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9">
                <!-- Order Information -->
                <div class="card order-info-card mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">
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
                                        <td><strong>Status:</strong></td>
                                        <td>
                                            <span class="badge bg-<?= getOrderStatusColor($order['status']) ?>">
                                                <?= ucfirst($order['status']) ?>
                                            </span>
                                        </td>
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
                                        <td><strong>Email:</strong></td>
                                        <td><?= esc($order['customer_email']) ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Phone:</strong></td>
                                        <td><?= esc($order['customer_phone']) ?></td>
                                    </tr>
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
                            <h5 class="mb-0">
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
                                        <div class="col-md-2 text-center">
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
                <div class="card mt-4">
                    <div class="card-header bg-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="fas fa-history me-2"></i>Payment History
                            </h5>
                            <a href="<?= site_url('user/order/' . $order['id'] . '/upload-payment') ?>" class="btn btn-primary btn-sm">
                                <i class="fas fa-upload me-1"></i>Upload Payment Proof
                            </a>
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
                                    <i class="fas fa-upload me-2"></i>Upload Payment Proof
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

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
