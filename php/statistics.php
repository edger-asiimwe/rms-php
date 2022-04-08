<?php

session_start();

require_once "config.php";

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

$sql = "SELECT * FROM orders ORDER BY dop DESC";


if($stmt = mysqli_prepare($link, $sql)){
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
}

mysqli_close($link);

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Retail Management System - Statistics</title>

        <link rel="stylesheet" href="/rms/css/main.css" type="text/css">
    </head>
    <body>
        <!--- Navigation Bar --->
        <nav>
            <ul class="topnav">
                <li><a class="active" href="">Statistics</a></li>
                <li><a href="order.php">Place Order</a></li>
                <li><a href="index.php">Home Dashboard</a></li>
                <li class="right"><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
        <!--- Navigation Bar --->

        <div style="overflow-x:auto;">
            <table id="statistics">
                <thead>
                    <tr>
                    <th scope="col">Order ID</th>
                    <th scope="col">Customer Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Address</th>
                    <th scope="col">Shipping</th>
                    <th scope="col">Item Name</th>
                    <th scope="col">Customer Note</th>
                    <th scope="col">Quantity</th>
                    <th scope="col">Price</th>
                    <th scope="col">Date of Order</th>
                    </tr>
                </thead>
                <tbody>
                    <?php //Displaying the rows from the result set
                        while($row = mysqli_fetch_assoc($result)){
                            echo "<tr>";
                            echo "<td>".$row['order_id']."</td>";
                            echo "<td>".$row['customer_name']."</td>";
                            echo "<td>".$row['customer_email']."</td>";
                            echo "<td>".$row['address']."</td>";
                            echo "<td>".$row['shipping_method']."</td>";
                            echo "<td>".$row['item_name']."</td>";
                            echo "<td>".$row['customer_note']."</td>";
                            echo "<td>".$row['quantity']."</td>";
                            echo "<td>".$row['unit_price']."</td>";
                            echo "<td>".$row['dop']."</td>"; 
                            echo "</tr>";
                        }
                    ?>
                </tbody>
            </table>
        </div>
    </body>
</html>

