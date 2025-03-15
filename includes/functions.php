<?php
/**
 * Common utility functions for the POS system
 */

/**
 * Sanitize user input
 * @param string $data Input data to sanitize
 * @return string Sanitized data
 */
function sanitize($data) {
    global $conn;
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    if ($conn) {
        $data = $conn->real_escape_string($data);
    }
    return $data;
}

/**
 * Generate a random string
 * @param int $length Length of the random string
 * @return string Random string
 */
function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

/**
 * Format currency
 * @param float $amount Amount to format
 * @return string Formatted currency
 */
function formatCurrency($amount) {
    return CURRENCY . ' ' . number_format($amount, 2);
}

/**
 * Format date
 * @param string $date Date to format
 * @return string Formatted date
 */
function formatDate($date) {
    return date(DATE_FORMAT, strtotime($date));
}

/**
 * Format time
 * @param string $time Time to format
 * @return string Formatted time
 */
function formatTime($time) {
    return date(TIME_FORMAT, strtotime($time));
}

/**
 * Check if user has permission
 * @param string $permission Permission to check
 * @return bool True if user has permission, false otherwise
 */
function hasPermission($permission) {
    // Check if user is logged in
    if (!isset($_SESSION['user_id'])) {
        return false;
    }
    
    // Check if user is admin (has all permissions)
    if ($_SESSION['user_role'] == 'admin') {
        return true;
    }
    
    // Check specific permission
    if (isset($_SESSION['permissions']) && in_array($permission, $_SESSION['permissions'])) {
        return true;
    }
    
    return false;
}

/**
 * Redirect to a URL
 * @param string $url URL to redirect to
 */
function redirect($url) {
    header("Location: $url");
    exit();
}

/**
 * Display flash message
 * @param string $message Message to display
 * @param string $type Type of message (success, error, warning, info)
 */
function setFlashMessage($message, $type = 'info') {
    $_SESSION['flash_message'] = $message;
    $_SESSION['flash_type'] = $type;
}

/**
 * Get flash message
 * @return array Flash message and type
 */
function getFlashMessage() {
    $message = isset($_SESSION['flash_message']) ? $_SESSION['flash_message'] : '';
    $type = isset($_SESSION['flash_type']) ? $_SESSION['flash_type'] : 'info';
    
    // Clear flash message
    unset($_SESSION['flash_message']);
    unset($_SESSION['flash_type']);
    
    return ['message' => $message, 'type' => $type];
}
?>