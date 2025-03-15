<?php
session_start();

// Include necessary files
require_once '../../config/database.php';
require_once '../../config/config.php';
require_once '../../includes/functions.php';
require_once '../../includes/header.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    redirect('../../views/auth/login.php');
}

// Get all sales with customer names
$query = "SELECT s.*, c.name as customer_name, u.name as user_name 
          FROM sales s 
          LEFT JOIN customers c ON s.customer_id = c.id 
          LEFT JOIN users u ON s.user_id = u.id 
          ORDER BY s.sale_date DESC";
$sales = $conn->query($query);
?>

<div class="container-fluid px-4">
    <h1 class="mt-4">Sales</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="../dashboard.php">Dashboard</a></li>
        <li class="breadcrumb-item active">Sales</li>
    </ol>
    
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div><i class="fas fa-shopping-cart me-1"></i> Sales List</div>
            <a href="new.php" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> New Sale</a>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Invoice #</th>
                        <th>Customer</th>
                        <th>Amount</th>
                        <th>Payment Method</th>
                        <th>Payment Status</th>
                        <th>Date</th>
                        <th>Cashier</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($sales && $sales->num_rows > 0): ?>
                        <?php while ($sale = $sales->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $sale['invoice_number']; ?></td>
                            <td><?php echo $sale['customer_name'] ?: 'Walk-in Customer'; ?></td>
                            <td><?php echo formatCurrency($sale['net_amount']); ?></td>
                            <td>
                                <?php if ($sale['payment_method'] == 'cash'): ?>
                                <span class="badge bg-success">Cash</span>
                                <?php elseif ($sale['payment_method'] == 'card'): ?>
                                <span class="badge bg-info">Card</span>
                                <?php else: ?>
                                <span class="badge bg-secondary">Other</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($sale['payment_status'] == 'paid'): ?>
                                <span class="badge bg-success">Paid</span>
                                <?php elseif ($sale['payment_status'] == 'pending'): ?>
                                <span class="badge bg-warning">Pending</span>
                                <?php else: ?>
                                <span class="badge bg-danger">Partial</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo formatDate($sale['sale_date']); ?></td>
                            <td><?php echo $sale['user_name']; ?></td>
                            <td>
                                <a href="view.php?id=<?php echo $sale['id']; ?>" class="btn btn-info btn-sm"><i class="fas fa-eye"></i></a>
                                <a href="print.php?id=<?php echo $sale['id']; ?>" class="btn btn-secondary btn-sm"><i class="fas fa-print"></i></a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center">No sales found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
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