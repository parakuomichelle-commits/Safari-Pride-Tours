<?php
include("db.php"); // Make sure path is correct

// Handle Add/Update Tour
if(isset($_POST['add_tour'])){
    $tour_id = $_POST['tour_id'] ?? '';
    $tour_name = $_POST['tour_name'];
    $location = $_POST['location'];
    $price = $_POST['price'];

    if($tour_id){ // Update existing tour
        $update = mysqli_query($conn, "UPDATE tours SET tour_name='$tour_name', location='$location', price='$price' WHERE tour_id='$tour_id'");
        if($update){
            header("Location: tours.php");
            exit();
        } else {
            echo "Error updating tour: " . mysqli_error($conn);
        }
    } else { // Add new tour
        $insert = mysqli_query($conn, "INSERT INTO tours (tour_name, location, price) VALUES ('$tour_name','$location','$price')");
        if($insert){
            header("Location: tours.php");
            exit();
        } else {
            echo "Error adding tour: " . mysqli_error($conn);
        }
    }
}

// Handle Delete Tour
if(isset($_GET['delete'])){
    $tour_id = $_GET['delete'];
    $delete = mysqli_query($conn, "DELETE FROM tours WHERE tour_id='$tour_id'");
    if($delete){
        header("Location: tours.php");
        exit();
    } else {
        echo "Error deleting tour: " . mysqli_error($conn);
    }
}

// Handle Edit Tour - Fetch data
if(isset($_GET['edit'])){
    $tour_id = $_GET['edit'];
    $editQuery = mysqli_query($conn, "SELECT * FROM tours WHERE tour_id='$tour_id'");
    $tourData = mysqli_fetch_assoc($editQuery);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tours Management - Admin</title>
    <link rel="stylesheet" href="../assets/css/admin-style.css">
    <style>
        form {
            background: #fff;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 10px;
            max-width: 500px;
        }
        form input, form button {
            padding: 10px;
            margin: 5px 0;
            width: 100%;
        }
        .action-btn {
            padding: 5px 10px;
            background: #004d00;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            margin-right: 5px;
        }
        .action-btn:hover {
            background: #ffcc00;
            color: #004d00;
        }
    </style>
</head>
<body>

<div class="sidebar">
    <h2>Admin Panel</h2>
    <a href="dashboard.php">Dashboard</a>
    <a href="users.php">Users</a>
    <a href="bookings.php">Bookings</a>
    <a href="tours.php" class="active">Tours</a>
    <a href="feedback.php">Feedback</a>
    <a href="logout.php">Logout</a>
</div>

<div class="main-content">
    <h2>Manage Tours</h2>

    <!-- Add/Edit Tour Form -->
    <form method="post">
        <h3><?php echo isset($tourData) ? "Edit Tour" : "Add New Tour"; ?></h3>
        <input type="hidden" name="tour_id" value="<?php echo isset($tourData) ? $tourData['tour_id'] : ''; ?>">
        <input type="text" name="tour_name" placeholder="Tour Name" required value="<?php echo isset($tourData) ? $tourData['tour_name'] : ''; ?>">
        <input type="text" name="location" placeholder="Location" required value="<?php echo isset($tourData) ? $tourData['location'] : ''; ?>">
        <input type="number" step="0.01" name="price" placeholder="Price" required value="<?php echo isset($tourData) ? $tourData['price'] : ''; ?>">
        <button type="submit" name="add_tour"><?php echo isset($tourData) ? "Update Tour" : "Add Tour"; ?></button>
    </form>

    <!-- Tours Table -->
    <table>
        <tr>
            <th>Tour ID</th>
            <th>Tour Name</th>
            <th>Location</th>
            <th>Price</th>
            <th>Actions</th>
        </tr>
        <?php
        $query = mysqli_query($conn, "SELECT * FROM tours ORDER BY tour_id DESC");
        while($row = mysqli_fetch_assoc($query)){
            echo "<tr>
                    <td>".$row['tour_id']."</td>
                    <td>".$row['tour_name']."</td>
                    <td>".$row['location']."</td>
                    <td>".$row['price']."</td>
                    <td>
                        <a class='action-btn' href='tours.php?edit=".$row['tour_id']."'>Edit</a>
                        <a class='action-btn' href='tours.php?delete=".$row['tour_id']."' onclick='return confirm(\"Are you sure?\")'>Delete</a>
                    </td>
                  </tr>";
        }
        ?>
    </table>
</div>

</body>
</html>
