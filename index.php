<?php
	require_once('config.php');
  $today = date_create();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <link rel="shortcut icon" type="image/x-icon" href="images/favicon.ico">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="style.css">
  <script src="jquery-3.6.4.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
  
  <link href="https://cdn.jsdelivr.net/npm/daisyui@3.1.0/dist/full.css" rel="stylesheet" type="text/css" />
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
  <script src="js/leaflet-providers.js"></script>
  <!-- MapLibre GL JS (still required to handle the rendering) -->
  <script type="text/javascript" src="//unpkg.com/maplibre-gl@2.4.0/dist/maplibre-gl.js"></script>
  <link href="//unpkg.com/maplibre-gl@2.4.0/dist/maplibre-gl.css" rel="stylesheet" />
  <!-- Mapbox GL Leaflet -->
  <script src="https://unpkg.com/@maplibre/maplibre-gl-leaflet@0.0.19/leaflet-maplibre-gl.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/fetch/3.6.2/fetch.min.js" integrity="sha512-1Gn7//DzfuF67BGkg97Oc6jPN6hqxuZXnaTpC9P5uw8C6W4yUNj5hoS/APga4g1nO2X6USBb/rXtGzADdaVDeA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet-ajax/2.1.0/leaflet.ajax.min.js"></script>
  <script src="https://d3js.org/d3.v4.js"></script>
  <script src='https://unpkg.com/leaflet.marker.slideto@0.2.0/Leaflet.Marker.SlideTo.js'></script>
  <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
  
  <title>On-Time Dashboard</title>

</head>

<body>
  <div class="h-full min-h-screen max-w-full bg-white overflow-hidden">
  <div class="drawer drawer-open">
    <input id="my-drawer-2" type="checkbox" class="drawer-toggle"/>
    <div class="mt-12 drawer-content">
      <div id="busData" class="font-bold float-left ml-16"></div>
      <!--<input class="float-right mr-12" onchange="showData(this.value)" type="date" id="dataDate" name="dataDate" value="2022-10-18" min="2022-10-18" max="2022-10-31">-->
      <div id="time" class="float-right mr-12">Time</div>
      <div id="map" class="outline outline-4 outline-slate-200 rounded-lg w-11/12 mt-8 m-auto"></div>
    </div> 
    <script>
      const map = L.map('map', {
        center: [29.652, -82.339],
        zoom: 13,
        maxBounds: L.latLngBounds(L.latLng(29.5808, -82.4735), L.latLng(29.7268, -82.2144)),
        doubleClickZoom: false,
        maxBoundsViscosity: 1,
        bounceAtZoomLimits: false,
        maxZoom: 16,
        minZoom: 12
      });
      var delays = L.layerGroup();
    </script>
  <div id="side-content" class="drawer-side">
    <h1 class="mt-2 ml-4 mb-2 text-2xl font-bold">Gainesville RTS OnTime</h1>
    <div id="data-content" style="display:none;">
    <?php
      include 'data.php';
    ?>
    </div>
    <div id="route-content">
      <label for="my-drawer-2" class="drawer-overlay"></label> 
      <h1 onclick="switchTabs('routes')" class="cursor-pointer float-left ml-4 rounded-t-lg text-lg font-bold bg-slate-200 pl-2 pr-2">Real-Time Info</h1>
      <h1 onclick="switchTabs('data')" class="cursor-pointer float-left rounded-t-lg text-lg font-bold bg-slate-100 pl-2 pr-2">Historical Data</h1>
      <!--<h1 class="float-left rounded-t-lg text-lg font-bold bg-slate-100 pl-2 pr-2">Other</h1>-->
      <ul class="p-4 w-80 h-screen overflow-auto bg-slate-200 ml-4 mb-8 rounded-r-lg text-base-content">
        <!--<form method="get" action="" id='routeform' name='routeform'>
        <div id="prac" onclick="display()">Display all routes</div>-->
        <select id="route" onchange="displayRoute(this.value)" class="bg-slate-800 text-white select text-base w-full">
          <option disabled selected>Add Route</option>
          <option value="display">Display all</option>
          <option value="clear">Clear all</option>
        <?php
          /*<select id="date" onchange="getDay()" class="select select-sm w-full max-w-xs">
          <option disabled selected>Select Date</option>
          <option value="20230626">06/26/2023</option>
          <option value="20230627">06/27/2023</option>
          <option value="20221018">10/18/2022</option>
          </select>*/
          $sql = "SELECT `route_id`, `route_long_name`, `route_color` FROM `routes` ORDER BY `routes`.`route_id` ASC";
          //$sql = "SELECT `stops`.`stop_lat`, `stops`.`stop_lon`, `stops`.`stop_id`, `stop_times`.`trip_id` FROM `stops` , `stop_times` WHERE `stops`.`stop_id` = `stop_times`.`stop_id` AND `stop_times`.`trip_id` = '1001004'";
          $result = $conn->query($sql);
          if ($result->num_rows > 0) {
            // Output data of each row
            while ($row = $result->fetch_assoc()) {
              //echo "<div class='collapse'>";
              $routeid = $row['route_id'];
              echo "<option value='$routeid'>Route $routeid</option>";
              /*echo "<input type='checkbox' value='$routeid' onchange='add(this)'/>";
              echo "<div class='collapse-title -mt-4'>";
              echo "<div class='float-left circle mr-2' style='background-color:#".$row['route_color'].";'></div>";
              echo "<div name='route' onclick='add(this.value)' value='$routeid' class='bg-slate-200 ml-1 mt-1 w-1/2 rounded-lg font-bold cursor-pointer text-[#373a40]'>Route " . $row['route_id'] . "</div>";
              echo "</div>";
              echo "<div class='collapse-content'>";
              echo "<p id='$routeid'>No buses</p>";
              echo "</div>";
              echo "</div>";*/
            }
          } else {
            echo "No routes found";
          }
        ?>
        </select>
        <div id="currRoutes" class="mt-2 -ml-1 grid grid-cols-2"></div>
        <div class="bg-white rounded-lg p-3 font-semibold">Current data for buses running right now</div>
      </ul>
    </div>
  </div>
