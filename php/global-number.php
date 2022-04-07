<?php

require_once("sqlite.php");

$results = $db->query('SELECT Case_Type, SUM(Difference) AS Cases 
FROM `covid-19` 
WHERE Case_Type!="Active" 
GROUP BY Case_Type');

$jsonData = array();
while ($res = $results->fetchArray(1)) {
    array_push($jsonData, $res);
}

$global_number_results = json_encode($jsonData);
echo $global_number_results;
