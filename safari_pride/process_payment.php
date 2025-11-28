<?php
session_start();
$booking_id = $_POST['booking_id'];
$amount = $_POST['amount'];
$method = $_POST['payment_method'];

// IF USER SELECTED MPESA → GO TO MPESA PAYMENT PAGE
if ($method == "Mpesa") {
    header("Location: process_payment.php?booking_id=$booking_id&amount=$amount");
    exit();
}

// IF USER SELECTED CARD → CARD PAGE
if ($method == "Card") {
    header("Location: process_payment.php?booking_id=$booking_id&amount=$amount");
    exit();
}

// IF BANK TRANSFER
if ($method == "Bank Transfer") {
    header("Location: process_payment.php?booking_id=$booking_id&amount=$amount");
    exit();
}

// IF CASH → YOU JUST MARK IT AS CASH PAYMENT
if ($method == "Cash") {
    header("Location: process_payment.php?booking_id=$booking_id&amount=$amount");
    exit();
}
?>
