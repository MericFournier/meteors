
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
         .delete {
            display:none;
         }
      </style>

   </head>
   <body>

      <?php foreach($meteors->data as $meteor): ?>
      <div class="div <?=  isset($meteor[2]) ? '' : 'delete'  ?> <?=  isset($meteor[8]) ? '' : 'delete'  ?>" data-country=" " data-date="<?=  $meteor[0] ?>" data-lat ="<?= $meteor[3]?>" data-lon ="<?= $meteor[5]?>" data-energie="<?= $meteor[2] ?>" data-velocity="<?=  $meteor[8] ?>" data-masse="<?=  (pow($meteor[8]*1000,2))/$meteor[2] ?>" data-magn ="" >

      </div>
      <?php endforeach ?>

   </body>

   <!--
<script>



var div = document.querySelectorAll('.div')
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

--> 


   <script>

      var country;
      var population;
      var div = document.querySelectorAll('.div')
      console.log(div)
      for ( var i = 0; i<div.length; i++ ) {
         div[i].onclick = function() {
            var that = this
            var lat = this.dataset.lat
            var lon = this.dataset.lon
            console.log(lat,lon);
            fetch( 'http://ws.geonames.org/countryCodeJSON?lat='+lat+'&lng='+lon+'&username=meric' )
            .then( ( response ) =>
            {
               return response.json()
            } )
            .then( ( result ) =>
            {
               return fetch( 'http://api.population.io:80/1.0/population/'+result.countryName+'/2016-12-31/' )
            } )
            .then( ( response ) =>
            {
               return response.json()
            } )
            .then( ( result ) =>
            {
               console.log(result.total_population.population);
               that.innerHTML = result.total_population.population;
            } ) 

            }






      }
      

   </script>
</html>

