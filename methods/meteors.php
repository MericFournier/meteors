<?php 
$url  = 'https://ssd-api.jpl.nasa.gov/fireball.api';

$meteors = file_get_contents($url);

// Convert to object
$meteors = json_decode($meteors);
$fatal = 0;
$error = 0;
$result = $meteors->data;
$final = [];

foreach ( $result as $_result ) {
   $error = 0;
   if ( !isset ($_result[0])) {
      $error += 1;
   }
   if ( !isset ($_result[2])) {
      $error += 1;
   }
   if ( !isset ($_result[3])) {
      $error += 1;
   }
   
   if ( !isset ($_result[5])) {
      $error += 1;
   }
   if ( !isset ($_result[7])) {
      $error += 1;
   }
   if ( !isset ($_result[8])) {
      $error += 1;
   }
   if ( $error < 1) {

      array_push($final, $_result);
      
   }
   
}

echo json_encode($final);


