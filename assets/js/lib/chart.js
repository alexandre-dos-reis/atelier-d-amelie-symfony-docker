import Chart from "chart.js/auto";

const stringToArray = (string, separator) => {
  const array = string.split(separator);
  return array.splice(0, array.length - 1);
};

const multCanvas = document.querySelectorAll(".chart");

multCanvas.forEach((canvas) => {
  // Convert HTML attributes string data to array data
  const labels = stringToArray(canvas.getAttribute("labels"), ";");
  const datas = stringToArray(canvas.getAttribute("datas"), ";");

  const ctx = canvas.getContext("2d");
  const myChart = new Chart(ctx, {
    type: canvas.getAttribute('type'),
    data: {
      labels,
      datasets: [
        {
          label: canvas.getAttribute('label'),
          data: datas,
          backgroundColor: [
            "rgba(255, 99, 102, 0.2)",
            "rgba(54, 162, 235, 0.2)",
            "rgba(255, 206, 86, 0.2)",
            "rgba(75, 192, 192, 0.2)",
            "rgba(153, 102, 255, 0.2)",
            "rgba(153, 102, 0, 0.2)",
            "rgba(153, 0, 255, 0.2)",
            "rgba(0, 102, 255, 0.2)",
            "rgba(50, 50, 255, 0.2)",
            "rgba(180, 180, 64, 0.2)",
          ],
          borderColor: [
            "rgba(255, 99, 132, 1)",
            "rgba(54, 162, 235, 1)",
            "rgba(255, 206, 86, 1)",
            "rgba(75, 192, 192, 1)",
            "rgba(153, 102, 255, 1)",
            "rgba(153, 102, 0, 1)",
            "rgba(153, 0, 255, 1)",
            "rgba(0, 102, 255, 1)",
            "rgba(50, 50, 255, 1)",
            "rgba(180, 180, 64, 1)",
          ],
          borderWidth: 1.5,
        },
      ],
    },
    options: {
      indexAxis: canvas.getAttribute('indexAxis'),
      responsive: canvas.getAttribute('responsive') === 'true' ? true : false,
      scales: {
        y: {
          beginAtZero: true,
        },
      },
    },
  });
});
