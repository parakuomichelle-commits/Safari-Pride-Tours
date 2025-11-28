<?php
session_start();

$booking_id = $_POST['booking_id'];
$amount = $_POST['amount'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Select Payment Method</title>
</head>
<body>

<h2>Select Payment Method</h2>

<p>Booking ID: <?php echo $booking_id; ?></p>
<p>Amount: KES <?php echo $amount; ?></p>

<form method="POST" action="process_payment.php">
    <label><strong>Choose Payment Method:</strong></label><br><br>

    <select name="payment_method" required>
        <option value="">-- Select Method --</option>
        <option value="Mpesa">M-Pesa</option>
        <option value="Card">Credit/Debit Card</option>
        <option value="Bank Transfer">Bank Transfer</option>
        <option value="Cash">Cash</option>
    </select>
    <br><br>

    <input type="hidden" name="booking_id" value="<?php echo $booking_id; ?>">
    <input type="hidden" name="amount" value="<?php echo $amount; ?>">

    <button type="submit">Continue</button>
</form>

</body>
</html>
