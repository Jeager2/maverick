<?php
require 'header.php';
	include_once('db.php');

	$db=Database::getInstance();
	$pdo=$db->getConnection();

	if (isset($_POST["deleteCook"])) {
		$cookid=$_POST['deleteCook'];
		$single=Database::update("delete from cooks where id=".$cookid,$pdo);
		$single=Database::update("delete from readings where cookid=".$cookid,$pdo);
	}

	$results=Database::select("SELECT * FROM cooks ORDER BY id DESC",$pdo);
?>
  <script type="text/javascript">
   $(function() {
	$('[id^=deleteCook]').hide();

        $('[data-toggle=confirmation]').confirmation({
		rootSelector: '[data-toggle=confirmation]',
	});

	$('[id^=deleteCook]').click(function() {
		$('#cookRow'+$(this).val()).remove();

		$.ajax({
			url:'cooks.php',
				type:'POST',
				data: 'deleteCook='+$(this).val(),
		});
	});

	$('[id^=cookRow]').hover(function() {
		$('#deleteCook'+$(this).attr('id').match(/\d+/)).toggle();
	});
   });
  </script>
<style>
	td.desc {
		position: relative;
	}
	.desc:before {
		content: '';
		display: inline-block;
	}
	td.desc span {
		white-space: nowrap; 
		overflow: hidden;
		text-overflow: ellipsis;
		position: absolute;
		left: 0;
		right: 0;
	   }
	</style>
 </head>
 <body>
  <div class="container">
   <?php $btnActive[3]=" class='active'";?>
   <?php require 'menu.php';?>
   <!-- Main component for a primary marketing message or call to action -->
   <div class="jumbotron">
    <h2>Cooks</h2>
    <table class="table table-hover table-sm">
     <thead><tr><th class="col-md-2">Date</th><th class="col-md-9">Description</th><th class="col-md-1">&nbsp;</th></tr></thead>
     <?php  foreach ($results as $row) { 
		 $t = strtotime($row['start']); ?>
      <tbody>
       <tr height=40 id="cookRow<?=$row['id']?>">
        <td><?=date('m',$t)."/".date('d',$t)."/".date('y',$t)." ".date('h',$t).":".date('ia',$t)?></td>
		<td class="desc"><span><?=$row['note']?></span></td>
        <td align=right>
	<?php if ($_SESSION['auth']) : ?>
         <button type="button" class="btn btn-xs btn-danger" data-toggle="confirmation" data-singleton="true" data-popout="true" data-btn-ok-class="btn-xs btn-danger" data-placement="left" data-title="Delete '<?=$row['start']?>'?" id="deleteCook<?=$row['id']?>" style="display:none" value=<?=$row['id']?>>
          <span class="glyphicon glyphicon-remove"></span>
         </button>
	<?php else: ?>
		<form action="graphs" method="GET">
			<input type="hidden" name="cook" value="<?=$row['id']?>" />
			<input type="submit" class="btn btn-xs btn-default" value="Select" />
		</form>
	<?php endif; ?>
        </td>
       </tr>
      </tbody>
     <?php  } ?>
    </table>
   </div>
  </div> <!-- /container -->
  <?php require 'footer.php';?>
 </body>
</html>

