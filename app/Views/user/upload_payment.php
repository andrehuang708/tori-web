<?= $this->extend('layouts/main') ?>

<?= $this->section('styles') ?>
<style>
    .upload-container {
        max-width: 800px;
        margin: 0 auto;
    }
    .file-upload-wrapper {
        border: 2px dashed #dee2e6;
        border-radius: 8px;
        padding: 2rem;
        text-align: center;
        transition: all 0.3s ease;
    }
    .file-upload-wrapper:hover {
        border-color: #007bff;
        background-color: #f8f9fa;
    }
    .payment-history-card {
        margin-top: 2rem;
    }
    .status-badge {
        font-size: 0.875rem;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container py-4">
    <div class="upload-container">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0">
                <i class="fas fa-upload mr-2"></i>Upload Payment Proof
            </h4>
            <a href="<?= site_url('user/order/' . $order['id']) ?>" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left mr-1"></i>Back to Order
            </a>
        </div>

        <!-- Order Info Card -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-muted">Order Number</h6>
                        <p class="mb-2"><strong><?= esc($order['order_number']) ?></strong></p>
                        <h6 class="text-muted">Order Date</h6>
                        <p class="mb-2"><?= date('M d, Y H:i', strtotime($order['created_at'])) ?></p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Total DP</h6>
                        <p class="mb-2"><strong>Rp <?= number_format($order['subtotal'], 0, ',', '.') ?></strong></p>
                        <h6 class="text-muted">Pelunasan</h6>
                        <p class="mb-2"><?= ucfirst($order['payment_method']) ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Upload Form -->
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0">
                    <i class="fas fa-image mr-2"></i>Upload Payment Proof
                </h5>
            </div>
            <div class="card-body">
                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success">
                        <?= session()->getFlashdata('success') ?>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger">
                        <?= session()->getFlashdata('error') ?>
                    </div>
                <?php endif; ?>

                <form action="<?= site_url('user/order/' . $order['id'] . '/upload-payment') ?>" method="post" enctype="multipart/form-data">
                    <?= csrf_field() ?>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="payment_proof" class="form-label">Upload Bukti Transfer *</label>
                                <div class="file-upload-wrapper">
                                    <input type="file" class="form-control" id="payment_proof" name="payment_proof" accept="image/*" required>
                                    <small class="text-muted">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        Allowed formats: JPG, JPEG, PNG, GIF (Max: 2MB)
                                    </small>
                                </div>
                                <?php if (isset($validation) && $validation->getError('payment_proof')): ?>
                                    <div class="text-danger small mt-1">
                                        <?= $validation->getError('payment_proof') ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <!-- Payment amount is now automatically set server-side -->
                        <input type="hidden" name="payment_amount" value="<?= $order['subtotal'] ?>">
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="payment_method" class="form-label">Metode Pembayaran *</label>
                                <select class="form-control" id="payment_method" name="payment_method" required>
                                    <option value="">Pilih Cara Bayar</option>
                                    <option value="bca">BCA</option>
                                    <option value="gopay">GoPay</option>
                                    <option value="seabank">Seabank</option>
                                    <option value="shopeepay">ShopeePay</option>

                                </select>
                                <?php if (isset($validation) && $validation->getError('payment_method')): ?>
                                    <div class="text-danger small mt-1">
                                        <?= $validation->getError('payment_method') ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="payment_date" class="form-label">Tanggal DP *</label>
                                <input type="date" class="form-control" id="payment_date" name="payment_date"
                                       value="<?= date('Y-m-d') ?>" required>
                                <?php if (isset($validation) && $validation->getError('payment_date')): ?>
                                    <div class="text-danger small mt-1">
                                        <?= $validation->getError('payment_date') ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes (Optional)</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3"
                                  placeholder="Add any additional information about your payment..."></textarea>
                        <?php if (isset($validation) && $validation->getError('notes')): ?>
                            <div class="text-danger small mt-1">
                                <?= $validation->getError('notes') ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block">
                        <i class="fas fa-upload mr-2"></i>Upload Payment Proof
                    </button>
                </form>
            </div>
        </div>

        <!-- Payment History -->
        <?php if (!empty($paymentHistory)): ?>
            <div class="card shadow-sm payment-history-card">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">
                        <i class="fas fa-history mr-2"></i>Payment History
                    </h5>
                </div>
                <div class="card-body">
                    <?php foreach ($paymentHistory as $payment): ?>
                        <div class="border-bottom pb-3 mb-3">
                            <div class="row align-items-center">
                                <div class="col-md-3">
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
                                <div class="col-md-2">
                                    <small class="text-muted d-block">Status</small>
                                    <span class="status-badge badge badge-<?= getPaymentStatusColor($payment['status']) ?>">
                                        <?= ucfirst($payment['status']) ?>
                                    </span>
                                </div>
                                <div class="col-md-3">
                                    <?php if ($payment['payment_proof_image']): ?>
                                        <a href="<?= site_url('uploads/payment_proofs/' . $payment['payment_proof_image']) ?>"
                                           target="_blank" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye mr-1"></i>View Proof
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
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>

<?php
// Helper function for payment status colors
function getPaymentStatusColor($status) {
    $colors = [
        'pending' => 'warning',
        'verified' => 'success',
        'rejected' => 'danger'
    ];
    return $colors[$status] ?? 'secondary';
}
?>
