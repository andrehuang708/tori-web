<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container py-5">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-lg-3">
            <!-- Categories -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Categories</h5>
                </div>
                <div class="list-group list-group-flush">
                    <a href="<?= site_url('products') ?>" class="list-group-item list-group-item-action <?= !$currentCategory ? 'active' : '' ?>">
                        All Products
                    </a>
                    <?php foreach ($categories as $cat): ?>
                        <a href="<?= site_url('products?category=' . $cat['id']) ?>" 
                           class="list-group-item list-group-item-action <?= $currentCategory == $cat['id'] ? 'active' : '' ?>">
                            <?= esc($cat['name']) ?>
                            <span class="badge bg-primary rounded-pill float-end"><?= $cat['product_count'] ?? 0 ?></span>
                        </a>
                    <?php endforeach; ?>
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
                                        <img src="<?= !empty($product['image']) ? base_url('uploads/products/' . $product['image']) : 'https://via.placeholder.com/100' ?>" 
                                             class="img-fluid rounded" alt="<?= esc($product['name']) ?>">
                                    </div>
                                    <div class="col-8 ps-3">
                                        <h6 class="card-title mb-1">
                                            <a href="<?= site_url('products/' . $product['slug']) ?>" class="text-dark">
                                                <?= esc($product['name']) ?>
                                            </a>
                                        </h6>
                                        <div class="text-warning mb-1">
                                            <?= str_repeat('★', 5) ?>
                                        </div>
                                        <div class="text-primary fw-bold">$<?= number_format($product['price'], 2) ?></div>
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
            <!-- Page Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-0"><?= $title ?></h1>
                    <p class="mb-0 text-muted"><?= $totalProducts ?> products found</p>
                </div>
                <div class="dropdown">
                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="sortDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        Sort by: <?= ucfirst(str_replace('_', ' ', $currentSort)) ?>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="sortDropdown">
                        <li><a class="dropdown-item <?= $currentSort === 'newest' ? 'active' : '' ?>" 
                              href="?sort=newest<?= $currentCategory ? '&category=' . $currentCategory : '' ?><?= $searchTerm ? '&search=' . urlencode($searchTerm) : '' ?>">Newest</a></li>
                        <li><a class="dropdown-item <?= $currentSort === 'price_asc' ? 'active' : '' ?>" 
                              href="?sort=price_asc<?= $currentCategory ? '&category=' . $currentCategory : '' ?><?= $searchTerm ? '&search=' . urlencode($searchTerm) : '' ?>">Price: Low to High</a></li>
                        <li><a class="dropdown-item <?= $currentSort === 'price_desc' ? 'active' : '' ?>" 
                              href="?sort=price_desc<?= $currentCategory ? '&category=' . $currentCategory : '' ?><?= $searchTerm ? '&search=' . urlencode($searchTerm) : '' ?>">Price: High to Low</a></li>
                        <li><a class="dropdown-item <?= $currentSort === 'name_asc' ? 'active' : '' ?>" 
                              href="?sort=name_asc<?= $currentCategory ? '&category=' . $currentCategory : '' ?><?= $searchTerm ? '&search=' . urlencode($searchTerm) : '' ?>">Name: A to Z</a></li>
                        <li><a class="dropdown-item <?= $currentSort === 'name_desc' ? 'active' : '' ?>" 
                              href="?sort=name_desc<?= $currentCategory ? '&category=' . $currentCategory : '' ?><?= $searchTerm ? '&search=' . urlencode($searchTerm) : '' ?>">Name: Z to A</a></li>
                        <li><a class="dropdown-item <?= $currentSort === 'popular' ? 'active' : '' ?>" 
                              href="?sort=popular<?= $currentCategory ? '&category=' . $currentCategory : '' ?><?= $searchTerm ? '&search=' . urlencode($searchTerm) : '' ?>">Most Popular</a></li>
                    </ul>
                </div>
            </div>

            <!-- Search Form -->
            <form action="<?= site_url('products') ?>" method="get" class="mb-4">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Search products..." 
                           value="<?= esc($searchTerm ?? '') ?>">
                    <?php if ($currentCategory): ?>
                        <input type="hidden" name="category" value="<?= $currentCategory ?>">
                    <?php endif; ?>
                    <button class="btn btn-primary" type="submit">
                        <i class="fas fa-search"></i> Search
                    </button>
                </div>
            </form>

            <!-- Products Grid -->
            <?php if (!empty($products)): ?>
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 mb-4">
                    <?php foreach ($products as $product): ?>
                        <div class="col">
                            <div class="card h-100 product-card">
                                <!-- Product Image -->
                                <div class="position-relative">
                                    <a href="<?= site_url('products/' . $product['slug']) ?>">
                                        <img src="<?= !empty($product['image']) ? base_url('uploads/products/' . $product['image']) : 'https://via.placeholder.com/300' ?>" 
                                             class="card-img-top product-image" alt="<?= esc($product['name']) ?>">
                                    </a>
                                    <?php if ($product['is_featured']): ?>
                                        <span class="position-absolute top-0 start-0 bg-warning text-dark px-2 py-1 m-2 rounded">
                                            <i class="fas fa-star me-1"></i> Featured
                                        </span>
                                    <?php endif; ?>
                                    <?php if (!empty($product['original_price']) && $product['original_price'] > $product['price']): 
                                        $discountPercent = round((($product['original_price'] - $product['price']) / $product['original_price']) * 100);
                                    ?>
                                        <span class="position-absolute top-0 end-0 bg-danger text-white px-2 py-1 m-2 rounded">
                                            -<?= $discountPercent ?>% OFF
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
                                            <?= esc($product['name']) ?>
                                        </a>
                                    </h5>
                                    
                                    <!-- Price -->
                                    <div class="mt-auto">
                                        <?php if (!empty($product['original_price']) && $product['original_price'] > $product['price']): 
                                            $discountPercent = round((($product['original_price'] - $product['price']) / $product['original_price']) * 100);
                                        ?>
                                            <div class="d-flex align-items-center">
                                                <h5 class="mb-0 text-danger">Rp <?= number_format($product['price'], 0, ',', '.') ?></h5>
                                                <small class="text-muted text-decoration-line-through ms-2">Rp <?= number_format($product['original_price'], 0, ',', '.') ?></small>
                                            </div>
                                        <?php else: ?>
                                            <h5 class="mb-0">Rp <?= number_format($product['price'], 0, ',', '.') ?></h5>
                                        <?php endif; ?>
                                        
                                        <!-- Rating -->
                                        <div class="text-warning mb-2">
                                            <?= str_repeat('★', 5) ?>
                                            <small class="text-muted">(0)</small>
                                        </div>
                                        
                                        <!-- Add to Cart Button -->
                                        <button type="button" class="btn btn-primary w-100 add-to-cart" 
                                                data-id="<?= $product['id'] ?>" 
                                                data-name="<?= esc($product['name']) ?>"
                                                data-price="<?= $product['price'] ?>">
                                            <i class="fas fa-shopping-cart me-2"></i> Add to Cart
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Pagination -->
                <?php if ($pager->getPageCount() > 1): ?>
                    <nav aria-label="Page navigation">
                        <?= $pager->links('default', 'default_full') ?>
                    </nav>
                <?php endif; ?>
                
            <?php else: ?>
                <div class="text-center py-5">
                    <div class="mb-3">
                        <i class="fas fa-search fa-4x text-muted"></i>
                    </div>
                    <h3>No products found</h3>
                    <p class="text-muted">We couldn't find any products matching your criteria.</p>
                    <a href="<?= site_url('products') ?>" class="btn btn-primary">Browse All Products</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
