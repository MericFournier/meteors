var country;
var population;
var div = document.querySelectorAll('.div');

console.log(div);

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

//var div = document.querySelectorAll('.div')
//console.log(div)
//for ( var i = 0; i<div.length; i++ ) {
//    div[i].onclick = function() {
//        var that = this
//        var data = this.dataset.country
//        fetch( 'http://api.population.io:80/1.0/population/'+data+'/2016-12-31/' )
//        .then( ( response ) =>
//        {
//        return response.json()
//        } )
//        .then( ( result ) =>
//        {
//        that.innerHTML = result.total_population.population
//
//        } ) 
//    }
//}
      