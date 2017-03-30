      
      var astrs = [];

      function init() {
        var m = {};

        start_(L, 'L');

        function start_(API, suffix) {
          var mapDiv = 'map' + suffix;
          var map = API.map(mapDiv, {
            center: [51.505, -0.09],
            maxZoom: 7,
            minZoom: 2,
            dragging: true,
            scrollWheelZoom: true,
          });
          m[suffix] = map;

          //Add baselayer
          API.tileLayer('http://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}.png',{
            attribution: '© OpenStreetMap contributors'
          }).addTo(map);

          //Add TileJSON overlay
          var json = {
            "profile": "mercator",
            "name": "Grand Canyon USGS",
            "format": "png",
            "bounds": [-112.26379395, 35.98245136, -112.10998535, 36.13343831],
            "minzoom": 10, "version": "1.0.0",
            "maxzoom": 16,
            "center": [-112.18688965, 36.057944835, 1],
            "type": "overlay", "description": "",
            "basename": "grandcanyon",
            "tilejson": "2.0.0",
            "sheme": "xyz",
            "tiles": ["http://tileserver.maptiler.com/grandcanyon/{z}/{x}/{y}.png"]};
          
          //If not able to display the overlay, at least move to the same location
          map.setView([2, 46], json.center[2]);

          // get API nasa data
          var astrs = <?= json_encode($asteroids) ?>;

          var _astrs = [];

          function astr (name, lat, ltn, mass, year)
          {
            this.name = name;
            this.lat = lat;
            this.ltn = ltn;
            this.mass = mass;
            this.year = year; 
          }

          document.addEventListener('click', function () {
            console.log('map.zoom : ' + map._zoom);
            console.log('zoom : ' + zoom);
            console.log('className :');
            console.log(myIcon.options.className);
          });

          // filtration des astéroides 
          // En fonction du nombre de d'astéroide
          for (let key in astrs) {
            if (astrs[key].reclat && astrs[key].reclong && astrs[key].year && astrs[key].mass ){
              _astrs.push(new astr(astrs[key].name, astrs[key].reclat, astrs[key].reclong, astrs[key].mass, astrs[key].year));
            }
          }
          
          var zoom = 2;

          setInterval(function(){ 
            // check if zoom change and zoom event
            if (zoom != map._zoom && zoom <= map._zoom) {
              zoom = map._zoom;              
              myIcon.options.className = 'zoom-' + zoom;
              filter_marker(zoom);
            }
            // check if zoom change and dezoom event
            if (zoom != map._zoom && zoom > map._zoom) {
              var className = myIcon.options.className;
              var elements = document.querySelectorAll('img.'+ 'zoom-' + zoom);
              console.log(elements);
              for (var i = 0; elements.length > i; i++) {
                elements[i].parentNode.removeChild(elements[i]);
                console.log('element delete');
              }
              zoom = map._zoom; 
            }
          }, 100);
          

          var rank = [0,0, 200000, 100000, 50000, 25000, 15000, 1000];

          var myIcon = L.icon({
            iconUrl: 'assets/img/meteorite.png',
            className: 'zoom-' + zoom,
          });

          // Add marker and show and filtre
          function filter_marker (zoom) {
            for (var i = 0; i<_astrs.length; i++) {
                if (_astrs[i].mass > rank[zoom]) {  
                  var marker_ = L.marker([_astrs[i].lat, _astrs[i].ltn], {icon: myIcon}).addTo(map);
                  marker_.bindPopup( '<b>' + _astrs[i].name + '</b> <br>' + '<b>Year : </b>' + _astrs[i].year + '<br>' + '<b>Mass : </b>' + _astrs[i].mass + ' kg' , 50);
                }
              //fin du for
            }
            //fin function
          }

          filter_marker(2);


          //Print coordinates of the mouse
          map.on('mousemove', function(e) {
            document.getElementById('coords').innerHTML = e.latlng.lat + ', ' + e.latlng.lng;
          });
        }
      }