<?php
// Start the session
session_start();

// Database connection
include '../php/db_connection.php'; 

// Fetch user ID from session
$user_id = $_SESSION['user_id'];

// Fetch user full name
$user_query = $conn->prepare("SELECT full_name FROM users WHERE id = ?");
$user_query->bind_param("i", $user_id);
$user_query->execute();
$user_result = $user_query->get_result();
$user_data = $user_result->fetch_assoc();
$user_query->close();

// Get job_id from query parameter
$job_id = isset($_GET['job_id']) ? $_GET['job_id'] : '';

// Check for success or error message
$success_message = isset($_GET['success']) ? "Application submitted successfully!" : "";
$error_message = isset($_GET['error']) ? "Error submitting application. Please try again." : "";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Application</title>
    <link rel="stylesheet" href="../css/apply.css"> <!-- css file link -->
    <script>
        function showMessage(message) {
            alert(message);
        }
    </script>
</head>
<body>
    <nav class="dashboard-navbar">
        <div class="logo">
            <img src="../img/logo.ico" alt="Logo"> <!-- logo image -->
        </div>
        <ul>
            <li><a href="../php/user_profile.php">Profile</a></li>
            <li><a href="../php/user_dashboard.php">Dashboard</a></li> <!-- Link to user dashboard -->
            <li><a href="../html/feedback.html">Feedback</a></li>
            <li><a href="../php/logout.php">Logout</a></li>
        </ul>
    </nav>

    <?php if ($success_message): ?>
        <script>showMessage("<?php echo addslashes($success_message); ?>");</script>
    <?php endif; ?>

    <?php if ($error_message): ?>
        <script>showMessage("<?php echo addslashes($error_message); ?>");</script>
    <?php endif; ?>

    <div class="application-form">
        <h1>Job Application</h1>
        <form method="POST" action="../php/submit_application.php"> <!-- Point to  PHP processing file -->
            <input type="hidden" name="job_id" value="<?php echo htmlspecialchars($job_id); ?>">
            
            <label for="user_id">User ID:</label>
            <input type="text" id="user_id" name="user_id" value="<?php echo htmlspecialchars($user_id); ?>" readonly>

            <label for="full_name">Full Name:</label>
            <input type="text" id="full_name" name="full_name" value="<?php echo htmlspecialchars($user_data['full_name']); ?>" readonly>

            <label for="mobile">Mobile Number:</label>
            <input type="text" id="mobile" name="mobile" pattern="[0-9]{10}" required placeholder="Enter 10-digit mobile number">

            <label for="skills">Skills:</label>
            <input type="text" id="skills" name="skills" required>

            <label for="resume_link">Resume Link:</label>
            <input type="url" id="resume_link" name="resume_link" required>

            <button type="submit">Submit Application</button>
        </form>
    </div>
</body>
</html>
