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
    
    $jsondata = json_encode($stat);
?>


<!doctype html>
<html lang="en">
  <head>
    <title>COVID-19</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS v5.0.2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css"  integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://unpkg.com/chart.js@3.7.0/dist/chart.js"></script>
    <script src="https://unpkg.com/chartjs-chart-geo@3.7.1/build/index.umd.min.js"></script>
    <style>
        body {
            width: 100vw;
            height: 100vh;
        }
        .container {
            width: 100%;
        }
        #world_data{
            background-color: lightgrey;
            width: 100%;
            height: 700px;

        }
    </style>

  </head>
  <body>
      <div class="container">
          <div class="row">
          <h1 class="text-center">2020年01/22至/03/14新冠肺炎數據</h1>
            <canvas id="myChart"></canvas>
            
            <div id="world_data" class="mt-5">
                <!-- 寫好版型，預先invisible ，資料放好改visible -->
            </div>

          </div>
      </div>
      

        <script>
            let stat = <?php echo $jsondata ?>;


            function get_confirmed_by_contries(name) {
                return name in stat ? stat[name] : 0;
            }

            fetch('http://localhost/ChartJS/countries-50m.json').then((r) => r.json()).then((data) => {
            const countries = ChartGeo.topojson.feature(data, data.objects.countries).features;

            const chart = new Chart(document.getElementById("myChart").getContext("2d"), {
                type: 'choropleth',
                data: {
                    labels: countries.map((d) => d.properties.name),
                    datasets: [{
                        label: 'COVID-19全球確診人口數',
                        data: countries.map((d) => ({feature: d, value: get_confirmed_by_contries(d.properties.name)})),
                    }]
                },
                options: {
                    showOutline: true,
                    showGraticule: true,
                    plugins: {
                        legend: {
                            display: true
                        },
                    },
                    scales: {
                        xy: {
                            projection: 'equalEarth'
                        },
                        color: {
                            type: 'colorLogarithmic',
                            interpolate:'YlOrRd',
                            quantize: 50,
                            legend: {
                                position: 'bottom-right',
                                align: 'right',
                            },
                        },
                    },
                    onClick: (evt, elems) => {
                        
                        // we assume only one country in elems
                        let country_name = elems[0].element.feature.properties.name;
                        
                        // in-place
                        let world_data = document.getElementById('world_data');
                        world_data.innerHTML = '<div> Replace to ' + country_name + '</div>';
                    },
                    
                },
            });
        });
        </script>
       
    </body>

      
    <!-- Bootstrap JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
  </body>
</html>