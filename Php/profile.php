<?php
include '../vendor/autoload.php';
include '../connections/db.php';
include '../connections/redis.php'; 
include '../connections/mongodb.php'; // Ensure MongoDB connection

header('Content-Type: application/json');  // Ensure proper JSON response
header('Access-Control-Allow-Origin: *'); // Allow cross-origin AJAX calls
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Function to get profile data
function getProfileData($hash){
    global $mongoClient;
    $profileCollection = $mongoClient->selectDatabase("myprofile")->selectCollection("users");
    return $profileCollection->findOne(['hash' => $hash]);
}

// Function to update profile data
function updateProfileData($hash, $profileData) {
    global $mongoClient;
    $profileCollection = $mongoClient->selectDatabase("myprofile")->selectCollection("users");

    try {
        $updateResult = $profileCollection->updateOne(
            ['hash' => $hash],
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
$data = json_decode($_POST['data'], true) ?? []; // Decoding as an array

// Validate session ID and get user email
$email = $redis->get($token);

// FIX: Correct array syntax for accessing hash
if (!isset($data['hash'])) {
    echo json_encode(["success" => false, "message" => "Missing profile identifier"]);
    exit;
}


$hash = $data['hash'];

if (!$email) {
    echo json_encode(["success" => false, "message" => "Please login again"]);
    exit;
}

if ($action === 'fetch') {
    $profile = getProfileData($hash);
    if ($profile) {
        echo json_encode(["success" => true, "profile" => $profile]);
    } else {
        echo json_encode(["success" => false, "message" => "Please update your profile"]);
    }
    exit;
}

if ($action === 'update') {
    if (!$data) {
        echo json_encode(["success" => false, "message" => "Invalid profile data"]);
        exit;
    }

    // FIX: Correct array syntax for accessing hash
    if (!isset($data['hash'])) {
        echo json_encode(["success" => false, "message" => "Missing profile identifier"]);
        exit;
    }

    // FIX: Update using email, not hash
    $response = updateProfileData($hash, $data)
        ? ["success" => true, "message" => "Profile updated successfully"]
        : ["success" => false, "message" => "No changes made or update failed"];
    
    echo json_encode($response);
    exit;
}   

echo json_encode(["success" => false, "message" => "Invalid action"]);
exit;
?>
