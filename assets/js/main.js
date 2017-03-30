var timeLine = {};

timeLine.el               = {};
timeLine.el.container     = document.querySelector('.time-line-item');
timeLine.el.progress      = document.querySelector('.progress-line');
timeLine.el.datayear      = document.querySelector('.data-year-item');
timeLine.el.datanb        = document.querySelector('.data-number-item');

var lineWidth       = document.querySelector('.time-line-item'),
    lineProgress    = document.querySelector('.progress-line'),
    linepos         = 0,
    timeLapse       = 1500,
    timeLapseRatio  = 1,
    timeLapseEnd    = 2017,
    asteroid_id     = 0,
    asteroidlength  = asteroids.length,
    lineContain     = timeLine.el.container.offsetWidth,
    ratiodiff       = (timeLapseEnd - timeLapse), // ~2.4
    ratio           = lineContain / ratiodiff,
    ratioTot        = ratio * timeLapseRatio;

for (var i = timeLapse; i <= timeLapseEnd; i += 50)
{
    // Create Time
    var div             = document.createElement("div");
    div.className       = 'time-line-date ' + i;
    div.style.transform = 'translateX(' + linepos + 'px)';
    div.innerHTML = i;
    timeLine.el.container.appendChild(div);
    

    console.log(i);
    console.log(linepos);

    linepos += ratioTot * 50;
}
linepos = 0;

function set_interval()
{
    timeLapse   += timeLapseRatio;
    linepos     += ratioTot;
    timeLine.el.progress.style.transform = 'translateX(' + linepos + 'px)'; 

    if(timeLapse >= timeLapseEnd - 1)
    {
    // Stop interval
      clearInterval(clear);
    }
    else
    {
    var nbAst   = 0;
    var massAst = -1;

      // Show asteroid per year
        while((asteroids[asteroid_id].year <= timeLapse) && asteroid_id < asteroidlength - 1)
        {
          asteroid_id += 1;
          nbAst       += 1;
          // Edit year and number Asteroid
          timeLine.el.datayear.innerHTML  = timeLapse;
          timeLine.el.datanb.innerHTML  = asteroid_id;
          
          //ini mass
          if(massAst == -1)
            massAst = asteroids[asteroid_id].mass
          else if(massAst < asteroids[asteroid_id].mass)
            massAst = asteroids[asteroid_id].mass
          
          // send to map
          time_line_marker(asteroids[asteroid_id]);
        }
        if (nbAst != 0)
        {
          var scalenb   = 0.5 + Math.log(nbAst),
            scalemass   = 0.3 + Math.log(massAst);

          // Create year bar
          var div             = document.createElement("div");
          div.className       = 'asteroid-number ' + asteroids[asteroid_id].year;
          div.style.transform = 'translateX(' + linepos + 'px) scaleY(' + scalenb + ')';
          timeLine.el.container.appendChild(div);
          // Create mass bar
          var div             = document.createElement("div");
          div.className       = 'asteroid-mass ' + asteroids[asteroid_id].mass;
          div.style.transform = 'translateX(' + linepos + 'px) scaleY(' + scalemass + ')';
          timeLine.el.container.appendChild(div);       
        }
    }
    return;
}
clear = setInterval("set_interval()", 20);


 var mya = L.icon({
      iconUrl: '../../assets/img/meteorite.png',
    });

var map;

// Add marker and show and filtre
function time_line_marker (id) {
  _astr_time_line = id;
  console.log('lol', _astr_time_line);
  var marker_ = L.marker([_astr_time_line.reclat, _astr_time_line.reclong], {icon: mya}).addTo(map);
  marker_.bindPopup( '<b>' + _astr_time_line.name + '</b> <br>' + '<b>Year : </b>' + _astr_time_line.year + '<br>' + '<b>Mass : </b>' + _astr_time_line.mass + ' kg' , 50);
}

/*******************
* * * * MAP * * * *
*****************/

// var asteroids = [];

function init() {
  var m = {};

  start_(L, 'L');

  function start_(API, suffix) {
    var mapDiv = 'map' + suffix;
    map    = API.map(mapDiv, {
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
    //var asteroids = <?= json_encode($asteroids) ?>;
    var _asteroids = [];

    function astr (name, lat, ltn, mass, year)
    {
      this.name = name;
      this.lat  = lat;
      this.ltn  = ltn;
      this.mass = mass;
      this.year = year; 
    }

    document.addEventListener('click', function (){
      console.log('map.zoom : ' + map._zoom);
      console.log('zoom : ' + zoom);
      console.log('className :');
      console.log(myIcon.options.className);
    });

    // filtration des astéroides 
    // En fonction du nombre de d'astéroide
    for (let key in asteroids) {
      if (asteroids[key].reclat && asteroids[key].reclong && asteroids[key].year && asteroids[key].mass ){
        _asteroids.push(new astr(asteroids[key].name, asteroids[key].reclat, asteroids[key].reclong, asteroids[key].mass, asteroids[key].year));
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
        var elements  = document.querySelectorAll('img.'+ 'zoom-' + zoom);
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
      iconUrl: '../../assets/img/meteorite.png',
      className: 'zoom-' + zoom,
    });

    // // Add marker and show and filtre
    // function filter_marker (zoom) {
    //   for (var i = 0; i<_asteroids.length; i++) {
    //       if (_asteroids[i].mass > rank[zoom]) {  
    //         var marker_ = L.marker([_asteroids[i].lat, _asteroids[i].ltn], {icon: myIcon}).addTo(map);
    //         marker_.bindPopup( '<b>' + _asteroids[i].name + '</b> <br>' + '<b>Year : </b>' + _asteroids[i].year + '<br>' + '<b>Mass : </b>' + _asteroids[i].mass + ' kg' , 50);
    //       }
    //     //fin du for
    //   }
    //   //fin function
    // }
    // filter_marker(2);


    //Print coordinates of the mouse
    map.on('mousemove', function(e) {
      document.getElementById('coords').innerHTML = e.latlng.lat + ', ' + e.latlng.lng;
    });
  }
}
init();