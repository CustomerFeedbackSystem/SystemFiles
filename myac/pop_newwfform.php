<?php
require_once('../assets_backend/be_includes/config.php');

//require_once('../Connections/connSystem.php');
//mysql_select_db($database_connSystem, $connSystem);

require_once('../assets_backend/be_includes/check_login.php');

if ( (isset($_POST['form_action'])) && ($_POST['form_action']=="add") )
	{
	//clean up variables
	$frmstatus=mysql_real_escape_string(trim($_POST['frm_status']));
	$frmdesc=mysql_real_escape_string(trim($_POST['frm_desc']));
	$frmname=mysql_real_escape_string(trim($_POST['frm_frmname']));
	
	//validate
	if ($frmstatus==1)
		{
		$error_1="<div class=\"msg_warning\">Please indicate the appropriate status for this form</div>";
		}
	if ($frmname<1)
		{
		$error_2="<div class=\"msg_warning\">Please select appropriate form module</div>";
		}
	if (!isset($error_2))
		{
		//check if that form is already in use 
		$res_inuse=mysql_query('SELECT idwfprocforms FROM wfprocforms WHERE syssubmodule_idsyssubmodule='.$frmname.' LIMIT 1');
		$num_inuse=mysql_num_rows($res_inuse);
		if ($num_inuse > 0)
			{
			$error_3="<div class=\"msg_warning\">Duplicate record detected</div>";
			}
		}
	
	//go ahead and add
		if ( (!isset($error_1)) && (!isset($error_2))  && (!isset($error_3))  )
			{
			$sql_newform="INSERT INTO wfprocforms (syssubmodule_idsyssubmodule,form_description,form_status,usrteam_idusrteam,createdon,createdby) 
			VALUES ('".$frmname."','".$frmdesc."','".$frmstatus."','".$_SESSION['MVGitHub_idacteam']."','".$timenowis."','".$_SESSION['MVGitHub_idacname']."')";
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
            New Custom Form
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
				$sql_frms="SELECT idsyssubmodule,submodule,
				(SELECT syssubmodule_idsyssubmodule FROM wfprocforms WHERE syssubmodule_idsyssubmodule=idsyssubmodule) as x
				FROM syssubmodule WHERE submod_type='FORM'";
				$res_frms=mysql_query($sql_frms);
				$fet_frms=mysql_fetch_array($res_frms);
				?>
               	<select name="frm_frmname">
                <option value="">---</option>
                <?php 
				do {
				?>
                <option <?php if ($fet_frms['x']==$fet_frms['idsyssubmodule']) { echo "disabled=\"disabled\"";  echo "title=\"This form is already configured\""; } ?> value="<?php echo $fet_frms['idsyssubmodule'];?>"><?php echo $fet_frms['submodule'];?></option>
                <?php
					} while ($fet_frms=mysql_fetch_array($res_frms));
				?>
                </select>
                </td>
            </tr>
            <tr>
            	<td class="tbl_data">
               	Form Status 
                </td>
                <td class="tbl_data">
                <select name="frm_status">
                	<option value="0">Disabled</option>
                    <option value="1" disabled="disabled">Enabled</option>
                </select>
                </td>
			</tr>
            <tr>
            	<td class="tbl_data">
               	Description
                </td>
                <td class="tbl_data">
               	<textarea cols="30" rows="2" name="frm_desc"></textarea>
                </td>
            </tr>
            <tr>
            	<td></td>
                <td height="50">
                <input type="hidden" value="add" name="form_action" />
                <a href="#" onclick="document.forms['add_form'].submit()" id="button_submit"></a>
                </td>
            </tr>
        </table>
    </form>
    <?php
	} else if (isset($success)) {
	echo "<div class=\"msg_success\">Your form has been added successfully.<br>You now need to configure the other parameters before activating it</div>";
	}
	?>
    </div>
</div>    
</body>
</html>
