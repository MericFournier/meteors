
console.log('Yo');


// reccupérer les éléments du dom 

// range inputs 
var range = {}; 
range.container = document.querySelector('.data-range');
range.elements = {};
range.elements.masse = range.container.querySelector('#rangeMasse');
range.elements.velo = range.container.querySelector('#rangeVelo');
range.elements.impact = range.container.querySelector('#rangeImpact');
// texte des inputs 
range.data_view = {};
range.data_view.masse = range.container.querySelector('#masseValue');
range.data_view.velo = range.container.querySelector('#veloValue');
range.data_view.impact = range.container.querySelector('#impactValue');
console.log(range.elements);
// éléments espace 
var space = {};
space.container = document.querySelector('.space');
space.elements = {}; 
space.elements.meteors = space.container.querySelectorAll('.meteor');
console.log("yo");
//Data elements compare
data_select = {}; 
data_select.container = document.querySelector('.data_selected');
data_select.elements = {};
data_select.elements.population = data_select.container.querySelector('#victim_count');

// actualiser les valeurs des textes des inputs 
range.elements.masse.addEventListener('input',function(){
   range.data_view.masse.innerHTML = this.value;
}); 
range.elements.velo.addEventListener('input',function(){
   range.data_view.velo.innerHTML = this.value;
}); 
range.elements.impact.addEventListener('input',function(){
   range.data_view.impact.innerHTML = this.value;
});

// charger les météorites 
var xhr = new XMLHttpRequest();
xhr.onreadystatechange = function()
{
   if( xhr.readyState === XMLHttpRequest.DONE )
   {
      if(xhr.status === 200)
      {
         var result = JSON.parse( xhr.responseText );
         console.log( 'success' );
         // création des météorites 
         for ( var i = 0; i<result.length; i++) {
            var div = document.createElement('div');
            div.classList.add("meteor");
            div.setAttribute('data-date', result[i][0]);
            div.setAttribute('data-energie', result[i][1]);
            div.setAttribute('data-impact', result[i][2]);
            div.setAttribute('data-lat', result[i][3]);
            div.setAttribute('data-lon', result[i][5]);
            div.setAttribute('data-alt', result[i][7]);
            div.setAttribute('data-vel', result[i][8]);
            div.setAttribute('data-masse', (result[i][8]*result[i][2]));
            space.container.appendChild(div);
            div.addEventListener('click',function() {
               var vel = this.dataset.vel;
               var impact = this.dataset.impact;
               var masse = vel*impact;
               var lat = this.dataset.lat;
               var lon = this.dataset.lon;
               console.log(vel);
               console.log(impact);
               console.log(masse);
               range.data_view.masse.innerHTML = masse;
               range.data_view.velo.innerHTML = vel;
               range.data_view.impact.innerHTML = impact;
               
               var factor = 100;
               
               range.elements.masse.setAttribute("max",(masse*factor));
               range.elements.velo.setAttribute("max",(vel*factor));
               range.elements.impact.setAttribute("max",(impact*factor));
               
               range.elements.masse.setAttribute("min",(masse*-factor));
               range.elements.velo.setAttribute("min",(vel*-factor));
               range.elements.impact.setAttribute("min",(impact*-factor));
               
               
               range.elements.masse.setAttribute("value",masse);
               range.elements.velo.setAttribute("value",vel);
               range.elements.impact.setAttribute("value",impact);
               getCountry(lat,lon); 
            });      
         }
      }
      else
      {
         console.log( 'error' );
      }
   }
};
xhr.open( 'GET', 'methods/meteors.php', true );
xhr.send();


// obtenir le pays
var getCountry = function (lat,lon) {
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
   xhr.open( 'GET', 'methods/getCountry.php?lat='+lat+'&lon='+lon, true );
   xhr.send();
}

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
   xhr.open( 'GET', 'methods/getPopulation.php?country='+country, true );
   xhr.send();
}