</div>
</div>
</body>
</html>

<script>
  var busIcon = L.divIcon({
    html: '<ion-icon size="large" name="bus"></ion-icon>',
    className: 'my-div-icon',
    iconSize: [32, 32]
  });
  var stopIcon = L.divIcon({
    html: '<ion-icon name="location"></ion-icon>',
    className: 'stop-icon',
    iconSize: [15, 15]
  });
  
  <?php
    //$sql = "SELECT `stop_id`, `stop_name`, `stop_desc`, `stop_lat`, `stop_lon` FROM `stops`";
    $sql = "SELECT * FROM `top_stops`";
    //$sql = "SELECT `stops`.`stop_lat`, `stops`.`stop_lon`, `stops`.`stop_id`, `stop_times`.`trip_id` FROM `stops` , `stop_times` WHERE `stops`.`stop_id` = `stop_times`.`stop_id` AND `stop_times`.`trip_id` = '1001004'";
    $result = $conn->query($sql);
		if ($result->num_rows > 0) {
			// Output data of each row
      $currentStop = "1";
			while ($row = $result->fetch_assoc()) {
        if ($currentStop != $row['stop_id']) {
          echo $stopOutput;
          $routes = " ";
          $currentStop = $row['stop_id'];
        }
        $routes = $routes . " " . $row['route_id'];
        $stopOutput = "var marker = L.marker([" . $row['stop_lat'] . "," . $row['stop_lon'] . "], {icon: stopIcon}).bindPopup(\"#".$row['stop_id'].": " . $row['stop_name'] . "</br>".$routes ."\").addTo(map);";
		  }
		} else {
			echo "No stops found";
		}
		$conn->close();
	?>
  delays.addTo(map);
  

  function switchTabs(name) {
    if (name == "data") {
      clear();
      document.getElementById("data-content").style.display = "block";
      document.getElementById("route-content").style.display = "none";
      //showPaths();
    }
    else {
      delays.clearLayers();
      document.getElementById("route-content").style.display = "block";
      document.getElementById("data-content").style.display = "none";
    }
  }
  const tripsShown = new Set();
	
  class Bus {
    constructor(vid, rt, lat, lon, dly, des, color, dlycolor) {
      this.vid = vid;
      this.rt = rt;
      this.lat = lat;
      this.lon = lon;
      this.dly = dly;
      this.des = des;
      this.color = color;
      this.dlycolor = dlycolor;
    }
    /*updateLoc(lat, lon) {
      this.marker.slideTo([lat, lon], {duration:4000});
    }
    delete() {
      map.removeLayer(this.marker);
    }*/
  }

  //$(document).ready(showPaths());
  
  var pathLayer = L.geoJSON().addTo(map);
  //$(document).ready(showPaths());
  function showPaths() {
    map.removeLayer(pathLayer);
    $.getJSON("data/route.geojson",function(data){
      // add GeoJSON layer to the map once the file is loaded
      pathLayer = L.geoJson(data ,{
        filter: pathFilter, 
        onEachFeature: function(feature, featureLayer) {
          featureLayer.bindPopup(feature.properties.long_name);
          featureLayer.setStyle({color: "#" + feature.properties.color});
        }
      }).addTo(map);
    });
    
  }
  var busMark = {};
  var buses = [];
  function pathFilter(feature) {
    return tripsShown.has(feature.properties.route_id);
  }
  function clear() {
    tripsShown.forEach((trip) => {
      remove(trip);
    });
  }
    //map.removeLayer(datalayer);
    //var allDisplayed = false;
    function displayRoute(value) {
      if (value === "display") {
        display();
      }
      else if (value === "clear") {
        clear();
      }
      else {
        addBus(value);
      }
      showPaths();
    }
    

  function remove(value) {
    buses.forEach((bus) => {
      if (bus.rt === value) {
        busMark[bus.vid].removeFrom(map);
      }
    });
    document.getElementById(value).remove();
    tripsShown.delete(value);
    showPaths();
  }

  function addBus(value) {
    //var routeid = value;
    //clearInterval(x);
    //document.getElementById("time").innerHTML = (new Date()).toLocaleString();
    if (!tripsShown.has(value)) {
      tripsShown.add(value);
      //var amount = 0;
      //search through all vid and add every vehicle that applies to the route being added
      buses.forEach((bus) => {
        if (bus.rt === value) {
          //if (amount === 0) {
            //document.getElementById(routeid).innerHTML = "Buses: ";
          //}
          //document.getElementById(routeid).innerHTML += bus.vid + " ";
          busMark[bus.vid].addTo(map);
          busMark[bus.vid]._icon.style.boxShadow = '0px 0px 0px 4px #' + bus.color;
          busMark[bus.vid]._icon.style.color = bus.dlycolor;
          //amount++;
        }
      });
      //call php function to show the route popup
      $.ajax(
          {
            url: "addRoute.php",
            type: 'POST',
            dataType: 'text',
            data: {route: value},
            success: function (responseText)
            {
              eval(responseText);
            },
                            
            error: function (responseText)
            {
              alert(responseText);
            }
        });
      //x = setInterval(changeTime(routeid), 15000);
    }
    //once the value has sufficiently been added, redisplay the paths to show the ones that apply
    
  }
  function display() {
      buses.forEach((bus) => {
        addBus(bus.rt);
      });
    }
