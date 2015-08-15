<?php
require_once('../assets_backend/be_includes/check_login_easy.php');

//limited to zone
if ((isset($_GET['do'])) && ($_GET['do']=="restrict_zone_escalation") )
	{
	$turn_value=trim(mysql_escape_string($_GET['turn']));
	$sql_update_limit="UPDATE wfescalation SET limit_to_zone='".$turn_value."' WHERE wftskflow_idwftskflow=".$_SESSION['idflow']."";
	mysql_query($sql_update_limit);
	}

if ( (isset($_GET['do'])) && ($_GET['do']=="delete") )
	{
	$idnot=mysql_escape_string(trim($_GET['id']));
	//ensure this user owns this record
	$sql_checkuser = "SELECT idwfescalations FROM wfescalation 
	INNER JOIN wftskflow ON wfescalation.wftskflow_idwftskflow=wftskflow.idwftskflow 
	INNER JOIN wfproc ON wftskflow.wfproc_idwfproc = wfproc.idwfproc WHERE wfproc.usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']." LIMIT 1  ";
	$res_checkuser = mysql_query($sql_checkuser);
	$num_checkuser = mysql_num_rows($res_checkuser);
	
	if ($num_checkuser < 1) //if this record does not exist for this userteam, then error
		{
		$msg = "<span class=\"msg_warning\">".$msg_warn_contactadmin."</span>";
		} else { //else it is ok just delete
		$sql_delete="DELETE FROM wfescalation WHERE idwfescalations=".$idnot." LIMIT 1 ";		
		mysql_query($sql_delete);
		$msg = "<span class=\"msg_success\">".$msg_success_delete."</span>";
		}
	}


//process new inputs
if ( (isset($_POST['formaction'])) && ($_POST['formaction']=="save_escalation") )
	{
	$drole=mysql_escape_string(trim($_POST['usrrole']));
	$dtat=mysql_escape_string(trim($_POST['tat']));
	$dtat_cat=mysql_escape_string(trim($_POST['tat_cat']));
	$esc_method=mysql_escape_string(trim($_POST['escalation_method']));
	
	
	if ($dtat_cat=="Days")
		{
		$com_timeframe = ($dtat*24*60*60);
		} else if ($dtat_cat=="Hours") 
		{
		$com_timeframe = ($dtat*60*60);
		} else {
		$com_timeframe = 0;
		}
	
	if ( ($esc_method==1) || ($esc_method==3) ) 
	{
	 //if the escalation step is with this step
	//find if the escalation time exceeds the set TaT for this task
	$sql_tat = "SELECT wftsktat FROM wftskflow WHERE idwftskflow=".$_SESSION['idflow']." LIMIT 1";
	$res_tat = mysql_query($sql_tat);
	$fet_tat = mysql_fetch_array($res_tat);
	$num_tat = mysql_num_rows($res_tat );
	
	$curr_tat=$fet_tat['wftsktat'];
	
	} else {
	
	$curr_tat=$_SESSION['workflow_tat'];
	
	}
	
	
	if ($com_timeframe < 1)
		{
		$error = "<span class=\"msg_warning\">".$msg_warning_notat."</span>";
		}
	if  ( ($curr_tat < $com_timeframe) && ( ($esc_method==1) || ($esc_method==2) ) )
		{
		$error_1 = "<span class=\"msg_warning\">".$msg_warning_esctat."</span>";
		}
		
	if ((isset($num_tat)) && ($num_tat < 1))
		{
		$error_2= "<span class=\"msg_warning\">".$msg_warn_contactadmin."</span>";
		}
	
		//then check if this record already exist
		$sql_duplicate = "SELECT * FROM wfescalation WHERE wftskflow_idwftskflow=".$_SESSION['idflow']." LIMIT 1 ";
		$res_duplicate = mysql_query($sql_duplicate);
		$num_duplicate = mysql_num_rows($res_duplicate);

	
//	if (
	
	//validate form inputs
	if (  (!isset($error)) && (!isset($error_1)) && (!isset($error_2)) && ($drole >0) && ($num_duplicate==0)  ) //since all the fields have a value
		{
		
		$sql_insert = "INSERT INTO wfescalation (usrrole_idusrrole,wftskflow_idwftskflow,leadtime,createdby,createdon,time_to_deadline) 
		VALUES('".$drole."','".$_SESSION['idflow']."','".$com_timeframe."','".$_SESSION['MVGitHub_idacname']."','".$timenowis."','".$esc_method."')";
		mysql_query($sql_insert);

		$msg_ok = "<span class=\"msg_success\">".$msg_changes_saved."</span>";		
		}
	
	}


