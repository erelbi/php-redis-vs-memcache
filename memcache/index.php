<?php

$memcached = new Memcached();
$memcached->addServer('memcached', 11211);


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
    
 
    $memcached->set($key, $data, 3600);
    
   
    $stored_data = $memcached->get($key);
    $response[] = [
        'operation' => $i,
        'written_data' => $data,
        'read_data' => $stored_data,
        'memcache_result_code' => $memcached->getResultCode()
    ];
}


header('Content-Type: application/json');
echo json_encode($response, JSON_PRETTY_PRINT);