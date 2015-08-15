<?php
//first check if logged in
if ( (!isset($_SESSION['MVGitHub_logstatus'])) || ($_SESSION['MVGitHub_logstatus']!="IS_LOGGED_IN") || (!isset($_SESSION['MVGitHub_idacname'])) || (!isset($_SESSION['MVGitHub_iduserrole'])) || (!isset($_SESSION['MVGitHub_idacteam'])) || (!isset($_SESSION['MVGitHub_iduserprofile'])) )
	{
	$_SESSION['autologout']=1;
	echo "<div style=\"color:#ff0000;font-family:arial;font-size:12px;font-weight:bold\">".$msg_warning_loginrequired."</div>";
	if ( (isset($_SESSION['autologout'])) && ($_SESSION['autologout']==1) && (!isset($_SESSION['autologoutOK'])) )
		{
	?>
    <script language="javascript">
	window.location='index.php';
	</script>
    <?php
		$_SESSION['autologout']=0;
		$_SESSION['autologoutOK']=1;
		}
	exit;
	}

//check login details
$sql_login="SELECT idusrac,usrname,currentsess FROM usrac WHERE idusrac=".$_SESSION['MVGitHub_idacname']." AND usrname='".$_SESSION['MVGitHub_acname']."' AND usrpass='".$_SESSION['MVGitHub_acpass']."' LIMIT 1";
$res_login=mysql_query($sql_login);
$num_login=mysql_num_rows($res_login);
$fet_login=mysql_fetch_array($res_login);

mysql_free_result($res_login);

if ($num_login<1)
	{
	echo "<div style=\"color:#ff0000;font-family:arial;font-size:12px;font-weight:bold\">".$msg_warning_loginrequired."</div>";
	exit;
	}



//check simultaneous access
if ($fet_login['currentsess']!=session_id())
	{
	echo "<div style=\"color:#ff0000;font-family:arial;font-size:12px;font-weight:bold\">You have been logged out because you or someone else has just logged onto the same account from another computer or device.<br><a href=\"../myac/logout.php\">Back to System</a></div>";
	exit;
	}
	
if (!isset($_SESSION['sec_mod']))
	{
	$_SESSION['sec_mod']=1;
	}

//check permissions for this user to view,add,select,delete for this module
$sql_modperm = "SELECT idsystemprofileaccess,syssubmodule_idsyssubmodule,sysprofiles_idsysprofiles,permview,permupdate,perminsert,permdelete FROM systemprofileaccess 
INNER JOIN syssubmodule ON systemprofileaccess.syssubmodule_idsyssubmodule = syssubmodule.idsyssubmodule 
WHERE permview=1 AND syssubmodule.sysmodule_idsysmodule=".$_SESSION['sec_mod']."  
AND sysprofiles_idsysprofiles=".$_SESSION['MVGitHub_iduserprofile']." LIMIT 1";
$res_modperm = mysql_query($sql_modperm);
$num_modperm = mysql_num_rows($res_modperm);
$fet_modperm = mysql_fetch_array($res_modperm);

mysql_free_result($res_modperm);
//get the object list permissions


if ($num_modperm < 1)
	{
	echo "<div style=\"color:#ff0000;font-family:arial;font-size:12px;font-weight:bold\">".$ec200."</div>";
	echo "<div style=\"padding:5px 5px 5px 50px\"><a href=\"../myac/logout.php\">-OK-</a></div>";
	exit;
	}	
?>	