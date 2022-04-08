<?php

require_once "config.php";
 
//Variables to store the details when creating an account
$username = $password = $confirm_password = $name = $business_name = $email = $image= "";
$username_err = $password_err = $confirm_password_err = $name_err = $business_name_err = $email_err = $image_err = ""; 

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    //Validate username. This involves checking if the username is already
    //used by another person in the database. If so, reject that one
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter a username.";
    } elseif(!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["username"]))){
        $username_err = "Username can only contain letters, numbers, and underscores.";
    } else{
        // Select statement to get the ID of a user
        $sql = "SELECT id FROM users WHERE username = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            $param_username = trim($_POST["username"]);
            
            // Execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $username_err = "This username is already taken.";
                } else{
                    $username = trim($_POST["username"]);
                }
            } else{
                echo "Something went wrong. Please try again later.";
            }

            mysqli_stmt_close($stmt);
        }
    }
    
    // START OF FIELD VALIDATION

    //Validate image
    if(!empty($_FILES['image']['name'])){
        $image_err = "Please enter an image.";     
    } else{
        $image = trim($_POST["image"]);
    }


    // Validate password
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter a password.";     
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "Password must have atleast 6 characters.";
    } else{
        $password = trim($_POST["password"]);
    }

    // Validate name
    if(empty(trim($_POST["name"]))){
        $name_err = "Please enter your name.";     
    } elseif(strlen(trim($_POST["name"])) < 6){
        $name_err = "Name must have atleast 6 characters.";
    } else{
        $name = trim($_POST["name"]);
    }

    // Validate email
    if(empty(trim($_POST["email"]))){
        $email_err = "Please enter your email.";     
    } elseif(strlen(trim($_POST["email"])) < 6){
        $email_err = "Email is invalid.";
    } else{
        $email = trim($_POST["email"]);
    }

    // Validate business name
    if(empty(trim($_POST["business_name"]))){
        $business_name_err = "Please enter you business name.";     
    } elseif(strlen(trim($_POST["business_name"])) < 6){
        $business_name_err = "Business name must have atleast 6 characters.";
    } else{
        $business_name = trim($_POST["business_name"]);
    }
    
    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Please confirm password.";     
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }
    //END OF FIELD VALIFATION


    //Process the image
    $fileName = basename($_FILES["image"]["name"]);
    $fileType = pathinfo($fileName, PATHINFO_EXTENSION);

    //Setting list of certain file formats to be allowed 
    $allowableFileFormats = array("jpg", "jpeg", "png");

    if (in_array($fileType, $allowableFileFormats)) {
        $image = $_FILES['image']['tmp_name']; 
        $imgContent = addslashes(file_get_contents($image)); 
    }


    // Check for errors before inserting into the database
    if(empty($username_err) && empty($password_err) && empty($confirm_password_err) && empty($name_err) && empty($business_name_err) && empty($email_err)){
        
        $sql = "INSERT INTO users (username, password, name, business_name, email, profile_picture) VALUES (?, ?, ?, ?, ?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "ssssss", $param_username, $param_password, $param_name, $param_business_name, $param_email, $param_image);
            
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            $param_name = $name;
            $param_business_name = $business_name;
            $param_email = $email;
            $param_image = $imgContent;
            
            if(mysqli_stmt_execute($stmt)){
                // Redirect to login page if successful for user to login
                header("location: login.php");
            } else{
                echo "Something went wrong. Please try again later.";
            }

            mysqli_stmt_close($stmt);
        }
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
    <title>Register Page</title>

    <link rel="stylesheet" href="/rms/css/main.css" type="text/css"> 
</head>
<body>
    <!--- Register form --->
    <div class="container">
        <form id="form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
            <h1>Registration</h1>
            <br>
            <div class="input-control">
                <label for="name">Full Name</label>
                <input id="name" name="name" type="text">
                <div class="error"></div>
            </div>
            <div class="input-control">
                <label for="email">Business name</label>
                <input id="business_name" name="business_name" type="text">
                <div class="error"></div>
            </div>
            <div class="input-control">
                <label for="username">Username</label>
                <input id="username" name="username" type="text">
                <div class="error"></div>
            </div>
            <div class="input-control">
                <label for="email">Email</label>
                <input id="email" name="email" type="text">
                <div class="error"></div>
            </div>
            <div class="input-control">
                <label for="password">Password</label>
                <input id="password"name="password" type="password">
                <div class="error"></div>
            </div>
            <div class="input-control">
                <label for="confrim_password">Confirm Password</label>
                <input id="confirm_password"name="confirm_password" type="password">
                <div class="error"></div>
            </div>
            <div class="input-control">
                <label for="image">Choose your picture</label>
                <input id="picture"name="image" type="file">
                <div class="error"></div>
            </div>
            <br>
            <button type="submit" name="submit">Sign Up</button>
            <br>
            <label for="link"><a href="login.php">Already have an account! Login here</a>.</p></label>
        </form>
    </div>
</body>
</html>