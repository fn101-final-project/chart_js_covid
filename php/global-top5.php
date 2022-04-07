<?php

$case_type = $_GET['case-type'];

if (isset($case_type)) {
    require_once("sqlite.php");

    $sql = sprintf('SELECT `Country/Region` AS Country,Case_Type,SUM(Difference) AS Case_Total 
    FROM `covid-19` 
    WHERE Case_Type="%s"
    GROUP BY Country
    ORDER BY Case_Total DESC
    LIMIT 5', $case_type);

    $results = $db->query($sql);

    $jsonData = array();
    while ($res = $results->fetchArray(1)) {
        array_push($jsonData, $res);
    }

    $global_top5_results = json_encode($jsonData);
    echo $global_top5_results;
}
