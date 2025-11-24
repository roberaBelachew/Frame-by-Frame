<?php
// Show PHP errors during development
ini_set('display_errors', 1);
error_reporting(E_ALL);

// --------------------
// Get client IP
// --------------------
if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ip = trim(explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0]);
} else {
    $ip = $_SERVER['REMOTE_ADDR'];
}

// --------------------
// IPinfo token
// --------------------
$token = "3bfa4f2176b2f4";

// Default (Constructor University Bremen) in case of failure
$loc = "53.1686,8.6512";
$city = "";
$country = "";

// --------------------
// Query IPinfo API
// --------------------
$url = "https://ipinfo.io/{$ip}/json?token={$token}";
$response = @file_get_contents($url);

if ($response !== false) {
    $data = json_decode($response, true);

    if (!empty($data["loc"])) {
        $loc = $data["loc"];
    }
}

list($lat, $lon) = explode(",", $loc);

// --------------------
// LOG INTO FILE
// --------------------
$logDir = __DIR__ . "/logs";
if (!is_dir($logDir)) {
    mkdir($logDir, 0777, true);
}

$logFile = $logDir . "/ip_logs.txt";
$timestamp = date("Y-m-d H:i:s");

$logLine = "$timestamp | IP=$ip | LAT=$lat | LON=$lon\n";
file_put_contents($logFile, $logLine, FILE_APPEND);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>IP Location Viewer</title>

    <!-- Leaflet CSS -->
    <link rel="stylesheet"
          href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
          crossorigin=""/>

    <style>
        body {
            font-family: Arial;
            text-align: center;
        }
        #map {
            height: 500px;
            width: 80%;
            margin: 20px auto;
            border: 2px solid black;
            border-radius: 10px;
        }
    </style>
</head>
<body>

<h1>Your Location Based on IP</h1>

<!-- BACK TO PROJECT HOME -->
<p>
  <a href="https://clabsql.constructor.university/~rbelachew/index.php">
     Back to Project Home
  </a>
</p>

<p><b>Detected IP:</b> <?= htmlspecialchars($ip) ?></p>

<div id="map"></div>

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        crossorigin="">
</script>

<script>
// Pass PHP values to JS
const lat = <?= $lat ?>;
const lon = <?= $lon ?>;
const ip  = "<?= htmlspecialchars($ip) ?>";

// Initialize map
const map = L.map('map').setView([lat, lon], 12);

// Load map tiles
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '&copy; OpenStreetMap contributors'
}).addTo(map);

// Add marker
L.marker([lat, lon]).addTo(map)
    .bindPopup("Your IP: " + ip)
    .openPopup();
</script>

</body>
</html>

