<?php

define('DBserver', 'localhost');
define('DBuser', 'root');
define('DBpass', '123456');
define('DBname', 'image_uploader');
 
/* Attempt to connect to MySQL database */
$link = mysqli_connect(DBserver, DBuser, DBpass, DBname);
 
// Check connection
if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
?>