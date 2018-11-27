<?php
	$sec = "30";
	require 'header.php';
    include_once('db.php');

        $db=Database::getInstance();
        $pdo=$db->getConnection();

	$activecook=Database::selectSingle("select cookid from activecook order by cookid desc limit 1;",$pdo);
	$cookID=$activecook['cookid'];
	if ($cookID < 0) {
		$activecook=Database::selectSingle("select id from cooks order by id desc limit 1;",$pdo);
		$cookID=$activecook['id'];
	}
		$single=Database::selectSingle("select probe1,probe2,time from readings where cookid=".$cookID." order by time desc limit 1;",$pdo);
		$probe1=$single['probe1'];
		$probe2=$single['probe2'];
		$time=$single['time'];
?>
  <script type='text/javascript' src='https://www.gstatic.com/charts/loader.js'></script>
  <script type='text/javascript'>
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
  </script>
	  <style>
		  #gauge_table td 
		  {
		      text-align:center; 
		      vertical-align:middle;
		  }
	  </style>
 </head>
 <body>
	 <div class="container">
		 <?php $btnActive[4]=" class='active'"; ?>
		 <?php require 'menu.php'; ?>
	 	<center>
  		  <table id='gauge_table'><tr>
  			<td><div id='food_div'></td><td></div><div id='pit_div'></div></td></tr></table>
  		  	<?= ($time ? 'Last updated: '.date('l, F jS, Y @ h:ia', strtotime($time)) : 'There isn\'t anything cooking right now.'); ?>
     	</center>
 	 </div>
 </body>
</html>
