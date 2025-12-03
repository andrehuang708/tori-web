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
<div class="container py-3 py-md-5">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10 col-xl-8">
            <div class="card shadow-sm border-0">
                <div class="card-body p-3 p-sm-4 p-md-5 text-center">
                    <div class="mb-3 mb-md-4">
                        <div class="bg-success bg-opacity-10 d-inline-flex p-3 rounded-circle mb-3">
                            <i class="fas fa-check-circle text-white" style="font-size: 2.5rem;"></i>
                        </div>
                        <h2 class="fw-bold mb-2 mb-md-3 fs-3 fs-md-2">Terima Kasih!</h2>
                        <p class="text-muted mb-3 mb-md-4 small">Pesanan Anda telah berhasil diterima dengan nomor pesanan:</p>
                        <div class="d-inline-flex align-items-center gap-2 mb-3 mb-md-4 position-relative">
                            <h4 class="fw-bold text-primary mb-0 fs-5 fs-md-4" id="orderNumber">#<?= $order['order_number'] ?></h4>
                            <button class="btn btn-sm btn-outline-primary" 
                                    onclick="copyOrderNumber()" 
                                    id="copyBtn"
                                    title="Salin nomor pesanan">
                                <i class="fas fa-copy"></i>
                            </button>
                        </div>
                        
                        <!-- Share Button -->
                        <div class="mb-3 mb-md-4">
                            <a href="<?= $whatsappLink ?>" 
                               class="btn btn-success btn-lg w-100 w-sm-auto" 
                               target="_blank"
                               style="background-color: #25D366; border-color: #25D366;">
                                <i class="fab fa-whatsapp me-2"></i> 
                                <span class="d-none d-sm-inline">Kirim Detail Pesanan via WhatsApp</span>
                                <span class="d-inline d-sm-none">Kirim via WhatsApp</span>
                            </a>
                            
                        </div>
                         <div class="mb-3 mb-md-4">
                         <a href="<?= base_url('user/order/' . $order['id'] . '/upload-payment') ?>" class="btn btn-primary btn-block w-100">
                                                <i class="fas fa-upload me-2"></i> Upload Bukti Pembayaran
                                            </a>    
                        </div>
                        <div class="alert alert-info alert-permanent text-start">
                            <div class="d-flex align-items-start">
                                <i class="fas fa-info-circle me-2 mt-1"></i>
                                <div>
                                    <h6 class="mb-2 fs-6">Informasi Penting</h6>
                                    <p class="mb-0 small">Terima kasih telah berbelanja di Toritoys. Kami akan segera memverifikasi pesanan Anda, Mohon untuk lakukan upload bukti pembayaran Anda.</p>
                                    <p class="mb-0 small mt-2">Anda juga dapat melacak pesanan Anda melalui tautan: 
                                        <a href="<?= $shortTrackingUrl ?>" target="_blank" class="text-primary text-break"><?= $shortTrackingUrl ?></a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card border-0 bg-light mb-3 mb-md-4">
                        <div class="card-body p-3 p-md-4">
                            <h5 class="mb-3 mb-md-4 fs-6 fs-md-5">Ringkasan Pesanan</h5>
                            
                            <!-- Mobile-optimized order items -->
                            <div class="d-block d-md-none">
                                <?php foreach ($orderItems as $item): ?>
                                <div class="border-bottom pb-3 mb-3">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h6 class="mb-1 fs-6"><?= esc($item['product_name']) ?></h6>
                                        <span class="badge bg-secondary ms-2">x<?= $item['quantity'] ?></span>
                                    </div>
                                    <div class="d-flex justify-content-between small text-muted">
                                        <span>Rp <?= number_format($item['price'], 0, ',', '.') ?></span>
                                        <span class="fw-bold text-dark">Rp <?= number_format($item['subtotal'], 0, ',', '.') ?></span>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                                
                                <div class="pt-2">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="fw-bold">Subtotal</span>
                                        <span>Rp <?= number_format($order['subtotal'], 0, ',', '.') ?></span>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span class="fw-bold">Total</span>
                                        <span class="fw-bold fs-5 text-primary">Rp <?= number_format($order['subtotal'], 0, ',', '.') ?></span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Desktop table view -->
                            <div class="table-responsive d-none d-md-block">
                                <table class="table table-borderless mb-0">
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
                                                <h6 class="mb-0"><?= esc($item['product_name']) ?></h6>
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
                            
                            <div class="row mt-3 mt-md-4">
                                <div class="col-12 col-md-6 mb-3 mb-md-0">
                                    <div class="card h-100">
                                        <div class="card-body p-3">
                                            <h6 class="mb-3 fs-6">Detail Pelanggan</h6>
                                            <ul class="list-unstyled mb-0 small">
                                                <li class="mb-2">
                                                    <i class="fas fa-user me-2 text-muted"></i>
                                                    <span class="text-break"><?= esc($order['customer_name']) ?></span>
                                                </li>
                                                <li class="mb-2">
                                                    <i class="fas fa-phone me-2 text-muted"></i>
                                                    <span class="text-break"><?= esc($order['customer_phone']) ?></span>
                                                </li>
                                                <?php if ($order['payment_method'] === 'direct' && !empty($order['address'])): ?>
                                                <li class="mb-0">
                                                    <i class="fas fa-map-marker-alt me-2 text-muted align-self-start"></i>
                                                    <span class="text-break"><?= nl2br(esc($order['address'])) ?></span>
                                                </li>
                                                <?php endif; ?>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="card h-100">
                                        <div class="card-body p-3">
                                            <h6 class="mb-3 fs-6">Metode Pembayaran</h6>
                                            <div class="mb-3">
                                                <?php 
                                                $paymentNames = [
                                                    'toped' => 'Tokopedia',
                                                    'shopee' => 'Shopee',
                                                    'direct' => 'Bayar di Tempat'
                                                ];
                                                ?>
                                                <h6 class="mb-1 fs-6"><?= $paymentNames[$order['payment_method']] ?? ucfirst($order['payment_method']) ?></h6>
                                                <?php if ($order['payment_method'] === 'direct'): ?>
                                                <p class="small text-muted mb-0">Bayar saat barang diterima</p>
                                                <?php else: ?>
                                                <p class="small text-muted mb-0">Ikuti instruksi yang dikirim via WhatsApp</p>
                                                <?php endif; ?>
                                            </div>
                                            <?php if ($order['payment_method'] !== 'direct'): ?>
                                            <div class="alert alert-warning small mb-3 py-2">
                                                <i class="fas fa-exclamation-triangle me-2"></i>
                                                <span class="d-none d-sm-inline">Harap selesaikan pembayaran dalam 1x24 jam untuk menghindari pembatalan otomatis.</span>
                                                <span class="d-inline d-sm-none">Selesaikan pembayaran dalam 1x24 jam.</span>
                                            </div>
                                           
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-grid gap-2 d-sm-flex justify-content-sm-center mt-3 mt-md-4">
                        <a href="<?= base_url('/products') ?>" class="btn btn-outline-primary">
                            <i class="fas fa-shopping-cart me-2"></i> Lanjut Berbelanja
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
    // Copy order number to clipboard
    function copyOrderNumber() {
        const orderNumber = document.getElementById('orderNumber').textContent;
        const copyBtn = document.getElementById('copyBtn');
        
        // Use modern clipboard API
        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(orderNumber).then(function() {
                showCopySuccess(copyBtn);
            }).catch(function(err) {
                // Fallback for older browsers
                fallbackCopyTextToClipboard(orderNumber, copyBtn);
            });
        } else {
            // Fallback for older browsers
            fallbackCopyTextToClipboard(orderNumber, copyBtn);
        }
    }
    
    // Fallback copy method for older browsers
    function fallbackCopyTextToClipboard(text, btn) {
        const textArea = document.createElement('textarea');
        textArea.value = text;
        textArea.style.position = 'fixed';
        textArea.style.left = '-999999px';
        document.body.appendChild(textArea);
        textArea.focus();
        textArea.select();
        
        try {
            const successful = document.execCommand('copy');
            if (successful) {
                showCopySuccess(btn);
            }
        } catch (err) {
            console.error('Failed to copy text: ', err);
        }
        
        document.body.removeChild(textArea);
    }
    
    // Show visual feedback when copy is successful
    function showCopySuccess(btn) {
        const originalHTML = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-check"></i>';
        btn.classList.remove('btn-outline-primary');
        btn.classList.add('btn-success');
        
        // Create tooltip
        const tooltip = document.createElement('div');
        tooltip.className = 'position-absolute bg-success text-white px-2 py-1 rounded small';
        tooltip.style.top = '-35px';
        tooltip.style.left = '50%';
        tooltip.style.transform = 'translateX(-50%)';
        tooltip.style.whiteSpace = 'nowrap';
        tooltip.style.zIndex = '1000';
        tooltip.textContent = 'Tersalin!';
        
        // Only set position if not already set
        const parentElement = btn.parentElement;
        const currentPosition = window.getComputedStyle(parentElement).position;
        if (currentPosition === 'static') {
            parentElement.style.position = 'relative';
        }
        parentElement.appendChild(tooltip);
        
        setTimeout(function() {
            btn.innerHTML = originalHTML;
            btn.classList.remove('btn-success');
            btn.classList.add('btn-outline-primary');
            tooltip.remove();
        }, 2000);
    }
    
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
