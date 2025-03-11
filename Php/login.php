
<?php
header("Access-Control-Allow-Origin: *"); // Allow requests from any origin
header("Access-Control-Allow-Methods: POST, GET, OPTIONS"); // Allow these HTTP methods
header("Access-Control-Allow-Headers: Content-Type, Authorization"); // Allow custom headers
header("Content-Type: application/json"); // Set response type

require '../vendor/autoload.php';
include '../connections/db.php';
include '../connections/redis.php';// Redis connection (Ensure you have this file to initialize Redis)


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = ($_POST["email"]);
    $password = ($_POST["password"]);


    // Prepare statement to fetch user from MySQL
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $token = bin2hex(random_bytes(16));
            $redis->setex($token,3600,$email);
            echo json_encode(['success'=> true,'message'=> 'Login Successfull', 'token' => $token, 'hash' => $user['hash']]);
        } 
        else {
            echo json_encode(['success' => false, 'message' => 'Incorrect Password']);
        }
    }
    else {
        echo json_encode(['success' => false, 'message' => 'User not found']);
    }
}
?>
