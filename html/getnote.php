<?php
if (is_numeric($_POST['cookid'])) {
	class MyDB extends SQLite3
	{
		function __construct()
		{
			$this->open('the.db');
		}
	}

	$database=new MyDB();
	$note=$database->querySingle('SELECT note FROM cooks WHERE id='.$_POST['cookid'].';');
	echo $note;
}
?>