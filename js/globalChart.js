export const drawGlobalNumber = (jsonData, caseType) => {
  const caseDiv = document.getElementsByClassName(caseType);
  const data = jsonData.find((data) => data.Case_Type === caseType);
  const globalNumberDiv = document.createElement('div');
  globalNumberDiv.classList.add('text-center', 'col-sm-4', 'col-md-2', 'pt-2');
  globalNumberDiv.innerHTML = `
    <h2>${data.Cases}</h2>
    <small>${data.Case_Type}</small>`;
  caseDiv[0].insertBefore(globalNumberDiv, caseDiv[0].firstChild);
};

//////////////////////////////////////////////////////////////

const colorMap = {
  '#FD6405': '',
  '#4C7835': '',
  '#013283': '',
  '#85C453': '',
  '#D40054': '',
  '#70EDDA': '',
};

const getCountryColor = (country) => {
  const colorIndex = Object.values(colorMap).indexOf(country);
  if (colorIndex === -1) {
    const firstEmptyIndex = Object.values(colorMap).indexOf('');
    const firstEmptyColor = Object.keys(colorMap)[firstEmptyIndex];
    colorMap[firstEmptyColor] = country;
    return firstEmptyColor;
  }
  return Object.keys(colorMap)[colorIndex];
};

export const drawGlobalTop5 = (jsonData, caseType) => {
  const caseDiv = document.getElementsByClassName(caseType);
  const globalTop5Div = document.createElement('div');
  globalTop5Div.classList.add('col-sm-8', 'col-md-5', 'ps-5', 'pb-4');
  const maxNumber = Math.max(...jsonData.map((data) => data.Case_Total));
  jsonData.map((data) => {
    const country = document.createElement('div');
    country.classList.add('row', 'pb-1', 'gx-1');
    country.innerHTML = `
      <div class="col-8">
          <h6 class="mb-1">${data.Country}</h6>
          <div class="progress">
              <div class="progress-bar" role="progressbar" style="background:${getCountryColor(
                data.Country
              )}; width: ${
      (data.Case_Total / maxNumber) * 100
    }%" aria-valuenow="${data.Case_Total}"
                  aria-valuemin="0" aria-valuemax="${maxNumber}"></div>
          </div>
      </div>
      <div class="col-4 d-flex">
          <h6 class="m-0 text-bottom">${data.Case_Total}</h6>
      </div>`;
    globalTop5Div.appendChild(country);
  });
  caseDiv[0].insertBefore(globalTop5Div, caseDiv[0].firstChild.nextSibling);
};

//////////////////////////////////////////////////////////////

export const drawGlobalDaily = (jsonData, caseType) => {
  const ctx = document
    .getElementById(`global-daily-${caseType}`)
    .getContext('2d');
  const labels = jsonData.map((el) => el.Date);
  const data = {
    labels: labels,
    datasets: [
      {
        label: '人數',
        data: jsonData.map((el) => el.Cases),
        borderColor: 'rgb(255, 99, 132)',
        backgroundColor: 'rgba(201, 203, 207, 0.3)',
      },
    ],
  };

  let delayed;
  const config = {
    type: 'bar',
    data: data,
    options: {
      elements: {
        bar: {
          borderWidth: 2,
        },
      },
      responsive: true,
      plugins: {
        legend: {
          position: 'right',
          display: false,
        },
        title: {
          display: false,
          text: `${caseType} Cases`,
        },
      },
      animation: {
        onComplete: () => {
          delayed = true;
        },
        delay: (context) => {
          let delay = 0;
          if (
            context.type === 'data' &&
            context.mode === 'default' &&
            !delayed
          ) {
            delay = context.dataIndex * 30 + context.datasetIndex * 10;
          }
          return delay;
        },
      },
      scales: {
        x: {
          grid: {
            display: false,
          },
        },
      },
    },
  };
  return new Chart(ctx, config);
};
