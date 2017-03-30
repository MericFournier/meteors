<?php

$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, 'https://ssd-api.jpl.nasa.gov/fireball.api?limit=100');
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
$forecast = curl_exec($curl);
curl_close($curl);

// cache handle    

$time = microtime(TRUE); 
require 'class/class.cache.php';
define('ROOT', dirname(__FILE__));
$cache = new cache(ROOT.'/cache', 10);

if (!$data_fire = $cache->read('data_fire.json'))
{
   $data_fire = $forecast;
   $cache->write('data_fire.json', $data_fire); 
}

//Convert to object

$data_saved = file_get_contents('cache/data_fire.json');

$data_saved = json_decode($data_saved);

echo '<pre>';
print_r($data_saved);
echo '</pre>';

$coord = [];
$data_attr = [];

if (!empty($data_saved))
{
   foreach ($data_saved->data as $key => $meteor)
   {
      $data_attr[$key] = array('date' => $meteor[0], 'energy' => $meteor[1], 'impact' => $meteor[2], 'vel' => $meteor[8], 'mass' => $meteor[2]*$meteor[8]);
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
   $data_attr = json_encode($data_attr);
}
?>

<!DOCTYPE HTML>
<html>
   <head>
      <title>WebGL Earth API: Markers</title>
      <link rel="stylesheet" type="text/css" href="src/styles/style.css">
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
               <div class="try">
                  <input id="rangeImpact" type="range" value="50" max="100" min="0" step="1">
               </div>
            </div>
            <h2>Données :</h2>
            <div class="data_selected">
               <div class="victim">
                  <img class="picto" src="src/images/victim.png" alt="victime">
               </div>
               <div class="victim_number" ><span id="victim_count"></span></div>
               <div>
                  <img class="picto" src="src/images/location.png" alt="localisation">
               </div>
               <div class="date">42°N 22E</div>
            </div>
            <div class="line"></div>
         </div>
         <div class="space elements_main">
            <div id="earth_div">
                <div class="satellite">
                    <div class="moon"><img src="src/images/moon.png" alt="Lune"></div>
<!--                    <div class="night"></div>-->
                </div>
            </div>
         </div>
         
         <canvas id="main_canvas"></canvas>
         
          <div class="tuto">
              <div class="perso">
                  <img src="src/images/astronaut.png" alt="Astronaute">
              </div>
              <div class="messagesTuto">
                  <p></p>
                  <div class="understood">Passer à la suite</div>
                  <div class="triangleTuto"><img src="src/images/parole.png" alt="Triangle de discution"></div>
              </div>
          </div>
      </div>
       
   </body>
   <script src="http://www.webglearth.com/v2/api.js"></script>
   <script src="src/js/main.js"></script>
   <script src="src/js/dat.gui.min.js"></script>
   <script src="src/js/scriptStars.js"></script>
   <script src="src/js/scriptTuto.js"></script>
   <!-- <script src="script2.js"></script> -->
   <script>

      var your_latitude = '',
          your_longitude = '',
          coord_discovered = false,
          options = { zoom: 6.0, center: [47.19537,8.524404] },
          earth = new WE.map('earth_div', options);

      function initialize_locate()
      {
         // texture
         WE.tileLayer('http://data.webglearth.com/natural-earth-color/{z}/{x}/{y}.jpg', {
            tileSize: 256,
            bounds: [[-85, -180], [85, 180]],
            minZoom: 0,
            maxZoom: 16,
            attribution: 'WebGLEarth example',
            tms: true
         }).addTo(earth);
         // https://cartodb-basemaps-{s}.global.ssl.fastly.net/light_all/{z}/{x}/{y}.png 
         //http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png

         var coord = <?= $coord ?>;
         var data_attr = <?= $data_attr ?>;

         for (let i = 0; i < coord.length; i++)
         {
            let marker = WE.marker([coord[i].lat, coord[i].lon], 'src/images/test.png').addTo(earth);
            marker.element.ourlocation = marker.element.querySelector('div');
            marker.element.ourlocation.classList.add('meteor_location');
            marker.element.ourlocation.setAttribute('data-date', data_attr[i].date);
            marker.element.ourlocation.setAttribute('data-energy', data_attr[i].energy);
            marker.element.ourlocation.setAttribute('data-impact', data_attr[i].impact);
            marker.element.ourlocation.setAttribute('data-vel', data_attr[i].vel);
            marker.element.ourlocation.setAttribute('data-mass', data_attr[i].mass);
            marker.element.ourlocation.setAttribute('data-lat', coord[i].lat);
            marker.element.ourlocation.setAttribute('data-lon', coord[i].lon);
         }

         earth.setView([51.505, -80], 1);
      }

      initialize_locate();
      get_locate_point();

      // The geocalisation taken care by the navigator ? 
      if(navigator.geolocation)
      {
         navigator.geolocation.getCurrentPosition(showLocation, errorHandler);
         function showLocation(position)
         {
            your_latitude = position.coords.latitude;
            your_longitude = position.coords.longitude;
            console.log(your_latitude, your_longitude);
            coord_discovered = true;
            if(coord_discovered)
            {
               if (coord_discovered)
               {
                  your_marker = WE.marker([your_latitude, your_longitude], 'src/images/location.png').addTo(earth);
                  your_marker.element.ourlocation = your_marker.element.querySelector('div');
                  your_marker.element.ourlocation.classList.add('current_location');
                  your_marker.bindPopup("<b>Vous êtes ici ! </b>", {maxWidth: 100, closeButton: true});
                  your_marker.openPopup();
                  earth.panTo([your_latitude, your_longitude], 1);
               }
            }
         }
         function errorHandler(error)
         {
            // Affichage d'un message d'erreur plus "user friendly" pour l'utilisateur.
            alert('Une erreur est survenue durant la géolocalisation. Veuillez réessayer plus tard.');
         }
      }
      else
      {
         alert('Votre navigateur ne prend malheureusement pas en charge la géolocalisation.');
      }

      var point_is_clicked = false,
          width_impact = 80,
          height_impact = 80,
          mass = '',
          vel = '',
          impact = '';

      function get_locate_point()
      {
         locate = {};
         locate.content = document.querySelector('#earth_div');
         locate.containers = locate.content.querySelectorAll('.we-pm-icon');

         for (let i = 0; i < locate.containers.length; i++)
         {
            if (locate.containers[i].classList.contains('meteor_location'))
            {
               locate.containers[i].innerHTML = "<div class='locate_circle'><div class='locate_second'></div></div>";
               locate.containers[i].addEventListener('click', function()
                                                     {
                  var new_type_point = this.querySelector('.locate_circle');
                  var new_point_ring = this.querySelector('.locate_second');  

                  if (point_is_clicked)
                  {
                     locate.second_ring = locate.content.querySelector('.locate_second_ring');
                     if (locate.second_ring == null)
                     {
                        new_point_ring.classList.add('locate_second_ring');
                        new_point_ring.style.opacity = '1';
                        new_point_ring.style.width = width_impact+'px';
                        new_point_ring.style.height = height_impact+'px';
                     }
                     else
                     {
                        locate.second_ring.style.opacity = '0';
                        locate.second_ring.classList.remove('locate_second_ring');
                        new_point_ring.classList.add('locate_second_ring');
                        new_point_ring.style.opacity = '1';
                        new_point_ring.style.width = width_impact+'px';
                        new_point_ring.style.height = height_impact+'px';
                     }
                  }
                  else
                  {
                     new_point_ring.classList.add('locate_second_ring');
                     new_point_ring.style.opacity = '1';
                     new_point_ring.style.width = width_impact+'px';
                     new_point_ring.style.height = height_impact+'px';
                     point_is_clicked = true;
                  }

                  mass = this.getAttribute('data-mass');
                  vel = this.getAttribute('data-vel');
                  impact = this.getAttribute('data-impact');
                  lat = this.getAttribute('data-lat');
                  lon = this.getAttribute('data-lon');
                  date = this.getAttribute('data-date');
                  
                  
                  var getCountry = function(lat,lon) {
                     var xhr = new XMLHttpRequest();
                     xhr.onreadystatechange = function()
                     {
                        if( xhr.readyState === XMLHttpRequest.DONE )
                        {
                           if(xhr.status === 200)
                           {
                              var result = JSON.parse( xhr.responseText );
                              console.log( 'success' ); 
                              console.log(result.countryName);
                              var country = result.countryName;
                              getData(country); 
                           }
                           else
                           {
                              console.log( 'error' );
                           }
                        }
                     };
                     xhr.open( 'GET', 'src/methods/getcountry.php?lat='+lat+'&lon='+lon, true );
                     xhr.send();
                  }
                  
                  getCountry(lat,lon);

                  // obtenir la population 
                  var getData = function (country) {
                     var xhr = new XMLHttpRequest();
                     xhr.onreadystatechange = function()
                     {
                        if( xhr.readyState === XMLHttpRequest.DONE )
                        {
                           if(xhr.status === 200)
                           {
                              var result = JSON.parse( xhr.responseText );
                              console.log( 'success' ); 
                              var population = result[0].population;
                              var taille = result[0].area;
                              var density = population/taille;
                              console.log(density);
                           }
                           else
                           {
                              console.log( 'error' );
                           }
                        }
                     };
                     xhr.open( 'GET', 'src/methods/getPopulation.php?country='+country, true );
                     xhr.send();
                  }


                  console.log(lat+' '+lon+' '+date);

                  modify_value();    
               });
            }
         }
      }

   </script>
</html>