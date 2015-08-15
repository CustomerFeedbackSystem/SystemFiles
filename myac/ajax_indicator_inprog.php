<?php
require_once('../assets_backend/be_includes/config.php');
require_once('../assets_backend/be_includes/check_login_easy.php');

$sql_inprog="SELECT DISTINCT count(tktin_idtktin) as inprog FROM wftasks WHERE wftasks.usrrole_idusrrole=".$_SESSION['MVGitHub_iduserrole']." AND wftskstatustypes_idwftskstatustypes=6 AND wftskstatusglobal_idwftskstatusglobal=2";
$res_inprog=mysql_query($sql_inprog);
$fet_inprog=mysql_fetch_array($res_inprog);

if ($fet_inprog['inprog'] > 0)
	{
	echo "<span class=\"box_count\">".$fet_inprog['inprog']."</span>";
	}
mysql_free_result($res_inprog);	
?>