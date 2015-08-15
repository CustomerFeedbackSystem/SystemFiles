<?php
require_once('../assets_backend/be_includes/config.php');
require_once('../assets_backend/be_includes/check_login_easy.php');

$q = trim($_GET['q']);

$sql = "SELECT fname,lname,usrname FROM usrac WHERE (fname LIKE '%".$q."%' OR lname LIKE '%".$q."%' OR usrname LIKE '%".$q."%') AND (usrrole_idusrrole!=".$_SESSION['this_roleid']." OR usrrole_idusrrole IS NULL) AND usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']."";
$res = mysql_query($sql);
$num = mysql_num_rows($res);
$fet = mysql_fetch_array($res);
//echo $sql;
if ($num > 0)
	{
	 do {
	echo $fet['fname']." ".$fet['lname'].", ".$fet['usrname']."\n";
		} while ($fet = mysql_fetch_array($res));
	} else {
	echo "Account Not Found";
	}
?>
