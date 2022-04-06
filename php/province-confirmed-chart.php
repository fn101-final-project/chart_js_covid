<?php

$country = $_GET['country'];

if(isset($country)){
    require_once("sqlite.php");

    $sql = sprintf('SELECT "Date", "Province/State" AS Province, SUM(Difference) AS Cases 
    FROM `covid-19` 
    WHERE "Country/Region"="%s" AND Case_Type="Confirmed" AND Province!=""
    GROUP BY "Date", Province',$country);

    $results = $db->query($sql);

    $jsonData = array();
    while ($res= $results->fetchArray(1))
    { array_push($jsonData, $res); }

    $province_confirmed_results = json_encode($jsonData);
    echo $province_confirmed_results;
}
