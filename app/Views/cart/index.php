<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Shopping Cart</h3>
                        <div class="card-tools">
                            <a href="<?= site_url('products') ?>" class="btn btn-sm btn-primary">
                                <i class="fas fa-arrow-left mr-1"></i> Continue Shopping
                            </a>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <?php if (empty($cartItems)): ?>
                            <div class="text-center p-4">
                                <i class="fas fa-shopping-cart fa-4x text-muted mb-3"></i>
                                <h4>Your cart is empty</h4>
                                <p class="text-muted">You have no items in your shopping cart.</p>
                                <a href="<?= site_url('products') ?>" class="btn btn-primary">
                                    <i class="fas fa-shopping-bag mr-1"></i> Start Shopping
                                </a>
                            </div>
                        <?php else: ?>
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th style="width: 120px">Product</th>
                                        <th>Name</th>
                                        <th class="text-center" style="width: 100px">Price</th>
                                        <th class="text-center" style="width: 180px">Quantity</th>
                                        <th class="text-right" style="width: 120px">Subtotal</th>
                                        <th style="width: 80px"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($cartItems as $id => $item): ?>
                                        <tr>
                                            <td>
                                                <img src="<?= !empty($item['image']) ? base_url('uploads/products/' . $item['image']) : 'https://via.placeholder.com/100' ?>" 
                                                     alt="<?= esc($item['name']) ?>" class="img-fluid">
                                            </td>
                                            <td>
                                                <a href="<?= site_url('products/' . $item['id']) ?>">
                                                    <?= esc($item['name']) ?>
                                                </a>
                                            </td>
                                            <td class="text-center">
                                                <?= 'Rp ' . number_format($item['price'], 0, ',', '.') ?>
                                            </td>
                                            <td>
                                                <form action="<?= site_url('cart/update') ?>" method="post" class="update-cart-form">
                                                    <?= csrf_field() ?>
                                                    <input type="hidden" name="rowid" value="<?= $id ?>">
                                                    <div class="input-group input-group-sm">
                                                        <div class="input-group-prepend">
                                                            <button type="button" class="btn btn-outline-secondary btn-number" data-type="minus" data-field="qty">
                                                                <i class="fas fa-minus"></i>
                                                            </button>
                                                        </div>
                                                        <input type="number" name="qty" class="form-control form-control-sm text-center" 
                                                               value="<?= $item['quantity'] ?>" min="1" max="<?= $item['stock'] ?? 99 ?>" onchange="this.form.submit()">
                                                        <div class="input-group-append">
                                                            <button type="button" class="btn btn-outline-secondary btn-number" data-type="plus" data-field="qty">
                                                                <i class="fas fa-plus"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </td>
                                            <td class="text-right">
                                                <?= 'Rp ' . number_format($item['price'] * $item['quantity'], 0, ',', '.') ?>
                                            </td>
                                            <td class="text-center">
                                                <a href="<?= site_url('cart/remove/' . $id) ?>" class="text-danger" 
                                                   onclick="return confirm('Are you sure you want to remove this item?')">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="4" class="text-right"><strong>Total:</strong></td>
                                        <td class="text-right">
                                            <strong><?= 'Rp ' . number_format($cartSummary['total_price'] ?? 0, 0, ',', '.') ?></strong>
                                        </td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        <?php endif; ?>
                    </div>
                    <?php if (!empty($cartItems)): ?>
                        <div class="card-footer">
                            <div class="row">
                                <div class="col-md-6">
                                    <a href="<?= site_url('cart/clear') ?>" class="btn btn-outline-danger" 
                                       onclick="return confirm('Are you sure you want to clear your cart?')">
                                        <i class="fas fa-trash-alt mr-1"></i> Clear Cart
                                    </a>
                                </div>
                                <div class="col-md-6 text-right">
                                    <a href="<?= site_url('cart/checkout') ?>" class="btn btn-success">
                                        Proceed to Checkout <i class="fas fa-arrow-right ml-1"></i>
                                    </a>
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

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    // Handle quantity buttons
    $('.btn-number').click(function(e) {
        e.preventDefault();
        
        const fieldName = $(this).attr('data-field');
        const type = $(this).attr('data-type');
        const input = $("input[name='" + fieldName + "']");
        const currentVal = parseInt(input.val());
        const max = parseInt(input.attr('max'));
        const min = parseInt(input.attr('min'));
        
        if (!isNaN(currentVal)) {
            if (type === 'minus') {
                if (currentVal > min) {
                    input.val(currentVal - 1).change();
                }
            } else if (type === 'plus') {
                if (currentVal < max) {
                    input.val(currentVal + 1).change();
                }
            }
        } else {
            input.val(1);
        }
    });
    
    // Auto-submit form on quantity change
    $('.update-cart-form').on('change', 'input[name="qty"]', function() {
        $(this).closest('form').submit();
    });
});
</script>
<?= $this->endSection() ?>
