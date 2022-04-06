export const drawCountryDaily = (jsonData) => {
  const ctx = document.getElementById(`country-daily`).getContext('2d');
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
