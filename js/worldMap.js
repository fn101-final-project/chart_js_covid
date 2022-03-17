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
                display: false,
              },
              title: {
                display: true,
                text: 'COVID-19全球確診人口數',
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
            onHover: (evt, elems) => {
              evt.native.target.style.cursor = elems[0] ? 'pointer' : 'default';
            },
            onClick: (evt, elems) => {
              let country_name = '';
              // we assume only one country in elems
              if (elems[0]) {
                country_name = elems[0].element.feature.properties.name;
                // in-place
                let countryData = document.getElementById('country-data');
                let worldData = document.getElementById('global-data');
                countryData.firstElementChild.innerHTML = `Replace to ${country_name}`;
                countryData.style.display = 'block';
                worldData.style.display = 'none';
              }
            },
          },
        }
      );
    });
};
