<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Track Your Order - <?= env('site_name') ?><?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h4 class="mb-0">
                        <i class="fas fa-search me-2"></i>Track Your Order
                    </h4>
                </div>
                <div class="card-body">
                    <!-- Phone Lookup Form -->
                    <form method="post" action="<?= site_url('track/order') ?>">
                        <?= csrf_field() ?>
                        <div class="mb-4">
                            <label for="phone" class="form-label">Enter Your Phone Number</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-phone"></i>
                                </span>
                                <input type="tel" 
                                       class="form-control form-control-lg" 
                                       id="phone" 
                                       name="phone" 
                                       value="<?= esc($phone ?? '') ?>" 
                                       placeholder="e.g., 08123456789"
                                       required>
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-search me-2"></i>Find Orders
                                </button>
                            </div>
                            <small class="text-muted">Enter the phone number used when placing your order</small>
                        </div>
                    </form>

                    <?php if (isset($error)): ?>
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <?= esc($error) ?>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($orders)): ?>
                        <div class="mt-4">
                            <h5 class="mb-3">Found Orders (<?= count($orders) ?>)</h5>
                            
                            <?php foreach ($orders as $order): ?>
                                <div class="card mb-3">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-0">Order #<?= $order['order_number'] ?></h6>
                                            <small class="text-muted">
                                                <?= date('d M Y H:i', strtotime($order['created_at'])) ?>
                                            </small>
                                        </div>
                                        <span class="badge bg-<?= 
                                            $order['status'] === 'completed' ? 'success' : 
                                            ($order['status'] === 'processing' || $order['status'] === 'shipped' ? 'primary' : 'warning') 
                                        ?>">
                                            <?= ucfirst($order['status']) ?>
                                        </span>
                                    </div>
                                    <div class="card-body">
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <small class="text-muted">Customer</small>
                                                <p class="mb-1"><?= esc($order['customer_name']) ?></p>
                                                <small class="text-muted">Phone</small>
                                                <p class="mb-0"><?= esc($order['customer_phone']) ?></p>
                                            </div>
                                            <div class="col-md-6 text-end">
                                                <small class="text-muted">Total Amount</small>
                                                <h5 class="text-primary mb-0">Rp <?= number_format($order['subtotal'], 0, ',', '.') ?></h5>
                                            </div>
                                        </div>
                                        
                                        <?php if (!empty($order['items'])): ?>
                                            <div class="mb-3">
                                                <small class="text-muted">Order Items</small>
                                                <?php foreach ($order['items'] as $item): ?>
                                                    <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                                        <div>
                                                            <strong><?= esc($item['product_name']) ?></strong>
                                                            <span class="text-muted ms-2">x<?= $item['quantity'] ?></span>
                                                        </div>
                                                        <span>Rp <?= number_format($item['subtotal'], 0, ',', '.') ?></span>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <div class="d-flex gap-2">
                                            <a href="<?= site_url("cart/success/{$order['id']}") ?>" class="btn btn-outline-primary btn-sm">
                                                <i class="fas fa-eye me-1"></i>View Details
                                            </a>
                                            <?php if ($order['payment_method'] === 'direct'): ?>
                                                <a href="https://wa.me/6287787395768?text=Hi,%20I%20want%20to%20confirm%20payment%20for%20order%20%23<?= $order['order_number'] ?>" 
                                                   target="_blank" 
                                                   class="btn btn-success btn-sm">
                                                    <i class="fab fa-whatsapp me-1"></i>Confirm Payment
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
