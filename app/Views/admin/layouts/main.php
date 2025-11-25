<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= getenv('site_name') ?> | Admin | <?= $title ?? 'Dashboard' ?></title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:ital,wght@0,300;0,400;0,600;0,700;1,400&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    
    <!-- AdminLTE CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2.0/dist/css/adminlte.min.css">
    
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.4/dist/sweetalert2.min.css">
    
    <!-- Custom admin styles -->
    <style>
        :root {
            --primary: #4e73df;
            --secondary: #858796;
            --success: #1cc88a;
            --info: #36b9cc;
            --warning: #f6c23e;
            --danger: #e74a3b;
            --light: #f8f9fc;
            --dark: #5a5c69;
        }
        
        body {
            font-family: 'Source Sans Pro', sans-serif;
            background-color: var(--light);
        }
        
        .main-sidebar {
            background-color: #4e73df;
            background-image: linear-gradient(180deg, #4e73df 10%, #224abe 100%);
            background-size: cover;
        }
        
        .brand-link {
            border-bottom: 1px solid rgba(255,255,255,.1);
        }
        
        .nav-sidebar > .nav-item .nav-link {
            color: rgba(255, 255, 255, 0.8);
        }
        
        .nav-sidebar > .nav-item .nav-link.active {
            color: #fff;
            background-color: rgba(255, 255, 255, 0.1);
        }
        
        .nav-sidebar > .nav-item .nav-link:hover {
            color: #fff;
            background-color: rgba(255, 255, 255, 0.1);
        }
        
        .content-wrapper {
            background-color: #f8f9fc;
        }
        
        .card {
            border: none;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            margin-bottom: 1.5rem;
        }
        
        .card-header {
            background-color: #f8f9fc;
            border-bottom: 1px solid #e3e6f0;
        }
    </style>
    
    <?= $this->renderSection('styles') ?>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
                <a href="<?= site_url('admin') ?>" class="nav-link">Home</a>
            </li>
        </ul>

        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">
            <!-- Notifications Dropdown Menu -->
            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#">
                    <i class="far fa-bell"></i>
                    <span class="badge badge-warning navbar-badge">15</span>
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                    <span class="dropdown-item dropdown-header">15 Notifications</span>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item">
                        <i class="fas fa-envelope mr-2"></i> 4 new messages
                        <span class="float-right text-muted text-sm">3 mins</span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item">
                        <i class="fas fa-users mr-2"></i> 8 friend requests
                        <span class="float-right text-muted text-sm">12 hours</span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item">
                        <i class="fas fa-file mr-2"></i> 3 new reports
                        <span class="float-right text-muted text-sm">2 days</span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?= site_url('admin/logout') ?>" title="Logout">
                    <i class="fas fa-sign-out-alt"></i>
                </a>
            </li>
        </ul>
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <!-- Brand Logo -->
        <a href="<?= site_url('admin') ?>" class="brand-link">
            <img src="<?= base_url('assets/img/logo.png') ?>" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
            <span class="brand-text font-weight-light"><?= getenv('app.name') ?> Admin</span>
        </a>

        <!-- Sidebar -->
        <div class="sidebar">
            <!-- Sidebar Menu -->
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                    <!-- Dashboard -->
                    <li class="nav-item">
                        <a href="<?= site_url('admin') ?>" class="nav-link <?= current_url() == site_url('admin') || strpos(current_url(), 'admin/dashboard') !== false ? 'active' : '' ?>">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>

                    <!-- Products -->
                    <li class="nav-item <?= strpos(current_url(), 'admin/products') !== false ? 'menu-open' : '' ?>">
                        <a href="#" class="nav-link <?= strpos(current_url(), 'admin/products') !== false ? 'active' : '' ?>">
                            <i class="nav-icon fas fa-box"></i>
                            <p>
                                Products
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="<?= site_url('admin/products') ?>" class="nav-link <?= current_url() == site_url('admin/products') ? 'active' : '' ?>">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>All Products</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= site_url('admin/products/create') ?>" class="nav-link <?= current_url() == site_url('admin/products/create') ? 'active' : '' ?>">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Add New</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= site_url('admin/categories') ?>" class="nav-link <?= current_url() == site_url('admin/categories') ? 'active' : '' ?>">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Categories</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- Orders -->
                    <li class="nav-item">
                        <a href="<?= site_url('admin/orders') ?>" class="nav-link <?= strpos(current_url(), 'admin/orders') !== false ? 'active' : '' ?>">
                            <i class="nav-icon fas fa-shopping-cart"></i>
                            <p>
                                Orders
                                <span class="badge badge-info right"><?= $total_orders ?? 0 ?></span>
                            </p>
                        </a>
                    </li>

                    <!-- Customers -->
                    <li class="nav-item">
                        <a href="<?= site_url('admin/customers') ?>" class="nav-link <?= strpos(current_url(), 'admin/customers') !== false ? 'active' : '' ?>">
                            <i class="nav-icon fas fa-users"></i>
                            <p>Customers</p>
                        </a>
                    </li>

                    <!-- Reports -->
                    <li class="nav-item">
                        <a href="<?= site_url('admin/reports') ?>" class="nav-link <?= strpos(current_url(), 'admin/reports') !== false ? 'active' : '' ?>">
                            <i class="nav-icon fas fa-chart-bar"></i>
                            <p>Reports</p>
                        </a>
                    </li>

                    <!-- Settings -->
                    <li class="nav-header">SETTINGS</li>
                    <li class="nav-item">
                        <a href="<?= site_url('admin/settings') ?>" class="nav-link <?= strpos(current_url(), 'admin/settings') !== false ? 'active' : '' ?>">
                            <i class="nav-icon fas fa-cog"></i>
                            <p>Settings</p>
                        </a>
                    </li>
                </ul>
            </nav>
            <!-- /.sidebar-menu -->
        </div>
        <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0"><?= $title ?? 'Dashboard' ?></h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="<?= site_url('admin') ?>">Home</a></li>
                            <li class="breadcrumb-item active"><?= $title ?? 'Dashboard' ?></li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <i class="icon fas fa-check"></i> <?= session()->getFlashdata('success') ?>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <i class="icon fas fa-ban"></i> <?= session()->getFlashdata('error') ?>
                    </div>
                <?php endif; ?>

                <?= $this->renderSection('content') ?>
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <footer class="main-footer">
        <strong>Copyright &copy; <?= date('Y') ?> <a href="<?= base_url() ?>"><?= getenv('app.name') ?></a>.</strong>
        All rights reserved.
        <div class="float-right d-none d-sm-inline-block">
            <b>Version</b> 1.0.0
        </div>
    </footer>

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Bootstrap 4 -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2.0/dist/js/adminlte.min.js"></script>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.4/dist/sweetalert2.all.min.js"></script>

<script>
$(function () {
    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
        $('.alert').fadeOut('slow', function() {
            $(this).remove();
        });
    }, 5000);

    // Initialize tooltips
    $('[data-toggle="tooltip"]').tooltip();

    // Confirm before delete
    $('.btn-delete').on('click', function(e) {
        e.preventDefault();
        const deleteUrl = $(this).attr('href');
        
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = deleteUrl;
            }
        });
    });
});
</script>

<?= $this->renderSection('scripts') ?>

</body>
</html>
