export default (stat) => {
  function get_confirmed_by_contries(name) {
    return name in stat ? stat[name] : 0;
  }

  fetch('./data/countries-50m.json')
    .then((r) => r.json())
    .then((data) => {
      const countries = ChartGeo.topojson.feature(
        data,
        data.objects.countries
      ).features;

      const chart = new Chart(
        document.getElementById('myChart').getContext('2d'),
        {
          type: 'choropleth',
          data: {
            labels: countries.map((d) => d.properties.name),
            datasets: [
              {
                label: 'COVID-19全球確診人口數',
                data: countries.map((d) => ({
                  feature: d,
                  value: get_confirmed_by_contries(d.properties.name),
                })),
              },
            ],
          },
          options: {
            showOutline: true,
            showGraticule: true,
            plugins: {
              legend: {
                display: true,
              },
            },
            scales: {
              xy: {
                projection: 'equalEarth',
              },
              color: {
                type: 'colorLogarithmic',
                interpolate: 'YlOrRd',
                quantize: 50,
                legend: {
                  position: 'bottom-right',
                  align: 'right',
                },
              },
            },
            onClick: (evt, elems) => {
              let country_name = '';
              // we assume only one country in elems
              if (elems[0]) {
                country_name = elems[0].element.feature.properties.name;
                // in-place
                let world_data = document.getElementById('country-data');
                world_data.innerHTML =
                  '<div> Replace to ' + country_name + '</div>';
              }
            },
          },
        }
      );
    });
};
