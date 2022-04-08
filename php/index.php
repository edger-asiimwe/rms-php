<?php

session_start();
 
require_once "config.php";

$name = $business_name = $email = $image_data = '';

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

$sql = "SELECT * FROM users WHERE username = '$_SESSION[username]'";

if($result = mysqli_query($link, $sql)){
    if(mysqli_num_rows($result) > 0){
        while($row = mysqli_fetch_array($result)){
            $name = $row['name'];
            $business_name = $row['business_name'];
            $email = $row['email'];
            $image_data = $row['profile_picture'];
        }
    }
}


mysqli_close($link);

?>
 

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Retail Management System - Home Page</title>

    <link rel="stylesheet" href="/rms/css/main.css" type="text/css">
</head> 
<body>
    <!--- Navigation Bar --->
    <nav>
        <ul class="topnav">
            <li><a class="active" href="">Dashboard</a></li>
            <li><a href="order.php">Place Order</a></li>
            <li><a href="statistics.php">View Statistics</a></li>
            <li><a href="index.php">Home Page</a></li>
            <li class="right"><a href="logout.php">Logout</a></li>
        </ul>
    </nav>
    <!--- Navigation Bar --->

    <!--- Personal Dashboard --->
    <div class="card">
        <img src="data:image/jpeg;base64,'.base64_encode($image->load()) .'" alt="Profile Picture" style="width:100%">
        <h1><?php echo $name; ?></h1>
        <p class="title"><?php echo $business_name; ?></p>
        <p><?php echo $email; ?></p>
    </div>
</body>
</html>