

<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <title>Space Impact</title>
      <link rel="stylesheet" href="style.css">
      <link href="https://fonts.googleapis.com/css?family=Economica|News+Cycle" rel="stylesheet">
   </head>
   <body>
      <div class="container">


         <div class="side_bar elements_main">
            <div class="logo">LOGO</div>
            <div class="line"></div>
            <div class="data-range">
               <h2>Masse : <span class="data-set" id="masseValue">56%</span></h2>
               <div class="try">
                  <input id="rangeMasse" type="range" value="50" max="100" min="0" step="1">
               </div>
               <h2>Velocité : <span class="data-set" id="veloValue">56%</span></h2>
               <div class="try">
                  <input id="rangeVelo" type="range" value="50" max="100" min="0" step="1">
               </div>
               <h2>Force d'impact : <span class="data-set" id="impactValue">56%</span></h2> 
               <!--La force d'impact est lié à la vélocité, si l'un bouge l'autre aussi-->
               <div class="try">
                  <input id="rangeImpact" type="range" value="50" max="100" min="0" step="1">
               </div>
            </div>
            <h2>Données :</h2>
            <div class="data_selected">
               <div class="victim">
                  <img class="picto" src="img/victim.png" alt="victime">
               </div>
               <div class="victim_number" ><span id="victim_count"></span></div>
               <div>
                  <img class="picto" src="img/location.png" alt="localisation">
               </div>
               <div class="date">42°N 22E</div>
            </div>
            <div class="button_compare">Comparer</div>
            <div class="line"></div>


         </div>
         <div class="space elements_main">
            
               <?php foreach($meteors->data as $meteor): ?>
            <div class="div <?=  $meteor[8] > 0 ? '' : 'delete'  ?> <?=  isset($meteor[2]) ? '' : 'delete'  ?> <?=  isset($meteor[8]) ? '' : 'delete'  ?>" data-country="" data-date="<?=  $meteor[0] ?>" data-lat ="<?= $meteor[3]?>" data-lon ="<?= $meteor[5]?>" data-impact="<?= $meteor[2] ?>" data-velo="<?=  $meteor[8] > 0 ? $meteor[8] : ''  ?>" data-masse="<?=  (pow($meteor[8]*1000,2))/$meteor[2] ?>" data-magn ="" ></div>

              
               <?php endforeach ?>
               
               <div class="div" data-country="" data-date="date" data-lat ="10" data-lon ="20" data-energie="29" data-velo="20" data-masse="49" data-magn ="35" >
            </div>
         </div>
      </div>
      <script src="script.js"></script>
   </body>
</html>