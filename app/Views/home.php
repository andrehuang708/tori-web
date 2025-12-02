<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Home - <?= getenv('site_name') ?><?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
/* Mobile-First Product Grid Styles */
.product-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 0.75rem;
}

/* Mobile: Horizontal Layout */
@media (max-width: 767px) {
    .product-grid {
        gap: 0.4rem;
    }
    
    .product-card {
        min-height: 120px;
        border-radius: 4px;
        flex-direction: row; /* Horizontal */
        align-items: center;
    }
    
    .product-image-container {
        width: 120px;
        height: 120px;
    }
    
    .product-body {
        padding: 0.4rem 0.5rem;
        min-height: 120px;
        flex: 1;
        justify-content: center;
    }
    
    .product-category {
        font-size: 0.6rem;
        margin-bottom: 0.1rem;
    }
    
    .product-title {
        font-size: 0.75rem;
        margin-bottom: 0.2rem;
        line-height: 1.0;
    }
    
    .product-details {
        margin-bottom: 0;
        gap: 0.4rem;
    }
    
    .price-current {
        font-size: 0.8rem;
    }
    
    .price-original {
        font-size: 0.6rem;
    }
    
    .btn-add-cart {
        padding: 0.25rem 0.4rem;
        font-size: 0.65rem;
        min-width: 28px;
        height: 28px;
    }
    
    .badge-featured, .badge-discount {
        font-size: 0.5rem;
        padding: 1px 2px;
    }
    
    .product-badges {
        top: 2px;
        left: 2px;
        right: 2px;
    }
}

/* Desktop: Grid Layout */
@media (min-width: 768px) {
    .product-grid {
        grid-template-columns: repeat(3, 1fr);
        gap: 1.5rem;
    }
    
    .product-card {
        min-height: auto;
        border-radius: 8px;
        flex-direction: column; /* Vertical for grid */
        align-items: stretch;
    }
    
    .product-image-container {
        width: 100%;
        height: 200px;
        position: relative;
        padding-top: 0;
    }
    
    .product-body {
        padding: 1rem;
        min-height: auto;
        flex: 1;
        display: flex;
        flex-direction: column;
    }
    
    .product-category {
        font-size: 0.75rem;
        margin-bottom: 0.5rem;
    }
    
    .product-title {
        font-size: 0.95rem;
        margin-bottom: 0.75rem;
        line-height: 1.3;
    }
    
    .product-details {
        margin-bottom: 1rem;
        gap: 0.75rem;
        flex-direction: column;
        align-items: flex-start;
    }
    
    .price-current {
        font-size: 1.1rem;
    }
    
    .price-original {
        font-size: 0.8rem;
    }
    
    .btn-add-cart {
        padding: 0.75rem 1rem;
        font-size: 0.85rem;
        min-width: auto;
        height: auto;
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }
    
    .btn-add-cart::before {
        content: "Add to Cart";
        margin-right: 0.5rem;
    }
    
    .btn-add-cart i {
        margin: 0;
    }
    
    .badge-featured, .badge-discount {
        font-size: 0.6rem;
        padding: 2px 6px;
    }
    
    .product-badges {
        top: 10px;
        left: 10px;
        right: 10px;
    }
}

/* Large Desktop: 4 columns */
@media (min-width: 1200px) {
    .product-grid {
        grid-template-columns: repeat(4, 1fr);
    }
}