//process updated inputs
if ((isset($_POST['formaction'])) && ($_POST['formaction']=="save_updates") )
	{
	//set session for this update
	if (isset($_GET['wfe']))
	{
	$_SESSION['idnotifi'] = trim($_GET['wfe']);
	}
	
	$drole=mysql_escape_string(trim($_POST['usrrole']));
	$dtat=mysql_escape_string(trim($_POST['tat']));
	$dtat_cat=mysql_escape_string(trim($_POST['tat_cat']));
	$esc_method=mysql_escape_string(trim($_POST['escalation_method']));
	
	if ($dtat_cat=="Days")
		{
		$com_timeframe = ($dtat*24*60*60);
		} else if ($dtat_cat=="Hours") 
		{
		$com_timeframe = ($dtat*60*60);
		} else {
		$com_timeframe = 0;
		}
	
	//echo $com_timeframe;
	if ( ($esc_method==1) || ($esc_method==3) ) 
		{
		 //if the escalation step is with this step
		//find if the escalation time exceeds the set TaT for this task
		$sql_tat = "SELECT wftsktat FROM wftskflow WHERE idwftskflow=".$_SESSION['idflow']." LIMIT 1";
		$res_tat = mysql_query($sql_tat);
		$fet_tat = mysql_fetch_array($res_tat);
		$num_tat = mysql_num_rows($res_tat );
		
		$curr_tat=$fet_tat['wftsktat'];
		
		} else {
		
		$curr_tat=$_SESSION['workflow_tat'];
		
		}
	
	if ($com_timeframe < 1)
		{
		$uerror = "<span class=\"msg_warning\">".$msg_warning_notat."</span>";
		}
	if  ( ($curr_tat < $com_timeframe) && ( ($esc_method==1) || ($esc_method==2) ) )
		{
		$uerror_1 = "<span class=\"msg_warning\">".$msg_warning_esctat."</span>";
		}
	if ($num_tat < 1)
		{
		$uerror_2= "<span class=\"msg_warning\">".$msg_warn_contactadmin."</span>";
		}
	

	//validate form inputs
	if (  (!isset($error)) && (!isset($error_1)) && (!isset($error_2)) && ($drole >0)  ) //sinc all the fields have a value
		{
		
		$sql_update = "UPDATE wfescalation SET usrrole_idusrrole='".$drole."',
		wftskflow_idwftskflow='".$_SESSION['idflow']."',
		leadtime='".$com_timeframe."',
		createdby='".$_SESSION['MVGitHub_idacname']."',
		createdon='".$timenowis."' 
		time_to_deadline='".$esc_method."'
		WHERE idwfescalations=".$_SESSION['idnotifi']." AND wftskflow_idwftskflow=".$_SESSION['idflow']."  LIMIT 1";
		
		mysql_query($sql_update);
		$umsg_ok = "<span class=\"msg_success\">".$msg_changes_saved."</span>";		
		}

	} //close form update action
	
//list the current records
$sql_escalations="SELECT idwfescalations,usrrole_idusrrole,leadtime,usrrolename,limit_to_zone,time_to_deadline FROM wfescalation
INNER JOIN usrrole ON wfescalation.usrrole_idusrrole=usrrole.idusrrole WHERE wftskflow_idwftskflow=".$_SESSION['idflow']." LIMIT 1";
$res_escalations=mysql_query($sql_escalations);
$fet_escalations=mysql_fetch_array($res_escalations);
$num_escalations=mysql_num_rows($res_escalations);
//echo $sql_notification;
//exit;

?>
<?php // if ( (!isset($_GET['do'])) || ( (isset($_GET['do'])) && ($_GET['do']!="add_escalation"))  ) { ?>
<?php // if (!isset($_GET['do'])) { ?>
<div style="padding:5px 5px 5px 15px">
	<a href="<?php echo $_SERVER['PHP_SELF'];?>?do=add_escalation" id="button_newesc"></a>
</div>
<?php // } ?>
<div>
<?php
	if (isset($msg_ok)) { echo $msg_ok; } 
	if (isset($error)) { echo $error; } 
	if (isset($error_1)) { echo $error_1; } 
	if (isset($error_2)) { echo $error_2; } 
