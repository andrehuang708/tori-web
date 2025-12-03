<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-shopping-cart mr-2"></i>
                        Order Details #<?= $order['order_number'] ?>
                    </h5>
                </div>
                <div class="card-body">
                    <?php
// Calculate total if not available
$orderTotal = $order['total_amount'] ?? 0;
if ($orderTotal == 0) {
    $orderTotal = ($order['subtotal'] ?? 0) + ($order['shipping_cost'] ?? 0) + ($order['tax_amount'] ?? 0);
}
?>

<!-- Tabs Navigation -->
                    <ul class="nav nav-tabs mb-4" id="orderTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="details-tab" data-toggle="tab" href="#details" role="tab" aria-controls="details" aria-selected="true">
                                <i class="fas fa-info-circle me-2"></i>Order Details
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="payment-tab" data-toggle="tab" href="#payment" role="tab" aria-controls="payment" aria-selected="false">
                                <i class="fas fa-credit-card me-2"></i>Payment History
                            </a>
                        </li>
                    </ul>

                    <!-- Tab Content -->
                    <div class="tab-content" id="orderTabsContent">
                        <!-- Order Details Tab -->
                        <div class="tab-pane fade show active" id="details" role="tabpanel" aria-labelledby="details-tab">
                            <div class="row">
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
                                            <td><?= esc($order['customer_phone'] ?? '-') ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Address:</strong></td>
                                            <td><?= esc($order['address'] ?? '-') ?></td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <h6>Order Information</h6>
                                    <table class="table table-sm">
                                        <tr>
                                            <td><strong>Order Date:</strong></td>
                                            <td><?= date('d M Y H:i', strtotime($order['created_at'])) ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Status:</strong></td>
                                            <td>
                                                <span class="badge badge-<?= $order['status'] == 'completed' ? 'success' : ($order['status'] == 'cancelled' ? 'danger' : ($order['status'] == 'processing' ? 'warning' : 'info')) ?>">
                                                    <?= ucfirst($order['status']) ?>
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Payment Method:</strong></td>
                                            <td><?= ucwords(str_replace('_', ' ', $order['payment_method'])) ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Payment Status:</strong></td>
                                            <td>
                                                <?php if (in_array($order['status'], ['completed', 'cancelled'])): ?>
                                                    <span class="badge badge-<?= ($order['payment_status'] ?? 'pending') == 'full_paid' ? 'success' : (($order['payment_status'] ?? 'pending') == 'dp_accepted' ? 'info' : 'warning') ?>">
                                                        <?= ($order['payment_status'] ?? 'pending') == 'pending' ? 'Pending' : (($order['payment_status'] ?? 'pending') == 'dp_accepted' ? 'DP Accepted' : 'Full Paid') ?>
                                                    </span>
                                                <?php else: ?>
                                                    <div class="d-flex align-items-center">
                                                        <span id="payment-status-badge" class="badge badge-<?= ($order['payment_status'] ?? 'pending') == 'full_paid' ? 'success' : (($order['payment_status'] ?? 'pending') == 'dp_accepted' ? 'info' : 'warning') ?>">
                                                            <?= ($order['payment_status'] ?? 'pending') == 'pending' ? 'Pending' : (($order['payment_status'] ?? 'pending') == 'dp_accepted' ? 'DP Accepted' : 'Full Paid') ?>
                                                        </span>
                                                        <div class="ml-2">
                                                            <select class="form-control form-control-sm" id="payment-status-select" style="width: auto;">
                                                                <option value="pending" <?= ($order['payment_status'] ?? 'pending') == 'pending' ? 'selected' : '' ?>>Pending</option>
                                                                <option value="dp_accepted" <?= ($order['payment_status'] ?? 'pending') == 'dp_accepted' ? 'selected' : '' ?>>DP Accepted</option>
                                                                <option value="full_paid" <?= ($order['payment_status'] ?? 'pending') == 'full_paid' ? 'selected' : '' ?>>Full Paid</option>
                                                            </select>
                                                        </div>
                                                        <button type="button" class="btn btn-sm btn-primary ml-2" onclick="updatePaymentStatus()">
                                                            <i class="fas fa-save"></i> Update
                                                        </button>
                                                    </div>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            
                            <hr>
                            
                            <h6>Order Items</h6>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Product</th>
                                            <th>Price</th>
                                            <th>Quantity</th>
                                            <th>Total</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($order['items'] as $item): ?>
                                        <tr>
                                            <td>
                                                <?= esc($item['product_name']) ?>
                                            </td>
                                            <td><?= number_format($item['price'], 2) ?></td>
                                            <td><?= $item['quantity'] ?></td>
                                            <td><?= number_format($item['price'] * $item['quantity'], 2) ?></td>
                                            <td>
                                                <span class="badge badge-<?= $item['order_items_status'] == 'shipped' ? 'success' : 'warning' ?>" id="status-<?= $item['id'] ?>">
                                                    <?= ucfirst($item['order_items_status'] ?? 'not fulfilled') ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?php if (in_array($order['status'], ['completed', 'cancelled'])): ?>
                                                    <span class="text-muted">-</span>
                                                <?php else: ?>
                                                    <?php if ($item['order_items_status'] == 'not fulfilled'): ?>
                                                    <button type="button" class="btn btn-sm btn-success" onclick="updateItemStatus(<?= $item['id'] ?>, 'shipped')">
                                                        <i class="fas fa-shipping-fast mr-1"></i> Mark as Shipped
                                                    </button>
                                                    <?php else: ?>
                                                    <button type="button" class="btn btn-sm btn-warning" onclick="updateItemStatus(<?= $item['id'] ?>, 'not fulfilled')">
                                                        <i class="fas fa-undo mr-1"></i> Mark as Not Fulfilled
                                                    </button>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="4"><strong>Subtotal:</strong></td>
                                            <td><?= number_format($order['subtotal'] ?? $order['total_amount'], 2) ?></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td colspan="4"><strong>Shipping:</strong></td>
                                            <td><?= number_format($order['shipping_cost'] ?? 0, 2) ?></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td colspan="4"><strong>Tax:</strong></td>
                                            <td><?= number_format($order['tax_amount'] ?? 0, 2) ?></td>
                                            <td></td>
                                        </tr>
                                        <tr class="font-weight-bold">
                                            <td colspan="4"><strong>Total:</strong></td>
                                            <td><?= number_format($orderTotal, 2) ?></td>
                                            <td></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>

                        <!-- Payment History Tab -->
                        <div class="tab-pane fade" id="payment" role="tabpanel" aria-labelledby="payment-tab">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="mb-0">Payment History</h6>
                            </div>
                            
                            <!-- Upload Payment Form -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h6 class="mb-0">
                                        <i class="fas fa-upload me-2"></i>Upload Payment Proof
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div id="uploadAlert" style="display: none;" class="alert"></div>
                                    
                                    <form id="uploadPaymentForm" action="<?= site_url('admin/orders/upload-payment/' . $order['id']) ?>" method="post" enctype="multipart/form-data">
                                        <?= csrf_field() ?>
                                        
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="payment_proof" class="form-label">Payment Proof Image *</label>
                                                    <input type="file" class="form-control" id="payment_proof" name="payment_proof" accept="image/*" required>
                                                    <small class="text-muted">
                                                        <i class="fas fa-info-circle me-1"></i>
                                                        Allowed formats: JPG, JPEG, PNG, GIF (Max: 2MB)
                                                    </small>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="payment_method" class="form-label">Payment Method *</label>
                                                    <select class="form-control" id="payment_method" name="payment_method" required>
                                                        <option value="">Select Payment Method</option>
                                                        <option value="bca">BCA</option>
                                                        <option value="gopay">GoPay</option>
                                                        <option value="seabank">Seabank</option>
                                                        <option value="shopeepay">ShopeePay</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="payment_amount" class="form-label">Payment Amount *</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">Rp</span>
                                                        </div>
                                                        <input type="number" class="form-control" id="payment_amount" name="payment_amount" 
                                                               value="<?= number_format($orderTotal, 0, '.', '') ?>" readonly>
                                                    </div>
                                                    <small class="text-muted">
                                                        <i class="fas fa-info-circle me-1"></i>
                                                        Automatically filled with order total amount (Rp <?= number_format($orderTotal, 0, ',', '.') ?>)
                                                    </small>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="payment_date" class="form-label">Payment Date *</label>
                                                    <input type="date" class="form-control" id="payment_date" name="payment_date" 
                                                           value="<?= date('Y-m-d') ?>" required>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="notes" class="form-label">Notes (Optional)</label>
                                            <textarea class="form-control" id="notes" name="notes" rows="3" 
                                                      placeholder="Add any additional information about the payment..."></textarea>
                                        </div>

                                        <div class="text-end">
                                            <button type="submit" class="btn btn-primary" id="uploadBtn">
                                                <i class="fas fa-upload me-2"></i>Upload Payment Proof
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            
                            <!-- Payment History DataTable -->
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">
                                        <i class="fas fa-history me-2"></i>Payment Records
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <?php if (!empty($paymentHistory)): ?>
                                        <div class="table-responsive">
                                            <table class="table table-striped table-hover" id="paymentHistoryTable">
                                                <thead>
                                                    <tr>
                                                        <th>Date</th>
                                                        <th>Amount</th>
                                                        <th>Method</th>
                                                        <th>Status</th>
                                                        <th>Proof</th>
                                                        <th>Verified By</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($paymentHistory as $payment): ?>
                                                        <tr>
                                                            <td>
                                                                <div><?= date('d M Y', strtotime($payment['payment_date'])) ?></div>
                                                                <small class="text-muted"><?= date('H:i', strtotime($payment['payment_date'])) ?></small>
                                                            </td>
                                                            <td>
                                                                <span class="fw-bold text-primary">Rp <?= number_format($payment['payment_amount'] ?? 0, 0, ',', '.') ?></span>
                                                            </td>
                                                            <td>
                                                                <?php 
                                                                $methods = [
                                                                    'bca' => 'BCA',
                                                                    'gopay' => 'GoPay', 
                                                                    'seabank' => 'Seabank',
                                                                    'shopeepay' => 'ShopeePay'
                                                                ];
                                                                echo $methods[$payment['payment_method']] ?? ucfirst($payment['payment_method']);
                                                                ?>
                                                            </td>
                                                            <td>
                                                                <span class="badge badge-<?= $payment['status'] == 'verified' ? 'success' : ($payment['status'] == 'rejected' ? 'danger' : 'warning') ?>">
                                                                    <?= ucfirst($payment['status']) ?>
                                                                </span>
                                                            </td>
                                                            <td>
                                                                <?php if ($payment['payment_proof_image']): ?>
                                                                    <img src="<?= base_url('uploads/payments/' . $payment['payment_proof_image']) ?>" 
                                                                         alt="Payment Proof" 
                                                                         class="img-thumbnail" 
                                                                         style="max-width: 60px; max-height: 40px; cursor: pointer;"
                                                                         onclick="window.open('<?= base_url('uploads/payments/' . $payment['payment_proof_image']) ?>', '_blank')">
                                                                <?php else: ?>
                                                                    <span class="text-muted">No image</span>
                                                                <?php endif; ?>
                                                            </td>
                                                            <td>
                                                                <?php if (isset($payment['verified_by']) && $payment['verified_by']): ?>
                                                                    Admin #<?= $payment['verified_by'] ?>
                                                                    <?php if (isset($payment['verified_at'])): ?>
                                                                        <br><small class="text-muted"><?= date('d M H:i', strtotime($payment['verified_at'])) ?></small>
                                                                    <?php endif; ?>
                                                                <?php else: ?>
                                                                    <span class="text-muted">-</span>
                                                                <?php endif; ?>
                                                            </td>
                                                            <td>
                                                                <div class="btn-group btn-group-sm">
                                                                    <?php if ($payment['payment_proof_image']): ?>
                                                                        <a href="<?= base_url('uploads/payments/' . $payment['payment_proof_image']) ?>" 
                                                                           target="_blank" 
                                                                           class="btn btn-outline-primary" 
                                                                           title="View Full Size">
                                                                            <i class="fas fa-eye"></i>
                                                                        </a>
                                                                    <?php endif; ?>
                                                                    
                                                                    <?php if ($payment['notes']): ?>
                                                                        <button type="button" 
                                                                                class="btn btn-outline-info" 
                                                                                title="View Notes"
                                                                                data-toggle="tooltip" 
                                                                                data-placement="top"
                                                                                data-original-title="<?= esc($payment['notes']) ?>"
                                                                                onclick="alert('Notes: <?= esc(str_replace(["'", '"'], ["\'", '\"'], $payment['notes'])) ?>')">
                                                                            <i class="fas fa-sticky-note"></i>
                                                                        </button>
                                                                    <?php endif; ?>
                                                                    
                                                                    <?php if ($payment['status'] == 'pending'): ?>
                                                                        <button type="button" class="btn btn-success" onclick="approvePayment(<?= $payment['id'] ?>)" title="Approve">
                                                                            <i class="fas fa-check"></i>
                                                                        </button>
                                                                        <button type="button" class="btn btn-danger" onclick="rejectPayment(<?= $payment['id'] ?>)" title="Reject">
                                                                            <i class="fas fa-times"></i>
                                                                        </button>
                                                                    <?php endif; ?>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    <?php else: ?>
                                        <div class="text-center py-5">
                                            <i class="fas fa-credit-card fa-3x text-muted mb-3"></i>
                                            <p class="text-muted">No payment proofs uploaded yet.</p>
                                            <p class="text-muted">Use the form above to upload a payment proof.</p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-3">
                        <a href="<?= site_url('admin/orders') ?>" class="btn btn-secondary">
                            <i class="fas fa-arrow-left mr-1"></i> Back to Orders
                        </a>
                        <?php if ($order['status'] != 'cancelled' && $order['status'] != 'completed'): ?>
                        <button type="button" class="btn btn-warning" onclick="updateOrderStatus(<?= $order['id'] ?>, 'cancelled')">
                            <i class="fas fa-times mr-1"></i> Cancel Order
                        </button>
                        <?php endif; ?>
                        <?php if ($order['status'] == 'pending' && !in_array($order['payment_status'] ?? 'pending', ['dp_accepted', 'full_paid'])): ?>
                        <button type="button" class="btn btn-info" onclick="updateOrderStatus(<?= $order['id'] ?>, 'processing')">
                            <i class="fas fa-play mr-1"></i> Mark as Processing
                        </button>
                        <?php endif; ?>
                        <?php if ($order['status'] == 'processing'): ?>
                        <button type="button" class="btn btn-success" onclick="updateOrderStatus(<?= $order['id'] ?>, 'completed')">
                            <i class="fas fa-check mr-1"></i> Mark as Completed
                        </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
