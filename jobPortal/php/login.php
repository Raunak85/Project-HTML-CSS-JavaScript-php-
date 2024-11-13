<?php
session_start();
require_once 'db_connection.php'; // database connection

$error_message = ""; 
$registration_message = ""; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];


    $query = "SELECT id, password FROM users WHERE email = ? AND role = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $email, $role);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row && password_verify($password, $row['password'])) {
        
        $_SESSION['user_id'] = $row['id']; 
        $_SESSION['email'] = $email;
        $_SESSION['role'] = $role;

     
        switch ($role) {
            case 'recruiter':
                $_SESSION['recruiter_id'] = $row['id'];
                header("Location: ../php/recruiter_dashboard.php");
                exit();
            case 'user':
                header("Location: ../php/user_dashboard.php");
                exit();
            case 'admin':
                header("Location: ../php/admin_dashboard.php");
                exit();
            default:
                $error_message = "Invalid role!"; // Fallback error message
        }
    } else {
     
        $check_query = "SELECT id FROM users WHERE email = ?";
        $check_stmt = $conn->prepare($check_query);
        $check_stmt->bind_param("s", $email);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();

        if ($check_result->num_rows > 0) {
            $error_message = "Invalid password or role!"; 
        } else {
            $registration_message = "Not registered!"; 
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../css/login.css">
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <form action="" method="POST">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <label for="role">Login as:</label>
            <select name="role" id="role" required>
                <option value="user">User</option>
                <option value="recruiter">Recruiter</option>
                <option value="admin">Admin</option>
            </select>
            <button type="submit">Login</button>
            <?php if (!empty($error_message)): ?>
                <div class="error-message"><?php echo htmlspecialchars($error_message); ?></div>
            <?php endif; ?>
            <?php if (!empty($registration_message)): ?>
                <div class="registration-message"><?php echo htmlspecialchars($registration_message); ?></div>
            <?php endif; ?>
            <p>Not registered? <a href="../html/signup.html">Sign up here</a></p>
        </form>
    </div>
</body>
</html>
