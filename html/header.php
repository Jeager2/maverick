<?php
// Login handler
session_start();
if (!empty($_POST)) {
    if (isset($_POST['username']) && isset($_POST['password'])) {
		$hasheduser  = file_get_contents("../bbq_user.txt"); // this is our stored user
		$hashedpass  = file_get_contents("../bbq_pass.txt"); // and our stored password
    		
    	// Verify user password and set $_SESSION
    	if ((password_verify($_POST['username'], $hasheduser)) && (password_verify($_POST['password'], $hashedpass))) {
    		$_SESSION['auth'] = true;
    	}
    }
} elseif ($_GET['action'] == 'logout') {
	session_destroy();
	header("Location: ".parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
  	<!-- this is the header.php code -->
	<meta http-equiv="refresh" content="1200;URL='./'">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <title>ET-732 Smokers</title>

    <!-- Bootstrap core CSS -->
    <link href="./css/bootstrap.min.css" rel="stylesheet">

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="./css/ie10-viewport-bug-workaround.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="./css/navbar.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script   src="https://code.jquery.com/jquery-3.1.0.js"   integrity="sha256-slogkvB1K3VOkzAI8QITxV3VzpOnkeNVsKvtkYLMjfk="   crossorigin="anonymous"></script>