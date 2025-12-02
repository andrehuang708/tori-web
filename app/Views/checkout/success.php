<?= $this->extend('layouts/main') ?>

<?php
// Build WhatsApp message with order details
$whatsappNumber = '6287787395768';

// Shorten the tracking URL using base64 encoding for better readability
$trackingCode = base64_encode("track:{$order['id']}:" . substr(md5($order['customer_phone'] . env('encryption.key')), 0, 10));
$shortTrackingUrl = base_url("o/{$trackingCode}");

$orderDetails = "*PESANAN BARU*%0A%0A";
$orderDetails .= "*Nomor Pesanan:* {$order['order_number']}%0A";
$orderDetails .= "*Nama:* {$order['customer_name']}%0A";
$orderDetails .= "*No. HP:* {$order['customer_phone']}%0A";

// Add address if available
if (!empty($order['address'])) {
    $orderDetails .= "*Alamat:* " . str_replace("\n", "%0A", $order['address']) . "%0A";
}

$orderDetails .= "%0A*Daftar Produk:*%0A";

foreach ($orderItems as $item) {
    $orderDetails .= "- {$item['product_name']} (x{$item['quantity']})%0A";    
    $orderDetails .= "  @Rp " . number_format($item['price'], 0, ',', '.') . " = Rp " . number_format($item['subtotal'], 0, ',', '.') . "%0A";
}

$orderDetails .= "%0A*Total:* Rp " . number_format($order['subtotal'], 0, ',', '.') . "%0A%0A";
$orderDetails .= "*Metode Pelunasan:* " . ucfirst($order['payment_method']) . "%0A";
$orderDetails .= "*Status:* Menunggu Pembayaran Anda %0A%0A";

// Add tracking information
$orderDetails .= "*Lacak Pesanan:*%0A";
$orderDetails .= "Anda dapat melacak status pesanan Anda melalui tautan berikut:%0A";
$orderDetails .= $shortTrackingUrl . "%0A%0A";

$orderDetails .= "Terima kasih telah berbelanja di " . env('site_name') . "!";

$whatsappLink = "https://wa.me/{$whatsappNumber}?text=" . $orderDetails;
?>

