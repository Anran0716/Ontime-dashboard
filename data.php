<?php
  $date = $today;
?>
<label for="my-drawer-2" class="drawer-overlay"></label> 
    <h1 onclick="switchTabs('routes')" class="cursor-pointer float-left mt-4 ml-4 rounded-t-lg text-lg font-bold bg-slate-100 pl-2 pr-1">Routes</h1>
    <h1 onclick="switchTabs('data')" class="cursor-pointer float-left mt-4 rounded-t-lg text-lg font-bold bg-slate-200 pl-3 pr-2">Data</h1>
    <h1 class="float-left mt-4 rounded-t-lg text-lg font-bold bg-slate-100 pl-2 pr-2">Other</h1>
    <ul class="p-2 w-fit h-screen overflow-auto bg-slate-200 ml-4 mb-8 rounded-lg text-base-content">
    <h1 class="text-lg font-bold text-slate-600 float-left mr-2">Data for</h1>
    <select id="date" onchange="getDay()" class="select select-sm w-full max-w-xs">
      <option disabled selected>Select Date</option>
      <option value="20230626">06/26/2023</option>
      <option value="20230627">06/27/2023</option>
      <option value="20221018">10/18/2022</option>
    </select>
    <script>
      function getDay() {
        var dbParam = document.getElementById("date").value;
        const xhttp = new XMLHttpRequest();
        
        xhttp.onload = function() {
          document.getElementById("body").innerHTML = this.responseText;
          delays.addTo(map);
        }
        
        xhttp.open("GET", "today.php?day=" + dbParam);
        xhttp.send();
        showDelays(dbParam);
        
      }
      function showDelays(day) {
        <?php
          $file = fopen("data/delays.csv", "r") or die("Unable to open file!");
          while(!feof($file)) {
              $line = fgetcsv($file);
              if ($line[0] == "tmstmp") {
                  continue;
              }
              $vid = $line[3];
              $tmstmp = $line[0];
              $lat = $line[1];
              $lon = $line[2];
              $rt = $line[4];
              $pid = $line[5];
              echo "L.circleMarker([".$lat.",".$lon."], {radius:8, opacity:0.1, weight:2, color:'red'}).addTo(map).bindPopup(\"".$rt."\");";
              
          }
          fclose($file);
        ?>
      }
    </script>
    <div class="grid grid-flow-row auto-rows-1">
        <div>
          <table id="routesTable" class="table-fixed space-x-4 text-slate-600">
          <h1 class="text-lg font-bold text-slate-600">Most Delayed Routes</h1>
            <thead class="space-x-4">
              <th>Route</th>
              <th class="ml-2">No. Delays</th>
            </thead>
            <tbody id="body" class="space-x-4">
            </tbody>
          </table>
        </div>
        <h1 class="text-lg font-bold text-slate-600">Average Arrival Delay</h1>
        <div id="delayGraph"></div>
        <div>03</div>
      </div>
    </ul>
    <script>
      
    </script>