function updateOrderStatus(orderId, newStatus) {
    if (!confirm('Are you sure you want to update the order status to ' + newStatus + '?')) {
        return;
    }
    
    fetch('<?= site_url('admin/orders/update-status') ?>/' + orderId, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: 'status=' + newStatus
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            location.reload();
        } else {
            alert(data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while updating the order status.');
    });
}

function updatePaymentStatus() {
    const newStatus = document.getElementById('payment-status-select').value;
    const orderId = <?= $order['id'] ?>;
    
    if (!confirm('Are you sure you want to update the payment status to ' + newStatus.charAt(0).toUpperCase() + newStatus.slice(1).replace('_', ' ') + '?')) {
        return;
    }
    
    fetch('<?= site_url('admin/orders/update-payment-status') ?>/' + orderId, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: 'status=' + newStatus
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update the status badge
            const badgeElement = document.getElementById('payment-status-badge');
            const statusText = data.status_text;
            let badgeClass = 'warning';
            
            if (newStatus == 'full_paid') {
                badgeClass = 'success';
            } else if (newStatus == 'dp_accepted') {
                badgeClass = 'info';
            }
            
            badgeElement.className = 'badge badge-' + badgeClass;
            badgeElement.textContent = statusText;
            
            // If order status was also updated, refresh the page to show/hide buttons
            if (data.order_status_updated) {
                alert(data.message + ' Order status automatically updated to Processing.');
                location.reload();
            } else {
                alert(data.message);
            }
        } else {
            alert(data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while updating the payment status.');
    });
}

