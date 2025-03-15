<?php
/**
 * Application Configuration
 */

// Application settings
define('APP_NAME', 'PHP POS System');
define('APP_VERSION', '1.0.0');
define('CURRENCY', '$');
define('DATE_FORMAT', 'd-m-Y');
define('TIME_FORMAT', 'h:i A');

// Directory paths
define('ROOT_PATH', dirname(__DIR__));
define('UPLOADS_PATH', ROOT_PATH . '/uploads');

// URL paths
$base_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']);
define('BASE_URL', $base_url);

// Error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>