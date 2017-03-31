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

// properties of icon map 
 var mya = L.icon({
      iconUrl: '../../assets/img/meteorite.png',
    });

var map;

// Add marker and show and filtre
function time_line_marker (id) {
  _astr_time_line = id;
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
  start_(L, 'mapWE')


  function start_(API, suffix) {
    var mapDiv = 'map' + suffix;
    map = API.map(mapDiv, {
      center: [51.505, -0.09],
      boxZoom: false,
      dragging: false,
      doubleClickZoom: false,
      maxZoom: 7,
      minZoom: 2,
      dragging: false,
      scrollWheelZoom: false,
      zoomControl: false,
    });
    m[suffix] = map;

    //Add baselayer
    API.tileLayer('http://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}.png',{
      attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    //If not able to display the overlay, at least move to the same location
    map.setView([2, 46], 1);

    //Print coordinates of the mouse
    map.on('mousemove', function(e) {
      document.getElementById('coords').innerHTML = e.latlng.lat + ', ' + e.latlng.lng;
    });
  }
}
init();