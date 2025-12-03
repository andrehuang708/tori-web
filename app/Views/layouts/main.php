<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= getenv('site_name') ?> | <?= $title ?? 'Welcome' ?></title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:ital,wght@0,300;0,400;0,600;0,700;1,400&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    
    <!-- Bootstrap 4 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
    
    <!-- AdminLTE CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2.0/dist/css/adminlte.min.css">
    
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.4/dist/sweetalert2.min.css">
    
    <!-- Custom styles -->
    <style>
        :root {
            --primary: #007bff;
            --secondary: #6c757d;
            --success: #28a745;
            --danger: #dc3545;
            --warning: #ffc107;
            --info: #17a2b8;
            --light: #f8f9fa;
            --dark: #343a40;
        }
        
        body {
            font-family: 'Source Sans Pro', sans-serif;
            background-color: #f4f6f9;
        }
        
        .product-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            margin-bottom: 20px;
            height: 100%;
            border: 1px solid rgba(0,0,0,.125);
            border-radius: 0.25rem;
            overflow: hidden;
        }
        
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        
        .product-image {
            height: 200px;
            object-fit: cover;
            width: 100%;
        }
        
        .main-header {
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0,0,0,.1);
        }
        
        .navbar-brand {
            font-weight: 600;
        }
        
        .content-wrapper {
            background-color: #f4f6f9;
            min-height: calc(100vh - 120px);
            padding: 20px 0;
        }
        
        .card {
            border: none;
            box-shadow: 0 0 10px rgba(0,0,0,0.05);
            margin-bottom: 20px;
        }
        
        .card-header {
            background-color: #fff;
            border-bottom: 1px solid rgba(0,0,0,.125);
        }
        
        .footer {
            background-color: #343a40;
            color: #fff;
            padding: 20px 0;
            margin-top: 40px;
        }
    </style>
    
    <?= $this->renderSection('styles') ?>
</head>
<body class="hold-transition layout-top-nav">
<div class="wrapper">
    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand-md navbar-light navbar-white">
        <div class="container">
            <a href="<?= site_url('/') ?>" class="navbar-brand">
                <img src="<?= base_url('assets/img/logo.png') ?>" alt="Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
                <span class="brand-text font-weight-light"><?= env('app.name', 'Tori Shop') ?></span>
            </a>

            <button class="navbar-toggler order-1" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse order-3" id="navbarCollapse">
                <!-- Left navbar links -->
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a href="<?= site_url('/') ?>" class="nav-link <?= uri_string() === '' ? 'active' : '' ?>">
                            <i class="fas fa-home"></i> Home
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= site_url('products') ?>" class="nav-link <?= strpos(uri_string(), 'products') === 0 ? 'active' : '' ?>">
                            <i class="fas fa-box"></i> Products
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= site_url('orders') ?>" class="nav-link <?= uri_string() === 'orders' ? 'active' : '' ?>">
                            <i class="fas fa-box-open"></i> My Order
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= site_url('contact') ?>" class="nav-link <?= uri_string() === 'contact' ? 'active' : '' ?>">
                            <i class="fas fa-envelope"></i> Contact
                        </a>
                    </li>
                </ul>

                <!-- Search Form -->
                <form class="form-inline ml-0 ml-md-3" action="<?= site_url('products/search') ?>" method="get">
                    <div class="input-group input-group-sm">
                        <input class="form-control form-control-navbar" type="search" name="q" placeholder="Search products..." aria-label="Search" value="<?= esc($searchQuery ?? '') ?>">
                        <div class="input-group-append">
                            <button class="btn btn-navbar" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Right navbar links -->
            <ul class="order-1 order-md-3 navbar-nav navbar-no-expand ml-auto">
                <!-- User Menu -->
                <li class="nav-item dropdown">
                    <a href="#" class="nav-link" data-toggle="dropdown">
                        <i class="fas fa-user-circle"></i>
                        <span class="ml-1"><?= session()->get('first_name') ?? session()->get('name') ?? session()->get('user_name') ?? 'User' ?></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a href="<?= site_url('user/profile') ?>" class="dropdown-item">
                            <i class="fas fa-user mr-2"></i> Profile
                        </a>
                        <a href="<?= site_url('logout') ?>" class="dropdown-item">
                            <i class="fas fa-sign-out-alt mr-2"></i> Logout
                        </a>
                    </div>
                </li>
                
                <!-- Cart Link -->
                <?php 
                $cart = service('cart');
                $cartItems = $cart->contents();
                $totalItems = $cart->totalItems();
                ?>
                <li class="nav-item">
                    <a href="<?= site_url('cart') ?>" class="nav-link">
                        <i class="fas fa-shopping-cart"></i>
                        <span class="badge badge-danger navbar-badge cart-count <?= ($totalItems > 0) ? '' : 'd-none' ?>">
                            <?= $totalItems ?>
                        </span>
                    </a>
                </li>
            </ul>
        </div>
    </nav>
    <!-- /.navbar -->

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Main content -->
        <div class="content">
            <div class="container">
                <?php if (session()->has('success')): ?>
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <?= session('success') ?>
                    </div>
                <?php endif; ?>
                
                <?php if (session()->has('error')): ?>
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <?= session('error') ?>
                    </div>
                <?php endif; ?>
                
                <?= $this->renderSection('content') ?>
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <!-- Main Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center">
                    <h5><?= getenv('site_name', 'Tori Shop') ?></h5>
                    <p class="text-muted mb-3">Quality products at affordable prices.</p>
                    <p class="mb-0 text-muted">&copy; <?= date('Y') ?> <?= getenv('site_name', 'Tori Shop') ?>. All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->
