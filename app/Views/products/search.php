<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container py-5">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-lg-3">
            <!-- Search Filters -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Search Filters</h5>
                </div>
                <div class="card-body">
                    <form action="<?= site_url('products/search') ?>" method="get">
                        <input type="hidden" name="q" value="<?= esc($searchQuery) ?>">
                        
                        <!-- Price Range -->
                        <div class="mb-4">
                            <h6 class="font-weight-bold">Price Range</h6>
                            <div class="row g-2">
                                <div class="col">
                                    <label class="form-label small mb-1">Min</label>
                                    <input type="number" class="form-control form-control-sm" name="min_price" 
                                           value="<?= esc($filters['min_price'] ?? '') ?>" placeholder="$">
                                </div>
                                <div class="col">
                                    <label class="form-label small mb-1">Max</label>
                                    <input type="number" class="form-control form-control-sm" name="max_price" 
                                           value="<?= esc($filters['max_price'] ?? '') ?>" placeholder="$">
                                </div>
                            </div>
                        </div>
                        
                        <!-- Categories -->
                        <?php if (!empty($categories)): ?>
                            <div class="mb-4">
                                <h6 class="font-weight-bold">Categories</h6>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="category" id="all-categories" 
                                           value="" <?= empty($filters['category_id']) ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="all-categories">
                                        All Categories
                                    </label>
                                </div>
                                <?php foreach ($categories as $category): ?>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="category" 
                                               id="cat-<?= $category['id'] ?>" value="<?= $category['id'] ?>"
                                               <?= ($filters['category_id'] ?? '') == $category['id'] ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="cat-<?= $category['id'] ?>">
                                            <?= esc($category['name']) ?>
                                            <span class="text-muted small">(<?= $category['product_count'] ?>)</span>
                                        </label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Availability -->
                        <div class="mb-4">
                            <h6 class="font-weight-bold">Availability</h6>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="in_stock" id="in-stock" 
                                       value="1" <?= !empty($filters['in_stock']) ? 'checked' : '' ?>>
                                <label class="form-check-label" for="in-stock">
                                    In Stock Only
                                </label>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary btn-sm w-100">Apply Filters</button>
                        <a href="<?= site_url('products/search?q=' . urlencode($searchQuery)) ?>" class="btn btn-outline-secondary btn-sm w-100 mt-2">
                            Reset Filters
                        </a>
                    </form>
                </div>
            </div>
            
            <!-- Featured Products -->
            <?php if (!empty($featuredProducts)): ?>
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Featured Products</h5>
                    </div>
                    <div class="card-body p-0">
                        <?php foreach ($featuredProducts as $product): ?>
                            <div class="p-3 border-bottom">
                                <div class="row g-0">
                                    <div class="col-4">
                                        <a href="<?= site_url('products/' . $product['slug']) ?>">
                                            <?php if (!empty($product['image'])): ?>
                                                <img src="<?= base_url('uploads/products/' . $product['image']) ?>" 
                                                     class="img-fluid rounded" alt="<?= esc($product['name']) ?>"
                                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                                                     style="width: 100px; height: 100px; object-fit: cover;">
                                                <div style="display: none; width: 100px; height: 100px; background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #6c757d;">
                                                    <i class="fas fa-image" style="font-size: 1.5rem; opacity: 0.5;"></i>
                                                </div>
                                            <?php else: ?>
                                                <div style="width: 100px; height: 100px; background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #6c757d;">
                                                    <i class="fas fa-image" style="font-size: 1.5rem; opacity: 0.5;"></i>
                                                </div>
                                            <?php endif; ?>
                                        </a>
                                    </div>
                                    <div class="col-8 ps-3">
                                        <h6 class="card-title mb-1">
                                            <a href="<?= site_url('products/' . $product['slug']) ?>" class="text-dark">
                                                <?= esc(character_limiter($product['name'], 30)) ?>
                                            </a>
                                        </h6>
                                        <div class="text-warning mb-1 small">
                                            <?= str_repeat('★', 5) ?>
                                        </div>
                                        <div class="text-primary fw-bold">Rp <?= number_format($product['price'], 0, ',', '.') ?></div>
                                        <?php if (!empty($product['po_price_desc']) && $product['po_price_desc'] > 0): ?>
                                            <div class="text-muted small">PO: Rp <?= number_format($product['po_price_desc'], 0, ',', '.') ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Main Content -->
        <div class="col-lg-9">
            <!-- Search Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-1">Search Results for: "<?= esc($searchQuery) ?>"</h1>
                    <p class="text-muted mb-0"><?= $totalResults ?> result<?= $totalResults != 1 ? 's' : '' ?> found</p>
                </div>
                <div class="dropdown">
                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="sortDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        Sort by: <?= ucfirst(str_replace('_', ' ', $sortBy)) ?>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="sortDropdown">
                        <li><a class="dropdown-item <?= $sortBy === 'relevance' ? 'active' : '' ?>" 
                              href="?q=<?= urlencode($searchQuery) ?>&sort=relevance<?= $filterQuery ?>">Relevance</a></li>
                        <li><a class="dropdown-item <?= $sortBy === 'newest' ? 'active' : '' ?>" 
                              href="?q=<?= urlencode($searchQuery) ?>&sort=newest<?= $filterQuery ?>">Newest</a></li>
                        <li><a class="dropdown-item <?= $sortBy === 'price_low_high' ? 'active' : '' ?>" 
                              href="?q=<?= urlencode($searchQuery) ?>&sort=price_low_high<?= $filterQuery ?>">Price: Low to High</a></li>
                        <li><a class="dropdown-item <?= $sortBy === 'price_high_low' ? 'active' : '' ?>" 
                              href="?q=<?= urlencode($searchQuery) ?>&sort=price_high_low<?= $filterQuery ?>">Price: High to Low</a></li>
                        <li><a class="dropdown-item <?= $sortBy === 'top_rated' ? 'active' : '' ?>" 
                              href="?q=<?= urlencode($searchQuery) ?>&sort=top_rated<?= $filterQuery ?>">Top Rated</a></li>
                    </ul>
                </div>
            </div>
            
            <!-- Search Results -->
            <?php if (!empty($products)): ?>
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 mb-4">
                    <?php foreach ($products as $product): ?>
                        <div class="col">
                            <div class="card h-100 product-card">
                                <!-- Product Image -->
                                <div class="position-relative">
                                    <a href="<?= site_url('products/' . $product['slug']) ?>">
                                        <?php if (!empty($product['image'])): ?>
                                            <img src="<?= base_url('uploads/products/' . $product['image']) ?>" 
                                                 class="card-img-top product-image" alt="<?= esc($product['name']) ?>"
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
                                    <?php if ($product['is_featured']): ?>
                                        <span class="position-absolute top-0 start-0 bg-warning text-dark px-2 py-1 m-2 rounded small">
                                            <i class="fas fa-star me-1"></i> Featured
                                        </span>
                                    <?php endif; ?>
                                    <?php if ($product['discount_percent'] > 0): ?>
                                        <span class="position-absolute top-0 end-0 bg-danger text-white px-2 py-1 m-2 rounded small">
                                            -<?= $product['discount_percent'] ?>%
                                        </span>
                                    <?php endif; ?>
                                </div>
                                
                                <!-- Product Body -->
                                <div class="card-body d-flex flex-column">
                                    <div class="mb-2">
                                        <a href="<?= site_url('products?category=' . $product['category_id']) ?>" class="text-muted small">
                                            <?= esc($product['category_name']) ?>
                                        </a>
                                    </div>
                                    <h5 class="card-title">
                                        <a href="<?= site_url('products/' . $product['slug']) ?>" class="text-dark">
                                            <?= esc(character_limiter($product['name'], 50)) ?>
                                        </a>
                                    </h5>
                                    
                                    <!-- Price -->
                                    <div class="mt-auto">
                                        <?php if ($product['discount_percent'] > 0): ?>
                                            <div class="d-flex align-items-center">
                                                <h5 class="mb-0 text-danger">Rp <?= number_format($product['price'] * (1 - $product['discount_percent'] / 100), 0, ',', '.') ?></h5>
                                                <small class="text-muted text-decoration-line-through ms-2">Rp <?= number_format($product['price'], 0, ',', '.') ?></small>
                                            </div>
                                        <?php else: ?>
                                            <h5 class="mb-0">Rp <?= number_format($product['price'], 0, ',', '.') ?></h5>
                                        <?php endif; ?>
                                        
                                        <?php if (!empty($product['po_price_desc']) && $product['po_price_desc'] > 0): ?>
                                            <div class="text-muted small mb-2">PO: Rp <?= number_format($product['po_price_desc'], 0, ',', '.') ?></div>
                                        <?php endif; ?>
                                        
                                        <!-- Rating -->
                                        <div class="text-warning mb-2 small">
                                            <?= str_repeat('★', 5) ?>
                                            <small class="text-muted">(0)</small>
                                        </div>
                                        
                                        <!-- Add to Cart Button -->
                                        <button type="button" class="btn btn-primary btn-sm w-100 add-to-cart" 
                                                data-id="<?= $product['id'] ?>" 
                                                data-name="<?= esc($product['name']) ?>"
                                                data-price="<?= $product['discount_percent'] > 0 ? $product['price'] * (1 - $product['discount_percent'] / 100) : $product['price'] ?>">
                                            <i class="fas fa-shopping-cart me-1"></i> Add to Cart
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <!-- Pagination -->
                <?php if ($pager->getPageCount() > 1): ?>
                    <nav aria-label="Search results pagination">
                        <?= $pager->links('default', 'pagination') ?>
                    </nav>
                <?php endif; ?>
                
            <?php else: ?>
                <div class="text-center py-5">
                    <div class="mb-3">
                        <i class="fas fa-search fa-4x text-muted"></i>
                    </div>
                    <h3>No products found</h3>
                    <p class="text-muted">We couldn't find any products matching your search criteria.</p>
                    <a href="<?= site_url('products') ?>" class="btn btn-primary">Browse All Products</a>
                </div>
            <?php endif; ?>
            
            <!-- Search Suggestions -->
            <?php if (!empty($suggestions)): ?>
                <div class="card mt-4">
                    <div class="card-body">
                        <h5 class="mb-3">Did you mean?</h5>
                        <div class="d-flex flex-wrap gap-2">
                            <?php foreach ($suggestions as $suggestion): ?>
                                <a href="<?= site_url('products/search?q=' . urlencode($suggestion)) ?>" class="btn btn-outline-secondary btn-sm">
                                    <?= esc($suggestion) ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    // Handle add to cart button
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
<?= $this->endSection() ?>
