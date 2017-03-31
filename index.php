<?php

// include meteor filter 
include 'filter_meteor.php';

// cache handle    

$time = microtime(TRUE); 
require 'class/class.cache.php';
define('ROOT', dirname(__FILE__));
$cache = new cache(ROOT.'/cache', 60*24); // reload cache every 24hours

// check if cache exist, if not create it

if (!$data_fire = $cache->read('data_fire.json'))
{
   $data_fire = $meteors;
   $cache->write('data_fire.json', $data_fire); 
}

// get from cache data
$data_saved = file_get_contents('cache/data_fire.json');

//Convert to object
$data_saved = json_decode($data_saved);


// create table for coord and other data
$coord = [];
$data_attr = [];

// prepare coord for the earthmap

if (!empty($data_saved))
{
   foreach ($data_saved->data as $key => $meteor)
   {
      $data_attr[$key] = array('date' => $meteor[0], 'energy' => $meteor[1], 'impact' => $meteor[2], 'vel' => ($meteor[8]*1000), 'mass' => 0);
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
      <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
      <!-- Favicon -->
      <link rel="apple-touch-icon" sizes="180x180" href="src/images/favicons/apple-touch-icon.png">
      <link rel="icon" type="image/png" href="src/images/favicons/favicon-32x32.png" sizes="32x32">
      <link rel="icon" type="image/png" href="src/images/favicons/favicon-16x16.png" sizes="16x16">
      <link rel="manifest" href="src/images/favicons/manifest.json">
      <link rel="mask-icon" href="src/images/favicons/safari-pinned-tab.svg" color="#5bbad5">
      <meta name="theme-color" content="#ffffff">
   </head>

   <body>
      <div class="mobile_version">
         <h1>COMING SOON   </h1>
      </div>
      <div class="container">
         <!-- SideBar -->
         <div class="side_bar elements_main">
            <!-- logo -->
            <div class="logo"><img src="src/images/logo.png" alt="logo"></div>
            <!-- END logo -->
            <div class="sideContent">
               <div class="line"></div>
               <!-- SideBar content -->
               <div class="data-range closed">
                  <h2>Dégâts potentiels :</h2>
                  <p class="data-set"><i class="fa fa-wheelchair" aria-hidden="true"></i> <span  id="victimes"></span> bléssés</p>
                  <p class="data-set"><i class="fa fa-heartbeat" aria-hidden="true"></i> <span  id="morts"></span> morts</p>
                  <div class="try">
                     <div class="impact_data" id="rangeImpact" value="">
                        <div class="fill_data"></div>
                     </div>
                  </div>
                  <h2>Masse : <span class="data-set" id="masseValue"></span> kg</h2><div class="try"><input class="setter" id="rangeMasse" type="range" value="" max="100" min="0" step="1"  ></div><h2>Velocité : <span class="data-set" id="veloValue"></span> m/s</h2><div class="try"><input id="rangeVelo" class="setter" type="range" value="" max="100" min="0" step="1"  ></div>
                  <h2>Force d'impact : <span class="data-set data_actual" id="impactValue"></span> e15 J</h2>
                  <h2>Rayon d'impacte : <span class="data-set" id="areaValue"></span> km2</h2>

                  <div class="line"></div>
                  <h3>Et pour comparer :</h3>
                  <p>En 2016, <span id="name_cause"></span> a fait <span id="value_cause"></span> morts !</p>
                  <p>Votre météorite a fait <span id="percent"></span>% de ce nombre !</p>
               </div>
               <!-- END SideBar content -->
            </div>
         </div>
         <!-- END SideBar -->
         <!-- Earth -->
         <div class="space elements_main">
            <div id="earth_div"></div>
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
         <!-- END Earth -->
      </div>
      </div>
   </body>

<!-- Call JS style sheet -->
<script src="http://www.webglearth.com/v2/api.js"></script>
<script src="src/js/main.js"></script>
<script>

   var comparetab = [{'name':'l aéronautique','value':750},{'name':'la drogue','value':190000},{'name':'la circulation routière','value':1300000},{'name':'la boisson','value':3300000},{'name':'la cigarette','value':6000000},{'name':'le cancer','value':15000000}]

   var density;
   var play = function()
   {
      var newmasse = range.elements.mass.value;
      var newvel = range.elements.vel.value;
      document.querySelector('#masseValue').innerHTML = newmasse;
      document.querySelector('#veloValue').innerHTML = newvel;
      document.querySelector('#rangeMasse').value = newmasse;
      document.querySelector('#rangeVelo').innerHTML = newvel;
      change_range_meteor = document.querySelector('.locate_second_ring');
      cin = 0.5*newmasse;
      cin = cin*(newvel*newvel);
      var newimpact = cin * energy; 
      newimpact = newimpact/4184000000000;
      var impactprompt = newimpact/1000000000000000;
      document.querySelector('#impactValue').innerHTML = Math.round(newimpact);
      vector = 0.178; // source : #
      var area = newimpact*vector;
      area = Math.log10(area);
      area = area*225000;
      if ( area < 1) {
         area = 0;
      }
      change_range_meteor.style.width = (area*0.2)/1000 + 'px';
      change_range_meteor.style.height = (area*0.2)/1000 + 'px';
      if (isNaN(density))
      {
         density = 1;
      }
      var hurt = area * density;
      var death = 0.3*hurt; 
      var hurted = 0.7*hurt; 
      range.data_view.area.innerHTML= Math.round(area);
      range.data_view.morts.innerHTML = Math.round(death);
      range.data_view.blesses.innerHTML = Math.round(hurted);

      for ( var i = 0; i<comparetab.length; i++) {
         if (    Math.round(death) < Math.round(comparetab[i].value) ) {
            var ratio = Math.round(death)/comparetab[i].value;
            ratio = ratio*100;
            document.querySelector('#name_cause').innerHTML = comparetab[i].name;
            document.querySelector('#value_cause').innerHTML = comparetab[i].value;
            document.querySelector('#percent').innerHTML = Math.round(ratio);
            return;
         }
      }
   }
   var setter = document.querySelectorAll('.setter');
   for ( var i = 0; i<setter.length; i++)
   {
      setter[i].addEventListener('change',function()
                                 {
         play();
      });
   }
   for ( var i = 0; i<setter.length; i++)
   {
      setter[i].addEventListener('input',function()
                                 {
         play();
      });
   }
   var your_latitude = '',
       your_longitude = '',
       coord_discovered = false,
       options = { zoom: 6.0, center: [47.19537,8.524404], zooming : false},
       earth = new WE.map('earth_div', options);

   function initialize_locate()
   {
      // application texture on earth
      WE.tileLayer('http://data.webglearth.com/natural-earth-color/{z}/{x}/{y}.jpg', {
         tileSize: 256,
         bounds: [[-85, -180], [85, 180]],
         minZoom: 0,
         maxZoom: 16,
         attribution: 'WebGLEarth example',
         tms: true,
      }).addTo(earth);

      // get variables from php for coord and other data from meteors
      var coord = <?= $coord ?>,
          _coord;

      var data_attr = <?= $data_attr ?>;

      for (var _coord in coord)
      {
         energy = data_attr[_coord].energy; 
         vel = data_attr[_coord].vel;
         impact = data_attr[_coord].impact; 

         energy = energy*Math.pow(10,10);
         convertor = impact*4184000000000;
         mass = (convertor-energy)*2;
         mass = mass/(vel*vel);
         mass = Math.ceil(mass);
         impact = 0;

         // Applications coord and calculs to markers

         let marker = WE.marker([coord[_coord].lat, coord[_coord].lon], 'src/images/test.png').addTo(earth);
         marker.element.ourlocation = marker.element.querySelector('div');
         marker.element.ourlocation.classList.add('meteor_location');
         marker.element.ourlocation.innerHTML = "<div class='locate_circle'><div class='locate_second'></div></div>";
         marker.element.ourlocation.setAttribute('data-date', data_attr[_coord].date);
         marker.element.ourlocation.setAttribute('data-energy', data_attr[_coord].energy);
         marker.element.ourlocation.setAttribute('data-vel', data_attr[_coord].vel);
         marker.element.ourlocation.setAttribute('data-lat', coord[_coord].lat);
         marker.element.ourlocation.setAttribute('data-lon', coord[_coord].lon);
         marker.element.ourlocation.setAttribute('data-mass', mass);
      }
      earth.setView([51.505, -80], 2);
   }

   // call function earth and markers
   initialize_locate();
   get_locate_point();

   // The geocalisation taken care by the navigator ? 
   if(navigator.geolocation)
   {
      navigator.geolocation.getCurrentPosition(showLocation, errorHandler);
      function showLocation(position)
      {
         your_latitude = position.coords.latitude; // set your latitude 
         your_longitude = position.coords.longitude; // set your longitude
         coord_discovered = true; // if you are located, place a new marker which represent your position on the map
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
         // NEED HTTPS TO WORK THIS IS A WAITING SOLUTION
         your_marker = WE.marker([48.8513472, 2.4205869], 'src/images/location.png').addTo(earth);
         your_marker.element.ourlocation = your_marker.element.querySelector('div');
         your_marker.element.ourlocation.classList.add('current_location');
         your_marker.bindPopup("<b>Vous êtes ici ! </b>", {maxWidth: 100, closeButton: true});
         your_marker.openPopup();
         earth.panTo([48.8513472, 2.4205869], 1);
         // Affichage d'un message d'erreur plus "user friendly" pour l'utilisateur.
         console.log('NEED HTTPS TO WORK, THIS IS A WAITING SOLUTION.');
      }
   }
   else
   {
      alert('Votre navigateur ne prend malheureusement pas en charge la géolocalisation.');
   }

   var point_is_clicked = false,
       mass = '',
       vel = '',
       impact = '';

   //handling marker and actions on
   function get_locate_point()
   {
      locate = {};
      locate.content = document.querySelector('#earth_div');
      locate.containers = locate.content.querySelectorAll('.we-pm-icon');

      for (let i = 0; i < locate.containers.length; i++)
      {
         if (locate.containers[i].classList.contains('meteor_location'))
         {
            locate.containers[i].addEventListener('click', function()
                                                  {

               var width_impact = 20 + impact,
                   height_impact = 20 + impact;
               var new_type_point = this.querySelector('.locate_circle');
               var new_point_ring = this.querySelector('.locate_second');  

               if (point_is_clicked)
               {
                  locate.second_ring = locate.content.querySelector('.locate_second_ring');
                  locate.layer_ring = locate.content.querySelector('.layer_ring');

                  if (locate.second_ring == null)
                  {
                     new_point_ring.classList.add('locate_second_ring');
                     new_point_ring.style.opacity = '1';
                     new_point_ring.style.width = 20+'px';
                     new_point_ring.style.height = 20 + 'px';
                  }
                  else
                  {
                     locate.layer_ring.classList.remove('layer_ring');
                     locate.second_ring.style.opacity = '0';
                     locate.second_ring.classList.remove('locate_second_ring');
                     this.classList.add('layer_ring');
                     new_point_ring.classList.add('locate_second_ring');
                     new_point_ring.style.opacity = '1';
                     new_point_ring.style.width = 20+'px';
                     new_point_ring.style.height = 20+'px';
                  }
               }
               else
               {
                  this.classList.add('layer_ring');
                  new_point_ring.classList.add('locate_second_ring');
                  new_point_ring.style.opacity = '1';
                  new_point_ring.style.width = 20+'px';
                  new_point_ring.style.height = 20+'px';
                  point_is_clicked = true;
               }
               var casualities =  function(density)
               {
                  if (isNaN(density)){
                     density = 1;
                  }

                  var area = Math.log(impact);
                  area = Math.log10(area);
                  area = area*225000;
                  if ( area < 1) {
                     area = 0;
                  }
                  var hurt = area * density;
                  var death = Math.ceil(0.3*hurt); 
                  var hurted = Math.ceil(0.7*hurt); 
                  range.data_view.area.innerHTML= Math.ceil(area);
                  range.data_view.morts.innerHTML = Math.ceil(death);
                  range.data_view.blesses.innerHTML = Math.ceil(hurted);
               }
               var actualImpact = function (impact)
               {
                  impactratio =  0;
                  range.elements.impact.fill.style.width = impactratio+'%'; 
               }
               var calculImpact = function()
               {
                  cin = 0.5*mass;
                  cin = cin*(vel*vel);
                  impact = cin + energy; 
                  impact = impact/4184000000000;
                  actualImpact(impact); 
                  return impact;
               }
               var actualView = function ()
               {
                  range.data_view.mass.innerHTML = mass;
                  range.data_view.vel.innerHTML = vel;
                  range.data_view.impact.innerHTML = Math.ceil(impact);
               }
               var actualLimits = function ()
               {
                  //max
                  range.elements.mass.setAttribute("max",(mass*10));
                  range.elements.vel.setAttribute("max",700000);
                  // min 
                  range.elements.mass.setAttribute("min",Math.ceil((0)));
                  range.elements.vel.setAttribute("min",Math.ceil((0)));
               }
               var actualCursor = function()
               {
                  range.elements.mass.setAttribute("value",mass);
                  range.elements.vel.setAttribute("value",vel);
                  range.elements.impact.setAttribute("value",calculImpact());
               }

               range.container.classList.remove('closed');

               vel = this.getAttribute('data-vel');
               impact = calculImpact();
               mass = this.getAttribute('data-mass');
               energy = this.getAttribute('data-energy');
               lat = this.getAttribute('data-lat');
               lon = this.getAttribute('data-lon');
               date = this.getAttribute('data-date');

               actualView(); 
               actualLimits();
               actualCursor(); 

               range.elements.mass.addEventListener('input',function(){
                  actualCursor();
                  actualImpact();
                  actualLimits();
                  actualView();
               }); 
               range.elements.vel.addEventListener('input',function(){
                  actualCursor();
                  actualImpact();
                  actualLimits();
                  actualView();
               }); 
               range.elements.impact.addEventListener('input',function(){
                  actualCursor();
                  actualImpact();
                  actualLimits();
                  actualView();
               });

               // obtenir population / density 
               var resultData = function(population,taille) {
                  density = population/taille;
                  casualities(density);
               }

               var getCountry = function(lat,lon) {
                  var xhr = new XMLHttpRequest();
                  xhr.onreadystatechange = function()
                  {
                     if( xhr.readyState === XMLHttpRequest.DONE )
                     {
                        if(xhr.status === 200)
                        {
                           var result = JSON.parse( xhr.responseText );
                           if ( result.countryName ) {
                              var country = result.countryName;

                              getData(country);
                           }
                           else {
                              resultData (0,0);

                           } 
                        }
                        else
                        {
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
                           ( 'success' ); 
                           var population = result[0].population;
                           var taille = result[0].area;
                           var density = population/taille;
                           resultData(population,taille);
                        }
                        else
                        {
                        }
                     }
                  };
                  xhr.open( 'GET', 'src/methods/getpopulation.php?country='+country, true );
                  xhr.send();
               }

               modify_value();    
            });
         }
      }
   }

</script>


<script>
   impact_energy = document.querySelectorAll('.meteor_location');
   var key; 

   max_energy = 0;

   for (key in impact_energy) {
      if(impact_energy[key]>max_energy) {
         max_energy = impact_energy[key]; 
      }
   }
</script>
<script src="src/js/scripttuto.js"></script>
<script src="src/js/scriptstars.js"></script>
<script src="src/js/dat.gui.min.js"></script>
<!-- END Call JS style sheet -->
</html>



