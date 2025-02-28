<?php
$host = "localhost";
$userName = "root";
$password = "";
$dbname = "guvi";
$port = 3307;

$connection = new mysqli($host, $userName, $password, $dbname, $port);

if($connection->connect_error){
    die('Connection Failed:' .$connection->connect_error);
}
?>
