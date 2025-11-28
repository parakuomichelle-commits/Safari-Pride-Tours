<?php
session_start();
require 'db.php';


if(!isset($_SESSION['user_id'])){
header("Location: login.php");
exit();
}


if(isset($_POST['book'])){
$user_id = $_SESSION['user_id'];
$tour_name = $_POST['tour_name'];
$date = $_POST['date'];
$people = $_POST['people'];


$query = "INSERT INTO bookings (user_id, tour_name, date, people) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param("issi", $user_id, $tour_name, $date, $people);


if($stmt->execute()){
header("Location: customer_dashboard.php?booked=1");
exit();
}
}
?>
