<?php
	require_once('config.php');
	$sql = "SELECT `stop_id`, `stop_code`, `stop_name`, `stop_desc`, `stop_lat`, `stop_lon` FROM `stops`";
	//$sql = "SELECT `stops`.`stop_lat`, `stops`.`stop_lon`, `stops`.`stop_id`, `stop_times`.`trip_id` FROM `stops` , `stop_times` WHERE `stops`.`stop_id` = `stop_times`.`stop_id` AND `stop_times`.`trip_id` = '1001004'";
  $result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <link rel="shortcut icon" type="image/x-icon" href="images/favicon.ico">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdn.jsdelivr.net/npm/daisyui@2.31.0/dist/full.css" rel="stylesheet" type="text/css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
  <script src="js/leaflet-providers.js"></script>
  <!-- MapLibre GL JS (still required to handle the rendering) -->
  <script type="text/javascript" src="//unpkg.com/maplibre-gl@2.4.0/dist/maplibre-gl.js"></script>
  <link href="//unpkg.com/maplibre-gl@2.4.0/dist/maplibre-gl.css" rel="stylesheet" />
  <!-- Mapbox GL Leaflet -->
  <script src="https://unpkg.com/@maplibre/maplibre-gl-leaflet@0.0.19/leaflet-maplibre-gl.js"></script>
        
  <title>On-Time Dashboard</title>

  <style>
		.leaflet-container {
			height: 762px;
			width: 1200px;
			max-width: 100%;
			max-height: 100%;
		}
	</style>
</head>
<body>
  <div data-theme="emerald" class="h-full min-h-screen flex flex-col bg-base-100">
  <div class="navbar bg-white">
    <div class="flex-1">
      <a class="btn btn-ghost normal-case text-xl">OnTime</a>
    </div>
    <div class="flex-none">
      <ul class="menu menu-horizontal px-1">
        <li><a>Item 1</a></li>
        <li><a>Item 3</a></li>
      </ul>
    </div>
  </div>
  <div class="drawer drawer-mobile">
  <input id="my-drawer-2" type="checkbox" class="drawer-toggle" />
  <div class="drawer-content flex flex-col items-center justify-center">
    <div id="map" class="m-auto mt-8 mr-8"></div>
    <label for="my-drawer-2" class="btn btn-primary drawer-button lg:hidden">Open drawer</label>
  
  </div> 
  <div class="drawer-side">
    <label for="my-drawer-2" class="drawer-overlay"></label> 
    <ul class="menu p-4 w-80 bg-base-100 text-base-content">
      <h1>Routes</h1>
      <li><a>Sidebar Item 1</a></li>
      <li><a>Sidebar Item 2</a></li>
    </ul>
  
  </div>
</div>
</div>
</body>
</html>

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

  <?php
		if ($result->num_rows > 0) {
			// Output data of each row
			while ($row = $result->fetch_assoc()) {
        echo "L.circleMarker([" . $row['stop_lat'] . "," . $row['stop_lon'] . "], {radius:2}).addTo(map);";
		  }
		} else {
			echo "No activities found";
		}
		//$conn->close();
	?>

//SELECT DISTINCT `trips`.`route_id`, `stop_times`.`stop_id`, `stops`.`stop_lat`, `stops`.`stop_lon` FROM `stop_times` , `trips` , `stops` WHERE `stop_times`.`trip_id` = `trips`.`trip_id` AND `stop_times`.`stop_id` = `stops`.`stop_id`;

  <?php
    //$sql = "SELECT `routes`.`route_color`, `routes`.`route_id`, `results`.`stop_id`, `results`.`stop_lat`, `results`.`stop_lon` FROM `routes` , `results` WHERE `routes`.`route_id` = `results`.`route_id`";
    $sql = "SELECT `stops`.`stop_lat`, `stops`.`stop_lon`, `stops`.`stop_id`, `stop_times`.`trip_id` FROM `stops` , `stop_times` WHERE `stops`.`stop_id` = `stop_times`.`stop_id` AND `stop_times`.`trip_id` = '1001001'";
    $result = $conn->query($sql);
		if ($result->num_rows > 0) {
      //$currentRoute = 
			// Output data of each row
      echo "var latlngs = [";
      $data = "";
			while ($row = $result->fetch_assoc()) {
        $data = $data . "[" . $row['stop_lat'] . "," . $row['stop_lon'] . "],";
		  }
      echo substr($data, 0, -1);
      echo "];";
      echo "var polyline = L.polyline(latlngs, {color: 'red'}).addTo(map);";
      //echo "var polyline = L.polyline(latlngs, {color: '#" . $row['route_color'] . "'}).addTo(map);";
		} else {
			echo "No activities found";
		}
		$conn->close();
	?>

// MapLibre GL JS does not handle RTL text by default, so we recommend adding this dependency to fully support RTL rendering. 
maplibregl.setRTLTextPlugin('https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-rtl-text/v0.2.1/mapbox-gl-rtl-text.js');

L.maplibreGL({
    style: 'https://tiles.stadiamaps.com/styles/alidade_smooth.json',  // Style URL; see our documentation for more options
    attribution: '&copy; <a href="https://stadiamaps.com/">Stadia Maps</a>, &copy; <a href="https://openmaptiles.org/">OpenMapTiles</a> &copy; <a href="https://openstreetmap.org">OpenStreetMap</a> contributors',
}).addTo(map);

	function onMapClick(e) {
		popup
			.setLatLng(e.latlng)
			.setContent(`You clicked the map at ${e.latlng.toString()}`)
			.openOn(map);
	}

	map.on('click', onMapClick);

</script>