function updateItemStatus(itemId, newStatus) {
    if (!confirm('Are you sure you want to update the item status to ' + newStatus + '?')) {
        return;
    }
    
    fetch('<?= site_url('admin/orders/update-item-status') ?>/' + itemId, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: 'status=' + newStatus
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update the status badge
            const statusElement = document.getElementById('status-' + itemId);
            const badgeClass = newStatus == 'shipped' ? 'success' : 'warning';
            statusElement.className = 'badge badge-' + badgeClass;
            statusElement.textContent = newStatus.charAt(0).toUpperCase() + newStatus.slice(1).replace('_', ' ');
            
            // Update the button
            const buttonRow = statusElement.closest('tr').querySelector('td:last-child');
            if (newStatus == 'shipped') {
                buttonRow.innerHTML = `
                    <button type="button" class="btn btn-sm btn-warning" onclick="updateItemStatus(${itemId}, 'not fulfilled')">
                        <i class="fas fa-undo mr-1"></i> Mark as Not Fulfilled
                    </button>
                `;
            } else {
                buttonRow.innerHTML = `
                    <button type="button" class="btn btn-sm btn-success" onclick="updateItemStatus(${itemId}, 'shipped')">
                        <i class="fas fa-shipping-fast mr-1"></i> Mark as Shipped
                    </button>
                `;
            }
            
            // If order was automatically completed, refresh the page
            if (data.order_completed) {
                alert(data.message + ' Order automatically marked as completed since all items are shipped.');
                location.reload();
            } else {
                alert(data.message);
            }
        } else {
            alert(data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while updating the item status.');
    });
}

