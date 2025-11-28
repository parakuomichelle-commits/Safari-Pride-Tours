<?php
session_start();
include 'db.php';

// Ensure only logged-in customers can access
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'customer') {
    header("Location: login.html");
    exit();
}

// Book tour
if (isset($_POST['book_tour'])) {
    $tour_id = $_POST['tour_id'];
    $user_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("INSERT INTO bookings (user_id, tour_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $user_id, $tour_id);
    $stmt->execute();
    $stmt->close();
}

// Delete booking
if (isset($_POST['delete_booking'])) {
    $booking_id = $_POST['booking_id'];

    // 1️⃣ Delete payment first
    $stmt = $conn->prepare("DELETE FROM payments WHERE booking_id=?");
    $stmt->bind_param("i", $booking_id);
    $stmt->execute();
    $stmt->close();

    // 2️⃣ Delete booking now that payment is gone
    $stmt = $conn->prepare("DELETE FROM bookings WHERE booking_id=? AND user_id=?");
    $stmt->bind_param("ii", $booking_id, $_SESSION['user_id']);
    $stmt->execute();
    $stmt->close();
}


// Handle Pay Now
if (isset($_POST['pay_now'])) {
    $booking_id = $_POST['booking_id'];
    $amount = $_POST['amount'];

    // Check if payment already exists
    $stmt = $conn->prepare("SELECT * FROM payments WHERE booking_id=?");
    $stmt->bind_param("i", $booking_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        // Insert payment
        $stmt2 = $conn->prepare("INSERT INTO payments (booking_id, amount, status) VALUES (?, ?, 'Paid')");
        $stmt2->bind_param("id", $booking_id, $amount);
        $stmt2->execute();
        $stmt2->close();
    }
    $stmt->close();

    // Refresh page to show updated status
    header("Location: customer_dashboard.php?view=payments");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Customer Dashboard - Safari Pride</title>
<style>
body { font-family: Arial, sans-serif; margin: 0; background-color: #eaf4e3; }
.sidebar { width: 200px; background-color: #3b8d3b; color: white; height: 100vh; position: fixed; padding-top: 20px; }
.sidebar a { display: block; color: white; padding: 15px; text-decoration: none; margin-bottom: 5px; }
.sidebar a:hover { background-color: #2c6b2c; }
.content { margin-left: 210px; padding: 20px; }
h2 { color: #2c6b2c; }
.tour-card { background: white; border-radius: 10px; padding: 15px; margin-bottom: 15px; display: flex; align-items: center; }
.tour-card img { width: 150px; height: 100px; margin-right: 15px; border-radius: 10px; object-fit: cover; }
button { padding: 10px 15px; background-color: #3b8d3b; color: white; border: none; border-radius: 5px; cursor: pointer; }
button:hover { background-color: #2c6b2c; }
</style>
</head>
<body>

<div class="sidebar">
    <a href="?view=tours">Available Tours</a>
    <a href="?view=bookings">My Bookings</a>
    <a href="?view=payments">Payments</a>
    <a href="logout.php">Logout</a>
</div>

<div class="content">
<?php
$view = $_GET['view'] ?? 'tours';

if ($view == 'tours') {
    echo "<h2>Available Tours</h2>";
    $result = $conn->query("SELECT * FROM tours");
    while ($row = $result->fetch_assoc()) {
        $imagePath = "uploads/".$row['image'];
        if (!file_exists($imagePath) || empty($row['image'])) {
            $imagePath = "uploads/default.jpg"; // fallback image
        }
        echo "<div class='tour-card'>
                <img src='".$imagePath."' alt='".htmlspecialchars($row['tour_name'])."'>
                <div>
                    <h3>".htmlspecialchars($row['tour_name'])."</h3>
                    <p>Location: ".htmlspecialchars($row['location'])."</p>
                    <p>Price: KES ".htmlspecialchars($row['price'])."</p>
                    <form method='POST'>
                        <input type='hidden' name='tour_id' value='".$row['tour_id']."'>
                        <button type='submit' name='book_tour'>Book Tour</button>
                    </form>
                </div>
              </div>";
    }

} elseif ($view == 'bookings') {
    echo "<h2>My Bookings</h2>";
    $stmt = $conn->prepare("SELECT b.booking_id, t.tour_name, t.location, t.price, t.image 
                            FROM bookings b 
                            JOIN tours t ON b.tour_id = t.tour_id 
                            WHERE b.user_id=?");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $imagePath = "uploads/".$row['image'];
        if (!file_exists($imagePath) || empty($row['image'])) {
            $imagePath = "uploads/default.jpg";
        }
        echo "<div class='tour-card'>
                <img src='".$imagePath."' alt='".htmlspecialchars($row['tour_name'])."'>
                <div>
                    <h3>".htmlspecialchars($row['tour_name'])."</h3>
                    <p>Location: ".htmlspecialchars($row['location'])."</p>
                    <p>Price: KES ".htmlspecialchars($row['price'])."</p>
                    <form method='POST'>
                        <input type='hidden' name='booking_id' value='".$row['booking_id']."'>
                        <button type='submit' name='delete_booking'>Cancel Booking</button>
                    </form>
                </div>
              </div>";
    }
    $stmt->close();

} elseif ($view == 'payments') {
    echo "<h2>Payments</h2>";

    $stmt = $conn->prepare("
        SELECT b.booking_id, t.tour_name, t.location, t.price, p.status, p.payment_date
        FROM bookings b
        JOIN tours t ON b.tour_id = t.tour_id
        LEFT JOIN payments p ON b.booking_id = p.booking_id
        WHERE b.user_id=?
    ");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $status = $row['status'] ?? 'Pending';
        $paymentDate = $row['payment_date'] ?? '-';
        
        echo "<div class='tour-card'>
                <div>
                    <h3>".htmlspecialchars($row['tour_name'])."</h3>
                    <p>Location: ".htmlspecialchars($row['location'])."</p>
                    <p>Amount: KES ".htmlspecialchars($row['price'])."</p>
                    <p>Status: <strong>$status</strong></p>
                    <p>Payment Date: $paymentDate</p>";
        
        // Show Pay Now button if pending
       if ($status == 'Pending') {
    echo "<form method='POST' action='choose_payment_method.php'>
            <input type='hidden' name='booking_id' value='".$row['booking_id']."'>
            <input type='hidden' name='amount' value='".$row['price']."'>
            <button type='submit'>Pay Now</button>
          </form>";
}

        echo "</div></div>";
    }
    $stmt->close();
}

?>
</div>

</body>
</html>
