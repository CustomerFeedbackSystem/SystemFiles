<?php
require_once('../assets_backend/be_includes/config.php');

require_once('../assets_backend/be_includes/check_login_easy.php');

$qry_values="SELECT idusrac,fname,lname FROM usrac ORDER BY fname ";
$res_values=mysql_query($qry_values);
$fet_values=mysql_fetch_array($res_values);

echo "[";
do { 
echo "{\"caption\":\"".$fet_values['fname']." ".$fet_values['lname']."\",\"value\":".$fet_values['idusrac']."},";  
	} 
while ($fet_values=mysql_fetch_array($res_values));  
echo "]";
mysql_free_result($res_values);
?>