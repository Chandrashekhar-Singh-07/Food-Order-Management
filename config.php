<?php
/**
 * Database configuration
 * Apne XAMPP/WAMP/LAMP setup ke hisaab se yeh values change karein
 */
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');        // XAMPP me default password empty hota hai
define('DB_NAME', 'food_ordering');

$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if (!$conn) {
    die('Database connection failed: ' . mysqli_connect_error());
}

mysqli_set_charset($conn, 'utf8mb4');

// Session start (cart aur admin login ke liye zaroori)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
