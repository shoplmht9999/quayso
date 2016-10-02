<?php
$host = "127.10.99.2";

$username ="adminpFvlRT5";

$password ="SeUireIEtY8T";	
$dbname = "fuck";



$connection = mysql_connect($host,$username,$password);

if (!$connection)

  {

  die('Could not connect: ' . mysql_error());

  }

mysql_select_db($dbname) or die(mysql_error());

mysql_query("SET NAMES utf8");
?>