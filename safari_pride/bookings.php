<?php
include("db.php");
if (isset($_SESSION['pay_booking'])) {
    $booking_id = $_SESSION['pay_booking'];
    $amount = $_SESSION['pay_amount'];

    echo "
    <div class='mpesa-box' style='padding:20px;border:1px solid #ccc;margin-top:20px;'>
        <h3>Pay with M-Pesa</h3>
        <p>Booking ID: $booking_id</p>
        <p>Amount: KES $amount</p>

        <form method='POST' action='mpesa_stk.php'>
            <label>Phone Number:</label><br>
            <input type='text' name='phone' placeholder='07xxxxxxxx' required><br><br>

            <input type='hidden' name='amount' value='$amount'>
            <input type='hidden' name='booking_id' value='$booking_id'>

            <button type='submit' name='mpesa_pay'>Pay with M-Pesa</button>
        </form>
    </div>
    ";
    unset($_SESSION['pay_booking']);
unset($_SESSION['pay_amount']);

}



?>
<h2>All Bookings</h2>
<table>
    <tr>
        <th>Booking ID</th>
        <th>User ID</th>
        <th>Tour</th>
        <th>Date</th>
    </tr>
    
    <?php
    $query = mysqli_query($conn, "SELECT * FROM bookings");
    while($row = mysqli_fetch_assoc($query)){
        echo "<tr>
                <td>".$row['booking_id']."</td>
                <td>".$row['user_id']."</td>
                <td>".$row['tour_name']."</td>
                <td>".$row['tour_date']."</td>
              </tr>";
    }
    ?>

</table>
</body>
</html>       