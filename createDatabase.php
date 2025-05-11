<?php
$servername = "localhost";
$username = "root";
$password = "";

// Connect without selecting a DB yet
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// DEBUGGING: $DROP = "DROP DATABASE IF EXISTS gym_db";
// if ($conn->query($DROP) === TRUE) {
//     echo "Database 'gym_db' dropped successfully!";
// } else {
//     echo "Error dropping database: " . $conn->error;
// }

//Create database
$dbName = "gym_db";
$sql = "CREATE DATABASE IF NOT EXISTS $dbName";

if ($conn->query($sql) === TRUE) {
    echo "Database '$dbName' created successfully!";
} else {
    echo "Error creating database: " . $conn->error;
}

$conn->close();
?>