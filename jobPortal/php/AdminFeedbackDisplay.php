<?php
session_start();
require_once 'db_connection.php'; 

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php"); 
    exit();
}


$query = "SELECT f.feedback_id, f.message, f.submitted_by, f.submitted_at, u.email, f.job_id 
          FROM feedback f 
          JOIN users u ON f.user_id = u.id 
          ORDER BY f.submitted_at DESC"; 

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback Management</title>
    <link rel="stylesheet" href="../css/AdminFeedbackDisplay.css"> 
</head>
<body>
    <nav>
        <div class="logo">
            <img src="../img/logo.ico" alt="Logo">
        </div>
        <ul>
            <li><a href="admin_dashboard.php">Dashboard</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>
    
    <div class="feedback-container">
        <h1>Feedback Management</h1>
        
        <h2>All Feedback</h2>
        <table>
            <thead>
                <tr>
                    <th>Message</th>
                    <th>User Email</th>
                    <th>Submitted By</th>
                    <th>Job ID</th>
                    <th>Submitted At</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($feedback = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($feedback['message']); ?></td>
                            <td><?php echo htmlspecialchars($feedback['email']); ?></td>
                            <td><?php echo htmlspecialchars($feedback['submitted_by']); ?></td>
                            <td><?php echo htmlspecialchars($feedback['job_id'] ? $feedback['job_id'] : 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($feedback['submitted_at']); ?></td>
                            <td>
                                <form action="delete_feedbackAdmin.php" method="POST" style="display:inline;">
                                    <input type="hidden" name="feedback_id" value="<?php echo $feedback['feedback_id']; ?>">
                                    <button type="submit" class="delete-button" onclick="return confirm('Are you sure you want to delete this feedback?');">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6">No feedback found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
