import { drawWorldMap } from './js/worldMap.js';
import {
  drawGlobalNumber,
  drawGlobalTop5,
  drawGlobalDaily,
} from './js/globalChart.js';
import {
  drawCountryDaily,
  drawProvinceConfirmed,
  drawProvinceConfirmedTop5,
} from './js/countryChart.js';

(async () => {
  let countryData = document.getElementById('country-data');
  let worldData = document.getElementById('global-data');

  //全球地圖
  const world_map_results = await fetch('./php/world-map.php').then(
    (response) => response.json()
  );
  const clickCountryEventName = 'clickCountry';
  drawWorldMap(world_map_results, clickCountryEventName);

  //全球數字
  const global_number_results = await fetch('./php/global-number.php').then(
    (response) => response.json()
  );

  //全球確診
  drawGlobalNumber(global_number_results, 'Confirmed');

  const global_top5_confirmed_results = await fetch(
    './php/global-top5.php?case-type=Confirmed'
  ).then((response) => response.json());
  drawGlobalTop5(global_top5_confirmed_results, 'Confirmed');

  const global_daily_confirmed_results = await fetch(
    './php/global-daily.php?case-type=Confirmed'
  ).then((response) => response.json());
  const globalDailyConfirmedCtx = document
    .getElementById('global-daily-confirmed')
    .getContext('2d');
  drawGlobalDaily(
    global_daily_confirmed_results,
    'Confirmed',
    globalDailyConfirmedCtx
  );

  //全球死亡
  drawGlobalNumber(global_number_results, 'Deaths');

  const global_top5_deaths_results = await fetch(
    './php/global-top5.php?case-type=Deaths'
  ).then((response) => response.json());
  drawGlobalTop5(global_top5_deaths_results, 'Deaths');

  const global_daily_deaths_results = await fetch(
    './php/global-daily.php?case-type=Deaths'
  ).then((response) => response.json());
  const globalDailyDeathsCtx = document
    .getElementById('global-daily-deaths')
    .getContext('2d');
  drawGlobalDaily(global_daily_deaths_results, 'Deaths', globalDailyDeathsCtx);

  //全球康復
  drawGlobalNumber(global_number_results, 'Recovered');

  const global_top5_recovered_results = await fetch(
    './php/global-top5.php?case-type=Recovered'
  ).then((response) => response.json());
  drawGlobalTop5(global_top5_recovered_results, 'Recovered');

  const global_daily_recovered_results = await fetch(
    './php/global-daily.php?case-type=Recovered'
  ).then((response) => response.json());
  const globalDailyRecoveredCtx = document
    .getElementById('global-daily-recovered')
    .getContext('2d');
  drawGlobalDaily(
    global_daily_recovered_results,
    'Recovered',
    globalDailyRecoveredCtx
  );

  //單一國家
  const countryDailyCtx = document
    .getElementById(`country-daily`)
    .getContext('2d');
  let countryDailyChart = new Chart(countryDailyCtx);
  const provinceConfirmedCtx = document
    .getElementById(`province-confirmed`)
    .getContext('2d');
  let provinceConfirmedChart = new Chart(provinceConfirmedCtx);
  const provinceConfirmedTop5Ctx = document
    .getElementById(`province-confirmed-top5`)
    .getContext('2d');
  let provinceConfirmedTop5Chart = new Chart(provinceConfirmedTop5Ctx);
  let chartAnimation;

  //單一國家-點擊後
  document.addEventListener(clickCountryEventName, async (event) => {
    worldData.classList.add('hidden');
    countryData.classList.remove('hidden');
    countryData.getElementsByTagName('h4')[0].innerHTML =
      event.detail.countryName;

    const country_daily_results = await fetch(
      `./php/country-daily.php?country=${event.detail.countryName}`
    ).then((response) => response.json());

    const country_province_results = await fetch(
      `./php/province-confirmed.php?country=${event.detail.countryName}`
    ).then((response) => response.json());

    //國家折線圖
    countryDailyChart.destroy();
    if (country_daily_results.length > 0) {
      countryDailyChart = drawCountryDaily(
        country_daily_results,
        countryDailyCtx
      );
    }

    //城市圖
    provinceConfirmedChart.destroy();
    provinceConfirmedTop5Chart.destroy();
    if (chartAnimation) clearInterval(chartAnimation);
    document.getElementById('country-total').innerText = null;

    if (country_province_results.length > 0) {
      //城市圓餅圖
      provinceConfirmedChart = drawProvinceConfirmed(
        country_province_results,
        provinceConfirmedCtx
      );

      //城市動態圖
      const date = country_province_results
        .map((el) => el.Date)
        .filter((el, index, arr) => arr.indexOf(el) === index);
      const provinceData = date
        .map((date) =>
          country_province_results
            .filter((el) => el.Date === date)
            .map((el) => {
              return {
                ...el,
                Cases: Number(el.Cases),
              };
            })
        )
        .map((el) => el.sort((a, b) => b.Cases - a.Cases).slice(0, 5));
      const countryData = date
        .map((date) =>
          country_province_results
            .filter((el) => el.Date === date)
            .map((el) => {
              return {
                ...el,
                Cases: Number(el.Cases),
              };
            })
        )
        .map((el) => {
          const caseArr = el.map((el) => el.Cases);
          return {
            Date: el[0].Date,
            Cases: caseArr.reduce((a, b) => a + b, 0),
          };
        });

      provinceConfirmedTop5Chart = drawProvinceConfirmedTop5(
        provinceData,
        provinceConfirmedTop5Ctx
      );

      //動態更新
      chartAnimation = updateProvinceConfirmedTop5(
        provinceConfirmedTop5Chart,
        provinceData,
        countryData
      );
    }

    countryData.scrollIntoView({
      behavior: 'smooth',
    });
  });

  //回到全球圖表
  const btn = document.getElementById('back-to-global');
  btn.addEventListener('click', () => {
    worldData.classList.remove('hidden');
    countryData.classList.add('hidden');
    window.scrollTo(0, 0);
  });

  //動態更新方法
  const updateProvinceConfirmedTop5 = (chart, provinceData, countryData) => {
    let counter = 0;
    const i = setInterval(() => {
      document.getElementById('country-total').innerText =
        countryData[counter].Cases;

      chart.options.plugins.title.text =
        countryData[counter].Date + ' 累積確診人數';
      chart.data.labels = provinceData[counter].map((el) => el.Province);
      chart.data.datasets[0].data = provinceData[counter].map((el) => el.Cases);
      chart.update();
      counter++;
      if (counter === provinceData.length) {
        counter = 0;
      }
    }, 1000);
    return i;
  };
})();
