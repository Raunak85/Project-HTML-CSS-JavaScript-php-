<?php
// Start the session to access session variables
session_start();

// Database connection
include 'db_connection.php';

// Get user_id from session
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

if (!$user_id) {
    echo "Please log in to view your profile.";
    exit();
}

// Query to get distinct company names for jobs applied by the user
$applied_companies_query = "
    SELECT DISTINCT j.company_name 
    FROM applications a 
    JOIN jobs j ON a.job_id = j.job_id 
    WHERE a.user_id = ?";
$stmt = $conn->prepare($applied_companies_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$applied_result = $stmt->get_result();

// Count total unique companies
$company_count = $applied_result->num_rows;

// Fetch the applied companies for display
$applied_companies = [];
while ($row = $applied_result->fetch_assoc()) {
    $applied_companies[] = htmlspecialchars($row['company_name']);
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="../css/user_profile.css"> <!-- Ensure the path to CSS is correct -->
</head>
<body>
    <nav>
        <div class="logo">
            <img src="../img/logo.ico" alt="Logo"> <!-- Replace with your logo image -->
        </div>
        <ul>
            <li><a href="user_dashboard.php">Dashboard</a></li>
            <li><a href="../html/feedback.html">Feedback</a></li>
            <li><a href="../php/logout.php">Logout</a></li>
        </ul>
        <div class="user-id">
            <?php if ($user_id): ?>
                User ID: <span><?php echo htmlspecialchars($user_id); ?></span>
            <?php endif; ?>
        </div>
    </nav>

    <div class="profile-content">
        <h1>User Profile</h1>
        <h2>Companies Applied To: <?php echo $company_count; ?></h2>
        <table>
            <thead>
                <tr>
                    <th>Company Name</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($company_count > 0): ?>
                    <?php foreach ($applied_companies as $company): ?>
                        <tr>
                            <td><?php echo $company; ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td>No companies applied to yet.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
