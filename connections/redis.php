<?php
require '../vendor/autoload.php';

use Predis\Client as redis;

try{
    $redis = new Redis([
        'host' => "localhost",
        'port' => 6379,
        'scheme' => "tcp"
    ]);
    $redis->ping();
} catch (Exception $e) {
    die(json_encode(["success" => false, "message" => "Redis connection failed: " . $e->getMessage()]));
}
?>
