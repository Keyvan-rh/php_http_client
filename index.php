<?php
// Use the Kubernetes Service DNS name instead of localhost
$serviceName = "php-http-server-git";
$namespace = "a-demo"; // Change this if you use a custom namespace
$port = 8080; // The port defined in your Service's 'port' field

$serverUrl = "http://$serviceName.$namespace.svc.cluster.local:$port/server.php?delay=3";

echo "Sending request to Kubernetes Service: $serverUrl \n";

$ch = curl_init($serverUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 15);

$response = curl_exec($ch);

if (curl_errno($ch)) {
    echo 'Error: ' . curl_error($ch);
} else {
    echo "Server says: " . $response . "\n";
}

curl_close($ch);
