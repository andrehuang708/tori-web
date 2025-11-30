<?php 
$this->extend('admin/layouts/main');

// Set page title
$title = 'Dashboard';

// Helper function to get status badge color
function getOrderStatusBadge($status)
{
    $statusColors = [
        'pending' => 'warning',
        'paid' => 'info',
        'processing' => 'primary',
        'shipped' => 'success',
        'completed' => 'success',
        'cancelled' => 'danger'
    ];
    return $statusColors[strtolower($status)] ?? 'secondary';
}

$this->section('content');
?>

<!-- Content Header (Page header) -->


<!-- Main content -->
<section class="content">
    <div class="container-fluid">

    <!-- Info boxes -->
    <div class="row">
        <!-- Total Orders -->
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-info elevation-1"><i class="fas fa-shopping-cart"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Orders</span>
                    <span class="info-box-number">
                        <?= number_format($total_orders) ?>
                        <small>orders</small>
                    </span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
        
        <!-- Total Products -->
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
                <span class="info-box-icon bg-success elevation-1"><i class="fas fa-box"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Products</span>
                    <span class="info-box-number"><?= number_format($total_products) ?></span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->

        <!-- Total Categories -->
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
                <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-tags"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Categories</span>
                    <span class="info-box-number"><?= number_format($total_categories) ?></span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
        
        <!-- Total Revenue -->
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
                <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-dollar-sign"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Revenue</span>
                    <span class="info-box-number">$<?= isset($total_revenue) ? number_format($total_revenue, 2) : '0.00' ?></span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->

    <div class="row">
        <!-- Recent Orders -->
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Recent Orders</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-valign-middle">
                            <thead>
                                <tr>
                                    <th>Order #</th>
                                    <th>Customer</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recent_orders as $order): 
                                    $orderId = is_array($order) ? $order['id'] : $order->id;
                                    $orderStatus = is_array($order) ? $order['status'] : $order->status;
                                    $statusBadge = getOrderStatusBadge($orderStatus);
                                ?>
                                <tr>
                                    <td>#<?= esc($orderId) ?></td>
                                    <td><?= esc(is_array($order) ? $order['customer_name'] : $order->customer_name) ?></td>
                                    <td>$<?= number_format(is_array($order) ? $order['subtotal'] : $order->subtotal, 2) ?></td>
                                    <td>
                                        <span class="badge bg-<?= $statusBadge ?>">
                                            <?= ucfirst(esc($orderStatus)) ?>
                                        </span>
                                    </td>
                                    <td><?= date('M d, Y', strtotime(is_array($order) ? $order['created_at'] : $order->created_at)) ?></td>
                                    <td>
                                        <a href="<?= site_url('admin/orders/view/' . $orderId) ?>" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.table-responsive -->
                </div>
                <!-- /.card-body -->
                <div class="card-footer text-center">
                    <a href="<?= site_url('admin/orders') ?>" class="uppercase">View All Orders</a>
                </div>
                <!-- /.card-footer -->
            </div>
            <!-- /.card -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->

    <div class="row">
        <!-- Low Stock Products -->
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Low Stock Products (Below 10)</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Category</th>
                                    <th>Stock</th>
                                    <th>Price</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($low_stock_products)): ?>
                                    <tr>
                                        <td colspan="5" class="text-center py-4">No low stock products found</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($low_stock_products as $product): 
                                        $productId = is_array($product) ? $product['id'] : $product->id;
                                        $stock = is_array($product) ? $product['stock'] : $product->stock;
                                        $stockClass = $stock <= 3 ? 'danger' : 'warning';
                                    ?>
                                    <tr>
                                        <td><?= esc(is_array($product) ? $product['name'] : $product->name) ?></td>
                                        <td><?= esc(is_array($product) ? $product['category_name'] : (isset($product->category_name) ? $product->category_name : 'N/A')) ?></td>
                                        <td>
                                            <div class="progress progress-xs">
                                                <div class="progress-bar bg-<?= $stockClass ?>" style="width: <?= min(100, ($stock / 10) * 100) ?>%"></div>
                                            </div>
                                            <span class="badge bg-<?= $stockClass ?>"><?= $stock ?> in stock</span>
                                        </td>
                                        <td>$<?= number_format(is_array($product) ? $product['price'] : $product->price, 2) ?></td>
                                        <td>
                                            <a href="<?= site_url('admin/products/edit/' . $productId) ?>" class="btn btn-sm btn-primary">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <a href="<?= site_url('admin/products/stock/' . $productId) ?>" class="btn btn-sm btn-info">
                                                <i class="fas fa-plus"></i> Add Stock
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.table-responsive -->
                </div>
                <!-- /.card-body -->
                <div class="card-footer clearfix">
                    <a href="<?= site_url('admin/products/create') ?>" class="btn btn-sm btn-info float-left">Add New Product</a>
                    <a href="<?= site_url('admin/products') ?>" class="btn btn-sm btn-secondary float-right">View All Products</a>
                </div>
                <!-- /.card-footer -->
            </div>
            <!-- /.card -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->

    <div class="row">
        <!-- Sales Chart -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Sales Overview</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                        <div class="btn-group">
                            <button type="button" class="btn btn-tool dropdown-toggle" data-toggle="dropdown">
                                <i class="far fa-calendar-alt"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right" role="menu">
                                <a href="#" class="dropdown-item">Today</a>
                                <a href="#" class="dropdown-item">This Week</a>
                                <a href="#" class="dropdown-item">This Month</a>
                                <a href="#" class="dropdown-item active">This Year</a>
                            </div>
                        </div>
                        <button type="button" class="btn btn-tool" data-card-widget="remove">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart">
                        <canvas id="salesChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                    </div>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
        <!-- /.col -->

        <!-- Top Products -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Top Selling Products</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body p-0">
                    <ul class="products-list product-list-in-card pl-2 pr-2">
                        <?php if (empty($top_products)): ?>
                            <li class="item text-center p-3">No products data available</li>
                        <?php else: ?>
                            <?php foreach ($top_products as $product): 
                                $productName = is_array($product) ? $product['name'] : $product->name;
                                $categoryName = is_array($product) ? $product['category_name'] : (isset($product->category_name) ? $product->category_name : 'N/A');
                                $totalSold = is_array($product) ? $product['total_sold'] : $product->total_sold;
                                $image = is_array($product) ? ($product['image'] ?? 'default-product.jpg') : ($product->image ?? 'default-product.jpg');
                            ?>
                            <li class="item">
                                <div class="product-img">
                                    <?php if (!empty($image) && $image !== 'default-product.jpg'): ?>
                                        <img src="<?= base_url('uploads/products/' . $image) ?>" alt="Product Image" class="img-size-50"
                                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                                             style="width: 50px; height: 50px; object-fit: cover;">
                                        <div style="display: none; width: 50px; height: 50px; background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); border-radius: 4px; display: flex; align-items: center; justify-content: center; color: #6c757d;">
                                            <i class="fas fa-image" style="font-size: 1rem; opacity: 0.5;"></i>
                                        </div>
                                    <?php else: ?>
                                        <div style="width: 50px; height: 50px; background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); border-radius: 4px; display: flex; align-items: center; justify-content: center; color: #6c757d;">
                                            <i class="fas fa-image" style="font-size: 1rem; opacity: 0.5;"></i>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="product-info">
                                    <a href="javascript:void(0)" class="product-title">
                                        <?= esc($productName) ?>
                                        <span class="badge badge-warning float-right"><?= number_format($totalSold) ?> sold</span>
                                    </a>
                                    <span class="product-description">
                                        <?= esc($categoryName) ?>
                                    </span>
                                </div>
                            </li>
                            <!-- /.item -->
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </ul>
                </div>
                <!-- /.card-body -->
                <div class="card-footer text-center">
                    <a href="<?= site_url('admin/reports/products') ?>" class="uppercase">View All Products Report</a>
                </div>
                <!-- /.card-footer -->
            </div>
            <!-- /.card -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
</div>

<?php
$this->endSection();

$this->section('scripts');
?>
<script>
$(document).ready(function() {
    // Sales chart configuration
    const salesData = <?= json_encode($sales_data) ?>;
    const ctx = document.getElementById('salesChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: salesData.labels,
            datasets: [{
                label: 'Sales',
                data: salesData.values,
                borderColor: 'rgb(78, 115, 223)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: 'Monthly Sales'
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
});
</script>

<?php
$this->endSection();
