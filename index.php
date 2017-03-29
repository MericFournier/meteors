
<?php

$url  = 'https://ssd-api.jpl.nasa.gov/fireball.api';

$meteors = file_get_contents($url);

// Convert to object
$meteors = json_decode($meteors);

?>
<!DOCTYPE html>
<html lang="en">
<head>
     <meta charset="UTF-8">
     <title>StarsImpact</title>
     
     <!--      le script de l'api doit se charger avant le dom-->
     <script src="http://www.webglearth.com/v2/api.js"></script>
     
     <link rel="stylesheet" href="src/styles/styleMeric.css">
     <link rel="stylesheet" href="src/styles/styleStars.css">
</head>
<body>

    <canvas id="main_canvas"></canvas>
    
    <?php foreach($meteors->data as $meteor): ?>
    <div class="div <?=  isset($meteor[2]) ? '' : 'delete'  ?> <?=  isset($meteor[8]) ? '' : 'delete'  ?>" data-country=" " data-date="<?=  $meteor[0] ?>" data-lat ="<?= $meteor[3]?>" data-lon ="<?= $meteor[5]?>" data-energie="<?= $meteor[2] ?>" data-velocity="<?=  $meteor[8] ?>" data-masse="<?=  (pow($meteor[8]*1000,2))/$meteor[2] ?>" data-magn ="" >

    </div>
    <?php endforeach ?>

    <script src="src/scripts/scriptMeric.js"></script>
    <script src="src/scripts/dat.gui.min.js"></script>
    <script src="src/scripts/scriptStars.js"></script>
</body>

</html>

