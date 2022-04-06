<?php

$country = $_GET['country'];

if(isset($country)){
    require_once("sqlite.php");

    $sql = sprintf('SELECT "Date", Case_Type, SUM(Difference) AS Cases 
    FROM `covid-19` 
    WHERE "Country/Region"="%s" AND Case_Type!="Active"
    GROUP BY "Date", Case_Type',$country);

    $results = $db->query($sql);

    $jsonData = array();
    while ($res= $results->fetchArray(1))
    { array_push($jsonData, $res); }

    $country_daily_results = json_encode($jsonData);
    echo $country_daily_results;
}


