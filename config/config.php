<?php
ob_start(); //Turns on output buffering
$servername = "localhost";
$database = "u253840030_letsuniteDB";
$username = "u253840030_neemasheby";
$password = "*cNstzv7Y";

//It helps us to store the values entered in the input in a variable called session variable. This is done so that the values can be easily corrected if there are errors.
session_start();

$timezone = date_default_timezone_set("Europe/London");

// Create connection
$conn = mysqli_connect($servername, $username, $password, $database);
// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
echo "Connected successfully";
mysqli_close($conn);

?>
