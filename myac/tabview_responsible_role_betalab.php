<?php require_once('../Connections/connSystem.php'); ?>
<?php
require_once('../assets_backend/be_includes/config.php');
//require_once('../Connections/connSystem.php');
//mysql_select_db($database_connSystem, $connSystem);
require_once('../assets_backend/be_includes/check_login_easy.php');

if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;

  $theValue = function_exists("mysql_escape_string") ? mysql_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

mysql_select_db($database_connSystem, $connSystem);
$query_roles = "SELECT idusrrole, usrrolename, usrroledesc, usrname, utitle, fname, lname, acstatus,userteamzonename, (SELECT idwfactors FROM wfactors WHERE usrrole.idusrrole = wfactors.usrrole_idusrrole AND wftskflow_idwftskflow=".$_SESSION['idflow'].") AS dis_user FROM usrrole INNER JOIN usrac ON usrrole.idusrrole = usrac.usrrole_idusrrole INNER JOIN usrteamzone ON usrrole.usrteamzone_idusrteamzone = usrteamzone.idusrteamzone WHERE usrteamzone.usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']." ORDER BY userteamzonename ASC,usrrolename ASC";
$roles = mysql_query($query_roles, $connSystem) or die(mysql_error());
$row_roles = mysql_fetch_assoc($roles);
$totalRows_roles = mysql_num_rows($roles);
?>
<?php
mysql_select_db($database_connSystem, $connSystem);
$query_sasi = "SELECT idusrrole,usrrolename,usrroledesc,usrname,utitle,fname,lname,acstatus,userteamzonename FROM usrrole  LEFT JOIN usrac ON usrrole.idusrrole=usrac.usrrole_idusrrole  INNER JOIN wfactors ON usrrole.idusrrole=wfactors.usrrole_idusrrole INNER JOIN usrteamzone ON usrrole.usrteamzone_idusrteamzone=usrteamzone.idusrteamzone WHERE usrteamzone.usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']." AND wftskflow_idwftskflow=".$_SESSION['idflow']." ORDER BY userteamzonename ASC,usrrolename ASC";
$sasi = mysql_query($query_sasi, $connSystem) or die(mysql_error());
$row_sasi = mysql_fetch_assoc($sasi);
$totalRows_sasi = mysql_num_rows($sasi);
?>
<?php  $lastTFM_nest = "";?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="../assets_backend/css/style.css" rel="stylesheet" type="text/css" />
</head>

<body>
<table border="0" width="790" cellpadding="1" cellspacing="0">
	<tr>
    	<td valign="top" class="hline">
        	<table border="0" cellpadding="2" cellspacing="0" width="395">
            	<tr>
                	<td class="tbl_h"><?php echo $lbl_role;?></td>
                    <td class="tbl_h" ><?php echo $lbl_username;?></td>
                    <td class="tbl_h" >&gt;</td>
                </tr>
                
