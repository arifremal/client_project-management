<?php
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$isAdmin = ($_SESSION['role'] == 'admin');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Project Management</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Poppins Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
        }
        .navbar-brand {
            font-weight: 600;
        }
        .card {
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            border: none;
        }
        .status-badge {
            font-size: 0.85rem;
            padding: 5px 10px;
            border-radius: 20px;
            font-weight: 500;
        }
        .status-not-started {
            background-color: #6c757d;
            color: white;
        }
        .status-in-progress {
            background-color: #fd7e14;
            color: white;
        }
        .status-completed {
            background-color: #198754;
            color: white;
        }
        .status-on-hold {
            background-color: #dc3545;
            color: white;
        }
        .payment-pending {
            background-color: #dc3545;
            color: white;
        }
        .payment-partial {
            background-color: #ffc107;
            color: black;
        }
        .payment-paid {
            background-color: #198754;
            color: white;
        }
        .action-btns .btn {
            padding: 5px 10px;
            font-size: 0.85rem;
        }
        .table th {
            background-color: #f1f5fd;
            font-weight: 600;
            color: #495057;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-black">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="bi bi-kanban"></i> Project Dashboard
            </a>
            <div class="d-flex align-items-center">
                <span class="text-white me-3">
                    <i class="bi bi-person-circle me-1"></i>
                    <?php echo $_SESSION['username']; ?> (<?php echo ucfirst($_SESSION['role']); ?>)
                </span>
                <a href="logout.php" class="btn btn-outline-light">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container-fluid mt-4">
        <div class="container">
            <!-- Action Buttons -->
            <div class="d-flex justify-content-between mb-4">
                <h2 class="mb-0">Project Overview</h2>
                <div>
                    <?php if ($isAdmin): ?>
                        <a href="add_project.php" class="btn btn-primary me-2">
                            <i class="bi bi-plus-circle"></i> Add Project
                        </a>
                        <a href="add_client.php" class="btn btn-primary me-2">
                            <i class="bi bi-person-plus"></i> Add Client
                        </a>
                    <?php endif; ?>
                    <a href="generate_pdf.php" class="btn btn-success">
                        <i class="bi bi-file-earmark-pdf"></i> Export PDF
                    </a>
                </div>
            </div>

            <!-- Projects Table -->
            <div class="card shadow-sm mb-5">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Project Name</th>
                                    <th>Client</th>
                                    <th>Brief</th>
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
                                    $statusClass = strtolower(str_replace(' ', '-', $row['status']));
                                    $paymentClass = strtolower($row['payment_status']);
                                ?>
                                <tr>
                                    <td><?php echo $row['id']; ?></td>
                                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['client_name'] ?? 'N/A'); ?></td>
                                    <td><?php echo htmlspecialchars($row['brief'] ?? 'N/A'); ?></td>
                                    <td><?php echo $row['start_date'] ? date('M d, Y', strtotime($row['start_date'])) : 'Not set'; ?></td>
                                    <td><?php echo $row['end_date'] ? date('M d, Y', strtotime($row['end_date'])) : 'Not set'; ?></td>
                                    <td>
                                        <span class="status-badge status-<?php echo $statusClass; ?>">
                                            <?php echo $row['status']; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="status-badge payment-<?php echo $paymentClass; ?>">
                                            <?php echo $row['payment_status']; ?>
                                        </span>
                                    </td>
                                    <td class="action-btns">
                                        <div class="d-flex gap-2">
                                            <a href="view_project.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-primary" title="View">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <?php if ($isAdmin): ?>
                                                <a href="edit_project.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-success" title="Edit">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <a href="delete_project.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-danger" title="Delete" onclick="return confirm('Are you sure you want to delete this project?')">
                                                    <i class="bi bi-trash"></i>
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>