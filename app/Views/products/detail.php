<?= $this->extend('layouts/main') ?>

<?= $this->section('styles') ?>
<style>
.bg-gradient-purple {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container py-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= site_url() ?>">Home</a></li>
            <li class="breadcrumb-item"><a href="<?= site_url('products') ?>">Products</a></li>
            <?php if (!empty($product['category_name'])): ?>
                <li class="breadcrumb-item">
                    <a href="<?= site_url('products?category=' . $product['category_id']) ?>">
                        <?= esc($product['category_name']) ?>
                    </a>
                </li>
            <?php endif; ?>
            <li class="breadcrumb-item active" aria-current="page"><?= esc($product['name']) ?></li>
        </ol>
    </nav>

    <div class="row">
        <!-- Product Images -->
        <div class="col-lg-6">
            <div class="card mb-4">
                <div class="card-body p-0">
                    <div class="product-image-container">
                        <?php if (!empty($product['image'])): ?>
                            <img id="main-product-image" 
                                 src="<?= base_url('uploads/products/' . $product['image']) ?>" 
                                 class="img-fluid w-100" 
                                 alt="<?= esc($product['name']) ?>"
                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            <div style="display: none; width: 100%; height: 400px; background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); display: flex; align-items: center; justify-content: center; color: #6c757d;">
                                <i class="fas fa-image" style="font-size: 4rem; opacity: 0.5;"></i>
                            </div>
                        <?php else: ?>
                            <div style="width: 100%; height: 400px; background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); display: flex; align-items: center; justify-content: center; color: #6c757d;">
                                <i class="fas fa-image" style="font-size: 4rem; opacity: 0.5;"></i>
                            </div>
                        <?php endif; ?>
                    </div>
                    <?php if (!empty($product_images)): ?>
                        <div class="product-thumbnails d-flex flex-wrap gap-2 p-3">
                            <?php foreach ($product_images as $key => $image): ?>
                                <div class="thumbnail-item border rounded p-1 <?= $key === 0 ? 'active' : '' ?>" 
                                     style="width: 80px; height: 80px; overflow: hidden; cursor: pointer;"
                                     data-image="<?= base_url('uploads/products/' . $image) ?>">
                                    <img src="<?= base_url('uploads/products/' . $image) ?>" 
                                         class="img-fluid h-100 w-100 object-fit-cover" 
                                         alt="<?= esc($product['name']) ?> - Thumbnail <?= $key + 1 ?>"
                                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                    <div style="display: none; width: 100%; height: 100%; background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); display: flex; align-items: center; justify-content: center; color: #6c757d;">
                                        <i class="fas fa-image" style="font-size: 1rem; opacity: 0.5;"></i>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Share Buttons -->
            <div class="card mb-4">
                <div class="card-body">
                    <h6 class="mb-3">Share this product:</h6>
                    <div class="d-flex gap-2">
                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode(current_url()) ?>" 
                           target="_blank" class="btn btn-outline-primary btn-sm">
                            <i class="fab fa-facebook-f me-1"></i> Facebook
                        </a>
                        <a href="https://twitter.com/intent/tweet?url=<?= urlencode(current_url()) ?>&text=<?= urlencode($product['name']) ?>" 
                           target="_blank" class="btn btn-outline-info btn-sm">
                            <i class="fab fa-twitter me-1"></i> Twitter
                        </a>
                        <a href="https://wa.me/?text=<?= urlencode('Check out this product: ' . $product['name'] . ' - ' . current_url()) ?>" 
                           target="_blank" class="btn btn-outline-success btn-sm">
                            <i class="fab fa-whatsapp me-1"></i> WhatsApp
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Info -->
        <div class="col-lg-6">
            <div class="card mb-4">
                <div class="card-body">
                    <?php if ($product['is_featured']): ?>
                        <span class="badge bg-warning text-dark mb-2">
                            <i class="fas fa-star me-1"></i> Featured
                        </span>
                    <?php endif; ?>
                    
                    <?php if (!empty($product['discount_percent']) && $product['discount_percent'] > 0): ?>
                        <span class="badge bg-danger me-2 mb-2">
                            -<?= $product['discount_percent'] ?>% OFF
                        </span>
                    <?php endif; ?>
                    
                    <h1 class="h3 mb-2"><?= esc($product['name']) ?></h1>
                    
                    <!-- Items Sold -->
                    <div class="d-flex align-items-center mb-3">
                        <div class="text-success me-2">
                            <i class="fas fa-shopping-cart"></i>
                            <span class="fw-bold"><?= number_format($product['sold_count'] ?? 0) ?> </span>
                        </div>
                        <span class="text-muted small">&nbsp;items sold</span>
                    </div>
                    
                    <!-- Price -->
                    <div class="mb-4">
                        <?php if (!empty($product['discount_percent']) && $product['discount_percent'] > 0): ?>
                            <div class="d-flex align-items-center mb-2">
                                <h2 class="text-danger mb-0">Rp <?= number_format($product['price'] * (1 - $product['discount_percent'] / 100), 0, ',', '.') ?></h2>
                                <h4 class="text-muted text-decoration-line-through ms-3 mb-0">Rp <?= number_format($product['price'], 0, ',', '.') ?></h4>
                                <span class="badge bg-danger ms-2">Save <?= $product['discount_percent'] ?>%</span>
                            </div>
                        <?php else: ?>
                            <h2 class="text-primary mb-2">DP : Rp <?= number_format($product['price'], 0, ',', '.') ?></h2>
                        <?php endif; ?>
                        
                        <?php if (!empty($product['po_price_desc']) && $product['po_price_desc'] > 0): ?>
                            <div class="d-flex align-items-center">
                                <span class="badge bg-gradient-purple text-white px-3 py-2">
                                    <i class="fas fa-tag me-1"></i> PO Price: Rp <?= number_format($product['po_price_desc'], 0, ',', '.') ?>
                                </span>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Short Description -->
                    <?php if (!empty($product['short_description'])): ?>
                        <div class="mb-4">
                            <p class="lead"><?= esc($product['short_description']) ?></p>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Add to Cart -->
                    <div class="mb-4">
                        <form id="add-to-cart-form" class="row g-2">
                            <div class="col-auto">
                                <div class="input-group" style="width: 120px;">
                                    <button type="button" class="btn btn-outline-secondary" id="decrement-qty">-</button>
                                    <input type="number" class="form-control text-center" id="quantity" name="quantity" value="1" min="1" max="<?= $product['stock'] ?>">
                                    <button type="button" class="btn btn-outline-secondary" id="increment-qty">+</button>
                                </div>
                            </div>
                            <div class="col">
                                <button type="submit" class="btn btn-primary btn-lg w-100" id="add-to-cart-btn" <?= $product['stock'] <= 0 ? 'disabled' : '' ?>>
                                    <i class="fas fa-shopping-cart me-2"></i>
                                    <?= $product['stock'] > 0 ? 'Add to Cart' : 'Out of Stock' ?>
                                </button>
                            </div>
                        </form>
                    </div>
                    
                    <!-- Product Meta -->
                    <div class="border-top pt-3">
                        <div class="row small text-muted">
                            <?php if ($product['sku']): ?>
                                <div class="col-md-6 mb-2">
                                    <strong>SKU:</strong> <?= $product['sku'] ?>
                                </div>
                            <?php endif; ?>
                            <div class="col-md-6 mb-2">
                                <strong>Category:</strong> 
                                <a href="<?= site_url('products?category=' . $product['category_id']) ?>">
                                    <?= esc($product['category_name']) ?>
                                </a>
                            </div>
                            <div class="col-12">
                                <strong>Availability:</strong> 
                                <?php if ($product['stock'] > 0): ?>
                                    <span class="text-success">In Stock (<?= $product['stock'] ?> available)</span>
                                <?php else: ?>
                                    <span class="text-danger">Out of Stock</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Product Tabs -->
            <div class="card mb-4">
                <div class="card-header bg-white">
                    <ul class="nav nav-tabs card-header-tabs" id="productTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="description-tab" data-bs-toggle="tab" 
                                    data-bs-target="#description" type="button" role="tab" aria-controls="description" 
                                    aria-selected="true">
                                Description
                            </button>
                        </li>
                        <?php if (!empty($product['specifications'])): ?>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="specifications-tab" data-bs-toggle="tab" 
                                        data-bs-target="#specifications" type="button" role="tab" aria-controls="specifications" 
                                        aria-selected="false">
                                    Specifications
                                </button>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="productTabsContent">
                        <!-- Description Tab -->
                        <div class="tab-pane fade show active" id="description" role="tabpanel" aria-labelledby="description-tab">
                            <?= $product['description'] ? nl2br(esc($product['description'])) : '<p class="text-muted">No description available.</p>' ?>
                        </div>
                        
                        <!-- Specifications Tab -->
                        <?php if (!empty($product['specifications'])): ?>
                            <div class="tab-pane fade" id="specifications" role="tabpanel" aria-labelledby="specifications-tab">
                                <?php 
                                $specs = json_decode($product['specifications'], true);
                                if (json_last_error() === JSON_ERROR_NONE && !empty($specs)): 
                                ?>
                                    <table class="table table-bordered">
                                        <tbody>
                                            <?php foreach ($specs as $key => $value): ?>
                                                <tr>
                                                    <th width="30%"><?= esc($key) ?></th>
                                                    <td><?= is_array($value) ? esc(implode(', ', $value)) : esc($value) ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                <?php else: ?>
                                    <p class="text-muted">No specifications available.</p>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Related Products -->
    <?php if (!empty($relatedProducts)): ?>
        <div class="row mt-5">
            <div class="col-12">
                <h3 class="mb-4">You May Also Like</h3>
                <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-4">
                    <?php foreach ($relatedProducts as $related): ?>
                        <div class="col">
                            <div class="card h-100 product-card">
                                <div class="position-relative">
                                    <a href="<?= site_url('products/' . $related['slug']) ?>">
                                        <?php if (!empty($related['image'])): ?>
                                            <img src="<?= base_url('uploads/products/' . $related['image']) ?>" 
                                                 class="card-img-top product-image" alt="<?= esc($related['name']) ?>"
                                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                                                 style="height: 200px; object-fit: cover;">
                                            <div style="display: none; width: 100%; height: 200px; background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); display: flex; align-items: center; justify-content: center; color: #6c757d;">
                                                <i class="fas fa-image" style="font-size: 2rem; opacity: 0.5;"></i>
                                            </div>
                                        <?php else: ?>
                                            <div style="width: 100%; height: 200px; background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); display: flex; align-items: center; justify-content: center; color: #6c757d;">
                                                <i class="fas fa-image" style="font-size: 2rem; opacity: 0.5;"></i>
                                            </div>
                                        <?php endif; ?>
                                    </a>
                                    <?php if ($related['is_featured']): ?>
                                        <span class="position-absolute top-0 start-0 bg-warning text-dark px-2 py-1 m-2 rounded small">
                                            <i class="fas fa-star me-1"></i> Featured
                                        </span>
                                    <?php endif; ?>
                                    <?php if (!empty($related['discount_percent']) && $related['discount_percent'] > 0): ?>
                                        <span class="position-absolute top-0 end-0 bg-danger text-white px-2 py-1 m-2 rounded small">
                                            -<?= $related['discount_percent'] ?>%
                                        </span>
                                    <?php endif; ?>
                                </div>
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title">
                                        <a href="<?= site_url('products/' . $related['slug']) ?>" class="text-dark">
                                            <?= esc(character_limiter($related['name'], 50)) ?>
                                        </a>
                                    </h5>
                                    <div class="mt-auto">
                                        <?php if (!empty($related['discount_percent']) && $related['discount_percent'] > 0): ?>
                                            <div class="d-flex align-items-center">
                                                <h5 class="mb-0 text-danger">Rp <?= number_format($related['price'] * (1 - $related['discount_percent'] / 100), 0, ',', '.') ?></h5>
                                                <small class="text-muted text-decoration-line-through ms-2">Rp <?= number_format($related['price'], 0, ',', '.') ?></small>
                                            </div>
                                        <?php else: ?>
                                            <h5 class="mb-0">Rp <?= number_format($related['price'], 0, ',', '.') ?></h5>
                                        <?php endif; ?>
                                        <button type="button" class="btn btn-primary btn-sm w-100 mt-2 add-to-cart" 
                                                data-id="<?= $related['id'] ?>" 
                                                data-name="<?= esc($related['name']) ?>"
                                                data-price="<?= !empty($related['discount_percent']) && $related['discount_percent'] > 0 ? $related['price'] * (1 - $related['discount_percent'] / 100) : $related['price'] ?>">
                                            <i class="fas fa-shopping-cart me-1"></i> Add to Cart
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    // Handle quantity buttons
    $('#increment-qty').on('click', function() {
        const input = $('#quantity');
        let value = parseInt(input.val());
        const max = parseInt(input.attr('max')) || 100;
        if (value < max) {
            input.val(value + 1);
        }
    });
    
    $('#decrement-qty').on('click', function() {
        const input = $('#quantity');
        let value = parseInt(input.val());
        if (value > 1) {
            input.val(value - 1);
        }
    });
    
    // Handle image thumbnails
    $('.thumbnail-item').on('click', function() {
        const imageUrl = $(this).data('image');
        $('#main-product-image').attr('src', imageUrl);
        $('.thumbnail-item').removeClass('border-primary');
        $(this).addClass('border-primary');
    });
    
    // Add to cart form submission
    $('#add-to-cart-form').on('submit', function(e) {
        e.preventDefault();
        
        const $form = $(this);
        const $button = $form.find('button[type="submit"]');
        const originalText = $button.html();
        
        // Show loading state
        $button.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1" role="status"></span> Adding...');
        
        // Get form data
        const formData = $form.serializeArray();
        
        // Send AJAX request
        $.ajax({
            url: '<?= site_url('cart/add') ?>',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    // Update cart count
                    const cartCount = parseInt($('.cart-count').text()) || 0;
                    const quantity = parseInt($('#quantity').val()) || 1;
                    $('.cart-count').text(cartCount + quantity).removeClass('d-none');
                    
                    // Show success message
                    Swal.fire({
                        icon: 'success',
                        title: 'Added to Cart',
                        text: 'Product has been added to your cart',
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message || 'Failed to add item to cart.'
                    });
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred. Please try again.'
                });
            },
            complete: function() {
                // Restore button state
                $button.prop('disabled', false).html(originalText);
            }
        });
    });
    
    // Handle add to cart from related products
    $('.add-to-cart').on('click', function() {
        const productId = $(this).data('id');
        const productName = $(this).data('name');
        const price = $(this).data('price');
        const quantity = 1;
        
        // Show loading state
        const $button = $(this);
        const originalText = $button.html();
        $button.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1" role="status"></span> Adding...');
        
        // Send AJAX request
        $.ajax({
            url: '<?= site_url('cart/add') ?>',
            type: 'POST',
            data: {
                '<?= csrf_token() ?>': '<?= csrf_hash() ?>',
                'product_id': productId,
                'quantity': quantity,
                'price': price
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    // Update cart count
                    const cartCount = parseInt($('.cart-count').text()) || 0;
                    $('.cart-count').text(cartCount + quantity).removeClass('d-none');
                    
                    // Show success message
                    Swal.fire({
                        icon: 'success',
                        title: 'Added to Cart',
                        text: productName + ' has been added to your cart',
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message || 'Failed to add item to cart.'
                    });
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred. Please try again.'
                });
            },
            complete: function() {
                // Restore button state
                $button.prop('disabled', false).html(originalText);
            }
        });
    });
});
</script>

