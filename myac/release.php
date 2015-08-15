<?php
require_once('../assets_backend/be_includes/config.php');

//require_once('../Connections/connSystem.php');
//mysql_select_db($database_connSystem, $connSystem);

require_once('../assets_backend/be_includes/check_login.php');

mysql_free_result($query_1); 
						mysql_free_result($query_2);
						mysql_free_result($query_3);
						mysql_free_result($query_4);
						mysql_free_result($query_5);
						mysql_free_result($query_6);
						mysql_free_result($query_8);
						mysql_free_result($query_9);
						mysql_free_result($res_tktcategory);
						mysql_free_result($res_msg);
						mysql_free_result($query_a);
						mysql_free_result($query_b);
						mysql_free_result($res_task_details);
						mysql_free_result($res_nextwf);
						mysql_free_result($res_new_task);
						mysql_free_result($res_smsout);
?>