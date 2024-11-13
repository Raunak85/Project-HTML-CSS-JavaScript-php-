<?php
require_once 'db_connection.php'; // Include database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $phone = $_POST['phone'];
    $role = $_POST['role'];

    $query = "INSERT INTO users (full_name, email, password, phone, role) VALUES ('$full_name', '$email', '$password', '$phone', '$role')";
    
    if (mysqli_query($conn, $query)) {
        header("Location: ../html/login.html");
    } else {
        echo "Error: " . $query . "<br>" . mysqli_error($conn);
    }
}
?>
