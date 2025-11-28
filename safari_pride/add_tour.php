<?php
session_start();
include 'db.php';

// Only allow admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.html");
    exit();
}

$msg = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tour_name = $_POST['tour_name'] ?? '';
    $location = $_POST['location'] ?? '';
    $price = $_POST['price'] ?? '';

    // Handle file upload
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        $filename = time() . "_" . $_FILES['photo']['name'];
        $destination = "uploads/" . $filename;
        if (move_uploaded_file($_FILES['photo']['tmp_name'], $destination)) {
            // Insert tour into database
            $stmt = $conn->prepare("INSERT INTO tours (tour_name, location, price, photo) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssds", $tour_name, $location, $price, $filename);
            if ($stmt->execute()) {
                $msg = "Tour added successfully!";
            } else {
                $msg = "Error: " . $stmt->error;
            }
        } else {
            $msg = "Failed to upload image.";
        }
    } else {
        $msg = "Please upload a photo.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add New Tour - Safari Pride</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f0f3f7; padding: 50px; }
        .container { max-width: 500px; margin: auto; background: #fff; padding: 30px; border-radius: 12px; box-shadow: 0 8px 20px rgba(0,0,0,0.1);}
        h2 { text-align: center; margin-bottom: 20px; }
        label { display: block; margin: 10px 0 5px; font-weight: bold; }
        input[type="text"], input[type="number"], input[type="file"] { width: 100%; padding: 10px; border-radius: 6px; border: 1px solid #ccc; }
        button { margin-top: 20px; width: 100%; padding: 12px; background: #6b8e23; color: white; border: none; border-radius: 6px; cursor: pointer; }
        button:hover { background: #556b2f; }
        .message { text-align: center; margin-top: 15px; font-weight: bold; color: green; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Add New Tour</h2>
        <?php if($msg != "") echo "<div class='message'>$msg</div>"; ?>
        <form method="POST" enctype="multipart/form-data">
            <label>Tour Name:</label>
            <input type="text" name="tour_name" required>

            <label>Location:</label>
            <input type="text" name="location" required>

            <label>Price:</label>
            <input type="number" name="price" step="0.01" required>

            <label>Photo:</label>
            <input type="file" name="photo" accept="image/*" required>

            <button type="submit">Add Tour</button>
        </form>
        <p style="text-align:center; margin-top:15px;"><a href="admin_dashboard.php" style="text-decoration:none; color:#6b8e23;">Back to Dashboard</a></p>
    </div>
</body>
</html>