?>
</div>
<?php if ((isset($_GET['do'])) && ($_GET['do']=="add_escalation") ) { ?>
<div>
<form action="" method="post" name="escalation">
<table border="0" class="border_thin" width="100%" cellpadding="2" cellspacing="0">
	<tr>
    	<td colspan="2" class="tbl_h">
        <?php echo $lbl_newesc;?>
        </td>
    </tr>
	<tr>
    	<td height="45" class="tbl_data">
        <?php echo $lbl_role;?>        </td>
        <td height="45" class="tbl_data">
        <?php
		$sql_usrrole="SELECT DISTINCT idusrrole,usrrolename,userteamzonename,utitle,fname,lname,idusrac FROM usrrole 
		INNER JOIN usrteamzone ON usrrole.usrteamzone_idusrteamzone=usrteamzone.idusrteamzone
		LEFT JOIN usrac ON usrrole.idusrrole=usrac.usrrole_idusrrole
		WHERE usrteamzone.usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']." ORDER BY userteamzonename,usrrolename ASC";
        $res_usrrole=mysql_query($sql_usrrole);
        $num_usrrole=mysql_num_rows($res_usrrole);
        $fet_usrrole=mysql_fetch_array($res_usrrole);
		?>
        <select name="usrrole">
        <?php
		do {
		?>
        <option title="<?php if ($fet_usrrole['idusrac']>0) {  echo $fet_usrrole['utitle']." ".$fet_usrrole['lname']." ".$fet_usrrole['fname']; } else { echo $lbl_vacant; }?>"  value="<?php echo $fet_usrrole['idusrrole'];?>"><?php echo "[".$fet_usrrole['userteamzonename']."] ".$fet_usrrole['usrrolename']; if ($fet_usrrole['idusrac']>0) { echo "*"; }?></option>
        <?php
		} while ($fet_usrrole=mysql_fetch_array($res_usrrole));
		?>
        </select>        </td>
       
    </tr>
    <tr>
    	<td height="45" class="tbl_data">&nbsp;</td>
        <td height="45" class="tbl_data">
        <?php
		$sql_listtat="SELECT idtatlist,tat_lbl,tat_desc FROM wfescalation_tatlist";
		$res_listtat=mysql_query($sql_listtat);
		$fet_listtat=mysql_fetch_array($res_listtat);
	//	echo $sql_tat;
		?>
        <select name="escalation_method">
         <?php
			do {
		?>
        <option value="<?php echo $fet_listtat['idtatlist'];?>"><?php echo $fet_listtat['tat_lbl'];?></option>
        <?php
			} while ($fet_listtat=mysql_fetch_array($res_listtat));
		?>
        </select>
        <span style="padding:0px 8px">|</span>
<input onkeyup="res(this,numb);" type="text" value="<?php if ( (isset($_POST['tat'])) && ($_POST['tat'])) { echo $_POST['tat'];} ?>" size="4" maxlength="2" name="tat" /> 
                    <select name="tat_cat">
                    <option <?php if ( (isset($_POST['tat_cat'])) && ($_POST['tat_cat']=="")){ echo "selected=\"selected\""; } ?> value="">---</option>
                    <option <?php if ( (isset($_POST['tat_cat'])) && ($_POST['tat_cat']=="".$lbl_hours."")){ echo "selected=\"selected\""; } ?> value="Hours"><?php echo $lbl_hours;?></option>
                    <option <?php if ( (isset($_POST['tat_cat'])) && ($_POST['tat_cat']=="".$lbl_days."")){ echo "selected=\"selected\""; } ?> value="Days"><?php echo $lbl_days;?></option>
                    </select>        </td>
    </tr>
    <tr>
    	<td></td>
    	<td height="45" class="tbl_data" >
<table border="0" style="margin:0px" cellpadding="0" cellspacing="0">
                        	<tr>
                            	<td>
                                <a href="#" onclick="document.forms['escalation'].submit()" id="button_save"></a>                                </td>
                                <td style="padding:0px 0px 0px 10px">
                                <input type="hidden" value="save_escalation" name="formaction" />
                               <a href="<?php echo $_SERVER['PHP_SELF'];?>" id="button_cancel" onclick="return confirm('<?php echo $msg_prompt_sure_cancel;?>');"></a>                                </td>
                            </tr>
                        </table>
                        </td>
    </tr>
</table>
</form>
</div>
<?php } ?>
<div>
<?php
if ((isset($_POST['formaction'])) && ($_POST['formaction']=="save_updates") )
	{
	if (isset($umsg_ok)) { echo $umsg_ok; } 
	if (isset($uerror)) { echo $uerror; } 
	if (isset($uerror_1)) { echo $uerror_1; }
	if (isset($uerror_2)) { echo $uerror_2; }
	}
