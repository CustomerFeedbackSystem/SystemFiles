<?php
require_once('../assets_backend/be_includes/config.php');

//require_once('../Connections/connSystem.php');
//mysql_select_db($database_connSystem, $connSystem);

require_once('../assets_backend/be_includes/check_login.php');

if (isset($_GET['idform']))	
	{
	$_SESSION['idform']=mysql_real_escape_string(trim($_GET['idform']));
	}


//INSERT NEW FORM GROUP
if ( (isset($_POST['form_action'])) && ($_POST['form_action']=="add_newfrmgrp") )
	{
	//clean it up
	$groupname=mysql_real_escape_string(trim($_POST['frmgrouplbl']));
	
	if (strlen($groupname)<2)
		{
		$msg_error="<div class=\"msg_warning\">Please enter a valid Form Items Group Name</div>";
		}
	
	if (!isset($msg_error))
		{
		//check if unique
		$sql_uniquegroup="SELECT wfprocassetsgrouplbl FROM wfprocassetsgroup 
		WHERE wfprocassetsgrouplbl='".$groupname."' AND userteam_owner=".$_SESSION['MVGitHub_idacteam']." LIMIT 1";
		$res_uniquegroup=mysql_query($sql_uniquegroup);
		$num_uniquegroup=mysql_num_rows($res_uniquegroup);
		
		
		if ($num_uniquegroup < 1)
			{
			//process the request
			$sql_newgroup="INSERT INTO wfprocassetsgroup(wfprocassetsgrouplbl,wfprocforms_idwfprocforms,createdon,createdby,userteam_owner) 
			VALUES ('".$groupname."','".$_SESSION['idform']."','".$timenowis."','".$_SESSION['MVGitHub_idacname']."','".$_SESSION['MVGitHub_idacteam']."')";
			mysql_query($sql_newgroup);
			
			$msg="<div class=\"msg_success\">Form Item Group has been added successfully</div>";
			}
		}
	
	}
	

//
if (isset($_GET['wfitemgroupid']))
	{
	$_SESSION['wfitemgroupid']=trim($_GET['wfitemgroupid']);
	}
	
	
