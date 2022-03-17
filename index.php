<?php
    require_once("./php/world-map.php");
    require_once("./php/global-chart.php");
?>


<!doctype html>
<html lang="en">

<head>
    <title>COVID-19</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS v5.0.2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://unpkg.com/chart.js@3.7.0/dist/chart.js"></script>
    <script src="https://unpkg.com/chartjs-chart-geo@3.7.1/build/index.umd.min.js"></script>
    <style>
        /* body {
            width: 100vw;
            height: 100vh;
        } */

        /* .container {
            width: 100%;
        } */
        ol, ul {
	        list-style: none;
        }
        li {
            padding: 20px;
        }
        h2{
            font-size: 2.5rem;
        }
        .progress {
            background: none;
            border-radius: 0px;
        }

        .text-bottom {
            align-self: flex-end;
        }

        #country-data {
            background-color: lightgrey;
            width: 100%;
            height: 700px;

        }
    </style>

</head>

<body>
    <div class="container-fluid">
        <h1 class="text-center">2020年01/22至/03/14新冠肺炎數據</h1>
    </div>
    <div class="container-lg">
        <div class="row world-map">
            <canvas id="myChart"></canvas>
        </div>
        <div class="row details">
            <div id="country-data" class="mt-5"></div>
            <div id="global-data">
            <div class="row mx-3 my-5 Confirmed">
                <div class="col-md-5">
                    <canvas id="global-daily-Confirmed" ></canvas>
                </div>
            </div>
            <div class="row mx-3 my-5 Deaths">
                <div class="col-md-5">
                    <canvas id="global-daily-Deaths" ></canvas>
                </div>
            </div>
            <div class="row mx-3 my-5 Recovered">
                <div class="col-md-5">
                    <canvas id="global-daily-Recovered" ></canvas>
                </div>
            </div>   
        </div> 
        </div>
    </div>

    <!-- Bootstrap JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"
        integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"
        integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF"
        crossorigin="anonymous"></script>
    <script type="module">
        import drawWorldMap from './js/worldMap.js';
        import {drawGlobalNumber, drawGlobalTop5, drawGlobalDaily} from './js/globalChart.js';

        //全球地圖
        drawWorldMap(<?= $world_map_results ?>);

        //全球確診
        drawGlobalNumber(<?= $global_number_results; ?>,'Confirmed');
        drawGlobalTop5(<?= $global_top5_confirmed_results; ?>,'Confirmed');
        drawGlobalDaily(<?= $global_daily_confirmed_results; ?>,'Confirmed');

        //全球死亡
        drawGlobalNumber(<?= $global_number_results; ?>,'Deaths');
        drawGlobalTop5(<?= $global_top5_deaths_results; ?>,'Deaths');
        drawGlobalDaily(<?= $global_daily_deaths_results; ?>,'Deaths');

        //全球康復
        drawGlobalNumber(<?= $global_number_results; ?>,'Recovered');
        drawGlobalTop5(<?= $global_top5_recovered_results; ?>,'Recovered');
        drawGlobalDaily(<?= $global_daily_recovered_results; ?>,'Recovered');
    </script>

</body>

</html>