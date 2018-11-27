<?php require 'header.php'; ?>
  <style>
   #loading-img {
    background: url(loading.gif) center top no-repeat;
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
  </style>
  <!--Load the AJAX API-->
  <script src="https://www.gstatic.com/charts/loader.js"></script>
  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
  <script src="./js/chart.js"></script>
 </head>
 <body>
	 <div class="container">
		 <?php $btnActive[5]=" class='active'";?>
		 <?php require 'menu.php';?>
		 <div class="col-xs-6"><h1>BBQ: <span id="pit"></span></h1></div>
		 <div class="col-xs-6"><h1>Food: <span id="food"></span></h1></div>
		 <div class="col-xs-12"><h2><span id="when"></span></h2></div>
    	 <div class="col-xs-12">
			 <div class="loading"><div id="loading-img"></div></div>
			 <div id='chart_div'></div>
		 </div>
    	 <div class="col-xs-4">
			 <div id="toggleLines" style="display:none">
      		 	<input type='checkbox' id='showFood' checked />Food&nbsp;
			 	<input type='checkbox' id='showPit' checked />Pit
			 </div>
		</div>
		<div class="col-xs-8">
		    <?php
		 	class MyDB extends SQLite3 {
		 		function __construct() {
		 			$this->open('the.db');
		 		}
		 	}
		 	$database=new MyDB();
		 	if ($_COOKIE['cookid']) {
		 		$activeCook=$_COOKIE['cookid'];
		 	} else {
		 		$activeCook=$database->querySingle("SELECT cookid from activecook;");
		 	}
		         echo "<form id='selectCook' class='form-inline' style='display:none' action='editgraph' method='GET'>\n";
		         echo "<select id='cookid' name='id' class='form-control'>\n";
		 	if ($activeCook!='-1') {
		 		echo "     <option value='".$activeCook."' selected>Active Cook (#".$activeCook.")</option>";
		 	}
		         $query="SELECT id, start FROM cooks ORDER BY id DESC LIMIT 20";
		 	if ($result=$database->query($query)) {
		 		if ($activeCook!='-1') {
		 			$result->fetchArray(); //prime read to pass up active cook
		 		}
		 		while($row=$result->fetchArray()) {
		 			$t=strtotime($row['start']);
		 			echo "<option value='".$row['id']."'".($_GET['cook'] == $row['id'] ? ' selected' : '').">Cook #".$row['id']." - ".date('m',$t)."/".date('d',$t)."/".date('y',$t)." at ".date('h',$t).":".date('ia',$t)."</option>\n";
		 		}
		 	}
		     ?>
		     </select>
    <?php if ($_SESSION['auth']) : ?>
		<input type="submit" class="btn btn-default" value="Edit" />
	<?php endif; ?>
			</form>
			</div>
			</div>
		</div>
	</div>
 </body>
</html>
