<?php
include '../vendor/autoload.php';
include '../connections/db.php';
include '../connections/redis.php'; 
include '../connections/mongodb.php'; // Ensure MongoDB connection

header('Content-Type: application/json');  // Ensure proper JSON response
header('Access-Control-Allow-Origin: *'); // Allow cross-origin AJAX calls
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
// header('Access-Control-Allow-Headers: Content-Type');
// Function to get profile data
function getProfileData($email){
    global $mongoClient;
    $profileCollection =$mongoClient->myprofile->users;
    return $profileCollection->findOne(['email' => $email]);
}
// Function to update profile data
function updateProfileData($email, $profileData) {
    global $mongoClient;
$profileCollection = $mongoClient->selectDatabase("myprofile")->selectCollection("users");


    try {
        $updateResult = $profileCollection->updateOne(
            ['email' => $email],
            ['$set' => $profileData],
            ['upsert' => true] // Insert if not found
        );

        return $updateResult->getModifiedCount() > 0 || $updateResult->getUpsertedCount() > 0;
        
    } catch (Exception $e) {
        error_log("MongoDB Update Error: " . $e->getMessage());
        return false;
    }
}

// Get POST data safely
$token = $_POST['token'] ?? '';
$action = $_POST['action'] ?? '';
$data = $_POST['data'] ?? [];

// Validate session ID and get user ID
$email = $redis->get($token);

if (!$email) {
    echo json_encode(["success" => false, "message" => "Please login again"]);
    exit;
}

if($action === 'fetch'){
    $profile = getProfileData($email);
    if($profile){
        echo json_encode(["success" => true, "profile" => $profile]);
    }
    else{
        echo json_encode(["success" => false, "message" => "Please update your profile"]);
    }
}

if ($action == 'update') {
    $response = updateProfileData($email, $data)
    ? ["success" => true, "message" => "Profile updated successfully"]
    : ["success" => false, "message" => "No changes made or update failed"];
    echo json_encode($response);
    exit;
}
echo json_encode(["success" => false, "message" => "Invalid action"]);
exit;
?>
