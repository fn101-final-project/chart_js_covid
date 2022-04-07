<?php

require_once("sqlite.php");

$sql = "SELECT * FROM `covid-19` WHERE Case_Type='Confirmed'" ;
$result = $db->query($sql);
$stat = array();

while ($row = $result->fetchArray()) {
    $country = $row['Country/Region'];
    $confirmed = $row['Difference'];
    if (array_key_exists($country, $stat)) {
        $stat[$country] = $stat[$country] + $confirmed;
    } else {
        $stat[$country] = $confirmed;
    }
}

    
$world_map_results = json_encode($stat);
echo $world_map_results;