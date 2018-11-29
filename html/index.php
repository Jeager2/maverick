<?php
require 'header.php';
?>
	<script src="./nosleep.js"></script>
	<script src="./js/alerts.js"></script>
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" 	integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
<?php

include_once('db.php');

$db  = Database::getInstance();
$pdo = $db->getConnection();

$activecookgauge = Database::selectSingle("select cookid from activecook order by cookid desc limit 1;", $pdo);
$cookID          = $activecookgauge['cookid'];
if ($cookID < 0) {
    $inactivecook = Database::selectSingle("select id from cooks order by id desc limit 1;", $pdo);
    $cookID       = $inactivecook['id'];
}
$single = Database::selectSingle("select probe1,probe2,time from readings where cookid=" . $cookID . " order by time desc limit 1;", $pdo);
$probe1 = $single['probe1'];
$probe2 = $single['probe2'];
$time   = $single['time'];
?>
    <!--Load the AJAX API-->
    <script src="https://www.gstatic.com/charts/loader.js"></script>
    <script src="./js/chart.js"></script>
    <script>
        google.charts.load('current', {'packages':['gauge']});
        google.charts.setOnLoadCallback(drawFoodChart);
        google.charts.setOnLoadCallback(drawPitChart);

        function drawFoodChart() {
          var data = google.visualization.arrayToDataTable([
            ['Label', 'Value'],
            ['Food', <?= $probe1 ?>],
          ]);

  	var foodOptions = {
  	  width: 180, height: 180,
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
  	  ['BBQ', <?= $probe2 ?>],
  	]);

          var pitOptions = {
            width: 180, height: 180,
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
	.input-group {
		margin-bottom: 3px;
	}
	.mwfront {
		min-width: 76px;
	}
     #loading-img {
      background: url(loading.gif);
	  background-repeat: no-repeat;
	  background-position: center -20px;
	  height: 100%;
     }
     .loading {
      position: absolute;
      top: 0;
      bottom: 0;
      left: 0;
      right: 0;
      z-index: 20;
     }
	 #leftColumn #roundwhite {
		 background-color: white;
		 border-radius: 10px;
		 margin-bottom: 3px;
		 min-height: 383px;
		 padding: 10px;
		 -moz-border-radius: 10px;
		 -webkit-border-radius: 10px;
	 }
	 #loadchart {
		 min-height: 300px;
	 }
	 #charttitle {
		 min-height: 63px;
	 }
	 #chartoptions {
		 min-height: 49px;
	 }
	 #rightColumn .btn-group {
		 margin-bottom: 15px;
	 }
	 #tempDiv {
		 text-align: center;
	 }
	 #tempDiv p {
		 margin-bottom: 0;
	 }
	 #tempDiv div {
		 background-color: white;
		 border-radius: 10px;
		 display: inline-block;
		 -moz-border-radius: 10px;
		 -webkit-border-radius: 10px;
		 margin: 15px auto;
		 min-height: 107px;
		 min-width: 160px;
		 padding: 10px;
		 /*text-align: center;*/
	 }
	 #tempDiv div span {
		 font-size: 40px;
		 font-weight: bold;
	 }
	 #tempDiv span:after {
		 content: "\00b0 F";
		 font-size: 16px;
		 font-weight: normal;
		 position: absolute;
		 vertical-align: super;
	 }
	 #gaugeDiv {
		 display: inline-block;
		 margin: 0 auto;
	 }
	 #pit_div {
		 min-height: 180px;
		 min-width: 180px;
	 }
	 #food_div {
		 min-height: 180px;
		 min-width: 180px;
	 }
	 #alertsDiv {
		 margin-bottom: 3px;
	 }
</style>
</head>
<body>
    <div class="container">
        <?php
$btnActive[0] = " class='active'";
?>
        <?php
require 'menu.php';
?>
        <!-- Main component for a primary marketing message or call to action -->
        <div class="jumbotron row">
            <div id="leftColumn" class="col-sm-9 col-xs-12">
                <div id="roundwhite">
					<div class="col-xs-12 text-center">
						<h2 style="display:inline-block"><span id="when"></span></h2>
						<h3 style="display:inline-block; position: absolute; right: 15px"><span id="live" class="label label-danger"></span></h3>
					</div>
                    <div id="loadchart" class="col-xs-12">
                        <div class="loading">
                            <div id="loading-img"></div>
                        </div>
                        <div id='chart_div'></div>
                    </div>
                </div>
                <div class="col-xs-4">
                    <div id="toggleLines" style="display:none">
                        <input type='checkbox' id='showFood' checked />Food&nbsp;
                        <input type='checkbox' id='showPit' checked />BBQ
                    </div>
                </div>
                <div class="col-xs-8 text-right">
                    <?php
