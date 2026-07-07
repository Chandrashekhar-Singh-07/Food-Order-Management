<?php
require 'config.php';
require 'includes/helpers.php';

$cart = $_SESSION['cart'] ?? [];
$cartItems = [];
$subtotal = 0;

if (!empty($cart)) {
    $ids = implode(',', array_map('intval', array_keys($cart)));
    $result = mysqli_query($conn, "SELECT m.*, c.name AS category_name FROM menu_items m JOIN categories c ON c.id = m.category_id WHERE m.id IN ($ids)");
    while ($row = mysqli_fetch_assoc($result)) {
        $qty = $cart[$row['id']];
        $lineTotal = $qty * $row['price'];
        $subtotal += $lineTotal;
        $row['qty'] = $qty;
        $row['line_total'] = $lineTotal;
        $cartItems[] = $row;
    }
}

$deliveryFee = $subtotal > 0 ? 30 : 0;
$grandTotal = $subtotal + $deliveryFee;
?>
<!DOCTYPE html>
<html lang="hi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Your Cart — Tadka Express</title>
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<?php include 'includes/header.php'; ?>

<div class="page-title-bar">
    <div class="container">
        <h1>Your Cart</h1>
        <p><?php echo count($cartItems); ?> item(s) added</p>
    </div>
</div>

<div class="container">
<?php if (empty($cartItems)): ?>
    <div class="empty-state">
        <div class="big-icon">🍽️</div>
        <h3>Aapka cart khaali hai</h3>
        <p>Menu se kuch tasty dishes add karein.</p>
        <br>
        <a href="index.php" class="add-btn" style="display:inline-block;">Browse Menu</a>
    </div>
<?php else: ?>
    <div class="cart-layout">
        <div class="cart-items">
            <?php foreach ($cartItems as $item):
                $style = category_style($item['category_name']);
            ?>
                <div class="cart-item" data-id="<?php echo (int)$item['id']; ?>">
                    <div class="thumb-mini" style="background: <?php echo $style['bg']; ?>;"><?php echo $style['icon']; ?></div>
                    <div>
                        <h4><?php echo h($item['name']); ?></h4>
                        <div class="line-price">₹<?php echo number_format($item['price'], 0); ?> &times; <?php echo $item['qty']; ?> = ₹<?php echo number_format($item['line_total'], 0); ?></div>
                    </div>
                    <div class="qty-stepper">
                        <button type="button" class="qty-minus" aria-label="Ghatayein">-</button>
                        <span class="qty-count"><?php echo $item['qty']; ?></span>
                        <button type="button" class="qty-plus" aria-label="Badhayein">+</button>
                    </div>
                    <a href="remove_from_cart.php?id=<?php echo (int)$item['id']; ?>" class="remove-link">Remove</a>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="summary-card">
            <h3>Bill Summary</h3>
            <div class="summary-row"><span>Subtotal</span><span>₹<?php echo number_format($subtotal, 0); ?></span></div>
            <div class="summary-row"><span>Delivery Fee</span><span>₹<?php echo number_format($deliveryFee, 0); ?></span></div>
            <div class="summary-total"><span>Total</span><span>₹<?php echo number_format($grandTotal, 0); ?></span></div>
            <a href="checkout.php" class="checkout-btn">Proceed to Checkout</a>
        </div>
    </div>
<?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
</body>
</html>