// MapLibre GL JS does not handle RTL text by default, so we recommend adding this dependency to fully support RTL rendering. 
maplibregl.setRTLTextPlugin('https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-rtl-text/v0.2.1/mapbox-gl-rtl-text.js');

L.maplibreGL({
    style: 'https://tiles.stadiamaps.com/styles/alidade_smooth.json',  // Style URL; see our documentation for more options
    attribution: '&copy; <a href="https://stadiamaps.com/">Stadia Maps</a>, &copy; <a href="https://openmaptiles.org/">OpenMapTiles</a> &copy; <a href="https://openstreetmap.org">OpenStreetMap</a> contributors',
}).addTo(map);
  

  $(document).ready(function() {
    getRealtime();
    document.getElementById("time").innerHTML = (new Date()).toLocaleString();
    setInterval(changeTime, 15000);
  });
  function changeTime() {
    
  //countDownDate = new Date(countDownDate.getTime() + 15000);
  //markerGroup.clearLayers();
  
  //markerGroup.addTo(map);
  document.getElementById("time").innerHTML = (new Date()).toLocaleString();
  getRealtime();
}

  //updates the position of vehicles
  function getRealtime() {
    $.ajax(
        {
          url: "realtime.php",
          type: 'POST',
          dataType: "json",
          data: {busArray: JSON.stringify(buses)},
          success: function (result)
          {
            var busNo = 0;
            const routes = new Set();
            var delayed = 0;
            result.forEach((bus) => {
              busNo++;
              routes.add(bus.rt);
              if (bus.dly === "True") {
                delayed++;
              }
              if (bus.vid in busMark) {
                busMark[bus.vid]._icon.style.color = bus.dlycolor;
                busMark[bus.vid].slideTo([bus.lat, bus.lon], {duration:15000});
              }
              else {
                buses.push(bus);
                busMark[bus.vid] = L.marker([bus.lat, bus.lon], {icon: busIcon}).bindPopup("#"+bus.vid+": "+bus.rt+"</br>Destination: "+bus.des+"</br>Location:"+bus.lat+", "+bus.lon);
                //busMark[bus.vid].remove();
              }
            }
            );
            document.getElementById("busData").innerHTML = "No. Buses: " + busNo + " No. Routes: " + routes.size + " No. Delayed: " + delayed;
          },
                          
          error: function (responseText)
          {
            alert(responseText);
          }
      });
  }

  //called when user selects to view old data
