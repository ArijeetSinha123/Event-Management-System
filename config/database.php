<?php
// Database configuration
$host = 'localhost';
$dbname = 'event_management';
$username = 'root';
$password = '';

// Start session safely (ONLY ONCE)
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

try {
    // Create PDO instance
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    
    // Set PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Set default fetch mode
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    
    // Optional: Set character set
    $pdo->exec("SET NAMES utf8");
    
} catch(PDOException $e) {
    // Don't show detailed error to users
    die("Database connection failed. Please try again later.");
}
?>