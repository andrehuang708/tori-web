<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= getenv('site_name') ?> | Admin <?= isset($title) ? ' | ' . $title : '' ?></title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom styles -->
    <style>
        .login-box {
            width: 100%;
            max-width: 400px;
            margin: 2rem auto;
            padding: 1rem;
        }
        .login-logo {
            text-align: center;
            margin-bottom: 2rem;
        }
        .login-logo h1 {
            font-size: 2rem;
            font-weight: 300;
            color: #343a40;
        }
        .login-card-body {
            padding: 2rem;
            border-radius: 0.5rem;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }
        .login-box-msg {
            margin-bottom: 1.5rem;
            text-align: center;
            color: #6c757d;
        }
        .input-group {
            margin-bottom: 1rem;
        }
        .input-group-text {
            background-color: #e9ecef;
            border: 1px solid #dee2e6;
            border-radius: 0.25rem 0 0 0.25rem;
        }
        .input-group input {
            border-radius: 0 0.25rem 0.25rem 0;
        }
        .btn-primary {
            width: 100%;
            padding: 0.5rem;
        }
        .alert {
            margin-bottom: 1rem;
        }
    
    <?= $this->renderSection('styles') ?>
    
    <style>
        .login-page {
            min-height: 100vh;
            background: #e9ecef;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-box {
            width: 360px;
            max-width: 100%;
        }
        .login-logo {
            font-size: 2.1rem;
            font-weight: 300;
            margin-bottom: 0.9rem;
            text-align: center;
        }
        .login-logo a {
            color: #444;
            text-decoration: none;
        }
        .login-card-body {
            padding: 2rem;
        }
        .login-box-msg {
            margin: 0;
            padding: 0 0 1.5rem;
            text-align: center;
            font-size: 1rem;
        }
        .input-group-text {
            border-radius: 0 0.25rem 0.25rem 0 !important;
        }
        .input-group .form-control:not(:last-child) {
            border-top-right-radius: 0;
            border-bottom-right-radius: 0;
        }
        .btn-block {
            display: block;
            width: 100%;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
        .btn-primary:hover {
            background-color: #0069d9;
            border-color: #0062cc;
        }
        .alert {
            margin-bottom: 1rem;
            padding: 0.75rem 1.25rem;
            border: 1px solid transparent;
            border-radius: 0.25rem;
        }
        .alert-danger {
            color: #721c24;
            background-color: #f8d7da;
            border-color: #f5c6cb;
        }
        .alert-success {
            color: #155724;
            background-color: #d4edda;
            border-color: #c3e6cb;
        }
        .invalid-feedback {
            display: block;
            width: 100%;
            margin-top: 0.25rem;
            font-size: 80%;
            color: #dc3545;
        }
        .is-invalid {
            border-color: #dc3545 !important;
            padding-right: calc(1.5em + 0.75rem);
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='none' stroke='%23dc3545' viewBox='0 0 12 12'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23dc3545' stroke='none'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right calc(0.375em + 0.1875rem) center;
            background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
        }
    </style>
</head>
<body class="hold-transition login-page">
    <?= $this->renderSection('content') ?>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <?= $this->renderSection('scripts') ?>
    
    <script>
        $(function () {
            // Enable iCheck plugin for checkboxes
            $('input[type="checkbox"]').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '20%' // optional
            });
            
            // Auto-hide alerts after 5 seconds
            setTimeout(function() {
                $('.alert').fadeOut('slow', function() {
                    $(this).remove();
                });
            }, 5000);
            
            // Focus on the first input field
            $('form').find('input[type="text"], input[type="email"], input[type="password"]').first().focus();
        });
    </script>
</body>
</html>
