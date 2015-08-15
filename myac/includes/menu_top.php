<?php
require_once('../assets_backend/be_includes/check_login_easy.php');

//we need to list all the menu items for this user as set in the db in a secure manner
$list_menu = "SELECT DISTINCT idsysmodule,modulename,systemprofileaccess.permview,systemprofileaccess.perminsert FROM sysmodule
INNER JOIN syssubmodule ON sysmodule.idsysmodule = syssubmodule.sysmodule_idsysmodule
INNER JOIN systemprofileaccess ON syssubmodule.idsyssubmodule = systemprofileaccess.syssubmodule_idsyssubmodule
INNER JOIN systeamaccess ON sysmodule.idsysmodule=systeamaccess.sysmodule_idsysmodule
WHERE systemprofileaccess.sysprofiles_idsysprofiles=".$_SESSION['MVGitHub_iduserprofile']." 
AND systeamaccess.usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']." AND sysmodule.sys_status=1 
AND systemprofileaccess.permview=1 AND sysmodule.sysmodulepos_id=1 GROUP BY idsysmodule ORDER BY sysmodule.listorder ASC";
//echo $list_menu;
$res_menu = mysql_query($list_menu);
$num_menu = mysql_num_rows($res_menu);
$fet_menu = mysql_fetch_array($res_menu);
//echo $list_menu;
do {
	
echo "<a href=\"".$_SERVER['PHP_SELF']."?mod=".$fet_menu['idsysmodule']."&amp;submod=0&ua=view&view=".$fet_menu['idsysmodule']."&uction=view_mod\"";

	if ($_SESSION['mod_celectd']==$fet_menu['idsysmodule']) { 
	
echo " style=\"color:#1D4F81;text-decoration:none;font-weight:bold\" class=\"topmenu_on\" "; 
	} else { 
echo " style=\"color:#F3F8FC;text-decoration:none\" class=\"topmenu\" ";
	}
echo " >".$fet_menu['modulename']." ";



	if ($fet_menu['idsysmodule']==2) //new undone and in progress tasks
		{
		
//CREATE EXCEPTION FOR BATCHES TO AVOID JS ERROR
if ($_SESSION['sec_submod']!="299999999")
	{
		echo "<script type=\"text/javascript\">
		refreshdiv_1();
		</script>";
		echo "<span name=\"div_1\" id=\"div_1\"></span>";
		}
	}
	
	if ($fet_menu['idsysmodule']==23) //unread escalations
		{
		//echo "<span class=\"box_count\">13</span>";
		}
		
	if ($fet_menu['idsysmodule']==24) //unread notifications
		{
		//echo "<span class=\"box_count\">43</span>";
		}	

echo "</a>";

	
 } while ($fet_menu = mysql_fetch_array($res_menu));
 
mysql_free_result($res_menu);
?>
