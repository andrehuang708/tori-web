<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Upload Payment Proof</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= site_url('admin') ?>">Home</a></li>
                        <li class="breadcrumb-item"><a href="<?= site_url('admin/orders') ?>">Orders</a></li>
                        <li class="breadcrumb-item"><a href="<?= site_url('admin/orders/view/' . $order['id']) ?>">Order #<?= $order['order_number'] ?></a></li>
                        <li class="breadcrumb-item active">Upload Payment</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-upload mr-2"></i>Upload Payment Proof for Order #<?= $order['order_number'] ?>
                            </h5>
                        </div>
                        <div class="card-body">
                            <?php if (session()->getFlashdata('success')): ?>
                                <div class="alert alert-success alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    <i class="icon fas fa-check"></i> <?= session()->getFlashdata('success') ?>
                                </div>
                            <?php endif; ?>

                            <?php if (session()->getFlashdata('error')): ?>
                                <div class="alert alert-danger alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    <i class="icon fas fa-ban"></i> <?= session()->getFlashdata('error') ?>
                                </div>
                            <?php endif; ?>

                            <!-- Order Info -->
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <h6>Order Information</h6>
                                    <table class="table table-sm">
                                        <tr>
                                            <td><strong>Order Number:</strong></td>
                                            <td><?= $order['order_number'] ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Customer:</strong></td>
                                            <td><?= $order['customer_name'] ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Order Date:</strong></td>
                                            <td><?= date('M d, Y H:i', strtotime($order['created_at'])) ?></td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <h6>Payment Information</h6>
                                    <table class="table table-sm">
                                        <tr>
                                            <td><strong>Subtotal:</strong></td>
                                            <td>Rp <?= number_format($order['subtotal'] ?? 0, 0, ',', '.') ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Shipping:</strong></td>
                                            <td>Rp <?= number_format($order['shipping_cost'] ?? 0, 0, ',', '.') ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Tax:</strong></td>
                                            <td>Rp <?= number_format($order['tax_amount'] ?? 0, 0, ',', '.') ?></td>
                                        </tr>
                                        <tr class="font-weight-bold">
                                            <td><strong>Total:</strong></td>
                                            <td>Rp <?= number_format(($order['subtotal'] ?? 0) + ($order['shipping_cost'] ?? 0) + ($order['tax_amount'] ?? 0), 0, ',', '.') ?></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            <!-- Upload Form -->
                            <form action="<?= site_url('admin/orders/upload-payment/' . $order['id']) ?>" method="post" enctype="multipart/form-data">
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
                                                       value="<?= number_format(($order['subtotal'] ?? 0) + ($order['shipping_cost'] ?? 0) + ($order['tax_amount'] ?? 0), 0, '.', '') ?>" readonly>
                                            </div>
                                            <small class="text-muted">
                                                <i class="fas fa-info-circle me-1"></i>
                                                Automatically filled with order total amount
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

                                <div class="d-flex justify-content-between">
                                    <a href="<?= site_url('admin/orders/view/' . $order['id']) ?>" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left mr-1"></i> Back to Order
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-upload mr-2"></i>Upload Payment Proof
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<?= $this->endSection() ?>
