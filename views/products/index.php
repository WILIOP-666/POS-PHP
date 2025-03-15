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

// Handle product deletion
if (isset($_GET['delete']) && !empty($_GET['delete'])) {
    $product_id = sanitize($_GET['delete']);
    
    // Check if product exists
    $query = "SELECT * FROM products WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        // Delete product
        $query = "DELETE FROM products WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $product_id);
        
        if ($stmt->execute()) {
            setFlashMessage('Product deleted successfully', 'success');
        } else {
            setFlashMessage('Failed to delete product', 'danger');
        }
    } else {
        setFlashMessage('Product not found', 'danger');
    }
    
    // Redirect to prevent resubmission
    redirect('index.php');
}

// Get all products with category names
$query = "SELECT p.*, c.name as category_name 
          FROM products p 
          LEFT JOIN categories c ON p.category_id = c.id 
          ORDER BY p.name ASC";
$products = $conn->query($query);
?>

<div class="container-fluid px-4">
    <h1 class="mt-4">Products</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="../dashboard.php">Dashboard</a></li>
        <li class="breadcrumb-item active">Products</li>
    </ol>
    
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div><i class="fas fa-table me-1"></i> Products List</div>
            <a href="add.php" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Add New Product</a>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Cost Price</th>
                        <th>Selling Price</th>
                        <th>Quantity</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($products && $products->num_rows > 0): ?>
                        <?php while ($product = $products->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $product['code']; ?></td>
                            <td><?php echo $product['name']; ?></td>
                            <td><?php echo $product['category_name'] ?: 'Uncategorized'; ?></td>
                            <td><?php echo formatCurrency($product['cost_price']); ?></td>
                            <td><?php echo formatCurrency($product['selling_price']); ?></td>
                            <td>
                                <?php if ($product['quantity'] < 10): ?>
                                <span class="badge bg-danger"><?php echo $product['quantity']; ?></span>
                                <?php else: ?>
                                <span class="badge bg-success"><?php echo $product['quantity']; ?></span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($product['status'] == 'active'): ?>
                                <span class="badge bg-success">Active</span>
                                <?php else: ?>
                                <span class="badge bg-danger">Inactive</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="view.php?id=<?php echo $product['id']; ?>" class="btn btn-info btn-sm"><i class="fas fa-eye"></i></a>
                                <a href="edit.php?id=<?php echo $product['id']; ?>" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i></a>
                                <a href="index.php?delete=<?php echo $product['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this product?')"><i class="fas fa-trash"></i></a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center">No products found</td>
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