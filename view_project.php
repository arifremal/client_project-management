<?php
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$project_id = $_GET['id'] ?? null;
if (!$project_id) {
    header('Location: dashboard.php');
    exit();
}

$stmt = $pdo->prepare("
    SELECT p.*, c.name as client_name, c.email as client_email, c.phone as client_phone 
    FROM projects p 
    LEFT JOIN clients c ON p.client_id = c.id 
    WHERE p.id = ?
");
$stmt->execute([$project_id]);
$project = $stmt->fetch();

if (!$project) {
    header('Location: dashboard.php');
    exit();
}

// Get project updates history
$updates_stmt = $pdo->prepare("
    SELECT pu.*, u.username 
    FROM project_updates pu
    JOIN users u ON pu.updated_by = u.id
    WHERE pu.project_id = ?
    ORDER BY pu.created_at DESC
");
$updates_stmt->execute([$project_id]);
$updates = $updates_stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Project</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .project-details { background: #f9f9f9; padding: 20px; border-radius: 5px; }
        .update-form { margin-top: 30px; }
        .update { border-bottom: 1px solid #eee; padding: 10px 0; }
        .status-completed { color: green; }
        .status-in-progress { color: orange; }
        .status-not-started { color: gray; }
        .status-on-hold { color: red; }
    </style>
</head>
<body>
    <h1>Project: <?= htmlspecialchars($project['name']) ?></h1>
    <a href="dashboard.php">Back to Dashboard</a>
    
    <div class="project-details">
        <p><strong>Client:</strong> <?= htmlspecialchars($project['client_name'] ?? 'N/A') ?></p>
        <p><strong>Start Date:</strong> <?= $project['start_date'] ?? 'Not set' ?></p>
        <p><strong>End Date:</strong> <?= $project['end_date'] ?? 'Not set' ?></p>
        <p><strong>Status:</strong> <span class="status-<?= strtolower(str_replace(' ', '-', $project['status'])) ?>">
            <?= $project['status'] ?>
        </span></p>
        <p><strong>Payment Status:</strong> <?= $project['payment_status'] ?></p>
        <h3>Project Brief:</h3>
        <p><?= nl2br(htmlspecialchars($project['brief'] ?? 'No brief available')) ?></p>
        <h3>Notes:</h3>
        <p><?= nl2br(htmlspecialchars($project['notes'] ?? 'No notes available')) ?></p>
    </div>

    <?php if ($_SESSION['role'] === 'admin'): ?>
        <a href="edit_project.php?id=<?= $project_id ?>" class="btn">Edit Project</a>
    <?php endif; ?>

    <div class="update-form">
        <h2>Add Update</h2>
        <form action="add_update.php" method="post">
            <input type="hidden" name="project_id" value="<?= $project_id ?>">
            <textarea name="update_text" rows="4" cols="50" required></textarea><br>
            <button type="submit">Add Update</button>
        </form>
    </div>

    <div class="updates-history">
        <h2>Update History</h2>
        <?php if (empty($updates)): ?>
            <p>No updates yet.</p>
        <?php else: ?>
            <?php foreach ($updates as $update): ?>
                <div class="update">
                    <p><strong><?= htmlspecialchars($update['username']) ?></strong> on <?= $update['created_at'] ?></p>
                    <p><?= nl2br(htmlspecialchars($update['update_text'])) ?></p>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</body>
</html>