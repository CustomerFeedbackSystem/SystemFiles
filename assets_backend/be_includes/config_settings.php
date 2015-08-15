<?php
//database connection
$hostname_connSystem = "localhost";
$database_connSystem = "majivoice_github";// 
$username_connSystem = "root"; //
$password_connSystem = "";//
$connSystem = mysql_connect($hostname_connSystem, $username_connSystem, $password_connSystem) or trigger_error(mysql_error(),E_USER_ERROR); 

mysql_select_db($database_connSystem, $connSystem);

//look and feel
$pagetitle="Customer Feedback &amp; Complaints Management System";
$url_absolute = "http://localhost/majivoice_github";
?>