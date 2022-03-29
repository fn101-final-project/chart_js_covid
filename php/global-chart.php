<?php

require_once("sqlite.php");

$results = $db->query('SELECT Case_Type, SUM(Difference) AS Cases 
FROM `covid-19` 
WHERE Case_Type!="Active" 
GROUP BY Case_Type');

$jsonData = array();
while ($res= $results->fetchArray(1))
{ array_push($jsonData, $res); }

$global_number_results = json_encode($jsonData);

/////////////////////////////////////////////////////////////

$results = $db->query('SELECT `Country/Region` AS Country,Case_Type,SUM(Difference) AS Case_Total 
FROM `covid-19` 
WHERE Case_Type="Confirmed"
GROUP BY Country
ORDER BY Case_Total DESC
LIMIT 5',);

$jsonData = array();
while ($res= $results->fetchArray(1))
{ array_push($jsonData, $res); }

$global_top5_confirmed_results = json_encode($jsonData);

////////////////////////////////////////////////////////////////


$results = $db->query('SELECT `Country/Region` AS Country,Case_Type,SUM(Difference) AS Case_Total 
FROM `covid-19` 
WHERE Case_Type="Deaths"
GROUP BY Country
ORDER BY Case_Total DESC
LIMIT 5',);

$jsonData = array();
while ($res= $results->fetchArray(1))
{ array_push($jsonData, $res); }

$global_top5_deaths_results = json_encode($jsonData);

////////////////////////////////////////////////////////////////


$results = $db->query('SELECT `Country/Region` AS Country,Case_Type,SUM(Difference) AS Case_Total 
FROM `covid-19` 
WHERE Case_Type="Recovered"
GROUP BY Country
ORDER BY Case_Total DESC
LIMIT 5',);

$jsonData = array();
while ($res= $results->fetchArray(1))
{ array_push($jsonData, $res); }

$global_top5_recovered_results = json_encode($jsonData);

////////////////////////////////////////////////////////////////


$results = $db->query('SELECT "Date", SUM(Difference) AS Cases
FROM `covid-19` 
WHERE Case_Type="Confirmed" 
GROUP BY "Date"',);

$jsonData = array();
while ($res= $results->fetchArray(1))
{ array_push($jsonData, $res); }

$global_daily_confirmed_results = json_encode($jsonData);

////////////////////////////////////////////////////////////////


$results = $db->query('SELECT "Date", SUM(Difference) AS Cases
FROM `covid-19` 
WHERE Case_Type="Deaths" 
GROUP BY "Date"',);

$jsonData = array();
while ($res= $results->fetchArray(1))
{ array_push($jsonData, $res); }

$global_daily_deaths_results = json_encode($jsonData);

////////////////////////////////////////////////////////////////


$results = $db->query('SELECT "Date", SUM(Difference) AS Cases
FROM `covid-19` 
WHERE Case_Type="Recovered" 
GROUP BY "Date"',);

$jsonData = array();
while ($res= $results->fetchArray(1))
{ array_push($jsonData, $res); }

$global_daily_recovered_results = json_encode($jsonData);


?>