function approvePayment(paymentId) {
    if (!confirm('Are you sure you want to approve this payment?')) {
        return;
    }
    
    fetch('<?= site_url('admin/orders/approve-payment') ?>/' + paymentId, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            location.reload();
        } else {
            alert(data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while approving the payment.');
    });
}

function rejectPayment(paymentId) {
    if (!confirm('Are you sure you want to reject this payment?')) {
        return;
    }
    
    fetch('<?= site_url('admin/orders/reject-payment') ?>/' + paymentId, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            location.reload();
        } else {
            alert(data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while rejecting the payment.');
    });
}

// Handle upload payment form submission (embedded form)
document.addEventListener('DOMContentLoaded', function() {
    const uploadForm = document.getElementById('uploadPaymentForm');
    if (uploadForm) {
        uploadForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const alertDiv = document.getElementById('uploadAlert');
            const submitBtn = document.getElementById('uploadBtn');
            const originalText = submitBtn.innerHTML;
            
            // Show loading state
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Uploading...';
            if (alertDiv) alertDiv.style.display = 'none';
            
            const formData = new FormData(this);
            
            fetch('<?= site_url('admin/orders/upload-payment/' . $order['id']) ?>', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                console.log('Response status:', response.status);
                console.log('Response headers:', response.headers);
                
                // Get response text to debug
                return response.text().then(text => {
                    console.log('Response text:', text);
                    try {
                        return JSON.parse(text);
                    } catch (e) {
                        console.error('JSON parse error:', e);
                        console.error('Response was not JSON:', text.substring(0, 200));
                        throw new Error('Server returned HTML instead of JSON: ' + text.substring(0, 100));
                    }
                });
            })
            .then(data => {
                console.log('Response data:', data);
                if (data.success) {
                    // Show success message
                    if (alertDiv) {
                        alertDiv.className = 'alert alert-success';
                        alertDiv.innerHTML = '<i class="fas fa-check-circle me-2"></i>' + data.message;
                        alertDiv.style.display = 'block';
                    }
                    
                    // Reset form
                    this.reset();
                    
                    // Refresh page after 2 seconds
                    setTimeout(function() {
                        location.reload();
                    }, 2000);
                } else {
                    // Show error message
                    if (alertDiv) {
                        alertDiv.className = 'alert alert-danger';
                        alertDiv.innerHTML = '<i class="fas fa-exclamation-circle me-2"></i>' + data.message;
                        alertDiv.style.display = 'block';
                    }
                    
                    // Reset button state
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                }
            })
            .catch(error => {
                console.error('Upload error:', error);
                if (alertDiv) {
                    alertDiv.className = 'alert alert-danger';
                    alertDiv.innerHTML = '<i class="fas fa-exclamation-circle me-2"></i>An error occurred while uploading the payment proof: ' + error.message;
                    alertDiv.style.display = 'block';
                }
                
                // Reset button state
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            });
        });
    }
});
</script>
<?= $this->endSection() ?>
