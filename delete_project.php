<?php
require 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

$project_id = $_GET['id'] ?? null;
if (!$project_id) {
    header('Location: dashboard.php');
    exit();
}

try {
    $pdo->beginTransaction();
    
    // Delete project updates first
    $pdo->prepare("DELETE FROM project_updates WHERE project_id = ?")
        ->execute([$project_id]);
    
    // Then delete the project
    $pdo->prepare("DELETE FROM projects WHERE id = ?")
        ->execute([$project_id]);
    
    $pdo->commit();
    
    $_SESSION['message'] = "Project deleted successfully";
} catch (Exception $e) {
    $pdo->rollBack();
    $_SESSION['error'] = "Error deleting project: " . $e->getMessage();
}

header('Location: dashboard.php');
exit();
?>