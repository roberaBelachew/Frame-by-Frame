<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$ip = $_SERVER['REMOTE_ADDR'];

$token = "aed762c54dcf43";
$api_url = "https://ipinfo.io/{$ip}/json?token={$token}";

$response = file_get_contents($api_url);
$data = json_decode($response, true);

$loc = $data["loc"] ?? "";
$coords = explode(",", $loc);
$lat = $coords[0] ?? "0";
$lon = $coords[1] ?? "0";
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Your Location</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css">
    <style>
        #map { height: 500px; width: 80%; margin: 20px auto; border: 2px solid #333; }
        body { font-family: Arial; text-align:center; }
    </style>
</head>
<body>

<h2>Your IP Location on the Map</h2>
<p>Your IP: <strong><?php echo $ip; ?></strong></p>

<div id="map"></div>

<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script>
var lat = <?php echo $lat ?>;
var lon = <?php echo $lon ?>;
var ip = "<?php echo $ip ?>";

var map = L.map('map').setView([lat, lon], 10);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 18,
}).addTo(map);

var marker = L.marker([lat, lon]).addTo(map)
    .bindPopup("Your IP: " + ip)
    .openPopup();
</script>

</body>
</html>

