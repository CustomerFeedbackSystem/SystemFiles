<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_connSystem = "localhost";
$database_connSystem = "majivoice_github";//    
$username_connSystem = "root";
$password_connSystem = "";
$connSystem = mysql_connect($hostname_connSystem, $username_connSystem, $password_connSystem) or trigger_error(mysql_error(),E_USER_ERROR); 
?>