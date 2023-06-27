<?php
    require_once('config.php');
    $day = $_GET['day'];
    
    if ($day === "20221018") {
        $sql = "SELECT `rt`, COUNT(*) FROM `20221018` WHERE `dly` = 'True' GROUP BY `rt` ORDER BY `COUNT(*)` DESC LIMIT 5";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<th>".$row['rt']."</th>";
                echo "<td>".$row['COUNT(*)']."</td>";
                echo "</tr>";
            }
        }
    }
    else {
        $file = fopen("data/delays.csv", "r") or die("Unable to open file!");
        $delayCounts = array();
        while(!feof($file)) {
            $line = fgetcsv($file);
            if ($line[0] == "vid") {
                continue;
            }
            $vid = $line[3];
            $tmstmp = $line[0];
            $lat = $line[1];
            $lon = $line[2];
            $rt = $line[4];
            $pid = $line[5];
            if (substr($tmstmp, 0, 8) == $day) {
                if (array_key_exists($rt, $delayCounts)) {
                    $delayCounts[$rt]++;
                }
                else {
                    $delayCounts[$rt] = 1;
                }
            }
        }
        arsort($delayCounts);
        $x = 0;
        foreach ($delayCounts as $key => $value) {
            if ($x == 5)
                break;
            echo "<tr>";
            echo "<th>".$key."</th>";
            echo "<td>".$value."</td>";
            echo "</tr>";
            $x++;
        }
        fclose($file);
    }
?>