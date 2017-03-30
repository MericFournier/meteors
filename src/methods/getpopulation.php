<?php 

$country = isset($_GET['country']) ? $_GET['country'] : '';
$url  = 'https://restcountries.eu/rest/v2/name/'.$country;
$result = file_get_contents($url);
echo ($result);