<style>
/* Product Image Container */
.product-image-container {
    position: relative;
    padding-top: 100%;
    background-color: #f8f9fa;
    overflow: hidden;
}

.product-image-container img {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: contain;
    padding: 1rem;
    transition: transform 0.3s ease;
}

/* Thumbnails */
.thumbnail-item {
    border: 2px solid transparent;
    transition: all 0.3s ease;
}

.thumbnail-item:hover, .thumbnail-item.active {
    border-color: #0d6efd;
}

/* Product Tabs */
.nav-tabs .nav-link {
    color: #6c757d;
    font-weight: 500;
    border: none;
    border-bottom: 2px solid transparent;
    padding: 0.75rem 1.25rem;
    transition: all 0.2s;
}

.nav-tabs .nav-link.active, 
.nav-tabs .nav-link:hover {
    color: #0d6efd;
    background: none;
    border-color: #0d6efd;
}

/* Related Products */
.section-title {
    position: relative;
    padding-bottom: 0.5rem;
    font-weight: 600;
}

.section-title:after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 50px;
    height: 3px;
    background-color: #0d6efd;
}

/* Responsive adjustments */
@media (max-width: 767.98px) {
    .product-image-container {
        padding-top: 100%;
    }
    
    .product-thumbnails {
        justify-content: center;
    }
}

/* Quantity input */
.quantity-input {
    -moz-appearance: textfield;
    text-align: center;
}

.quantity-input::-webkit-outer-spin-button,
.quantity-input::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

/* Product card hover effect */
.product-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    border: 1px solid #e9ecef;
}

.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
}

/* Price styling */
.original-price {
    text-decoration: line-through;
    color: #6c757d;
}

.discounted-price {
    color: #dc3545;
    font-weight: 600;
}
</style>
<?= $this->endSection() ?>