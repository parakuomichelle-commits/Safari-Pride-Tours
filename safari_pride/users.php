<?php
include("db.php");
?>

<h2>Registered Users</h2>
<table>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Email</th>
    </tr>
    <?php
    $query = mysqli_query($conn, "SELECT * FROM users");
    while($row = mysqli_fetch_assoc($query)){
        echo "<tr>
                <td>".$row['id']."</td>
                <td>".$row['name']."</td>
                <td>".$row['email']."</td>
              </tr>";
    }
    ?>
</table>
