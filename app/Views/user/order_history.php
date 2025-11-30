<?= $this->extend('layouts/main') ?>

<?= $this->section('styles') ?>
<style>
    body {
        background-color: #f8f9fa;
    }
    .page-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 2rem 0;
        margin: -20px -15px 20px -15px;
    }
    .order-card {
        transition: all 0.3s ease;
        margin-bottom: 1rem;
        border-left: 4px solid #007bff;
    }
    .order-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    .status-badge {
        font-size: 0.8rem;
        padding: 0.35rem 0.75rem;
        border-radius: 20px;
    }
    .sidebar {
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    .nav-link {
        color: #6c757d;
        border-radius: 8px;
        margin: 0.25rem 0;
    }
    .nav-link:hover, .nav-link.active {
        background-color: #f8f9fa;
        color: #007bff;
    }
    .nav-link i {
        width: 20px;
    }
    .empty-state {
        text-align: center;
        padding: 3rem;
    }
    .empty-state i {
        font-size: 4rem;
        color: #dee2e6;
        margin-bottom: 1rem;
    }
    .filter-section {
        background-color: white;
        border-radius: 10px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container py-4">
    

    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3">
            <div class="sidebar">
                <div class="p-3">
                    <h6 class="text-muted mb-3">Account Menu</h6>
                    <nav class="nav nav-pills flex-column">
                        <a href="<?= site_url('user/order-history') ?>" class="nav-link active">
                            <i class="fas fa-list me-2"></i>Order History
                        </a>
                        <a href="<?= site_url('user/track-order') ?>" class="nav-link">
                            <i class="fas fa-search me-2"></i>Track Order
                        </a>
                        <a href="<?= site_url('user/profile') ?>" class="nav-link">
                            <i class="fas fa-user me-2"></i>Profile Settings
                        </a>
                    
                        <a href="<?= site_url('cart') ?>" class="nav-link">
                            <i class="fas fa-shopping-cart me-2"></i>Shopping Cart
                        </a>
                        <a href="<?= site_url('/') ?>" class="nav-link">
                            <i class="fas fa-home me-2"></i>Back to Shop
                        </a>
                          <a href="<?= site_url('logout') ?>" class="nav-link text-danger">
                            <i class="fas fa-sign-out-alt me-2"></i>Log Out
                        </a>
                        <hr>
                        
                    </nav>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-md-9">
            <!-- Filter Section -->
            <div class="filter-section">
                <div class="row align-items-center mb-3">
                    <div class="col-md-6">
                        <h5 class="mb-0">All Orders</h5>
                        <small class="text-muted"><span id="orderCount"><?= count($orders) ?></span> orders found</small>
                    </div>
                    <div class="col-md-6">
                        <div class="input-group">
                            <input type="text" id="searchInput" class="form-control" placeholder="Search by order number, phone, or status...">
                            <button class="btn btn-outline-secondary" type="button" id="clearSearch">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Pagination Controls -->
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="d-flex align-items-center">
                            <label class="me-2">Show:</label>
                            <select id="pageSize" class="form-select form-select-sm" style="width: auto;">
                                <option value="10">10</option>
                                <option value="25" selected>25</option>
                                <option value="50">50</option>
                                <option value="<?= count($orders) ?>">All</option>
                            </select>
                            <span class="ms-2">per page</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <nav id="paginationNav">
                            <ul class="pagination pagination-sm justify-content-end mb-0">
                                <!-- Pagination will be generated by JavaScript -->
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>

            <!-- Orders List -->
            <?php if (empty($orders)): ?>
                <div class="empty-state card">
                    <div class="card-body">
                        <i class="fas fa-shopping-bag"></i>
                        <h4>No Orders Found</h4>
                        <p class="text-muted">You haven't placed any orders yet.</p>
                        <a href="<?= site_url('/') ?>" class="btn btn-primary">
                            <i class="fas fa-shopping-cart me-2"></i>Start Shopping
                        </a>
                    </div>
                </div>
            <?php else: ?>
                <div id="ordersContainer">
                    <?php foreach ($orders as $order): ?>
                        <div class="order-card card" data-order-number="<?= esc($order['order_number']) ?>" 
                             data-phone="<?= esc($order['customer_phone']) ?>" data-status="<?= esc($order['status']) ?>">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-md-3">
                                        <h6 class="mb-1 fw-bold"><?= esc($order['order_number']) ?></h6>
                                        <small class="text-muted">
                                            <i class="fas fa-calendar me-1"></i>
                                            <?= date('M d, Y H:i', strtotime($order['created_at'])) ?>
                                        </small>
                                        <br>
                                        <small class="text-muted">
                                            <i class="fas fa-phone me-1"></i>
                                            <?= esc($order['customer_phone']) ?>
                                        </small>
                                    </div>
                                    <div class="col-md-2">
                                        <span class="status-badge badge bg-<?= getOrderStatusColor($order['status']) ?>">
                                            <?= ucfirst($order['status']) ?>
                                        </span>
                                    </div>
                                    <div class="col-md-2">
                                        <small class="text-muted d-block">Payment Method</small>
                                        <strong><?= ucfirst($order['payment_method']) ?></strong>
                                    </div>
                                    <div class="col-md-2">
                                        <small class="text-muted d-block">Total Amount</small>
                                        <h5 class="mb-0 text-primary">Rp <?= number_format($order['subtotal'], 0, ',', '.') ?></h5>
                                    </div>
                                    <div class="col-md-2">
                                        <small class="text-muted d-block">Items</small>
                                        <h6 class="mb-0"><?= count($order['items']) ?> items</h6>
                                    </div>
                                    <div class="col-md-1 text-end">
                                         <small class="text-muted d-block">Actions</small>
                                         <a href="<?= site_url('user/order/' . $order['id']) ?>" class="btn btn-sm btn-outline-primary">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                        </div>
                                      
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <!-- No Results Message -->
                <div id="noResults" class="empty-state card" style="display: none;">
                    <div class="card-body">
                        <i class="fas fa-search"></i>
                        <h4>No Orders Found</h4>
                        <p class="text-muted">No orders match your search criteria.</p>
                        <button class="btn btn-outline-primary" onclick="clearSearch()">
                            <i class="fas fa-times me-2"></i>Clear Search
                        </button>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Search and Pagination functionality
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const clearButton = document.getElementById('clearSearch');
        const pageSizeSelect = document.getElementById('pageSize');
        const ordersContainer = document.getElementById('ordersContainer');
        const noResults = document.getElementById('noResults');
        const orderCount = document.getElementById('orderCount');
        const paginationNav = document.getElementById('paginationNav');
        
        let allOrders = [];
        let filteredOrders = [];
        let currentPage = 1;
        let pageSize = 25;

        // Store all orders
        if (ordersContainer) {
            allOrders = Array.from(ordersContainer.querySelectorAll('.order-card'));
            filteredOrders = [...allOrders];
        }

        // Pagination functions
        function updatePagination() {
            const totalPages = Math.ceil(filteredOrders.length / pageSize);
            const startIndex = (currentPage - 1) * pageSize;
            const endIndex = startIndex + pageSize;
            
            // Show/hide orders for current page
            filteredOrders.forEach((order, index) => {
                if (index >= startIndex && index < endIndex) {
                    order.style.display = '';
                } else {
                    order.style.display = 'none';
                }
            });

            // Update pagination controls
            if (paginationNav) {
                const paginationList = paginationNav.querySelector('ul');
                paginationList.innerHTML = '';

                // Previous button
                const prevLi = document.createElement('li');
                prevLi.className = `page-item ${currentPage === 1 ? 'disabled' : ''}`;
                prevLi.innerHTML = `<a class="page-link" href="#" data-page="${currentPage - 1}">Previous</a>`;
                paginationList.appendChild(prevLi);

                // Page numbers
                const maxVisiblePages = 5;
                let startPage = Math.max(1, currentPage - Math.floor(maxVisiblePages / 2));
                let endPage = Math.min(totalPages, startPage + maxVisiblePages - 1);

                if (endPage - startPage < maxVisiblePages - 1) {
                    startPage = Math.max(1, endPage - maxVisiblePages + 1);
                }

                for (let i = startPage; i <= endPage; i++) {
                    const li = document.createElement('li');
                    li.className = `page-item ${i === currentPage ? 'active' : ''}`;
                    li.innerHTML = `<a class="page-link" href="#" data-page="${i}">${i}</a>`;
                    paginationList.appendChild(li);
                }

                // Next button
                const nextLi = document.createElement('li');
                nextLi.className = `page-item ${currentPage === totalPages || totalPages === 0 ? 'disabled' : ''}`;
                nextLi.innerHTML = `<a class="page-link" href="#" data-page="${currentPage + 1}">Next</a>`;
                paginationList.appendChild(nextLi);

                // Add click handlers
                paginationList.addEventListener('click', function(e) {
                    e.preventDefault();
                    const pageLink = e.target.closest('.page-link');
                    if (pageLink && !pageLink.parentElement.classList.contains('disabled')) {
                        const page = parseInt(pageLink.getAttribute('data-page'));
                        if (page >= 1 && page <= totalPages) {
                            currentPage = page;
                            updatePagination();
                        }
                    }
                });
            }

            // Update results info
            const visibleCount = filteredOrders.slice(startIndex, endIndex).length;
            if (orderCount) {
                if (filteredOrders.length === 0) {
                    orderCount.textContent = '0';
                } else {
                    orderCount.textContent = `${startIndex + 1}-${Math.min(endIndex, filteredOrders.length)} of ${filteredOrders.length}`;
                }
            }
        }

        // Search function
        function performSearch() {
            const searchTerm = searchInput.value.toLowerCase().trim();

            if (searchTerm === '') {
                filteredOrders = [...allOrders];
            } else {
                filteredOrders = allOrders.filter(orderCard => {
                    const orderNumber = orderCard.getAttribute('data-order-number').toLowerCase();
                    const phone = orderCard.getAttribute('data-phone').toLowerCase();
                    const status = orderCard.getAttribute('data-status').toLowerCase();
                    const orderText = orderCard.textContent.toLowerCase();

                    return orderNumber.includes(searchTerm) || 
                           phone.includes(searchTerm) || 
                           status.includes(searchTerm) || 
                           orderText.includes(searchTerm);
                });
            }

            // Reset to first page when searching
            currentPage = 1;
            
            // Show/hide no results message
            if (noResults && ordersContainer) {
                if (filteredOrders.length === 0) {
                    noResults.style.display = '';
                    ordersContainer.style.display = 'none';
                    paginationNav.style.display = 'none';
                } else {
                    noResults.style.display = 'none';
                    ordersContainer.style.display = '';
                    paginationNav.style.display = '';
                }
            }

            updatePagination();
        }

        // Clear search function
        function clearSearch() {
            searchInput.value = '';
            performSearch();
        }

        // Page size change handler
        function changePageSize() {
            pageSize = parseInt(pageSizeSelect.value);
            currentPage = 1;
            updatePagination();
        }

        // Event listeners
        if (searchInput) {
            searchInput.addEventListener('input', performSearch);
            searchInput.addEventListener('keyup', function(e) {
                if (e.key === 'Escape') {
                    clearSearch();
                }
            });
        }

        if (clearButton) {
            clearButton.addEventListener('click', clearSearch);
        }

        if (pageSizeSelect) {
            pageSizeSelect.addEventListener('change', changePageSize);
        }

        // Global functions
        window.clearSearch = clearSearch;

        // Initialize pagination
        updatePagination();
    });

    function printOrder(orderNumber) {
        const printContent = document.querySelector('.order-card');
        if (printContent) {
            window.print();
        }
    }
</script>
<?= $this->endSection() ?>

<?php
// Helper function for status colors
function getOrderStatusColor($status) {
    $colors = [
        'pending' => 'warning',
        'processing' => 'info',
        'completed' => 'success',
        'delivered' => 'success',
        'cancelled' => 'danger'
    ];
    return $colors[$status] ?? 'secondary';
}
?>
