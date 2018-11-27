  google.charts.load('current', {'packages':['gauge']});
  google.charts.setOnLoadCallback(drawFoodChart);
  google.charts.setOnLoadCallback(drawPitChart);

  function drawFoodChart() {
    var data = google.visualization.arrayToDataTable([
      ['Label', 'Value'],
      ['Food', <?=$probe1?>],
    ]);

var foodOptions = {
  width:200, height: 200,
  redFrom: 203, redTo: 250,
  yellowFrom: 130, yellowTo: 165,
  greenFrom: 165, greenTo: 203,
  minorTicks: 10, max:250, min:100, majorTicks:['100', '150', '200', '250']
};

    var chart = new google.visualization.Gauge(document.getElementById('food_div'));
    chart.draw(data, foodOptions);
  }

  function drawPitChart() {
var data = google.visualization.arrayToDataTable([
  ['Label', 'Value'],
  ['Pit', <?=$probe2?>],
]);

    var pitOptions = {
      width: 200, height: 200,
      redFrom: 300, redTo: 350,
  yellowFrom: 250, yellowTo: 300,
      greenFrom: 215, greenTo: 250,
      minorTicks: 10, max:350, min:100, majorTicks:['100', '150', '200', '250', '300', '350']
    };

var chart = new google.visualization.Gauge(document.getElementById('pit_div'));
chart.draw(data, pitOptions);
  }