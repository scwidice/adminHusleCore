<?php
session_start();
include 'dbConnect.php';

// Check if the admin is logged in
if (!isset($_SESSION['adminLoggedIn']) || $_SESSION['adminLoggedIn'] !== true) {
    header("Location: signIn.php");
    exit();
}

// Get the admin's name from the session
$adminName = isset($_SESSION['adminName']) ? htmlspecialchars($_SESSION['adminName']) : 'Admin';

// Fetch report data
// Total Members
$totalMembersQuery = $conn->prepare("SELECT COUNT(*) as total FROM Members");
$totalMembersQuery->execute();
$totalMembersResult = $totalMembersQuery->get_result();
$totalMembers = $totalMembersResult->fetch_assoc()['total'] ?? 0;

// Today's Enrollments
$today = date('Y-m-d');
$dailyEnrollmentsQuery = $conn->prepare("SELECT COUNT(*) as total FROM ActivityBookings WHERE DATE(createdAt) = ?");
$dailyEnrollmentsQuery->bind_param("s", $today);
$dailyEnrollmentsQuery->execute();
$dailyEnrollmentsResult = $dailyEnrollmentsQuery->get_result();
$dailyEnrollments = $dailyEnrollmentsResult->fetch_assoc()['total'] ?? 0;

// Today's Sessions
$dailySessionsQuery = $conn->prepare("SELECT COUNT(*) as total FROM Activities WHERE DATE(schedule) = ?");
$dailySessionsQuery->bind_param("s", $today);
$dailySessionsQuery->execute();
$dailySessionsResult = $dailySessionsQuery->get_result();
$dailySessions = $dailySessionsResult->fetch_assoc()['total'] ?? 0;

// Cancellations
$cancellationsQuery = $conn->prepare("SELECT COUNT(*) as total FROM ActivityBookings WHERE bookingStatus = 'cancelled' AND DATE(updatedAt) = ?");
$cancellationsQuery->bind_param("s", $today);
$cancellationsQuery->execute();
$cancellationsResult = $cancellationsQuery->get_result();
$cancellations = $cancellationsResult->fetch_assoc()['total'] ?? 0;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Reports - HustleCore</title>
    <link rel="stylesheet" href="adminStyles.css">
</head>
<body>
    <header>
        <a href="adminDashboard.php"> <image class="logo" src="images/logo.png" alt="gym logo"> </image> </a>
        <div class="user">
            <span><?php echo htmlspecialchars($adminName); ?></span>
            <form method="post" action="signIn.php">
                <button type="submit" name="logout">Log out</button>
            </form>
        </div>
    </header>

    <main>
        <div class="sideBar">
            <h4>Admin Panel</h4>
            <a href="adminDashboard.php">Dashboard</a>
            <a href="adminManageActivities.php">Manage Classes & Sessions</a>
            <a href="adminMembers.php">Members</a>
            <a href="adminManageBookings.php">Bookings</a>
            <p class="currentPage"><a href="adminReports.php">Reports</a></p>
        </div>

        <div class="dashboardContent">
            <div class="breadcrumb">&gt; Reports</div>
            <h2 class="adminHeading2">Reports</h2>

            <div class="stats">
                <div class="card">
                    <h5>Total Members</h5>
                    <p><?php echo $totalMembers; ?></p>
                </div>
                <div class="card">
                    <h5>Today's Enrollments</h5>
                    <p><?php echo $dailyEnrollments; ?></p>
                </div>
                <div class="card">
                    <h5>Today's Sessions</h5>
                    <p><?php echo $dailySessions; ?></p>
                </div>
                <div class="card">
                    <h5>Cancellations</h5>
                    <p><?php echo $cancellations; ?></p>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