//UPDATE NEW FORM GROUP
if ( (isset($_POST['form_action'])) && ($_POST['form_action']=="edit_newfrmgrpname") )
	{
	//echo $_POST['form_action'];
	//clean it up
	$groupname=mysql_real_escape_string(trim($_POST['frmgrouplbl']));
	$_SESSION['wfgroupitemid']=mysql_real_escape_string(trim($_GET['wfitemgroupid']));
	
	
	if (strlen($groupname)<2)
		{
		$msg_error="<div class=\"msg_warning\">Please enter a valid Form Items Group Name</div>";
		}
	
	if (!isset($msg_error))
		{
		//check if unique
		$sql_uniquegroup="SELECT wfprocassetsgrouplbl FROM wfprocassetsgroup 
		WHERE wfprocassetsgrouplbl='".$groupname."' AND userteam_owner=".$_SESSION['MVGitHub_idacteam']." LIMIT 1";
		$res_uniquegroup=mysql_query($sql_uniquegroup);
		$num_uniquegroup=mysql_num_rows($res_uniquegroup);
		$fet_uniquegroup=mysql_num_rows($res_uniquegroup);
		
		
		if (($num_uniquegroup < 1) && ($fet_uniquegroup['wfprocassetsgrouplbl']!=$groupname) )
			{
			//process the request
			$sql_updategroup="UPDATE wfprocassetsgroup SET wfprocassetsgrouplbl='".$groupname."',
			modifiedon='".$timenowis."',
			modifiedby='".$_SESSION['MVGitHub_idacname']."'
			WHERE userteam_owner=".$_SESSION['MVGitHub_idacteam']." AND idwfprocassetsgroup=".$_SESSION['wfgroupitemid']." LIMIT 1";
			mysql_query($sql_updategroup);
			
			$msg="<div class=\"msg_success\">Form Item Group has been updated successfully</div>";
			}
		}
	
	}	
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
        	<td >Form Field Groups</td>
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
  
    <div style="padding:45px 5px 0px 5px">
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
    	<tr>
        	<td valign="top" width="50%" style="padding:8px 0px">
            <div class="table_header" style="padding:8px 5px">List Field Group | <a title="Add New Form Group to Group Form Items Together" href="<?php echo $_SERVER['PHP_SELF'];?>?do=new_form_itemlbl">Add  Section</a></div>
            <?php
			$res_sections=mysql_query('SELECT idwfprocassetsgroup,wfprocassetsgrouplbl FROM  wfprocassetsgroup 
			WHERE wfprocforms_idwfprocforms='.$_SESSION['idform'].' AND userteam_owner='.$_SESSION['MVGitHub_idacteam'].'');
			$fet_sections=mysql_fetch_array($res_sections);
			$num_sections=mysql_num_rows($res_sections);
			
			if ($num_sections < 1)
				{
				echo "<div class=\"msg_warning\">No Sections</div>";
				} else {
					do {
			?>
            <div class="tbl_data">
			<table border="0" cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td>
					<?php echo $fet_sections['wfprocassetsgrouplbl'];?>
                    </td>
                    <td width="30">
                    <a href="<?php echo $_SERVER['PHP_SELF'];?>?do=edit_form_itemgrouplbl&amp;wfitemgroupid=<?php echo $fet_sections['idwfprocassetsgroup'];?>" id="button_edit_small"></a>
                    </td>
			  </tr>
			</table>
                    </div>
            <?php
					} while ($fet_sections=mysql_fetch_array($res_sections));
				}
			?>	
          </td>
            <td valign="top">
            <?php
		//for inserting new form assets
		if ((isset($_GET['do'])) && ($_GET['do']=="new_form_itemlbl") )
			{
			if (isset($msg)) { echo $msg; }
			if (isset($msg_error)) { echo $msg_error; }
		?>
        <form method="post" action="" name="frmgroup" autocomplete="off">
        <table border="0" cellpadding="2" cellspacing="0" width="375" class="border_thick">
        		<tr>
                	<td height="24" colspan="2" class="table_header">New Group </td>
              </tr>
            	<tr>
                	<td class="tbl_data">
                    <strong>Form Group Name</strong>
                    </td>
                    <td class="tbl_data">
                   <input type="text" name="frmgrouplbl" />
                   </td>
                </tr>
                <tr>
                	<td></td>
                    <td>
                    <input type="hidden" value="add_newfrmgrp" name="form_action" />
                    <a href="#" onclick="document.forms['frmgroup'].submit()" id="button_save"></a>
                    </td>
                </tr>
		</table>                
        </form>
        <?php
		}
		?>
        
        <?php
		//for inserting new form assets
		if ((isset($_GET['do'])) && ($_GET['do']=="edit_form_itemgrouplbl") )
			{
			$wfitemgroupid = mysql_real_escape_string(trim($_GET['wfitemgroupid']));
			$sql_group="SELECT  idwfprocassetsgroup,wfprocassetsgrouplbl FROM wfprocassetsgroup WHERE idwfprocassetsgroup=".$wfitemgroupid." LIMIT 1";
			$res_group=mysql_query($sql_group);
			$fet_group=mysql_fetch_array($res_group);
			
			if (isset($msg)) { echo $msg; }
			if (isset($msg_error)) { echo $msg_error; }
		?>
        <form method="post" action="" name="editfrmgroup" autocomplete="off">
        <table border="0" cellpadding="2" cellspacing="0" width="375" class="border_thick">
        		<tr>
                	<td height="24" colspan="2" class="table_header">Edit Group</td>
              </tr>
            	<tr>
                	<td class="tbl_data">
                    <strong>Form Group Name</strong>
                    </td>
                    <td class="tbl_data">
                   <input type="text" value="<?php echo $fet_group['wfprocassetsgrouplbl'];?>" name="frmgrouplbl" />
                   </td>
                </tr>
                <tr>
                	<td></td>
                    <td>
                    <input type="hidden" value="edit_newfrmgrpname" name="form_action" />
                    <a href="#" onclick="document.forms['editfrmgroup'].submit()" id="button_save"></a>
                    </td>
                </tr>
		</table>                
        </form>
        <?php
		}
		?>
            </td>
        </tr>
    </table> 

	</div>
</div>    
</body>
</html>
