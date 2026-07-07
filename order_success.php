<?php
require 'config.php';

$orderId = $_SESSION['last_order_id'] ?? null;
if (!$orderId) {
    header('Location: index.php');
    exit;
}

$result = mysqli_query($conn, "SELECT * FROM orders WHERE id = " . (int) $orderId);
$order = mysqli_fetch_assoc($result);
if (!$order) {
    header('Location: index.php');
    exit;
}
unset($_SESSION['last_order_id']);
?>
<!DOCTYPE html>
<html lang="hi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Order Confirmed — Tadka Express</title>
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<?php include 'includes/header.php'; ?>

<div class="container">
    <div class="success-box">
        <div class="stamp">&#10003;</div>
        <h1>Order Confirm Ho Gaya!</h1>
        <p>Dhanyavaad, <?php echo htmlspecialchars($order['customer_name'], ENT_QUOTES, 'UTF-8'); ?>! Aapka khana taiyaar kiya ja raha hai.</p>
        <div class="order-id-chip">Order #<?php echo str_pad($order['id'], 5, '0', STR_PAD_LEFT); ?></div>
        <p>Total: <strong>₹<?php echo number_format($order['total_amount'], 0); ?></strong> &middot; Payment: <?php echo htmlspecialchars($order['payment_method'], ENT_QUOTES, 'UTF-8'); ?></p>
        <br>
        <a href="index.php" class="add-btn" style="display:inline-block;">Order More Food</a>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
</body>
</html>