class MyDB extends SQLite3
{
    function __construct()
    {
        $this->open('the.db');
    }
}
$databasecook = new MyDB();
if ($_COOKIE['cookid']) {
    $activeCook = $_COOKIE['cookid'];
} else {
    $activeCook = $databasecook->querySingle("SELECT cookid from activecook;");
}
?>
                    <div id="selectCook" class="form-group" style="display:none">
                        <form action="editgraph" class="form-inline" method="GET">
                            <select id="cookid" name="cook" class="form-control">
                            <?php
if ($activeCook != '-1') {
    echo "     <option value='" . $activeCook . "' selected>Active Cook (#" . $activeCook . ")</option>";
}
$query = "SELECT id, start FROM cooks ORDER BY id DESC LIMIT 20";
if ($result = $databasecook->query($query)) {
    if ($activeCook != '-1') {
        $result->fetchArray(); //prime read to pass up active cook
    }
    while ($row = $result->fetchArray()) {
        $t = strtotime($row['start']);
        echo "<option value='" . $row['id'] . "'" . ($_GET['cook'] == $row['id'] ? ' selected' : '') . ">Cook #" . $row['id'] . " - " . date('m', $t) . "/" . date('d', $t) . "/" . date('y', $t) . " at " . date('h', $t) . ":" . date('ia', $t) . "</option>\n";
    }
}
?>
                            </select>
                            <?php
if ($_SESSION['auth']):
?>
                            <input type="submit" class="btn btn-default" value="Edit" />
                            <?php
endif;
?>
                        </form>
                    </div>
                </div>
                <div id="note" class="col-xs-12">
                    <?php
$noteDB = new MyDB();
if ($activeCook != '-1') {
    $note = $noteDB->querySingle("SELECT note from cooks WHERE cookid='" . $activeCook . "';");
} else {
    $note = $noteDB->querySingle("SELECT note from cooks ORDER BY id DESC LIMIT 1;");
}
echo $note;
?>
                </div>
            </div>
            <div id="rightColumn" class="col-sm-3 col-xs-12 text-center">
                <div class="btn-group" data-toggle="buttons">
                    <label class="btn btn-sm btn-default<?= ($_SESSION['auth'] ? '' : ' active'); ?>">
                    <input type="radio" name="options" id="togText" <?= ($_SESSION['auth'] ? '' : 'checked ') ?>/> Text
                    </label>
                    <label class="btn btn-sm btn-default">
                    <input type="radio" name="options" id="togGauges" /> Gauges
                    </label>
                    <?php
if ($_SESSION['auth']):
?>
                    <label class="btn btn-sm btn-default active">
                    <input type="radio" name="options" id="togStart" checked /> Control
                    </label>
                    <?php
endif;
?>
                </div>
                <!-- Display of TEXT tab begins here -->
                <div id="tempDiv" style="display:<?= ($_SESSION['auth'] ? 'none' : 'block'); ?>">
                    <div>
                        <p>BBQ</p>
                        <span id="pit">&nbsp;&nbsp;&nbsp;&nbsp;</span>
                    </div>
                    <div>
                        <p>Food</p>
                        <span id="food">&nbsp;&nbsp;&nbsp;&nbsp;</span>
                    </div>
                </div><!-- Display of TEXT tab END here -->
                <div id="gaugeDiv" style="display:none"><!-- Display of GAUGES tab START here -->
                    <div id="pit_div"></div>
                    <div id="food_div"></div>
                </div>
                <!-- Display of CONTROL tab START here -->
                <div id="controlDiv">
                    <?php
if ($_SESSION['auth']):
    exec("pgrep maverick", $pids);
    if (empty($pids)) {
        $val           = 'Start New Cook';
        $btnClass      = 'btn btn-block btn-success';
        $showAlertsRow = 'display:block';
    } else {
        $val           = 'Stop Cook';
        $btnClass      = 'btn btn-block btn-danger';
        $showAlertsRow = 'display:none';
    }
    
    $database = new MyDB();
    
    $smokersList = null;
    $query       = "SELECT start,end,pitLow,pitHi,foodLow,foodHi,note FROM cooks ORDER BY id DESC LIMIT 1;";
    if ($result = $database->query($query)) {
        while ($row = $result->fetchArray()) {
            $pL    = $row['pitLow'];
            $pH    = $row['pitHi'];
            $fL    = $row['foodLow'];
            $fH    = $row['foodHi'];
            $desc  = $row['note'];
            $start = $row['start'];
            $end   = $row['end'];
        }
    }
    
    if (($database->querySingle('SELECT cookid FROM activecook')) > -1) {
        $keepAwake = "noSleep.enable(); allowedToSleep=false;";
    } else {
        $keepAwake = "noSleep.disable(); allowedToSleep=true;";
    }
    
    if (empty($pids)) {
        $query       = "SELECT * FROM smokers ORDER BY id DESC;";
        $smokersList = $database->query($query);
    }
