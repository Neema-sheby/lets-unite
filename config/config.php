<?php
ob_start(); //Turns on output buffering

//It helps us to store the values entered in the input in a variable called session variable. This is done so that the values can be easily corrected if there are errors.
session_start();
$timezone = date_default_timezone_set("Europe/London");


$con = mysqli_connect("localhost","root","", "social network database");

if(mysqli_connect_errno()){
  echo "Failed to connect :".mysqli_connect_errno();
}
?>