<!-- Static navbar -->
<nav class="navbar navbar-default">
<div class="container-fluid">
  <div class="navbar-header">
    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
      <span class="sr-only">Toggle navigation</span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
    </button>
    <a class="navbar-brand" href="/">Alex.bbq</a>
  </div>
  <div id="navbar" class="navbar-collapse collapse">
    <ul class="nav navbar-nav">
      <li<?=$btnActive[0]?>><a href="./">Home</a></li>
	  <li<?=$btnActive[3]?>><a href="/cooks">Cooks</a></li>
      <li<?=$btnActive[4]?>><a href="/gauges">Gauges</a></li>
      <li<?=$btnActive[5]?>><a href="/graphs">Graphs</a></li>
	  <?php if ($_SESSION['auth']) : ?>
	      <li<?=$btnActive[2]?>><a href="/smokers">Smokers</a></li>
		  <li<?=$btnActive[1]?>><a href="/settings">Settings</a></li>
	  <?php endif; ?>
    </ul>
    <div class="nav navbar-nav navbar-right">
		<?php if ($_SESSION['auth']) : ?>
			<form method="GET">
			<input type="hidden" name="action" value="logout" />	
			<input type="submit" class="btn btn-default" value="Log Out" />
			</form>
		<?php else : ?>
			<form class="form-inline" method="POST">
			<input name="username" type="text" class="form-control" placeholder="Username" size="15" maxlength="40" />
			<input name="password" type="password" class="form-control" placeholder="Password" size="15" maxlength="40" />
			<input type="submit" class="btn btn-default" value="Login" />
			</form>
		<?php endif; ?>
	</div>
  </div><!--/.nav-collapse -->
</div><!--/.container-fluid -->
</nav>
