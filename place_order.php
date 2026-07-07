<?php
require 'config.php';

$cart = $_SESSION['cart'] ?? [];
if (empty($cart)) {
    header('Location: cart.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: checkout.php');
    exit;
}

$name    = trim($_POST['customer_name'] ?? '');
$phone   = trim($_POST['phone'] ?? '');
$address = trim($_POST['address'] ?? '');
$payment = in_array($_POST['payment_method'] ?? '', ['COD', 'Online']) ? $_POST['payment_method'] : 'COD';

// ---- Basic validation ----
if ($name === '' || $phone === '' || $address === '') {
    $_SESSION['checkout_error'] = 'Kripya sabhi fields bharein.';
    header('Location: checkout.php');
    exit;
}
if (!preg_match('/^[0-9]{10}$/', $phone)) {
    $_SESSION['checkout_error'] = 'Kripya sahi 10-digit phone number dalein.';
    $_SESSION['checkout_name'] = $name;
    $_SESSION['checkout_address'] = $address;
    header('Location: checkout.php');
    exit;
}

// ---- Recalculate totals server-side (never trust the client) ----
$ids = implode(',', array_map('intval', array_keys($cart)));
$result = mysqli_query($conn, "SELECT * FROM menu_items WHERE id IN ($ids) AND is_available = 1");
$items = [];
$subtotal = 0;
while ($row = mysqli_fetch_assoc($result)) {
    $qty = (int) $cart[$row['id']];
    if ($qty <= 0) continue;
    $lineTotal = $qty * $row['price'];
    $subtotal += $lineTotal;
    $items[] = ['id' => $row['id'], 'name' => $row['name'], 'price' => $row['price'], 'qty' => $qty];
}

if (empty($items)) {
    $_SESSION['checkout_error'] = 'Cart me valid items nahi mile.';
    header('Location: cart.php');
    exit;
}

$deliveryFee = 30;
$grandTotal = $subtotal + $deliveryFee;

// ---- Insert order ----
$nameEsc    = mysqli_real_escape_string($conn, $name);
$phoneEsc   = mysqli_real_escape_string($conn, $phone);
$addressEsc = mysqli_real_escape_string($conn, $address);
$paymentEsc = mysqli_real_escape_string($conn, $payment);

$sql = "INSERT INTO orders (customer_name, phone, address, payment_method, total_amount, status)
        VALUES ('$nameEsc', '$phoneEsc', '$addressEsc', '$paymentEsc', $grandTotal, 'Pending')";

if (!mysqli_query($conn, $sql)) {
    $_SESSION['checkout_error'] = 'Order place karte waqt error aayi. Dobara try karein.';
    header('Location: checkout.php');
    exit;
}

$orderId = mysqli_insert_id($conn);

foreach ($items as $item) {
    $itemNameEsc = mysqli_real_escape_string($conn, $item['name']);
    mysqli_query($conn, "INSERT INTO order_items (order_id, menu_item_id, item_name, price, quantity)
        VALUES ($orderId, {$item['id']}, '$itemNameEsc', {$item['price']}, {$item['qty']})");
}

// ---- Clear cart, redirect to success page ----
unset($_SESSION['cart']);
$_SESSION['last_order_id'] = $orderId;

header('Location: order_success.php');
exit;
