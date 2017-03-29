
<?php

//$url  = 'https://ssd-api.jpl.nasa.gov/fireball.api';
//
//$meteors = file_get_contents($url);
//
//// Convert to object
//$meteors = json_decode($meteors);

//partie de Johann

$url  = 'https://ssd-api.jpl.nasa.gov/fireball.api?limit=100';

$forecast = file_get_contents($url);

/*debut du cache*/
$cache = 'cache/data.json';
$expire = time() -3600 ; // valable une heure

if(file_exists($cache) && filemtime($cache) > $expire)
{
  readfile($cache);
}
else
{
  ob_start(); // ouverture du tampon

  $getdata = $forecast;
//  echo $getdata;

  $temp = ob_get_contents(); // copie du contenu du tampon dans une chaîne
  ob_end_clean(); // effacement du contenu du tampon et arrêt de son fonctionnement

  file_put_contents($cache, $temp) ; // on écrit la chaîne précédemment récupérée ($page) dans un fichier ($cache) 
//  echo $temp ; // on affiche notre page :D 
}

//Convert to object

$data_saved = file_get_contents('cache/data.json');
$data_saved = json_decode($data_saved);

$coord = [];

if (!empty($data_saved))
{
  foreach ($data_saved->data as $key => $meteor)
  {
    if ($meteor[4] === 'N' && $meteor[6] === 'E')
    {
      $coord[$key] = array('lat' => $meteor[3], 'lon' => $meteor[5]);
    }
    else if ($meteor[4] === 'S' && $meteor[6] === 'W')
    {
      $coord[$key] = array('lat' => -$meteor[3], 'lon' => -$meteor[5]);
    }
    else if ($meteor[4] === 'N' && $meteor[6] === 'W')
    {
      $coord[$key] = array('lat' => $meteor[3], 'lon' => -$meteor[5]);
    }
    else if ($meteor[4] === 'S' && $meteor[6] === 'E')
    {
      $coord[$key] = array('lat' => -$meteor[3], 'lon' => $meteor[5]);
    }
  }
  $coord = json_encode($coord);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
     <meta charset="UTF-8">
     <title>StarsImpact</title>
     
     <!--      le script de l'api doit se charger avant le dom-->
     <script src="http://www.webglearth.com/v2/api.js"></script>
     <script src="src/scripts/scriptAPI.js"></script>
     
     <link rel="stylesheet" href="src/styles/stylePlanet.css">
     <link rel="stylesheet" href="src/styles/styleMeric.css">
     <link rel="stylesheet" href="src/styles/styleStars.css">
     
</head>
<body>

    <canvas id="main_canvas"></canvas>
    <div id="earth_div">
        <div class="satellite">
            <div class="moon"><img src="src/images/moon.png" alt="Lune"></div>
            <div class="night"></div>
        </div>
    </div>
    
    
    <script src="src/scripts/scriptMeric.js"></script>
    <script src="src/scripts/dat.gui.min.js"></script>
    <script src="src/scripts/scriptStars.js"></script>
<!--    <script src="http://www.webglearth.com/v2/api.js"></script>-->
    <script src="src/scripts/scriptPlanet.js"></script>
    
</body>

</html>

