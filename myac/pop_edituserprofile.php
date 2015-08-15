<?php
require_once('../assets_backend/be_includes/config.php');
//require_once('../Connections/connSystem.php');
//mysql_select_db($database_connSystem, $connSystem);
require_once('../assets_backend/be_includes/check_login_easy.php');

if (isset($_GET['profile']))
	{
	$profileid=mysql_escape_string(trim($_GET['profile']));
	$_SESSION['edithisprofile']=$profileid;
	}
	//now, find out if this profile is owned by this organization
	$sql_owner="SELECT idsysprofiles,usrteamzone_idusrteamzone,sysprofile FROM sysprofiles
	INNER JOIN usrteamzone ON sysprofiles.usrteamzone_idusrteamzone=usrteamzone.idusrteamzone
	WHERE usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']." LIMIT 1";
	$res_owner=mysql_query($sql_owner);
	$num_owner=mysql_num_rows($res_owner);
	$fet_owner=mysql_fetch_array($res_owner);
	
	if ($num_owner < 1)
		{
		$feedback= "<div class=\"msg_warning\">".$msg_warn_violation."</div>";
		exit;
		}
//if post is update
if ( (isset($_POST['formaction'])) && ($_POST['formaction']=="update") )
	{
	//clean up variable
	$profile_name=mysql_escape_string(trim($_POST['profile_name']));
	$profile_zone=preg_replace('/[^a-z\-_0-9\.:\/]/i','',mysql_escape_string(trim($_POST['region'])));
	
	if ( (strlen($profile_name) < 1) || ($profile_zone<1)  )
		{
		$error="1";
		$feedback= "<div class=\"msg_warning\">".$msg_warn_misscontent."</div>";
		}
	
	//check to ensure that this name is unique
	$sql_unique="SELECT idsysprofiles FROM sysprofiles WHERE sysprofile='".$profile_name."' AND usrteamzone_idusrteamzone=".$profile_zone." LIMIT 1 ";
	$res_unique=mysql_query($sql_unique);
	$num_unique=mysql_num_rows($res_unique);
	
	
	if ($num_unique==0) //update
		{
		
		$sql_update="UPDATE sysprofiles SET usrteamzone_idusrteamzone='".$profile_zone."',
		sysprofile='".$profile_name."' WHERE idsysprofiles=".$_SESSION['edithisprofile'].""; 
		mysql_query($sql_update);
		
		$feedback= "<div class=\"msg_success\">".$msg_changes_saved."</div>";
		
		} else { //warn
		
		$feedback= "<div class=\"msg_warning\">".$msg_warning_duplicate_wf."</div>";
		
		}
	}
	
$sql_profile="SELECT idsysprofiles,usrteamzone_idusrteamzone,sysprofile FROM sysprofiles WHERE idsysprofiles=".$_SESSION['edithisprofile']." LIMIT 1";
$res_profile=mysql_query($sql_profile);
$fet_profile=mysql_fetch_array($res_profile);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="../assets_backend/css/style.css" rel="stylesheet" type="text/css" />
<title>Edit Profile</title>
</head>
<body>
<form method="post" action="" name="edit_profile" autocomplete="off">
<table border="0" width="100%" cellpadding="2" cellspacing="0">
	<tr>
    	<td class="tbl_h" colspan="2">
        	<table border="0" width="100%">
            	<tr>
                	<td>
					<?php echo $lbl_access_profile;?>
                    </td>
                    <td width="100">
                    <a href="#" onClick="parent.tb_remove(); parent.location.reload(1)" id="button_closewin"></a>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
    	<td colspan="2">
        <?php
		if (isset($feedback))
			{
			echo $feedback;
			}
		?>
        </td>
    </tr>
    <tr>
    	<td height="45" class="tbl_data">
        <?php echo $lbl_access_profile;?>        </td>
        <td height="45" class="tbl_data">
      <input type="text" size="30" value="<?php echo $fet_profile['sysprofile'];?>" maxlength="30" name="profile_name" />      </td>
    </tr>
    <tr>
    	<td height="45" class="tbl_data">
        <?php
		echo $lbl_zonename;
		?>        </td>
        <td height="45" class="tbl_data">
        <select name="region">
        	<?php
			$sql_region="SELECT idusrteamzone,usrteam_idusrteam,userteamzonename FROM usrteamzone
			WHERE usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']."";
			$res_region=mysql_query($sql_region);
			$fet_region=mysql_fetch_array($res_region);
			
			do {
			echo "<option ";
			if ($fet_profile['usrteamzone_idusrteamzone']==$fet_region['idusrteamzone'])
				{
				echo " selected=\"selected\" ";
				}
			echo " value=\"".$fet_region['idusrteamzone']."\">".$fet_region['userteamzonename']."</option>";
			} while ($fet_region=mysql_fetch_array($res_region));
			?>
        </select>        </td>
    </tr>
    <tr>
    	<td></td>
        <td height="45"  align="left">
         <input type="hidden" name="formaction" value="update">
               <a href="#" onClick="document.forms['edit_profile'].submit()" id="button_save"></a>        </td>
    </tr>
</table>
</form>
</body>
</html>