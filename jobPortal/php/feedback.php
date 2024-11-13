<?php
session_start(); 
include 'db_connection.php'; 

$feedback_message = ""; 


if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id']; 
    $submitted_by = 'user';
} else {
    echo "User ID not found. Please log in.";
    exit();
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $message = $_POST['message']; 
    $job_id = !empty($_POST['job_id']) ? $_POST['job_id'] : null; 

   
    $stmt = $conn->prepare("INSERT INTO feedback (user_id, job_id, message, submitted_by) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiss", $user_id, $job_id, $message, $submitted_by);

    if ($stmt->execute()) {
        $feedback_message = "Feedback submitted successfully!";
    } else {
        $feedback_message = "Error: " . $stmt->error;
    }

    $stmt->close();
}


$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Feedback</title>
    <link rel="stylesheet" href="../css/feedback.css"> <!-- CSS file -->
</head>
<body>
    <nav>
        <ul>
            <li><a href="user_dashboard.php">Dashboard</a></li>
            <li><a href="../php/logout.php">Logout</a></li>
        </ul>
    </nav>
    <div class="feedback-container">
        <h2>Submit Feedback</h2>
        <form action="" method="POST">
            <label for="message">Message:</label>
            <textarea id="message" name="message" required></textarea>

            <button type="submit">Submit Feedback</button>
        </form>

        <!-- Display feedback submission message -->
        <?php if (isset($feedback_message)): ?>
            <div class="message"><?php echo htmlspecialchars($feedback_message); ?></div>
        <?php endif; ?>
    </div>
</body>
</html>
