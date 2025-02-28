<?php
require '../vendor/autoload.php';  // Load MongoDB PHP Library

use MongoDB\Client;

// Connect to MongoDB
$mongoClient = new Client("mongodb://localhost:27017");
$database = $mongoClient->myprofile;  // Change this to your database name
$profilesCollection = $database->users;  // Change this to your collection name
if ($profilesCollection) {
    $insertData = [
        'name' => 'John Doe' // Change this to the actual name value
    ];

    // Insert into collection
    $insertResult = $profilesCollection->insertOne($insertData);
    echo "MongoDB connected successfully!";
} else {
    echo "Failed to connect to MongoDB!";
}
?>
