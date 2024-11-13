<?php
session_start();
require_once 'db_connection.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}


if (isset($_POST['feedback_id'])) {
    $feedback_id = intval($_POST['feedback_id']); 

    
    $stmt = $conn->prepare("DELETE FROM feedback WHERE feedback_id = ?");
    $stmt->bind_param("i", $feedback_id);

    if ($stmt->execute()) {
       
        header("Location: AdminFeedbackDisplay.php?success=1");
        exit();
    } else {
        
        header("Location: AdminFeedbackDisplay.php?error=1");
        exit();
    }
} else {
    header("Location: AdminFeedbackDisplay.php");
    exit();
}
?>
