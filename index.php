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
      <title>Document</title>
      <script src="http://www.webglearth.com/v2/api.js"></script>

   <style>
      
      .div { 
         width:100px;
         height:30px;
         border-radius:20px;
         border:1px solid black;
         margin:20px;
         display:inline-block;
      }
      </style>
      
   </head>
   <body>

      <?php foreach($meteors->data as $meteor): ?>
      <div class="div" data-date="<?=  $meteor[0] ?>" data-country =" <?= $meteor[3].' '.$meteor[4].' '.$meteor[5].' '.$meteor[6] ?> ">
         
      </div>
      <?php endforeach ?>

   </body>
   <!-- Obtenir la population dans le pays cliqué --> 
   <script>

      var population;

      var div = document.querySelectorAll('.test')
      console.log(div)
      for ( var i = 0; i<div.length; i++ ) {
         div[i].onclick = function() {
            var that = this
            var data = this.dataset.country
            fetch( 'http://api.population.io:80/1.0/population/'+data+'/2016-12-31/' )
               .then( ( response ) =>
                     {
               return response.json()
            } )
               .then( ( result ) =>
                     {
               that.innerHTML = result.total_population.population

            } ) 
         }
      }

   </script>
</html>

