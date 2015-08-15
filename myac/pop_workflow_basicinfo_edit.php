<?php
require_once('../assets_backend/be_includes/config.php');
//require_once('../Connections/connSystem.php');
//mysql_select_db($database_connSystem, $connSystem);

require_once('../assets_backend/be_includes/check_login_easy.php');

	$sql_wf="SELECT * FROM wfproc WHERE usrteamzone_idusrteamzone=".$_SESSION['MVGitHub_userteamzoneid']." AND idwfproc=".$_SESSION['wfselected']." LIMIT 1";
	$res_wf=mysql_query($sql_wf);
	$fet_wf=mysql_fetch_array($res_wf);
	$num_wf=mysql_num_rows($res_wf);
	
	//process this form
if ( (isset($_POST['formaction'])) && ($_POST['formaction']=="save") )
	{
	//first, clean up the data
	$ddesc=mysql_escape_string(trim($_POST['wfdesc']));
	$dtat=mysql_escape_string(trim($_POST['tat']));
	$dtat_cat=mysql_escape_string(trim($_POST['tat_cat']));
	$dwfname=mysql_escape_string(trim($_POST['wfname']));
	$dstatus=mysql_escape_string(trim($_POST['wfstate']));
	
	if (isset($_POST['mobacc']))
		{
		$dmobacc=1;
		} else {
		$dmobacc=0;
		}
	
	if ($_SESSION['wfprocname']!=$dwfname) //if 
		{
		//check for duplicate workflow for this userteamzone
		$sql_exists = "SELECT idwfproc FROM wfproc WHERE usrteamzone_idusrteamzone=".$_SESSION['MVGitHub_userteamzoneid']." AND wfprocname='".$dwfname."' LIMIT 1";
		$res_exists = mysql_query($sql_exists);
		$num_exists = mysql_num_rows($res_exists);
		}
	
	//second, look for the required fields
	if (strlen($ddesc) < 1)
		{
		$error_1 = "<div class=\"msg_warning\">".$msg_warning_desc_required."</div>";
		}
	if (($dtat<1) || ($dtat==""))
		{
		$error_2 = "<div class=\"msg_warning\">".$msg_warning_tat_required."</div>";
		}
	if (strlen($dtat_cat)<2)
		{
		$error_3 = "<div class=\"msg_warning\">".$msg_warning_tatcat_required."</div>";
		}
	
	if ($_SESSION['wfprocname']!=$dwfname) //if what is keyed in is different from what was in the db, thats the only time to check
		{
			if ($num_exists > 0) //if record already exists
			{
			$error_4 = "<div class=\"msg_warning\">".$msg_warning_duplicate_wf."</div>";
			}
		}
		
	//if there is no error, then process	
		if ( (!isset($error_1)) && (!isset($error_2)) && (!isset($error_3))  && (!isset($error_4)) )
			{
			
			//first, lets fix the hours or days to seconds depending on the choice selected
			if ($dtat_cat=="Days")
				{
				$com_timeframe = $dtat*24*60*60;
				}
			if ($dtat_cat=="Hours")
				{
				$com_timeframe = $dtat*60*60;
				}
			//now, lets add the record
			$sql_wf="UPDATE wfproc SET wfprocname='".$dwfname."',wfproctat='".$com_timeframe."',wfprocdesc='".$ddesc."',mobileaccess='".$dmobacc."',wfstatus='".$dstatus."',modifiedby='".$_SESSION['MVGitHub_idacname']."',modifiedon='".$timenowis."'
			WHERE idwfproc=".$_SESSION['wfselected']." LIMIT 1";
			mysql_query($sql_wf);
			
			?>
			<script language="javascript">
			alert ('<?php echo $msg_workflow_updated;?>');
			window.location='<?php echo "pop_success.php?uction=edit_workflow&wf=".$_SESSION['wfselected']."&fa=saved";?>';
			</script>		
			<?php
			} //close if no error
		
	} //close if form is submitted
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Window</title>
<link href="../assets_backend/css/style.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div>
	<div class="tbl_sh">
    <table border="0" width="100%" cellpadding="0" cellspacing="0">
						<tr>
                            <td>
                            <?php echo $lbl_wfbasicinfo; ?>
                            </td>
                            <td align="right" style="position:absolute; right:5px">
                            <a href="#" onClick="parent.tb_remove();" id="button_closewin"></a>
                            </td>
                        </tr>
                    </table>
    </div>
    <div>
       <form method="post" action="" name="wfbasics">
        	<table border="0" cellpadding="3" cellspacing="0">
				<tr>
                	<td width="171" height="35" class="tbl_data">
                    <strong><?php echo $lbl_wfname;?></strong>
                    </td>
              <td width="336" height="35" class="tbl_data">
			  <input type="text" maxlength="60" size="40" value="<?php echo $fet_wf['wfprocname'];?>" name="wfname" /></td>
           	  </tr>
                <tr>
                	<td class="tbl_data">
                   <strong> <?php echo $lbl_description;?></strong>                   </td>
                  <td class="tbl_data">
                    <textarea cols="30" rows="3" name="wfdesc"><?php if (isset($_POST['wfdesc'])){ echo $_POST['wfdesc'];} if (!isset($_POST['wfdesc'])){ echo $fet_wf['wfprocdesc']; }?></textarea>                  </td>
                </tr>
                 <tr>
                	<td class="tbl_data" height="30">
                    <strong><?php echo $lbl_tat;?></strong>                    </td>
                   <td height="30" class="tbl_data">
                    <input onKeyUp="res(this,numb);" type="text" value="<?php if ( (isset($_POST['tat'])) && ($_POST['tat'])) { echo $_POST['tat'];} ?><?php if (!isset($_POST['tat'])) { if ($fet_wf['wfproctat']>"172800"){ echo ($fet_wf['wfproctat']/(60 * 60 * 24));} else { echo ($fet_wf['wfproctat']/(60 * 60));} }?>" size="4" maxlength="2" name="tat" /> 
                    <select name="tat_cat">
                    <option <?php if ( (isset($_POST['tat_cat'])) && ($_POST['tat_cat']=="")){ echo "selected=\"selected\""; } ?> value="">---</option>
                    <option <?php if ( (isset($_POST['tat_cat'])) && ($_POST['tat_cat']=="Hours")){ echo "selected=\"selected\""; } ?><?php if (!isset($_POST['tat_cat'])){ if ($fet_wf['wfproctat']<="172800"){ echo "selected=\"selected\""; } } ?> value="Hours">Hours</option>
                    <option <?php if ( (isset($_POST['tat_cat'])) && ($_POST['tat_cat']=="Days")){ echo "selected=\"selected\""; } ?><?php if (!isset($_POST['tat_cat'])){ if ($fet_wf['wfproctat']>="172800"){ echo "selected=\"selected\""; } } ?> value="Days">Days</option>
                    </select>                   </td>
              </tr>
              <tr>
                	<td class="tbl_data" height="30">
                    <strong><?php echo $lbl_status;?></strong>
                    </td>
                    <td class="tbl_data">
                    <select name="wfstate">
                    	<option value="1" <?php if ($fet_wf['wfstatus']==1) { echo "selected=\"selected\""; }?>>ON (Active &amp; Running)</option>
                        <option value="0" <?php if ($fet_wf['wfstatus']==0) { echo "selected=\"selected\""; }?>>OFF (Inactive)</option>
                    </select>
                    <?php
					
					?>
                    </td>
              </tr>
                 <tr>
                	<td class="tbl_data" height="30">
                   <!-- <strong><?php // echo $lbl_mobileaccess;?></strong>-->                    </td>
                   <td height="30" class="tbl_data">
                    <input type="hidden" value="1" name="mobacc"   />
                    </td>
              </tr>
                <tr>
                	<td colspan="2">
                    <table border="0" style="margin:5px 10px 5px 20px">
                        	<tr>
                            	<td>
                                <a href="#" onClick="document.forms['wfbasics'].submit()" id="button_save"></a>                                </td>
                                <td style="padding:0px 0px 0px 10px">
                                <input type="hidden" value="save" name="formaction" />
                               <a href="#"  id="button_cancel" onClick="return confirm('<?php echo $msg_prompt_sure_cancel;?>');parent.tb_remove();"></a>                                </td>
                            </tr>
                        </table>                    </td>
                </tr>
			</table>
		</form>
    </div>
</div>
</body>
</html>
