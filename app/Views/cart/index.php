<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container py-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-shopping-cart me-2"></i>Keranjang Belanja
                    </h5>
                </div>
                <div class="card-body">
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger">
                            <strong>Error:</strong> <?= esc($error) ?>
                        </div>
                    <?php endif; ?>

                    <?php if (empty($cartItems)): ?>
                        <div class="text-center py-5">
                            <i class="fas fa-shopping-cart fa-4x text-muted mb-3"></i>
                            <h4>Your cart is empty</h4>
                            <p class="text-muted">You have no items in your shopping cart.</p>
                            <a href="<?= site_url('products') ?>" class="btn btn-primary">
                                <i class="fas fa-shopping-bag me-2"></i> Mulai Berbelanja
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Harga (DP)</th>
                                        <th>Jumlah</th>
                                        <th>Total</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($cartItems as $id => $item): ?>
                                        <tr data-product-id="<?= $id ?>">
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <?php if (!empty($item['image'])): ?>
                                                        <img src="<?= base_url('uploads/products/' . $item['image']) ?>"
                                                             alt="<?= esc($item['name']) ?>" class="rounded" style="width: 50px; height: 50px; object-fit: cover;">
                                                    <?php else: ?>
                                                        <div style="width: 50px; height: 50px; background: #f8f9fa; display: flex; align-items: center; justify-content: center; border-radius: 4px;">
                                                            <i class="fas fa-image text-muted"></i>
                                                        </div>
                                                    <?php endif; ?>
                                                    <div class="ml-3">
                                                        <h6 class="mb-0"><?= esc($item['name']) ?></h6>
                                                        <small class="text-muted"><?= esc($item['category'] ?? 'No category') ?></small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>Rp <?= number_format($item['price'], 0, ',', '.') ?></td>
                                            <td>
                                                <input type="number" value="<?= $item['quantity'] ?>" min="1" class="form-control form-control-sm" style="width: 80px;">
                                            </td>
                                            <td>Rp <?= number_format($item['price'] * $item['quantity'], 0, ',', '.') ?></td>
                                            <td>
                                                <button class="btn btn-sm btn-danger" onclick="removeFromCart('<?= $id ?>')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-6">
                                <a href="<?= site_url('products') ?>" class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-left me-2"></i> Lanjut Berbelanja
                                </a>
                            </div>
                            <div class="col-md-6 text-right">
                                <h5>Subtotal: Rp <?= number_format($subtotal, 0, ',', '.') ?></h5>
                                <a href="<?= site_url('cart/checkout') ?>" class="btn btn-success">
                                    <i class="fas fa-credit-card me-2"></i> Proses Pembayaran
                                </a>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
function removeFromCart(productId) {
    if (confirm('Are you sure you want to remove this item from your cart?')) {
        $.ajax({
            url: '<?= site_url('cart/remove') ?>/' + productId,
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    location.reload(); // Refresh the page to update cart
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function() {
                alert('Error removing item from cart. Please try again.');
            }
        });
    }
}

// Update quantity functionality
$(document).ready(function() {
    $('input[type="number"]').on('change', function() {
        var productId = $(this).closest('tr').data('product-id');
        var quantity = $(this).val();

        if (quantity < 1) {
            quantity = 1;
            $(this).val(1);
        }

        updateCartQuantity(productId, quantity);
    });
});

function updateCartQuantity(productId, quantity) {
    $.ajax({
        url: '<?= site_url('cart/update') ?>',
        method: 'POST',
        data: {
            '<?= csrf_token() ?>': '<?= csrf_hash() ?>',
            item_id: productId,
            quantity: quantity
        },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                location.reload(); // Refresh to update totals
            } else {
                alert('Error: ' + response.message);
            }
        },
        error: function() {
            alert('Error updating cart. Please try again.');
        }
    });
}
</script>
<?= $this->endSection() ?>
