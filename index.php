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
            display: none;
        }
    </style>

</head>

<body>
    <div class="container-fluid">
        <h1 class="text-center">2020年01/22至03/14新冠肺炎數據</h1>
    </div>
    <div class="container-lg">
        <div class="row world-map">
            <canvas id="myChart"></canvas>
        </div>
        <div class="row pt-5 details">
            <div id="country-data" class="mt-5">
                <div class="row px-4 justify-content-between">
                    <h4 class="col-4"></h4>
                    <button class="btn btn-primary col-3" id="back-to-global">回到全球圖表</button>    
                </div>
                <canvas id="country-daily" ></canvas>
            </div>
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
        import {drawWorldMap} from './js/worldMap.js';
        import {drawGlobalNumber, drawGlobalTop5, drawGlobalDaily} from './js/globalChart.js';
        import {drawCountryDaily} from './js/countryChart.js';

        let countryData = document.getElementById('country-data');
        let worldData = document.getElementById('global-data');

        //全球地圖
        const clickCountryEventName = 'clickCountry';
        let countryDailyChart=new Chart();
        document.addEventListener(clickCountryEventName,async(event)=>{
            const country_daily_results = await fetch(`./php/country-chart.php?country=${event.detail.countryName}`).then(response=>response.json());
            countryDailyChart.destroy();
            if(country_daily_results.length>0){
                countryDailyChart = drawCountryDaily(country_daily_results);
            }
            countryData.getElementsByTagName('h4')[0].innerHTML = event.detail.countryName;
            countryData.style.display = 'block';
            worldData.style.display = 'none';
        })
        drawWorldMap(<?= $world_map_results ?>, clickCountryEventName);
        
        

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

        //back to global button
        const btn = document.getElementById('back-to-global');
        btn.addEventListener('click',()=>{
            countryData.style.display='none';
            worldData.style.display='block';
        })
    </script>

</body>

</html>