<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bott";

$conn = mysqli_connect($servername, $username, $password, $dbname) or die("Database Error: " . mysqli_connect_error());

if (isset($_POST['userQuery'])) {
    $userQuery = trim($_POST['userQuery']);
    $emailid = isset($_POST['emailid']) ? trim($_POST['emailid']) : '';

    if ($emailid === '') {
        echo "Email ID not provided.";
        exit;
    }

    // Validate email format
    if (!filter_var($emailid, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format.";
        exit;
    }

    $query = $userQuery;

    // Use prepared statement to insert data into the query table
    $stmt = $conn->prepare("INSERT INTO query (query, emailid) VALUES (?, ?)");
    $stmt->bind_param("ss", $query, $emailid);

    // Execute the statement
    if ($stmt->execute()) {
        echo "Thank you for providing your email!";  // Message to the user
    } else {
        echo "Error: Unable to insert data into database.";
    }

    // Close the statement
    $stmt->close();
} else {
    echo "No data received.";
}

mysqli_close($conn);