/* Tablet: 2 columns */
@media (min-width: 768px) and (max-width: 991px) {
    .product-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

/* Base Product Card Styles */
.product-card {
    border: 1px solid #e9ecef;
    border-radius: 8px;
    overflow: hidden;
    transition: all 0.2s ease;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    display: flex;
    background: white;
}

.product-card:hover {
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    border-color: #dee2e6;
}

/* Product Image Container - Base Styles */
.product-image-container {
    position: relative;
    overflow: hidden;
    flex-shrink: 0;
}

/* Product Image Placeholder Styles */
.product-image {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.product-image-placeholder {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: #f8f9fa;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #adb5bd;
    font-size: 1.5rem;
}

.product-image-placeholder i {
    font-size: 1.5rem;
    opacity: 0.5;
}

@media (max-width: 767px) {
    .product-image-placeholder i {
        font-size: 1.2rem;
    }
}

/* Product Badges - Minimalist */
.product-badges {
    position: absolute;
    top: 4px;
    left: 4px;
    right: 4px;
    display: flex;
    justify-content: space-between;
    pointer-events: none;
    z-index: 2;
}

.badge-featured, .badge-discount {
    font-size: 0.6rem;
    padding: 1px 4px;
    border-radius: 12px;
    font-weight: 600;
}

/* Product Body - Minimalist Layout */
.product-body {
    padding: 0.5rem 0.75rem;
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: center;
    min-height: 120px;
}

@media (min-width: 768px) {
    .product-body {
        padding: 0.75rem 1rem;
        min-height: 120px;
    }
}

.product-category {
    font-size: 0.65rem;
    color: #6c757d;
    margin-bottom: 0.15rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

@media (min-width: 768px) {
    .product-category {
        font-size: 0.7rem;
        margin-bottom: 0.2rem;
    }
}

.product-title {
    font-size: 0.8rem;
    font-weight: 600;
    margin-bottom: 0.3rem;
    line-height: 1.1;
    color: #212529;
}

@media (min-width: 768px) {
    .product-title {
        font-size: 0.9rem;
        margin-bottom: 0.4rem;
    }
}

.product-title a {
    color: inherit;
    text-decoration: none;
    transition: color 0.2s;
}

.product-title a:hover {
    color: #007bff;
}

/* Product Details Row - Minimalist */
.product-details {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 0;
    gap: 0.5rem;
}

/* Price Section - IDR Currency */
.product-price {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 0.25rem;
}

.price-current {
    font-weight: 600;
    color: #2c3e50;
    font-size: 0.9rem;
}

.price-original {
    text-decoration: line-through;
    color: #95a5a6;
    font-size: 0.8rem;
}

.price-po {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 2px 6px;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 500;
    margin-left: 0.25rem;
}

.price-current {
    font-size: 0.9rem;
    font-weight: 700;
    color: #dc3545;
}

@media (min-width: 768px) {
    .price-current {
        font-size: 1rem;
    }
}

.price-original {
    font-size: 0.7rem;
    color: #6c757d;
    text-decoration: line-through;
    margin-left: 0.5rem;
}

/* Product Actions - Minimalist */
.product-actions {
    display: flex;
    align-items: center;
}

.btn-add-cart {
    background: #007bff;
    border: none;
    border-radius: 4px;
    padding: 0.4rem 0.8rem;
    font-weight: 500;
    transition: all 0.2s ease;
    font-size: 0.75rem;
    color: white;
    white-space: nowrap;
    display: flex;
    align-items: center;
    justify-content: center;
    min-width: 36px;
    height: 36px;
}

@media (min-width: 768px) {
    .btn-add-cart {
        padding: 0.5rem 1rem;
        font-size: 0.8rem;
        min-width: 40px;
        height: 40px;
    }
}

.btn-add-cart:hover {
    background: #0056b3;
    transform: translateY(-1px);
}

/* Touch Optimizations for Mobile */
@media (max-width: 767px) {
    .btn-add-cart:active {
        transform: scale(0.95);
        background: #0056b3;
    }
    
    .product-card:active {
        transform: scale(0.98);
    }
}
</style>
<?= $this->endSection() ?>

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

<!-- Featured Products -->
<?php if (!empty($featured_products)): ?>
<section class="py-5 bg-light">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Featured Products</h2>
            <a href="<?= site_url('products') ?>" class="btn btn-outline-primary">View All</a>
        </div>
        
        <div class="product-grid">
            <?php foreach ($featured_products as $product): ?>
                <div class="product-card">
                    <!-- Product Image -->
                    <div class="product-image-container">
                        <a href="<?= site_url('products/' . $product['slug']) ?>">
                            <?php if (!empty($product['image'])): ?>
                                <img src="<?= base_url('uploads/products/' . $product['image']) ?>" 
                                     class="product-image" alt="<?= esc($product['name']) ?>"
                                     onerror="this.onerror=null; this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                <div class="product-image-placeholder" style="display: none;">
                                    <i class="fas fa-image"></i>
                                </div>
                            <?php else: ?>
                                <div class="product-image-placeholder">
                                    <i class="fas fa-image"></i>
                                </div>
                            <?php endif; ?>
                        </a>
                        <div class="product-badges">
                            <span class="badge-featured bg-warning text-white">
                                <i class="fas fa-star"></i> Featured
                            </span>
                            <?php if (!empty($product['original_price']) && $product['original_price'] > $product['price']): 
                                $discountPercent = round((($product['original_price'] - $product['price']) / $product['original_price']) * 100);
                            ?>
                                <span class="badge-discount bg-danger text-white">
                                    -<?= $discountPercent ?>%
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Product Body - Minimalist -->
                    <div class="product-body">
                        <div>
                            <div class="product-category">
                                <?= esc($product['category_name']) ?>
                            </div>
                            <h5 class="product-title">
                                <a href="<?= site_url('products/' . $product['slug']) ?>" class="text-dark text-decoration-none">
                                    <?= esc($product['name']) ?>
                                </a>
                            </h5>
                            
                            <!-- Price and Button -->
                            <div class="product-details">
                                <div class="product-price">
                                    <?php if (!empty($product['original_price']) && $product['original_price'] > $product['price']): ?>
                                        <span class="price-current">Rp <?= number_format($product['price'], 0, ',', '.') ?></span>
                                        <span class="price-original">Rp <?= number_format($product['original_price'], 0, ',', '.') ?></span>
                                    <?php else: ?>
                                        <span class="price-current">Rp <?= number_format($product['price'], 0, ',', '.') ?></span>
                                    <?php endif; ?>
                                    <?php if (!empty($product['po_price_desc']) && $product['po_price_desc'] > 0): ?>
                                        <span class="price-po">PO: Rp <?= number_format($product['po_price_desc'], 0, ',', '.') ?></span>
                                    <?php endif; ?>
                                </div>
                                
                                <!-- Add to Cart Button -->
                                <div class="product-actions">
                                    <button type="button" class="btn-add-cart add-to-cart" 
                                            data-id="<?= $product['id'] ?>" 
                                            data-name="<?= esc($product['name']) ?>"
                                            data-price="<?= $product['price'] ?>">
                                        <i class="fas fa-cart-plus"></i>
                                    </button>
                                </div>
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
        
        <div class="product-grid">
            <?php foreach ($new_arrivals as $product): ?>
                <div class="product-card">
                    <!-- Product Image -->
                    <div class="product-image-container">
                        <a href="<?= site_url('products/' . $product['slug']) ?>">
                            <?php if (!empty($product['image'])): ?>
                                <img src="<?= base_url('uploads/products/' . $product['image']) ?>" 
                                     class="product-image" alt="<?= esc($product['name']) ?>"
                                     onerror="this.onerror=null; this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                <div class="product-image-placeholder" style="display: none;">
                                    <i class="fas fa-image"></i>
                                </div>
                            <?php else: ?>
                                <div class="product-image-placeholder">
                                    <i class="fas fa-image"></i>
                                </div>
                            <?php endif; ?>
                        </a>
                        <div class="product-badges">
                            <span class="badge-featured bg-success text-white">
                                <i class="fas fa-sparkles"></i> New
                            </span>
                            <?php if (!empty($product['original_price']) && $product['original_price'] > $product['price']): 
                                $discountPercent = round((($product['original_price'] - $product['price']) / $product['original_price']) * 100);
                            ?>
                                <span class="badge-discount bg-danger text-white">
                                    -<?= $discountPercent ?>%
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Product Body - Minimalist -->
                    <div class="product-body">
                        <div>
                            <div class="product-category">
                                <?= esc($product['category_name']) ?>
                            </div>
                            <h5 class="product-title">
                                <a href="<?= site_url('products/' . $product['slug']) ?>" class="text-dark text-decoration-none">
                                    <?= esc($product['name']) ?>
                                </a>
                            </h5>
                            
                            <!-- Price and Button -->
                            <div class="product-details">
                                <div class="product-price">
                                    <?php if (!empty($product['original_price']) && $product['original_price'] > $product['price']): ?>
                                        <span class="price-current">Rp <?= number_format($product['price'], 0, ',', '.') ?></span>
                                        <span class="price-original">Rp <?= number_format($product['original_price'], 0, ',', '.') ?></span>
                                    <?php else: ?>
                                        <span class="price-current">Rp <?= number_format($product['price'], 0, ',', '.') ?></span>
                                    <?php endif; ?>
                                    <?php if (!empty($product['po_price_desc']) && $product['po_price_desc'] > 0): ?>
                                        <span class="price-po">PO: Rp <?= number_format($product['po_price_desc'], 0, ',', '.') ?></span>
                                    <?php endif; ?>
                                </div>
                                
                                <!-- Add to Cart Button -->
                                <div class="product-actions">
                                    <button type="button" class="btn-add-cart add-to-cart" 
                                            data-id="<?= $product['id'] ?>" 
                                            data-name="<?= esc($product['name']) ?>"
                                            data-price="<?= $product['price'] ?>">
                                        <i class="fas fa-cart-plus"></i>
                                    </button>
                                </div>
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