?>
</div>
<?php
if ( (isset($_GET['do'])) && ($_GET['do']=="update")) { ?>

<div>
<form action="" method="post" name="escalation_update">
<table border="0" class="border_thick" width="100%" cellpadding="2" cellspacing="0">
	<tr>
    	<td colspan="2" class="tbl_h">
        <?php echo $lbl_updateesc;?>
        </td>
    </tr>
	<tr>
    	<td height="45" class="tbl_data">
        <?php echo $lbl_role;?>        </td>
        <td height="45" class="tbl_data">
        <?php
		$sql_usrrole="SELECT DISTINCT idusrrole,usrrolename,userteamzonename,utitle,fname,lname,idusrac FROM usrrole 
					INNER JOIN usrteamzone ON usrrole.usrteamzone_idusrteamzone=usrteamzone.idusrteamzone
					LEFT JOIN usrac ON usrrole.idusrrole=usrac.usrrole_idusrrole
					WHERE usrteamzone.usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']."  ORDER BY usrrolename ASC";
		$res_usrrole=mysql_query($sql_usrrole);
		$num_usrrole=mysql_num_rows($res_usrrole);
		$fet_usrrole=mysql_fetch_array($res_usrrole);
		?>
        <select name="usrrole">
        <?php
		do {
		?>
        <option title="<?php if ($fet_usrrole['idusrac']>0) {  echo $fet_usrrole['utitle']." ".$fet_usrrole['lname']." ".$fet_usrrole['fname']; } else { echo $lbl_vacant; }?>" <?php if ($fet_escalations['usrrole_idusrrole']==$fet_usrrole['idusrrole']) { echo "selected=\"selected\""; } ?> value="<?php echo $fet_usrrole['idusrrole'];?>"><?php echo "[".$fet_usrrole['userteamzonename']."] ".$fet_usrrole['usrrolename']; if ($fet_usrrole['idusrac']>0) { echo "*"; }?></option>
        <?php
		} while ($fet_usrrole=mysql_fetch_array($res_usrrole));
		?>
        </select>        </td>
       
    </tr>
    <tr>
    	<td height="45" class="tbl_data">&nbsp;</td>
        <td height="45" class="tbl_data">
        <?php
		$sql_listtat="SELECT idtatlist,tat_lbl,tat_desc FROM wfescalation_tatlist";
		$res_listtat=mysql_query($sql_listtat);
		$fet_listtat=mysql_fetch_array($res_listtat);
		//echo $sql_listtat;
		?>
        <select name="escalation_method">
        <?php
		
			do {
		?>
        <option <?php if ($fet_escalations['time_to_deadline']==$fet_listtat['idtatlist']) { echo "selected=\"selected\""; } ?> value="<?php echo $fet_listtat['idtatlist'];?>"><?php echo $fet_listtat['tat_lbl'];?></option>
        <?php
			} while ($fet_listtat=mysql_fetch_array($res_listtat));
		?>
        </select>
         <span style="padding:0px 8px">|</span>
<input onkeyup="res(this,numb);" type="text" value="<?php if ( (isset($_POST['tat'])) && ($_POST['tat'])) { echo $_POST['tat'];} ?><?php if (!isset($_POST['tat'])) { if ($fet_escalations['leadtime']>"172800"){ echo ($fet_escalations['leadtime']/(60 * 60 * 24));} else { echo ($fet_escalations['leadtime']/(60 * 60));} }?>" size="4" maxlength="2" name="tat" /> 
                    <select name="tat_cat">
                    <option <?php if ( (isset($_POST['tat_cat'])) && ($_POST['tat_cat']=="")){ echo "selected=\"selected\""; } ?> value="">---</option>
                    <option <?php if ( (isset($_POST['tat_cat'])) && ($_POST['tat_cat']=="Hours")){ echo "selected=\"selected\""; } ?><?php if (!isset($_POST['tat_cat'])){ if ($fet_escalations['leadtime']<="172800"){ echo "selected=\"selected\""; } } ?> value="Hours">Hours</option>
                    <option <?php if ( (isset($_POST['tat_cat'])) && ($_POST['tat_cat']=="Days")){ echo "selected=\"selected\""; } ?><?php if (!isset($_POST['tat_cat'])){ if ($fet_escalations['leadtime']>"172800"){ echo "selected=\"selected\""; } } ?> value="Days">Days</option>
                    </select>        </td>
    </tr>
    <tr>
    <td></td>
    	<td height="45" class="tbl_data" >
<table border="0" style="margin:0px" cellpadding="0" cellspacing="0">
                        	<tr>
                            	<td>
                                <a href="#" onclick="document.forms['escalation_update'].submit()" id="button_save"></a>
                                </td>
                                <td style="padding:0px 0px 0px 10px">
                                <input type="hidden" value="save_updates" name="formaction" />
                               <a href="<?php echo $_SERVER['PHP_SELF'];?>" id="button_cancel" onclick="return confirm('<?php echo $msg_prompt_sure_cancel;?>');"></a>
                               </td>
                            </tr>
                        </table>
                        </td>
    </tr>
</table>
</form>
</div>
<?php } ?>
<div class="tbl_data" style="text-align:left">
<?php
if ($fet_escalations > 0)
	{	
    	if ($fet_escalations['limit_to_zone']==1)
						{
						$switch_to="0";
						$suffix="_on";
						} else {
						$switch_to="1";
						$suffix="";
						}
						
						echo "<table border=0><tr>
						<td>
	                    <a href=\"#\" style=\"text-decoration:none;\" class=\"tooltip\">
						<img src=\"../assets_backend/icons/help.gif\" border=\"0\" align=\"absmiddle\" />
						<span>".$msg_tip_limittozone."</span></a>
						</td>
						<td width=\"20\"><a href=\"".$_SERVER['PHP_SELF']."?do=restrict_zone_escalation&amp;turn=".$switch_to."\" id=\"button_check".$suffix."\"></a></td>";						
						echo "<td align=\"left\"><strong>".$lbl_ltdregion_title."</strong></td></tr></table>";
    }
