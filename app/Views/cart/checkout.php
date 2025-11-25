<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Checkout - <?= env('site_name') ?><?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container py-5">
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">
                        <i class="fas fa-user me-2"></i>Informasi Pelanggan
                    </h5>
                </div>
                <div class="card-body">
                    <?= form_open('cart/process-checkout', ['id' => 'checkoutForm']) ?>
                        <?= csrf_field() ?>
                        
                        <div class="row mb-3">
                            <div class="col-md-6 mb-3">
                                <label for="customer_name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="customer_name" name="customer_name" required>
                                <div class="invalid-feedback">
                                    Harap isi nama lengkap
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="customer_phone" class="form-label">Nomor Telepon <span class="text-danger">*</span></label>
                                <input type="tel" class="form-control" id="customer_phone" name="customer_phone" required>
                                <div class="invalid-feedback">
                                    Harap isi nomor telepon
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label mb-3">Metode Pembayaran <span class="text-danger">*</span></label>
                            
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="payment_method" id="toped" value="toped" required>
                                <label class="form-check-label d-flex align-items-center" for="toped">
                                    Tokopedia
                                </label>
                            </div>
                            
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="payment_method" id="shopee" value="shopee" required>
                                <label class="form-check-label d-flex align-items-center" for="shopee">
                                 Shopee
                                </label>
                            </div>
                            
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_method" id="direct" value="direct" required>
                                <label class="form-check-label" for="direct">
                                    Direct Transfer
                                </label>
                            </div>
                            
                            <div id="addressField" class="mt-3" style="display: none;">
                                <label for="address" class="form-label">Alamat Pengiriman <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="address" name="address" rows="3" required></textarea>
                                <div class="invalid-feedback">
                                    Harap isi alamat pengiriman
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <a href="<?= site_url('cart') ?>" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Kembali ke Keranjang
                            </a>
                            <button type="submit" class="btn btn-primary">
                                Proses Pesanan<i class="fas fa-arrow-right ms-2"></i>
                            </button>
                        </div>
                    <?= form_close() ?>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">
                        <i class="fas fa-shopping-cart me-2"></i>Ringkasan Pesanan
                    </h5>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        <?php foreach ($cart_items as $item): ?>
                            <li class="list-group-item">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="me-3">
                                        <h6 class="mb-1"><?= esc($item['name']) ?></h6>
                                        <small class="text-muted"><?= $item['quantity'] ?> x Rp <?= number_format($item['price'], 0, ',', '.') ?></small>
                                    </div>
                                    <div class="text-end">
                                        <span class="fw-bold">Rp <?= number_format($item['price'] * $item['quantity'], 0, ',', '.') ?></span>
                                    </div>
                                </div>
                            </li>
                        <?php endforeach; ?>
                        
                        <li class="list-group-item bg-light">
                            <div class="d-flex justify-content-between fw-bold fs-5">
                                <span>Total</span>
                                <span>Rp <?= number_format($cart_summary['total_price'] ?? 0, 0, ',', '.') ?></span>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Show/hide address field based on payment method
    const paymentMethod = document.querySelectorAll('input[name="payment_method"]');
    const addressField = document.getElementById('addressField');
    
    paymentMethod.forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.value === 'direct') {
                addressField.style.display = 'block';
                document.getElementById('address').required = true;
            } else {
                addressField.style.display = 'none';
                document.getElementById('address').required = false;
            }
        });
    });
    
    // Form validation
    const form = document.getElementById('checkoutForm');
    
    form.addEventListener('submit', function(event) {
        if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        }
        
        form.classList.add('was-validated');
    }, false);
    
    // Format phone number with Indonesian country code +62
    const phoneInput = document.getElementById('customer_phone');
    if (phoneInput) {
        // Add +62 prefix if not present when input loses focus
        phoneInput.addEventListener('blur', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.startsWith('0')) {
                value = '62' + value.substring(1);
            } else if (!value.startsWith('62') && value.length > 0) {
                value = '62' + value;
            }
            if (value) {
                e.target.value = '+' + value;
            }
        });
        
        // Format as user types
        phoneInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.startsWith('0')) {
                value = value.substring(1);
            }
            if (value.startsWith('62')) {
                value = value.substring(2);
            }
            
            if (value.length > 0) {
                // Format as +62 812-3456-7890
                let formatted = '+62 ';
                if (value.length > 3) {
                    formatted += value.substring(0, 3) + '-' + value.substring(3, 7);
                    if (value.length > 7) {
                        formatted += '-' + value.substring(7, 11);
                    }
                } else {
                    formatted += value;
                }
                e.target.value = formatted;
            }
        });
    }
});
</script>
<?= $this->endSection() ?>
