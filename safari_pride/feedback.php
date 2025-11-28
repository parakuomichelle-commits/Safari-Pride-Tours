<?php
// Include database connection
include("db.php"); // adjust path if needed
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Feedback - Admin</title>
    <link rel="stylesheet" href="../assets/css/admin-style.css">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #004d00;
            color: white;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        h2 {
            color: #004d00;
        }
    </style>
</head>
<body>

<!-- Sidebar Navigation -->
<div class="sidebar">
    <h2>Admin Panel</h2>
    <a href="dashboard.php">Dashboard</a>
    <a href="users.php">Users</a>
    <a href="bookings.php">Bookings</a>
    <a href="tours.php">Tours</a>
    <a href="feedback.php" class="active">Feedback</a>
    <a href="logout.php">Logout</a>
</div>

<!-- Main Content -->
<div class="main-content">
    <h2>User Feedback</h2>
    <table>
        <tr>
            <th>Feedback ID</th>
            <th>User Name</th>
            <th>Tour Name</th>
            <th>Comments</th>
            <th>Rating</th>
            <th>Date</th>
        </tr>

        <?php
        // Join users and feedback table to get user names
        $query = mysqli_query($conn, "
            SELECT feedback.feedback_id, users.name AS user_name, feedback.tour_name, feedback.comments, feedback.rating, feedback.feedback_date
            FROM feedback
            INNER JOIN users ON feedback.user_id = users.id
            ORDER BY feedback.feedback_date DESC
        ");

        while($row = mysqli_fetch_assoc($query)){
            echo "<tr>
                    <td>".$row['feedback_id']."</td>
                    <td>".$row['user_name']."</td>
                    <td>".$row['tour_name']."</td>
                    <td>".$row['comments']."</td>
                    <td>".$row['rating']."</td>
                    <td>".$row['feedback_date']."</td>
                  </tr>";
        }
        ?>
    </table>
</div>

</body>
</html>
