<?php

session_start();
require_once 'db_connection.php'; 


if (!isset($_SESSION['recruiter_id'])) {
    header("Location: login.php"); 
    exit();
}


$recruiter_id = $_SESSION['recruiter_id'];


$query = "
    SELECT j.job_id, j.title, j.company_name, a.user_id, u.full_name, u.phone, a.applied_at, a.resume_link
    FROM jobs j
    LEFT JOIN applications a ON j.job_id = a.job_id
    LEFT JOIN users u ON a.user_id = u.id
    WHERE j.posted_by = ?
";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $recruiter_id);
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/recruiter_dashboard.css"> 
    <title>Recruiter Dashboard</title>
</head>
<body>
    <nav>
        <div class="logo">
            <img src="../img/logo.ico" alt="Job Portal Logo"> 
        </div>
        <ul>
            <li><a href="post_job.php">Post a Job</a></li>
            <li><a href="recruiter_feedback.php">Feedback</a></li>
            <li><a href="../php/logout.php">Logout</a></li>
        </ul>
    </nav>
    <div class="container">
        <h1>Welcome to the Recruiter Dashboard</h1>
        <div id="job-listings">
            <h2>Your Job Listings and Applicants</h2>
            <?php if ($result->num_rows > 0): ?>
                <table>
                    <tr>
                        <th>Job Title</th>
                        <th>Company Name</th>
                        <th>Applicant Name</th>
                        <th>Applicant Phone</th>
                        <th>Applied At</th>
                        <th>Resume Link</th> 
                    </tr>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['title']); ?></td>
                            <td><?php echo htmlspecialchars($row['company_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['full_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['phone']); ?></td>
                            <td><?php echo htmlspecialchars($row['applied_at']); ?></td>
                            <td>
                                <?php if ($row['resume_link']): ?>
                                    <a href="<?php echo htmlspecialchars($row['resume_link']); ?>" target="_blank">View Resume</a>
                                <?php else: ?>
                                    No Resume Submitted
                                <?php endif; ?>
                            </td> 
                        </tr>
                    <?php endwhile; ?>
                </table>
            <?php else: ?>
                <p>No job listings found or no applicants.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
