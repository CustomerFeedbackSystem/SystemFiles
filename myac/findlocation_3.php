<?php
require_once('../Connections/connSystem.php');
mysql_select_db($database_connSystem, $connSystem);

$q = strtolower(mysql_escape_string($_GET['q']));
///echo $q;
if (!$q) return;

$sql = "SELECT DISTINCT locationname FROM loctowns WHERE locationname LIKE '%".$q."%' AND is_valid='1' LIMIT 10 ";
$rsd = mysql_query($sql);
while ($rs = mysql_fetch_array($rsd)) 
	{
	$lname = $rs['locationname'];
	echo "$lname\n";
	}
?>