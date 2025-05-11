<?php

include 'dbConnect.php';


if ($conn) {
    // Create Members tables if not exists 
    $sqlCreateMembersTable = "CREATE TABLE IF NOT EXISTS Members (
        memberID INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        firstName VARCHAR(50) NOT NULL,
        lastName VARCHAR(50) NOT NULL,
        email VARCHAR(100) NOT NULL UNIQUE,
        memberPassword VARCHAR(255) NOT NULL,
        registrationDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP)";

    if ($conn->query($sqlCreateMembersTable) === FALSE) {
        echo "Error creating Members table: " . $conn->error . "\n";
    }

    
    // Create Activity table if it doesn't exist
    $sqlCreateActivitiesTable= "CREATE TABLE IF NOT EXISTS Activities (
        activityID INT AUTO_INCREMENT PRIMARY KEY,
        activityType ENUM('class', 'session') NOT NULL,
        activityName VARCHAR(100) NOT NULL,
        instructor VARCHAR(100),
        schedule DATETIME NOT NULL,
        duration INT UNSIGNED NOT NULL DEFAULT 60,
        capacity INT DEFAULT 20)";

    if ($conn->query($sqlCreateActivitiesTable) === FALSE) {
        echo "Error creating Activities table: " . $conn->error . "\n";
    }


    // Create activity bookings
    $sqlCreateActivityBookingTable = "CREATE TABLE IF NOT EXISTS ActivityBookings (
        bookingID INT AUTO_INCREMENT PRIMARY KEY,
        memberID INT UNSIGNED NOT NULL,
        activityID INT NOT NULL,
        bookingDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        bookingStatus ENUM('booked', 'cancelled', 'attended', 'logged in', 'logged out') DEFAULT 'booked',
        notes TEXT,
        createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (memberID) REFERENCES Members(memberID),
        FOREIGN KEY (activityID) REFERENCES Activities(activityID),
        UNIQUE KEY unique_member_activity (memberID, activityID) )";

    if ($conn->query($sqlCreateActivityBookingTable) === FALSE) {
        echo "Error creating ActivityBookings table: " . $conn->error . "\n";
    }

    // attendace table
    $sqlCreateAttendaceTable = "CREATE TABLE IF NOT EXISTS Attendance (
        attendanceID INT AUTO_INCREMENT PRIMARY KEY,
        activityID INT NOT NULL,
        memberID INT UNSIGNED NOT NULL,
        status ENUM('logged in', 'logged out', 'cancelled') NOT NULL,
        timestamp DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (activityID) REFERENCES Activities(activityID),
        FOREIGN KEY (memberID) REFERENCES Members(memberID))";

    if ($conn->query($sqlCreateAttendaceTable) === FALSE) {
        echo "Error creating Attendance table: " . $conn->error . "\n";
    }
    
}
?>