<!-- jQuery -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2.0/dist/js/adminlte.min.js"></script>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.4/dist/sweetalert2.all.min.js"></script>

<script>
$(function () {
    // Auto-hide alerts after 5 seconds (except permanent ones)
    setTimeout(function() {
        $('.alert:not(.alert-permanent)').fadeOut('slow', function() {
            $(this).remove();
        });
    }, 5000);

    // Initialize tooltips
    $('[data-toggle="tooltip"]').tooltip();
    
    // Add smooth scrolling to all links
    $('a[href*="#"]').on('click', function(event) {
        if (this.hash !== '') {
            event.preventDefault();
            const hash = this.hash;
            $('html, body').animate({
                scrollTop: $(hash).offset().top - 70
            }, 800, function(){
                window.location.hash = hash;
            });
        }
    });
    
    // Back to top button
    $(window).scroll(function() {
        if ($(this).scrollTop() > 300) {
            $('.back-to-top').fadeIn('slow');
        } else {
            $('.back-to-top').fadeOut('slow');
        }
    });
    
    $('.back-to-top').click(function() {
        $('html, body').animate({scrollTop: 0}, 800);
        return false;
    });
    
    // Add to cart functionality
    $(document).on('click', '.add-to-cart', function(e) {
        e.preventDefault();
        
        const $btn = $(this);
        const productId = $btn.data('id');
        const productName = $btn.data('name');
        const price = $btn.data('price');
        const $icon = $btn.find('i');
        const originalHtml = $btn.html();
        
        // Show loading state
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
                console.log('Cart add response:', response);
                // Always update the cart count after adding an item
                updateCartCount().done(function() {
                    // Show success message after updating the count
                    if (response && response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Added to Cart',
                            text: productName + ' has been added to your cart',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    } else {
                        const errorMessage = (response && response.message) || 'Failed to add item to cart';
                        console.error('Add to cart error:', errorMessage);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: errorMessage,
                            showConfirmButton: true
                        });
                    }
                });
            },
            error: function(xhr) {
                let errorMessage = 'Failed to add item to cart';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: errorMessage,
                    showConfirmButton: true
                });
            },
            complete: function() {
                // Restore button state
                $btn.prop('disabled', false).html(originalHtml);
            }
        });
    });
    
    // Update cart count on page load and after cart operations
    function updateCartCount() {
        console.log('Updating cart count...');
        return $.ajax({
            url: '<?= site_url('cart/count') ?>',
            method: 'GET',
            dataType: 'json',
            cache: false
        }).done(function(response) {
            console.log('Cart count response:', response);
            if (response && typeof response.count !== 'undefined') {
                const count = parseInt(response.count) || 0;
                console.log('Setting cart count to:', count);
                
                // Update all cart count elements
                $('.cart-count').each(function() {
                    $(this).text(count);
                    if (count > 0) {
                        $(this).removeClass('d-none');
                    } else {
                        $(this).addClass('d-none');
                    }
                });
                
                // Update cart header text if it exists
                const $header = $('.dropdown-header').first();
                if ($header.length) {
                    $header.text(count + ' Item' + (count !== 1 ? 's' : '') + ' in Cart');
                }
            } else {
                console.error('Invalid response format:', response);
            }
        }).fail(function(jqXHR, textStatus, errorThrown) {
            console.error('Failed to update cart count:', textStatus, errorThrown);
            console.error('Response:', jqXHR.responseText);
        });
    }
    
    // Initial cart count update
    $(document).ready(function() {
        updateCartCount();
    });
});
</script>

<?= $this->renderSection('scripts') ?>

<!-- Back to top button -->
<a href="#" class="back-to-top" style="display: none; position: fixed; bottom: 20px; right: 20px; z-index: 99; width: 40px; height: 40px; line-height: 40px; text-align: center; background: var(--primary); color: white; border-radius: 50%;">
    <i class="fas fa-arrow-up"></i>
</a>

</body>
</html>
