<?= $this->extend('layouts/main') ?>

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
        content: "Pre-Order";
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

/* Rating - Hidden for Minimalist */
.product-rating {
    display: none;
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

/* Quick Actions - Hidden for Minimalist */
.quick-actions {
    display: none !important;
}

/* Mobile Sidebar Toggle */
.mobile-sidebar-toggle {
    display: block;
    position: fixed;
    top: 80px;
    left: 15px;
    z-index: 1000;
    background: #007bff;
    color: white;
    border: none;
    border-radius: 50%;
    width: 50px;
    height: 50px;
    font-size: 1.25rem;
    box-shadow: 0 4px 12px rgba(0,123,255,0.3);
}

@media (min-width: 992px) {
    .mobile-sidebar-toggle {
        display: none;
    }
}

.mobile-sidebar {
    position: fixed;
    top: 0;
    left: -280px;
    width: 280px;
    height: 100vh;
    background: white;
    z-index: 999;
    transition: left 0.3s ease;
    overflow-y: auto;
    box-shadow: 2px 0 10px rgba(0,0,0,0.1);
}

.mobile-sidebar.active {
    left: 0;
}

.sidebar-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.5);
    z-index: 998;
    display: none;
}

.sidebar-overlay.active {
    display: block;
}

@media (min-width: 992px) {
    .mobile-sidebar {
        display: none;
    }
    
    .sidebar-overlay {
        display: none !important;
    }
}

/* Search Bar Mobile Optimization */
.search-section {
    background: #f8f9fa;
    padding: 1rem;
    border-radius: 12px;
    margin-bottom: 1.5rem;
}

.search-input-group {
    position: relative;
}

.search-input-group .form-control {
    padding-right: 3rem;
    border-radius: 25px;
    border: 2px solid #e9ecef;
    transition: border-color 0.3s ease;
}

.search-input-group .form-control:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0,123,255,0.25);
}

.search-input-group .btn {
    position: absolute;
    right: 5px;
    top: 50%;
    transform: translateY(-50%);
    border-radius: 50%;
    width: 36px;
    height: 36px;
    padding: 0;
}

/* Header Section */
.page-header-section {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 2rem 0;
    margin-bottom: 2rem;
    border-radius: 0 0 20px 20px;
}

/* Empty State */
.empty-state {
    padding: 3rem 1rem;
    text-align: center;
}

.empty-state i {
    font-size: 4rem;
    color: #6c757d;
    margin-bottom: 1rem;
}

/* Responsive adjustments */
@media (max-width: 576px) {
    .product-badges {
        top: 8px;
        left: 8px;
        right: 8px;
    }
    
    .badge-featured, .badge-discount {
        font-size: 0.65rem;
        padding: 3px 6px;
    }
    
    .product-body {
        padding: 0.75rem;
    }
    
    .product-title {
        font-size: 0.9rem;
        height: 2.5rem;
    }
    
    .price-current {
        font-size: 1.1rem;
    }
    
    .btn-add-cart {
        padding: 0.6rem;
        font-size: 0.875rem;
    }
}

/* Custom Pagination Styles */
.pagination-wrapper {
    display: flex;
    justify-content: center;
    align-items: center;
    margin-top: 3rem;
    padding: 2rem 0;
}

.pagination {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    background: white;
    padding: 0.75rem 1rem;
    border-radius: 50px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    border: 1px solid #e9ecef;
}

.pagination .page-item {
    margin: 0 0.25rem;
}

.pagination .page-link {
    border: none;
    background: transparent;
    color: #6c757d;
    padding: 0.5rem 0.75rem;
    margin: 0 0.125rem;
    border-radius: 10px;
    font-weight: 500;
    transition: all 0.3s ease;
    min-width: 40px;
    text-align: center;
    display: flex;
    align-items: center;
    justify-content: center;
}

.pagination .page-link:hover {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
}

.pagination .page-item.active .page-link {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    transform: scale(1.05);
}

.pagination .page-item.disabled .page-link {
    color: #dee2e6;
    pointer-events: none;
    opacity: 0.6;
}

