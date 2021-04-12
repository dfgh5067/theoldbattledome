<?php

function connect_to_server(){
	$host = "";
	$dbuser = "";
	$pass = "";
	$db = "";
	$con = mysqli_connect($host, $dbuser, $pass, $db);
	if (mysqli_connect_errno())
	  {
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
	  }
	  return $con;
}


//adds the required copyright line at the bottome of every page
function footer(){
	$currentYear = date("Y");
	
	$c = "<br>
	<div id=\"footer\" style=\"margin-left:auto; margin-right:auto; text-align:center;\">
	Copyright 2000-" . $currentYear . " NeoPets, Inc. All rights reserved. Permission pending.<br><br>
	</div>";
	
	echo $c;
}









?>