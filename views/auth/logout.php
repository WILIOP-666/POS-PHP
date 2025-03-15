<?php
session_start();

// Include necessary files
require_once '../../config/config.php';
require_once '../../includes/functions.php';

// Destroy session
session_unset();
session_destroy();

// Redirect to login page
setFlashMessage('You have been logged out successfully', 'info');
redirect('../../views/auth/login.php');
?>