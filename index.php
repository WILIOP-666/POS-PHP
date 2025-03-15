<?php
// Main entry point for the POS system
session_start();

// Include configuration files
require_once 'config/database.php';
require_once 'config/config.php';
require_once 'includes/functions.php';

// Check if user is logged in, if not redirect to login page
if (!isset($_SESSION['user_id']) && basename($_SERVER['PHP_SELF']) != 'login.php') {
    header("Location: views/auth/login.php");
    exit();
}

// Default route to dashboard if logged in
if (isset($_SESSION['user_id']) && basename($_SERVER['PHP_SELF']) == 'index.php') {
    header("Location: views/dashboard.php");
    exit();
}
?>