<?= $this->section('title') ?>Pesanan Berhasil - <?= env('site_name') ?><?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-body p-5 text-center">
                    <div class="mb-4">
                        <div class="bg-success bg-opacity-10 d-inline-flex p-3 rounded-circle mb-3">
                            <i class="fas fa-check-circle text-white" style="font-size: 3rem;"></i>
                        </div>
                        <h2 class="fw-bold mb-3">Terima Kasih!</h2>
                        <p class="text-muted mb-4">Pesanan Anda telah berhasil diterima dengan nomor pesanan:</p>
                        <h4 class="fw-bold text-primary mb-4">#<?= $order['order_number'] ?></h4>
                        
                        <!-- Share Button -->
                        <div class="mb-4">
                            <a href="<?= $whatsappLink ?>" 
                               class="btn btn-success btn-lg" 
                               target="_blank"
                               style="background-color: #25D366; border-color: #25D366;">
                                <i class="fab fa-whatsapp me-2"></i> Kirim Detail Pesanan via WhatsApp
                            </a>
                        </div>

                        <div class="alert alert-info text-start">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-info-circle me-2"></i>
                                <div>
                                    <h6 class="mb-1">Informasi Penting</h6>
                                    <p class="mb-0 small">Kami akan segera menghubungi Anda untuk konfirmasi pesanan melalui nomor telepon yang telah Anda berikan.</p>
                                    <p class="mb-0 small mt-2">Anda juga dapat melacak pesanan Anda melalui tautan: 
                                        <a href="<?= $shortTrackingUrl ?>" target="_blank" class="text-primary"><?= $shortTrackingUrl ?></a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card border-0 bg-light mb-4">
                        <div class="card-body p-4">
                            <h5 class="mb-4">Ringkasan Pesanan</h5>
                            
                            <div class="table-responsive">
                                <table class="table table-borderless">
                                    <thead>
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
                                                    <div class="me-3">
                                                        <h6 class="mb-0"><?= esc($item['product_name']) ?></h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-end">Rp <?= number_format($item['price'], 0, ',', '.') ?></td>
                                            <td class="text-center"><?= $item['quantity'] ?></td>
                                            <td class="text-end fw-bold">Rp <?= number_format($item['subtotal'], 0, ',', '.') ?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                    <tfoot class="border-top">
                                        <tr>
                                            <td colspan="3" class="text-end fw-bold">Subtotal</td>
                                            <td class="text-end">Rp <?= number_format($order['subtotal'], 0, ',', '.') ?></td>
                                        </tr>
                                        <tr>
                                            <td colspan="3" class="text-end fw-bold">Total</td>
                                            <td class="text-end fw-bold fs-5 text-primary">Rp <?= number_format($order['subtotal'], 0, ',', '.') ?></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            
                            <div class="row mt-4">
                                <div class="col-md-6 mb-3 mb-md-0">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <h6 class="mb-3">Detail Pelanggan</h6>
                                            <ul class="list-unstyled mb-0">
                                                <li class="mb-2">
                                                    <i class="fas fa-user me-2 text-muted"></i>
                                                    <?= esc($order['customer_name']) ?>
                                                </li>
                                                <li class="mb-2">
                                                    <i class="fas fa-phone me-2 text-muted"></i>
                                                    <?= esc($order['customer_phone']) ?>
                                                </li>
                                                <?php if ($order['payment_method'] === 'direct' && !empty($order['address'])): ?>
                                                <li class="mb-0">
                                                    <i class="fas fa-map-marker-alt me-2 text-muted"></i>
                                                    <?= nl2br(esc($order['address'])) ?>
                                                </li>
                                                <?php endif; ?>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <h6 class="mb-3">Metode Pembayaran</h6>
                                            <div class="d-flex align-items-center mb-3">
                                                <?php 
                                                $paymentIcons = [
                                                    'toped' => 'fab fa-shopify text-success',
                                                    'shopee' => 'fab fa-shopify text-orange',
                                                    'direct' => 'fas fa-money-bill-wave text-primary'
                                                ];
                                                $paymentNames = [
                                                    'toped' => 'Tokopedia',
                                                    'shopee' => 'Shopee',
                                                    'direct' => 'Bayar di Tempat'
                                                ];
                                                ?>
                                                <i class="<?= $paymentIcons[$order['payment_method']] ?? 'fas fa-credit-card' ?> fa-2x me-3"></i>
                                                <div>
                                                    <h6 class="mb-0"><?= $paymentNames[$order['payment_method']] ?? ucfirst($order['payment_method']) ?></h6>
                                                    <?php if ($order['payment_method'] === 'direct'): ?>
                                                    <p class="small text-muted mb-0">Bayar saat barang diterima</p>
                                                    <?php else: ?>
                                                    <p class="small text-muted mb-0">Ikuti instruksi yang dikirim via WhatsApp</p>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            <?php if ($order['payment_method'] !== 'direct'): ?>
                                            <div class="alert alert-warning small mb-0">
                                                <i class="fas fa-exclamation-triangle me-2"></i>
                                                Harap selesaikan pembayaran dalam 1x24 jam untuk menghindari pembatalan otomatis.
                                            </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-md-center mt-4">
                        <a href="<?= base_url('/') ?>" class="btn btn-outline-primary">
                            <i class="fas fa-home me-2"></i> Kembali ke Beranda
                        </a>
                        <a href="<?= $whatsappLink ?>" 
                           class="btn btn-success" 
                           target="_blank"
                           style="background-color: #25D366; border-color: #25D366;">
                            <i class="fab fa-whatsapp me-2"></i> Kirim ke WhatsApp
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    // Track order conversion
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof gtag !== 'undefined') {
            gtag('event', 'conversion', {
                'send_to': 'AW-123456789/AbC-D_efGhiJkLmNoPqRsTuv',
                'value': <?= $order['subtotal'] ?>,
                'currency': 'IDR',
                'transaction_id': '<?= $order['order_number'] ?>'
            });
        }
        
        // Store order details in localStorage for potential cart recovery
        if (typeof Storage !== 'undefined') {
            const orderData = {
                orderNumber: '<?= $order['order_number'] ?>',
                total: <?= $order['subtotal'] ?>
            };
            sessionStorage.setItem('lastOrder', JSON.stringify(orderData));
        }
        
        // Auto-open WhatsApp in new tab if needed
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('share') === 'whatsapp') {
            window.open('<?= $whatsappLink ?>', '_blank');
        }
    });
</script>
<?= $this->endSection() ?>
