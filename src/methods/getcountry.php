<?php 

// get country from position (lat, lon)
$lat = isset($_GET['lat']) ? $_GET['lat'] : '';
$lon = isset($_GET['lon']) ? $_GET['lon'] : '';
$url  = 'http://ws.geonames.org/countryCodeJSON?lat='.$lat.'&lng='.$lon.'&username=meric';
$result = file_get_contents($url);
echo ($result);