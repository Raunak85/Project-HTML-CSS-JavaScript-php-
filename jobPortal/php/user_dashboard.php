<?php
session_start();
include 'db_connection.php';

$search_query = "";
if (isset($_POST['search'])) {
    $search_query = $_POST['search_query'];

    $sql = "SELECT j.job_id, j.title, j.company_name, j.description, j.location, j.vacancy_date 
            FROM jobs j 
            WHERE j.location LIKE '%$search_query%' OR j.title LIKE '%$search_query%'";
} else {
    $sql = "SELECT j.job_id, j.title, j.company_name, j.description, j.location, j.vacancy_date 
            FROM jobs j";
}

$result = $conn->query($sql);

$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

if (!$user_id) {
    // If the user is not logged in, trigger the redirect using JavaScript
    echo "<script src='../javascript/redirectLogin.js'></script>";
    echo "<script>redirectToLogin();</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="../css/user_dashboard.css">
    <script src="../javascript/jobCards.js"></script> <!-- Include the jobCards.js -->
</head>
<body>
    <nav>
        <div class="logo">
            <img src="../img/logo.ico" alt="Logo"> 
        </div>
        <ul>
            <li><a href="user_profile.php">Profile</a></li>
            <li><a href="../html/feedback.html">Feedback</a></li>
            <li><a href="../php/logout.php">Logout</a></li>
        </ul>
        <?php if ($user_id): ?>
            <div class="user-id">
                User ID: <?php echo htmlspecialchars($user_id); ?>
            </div>
        <?php endif; ?>
    </nav>

    <!-- Search form -->
    <div class="search-container">
        <form method="POST" action="">
            <input type="text" name="search_query" placeholder="Search by location or profile" value="<?php echo htmlspecialchars($search_query); ?>">
            <button type="submit" name="search">Search</button>
        </form>
    </div>

    <div class="dashboard-content">
        <h1>Available Job Vacancies</h1>
        <div class="job-listings">
           
        </div>
    </div>

    <script>
       
        const jobs = <?php echo json_encode($result->fetch_all(MYSQLI_ASSOC)); ?>;

       
        renderJobCards(jobs);
    </script>
</body>
</html>

<?php
$conn->close();
?>
