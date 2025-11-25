<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Home - <?= getenv('site_name') ?><?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- Hero Section -->
<section class="hero-section bg-light py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 mb-4">Welcome to <?= getenv('site_name') ?></h1>
                <p class="lead mb-4">Discover amazing products at great prices. Shop now and get the best deals on our wide range of products.</p>
                <a href="<?= site_url('products') ?>" class="btn btn-primary btn-lg">Shop Now</a>
            </div>
            <div class="col-lg-6">
                <img src="<?= base_url('assets/img/logo.png') ?>" alt="Hero Image" class="img-fluid rounded">
            </div>
        </div>
    </div>
</section>

<!-- Categories Section -->
<?php if (!empty($categories)): ?>
<section class="py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Shop by Category</h2>
            <a href="<?= site_url('categories') ?>" class="btn btn-outline-primary">View All</a>
        </div>
        
        <div class="row">
            <?php foreach (array_slice($categories, 0, 4) as $category): ?>
                <div class="col-md-3 col-6 mb-4">
                    <a href="<?= site_url('category/' . $category['slug']) ?>" class="text-decoration-none">
                        <div class="card category-card h-100">
                            <div class="position-relative">
                                <img src="<?= !empty($category['image']) ? base_url('uploads/categories/' . $category['image']) : base_url('assets/img/no-image.png') ?>" 
                                     class="card-img-top" alt="<?= esc($category['name']) ?>" style="height: 200px; object-fit: cover;">
                                <div class="card-img-overlay d-flex align-items-end p-0">
                                    <div class="w-100 bg-dark bg-opacity-75 text-white p-3">
                                        <h5 class="card-title mb-0"><?= esc($category['name']) ?></h5>
                                        <small><?= $category['product_count'] ?? 0 ?> items</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>
