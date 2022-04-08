<?php

//Database credentials 
define('DB_HOST', "127.0.0.1");
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'rms'); 

$link = mysqli_connect(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check connection
if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
} 

?>