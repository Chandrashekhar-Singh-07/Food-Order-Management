<?php
require 'config.php';

$itemId = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($itemId > 0 && isset($_SESSION['cart'][$itemId])) {
    unset($_SESSION['cart'][$itemId]);
}

header('Location: cart.php');
exit;
