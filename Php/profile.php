<?php
include '../vendor/autoload.php';
include '../connections/db.php';
include '../connections/redis.php'; 

header('Content-Type: application/json');

    $token = $_POST["token"];
    $action = $_POST["action"];



?>
<?php
require_once '../db/connect_mongo.php';
require_once 'redis_session.php';

// Function to update profile data
function updateProfileData($userID, $profileData) {
    global $mongo;
    $profileCollection = $mongo->mydb->profiles;
    
    $updateResult = $profileCollection->updateOne(
        ['userID' => $userID],
        ['$set' => $profileData],
        ['upsert' => true] // Insert if not found
    );

    return $updateResult->getModifiedCount() > 0;
}

$sessionID = $_POST['sessionID'];
$action = $_POST['action'];
$data = $_POST['data'] ?? [];
$response = [];

// Validate session ID and get user ID
$userID = getSession($sessionID);

if (!$userID) {
    $response['success'] = false;
    $response['message'] = 'Please login again';
} elseif ($action === 'update') {
    if (updateProfileData($userID, $data)) {
        $response['success'] = true;
        $response['message'] = 'Profile updated successfully';
    } else {
        $response['success'] = false;
        $response['message'] = 'No changes made or update failed';
    }
} else {
    $response['success'] = false;
    $response['message'] = 'Invalid action';
}

echo json_encode($response);
?>
