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
                                    <td><?= number_format($order['total_amount'], 2) ?></td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
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
</script>
<?= $this->endSection() ?>
