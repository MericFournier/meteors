// reccupérer les éléments du dom 


// éléments sidebar 
var sidebar = {};
sidebar.container = document.querySelector('.side_bar');
sidebar.elements = {};
sidebar.elements.logo = sidebar.container.querySelector('.logo');
sidebar.elements.range = sidebar.container.querySelector('.sideContent');
sidebar.elements.data = sidebar.container.querySelector('.sideData');
// inputs range 
var range = {}; 
range.container = sidebar.container.querySelector('.data-range');
range.elements = {};
range.elements.mass = range.container.querySelector('#rangeMasse');
range.elements.vel = range.container.querySelector('#rangeVelo');
range.elements.impact = range.container.querySelector('#rangeImpact');
range.elements.area = range.container.querySelector('#rangeArea');
range.elements.impact.fill = range.container.querySelector('.fill_data');
// texte des inputs 
range.data_view = {};
range.data_view.mass = range.container.querySelector('#masseValue');
range.data_view.vel = range.container.querySelector('#veloValue');
range.data_view.impact = range.container.querySelector('#impactValue');
range.data_view.area = range.container.querySelector('#areaValue');
range.data_view.blesses = range.container.querySelector('#victimes');
range.data_view.morts = range.container.querySelector('#morts');
//Data elements compare
//data_select = {}; 
//data_select.container = document.querySelector('.data_selected');
//data_select.elements = {};
//data_select.elements.population = data_select.container.querySelector('#victim_count');
//  System 
var space = {};
space.container = document.querySelector('.space');

function modify_value()
{
//   range.data_view.mass.innerHTML = mass;
//   range.data_view.vel.innerHTML = vel;
//   range.data_view.impact.innerHTML = impact;
//   
//   var factor = 100;
//   
//   range.elements.mass.setAttribute("max",(mass*factor));
//   range.elements.vel.setAttribute("max",(vel*factor));
//   range.elements.impact.setAttribute("max",(impact*factor));
//   
//   range.elements.mass.setAttribute("min",(mass*-factor));
//   range.elements.vel.setAttribute("min",(vel*-factor));
//   range.elements.impact.setAttribute("min",(impact*-factor));
//   
//   
//   range.elements.mass.setAttribute("value",mass);
//   range.elements.vel.setAttribute("value",vel);
//   range.elements.impact.setAttribute("value",impact);
//   // getCountry(lat,lon); 
//
//   // actualiser les valeurs des textes des inputs 
//   range.elements.mass.addEventListener('input',function(){
//      range.data_view.masse.innerHTML = this.value;
//   }); 
//   range.elements.vel.addEventListener('input',function(){
//      range.data_view.velo.innerHTML = this.value;
//   }); 
//   range.elements.impact.addEventListener('input',function(){
//      range.data_view.impact.innerHTML = this.value;
//   });
}

// // charger les météorites 
// var xhr = new XMLHttpRequest();
// xhr.onreadystatechange = function()
// {
//    if( xhr.readyState === XMLHttpRequest.DONE )
//    {
//       if(xhr.status === 200)
//       {
//          var result = JSON.parse( xhr.responseText );
//          console.log( 'success' );
//          // création des météorites 
//          for ( var i = 0; i<result.length; i++) {
//             var div = document.createElement('div');
//             div.classList.add("meteor");
//             div.setAttribute('data-date', result[i][0]);
//             div.setAttribute('data-energie', result[i][1]);
//             div.setAttribute('data-impact', result[i][2]);
//             div.setAttribute('data-lat', result[i][3]);
//             div.setAttribute('data-lon', result[i][5]);
//             div.setAttribute('data-alt', result[i][7]);
//             div.setAttribute('data-vel', result[i][8]);
//             div.setAttribute('data-masse', (result[i][8]*result[i][2]));
//             space.container.appendChild(div);
//             div.addEventListener('click',function() {
//                var vel = this.dataset.vel;
//                var impact = this.dataset.impact;
//                var masse = vel*impact;
//                var lat = this.dataset.lat;
//                var lon = this.dataset.lon;
//                console.log(vel);
//                console.log(impact);
//                console.log(masse);
//                range.data_view.masse.innerHTML = masse;
//                range.data_view.velo.innerHTML = vel;
//                range.data_view.impact.innerHTML = impact;
               
//                var factor = 100;
               
//                range.elements.masse.setAttribute("max",(masse*factor));
//                range.elements.velo.setAttribute("max",(vel*factor));
//                range.elements.impact.setAttribute("max",(impact*factor));
               
//                range.elements.masse.setAttribute("min",(masse*-factor));
//                range.elements.velo.setAttribute("min",(vel*-factor));
//                range.elements.impact.setAttribute("min",(impact*-factor));
               
               
//                range.elements.masse.setAttribute("value",masse);
//                range.elements.velo.setAttribute("value",vel);
//                range.elements.impact.setAttribute("value",impact);
//                getCountry(lat,lon); 
//             });      
//          }
//       }
//       else
//       {
//          console.log( 'error' );
//       }
//    }
// };



// xhr.open( 'GET', 'methods/meteors.php', true );
// xhr.send();


// // obtenir le pays
// var getCountry = function (lat,lon) {
//    var xhr = new XMLHttpRequest();
//    xhr.onreadystatechange = function()
//    {
//       if( xhr.readyState === XMLHttpRequest.DONE )
//       {
//          if(xhr.status === 200)
//          {
//             var result = JSON.parse( xhr.responseText );
//             console.log( 'success' ); 
//             console.log(result.countryName);
//             var country = result.countryName;
//             getData(country); 
//          }
//          else
//          {
//             console.log( 'error' );
//          }
//       }
//    };
//    xhr.open( 'GET', 'methods/getCountry.php?lat='+lat+'&lon='+lon, true );
//    xhr.send();
// }

// // obtenir la population 
// var getData = function (country) {
//    var xhr = new XMLHttpRequest();
//    xhr.onreadystatechange = function()
//    {
//       if( xhr.readyState === XMLHttpRequest.DONE )
//       {
//          if(xhr.status === 200)
//          {
//             var result = JSON.parse( xhr.responseText );
//             console.log( 'success' ); 
//             var population = result[0].population;
//             var taille = result[0].area;
//             var density = population/taille;
//             console.log(density);
//          }
//          else
//          {
//             console.log( 'error' );
//          }
//       }
//    };
//    xhr.open( 'GET', 'methods/getPopulation.php?country='+country, true );
//    xhr.send();
// }