<?php do { ?>
    <?php $TFM_nest = $row_roles['userteamzonename'];
if ($lastTFM_nest != $TFM_nest) { 
	$lastTFM_nest = $TFM_nest; ?>
    <tr>
    	<td colspan="3" height="10" bgcolor="#CCCCCC"></td>
    </tr>
    <tr>    
    <td colspan="3" class="divcol">
                  <?php echo $row_roles['userteamzonename'];?>                  </td>
                </tr>
    <?php } //End of Basic-UltraDev Simulated Nested Repeat?>
        <tr <?php 
// technocurve arc 3 php mv block2/3 start
echo " style=\"background-color:$mocolor\" onMouseOver=\"this.style.backgroundColor='$mocolor3'\" onMouseOut=\"this.style.backgroundColor='$mocolor'\"";
// technocurve arc 3 php mv block2/3 end
?>>
                	<td class="tbl_data"><span title="<?php echo $row_roles['usrroledesc'];?>"><?php echo $row_roles['usrrolename'];?></span></td>
                    <td class="tbl_data">
                    <?php
					if (strlen($row_roles['usrname'])>0) { ?>
                    <span title="<?php echo $row_roles['utitle']." ".$row_roles['lname']." , ".$row_roles['fname'];?>"><?php echo $row_roles['usrname'];?></span>
                    <?php } else { echo "---"; } ?>
                    </td>
                    <td width="30" class="tbl_data">
                    
                    <?php
				if ((!isset($fet_cact['usrgroup_idusrgroup'])) || ($fet_cact['usrgroup_idusrgroup'] < 1)) //if not set to group
					{
					if ($row_roles['dis_user']=="") { ?>
                    <a title="<?php echo $ins_assignresp; ?>" href="<?php echo $_SERVER['PHP_SELF'];?>?do=assign_role&amp;id=<?php echo $row_roles['idusrrole'];?>" id="button_send_right"></a>
                    <?php } else { ?>
                    <img src="../assets_backend/btns/btn_send_right_disabled.jpg" border="0" align="absmiddle" title="<?php echo $lbl_disabled;?>" />
                    <?php } 
					} //if not set to group
					?>
                    
                    </td>
                    </tr>
					<?php } while ($row_roles = mysql_fetch_assoc($roles)); ?>
              <?php 
// technocurve arc 3 php mv block3/3 start
if ($mocolor == $mocolor1) {
	$mocolor = $mocolor2;
} else {
	$mocolor = $mocolor1;
}
// technocurve arc 3 php mv block3/3 end
?>



                <?php						
				if ($totalRows_roles < 2)
					{
				?>
              <!--  <tr>
                	<td colspan="3">
                    <div class="msg_warning">
                    <?php //echo $msg_no_role;?>
                    </div>
                    </td>
                </tr> -->
                <?php } ?>
            </table>
        </td>
      <td valign="top"  width="395">
      <table border="0" cellpadding="2" cellspacing="0" width="100%">
            	<tr>
                	<td width="30" class="tbl_h">&lt;</td>
                  <td class="tbl_h"><?php echo $lbl_role;?></td>
                    <td class="tbl_h"><?php echo $lbl_username;?></td>
                </tr>
                 <?php do { ?>
                   <?php $TFM_nest = $row_sasi['userteamzonename'];
if ($lastTFM_nest != $TFM_nest) { 
	$lastTFM_nest = $TFM_nest; ?>
    <tr>
    	<td colspan="3" height="10" bgcolor="#CCCCCC"></td>
    </tr>
                   <tr>
                     <td colspan="3" class="divcol"><?php echo $row_sasi['userteamzonename'];?> </td>
                   </tr>
                   <?php } //End of Basic-UltraDev Simulated Nested Repeat?>
                   <tr <?php 
// technocurve arc 3 php mv block2/3 start
echo " style=\"background-color:$mocolor\" onMouseOver=\"this.style.backgroundColor='$mocolor3'\" onMouseOut=\"this.style.backgroundColor='$mocolor'\"";
// technocurve arc 3 php mv block2/3 end
?>>
                     <td width="30" class="tbl_data"><a href="<?php echo $_SERVER['PHP_SELF'];?>?do=unassign_role&amp;id=<?php echo $row_sasi['idusrrole'];?>" id="button_send_left"></a></td>
                     <td class="tbl_data"><?php echo $row_sasi['usrrolename']?></td>
                     <td class="tbl_data"><span title="<?php echo $row_sasi['utitle']." ".$row_sasi['lname']." , ".$row_sasi['fname'];?>"> <?php echo $row_sasi['usrname']?> </span> </td>
                   </tr>
                   <?php } while ($row_sasi = mysql_fetch_assoc($sasi)); ?>
                <?php 
// technocurve arc 3 php mv block3/3 start
if ($mocolor == $mocolor1) {
	$mocolor = $mocolor2;
} else {
	$mocolor = $mocolor1;
}
// technocurve arc 3 php mv block3/3 end
?>
                
		</table>
      </td>
    </tr>
</table>
</body>
</html>
<?php
mysql_free_result($roles);

mysql_free_result($sasi);
?>
