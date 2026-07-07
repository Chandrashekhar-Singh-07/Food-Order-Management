<?php
require 'config.php';
require 'includes/helpers.php';

header('Content-Type: application/json');

$itemId = isset($_POST['item_id']) ? (int) $_POST['item_id'] : 0;
$delta  = isset($_POST['delta']) ? (int) $_POST['delta'] : 0;

if ($itemId <= 0 || $delta === 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit;
}

// Make sure the item actually exists and is available
$check = mysqli_query($conn, "SELECT id FROM menu_items WHERE id = $itemId AND is_available = 1 LIMIT 1");
if (mysqli_num_rows($check) === 0) {
    echo json_encode(['success' => false, 'message' => 'Item not found']);
    exit;
}

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$currentQty = $_SESSION['cart'][$itemId] ?? 0;
$newQty = $currentQty + $delta;

if ($newQty <= 0) {
    unset($_SESSION['cart'][$itemId]);
    $newQty = 0;
} else {
    $_SESSION['cart'][$itemId] = $newQty;
}

echo json_encode([
    'success'    => true,
    'qty'        => $newQty,
    'cart_count' => cart_count(),
]);
