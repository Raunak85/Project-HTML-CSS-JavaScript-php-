<?php
session_start();
require_once 'db_connection.php'; // database connection

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php"); // Redirect to login if not admin
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['job_id'])) {
    $job_id = $_POST['job_id'];
    
    // Prepare a statement to prevent SQL injection
    $delete_query = "DELETE FROM jobs WHERE job_id = ?";
    $stmt = $conn->prepare($delete_query);
    $stmt->bind_param("i", $job_id);
    
    if ($stmt->execute()) {
        header("Location: admin_dashboard.php"); 
        exit();
    } else {
        echo "Error deleting job: " . $stmt->error;
    }
}
?>
