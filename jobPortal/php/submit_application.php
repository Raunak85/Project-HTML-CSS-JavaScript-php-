<?php
session_start();
require_once 'db_connection.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST['user_id'];
    $job_id = $_POST['job_id'];
    $skills = $_POST['skills'];
    $resume_link = $_POST['resume_link'];
    $mobile = $_POST['mobile']; 

   
    $query = "INSERT INTO applications (user_id, job_id, skills, resume_link, mobile) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iisss", $user_id, $job_id, $skills, $resume_link, $mobile);

    if ($stmt->execute()) {
       
        header("Location: ../html/apply.php?success=1&job_id=" . urlencode($job_id));
        exit();
    } else {
        
        header("Location: ../html/apply.php?error=1&job_id=" . urlencode($job_id));
        exit();
    }

    $stmt->close();
}
$conn->close();
?>
