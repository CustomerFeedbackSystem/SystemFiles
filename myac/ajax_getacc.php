<?php
require_once('../assets_backend/be_includes/config.php');
require_once('../assets_backend/be_includes/check_login_easy.php');
$q = intval($_GET['q']);

$sql = "SELECT regnam FROM acc_list WHERE accnum='".$q."' ";
$res = mysql_query($sql);
$num = mysql_num_rows($res);
$fet = mysql_fetch_array($res);

if ($num > 0)
	{
	echo "<span style=\"color:#009900\">".$fet['regnam']."</span>";
	} else {
	echo "<span style=\"color:#ff0000\">N/A</span>";
	}
?>
