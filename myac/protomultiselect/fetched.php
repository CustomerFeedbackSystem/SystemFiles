<?php
require_once('../assets_backend/be_includes/config.php');

$qry_values="SELECT id,uname FROM z_test ORDER BY uname LIMIT 10";
$res_values=mysql_query($qry_values);
$fet_values=mysql_fetch_array($res_values);

echo "[";
do { 
echo "{\"caption\":\"".$fet_values['uname']."\",\"value\":".$fet_values['id']."},";  
	} 
while ($fet_values=mysql_fetch_array($res_values));  
echo "]";
mysql_free_result($res_values);
?>