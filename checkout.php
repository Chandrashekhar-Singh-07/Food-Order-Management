<?php
require 'config.php';
require 'includes/helpers.php';

$cart = $_SESSION['cart'] ?? [];
if (empty($cart)) {
    header('Location: cart.php');
    exit;
}

$ids = implode(',', array_map('intval', array_keys($cart)));
$result = mysqli_query($conn, "SELECT * FROM menu_items WHERE id IN ($ids)");
$subtotal = 0;
while ($row = mysqli_fetch_assoc($result)) {
    $subtotal += $row['price'] * $cart[$row['id']];
}
$deliveryFee = 30;
$grandTotal = $subtotal + $deliveryFee;

$error = $_SESSION['checkout_error'] ?? '';
unset($_SESSION['checkout_error']);
?>
<!DOCTYPE html>
<html lang="hi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Checkout — Tadka Express</title>
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<?php include 'includes/header.php'; ?>

<div class="page-title-bar">
    <div class="container">
        <h1>Checkout</h1>
        <p>Delivery details bharein aur order confirm karein</p>
    </div>
</div>

<div class="container">
    <div class="cart-layout">
        <div class="form-card">
            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo h($error); ?></div>
            <?php endif; ?>
            <form action="place_order.php" method="POST">
                <div class="form-group">
                    <label for="name">Full Name</label>
                    <input type="text" id="name" name="customer_name" required value="<?php echo h($_SESSION['checkout_name'] ?? ''); ?>">
                </div>
                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input type="tel" id="phone" name="phone" pattern="[0-9]{10}" placeholder="10-digit mobile number" required value="<?php echo h($_SESSION['checkout_phone'] ?? ''); ?>">
                </div>
                <div class="form-group">
                    <label for="address">Delivery Address</label>
                    <textarea id="address" name="address" required placeholder="House no, street, area, city, pincode"><?php echo h($_SESSION['checkout_address'] ?? ''); ?></textarea>
                </div>
                <div class="form-group">
                    <label>Payment Method</label>
                    <div class="pay-options">
                        <label><input type="radio" name="payment_method" value="COD" checked> Cash on Delivery</label>
                        <label><input type="radio" name="payment_method" value="Online"> Pay Online</label>
                    </div>
                </div>
                <button type="submit" class="checkout-btn" style="border:none;">Place Order — ₹<?php echo number_format($grandTotal, 0); ?></button>
            </form>
        </div>

        <div class="summary-card">
            <h3>Bill Summary</h3>
            <div class="summary-row"><span>Subtotal</span><span>₹<?php echo number_format($subtotal, 0); ?></span></div>
            <div class="summary-row"><span>Delivery Fee</span><span>₹<?php echo number_format($deliveryFee, 0); ?></span></div>
            <div class="summary-total"><span>Total</span><span>₹<?php echo number_format($grandTotal, 0); ?></span></div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; unset($_SESSION['checkout_name'], $_SESSION['checkout_phone'], $_SESSION['checkout_address']); ?>
</body>
</html>
