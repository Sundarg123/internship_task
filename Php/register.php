<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

include '../connections/db.php';
require '../vendor/autoload.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $hash = password_hash($_POST["email"], PASSWORD_BCRYPT); // Hash password
    $username = $_POST["userName"];
    $email = $_POST["email"];
    $password = password_hash($_POST["password"], PASSWORD_BCRYPT); // Hash password

    if ($connection->connect_error) {
        die("Connection failed: " . $connection->connect_error);
      }

    // Insert new user
    $stmt = $connection->prepare("INSERT INTO users (hash, userName, email, password) VALUES (?,?, ?, ?)");
    $stmt->bind_param("ssss", $hash, $username, $email, $password); 
    $stmt->execute();
 
    if ($stmt) {
        echo json_encode(["status" => "success", "data" => "Registration Successful"]);
    } else {
        echo json_encode(["status" => "error", "message" => $conn->error]);
    }

    $stmt->close();
    $connection->close();
    }

?>
