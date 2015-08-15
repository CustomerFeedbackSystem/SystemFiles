<?php
require_once('../assets_backend/be_includes/config.php');
require_once('../assets_backend/be_includes/check_login_easy.php');
$rqtrId=intval($_GET['rqtr']);
//find if there are disaggragations related to this
$sql_disagg="SELECT * FROM disagglink INNER JOIN disagg ON disagglink.disagg_iddisagg=disagg.iddisagg
WHERE disagglink.tktactivitytype_idtktactivitytype=".$rqtrId."";
//cho $sql_disagg;
$res_disagg=mysql_query($sql_disagg);
$num_disagg=mysql_num_rows($res_disagg);
$fet_disagg=mysql_fetch_array($res_disagg);

if ($num_disagg > 0)
	{
	echo "<table border=0 cellpadding=2 cellspacing=0 style=\"margin:0px 0px 0px 10px; background-color:#f4f4f4\">";
	do {
	echo "<tr><td class=tbl_data><small>".$fet_disagg['disaggval']."</small></td>";
	echo "<td><input type=\"text\" onKeyUp=\"res(this,numb);\" value=\"\" name=\"disagg[]\"></td></tr>";
		} while ($fet_disagg=mysql_fetch_array($res_disagg));
	echo "</table>";
	}
?>