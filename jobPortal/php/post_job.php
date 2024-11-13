<?php
session_start();
require_once 'db_connection.php'; // database connection

// Check if recruiter is logged in
if (!isset($_SESSION['recruiter_id'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}

// Get recruiter ID from session
$recruiter_id = $_SESSION['recruiter_id'];

// Initialize feedback message variable
$feedback_message = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $company_name = $_POST['company_name'];
    $description = $_POST['description'];
    $location = $_POST['location'];
    $vacancy_date = $_POST['vacancy_date'];

    // Validate form data (ensure required fields are filled)
    if (!empty($title) && !empty($company_name) && !empty($description) && !empty($location) && !empty($vacancy_date)) {
        // Prepare and bind SQL statement
        $stmt = $conn->prepare("INSERT INTO jobs (title, company_name, description, location, posted_by, vacancy_date) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssis", $title, $company_name, $description, $location, $recruiter_id, $vacancy_date);

        // Execute the statement and handle success/failure
        if ($stmt->execute()) {
            $feedback_message = "Job posted successfully!";
        } else {
            $feedback_message = "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        $feedback_message = "Please fill all the required fields.";
    }
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/post_job.css"> <!-- Link to the CSS file -->
    <title>Post a Job</title>
</head>
<body>
    <nav>
        <div class="logo">
            <img src="../img/logo.ico" alt="Job Portal Logo"> <!-- logo  -->
        </div>
        <ul>
            <li><a href="recruiter_dashboard.php">Dashboard</a></li>
            <li><a href="recruiter_feedback.php">Feedback</a></li>
            <li><a href="../php/logout.php">Logout</a></li>
        </ul>
    </nav>
    
    <div class="post-job-container">
        <h1>Post a New Job</h1>

        <!-- Display feedback message -->
        <?php if (!empty($feedback_message)): ?>
            <p class="feedback-message"><?php echo htmlspecialchars($feedback_message); ?></p>
        <?php endif; ?>

        <form action="" method="POST">
            <label for="title">Job Title:</label>
            <input type="text" id="title" name="title" required>

            <label for="company_name">Company Name:</label>
            <input type="text" id="company_name" name="company_name" required>

            <label for="description">Job Description:</label>
            <textarea id="description" name="description" rows="5" required></textarea>

            <label for="location">Location:</label>
            <input type="text" id="location" name="location" required>

            <label for="vacancy_date">Vacancy Date:</label>
            <input type="date" id="vacancy_date" name="vacancy_date" required>

            <button type="submit">Post Job</button>
        </form>
    </div>
</body>
</html>
