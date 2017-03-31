<?php 

// get data from api

$curl = curl_init();
 curl_setopt($curl, CURLOPT_URL, 'https://ssd-api.jpl.nasa.gov/fireball.api');
 curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
 $meteors = curl_exec($curl);
 curl_close($curl);  

// Convert to object
$meteors = json_decode($meteors);

// check missing data 

foreach ($meteors->data as $key => $_meteor) {
   if (!isset($_meteor[8]) || !isset($_meteor[0]) || !isset($_meteor[1]) || !isset($_meteor[2]) || !isset($_meteor[3]) || !isset($_meteor[4]) || !isset($_meteor[5]) || !isset($_meteor[6]) || !isset($_meteor[7]))
   {
      unset($meteors->data[$key]);
   }
}

$meteors = json_encode($meteors);




