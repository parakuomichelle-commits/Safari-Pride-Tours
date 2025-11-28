<?php
session_start();
include 'db.php';

// Only allow admins
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.html");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - Safari Pride</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            display: flex;
            background: #f5f5f5;
        }
        /* Sidebar */
        .sidebar {
            width: 220px;
            background-color: #6b8e23;
            height: 100vh;
            color: #fff;
            display: flex;
            flex-direction: column;
            position: fixed;
        }
        .sidebar h2 {
            text-align: center;
            padding: 20px 0;
            border-bottom: 1px solid #fff;
            margin: 0;
        }
        .sidebar a {
            color: #fff;
            padding: 15px 20px;
            text-decoration: none;
            border-bottom: 1px solid rgba(255,255,255,0.2);
            transition: 0.3s;
        }
        .sidebar a:hover {
            background-color: #556b2f;
        }
        /* Main content */
        .main {
            margin-left: 220px;
            padding: 20px;
            width: calc(100% - 220px);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ccc;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        h3 { margin-top: 0; }
        .btn { padding: 6px 12px; background-color: #6b8e23; color: #fff; border: none; border-radius: 4px; cursor: pointer; }
        .btn:hover { background-color: #556b2f; }
    </style>
</head>
<body>

<div class="sidebar">
    <h2>Admin Panel</h2>
    <a href="?page=users">Users</a>
    <a href="?page=tours">Tours</a>
    <a href="?page=bookings">Bookings</a>
    <a href="?page=payments">Payments</a>
    <a href="logout.php">Logout</a>
</div>

<div class="main">
    <?php
    $page = $_GET['page'] ?? 'users';

    // ---------------- USERS ----------------
    if ($page === 'users') {
        echo "<h3>All Users</h3>";
        $result = $conn->query("SELECT id, full_name, email, role, created_at FROM users");
        echo "<table>
                <tr>
                    <th>ID</th><th>Name</th><th>Email</th><th>Role</th><th>Joined</th>
                </tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['id']}</td>
                    <td>{$row['full_name']}</td>
                    <td>{$row['email']}</td>
                    <td>{$row['role']}</td>
                    <td>{$row['created_at']}</td>
                  </tr>";
        }
        echo "</table>";
    }

    // ---------------- TOURS ----------------
    elseif ($page === 'tours') {
        echo "<h3>Manage Tours</h3>";
        echo '<a href="add_tour.php" class="btn">Add New Tour</a>';

        $result = $conn->query("SELECT * FROM tours");
        echo "<table>
                <tr><th>ID</th><th>Name</th><th>Location</th><th>Price</th><th>Photo</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['tour_id']}</td>
                    <td>{$row['tour_name']}</td>
                    <td>{$row['location']}</td>
                    <td>{$row['price']}</td>
                    
                  </tr>";
        }
        echo "</table>";
    }

    // ---------------- BOOKINGS ----------------
    elseif ($page === 'bookings') {
        echo "<h3>Bookings</h3>";
        $result = $conn->query("
            SELECT b.booking_id, u.full_name, t.tour_name, b.tour_date, b.payment_status
            FROM bookings b
            JOIN users u ON b.user_id=u.id
            JOIN tours t ON b.tour_id=t.tour_id
        ");
        echo "<table>
                <tr><th>ID</th><th>User</th><th>Tour</th><th>Date</th><th>Payment Status</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['booking_id']}</td>
                    <td>{$row['full_name']}</td>
                    <td>{$row['tour_name']}</td>
                    <td>{$row['tour_date']}</td>
                    <td>{$row['payment_status']}</td>
                  </tr>";
        }
        echo "</table>";
    }

    // ---------------- PAYMENTS ----------------
    elseif ($page === 'payments') {
        echo "<h3>Payments</h3>";
        $result = $conn->query("
            SELECT b.booking_id, u.full_name, t.tour_name, b.payment_status, b.tour_date
            FROM bookings b
            JOIN users u ON b.user_id=u.id
            JOIN tours t ON b.tour_id=t.tour_id
        ");
        echo "<table>
                <tr><th>Booking ID</th><th>User</th><th>Tour</th><th>Date</th><th>Payment Status</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['booking_id']}</td>
                    <td>{$row['full_name']}</td>
                    <td>{$row['tour_name']}</td>
                    <td>{$row['tour_date']}</td>
                    <td>{$row['payment_status']}</td>
                  </tr>";
        }
        echo "</table>";
    }
    ?>
</div>

</body>
</html>
