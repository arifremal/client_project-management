<?php
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$isAdmin = ($_SESSION['role'] == 'admin');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <style>
        body { font-family: poppins; margin: 0; padding: 0; }
        .header { background: #333; color: white; padding: 10px 20px; display: flex; justify-content: space-between; }
        .container { padding: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .btn { padding: 5px 10px; text-decoration: none; border-radius: 3px; }
        .btn-primary { background: #337ab7; color: white; }
        .btn-success { background: #5cb85c; color: white; }
        .btn-danger { background: #d9534f; color: white; }
        .status-not-started { color: #777; }
        .status-in-progress { color: #f0ad4e; }
        .status-completed { color: #5cb85c; }
        .status-on-hold { color: #d9534f; }
        .payment-pending { color: #d9534f; }
        .payment-partial { color: #f0ad4e; }
        .payment-paid { color: #5cb85c; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Project Dashboard</h2>
        <div>
            <span>Welcome, <?php echo $_SESSION['username']; ?> (<?php echo $_SESSION['role']; ?>)</span>
            <a href="logout.php" style="color: white; margin-left: 15px;">Logout</a>
        </div>
    </div>
    
    <div class="container">
        <?php if ($isAdmin): ?>
            <a href="add_project.php" class="btn btn-primary">Add New Project</a>
            <a href="add_client.php" class="btn btn-primary">Add New Client</a>
        <?php endif; ?>
        <a href="generate_pdf.php" class="btn btn-success">Export to PDF</a>
        
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Project Name</th>
                    <th>Client</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Status</th>
                    <th>Payment</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = "SELECT p.*, c.name as client_name FROM projects p LEFT JOIN clients c ON p.client_id = c.id";
                $stmt = $pdo->query($query);
                
                while ($row = $stmt->fetch()):
                ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                    <td><?php echo htmlspecialchars($row['client_name'] ?? 'N/A'); ?></td>
                    <td><?php echo $row['start_date'] ?? 'Not set'; ?></td>
                    <td><?php echo $row['end_date'] ?? 'Not set'; ?></td>
                    <td class="status-<?php echo str_replace(' ', '-', strtolower($row['status'])); ?>">
                        <?php echo $row['status']; ?>
                    </td>
                    <td class="payment-<?php echo strtolower($row['payment_status']); ?>">
                        <?php echo $row['payment_status']; ?>
                    </td>
                    <td>
                        <a href="view_project.php?id=<?php echo $row['id']; ?>" class="btn btn-primary">View</a>
                        <?php if ($isAdmin): ?>
                            <a href="edit_project.php?id=<?php echo $row['id']; ?>" class="btn btn-success">Edit</a>
                            <a href="delete_project.php?id=<?php echo $row['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>