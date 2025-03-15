<?php
session_start();

// Include necessary files
require_once '../config/database.php';
require_once '../config/config.php';
require_once '../includes/functions.php';
require_once '../includes/header.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    redirect('../views/auth/login.php');
}

// Get dashboard statistics
$stats = [];

// Total products
$query = "SELECT COUNT(*) as total FROM products";
$result = $conn->query($query);
$stats['total_products'] = $result->fetch_assoc()['total'];

// Total categories
$query = "SELECT COUNT(*) as total FROM categories";
$result = $conn->query($query);
$stats['total_categories'] = $result->fetch_assoc()['total'];

// Total customers
$query = "SELECT COUNT(*) as total FROM customers";
$result = $conn->query($query);
$stats['total_customers'] = $result->fetch_assoc()['total'];

// Total sales
$query = "SELECT COUNT(*) as total FROM sales";
$result = $conn->query($query);
$stats['total_sales'] = $result->fetch_assoc()['total'];

// Total revenue
$query = "SELECT SUM(net_amount) as total FROM sales";
$result = $conn->query($query);
$stats['total_revenue'] = $result->fetch_assoc()['total'] ?: 0;

// Low stock products (less than 10 items)
$query = "SELECT COUNT(*) as total FROM products WHERE quantity < 10";
$result = $conn->query($query);
$stats['low_stock'] = $result->fetch_assoc()['total'];

// Recent sales
$query = "SELECT s.id, s.invoice_number, s.net_amount, s.sale_date, c.name as customer_name 
          FROM sales s 
          LEFT JOIN customers c ON s.customer_id = c.id 
          ORDER BY s.sale_date DESC LIMIT 5";
$recent_sales = $conn->query($query);

// Top selling products
$query = "SELECT p.name, SUM(si.quantity) as total_qty 
          FROM sale_items si 
          JOIN products p ON si.product_id = p.id 
          GROUP BY si.product_id 
          ORDER BY total_qty DESC LIMIT 5";
$top_products = $conn->query($query);
?>

<div class="container-fluid px-4">
    <h1 class="mt-4">Dashboard</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Dashboard</li>
    </ol>
    
    <!-- Statistics Cards -->
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card bg-primary text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-0"><?php echo $stats['total_products']; ?></h3>
                            <div>Total Products</div>
                        </div>
                        <i class="fas fa-box fa-2x"></i>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="products/index.php">View Details</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-warning text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-0"><?php echo $stats['total_categories']; ?></h3>
                            <div>Categories</div>
                        </div>
                        <i class="fas fa-tags fa-2x"></i>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="categories/index.php">View Details</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-success text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-0"><?php echo $stats['total_sales']; ?></h3>
                            <div>Total Sales</div>
                        </div>
                        <i class="fas fa-shopping-cart fa-2x"></i>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="sales/index.php">View Details</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-danger text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-0"><?php echo formatCurrency($stats['total_revenue']); ?></h3>
                            <div>Total Revenue</div>
                        </div>
                        <i class="fas fa-dollar-sign fa-2x"></i>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="reports/index.php">View Reports</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Recent Sales & Top Products -->
    <div class="row">
        <div class="col-xl-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-table me-1"></i>
                    Recent Sales
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Invoice</th>
                                <th>Customer</th>
                                <th>Amount</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($recent_sales && $recent_sales->num_rows > 0): ?>
                                <?php while ($sale = $recent_sales->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $sale['invoice_number']; ?></td>
                                    <td><?php echo $sale['customer_name'] ?: 'Walk-in Customer'; ?></td>
                                    <td><?php echo formatCurrency($sale['net_amount']); ?></td>
                                    <td><?php echo formatDate($sale['sale_date']); ?></td>
                                </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="text-center">No sales found</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-chart-bar me-1"></i>
                    Top Selling Products
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Quantity Sold</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($top_products && $top_products->num_rows > 0): ?>
                                <?php while ($product = $top_products->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $product['name']; ?></td>
                                    <td><?php echo $product['total_qty']; ?></td>
                                </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="2" class="text-center">No products sold yet</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- Custom JS -->
<script>
    // Toggle sidebar
    document.getElementById('menu-toggle').addEventListener('click', function(e) {
        e.preventDefault();
        document.getElementById('wrapper').classList.toggle('toggled');
    });
</script>
</body>
</html>