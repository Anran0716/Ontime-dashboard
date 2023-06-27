<?php
	require_once('config.php');
    $file = fopen("data/realtime.csv", "r") or die("Unable to open file!");
    //echo "document.getElementById('prac').innerHTML += 'ya';";
    
    if (isset($_POST['busArray'])) {
        $busArray = $_POST["busArray"];
        $busArray = json_decode($busArray);
    }

    while(!feof($file)) {
        $line = fgetcsv($file);
        if ($line[0] == "vid") {
            continue;
        }
        $vid = $line[0];
        $tmstmp = $line[1];
        $lat = $line[2];
        $lon = $line[3];
        $rt = $line[6];
        $des = $line[7];
        $dly = $line[9];
        if ($vid == "") {
            continue;
        }
        if ($dly === "True") {
            $color = '#f02f11';
        }
        else {
            $color = '#3bbf40';
        }
        $sql = "SELECT `route_color` FROM `routes` WHERE `route_id` = $rt";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $rtcolor = $row['route_color'];
            }
        }

        $currentBus = null;
        foreach($busArray as &$bus) {
            if ($bus->vid === $vid) {
                $bus->lat = $lat;
                $bus->lon = $lon;
                $bus->dly = $dly;
                $bus->des = $des;
                $bus->dlycolor = $color;
                $currentBus = $bus;
                break;
            }
        }
        if ($currentBus == null) {
            $newBus = new StdClass();
            $newBus->vid = $vid;
            $newBus->rt = $rt;
            $newBus->lat = $lat;
            $newBus->lon = $lon;
            $newBus->dly = $dly;
            $newBus->des = $des;
            $newBus->color = $rtcolor;
            $newBus->dlycolor = $color;
            array_push($busArray, $newBus);
        }

            //echo "const bus".$vid." = new Bus(".$vid.",".$rt.",true,".$lat.",".$lon.");";
            //echo "bus".$vid.".marker.bindPopup(\"" . $rt ."</br>Destination: $des\");";
            //echo "bus".$vid.".marker._icon.style.color = '".$color."';";
            //echo "bus".$vid.".marker._icon.style.boxShadow = '0px 0px 0px 4px #".$rtcolor."';";
            //echo "buses.push(bus".$vid.");";
    
            //echo "document.getElementById('prac').innerHTML = 'um';";
            /*$currentBus = null;
            foreach($buses as $bus) {
                if ($bus->vid === $vid) {
                    $currentBus = $bus;
                    echo $currentBus.".updateLoc(".$lat.",".$lon.");";
                    break;
                }
            }
            if ($currentBus === null) {
                echo "const bus = new Bus(".$vid.",".$rt.",true,".$lat.",".$lon.");";
                echo "bus.marker.bindPopup(\"" . $rt ."</br>Destination: $des\");";
                echo "bus.marker._icon.style.color = '".$color."';";
                echo "bus.marker._icon.style.boxShadow = '0px 0px 0px 4px #".$rtcolor."';";
                echo "buses.push(bus);";
            }*/
    }
    $conn->close();
    fclose($file);
    echo json_encode($busArray);
?>