<!-- Featured Products -->
<?php if (!empty($featured_products)): ?>
<section class="py-5 bg-light">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Featured Products</h2>
            <a href="<?= site_url('products') ?>" class="btn btn-outline-primary">View All</a>
        </div>
        
        <div class="row">
            <?php foreach ($featured_products as $product): ?>
                <div class="col-lg-3 col-md-4 col-6 mb-4">
                    <div class="card product-card h-100">
                        <div class="position-relative">
                            <a href="<?= site_url('product/' . $product['slug']) ?>">
                                <img src="<?= !empty($product['image']) ? base_url('uploads/products/' . $product['image']) : base_url('assets/img/no-image.png') ?>" 
                                     class="card-img-top product-image" alt="<?= esc($product['name']) ?>">
                            </a>
                            <span class="badge bg-primary position-absolute top-0 end-0 m-2">Featured</span>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <span class="badge bg-secondary"><?= esc($product['category_name'] ?? 'Uncategorized') ?></span>
                                <span class="badge bg-<?= $product['stock'] > 0 ? 'success' : 'danger' ?>">
                                    <?= $product['stock'] > 0 ? 'In Stock' : 'Out of Stock' ?>
                                </span>
                            </div>
                            <h5 class="card-title">
                                <a href="<?= site_url('product/' . $product['slug']) ?>" class="text-dark text-decoration-none">
                                    <?= esc(character_limiter($product['name'], 50)) ?>
                                </a>
                            </h5>
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <h5 class="mb-0 text-primary"><?= number_to_currency($product['price'], 'IDR') ?></h5>
                                <button class="btn btn-sm btn-outline-primary add-to-cart" 
                                        data-id="<?= $product['id'] ?>"
                                        data-name="<?= esc($product['name']) ?>"
                                        data-price="<?= $product['price'] ?>">
                                    <i class="fas fa-cart-plus"></i> Add to Cart
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- New Arrivals -->
<?php if (!empty($new_arrivals)): ?>
<section class="py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>New Arrivals</h2>
            <a href="<?= site_url('products?sort=newest') ?>" class="btn btn-outline-primary">View All</a>
        </div>
        
        <div class="row">
            <?php foreach ($new_arrivals as $product): ?>
                <div class="col-lg-3 col-md-4 col-6 mb-4">
                    <div class="card product-card h-100">
                        <div class="position-relative">
                            <a href="<?= site_url('product/' . $product['slug']) ?>">
                                <img src="<?= !empty($product['image']) ? base_url('uploads/products/' . $product['image']) : base_url('assets/img/no-image.png') ?>" 
                                     class="card-img-top product-image" alt="<?= esc($product['name']) ?>">
                            </a>
                            <span class="badge bg-success position-absolute top-0 end-0 m-2">New</span>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">
                                <a href="<?= site_url('product/' . $product['slug']) ?>" class="text-dark text-decoration-none">
                                    <?= esc(character_limiter($product['name'], 50)) ?>
                                </a>
                            </h5>
                            <p class="card-text text-muted small mb-2">
                                <?= esc(character_limiter($product['description'] ?? '', 60)) ?>
                            </p>
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0 text-primary"><?= number_to_currency($product['price'], 'IDR') ?></h5>
                                <button class="btn btn-sm btn-outline-primary add-to-cart" 
                                        data-id="<?= $product['id'] ?>"
                                        data-name="<?= esc($product['name']) ?>"
                                        data-price="<?= $product['price'] ?>">
                                    <i class="fas fa-cart-plus"></i> Add to Cart
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Sale Products -->
<?php if (!empty($sale_products)): ?>
<section class="py-5 bg-light">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Special Offers</h2>
            <a href="<?= site_url('products?sort=discount') ?>" class="btn btn-outline-primary">View All</a>
        </div>
        
        <div class="row">
            <?php foreach ($sale_products as $product): ?>
                <div class="col-lg-3 col-md-4 col-6 mb-4">
                    <div class="card product-card h-100 border-danger">
                        <div class="position-relative">
                            <a href="<?= site_url('product/' . $product['slug']) ?>">
                                <img src="<?= !empty($product['image']) ? base_url('uploads/products/' . $product['image']) : base_url('assets/img/no-image.png') ?>" 
                                     class="card-img-top product-image" alt="<?= esc($product['name']) ?>">
                            </a>
                            <span class="badge bg-danger position-absolute top-0 end-0 m-2">Sale</span>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">
                                <a href="<?= site_url('product/' . $product['slug']) ?>" class="text-dark text-decoration-none">
                                    <?= esc(character_limiter($product['name'], 50)) ?>
                                </a>
                            </h5>
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="text-muted text-decoration-line-through me-2">
                                        <?= number_to_currency($product['price'] * 1.2, 'IDR') ?>
                                    </span>
                                    <span class="h5 text-danger mb-0">
                                        <?= number_to_currency($product['price'], 'IDR') ?>
                                    </span>
                                </div>
                                <button class="btn btn-sm btn-danger add-to-cart" 
                                        data-id="<?= $product['id'] ?>"
                                        data-name="<?= esc($product['name']) ?>"
                                        data-price="<?= $product['price'] ?>">
                                    <i class="fas fa-cart-plus"></i> Add to Cart
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Call to Action -->
<section class="py-5 bg-primary text-white">
    <div class="container text-center">
        <h2 class="mb-4">Ready to find your perfect product?</h2>
        <p class="lead mb-4">Explore our wide range of products and find exactly what you're looking for.</p>
        <a href="<?= site_url('products') ?>" class="btn btn-light btn-lg">Browse All Products</a>
    </div>
</section>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    // Add to cart functionality
    $('.add-to-cart').on('click', function() {
        const productId = $(this).data('id');
        const productName = $(this).data('name');
        const price = $(this).data('price');
        
        // Show loading state
        const $btn = $(this);
        const $icon = $btn.find('i');
        const originalHtml = $btn.html();
        $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Adding...');
        
        // Add to cart via AJAX
        $.ajax({
            url: '<?= site_url('cart/add') ?>',
            method: 'POST',
            data: {
                'product_id': productId,
                'quantity': 1,
                'price': price,
                '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
            },
            success: function(response) {
                // Update cart count in navbar
                const cartCount = parseInt($('.cart-count').text()) || 0;
                $('.cart-count').text(cartCount + 1).removeClass('d-none');
                
                // Show success message
                Swal.fire({
                    icon: 'success',
                    title: 'Added to Cart',
                    text: productName + ' has been added to your cart',
                    showConfirmButton: false,
                    timer: 1500
                });
            },
            error: function(xhr) {
                let errorMessage = 'Failed to add item to cart';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                Swal.fire('Error', errorMessage, 'error');
            },
            complete: function() {
                // Restore button state
                $btn.prop('disabled', false).html(originalHtml);
            }
        });
    });
});
</script>
<?= $this->endSection() ?>
