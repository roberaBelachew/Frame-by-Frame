<?php

$log_dir = __DIR__ . '/logs';
if (!is_dir($log_dir)) {
    mkdir($log_dir, 0755, true);
}

$access_file = $log_dir . '/access.log';
$error_file  = $log_dir . '/error.log';


$ip      = $_SERVER['REMOTE_ADDR'] ?? '-';
$time    = date('d/M/Y:H:i:s O'); // Apache common log time format
$method  = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$uri     = $_SERVER['REQUEST_URI'] ?? '/';
$protocol = $_SERVER['SERVER_PROTOCOL'] ?? 'HTTP/1.1';
$status  = http_response_code(); // note: may be 200 at this point
$size    = '-';
$ref     = $_SERVER['HTTP_REFERER'] ?? '-';
$agent   = addslashes($_SERVER['HTTP_USER_AGENT'] ?? '-');


$line = sprintf("%s - - [%s] \"%s %s %s\" %s %s \"%s\" \"%s\"\n",
    $ip, $time, $method, $uri, $protocol, $status, $size, $ref, $agent);


file_put_contents($access_file, $line, FILE_APPEND | LOCK_EX);


?>

