<?php
//session_start();
require_once('../Connections/connSystem.php');
mysql_select_db($database_connSystem, $connSystem);
//count the number of new tasks for this user

					$sql_countin="SELECT count(*) as newtsks FROM wftasks WHERE usrrole_idusrrole=".$_SESSION['MVGitHub_iduserrole']." AND wftskstatustypes_idwftskstatustypes=0";
					$res_countin=mysql_query($sql_countin);
					$fet_countin=mysql_fetch_array($res_countin);
					
					if ($fet_countin['newtsks']) 
						{
						$tsksin_counted=1;
						echo "<span class=\"box_count\">".$fet_countin['newtsks']."</span>";
						}
					
?>