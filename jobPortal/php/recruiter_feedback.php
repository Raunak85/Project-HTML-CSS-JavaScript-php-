<?php

session_start();
require_once 'db_connection.php'; //database connection

// Check if recruiter is logged in
if (!isset($_SESSION['recruiter_id'])) {
    header("Location: login.php"); // Redirect if not logged in
    exit();
}

$recruiter_id = $_SESSION['recruiter_id']; // Get recruiter ID from session
$message = "";
$job_id = null;
$feedbackSubmitted = false; // Flag to track feedback submission

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $message = $_POST['message'];
    $job_id = !empty($_POST['job_id']) ? $_POST['job_id'] : null;

    // Prepare and execute the SQL query to insert feedback
    $query = "INSERT INTO feedback (user_id, job_id, message, submitted_by) VALUES (?, ?, ?, 'recruiter')";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iis", $recruiter_id, $job_id, $message);

    if ($stmt->execute()) {
        $feedbackSubmitted = true; // Set flag to true when feedback is submitted successfully
    } else {
        echo "Error submitting feedback: " . $stmt->error;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recruiter Feedback</title>
    <link rel="stylesheet" href="../css/recruiter_feedback.css"> <!-- Link to CSS file -->
    <script>
        // JavaScript to show alert on successful feedback submission
        function showSuccessAlert() {
            alert("Feedback submitted successfully!");
        }
    </script>
</head>
<body>
    <nav>
        <ul>
            <li><a href="recruiter_dashboard.php">Dashboard</a></li>
            <li><a href="post_job.php">Post Job</a></li>
            <li><a href="../php/logout.php">Logout</a></li>
        </ul>
    </nav>

    <div class="container">
        <h1>Submit Feedback</h1>

        <!-- Trigger the JavaScript alert if feedback is submitted successfully -->
        <?php if ($feedbackSubmitted): ?>
            <script>showSuccessAlert();</script>
        <?php endif; ?>

        <form action="" method="POST">
            <label for="message">Feedback Message:</label>
            <textarea id="message" name="message" required></textarea>

            <label for="job_id">Related Job ID (Optional):</label>
            <input type="number" id="job_id" name="job_id" placeholder="Enter Job ID if applicable">

            <button type="submit">Submit Feedback</button>
        </form>
    </div>
</body>
</html>
