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
    <title>test</title>
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
    </style>

  </head>
  <body>
        <canvas id="myChart"></canvas>

        <script>
            let stat = <?php echo $jsondata ?>;
            // stat = {
            //     'Taiwan': 3
            // }

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
                        label: 'Countries',
                        data: countries.map((d) => ({feature: d, value: get_confirmed_by_contries(d.properties.name)})),
                    }]
                },
                options: {
                    showOutline: true,
                    showGraticule: true,
                    plugins: {
                        legend: {
                        display: false
                        },
                    },
                    scales: {
                        xy: {
                        projection: 'equalEarth'
                        }
                    }
                }
            });
        });
        </script>
    </body>

      
    <!-- Bootstrap JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
  </body>
</html>