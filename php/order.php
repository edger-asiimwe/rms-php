<?php

require_once "config.php";

session_start();

//Check if user is logged in
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

//Initialise the variables to store the different form values
$customer_name = $customer_email = $address = $shipping_method = $item_name = $customer_note = "";
$customer_name_err = $customer_email_err = $address_err = $shipping_method_err = $item_name_err = $customer_note_err = "";
$quantity = $unit_price = 0;

if($_SERVER["REQUEST_METHOD"] == "POST"){

    //START OF FORM VALIDATION

    //Validate the customer name
    if(empty(trim($_POST["customer_name"]))){
        $customer_name_err = "Please enter a customer name.";
    } else{
        $customer_name = trim($_POST["customer_name"]);
    }

    //Validate the customer email
    if(empty(trim($_POST["customer_email"]))){
        $customer_email_err = "Please enter a customer email.";
    } else{
        $customer_email = trim($_POST["customer_email"]);
    }

    //Validate the address
    if(empty(trim($_POST["address"]))){
        $address_err = "Please enter an address.";
    } else{
        $address = trim($_POST["address"]);
    }

    //Validate the shipping method
    if(empty(trim($_POST["shipping_method"]))){
        $shipping_method_err = "Please enter a shipping method.";
    } else{
        $shipping_method = trim($_POST["shipping_method"]);
    }

    //Validate the item name
    if(empty(trim($_POST["item_name"]))){
        $item_name_err = "Please enter an item name.";
    } else{
        $item_name = trim($_POST["item_name"]);
    }

    //Validate the customer note
    if(empty(trim($_POST["customer_note"]))){
        $customer_note_err = "Please enter a customer note.";
    } else{
        $customer_note = trim($_POST["customer_note"]);
    }

    //Validate the quantity
    if(empty(trim($_POST["quantity"]))){
        $quantity_err = "Please enter a quantity.";
    }elseif($_POST["quantity"] < 0){
        $quantity_err = "Please enter a valid quantity above 0.";
    } else{
        $quantity = trim($_POST["quantity"]);
    }

    //Validate the unit price
    if(empty(trim($_POST["unit_price"]))){
        $unit_price_err = "Please enter a unit price.";
    }elseif($_POST["unit_price"] < 0){
        $unit_price_err = "Please enter a valid unit price above 0.";
    } else{
        $unit_price = trim($_POST["unit_price"]);
    } 
    
    //END OF FORM VALIDATION

    //Check if there are any errors before submitting to the database
    if(empty($customer_name_err) && empty($customer_email_err) && empty($address_err) && empty($shipping_method_err) && empty($item_name_err) && empty($customer_note_err) && empty($quantity_err) && empty($unit_price_err)){
        
        $sql = "INSERT INTO orders (customer_name, customer_email, address, shipping_method, item_name, customer_note, quantity, unit_price) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        
        if($stmt = mysqli_prepare($link, $sql)){
           
            mysqli_stmt_bind_param($stmt, "ssssssss", $param_customer_name, $param_customer_email, $param_address, $param_shipping_method, $param_item_name, $param_customer_note, $param_quantity, $param_unit_price);
            
            $param_customer_name = $customer_name;
            $param_customer_email = $customer_email;
            $param_address = $address;
            $param_shipping_method = $shipping_method;
            $param_item_name = $item_name;
            $param_customer_note = $customer_note;
            $param_quantity = $quantity;
            $param_unit_price = $unit_price;
            
            if(mysqli_stmt_execute($stmt)){
                //Redirect to the statistics page if an order is successful
                header("location: statistics.php");
            } else{
                echo "Something went wrong. Please try again later.";
            }
        }
        
        mysqli_stmt_close($stmt);
    }

    mysqli_close($link);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Retail Management System - Order Form</title>

    <link rel="stylesheet" href="/rms/css/main.css" type="text/css">
</head>
<body>
      <!--- Navigation Bar --->
        <nav>
            <ul class="topnav">
                <li><a class="active" href="">Order</a></li>
                <li><a href="statistics.php">View Statistics</a></li>
                <li><a href="index.php">Home Dashboard</a></li>
                <li class="right"><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
        <!--- Navigation Bar --->

        <!--- Order form --->
    <div class="container">
        <form id="form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
            <h1>Make order</h1>
            <br>
            <div class="input-control">
                <label for="customer_name">Customer Name</label>
                <input id="customer_name" name="customer_name" type="text">
                <div class="error"></div>
            </div>
            <div class="input-control">
                <label for="customer_email">Customer Email</label>
                <input id="customer_email" name="customer_email" type="email">
                <div class="error"></div>
            </div>
            <div class="input-control">
                <label for="address">Address</label>
                <input id="address" name="address" type="text">
                <div class="error"></div>
            </div>
            <div class="input-control">
                <label for="shipping_method">Shipping Method</label>
                <input id="shipping_method" name="shipping_method" type="text">
                <div class="error"></div>
            </div>
            <div class="input-control">
                <label for="item_name">Item Name</label>
                <input id="item_name"name="item_name" type="text">
                <div class="error"></div>
            </div>
            <div class="input-control">
                <label for="customer_note">Customer Note</label>
                <input id="customer_note"name="customer_note" type="text">
                <div class="error"></div>
            </div>
            <div class="input-control">
                <label for="quantity">Quantity</label>
                <input id="quantity"name="quantity" type="number">
                <div class="error"></div>
            </div>
            <div class="input-control">
                <label for="unit_price">Choose your picture</label>
                <input id="unit_price"name="unit_price" type="number">
                <div class="error"></div>
            </div>
            <br>
            <button type="submit">Place Order</button>
        </form>
    </div>
    <!--- Order form --->
</body>
</html>

