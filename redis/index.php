<?php


$redis = new Redis();
$redis->connect('redis', 6379);


session_start();


$request_count = isset($_SESSION['request_count']) ? $_SESSION['request_count'] : 0;
$request_count++;
$_SESSION['request_count'] = $request_count;


$response = [];
for ($i = 1; $i <= 3; $i++) {
    $key = "request_{$request_count}_operation_{$i}";
    $data = [
        'timestamp' => time(),
        'operation_number' => $i,
        'request_number' => $request_count,
        'session_id' => session_id()
    ];
    
   
    $redis->setex($key, 3600, json_encode($data));
    
   
    $stored_data = json_decode($redis->get($key), true);
    $response[] = [
        'operation' => $i,
        'written_data' => $data,
        'read_data' => $stored_data,
        'redis_status' => $redis->getLastError() ?: 'success'
    ];
}


header('Content-Type: application/json');
echo json_encode($response, JSON_PRETTY_PRINT);

$redis->close();