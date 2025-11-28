<?php
include("db.php");

// Optional: Add admin login check here
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - Safari Pride Tours</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <style>
        /* Dashboard specific styling */
        body {font-family: Arial, sans-serif; background: #f5f5f5;}
        .dashboard-header {background: #004d00; color: white; padding: 20px; text-align: center;}
        .dashboard-nav {display: flex; justify-content: center; gap: 20px; margin: 20px 0;}
        .dashboard-nav a {background: #ffcc00; padding: 10px 20px; border-radius: 5px; color: #004d00; font-weight: bold; text-decoration: none;}
        .dashboard-nav a:hover {background: #e6b800;}
        .dashboard-section {display: flex; justify-content: center; gap: 30px; flex-wrap: wrap; margin: 20px;}
        .card {background: white; padding: 20px; border-radius: 10px; width: 250px; box-shadow: 0 5px 15px rgba(0,0,0,0.1);}
        .card h3 {margin-top: 0;}
        table {width: 100%; border-collapse: collapse;}
        th, td {padding: 10px; border-bottom: 1px solid #ddd;}
        th {background: #004d00; color: white;}
    </style>
</head>
<body>

<div class="dashboard-header">
    <h1>Admin Dashboard</h1>
</div>

<div class="dashboard-nav">
    <a href="dashboard.php">Dashboard</a>
    <a href="users.php">Users</a>
    <a href="bookings.php">Bookings</a>
    <a href="tours.php">Tours</a>
</div>

<div class="dashboard-section">
    <div class="card">
        <h3>Total Users</h3>
        <?php
        $userCount = mysqli_query($conn, "SELECT * FROM users");
        echo "<p>" . mysqli_num_rows($userCount) . "</p>";
        ?>
    </div>
    <div class="card">
        <h3>Total Bookings</h3>
        <?php
        $bookingCount = mysqli_query($conn, "SELECT * FROM bookings");
        echo "<p>" . mysqli_num_rows($bookingCount) . "</p>";
        ?>
    </div>
    <div class="card">
        <h3>Total Tours</h3>
        <?php
        $tourCount = mysqli_query($conn, "SELECT * FROM tours");
        echo "<p>" . mysqli_num_rows($tourCount) . "</p>";
        ?>
    </div>
</div>

</body>
</html>
