<?php
session_start();
require_once 'db_connection.php'; 

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php"); 
    exit();
}


$query = "SELECT j.job_id, j.title, j.company_name, j.location, u.email AS recruiter_email 
          FROM jobs j 
          JOIN users u ON j.posted_by = u.id"; 

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../css/admin_dashboard.css">    
</head>
<body>
    <!-- navbar -->
    <nav>
        <!-- logo -->
        <div class="logo">
            <img src="../img/logo.ico" alt="Logo"> 
        </div>
        <ul>
            <li><a href="AdminFeedbackDisplay.php">Feedback</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>
        <!-- show on admin Dashboard -->
    <div class="dashboard-container">
        <h1>Admin Dashboard</h1>
        <h2>Jobs Posted by Recruiters</h2>
        
        <table>
            <thead>
                <tr>
                    <th>Job Title</th>
                    <th>Company Name</th>
                    <th>Location</th>
                    <th>Recruiter Email</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($job = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($job['title']); ?></td>
                            <td><?php echo htmlspecialchars($job['company_name']); ?></td>
                            <td><?php echo htmlspecialchars($job['location']); ?></td>
                            <td><?php echo htmlspecialchars($job['recruiter_email']); ?></td>
                            <td>
                                <form action="delete_job.php" method="POST" style="display:inline;">
                                    <input type="hidden" name="job_id" value="<?php echo $job['job_id']; ?>">
                                    <button type="submit" class="delete-button" onclick="return confirm('Are you sure you want to delete this job?');">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5">No jobs found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