.pagination .page-item:first-child .page-link,
.pagination .page-item:last-child .page-link {
    background: #f8f9fa;
    color: #6c757d;
    border-radius: 50%;
    width: 40px;
    height: 40px;
    padding: 0;
    display: flex;
    align-items: center;
    justify-content: center;
}

.pagination .page-item:first-child .page-link:hover,
.pagination .page-item:last-child .page-link:hover {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

/* Pagination Info */
.pagination-info {
    text-align: center;
    margin-bottom: 1rem;
    color: #6c757d;
    font-size: 0.875rem;
}

.pagination-info strong {
    color: #495057;
    font-weight: 600;
}

/* Mobile Pagination */
@media (max-width: 768px) {
    .pagination {
        padding: 0.5rem;
        gap: 0.25rem;
        flex-wrap: wrap;
        justify-content: center;
    }
    
    .pagination .page-link {
        padding: 0.4rem 0.6rem;
        font-size: 0.875rem;
        min-width: 35px;
    }
    
    .pagination .page-item:first-child .page-link,
    .pagination .page-item:last-child .page-link {
        width: 35px;
        height: 35px;
        font-size: 0.875rem;
    }
    
    .pagination .page-item {
        margin: 0 0.125rem;
    }
}

@media (max-width: 576px) {
    .pagination-wrapper {
        margin-top: 2rem;
        padding: 1.5rem 0;
    }
    
    .pagination {
        padding: 0.375rem;
        gap: 0.125rem;
    }
    
    .pagination .page-link {
        padding: 0.375rem 0.5rem;
        font-size: 0.8rem;
        min-width: 32px;
    }
    
    .pagination .page-item:first-child .page-link,
    .pagination .page-item:last-child .page-link {
        width: 32px;
        height: 32px;
        font-size: 0.8rem;
    }
    
    /* Hide some page numbers on very small screens */
    .pagination .page-item:nth-child(n+4):nth-child(-n+5),
    .pagination .page-item:nth-last-child(n+4):nth-last-child(-n+5) {
        display: none;
    }
    
    .pagination .page-item:nth-child(3),
    .pagination .page-item:nth-last-child(3) {
        display: block;
    }
}
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid p-0">
    <!-- Mobile Sidebar Toggle -->
    <button class="mobile-sidebar-toggle" onclick="toggleMobileSidebar()">
        <i class="fas fa-bars"></i>
    </button>
    
    <!-- Mobile Sidebar Overlay -->
    <div class="sidebar-overlay" onclick="toggleMobileSidebar()"></div>
    
    <!-- Page Header -->


    <div class="container">
        <div class="row">
            <!-- Mobile Sidebar -->
            <div class="mobile-sidebar" id="mobileSidebar">
                <div class="p-3">
                    <button class="btn btn-outline-secondary mb-3 w-100" onclick="toggleMobileSidebar()">
                        <i class="fas fa-times me-2"></i>Close
                    </button>
                    
                    <!-- Categories -->
                    <div class="card mb-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-list me-2"></i>Categories
                            </h5>
                        </div>
                        <div class="list-group list-group-flush">
                            <a href="<?= site_url('products') ?>" class="list-group-item list-group-item-action <?= !$currentCategory ? 'active' : '' ?>">
                                <i class="fas fa-th me-2"></i>All Products
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
                                <h5 class="mb-0">
                                    <i class="fas fa-star me-2"></i>Featured Products
                                </h5>
                            </div>
                            <div class="card-body p-0">
                                <?php foreach ($featuredProducts as $product): ?>
                                    <div class="p-3 border-bottom">
                                        <div class="d-flex align-items-center">
                                            <div class="me-3">
                                                <?php if (!empty($product['image'])): ?>
                                                    <img src="<?= base_url('uploads/products/' . $product['image']) ?>" 
                                                         class="rounded" alt="<?= esc($product['name']) ?>" style="width: 60px; height: 60px; object-fit: cover;"
                                                         onerror="this.onerror=null; this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                                    
                                                <?php else: ?>
                                                    <div style="width: 60px; height: 60px; background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #6c757d;">
                                                        <i class="fas fa-image"></i>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1">
                                                    <a href="<?= site_url('products/' . $product['slug']) ?>" class="text-dark text-decoration-none">
                                                        <?= esc($product['name']) ?>
                                                    </a>
                                                </h6>
                                                <div class="text-primary fw-bold">Rp <?= number_format($product['price'], 0, ',', '.') ?></div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Responsive Sidebar -->
            <div class="col-lg-3">
                <div class="sidebar d-lg-block d-none" id="desktopSidebar">
                    <!-- Categories -->
                    <div class="card mb-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-list me-2"></i>Categories
                            </h5>
                        </div>
                        <div class="list-group list-group-flush">
                            <a href="<?= site_url('products') ?>" class="list-group-item list-group-item-action <?= !$currentCategory ? 'active' : '' ?>">
                                <i class="fas fa-th me-2"></i>All Products
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
                                <h5 class="mb-0">
                                    <i class="fas fa-star me-2"></i>Featured Products
                                </h5>
                            </div>
                            <div class="card-body p-0">
                                <?php foreach ($featuredProducts as $product): ?>
                                    <div class="p-3 border-bottom">
                                        <div class="d-flex align-items-center">
                                            <div class="me-3">
                                                <?php if (!empty($product['image'])): ?>
                                                    <img src="<?= base_url('uploads/products/' . $product['image']) ?>" 
                                                         class="rounded" alt="<?= esc($product['name']) ?>" style="width: 60px; height: 60px; object-fit: cover;"
                                                         onerror="this.onerror=null; this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                                    <div style="display: none; width: 60px; height: 60px; background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #6c757d;">
                                                        <i class="fas fa-image"></i>
                                                    </div>
                                                <?php else: ?>
                                                    <div style="width: 60px; height: 60px; background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #6c757d;">
                                                        <i class="fas fa-image"></i>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1">
                                                    <a href="<?= site_url('products/' . $product['slug']) ?>" class="text-dark text-decoration-none">
                                                        <?= esc($product['name']) ?>
                                                    </a>
                                                </h6>
                                                <div class="text-primary fw-bold">Rp <?= number_format($product['price'], 0, ',', '.') ?></div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-lg-9">
                <!-- Search Section -->
                <div class="search-section">
                    <form action="<?= site_url('products') ?>" method="get">
                        <div class="search-input-group">
                            <input type="text" name="search" class="form-control form-control-lg" placeholder="🔍 Search products..." 
                                   value="<?= esc($searchTerm ?? '') ?>">
                            <?php if ($currentCategory): ?>
                                <input type="hidden" name="category" value="<?= $currentCategory ?>">
                            <?php endif; ?>
                            <button class="btn btn-primary" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Products Grid -->
                <?php if (!empty($products)): ?>
                    <div class="product-grid">
                        <?php foreach ($products as $product): ?>
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
                                        <?php if ($product['is_featured']): ?>
                                            <span class="badge-featured bg-warning text-dark">
                                                <i class="fas fa-star"></i>
                                            </span>
                                        <?php endif; ?>
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

                    <!-- Pagination -->
                    <?php if ($pager->getPageCount() > 1): ?>
                        <div class="pagination-wrapper">
                            <div class="pagination-info">
                                Showing <strong><?= ($pager->getPerPage() * ($pager->getCurrentPage() - 1)) + 1 ?></strong> to 
                                <strong><?= min($pager->getPerPage() * $pager->getCurrentPage(), $pager->getTotal()) ?></strong> of 
                                <strong><?= $pager->getTotal() ?></strong> products
                            </div>
                            <nav aria-label="Page navigation">
                                <?= $pager->links('default', 'default_full') ?>
                            </nav>
                        </div>
                    <?php endif; ?>
                    
                <?php else: ?>
                    <div class="empty-state">
                        <div class="mb-3">
                            <i class="fas fa-search"></i>
                        </div>
                        <h3>No products found</h3>
                        <p class="text-muted">We couldn't find any products matching your criteria.</p>
                        <a href="<?= site_url('products') ?>" class="btn btn-primary">
                            <i class="fas fa-th me-2"></i>Browse All Products
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
// Mobile Sidebar Toggle
function toggleMobileSidebar() {
    const sidebar = document.querySelector('.mobile-sidebar');
    const overlay = document.querySelector('.sidebar-overlay');
    
    sidebar.classList.toggle('active');
    overlay.classList.toggle('active');
    
    // Prevent body scroll when sidebar is open
    if (sidebar.classList.contains('active')) {
        document.body.style.overflow = 'hidden';
    } else {
        document.body.style.overflow = '';
    }
}

// Add to Cart functionality
document.addEventListener('DOMContentLoaded', function() {
    // Add to cart buttons
    const addToCartButtons = document.querySelectorAll('.add-to-cart');
    
    addToCartButtons.forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.dataset.id;
            const productName = this.dataset.name;
            const productPrice = this.dataset.price;
            
            // Show loading state
            const originalText = this.innerHTML;
            this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Adding...';
            this.disabled = true;
            
            // Actual AJAX call to add to cart
            fetch('<?= site_url('cart/add') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: new URLSearchParams({
                    'product_id': productId,
                    'quantity': 1
                })
            })
            .then(response => response.json())
            .then(data => {
                console.log('Cart response:', data);
                
                if (data.success) {
                    this.innerHTML = '<i class="fas fa-check me-2"></i> Added!';
                    this.classList.remove('btn-primary');
                    this.classList.add('btn-success');
                    
                    // Update cart count
                    updateCartCount(data.cart_summary?.total_items || 1);
                    
                    // Show success message
                    showNotification('Product added to cart!', 'success');
                } else {
                    // Show error message
                    showNotification(data.message || 'Failed to add to cart', 'error');
                    this.innerHTML = originalText;
                    this.disabled = false;
                }
                
                // Reset button after 2 seconds
                setTimeout(() => {
                    this.innerHTML = originalText;
                    this.classList.remove('btn-success');
                    this.classList.add('btn-primary');
                    this.disabled = false;
                }, 2000);
            })
            .catch(error => {
                console.error('Add to cart error:', error);
                showNotification('Error adding to cart', 'error');
                this.innerHTML = originalText;
                this.disabled = false;
            });
        });
    });
    
    // Update cart count function
    function updateCartCount(count = null) {
        // If count is provided, use it, otherwise make AJAX call
        if (count !== null) {
            const cartCountElements = document.querySelectorAll('.cart-count');
            cartCountElements.forEach(element => {
                element.textContent = count;
                if (count > 0) {
                    element.classList.remove('d-none');
                }
            });
        } else {
            // Make AJAX call to get current cart count
            fetch('<?= site_url('cart/count') ?>')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const cartCountElements = document.querySelectorAll('.cart-count');
                        cartCountElements.forEach(element => {
                            element.textContent = data.count;
                            if (data.count > 0) {
                                element.classList.remove('d-none');
                            }
                        });
                    }
                })
                .catch(error => console.error('Cart count error:', error));
        }
    }
    
    // Show notification function
    function showNotification(message, type = 'info') {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `alert alert-${type === 'error' ? 'danger' : type} alert-dismissible fade show position-fixed`;
        notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
        notification.innerHTML = `
            ${message}
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        `;
        
        document.body.appendChild(notification);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 5000);
    }
    
    // Initialize cart count on page load
    updateCartCount();
    
    // Search input enhancement
    const searchInput = document.querySelector('.search-input-group input');
    if (searchInput) {
        searchInput.addEventListener('focus', function() {
            this.parentElement.classList.add('search-focused');
        });
        
        searchInput.addEventListener('blur', function() {
            this.parentElement.classList.remove('search-focused');
        });
    }
    
    // Close mobile sidebar when clicking outside
    document.addEventListener('click', function(event) {
        const sidebar = document.querySelector('.mobile-sidebar');
        const toggle = document.querySelector('.mobile-sidebar-toggle');
        
        if (!sidebar.contains(event.target) && !toggle.contains(event.target)) {
            if (sidebar.classList.contains('active')) {
                toggleMobileSidebar();
            }
        }
    });
    
    // Handle window resize
    let resizeTimer;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function() {
            if (window.innerWidth >= 992) {
                // Close mobile sidebar on desktop
                const sidebar = document.querySelector('.mobile-sidebar');
                const overlay = document.querySelector('.sidebar-overlay');
                sidebar.classList.remove('active');
                overlay.classList.remove('active');
                document.body.style.overflow = '';
            }
        }, 250);
    });
});
</script>
<?= $this->endSection() ?>
