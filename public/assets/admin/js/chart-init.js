"use strict";
if ($('#lineChart').length > 0) {
  var ctx = document.getElementById('lineChart').getContext('2d');
  var gradientFill = ctx.createLinearGradient(0, 0, 0, 300);
  gradientFill.addColorStop(0, 'rgba(37, 99, 235, 0.35)');
  gradientFill.addColorStop(1, 'rgba(37, 99, 235, 0.0)');

  var myLineChart = new Chart(ctx, {
    type: 'line',
    data: {
      labels: months,
      datasets: [{
        label: Monthly_Income,
        borderColor: "#2563EB",
        pointBorderColor: "#FFFFFF",
        pointBackgroundColor: "#2563EB",
        pointBorderWidth: 2,
        pointHoverRadius: 6,
        pointHoverBorderWidth: 2,
        pointRadius: 5,
        backgroundColor: gradientFill,
        fill: true,
        borderWidth: 3,
        lineTension: 0.4,
        data: inTotals
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      legend: {
        display: false
      },
      scales: {
        xAxes: [{
          gridLines: {
            display: false,
            drawBorder: false
          },
          ticks: {
            fontColor: "#94A3B8",
            fontFamily: "Plus Jakarta Sans"
          }
        }],
        yAxes: [{
          gridLines: {
            color: "rgba(226, 232, 240, 0.5)",
            zeroLineColor: "transparent",
            drawBorder: false
          },
          ticks: {
            fontColor: "#94A3B8",
            fontFamily: "Plus Jakarta Sans"
          }
        }]
      },
      tooltips: {
        backgroundColor: "#0F172A",
        titleFontFamily: "Plus Jakarta Sans",
        bodyFontFamily: "Plus Jakarta Sans",
        cornerRadius: 10,
        xPadding: 12,
        yPadding: 12
      }
    }
  });
}

if ($('#usersChart').length > 0) {
  var ctx2 = document.getElementById('usersChart').getContext('2d');
  var gradientFill2 = ctx2.createLinearGradient(0, 0, 0, 300);
  gradientFill2.addColorStop(0, 'rgba(124, 58, 237, 0.35)');
  gradientFill2.addColorStop(1, 'rgba(124, 58, 237, 0.0)');

  var myUsersChart = new Chart(ctx2, {
    type: 'line',
    data: {
      labels: months,
      datasets: [{
        label: Monthly_Premium_Users,
        borderColor: "#7C3AED",
        pointBorderColor: "#FFFFFF",
        pointBackgroundColor: "#7C3AED",
        pointBorderWidth: 2,
        pointHoverRadius: 6,
        pointHoverBorderWidth: 2,
        pointRadius: 5,
        backgroundColor: gradientFill2,
        fill: true,
        borderWidth: 3,
        lineTension: 0.4,
        data: userTotals
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      legend: {
        display: false
      },
      scales: {
        xAxes: [{
          gridLines: {
            display: false,
            drawBorder: false
          },
          ticks: {
            fontColor: "#94A3B8",
            fontFamily: "Plus Jakarta Sans"
          }
        }],
        yAxes: [{
          gridLines: {
            color: "rgba(226, 232, 240, 0.5)",
            zeroLineColor: "transparent",
            drawBorder: false
          },
          ticks: {
            fontColor: "#94A3B8",
            fontFamily: "Plus Jakarta Sans"
          }
        }]
      },
      tooltips: {
        backgroundColor: "#0F172A",
        titleFontFamily: "Plus Jakarta Sans",
        bodyFontFamily: "Plus Jakarta Sans",
        cornerRadius: 10,
        xPadding: 12,
        yPadding: 12
      }
    }
  });
}
