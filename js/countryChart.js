export const drawCountryDaily = (jsonData, ctx) => {
  const data = {
    labels: jsonData
      .filter((el) => el.Case_Type === 'Confirmed')
      .map((el) => el.Date),
    datasets: [
      {
        label: 'Confirmed',
        data: jsonData
          .filter((el) => el.Case_Type === 'Confirmed')
          .map((el) => el.Cases),
        borderColor: 'rgb(239, 71, 111)',
        backgroundColor: 'rgba(239, 71, 111, 0.3)',
      },
      {
        label: 'Deaths',
        data: jsonData
          .filter((el) => el.Case_Type === 'Deaths')
          .map((el) => el.Cases),
        borderColor: 'rgb(6, 214, 160)',
        backgroundColor: 'rgba(6, 214, 160, 0.3)',
      },
      {
        label: 'Recovered',
        data: jsonData
          .filter((el) => el.Case_Type === 'Recovered')
          .map((el) => el.Cases),
        borderColor: 'rgb(17, 138, 178)',
        backgroundColor: 'rgba(17, 138, 178, 0.3)',
      },
    ],
  };

  const config = {
    type: 'line',
    data: data,
    options: {
      responsive: true,
      interaction: {
        mode: 'index',
        intersect: false,
      },
      stacked: false,
      plugins: {
        legend: {
          position: 'top',
          display: true,
        },
        title: {
          display: false,
          text: '人數',
        },
        tooltip: {
          position: 'nearest',
        },
      },
      scales: {
        y: {
          type: 'linear',
          display: true,
          position: 'right',
          title: {
            text: '人數',
            display: true,
          },
          ticks: {
            precision: 0,
          },
        },
      },
    },
  };

  return new Chart(ctx, config);
};

const generateColor = (number) => {
  const hue = number * 137.508;
  return `hsl(${hue},95%,75%)`;
};

export const drawProvinceConfirmed = (jsonData, ctx) => {
  const labels = jsonData
    .map((el) => el.Province)
    .filter((el, index, arr) => arr.indexOf(el) === index);
  const dataPoints = labels
    .map((province) =>
      jsonData
        .filter((el) => el.Province === province)
        .map((el) => Number(el.Cases))
    )
    .map((el) => Math.max(...el));

  const backgroundColors = [];
  for (let i = 0; i < labels.length; i++) {
    backgroundColors.push(generateColor(i + 1));
  }
  const data = {
    labels: labels,
    datasets: [
      {
        label: '確診人數',
        data: dataPoints,
        backgroundColor: backgroundColors,
      },
    ],
  };

  const config = {
    type: 'pie',
    data: data,
    plugins: [ChartDataLabels],
    options: {
      responsive: true,
      plugins: {
        legend: {
          position: 'top',
          display: false,
        },
        title: {
          display: true,
          text: '各城市累積確診人數',
        },
        datalabels: {
          backgroundColor: function (context) {
            return context.dataset.backgroundColor;
          },
          borderColor: 'white',
          borderRadius: 25,
          borderWidth: 2,
          color: 'white',
          display: function (context) {
            var dataset = context.dataset;
            var count = dataset.data.length;
            var value = dataset.data[context.dataIndex];
            var percentage =
              (value / dataset.data.reduce((a, b) => a + b, 0)) * 100;
            return percentage > 5;
          },
          font: {
            weight: 'bold',
          },
          padding: 6,
          formatter: function (value, context) {
            return context.chart.data.labels[context.dataIndex];
          },
        },
      },
    },
  };

  return new Chart(ctx, config);
};

export const drawProvinceConfirmedTop5 = (dataPoints, ctx) => {
  const labels = dataPoints[0].map((el) => el.Province);
  const data = {
    labels: labels,
    datasets: [
      {
        label: '前五大確診人數城市',
        data: dataPoints[0].map((el) => el.Cases),
        borderColor: 'rgb(255, 99, 132)',
        backgroundColor: 'rgba(201, 203, 207, 0.3)',
      },
    ],
  };
  const config = {
    type: 'bar',
    data: data,
    options: {
      indexAxis: 'y',
      elements: {
        bar: {
          borderWidth: 2,
        },
      },
      responsive: true,

      plugins: {
        legend: {
          display: false,
          position: 'bottom',
        },
        title: {
          display: true,
          text: '累積確診人數',
        },
      },
      scales: {
        x: {
          ticks: {
            precision: 0,
          },
        },
      },
    },
  };

  return new Chart(ctx, config);
};