function showData(date) {
  markerGroup.clearLayers();
  clearInterval(x);
    var time = "00:00:00";
    $.ajax(
      {
        url: "readFile.php",
        type: 'POST',
        dataType: 'text',
        data: {date: date},
        success: function (responseText)
        {
          eval(responseText);
        },
                        
        error: function (responseText)
        {
          alert(responseText);
        }
    });
  }

  function change() {
    if (countDownDate == "00:00:00") {
      clearInterval(z);
      document.getElementById("time").innerHTML = "EXPIRED";
    }
    countDownDate = new Date(countDownDate.getTime() + 15000);
    document.getElementById("time").innerHTML = countDownDate.toLocaleString();
      
    // If the count down is over, write some text 
    if (countDownDate.getHours() > 22) {
      clearInterval(z);
      document.getElementById("time").innerHTML = "EXPIRED";
    }
  }

// set the dimensions and margins of the graph
var margin = {top: 10, right: 30, bottom: 30, left: 40},
    width = 500 - margin.left - margin.right,
    height = 400 - margin.top - margin.bottom;

// append the svg object to the body of the page
var svg = d3.select("#delayGraph")
  .append("svg")
    .attr("width", width + margin.left + margin.right)
    .attr("height", height + margin.top + margin.bottom)
  .append("g")
    .attr("transform",
          "translate(" + margin.left + "," + margin.top + ")");

//Read the data
d3.csv("data/delaysByHour.csv",

  // When reading the csv, I must format variables:
  function(d){
    return { time : d3.timeParse("%H:%M")(d.time), amount : d.amount }
  },

  // Now I can use this dataset:
  function(data) {

    // Add X axis --> it is a date format
    var x = d3.scaleTime()
      .domain(d3.extent(data, function(d) { return d.time; }))
      .range([ 0, width ]);
    svg.append("g")
      .attr("transform", "translate(0," + height + ")")
      .call(d3.axisBottom(x));

    // Add Y axis
    var y = d3.scaleLinear()
      .domain([0, d3.max(data, function(d) { return +d.amount; })])
      .range([ height, 0 ]);
    svg.append("g")
      .call(d3.axisLeft(y));

    // Add the line
    svg.append("path")
      .datum(data)
      .attr("fill", "none")
      .attr("stroke", "steelblue")
      .attr("stroke-width", 1.5)
      .attr("d", d3.line()
        .x(function(d) { return x(d.time) })
        .y(function(d) { return y(d.amount) })
        )

})

</script>