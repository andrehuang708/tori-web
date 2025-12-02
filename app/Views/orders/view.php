<?= $this->extend('layouts/main') ?>

<?= $this->section('styles') ?>
<style>
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
</style>
<?= $this->endSection() ?>

<?= $this->section('title') ?>Lacak Pesanan #<?= $order['order_number'] ?> - <?= env('site_name') ?><?= $this->endSection() ?>

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

        <!-- Main Content -->
        <div class="col-md-9">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Detail Pesanan #<?= $order['order_number'] ?></h4>
                        <span class="status-badge bg-<?= 
                            $order['status'] === 'completed' ? 'success' : 
                            ($order['status'] === 'processing' || $order['status'] === 'shipped' ? 'primary' : 'warning') 
                        ?>">
                            <?= ucfirst($order['status']) ?>
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="mb-3">Informasi Pelanggan</h6>
                            <ul class="list-unstyled">
                                <li class="mb-2">
                                    <i class="fas fa-user me-2 text-muted"></i>
                                    <?= esc($order['customer_name']) ?>
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-phone me-2 text-muted"></i>
                                    <?= esc($order['customer_phone']) ?>
                                </li>
                                <?php if (!empty($order['address'])): ?>
                                <li class="mb-0">
                                    <i class="fas fa-map-marker-alt me-2 text-muted"></i>
                                    <?= nl2br(esc($order['address'])) ?>
                                </li>
                                <?php endif; ?>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6 class="mb-3">Rincian Pesanan</h6>
                            <ul class="list-unstyled">
                                <li class="mb-2">
                                    <span class="text-muted">Tanggal:</span>
                                    <?= date('d M Y H:i', strtotime($order['created_at'])) ?>
                                </li>
                                <li class="mb-2">
                                    <span class="text-muted">Metode Pembayaran:</span>
                                    <?php 
                                    $paymentMethods = [
                                        'toped' => 'Tokopedia',
                                        'shopee' => 'Shopee',
                                        'direct' => 'Bayar di Tempat (COD)'
                                    ];
                                    echo $paymentMethods[$order['payment_method']] ?? ucfirst($order['payment_method']);
                                    ?>
                                </li>
                                <li class="mb-0">
                                    <span class="text-muted">Status:</span>
                                    <span class="fw-bold"><?= ucfirst($order['status']) ?></span>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <h6 class="mb-3">Produk Dipesan</h6>
                    <div class="table-responsive">
                        <table class="table">
                            <thead class="bg-light">
                                <tr>
                                    <th>Produk</th>
                                    <th class="text-end">Harga</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($orderItems as $item): ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div>
                                                <h6 class="mb-0"><?= esc($item['product_name']) ?></h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-end">Rp <?= number_format($item['price'], 0, ',', '.') ?></td>
                                    <td class="text-center"><?= $item['quantity'] ?></td>
                                    <td class="text-end">Rp <?= number_format($item['subtotal'], 0, ',', '.') ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot class="bg-light">
                                <tr>
                                    <td colspan="3" class="text-end fw-bold">Subtotal</td>
                                    <td class="text-end">Rp <?= number_format($order['subtotal'], 0, ',', '.') ?></td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-end fw-bold">Total</td>
                                    <td class="text-end fw-bold text-primary">Rp <?= number_format($order['subtotal'], 0, ',', '.') ?></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <?php if (isset($isGuestView) && $isGuestView): ?>
                    <div class="alert alert-info mt-4">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-info-circle me-2"></i>
                            <div>
                                <h6 class="mb-1">Informasi Penting</h6>
                                <p class="mb-0">Halaman ini hanya dapat diakses melalui tautan yang dikirim ke nomor telepon Anda. Jangan bagikan tautan ini kepada siapapun.</p>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