?>	
</div>

<div>
<table border="0" cellpadding="2" cellspacing="0" width="100%">
<tr>
		<td class="tbl_h"><?php echo $lbl_role;?></td>
	  <td  class="tbl_h" ><?php echo $lbl_timing;?></td>
      <td class="tbl_h" >&nbsp;</td>
        
    </tr>
                <?php
if ($fet_escalations > 0)
	{			
					do {
				?>
                <tr <?php 
// technocurve arc 3 php mv block2/3 start
echo " style=\"background-color:$mocolor\" onMouseOver=\"this.style.backgroundColor='$mocolor3'\" onMouseOut=\"this.style.backgroundColor='$mocolor'\"";
// technocurve arc 3 php mv block2/3 end
?>>
                	<td class="tbl_data">
					<a href="<?php echo $_SERVER['PHP_SELF'];?>?do=update&amp;wfe=<?php echo $fet_escalations['idwfescalations'];?>"><?php echo $fet_escalations['usrrolename'];?></a>
                    </td>
                    <td class="tbl_data">
                    <?php 
				if ($fet_escalations['leadtime']>"172800")
					{ //if greater than 48 hours, then make it days, else keep it hours
					echo ($fet_escalations['leadtime']/(60 * 60 * 24))."&nbsp;".$lbl_days;
					} else {
					echo ($fet_escalations['leadtime']/(60 * 60))."&nbsp;".$lbl_hours;
					}
				?>
                    </td>
                    <td class="tbl_data">
                    <a onclick="return confirm('<?php echo $msg_prompt_delete;?>');" href="<?php echo $_SERVER['PHP_SELF'];?>?do=delete&amp;id=<?php echo $fet_escalations['idwfescalations'];?>" id="button_delete_small"></a>
                    </td>
				</tr>
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
			
			} while ($fet_escalations=mysql_fetch_array($res_escalations));
	} else {
?>
<tr>
	<td colspan="3" height="40">
    <div class="msg_instructions">
    <?php echo $msg_noesc;?>
    </div>    
    </td>
</tr>
<?php } ?>
 </table>
</div>
