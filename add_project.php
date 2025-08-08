<?php
require 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

// Fetch all clients for dropdown
$clients = $pdo->query("SELECT id, name FROM clients")->fetchAll();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $brief = $_POST['brief'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $status = $_POST['status'];
    $payment_status = $_POST['payment_status'];
    $client_id = $_POST['client_id'];
    $notes = $_POST['notes'];

    $stmt = $pdo->prepare("
        INSERT INTO projects (
            name, brief, start_date, end_date, 
            status, payment_status, client_id, notes
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ");
    
    if ($stmt->execute([$name, $brief, $start_date, $end_date, $status, $payment_status, $client_id, $notes])) {
        $project_id = $pdo->lastInsertId();
        
        // Add initial update
        $update_text = "Project created";
        $pdo->prepare("INSERT INTO project_updates (project_id, update_text, updated_by) VALUES (?, ?, ?)")
            ->execute([$project_id, $update_text, $_SESSION['user_id']]);
            
        header("Location: view_project.php?id=$project_id");
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add New Project</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        form { max-width: 600px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; }
        input, select, textarea { width: 100%; padding: 8px; }
        textarea { height: 100px; }
    </style>
</head>
<body>
    <h1>Add New Project</h1>
    <a href="dashboard.php">Back to Dashboard</a>
    
    <form method="post">
        <div class="form-group">
            <label>Project Name</label>
            <input type="text" name="name" required>
        </div>
        
        <div class="form-group">
            <label>Client</label>
            <select name="client_id">
                <option value="">-- Select Client --</option>
                <?php foreach ($clients as $client): ?>
                    <option value="<?= $client['id'] ?>"><?= htmlspecialchars($client['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="form-group">
            <label>Brief</label>
            <textarea name="brief"></textarea>
        </div>
        
        <div class="form-group">
            <label>Start Date</label>
            <input type="date" name="start_date">
        </div>
        
        <div class="form-group">
            <label>End Date</label>
            <input type="date" name="end_date">
        </div>
        
        <div class="form-group">
            <label>Status</label>
            <select name="status">
                <option value="Not Started">Not Started</option>
                <option value="In Progress">In Progress</option>
                <option value="Completed">Completed</option>
                <option value="On Hold">On Hold</option>
            </select>
        </div>
        
        <div class="form-group">
            <label>Payment Status</label>
            <select name="payment_status">
                <option value="Pending">Pending</option>
                <option value="Partial">Partial</option>
                <option value="Paid">Paid</option>
            </select>
        </div>
        
        <div class="form-group">
            <label>Notes</label>
            <textarea name="notes"></textarea>
        </div>
        
        <button type="submit">Create Project</button>
    </form>
</body>
</html>