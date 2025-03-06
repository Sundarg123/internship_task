<?php
require '../vendor/autoload.php';  // Load MongoDB PHP Library

use MongoDB\Client;

global $mongoClient; // Make sure $mongoClient is accessible globally
$mongoClient = new Client("mongodb://localhost:27017"); // Connect to MongoDB
?>

