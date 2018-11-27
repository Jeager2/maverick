<?php
require 'header.php';
if ($_SESSION['auth']) :
	include_once('db.php');
	$db=Database::getInstance();
	$pdo=$db->getConnection();

	if (isset($_POST["setAlerts"])) {
		$pL=$_POST['pitLow'];
		$pH=$_POST['pitHi'];
		$fL=$_POST['foodLow'];
		$fH=$_POST['foodHi'];
		$email=$_POST['alertEmail'];
		$query="update cooks set pitLow='".$pL."',pitHi='".$pH."',foodLow='".$fL."',foodHi='".$fH."',email='".$email."' where id=".$_COOKIE['cookID'].";";
	        $single=Database::update($query,$pdo);
	} else {
	        $row=Database::selectSingle('SELECT pitLow,pitHi,foodLow,foodHi,email FROM cooks WHERE id='.$_COOKIE['cookID'],$pdo);
		$pL=$row['pitLow'];
		$pH=$row['pitHi'];
		$fL=$row['foodLow'];
		$fH=$row['foodHi'];
		$email=$row['email'];
	}
endif;
?>
 </head>
 <body>
  <div class="container">
   <?php $btnActive[1]=" class='active'";?>
   <?php require 'menu.php';?>
   <!-- Main component for a primary marketing message or call to action -->
   <div class="jumbotron">
<?php if ($_SESSION['auth']) : ?>
    <h2>Settings</h2>
     <div class="row">
      <form action="alerts.php" method="post">
       <div class="form-group">
        <div class="col-sm-2 col-xs-4">
         <label for="pitLow">Pit Low:</label><input type="number" class="form-control" name="pitLow" id="pitLow" min="1" max="500" value=<?=$pL?>>
         <label for="pitHigh">Pit High:</label><input type="number" class="form-control" name="pitHi" id="pitHi" min="1" max="500" value=<?=$pH?>>
         <label for="foodLow">Food Low:</label><input type="number" class="form-control" name="foodLow" id="foodLow" min="1" max="500" value=<?=$fL?>>
         <label for="foodHigh">Food High:</label><input type="number" class="form-control" name="foodHi" id="foodHi" min="1" max="500" value=<?=$fH?>><br/>
         <label for="alertEmail">To:</label><input type="email" class="form-control" name="alertEmail" id="alertEmail" value=<?=$email?>><br/>
         <input class="btn btn-lg btn-success" type="submit" value="Set Alerts" name="setAlerts" id="setAlerts">
        </div>
       </div>
      </form>
     </div>
<?php else: ?>
	<h3>Sorry, you need to log in to access this page.</h3>
<?php endif; ?>
    </div>
   </div> <!-- /container -->
   <?php require 'footer.php';?>
 </body>
</html>

