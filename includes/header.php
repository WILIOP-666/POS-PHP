<?php
// Check if user is logged in
if (!isset($_SESSION['user_id']) && basename($_SERVER['PHP_SELF']) != 'login.php') {
    header("Location: ../views/auth/login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo APP_NAME; ?></title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        .sidebar {
            min-height: 100vh;
            background-color: #212529;
        }
        .sidebar-heading {
            padding: 0.875rem 1.25rem;
            font-size: 1.2rem;
        }
        .list-group-item {
            background-color: #212529;
            color: rgba(255, 255, 255, 0.8);
            border: none;
            padding: 0.75rem 1.25rem;
        }
        .list-group-item.active, .list-group-item:hover {
            background-color: #343a40;
            color: #fff;
        }
        .list-group-item i {
            margin-right: 0.5rem;
        }
    </style>
</head>
<body>
    <div class="d-flex" id="wrapper">
        <!-- Sidebar -->
        <div class="sidebar" id="sidebar-wrapper">
            <div class="sidebar-heading text-center py-4 text-white fs-4 fw-bold">
                <i class="fas fa-cash-register me-2"></i><?php echo APP_NAME; ?>
            </div>
            <div class="list-group list-group-flush my-3">
                <a href="<?php echo BASE_URL; ?>/views/dashboard.php" class="list-group-item list-group-item-action">
                    <i class="fas fa-tachometer-alt"></i>Dashboard
                </a>
                <a href="<?php echo BASE_URL; ?>/views/products/index.php" class="list-group-item list-group-item-action">
                    <i class="fas fa-box"></i>Products
                </a>
                <a href="<?php echo BASE_URL; ?>/views/categories/index.php" class="list-group-item list-group-item-action">
                    <i class="fas fa-tags"></i>Categories
                </a>
                <a href="<?php echo BASE_URL; ?>/views/sales/index.php" class="list-group-item list-group-item-action">
                    <i class="fas fa-shopping-cart"></i>Sales
                </a>
                <a href="<?php echo BASE_URL; ?>/views/customers/index.php" class="list-group-item list-group-item-action">
                    <i class="fas fa-users"></i>Customers
                </a>
                <?php if ($_SESSION['user_role'] == 'admin'): ?>
                <a href="<?php echo BASE_URL; ?>/views/users/index.php" class="list-group-item list-group-item-action">
                    <i class="fas fa-user-cog"></i>Users
                </a>
                <a href="<?php echo BASE_URL; ?>/views/reports/index.php" class="list-group-item list-group-item-action">
                    <i class="fas fa-chart-bar"></i>Reports
                </a>
                <a href="<?php echo BASE_URL; ?>/views/settings/index.php" class="list-group-item list-group-item-action">
                    <i class="fas fa-cog"></i>Settings
                </a>
                <?php endif; ?>
            </div>
        </div>
        <!-- Page Content -->
        <div id="page-content-wrapper" class="w-100">
            <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
                <div class="container-fluid">
                    <button class="btn btn-dark" id="menu-toggle">
                        <i class="fas fa-bars"></i>
                    </button>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle text-white" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-user me-2"></i><?php echo isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'User'; ?>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/views/profile.php">Profile</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/views/auth/logout.php">Logout</a></li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
            
            <!-- Flash Messages -->
            <?php 
            $flash = getFlashMessage();
            if (!empty($flash['message'])): 
            ?>
            <div class="alert alert-<?php echo $flash['type']; ?> alert-dismissible fade show m-3" role="alert">
                <?php echo $flash['message']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php endif; ?>
            
            <!-- Main Content -->