<?php
//this is to connect to the database

	$link=mysqli_connect("localhost","root","","pepsi_database");
	if($link==false)
		die("Could not connect to Database".mysqli_connect_error());
?>
