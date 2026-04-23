<?php
// Use the Kubernetes Service DNS name instead of localhost
$serviceName = "php-http-server-git";
$namespace = "a-demo"; // Change this if you use a custom namespace
$port = 8080; // The port defined in your Service's 'port' field
$duration    = 300; // Total run time (5 minutes)

$serverUrl = "http://$serviceName.$namespace.svc.cluster.local:$port/index.php";
$startTime = time();
$endTime   = $startTime + $duration;

echo "Starting constant requests for 5 minutes...\n";
echo "Target: $serverUrl\n";

while (time() < $endTime) {
    $iterationStart = microtime(true);
    $ch = curl_init($serverUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 15);
    
    $response = curl_exec($ch);
    $error = curl_error($ch);
    curl_close($ch);

    $iterationEnd = microtime(true);
    $elapsed = round($iterationEnd - $iterationStart, 2);

    if ($error) {
        echo "[ERROR] " . date("H:i:s") . " - $error\n";
        // Wait 1 second before retrying on error to avoid rapid-fire failure loops
        sleep(1);
    } else {
        echo "[OK] " . date("H:i:s") . " - Server replied: '$response' (Took {$elapsed}s)\n";
    }

    // Optional: Tiny pause to stay friendly to the OS scheduler
    usleep(10000); // 10ms
}

echo "Finished 5-minute run.\n";
?>
