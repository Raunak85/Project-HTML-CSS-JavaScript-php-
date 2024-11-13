<?php

include 'db_connection.php'; // Ensure this is correctly linked

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
} else {
    echo "Database connection successful!";
}

?>
