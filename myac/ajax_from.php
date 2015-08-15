<?php
require_once('../assets_backend/be_includes/config.php');
//require_once('../Connections/connSystem.php');
//mysql_select_db($database_connSystem, $connSystem);

require_once('../assets_backend/be_includes/check_login_easy.php');

$val=intval($_GET['workflow_step']);

//first, find out if the actors are at the role or group level
$sql_rg="SELECT idwfactors,usrrole_idusrrole,usrgroup_idusrgroup FROM wfactors WHERE wftskflow_idwftskflow=".$val." LIMIT 1";
$res_rg=mysql_query($sql_rg);
$fet_rg=mysql_fetch_array($res_rg);

if ($fet_rg['usrrole_idusrrole']>0)
  	{				
	$query="SELECT DISTINCT idusrrole,usrrolename,fname,lname,utitle FROM wfactors 
	INNER JOIN usrrole ON wfactors.usrrole_idusrrole=usrrole.idusrrole
	INNER JOIN usrac ON usrrole.idusrrole=usrac.usrrole_idusrrole 
	INNER JOIN wftskflow ON wfactors.wftskflow_idwftskflow=wftskflow.idwftskflow
	WHERE wfactors.wftskflow_idwftskflow=".$val." 
	AND wftskflow.wfproc_idwfproc=".$_SESSION['thisWFsession']."
	ORDER BY usrrolename ASC";
	}
	
if ($fet_rg['usrgroup_idusrgroup']>0)
  	{				
	$query="SELECT DISTINCT idusrrole,usrrolename,fname,lname,utitle FROM wfactors 
	INNER JOIN link_userrole_usergroup ON wfactors.usrgroup_idusrgroup=link_userrole_usergroup.usrgroup_idusrgroup  
	INNER JOIN usrrole ON link_userrole_usergroup.usrrole_idusrrole=usrrole.idusrrole
	INNER JOIN usrac ON usrrole.idusrrole=usrac.usrrole_idusrrole
	INNER JOIN wftskflow ON wfactors.wftskflow_idwftskflow=wftskflow.idwftskflow
	WHERE link_userrole_usergroup.usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']." 
	AND link_userrole_usergroup.usrgroup_idusrgroup=".$fet_rg['usrgroup_idusrgroup']." 
	AND wfactors.wftskflow_idwftskflow=".$val." 
	AND wftskflow.wfproc_idwfproc=".$_SESSION['thisWFsession']."
	ORDER BY usrrolename ASC";
	}	
	
$result=mysql_query($query);
//echo $query;
?>
<select name="actor">
<option value=""> --- </option>
<?php while($row=mysql_fetch_array($result)) { ?>
<option value=<?php echo $row['idusrrole']; ?>><?php echo $row['usrrolename']; ?> <small>(<?php echo $row['fname']; ?> <?php echo $row['lname']; ?>)</small></option>
<?php } ?>
</select>