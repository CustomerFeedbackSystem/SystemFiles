<?php
require_once('../assets_backend/be_includes/config.php');
//require_once('../Connections/connSystem.php');
//mysql_select_db($database_connSystem, $connSystem);

require_once('../assets_backend/be_includes/check_login.php');

if (isset($_GET['idflow']))
	{
	$tksflow=trim($_GET['idflow']);
	} else {
	exit;
	}
	
//first find out if to a group or to a role	
$sql_rg="SELECT idwfactors,usrrole_idusrrole,usrgroup_idusrgroup FROM wfactors WHERE wftskflow_idwftskflow=".$tksflow." LIMIT 1";
$res_rg=mysql_query($sql_rg);
$fet_rg=mysql_fetch_array($res_rg);

//find out the list order of the current workflow
$sql_lo="SELECT listorder FROM wftskflow WHERE idwftskflow=".$tksflow."";
$res_lo=mysql_query($sql_lo);
$fet_lo=mysql_fetch_array($res_lo);

$sql_nlo="SELECT listorder FROM wftskflow WHERE listorder>".$fet_lo['listorder']." ORDER BY listorder ASC LIMIT 1";
$res_nlo=mysql_query($sql_nlo);
$fet_nlo=mysql_fetch_array($res_nlo);

//echo "<br><br><br><br>". $sql_rg;
if ($fet_rg['usrrole_idusrrole']>0)
	{
	$actor_type="ROLE";
	}
	
if ($fet_rg['usrgroup_idusrgroup']>0)
	{
	$actor_type="GROUP";
	}
		
// check the next steps actors for the next workflow
if ($actor_type=="ROLE")
	{
	$sql_nextactors="SELECT DISTINCT idusrrole,usrrolename,fname,lname,utitle,usrname FROM wfactors 
	INNER JOIN wftskflow ON wfactors.wftskflow_idwftskflow=wftskflow.idwftskflow
	INNER JOIN usrrole ON wfactors.usrrole_idusrrole=usrrole.idusrrole
	INNER JOIN usrac ON usrrole.idusrrole=usrac.usrrole_idusrrole
	WHERE wftskflow.listorder > ".$fet_nlo['listorder']." GROUP BY wftskflow.idwftskflow ORDER BY usrrolename ASC ";
	}
	
if ($actor_type=="GROUP")
	{
	$sql_nextactors="SELECT DISTINCT idusrrole,usrrolename,fname,lname,utitle,usrname FROM wfactors 
	INNER JOIN link_userrole_usergroup ON wfactors.usrgroup_idusrgroup=link_userrole_usergroup.usrgroup_idusrgroup 
	INNER JOIN usrrole ON link_userrole_usergroup.usrrole_idusrrole=usrrole.idusrrole 
	INNER JOIN usrac ON usrrole.idusrrole=usrac.usrrole_idusrrole 
	INNER JOIN wftskflow ON wfactors.wftskflow_idwftskflow=wftskflow.idwftskflow 
	WHERE link_userrole_usergroup.usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']." AND 
	wfactors.usrgroup_idusrgroup=".$fet_rg['usrgroup_idusrgroup']." AND 
	wftskflow.listorder > ".$fet_nlo['listorder']." GROUP BY wftskflow.idwftskflow ORDER BY usrrolename ASC ";
	}	
	$res_nextactors=mysql_query($sql_nextactors);
	$num_nextactors=mysql_num_rows($res_nextactors);
	$fet_nextactors=mysql_fetch_array($res_nextactors);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="../assets_backend/css/style.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div>
<table border="0" cellpadding="2" cellspacing="0">
<?php
if ($num_nextactors>0)
	{
	do {
?>
	<tr>
    	<td class="tbl_data">
        <?php echo $fet_nextactors['usrrolename'];?>
        </td>
        <td  class="tbl_data">
         <small><strong><?php echo $fet_nextactors['utitle'];?> <?php echo $fet_nextactors['fname'];?> <?php echo $fet_nextactors['lname'];?></strong> 
      (<?php echo $fet_nextactors['usrname'];?>)</small>
        </td>
    </tr>
<?php
		 } while ($fet_nextactors=mysql_fetch_array($res_nextactors));
	} else {
?>    
<tr>
	<td>
    <div class="msg_warning">
    No Actors Found to Receive Tasks
    </div>
    </td>
</tr>
<?php } ?>
</table>
</div>
</body>
</html>
