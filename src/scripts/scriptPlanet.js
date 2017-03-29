var your_latitude = '',
      your_longitude = '',
      coord_discovered = false;

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
      function initialize()
      {
        var options = { zoom: 6.0, position: [your_latitude,your_longitude] }
        var earth = new WE.map('earth_div', options);

        // texture
        WE.tileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(earth);
        // https://cartodb-basemaps-{s}.global.ssl.fastly.net/light_all/{z}/{x}/{y}.png 
        //http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png

        if (coord_discovered){you_marker = WE.marker([your_latitude, your_longitude], 'src/images/location.png').addTo(earth);}

        var coord = "<?= $coord ?>";

        for (let i = 0; i < coord.length; i++)
        {
          let marker = WE.marker([coord[i].lat, coord[i].lon], 'src/images/locatio.png').addTo(earth);
          // marker.bindPopup("<b>Cairo</b><br>Yay, you found me!", {maxWidth: 120, closeButton: true});
        }

        earth.setView([51.505, 0], 1);
      }

      initialize();
    }
    function errorHandler(error)
    {
      // Affichage d'un message d'erreur plus "user friendly" pour l'utilisateur.
      alert('Une erreur est survenue durant la géolocalisation. Veuillez réessayer plus tard.');
      function initialize()
      {
        var earth = new WE.map('earth_div');

        // texture
        WE.tileLayer('https://cartodb-basemaps-{s}.global.ssl.fastly.net/light_all/{z}/{x}/{y}.png ').addTo(earth);
        // https://cartodb-basemaps-{s}.global.ssl.fastly.net/light_all/{z}/{x}/{y}.png 
        //http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png

        var coord = "<?= $coord ?>";

        for (let i = 0; i < coord.length; i++)
        {
          let marker = WE.marker([coord[i].lat, coord[i].lon], 'src/images/location.png', 10, 10).addTo(earth);
          // marker.bindPopup("<b>Cairo</b><br>Yay, you found me!", {maxWidth: 120, closeButton: true});
        }

        earth.setView([51.505, 0], 1);
      }

      initialize();
    }
    }
    else
    {
      alert('Votre navigateur ne prend malheureusement pas en charge la géolocalisation.');
      function initialize()
      {
        var earth = new WE.map('earth_div');

        // texture
        WE.tileLayer('https://cartodb-basemaps-{s}.global.ssl.fastly.net/light_all/{z}/{x}/{y}.png ').addTo(earth);
        // https://cartodb-basemaps-{s}.global.ssl.fastly.net/light_all/{z}/{x}/{y}.png 
        //http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png

        var coord = "<?= $coord ?>";

        for (let i = 0; i < coord.length; i++)
        {
          let marker = WE.marker([coord[i].lat, coord[i].lon], 'src/images/location.png', 10, 10).addTo(earth);
          // marker.bindPopup("<b>Cairo</b><br>Yay, you found me!", {maxWidth: 120, closeButton: true});
        }

        earth.setView([51.505, 0], 1);
      }

      initialize();
    }