?>
                    <form id="alertsForm" method="POST">
                        <div id="alertsDiv" style="<?= $showAlertsRow ?>">
                            <input type="hidden" name="p1" id="p1" value="clicked" />
                            <label for="smoker">Smoker <span class="glyphicon glyphicon-fire text-warning"></span></label>
                            <select class="form-control" name="smoker" id="smoker" style="margin-bottom:3px">
                                <?php
    if ($smokersList) {
?>
                                <?php
        while ($smokersRow = $smokersList->fetchArray()) {
?>
                                <option value=<?= $smokersRow['id'] ?>><?= htmlspecialchars($smokersRow['desc']) ?></option>
                                <?php
        }
?>
                                <?php
    }
?>
                                <?php
    $database->close();
?>
                            </select>
                            <label for="pitHi" class="sr-only">BBQ High</label>
                            <div class="input-group">
                                <div class="input-group-addon mwfront text-right">BBQ <span class="fas fa-arrow-up text-danger"></span></div>
                                <input type="number" class="form-control" name="pitHi" id="pitHi" min="1" max="500" <?= ($pH ? 'value="' . $pH . '" ' : ''); ?>placeholder="BBQ High" />
                                <div class="input-group-addon">&deg;F</div>
                            </div>
                            <label for="pitLow" class="sr-only">BBQ Low</label>
                            <div class="input-group">
                                <div class="input-group-addon mwfront text-right">BBQ <span class="fas fa-arrow-down text-primary"></span></div>
                                <input type="number" class="form-control" name="pitLow" id="pitLow" min="1" max="500" <?= ($pL ? 'value="' . $pL . '" ' : ''); ?>placeholder="BBQ Low" />
                                <div class="input-group-addon">&deg;F</div>
                            </div>
                            <label for="foodHi" class="sr-only">Food High</label>
                            <div class="input-group">
                                <div class="input-group-addon mwfront text-right">Food <span class="fas fa-arrow-up text-danger"></span></div>
                                <input type="number" class="form-control" name="foodHi" id="foodHi" min="1" max="500" <?= ($fH ? 'value="' . $fH . '" ' : ''); ?>placeholder="Food High" />
                                <div class="input-group-addon">&deg;F</div>
                            </div>
                            <label for="foodLow" class="sr-only">Food Low</label>
                            <div class="input-group">
                                <div class="input-group-addon mwfront text-right">Food <span class="fas fa-arrow-down text-primary"></span></div>
                                <input type="number" class="form-control" name="foodLow" id="foodLow" min="1" max="500" <?= ($fL ? 'value="' . $fL . '" ' : ''); ?>placeholder="Food Low" />
                                <div class="input-group-addon">&deg;F</div>
                            </div>
                            <label for="bbqNotes" class="sr-only">Description</label>
                            <textarea class="form-control" name="bbqNotes" id="bbqNotes" rows="3" placeholder="Description"></textarea>
                        </div>
                        <input class="<?= $btnClass ?>" type="submit" value="<?= $val ?>" id="toggleCook" />
                    </form>
                    <div class="row" id="silenceAlertDiv" style="display:none">
                        <input class="btn btn-danger" type="button" value="Silence" id="silenceAlert">
                    </div>
                    <div class="row">
                        <p id="alertType" class="h2 bg-danger"></p>
                    </div>
                    <?php
endif;
?>
                </div>
                <!-- /controlDiv -->
            </div>
            <!-- /rightColumn -->
        </div>
        <!-- /jumbotron -->
    </div>
    <!-- /container -->
    <?php
require 'footer.php';
?>
    <script>
        <?= $keepAwake ?>
        $(function(){
        	$('#cookid').change(function(){
        		$.ajax({
        			url: 'getnote.php',
        			type: 'POST',
        			data: {cookid: $(this).val()},
        			success: function(data) {
        				$('#note').html(data);
        			},
        		});
        	});
        	
        	$("#togText").change(function(){
        		$("#tempDiv").show("slow");
        		$("#gaugeDiv").hide();
        		$("#controlDiv").hide();
        	});
        
        	$("#togGauges").change(function(){
        		$("#gaugeDiv").fadeIn(500);
        		$("#tempDiv").hide();
        		$("#controlDiv").hide();
        	});
        
        	$("#togStart").change(function(){
        	    $("#controlDiv").show("slow");
        		$("#tempDiv").hide();
        		$("#gaugeDiv").hide();
        	});
        });
    </script>
</body>
</html>