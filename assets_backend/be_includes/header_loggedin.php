<div id="header">
<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
    <?php if (strlen($_SESSION['MVGitHub_logo'])>4) { ?>
    	<td height="80" >
        <img src="../<?php echo $_SESSION['MVGitHub_logo'];?>" border="0" align="absmiddle" />
        </td>
	<?php } ?>
   	  <td valign="top" height="80" width="100%" style="padding:15px 0px 0px 10px" class="title_header" align="left">
        <?php echo $_SESSION['MVGitHub_acteam'];?>
        </td>
    </tr>
</table>
</div>
<div id="header_nav">
<table border="0" cellpadding="0" cellspacing="0">
	<tr>
    	<td class="text_header">
		<?php echo $_SESSION['MVGitHub_usrtitle']."&nbsp;".$_SESSION['MVGitHub_usrlname']."&nbsp;[".$_SESSION['MVGitHub_userrole'].", ".$_SESSION['MVGitHub_userteamzone']."]";?>
		</td>
<?php
$list_hmenu = "SELECT DISTINCT idsysmodule,modulename,systemprofileaccess.permview,systemprofileaccess.perminsert,systemprofileaccess.syssubmodule_idsyssubmodule FROM sysmodule
INNER JOIN syssubmodule ON sysmodule.idsysmodule = syssubmodule.sysmodule_idsysmodule
INNER JOIN systemprofileaccess ON syssubmodule.idsyssubmodule = systemprofileaccess.syssubmodule_idsyssubmodule
INNER JOIN systeamaccess ON sysmodule.idsysmodule=systeamaccess.sysmodule_idsysmodule
WHERE systemprofileaccess.sysprofiles_idsysprofiles=".$_SESSION['MVGitHub_iduserprofile']." 
AND systeamaccess.usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']." 
AND systemprofileaccess.permview=1 AND sysmodule.sysmodulepos_id=2 AND sysmodule.sys_status=1 AND syssubmodule.submod_status=1 GROUP BY idsysmodule ORDER BY sysmodule.listorder ASC";
//echo $list_hmenu;
$res_hmenu = mysql_query($list_hmenu);
$num_hmenu = mysql_num_rows($res_hmenu);
$fet_hmenu = mysql_fetch_array($res_hmenu);

do {
?>
<td>
        <a href="<?php echo $_SERVER['PHP_SELF'];?>?mod=<?php echo $fet_hmenu['idsysmodule'];?>&amp;uction=view_submod&amp;submod=<?php echo $fet_hmenu['syssubmodule_idsyssubmodule'];?>" id="button_<?php echo strtolower(str_replace('_','',$fet_hmenu['modulename']));?>"></a>
</td>
<?php
} while ($fet_hmenu = mysql_fetch_array($res_hmenu));
?>

        <td>
        <a href="logout.php" id="button_logout" onclick="return confirm('<?php echo $msg_prompt_logout; ?>');"></a>
        </td>
	</tr>
</table>    
</div>
<div style="height:3px; background-color:#BFD7EF">
</div>
<div id="header_menu" style="padding:15px 0px 0px 0px; margin:-15px 0px 0px 0px;"><!-- this fix for Microsoft IE -->
<?php require_once('../myac/includes/menu_top.php');?>
</div>
