<?php
require_once('../assets_backend/be_includes/config.php');
require_once('../assets_backend/be_includes/check_login_easy.php');

$q = strtolower(mysql_escape_string($_GET['q']));
//echo $q;

//set the rules for the query below depending on the job level of the user
//if  > coordinator, then no restriction

//if < coordinator, then restrict to the region

if (!$q) return;

$sql = "SELECT utitle,fname,lname,usrname,usrrolename FROM usrac
INNER JOIN usrrole ON usrac.usrrole_idusrrole=usrrole.idusrrole 
WHERE usrrolename LIKE '%".$q."%' OR fname LIKE '%".$q."%' OR lname LIKE '%".$q."%' OR usrname LIKE '%".$q."%'  LIMIT 10 ";
$rsd = mysql_query($sql);
while ($rs = mysql_fetch_array($rsd)) 
	{
	$lname = $rs['usrrolename'].", ".$rs['utitle']." ".$rs['fname']." ".$rs['lname']."";
	echo "$lname\n";
	}
?>