<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Order Status Update</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: #007bff;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            background: #f9f9f9;
            padding: 30px;
            border: 1px solid #ddd;
        }
        .footer {
            background: #f1f1f1;
            padding: 20px;
            text-align: center;
            border: 1px solid #ddd;
            border-top: none;
            border-radius: 0 0 5px 5px;
        }
        .order-info {
            background: white;
            padding: 15px;
            border-left: 4px solid #007bff;
            margin: 20px 0;
        }
        .status-badge {
            display: inline-block;
            padding: 5px 15px;
            background: #28a745;
            color: white;
            border-radius: 20px;
            font-weight: bold;
        }
        .btn {
            display: inline-block;
            padding: 12px 30px;
            background: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Order Status Update</h1>
    </div>
    
    <div class="content">
        <p>Dear <?= esc($order['customer_name']) ?>,</p>
        
        <p>We're writing to inform you that your order has been updated.</p>
        
        <div class="order-info">
            <h3>Order Details</h3>
            <p><strong>Order Number:</strong> <?= esc($order['order_number']) ?></p>
            <p><strong>Status:</strong> <span class="status-badge"><?= ucfirst($status) ?></span></p>
            <p><strong>Date:</strong> <?= date('F j, Y, g:i a') ?></p>
        </div>
        
        <?php if ($status == 'processing'): ?>
        <p>Your order is now being processed and will be shipped soon. We'll notify you once it has been shipped.</p>
        <?php elseif ($status == 'shipped'): ?>
        <p>Your order has been shipped! You should receive it within the estimated delivery time.</p>
        <?php elseif ($status == 'completed'): ?>
        <p>Your order has been completed. Thank you for shopping with us!</p>
        <?php elseif ($status == 'cancelled'): ?>
        <p>Your order has been cancelled. If you have any questions, please contact our support team.</p>
        <?php else: ?>
        <p>Your order status has been updated to: <?= ucfirst($status) ?></p>
        <?php endif; ?>
        
        <p>If you have any questions about your order, please don't hesitate to contact our customer support.</p>
        
        <p>Thank you for your business!</p>
        
        <div style="text-align: center;">
            <a href="<?= site_url('cart/track/order/' . $order['id'] . '/' . md5($order['order_number'])) ?>" class="btn">
                Track Your Order
            </a>
        </div>
    </div>
    
    <div class="footer">
        <p>&copy; <?= date('Y') ?> <?= getenv('site_name') ?: 'E-Commerce Store' ?>. All rights reserved.</p>
        <p>This is an automated message. Please do not reply to this email.</p>
    </div>
</body>
</html>
