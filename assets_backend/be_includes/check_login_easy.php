<?php
if ( (!isset($_SESSION['MVGitHub_logstatus'])) || ($_SESSION['MVGitHub_logstatus']!="IS_LOGGED_IN") || (!isset($_SESSION['MVGitHub_idacname'])) || (!isset($_SESSION['MVGitHub_iduserrole'])) || (!isset($_SESSION['MVGitHub_idacteam'])) || (!isset($_SESSION['MVGitHub_iduserprofile'])) )
	{
	$_SESSION['autologout']=1;
	echo "<div style=\"color:#ff0000;font-family:arial;font-size:12px;font-weight:bold\">".$msg_warning_loginrequired."</div>";
	exit;
	if ( (isset($_SESSION['autologout'])) && ($_SESSION['autologout']==1) && (!isset($_SESSION['autologoutOK'])) )
		{
		exit;
		}
	}

?>	