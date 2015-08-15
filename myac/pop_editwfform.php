<?php
require_once('../assets_backend/be_includes/config.php');

//require_once('../Connections/connSystem.php');
//mysql_select_db($database_connSystem, $connSystem);

require_once('../assets_backend/be_includes/check_login.php');

//get the id
if (isset($_GET['idform']))	
	{
	$_SESSION['idform']=mysql_real_escape_string(trim($_GET['idform']));
	}

//get the form details
$res_details=mysql_query('SELECT form_description,form_status FROM wfprocforms WHERE idwfprocforms='.$_SESSION['idform'].'');
$fet_details=mysql_fetch_array($res_details);

if ( (isset($_POST['form_action'])) && ($_POST['form_action']=="edit") )
	{
	//clean up variables
	$frmstatus=mysql_real_escape_string(trim($_POST['frm_status']));
	$frmdesc=mysql_real_escape_string(trim($_POST['frm_desc']));
	
	//validate
	if ($frmstatus==1)
		{
		$error_1="<div class=\"msg_warning\">Please indicate the appropriate status for this form</div>";
		}

	
	//go ahead and add
		if ( !isset($error_1)  )
			{
			$sql_newform="UPDATE wfprocforms SET 
			form_description='".$frmdesc."',
			form_status='".$frmstatus."'
			WHERE idwfprocforms=".$_SESSION['idform']." AND usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']."";
			mysql_query($sql_newform);
			
			$success=1;
			
			}//no error
	
	} //form action
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="../assets_backend/css/style.css" rel="stylesheet" type="text/css" />
<title>Untitled Document</title>
</head>
<body>
<div>
	<div class="tbl_sh" style="position:fixed; margin:0px; padding:0px; top:0px">
    <table border="0" cellpadding="0" cellspacing="0" width="650">
  		<tr>
        	<td >
            Edit Custom Form
            </td>
        	<td align="right">
            	<table border="0" cellpadding="0" cellspacing="0">
                	<tr>
                        <td><a href="#" onClick="parent.tb_remove();parent.location.reload(1)" id="button_closewin"></a></td>
                    </tr>
                 </table>
            	
            </td>
      </tr>
    </table>
    </div>
  
    <div style="padding:45px 10px 0px 10px">
    <?php
	if (!isset($success))
		{
		if (isset($error_1)) { echo $error_1; }
		if (isset($error_2)) { echo $error_2; }
		if (isset($error_3)) { echo $error_3; }
	?>
    <form method="post" action="" name="add_form">
    	<table border="0" cellpadding="2" cellspacing="0">
        	<tr>
            	<td class="tbl_data">
               	Form Module</td>
                <td class="tbl_data">
                <?php
				$sql_frms="SELECT idsyssubmodule,submodule FROM wfprocforms 
				INNER JOIN syssubmodule ON wfprocforms.syssubmodule_idsyssubmodule=syssubmodule.idsyssubmodule
				WHERE idwfprocforms=".$_SESSION['idform']."";
				$res_frms=mysql_query($sql_frms);
				$fet_frms=mysql_fetch_array($res_frms);
				?>
                <input type="text" readonly="readonly" value="<?php echo $fet_frms['submodule'];?>" />
                </td>
            </tr>
            <tr>
            	<td class="tbl_data">
               	Form Status 
                </td>
                <td class="tbl_data">
                <select name="frm_status">
                	<option value="0" <?php if ($fet_details['form_status']==0) { echo "selected=\"selected\""; }?>>Disabled</option>
                    <option value="1" <?php if ($fet_details['form_status']==1) { echo "selected=\"selected\""; }?>>Enabled</option>
                </select>
                </td>
			</tr>
            <tr>
            	<td class="tbl_data">
               	Description
                </td>
                <td class="tbl_data">
               	<textarea cols="30" rows="2" name="frm_desc"><?php echo $fet_details['form_description'];?></textarea>
                </td>
            </tr>
            <tr>
            	<td></td>
                <td height="50">
                <input type="hidden" value="edit" name="form_action" />
                <a href="#" onclick="document.forms['add_form'].submit()" id="button_save"></a>
                </td>
            </tr>
        </table>
    </form>
    <?php
	} else if (isset($success)) {
	echo "<div class=\"msg_success\">Your form has been updated successfully</div>";
	}
	?>
    </div>
</div>    
</body>
</html>
