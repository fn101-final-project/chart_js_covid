<?php

$case_type = $_GET['case-type'];

if (isset($case_type)) {
    require_once("sqlite.php");

    $sql = sprintf('SELECT "Date", SUM(Difference) AS Cases
    FROM `covid-19` 
    WHERE Case_Type="%s" 
    GROUP BY "Date"', $case_type);

    $results = $db->query($sql);

    $jsonData = array();
    while ($res = $results->fetchArray(1)) {
        array_push($jsonData, $res);
    }

    $global_daily_results = json_encode($jsonData);
    echo $global_daily_results;
}