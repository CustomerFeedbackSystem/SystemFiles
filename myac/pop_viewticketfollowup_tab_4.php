<?php
require_once('../assets_backend/be_includes/check_login_easy.php');

if  ((isset($_POST['formaction'])) && ($_POST['formaction']=="newaction") )
	{
	
	//clean up data
	$action_details=preg_replace('/[^a-z\-_0-9\.:@\/\s]/i','',mysql_escape_string(trim($_POST['actiondetails'])));
	$action_time=preg_replace('/[^a-z\-_0-9\.:\/\s]/i','',mysql_escape_string(trim($_POST['timeaction'])));
	if (strlen($action_time) > 1) { $action_time_post=date("Y-m-d H:i:s",strtotime($action_time)); }
	$activity=preg_replace('/[^a-z\-_0-9\.:\/\s]/i','',mysql_escape_string(trim($_POST['activity'])));
	//validate
	if ($activity < 1) 
		{
		$error_1="<span class=\"msg_warning\">- ".$msg_warning_activity."</span><br>";
		}
	if (strlen($action_time) < 10) 
		{
		$error_2="<span class=\"msg_warning\">- ".$msg_warning_date."</span><br>";
		}
	if (strlen($action_details) < 1) 
		{
		$error_3="<span class=\"msg_warning\">- ".$msg_warning_actdetails."</span><br>";
		}
	
	//post
	if ( (!isset($error_1)) && (!isset($error_2)) && (!isset($error_3)) )
		{
		//process the request
		//log this as an activity
		$sql_logactivity="INSERT INTO tktactivity (idtktesclevel, idtktinPK, idtktactivitytype, activity_date, activity_details, entered_by, entered_by_role, addedby, addedon, modifiedby, modifiedon) 
		VALUES ('1', '".$_SESSION['tktid']."', '".$activity."', '".$action_time_post."', '".$action_details."','".$_SESSION['MVGitHub_idacname']."', '".$_SESSION['MVGitHub_iduserrole']."', '".$_SESSION['MVGitHub_idacname']."','".$timenowis."', '0000-00-00 00:00:00','0')";
		mysql_query($sql_logactivity);
		
		$msg_ok="<span class=\"msg_success\">".$msg_changes_saved."</span>";
		}
	
	//acknowledge
	}
?>

<div class="table_header">
<?php echo $lbl_historyact;?>
</div>

<div>
<table border="0" width="100%">
	<tr>
    	<td width="50%" valign="top">
        <?php
	   $sql_activity="SELECT activity_date,activity_details,usrname,fname,lname,activitytype FROM tktactivity INNER JOIN usrac ON tktactivity.entered_by=usrac.idusrac INNER JOIN tktactivitytype ON tktactivity.idtktactivitytype=tktactivitytype.idtktactivitytype WHERE idtktinPK=".$_SESSION['tktid']." ORDER BY activity_date ASC";
	   $res_activity=mysql_query($sql_activity);
	   $num_activity=mysql_num_rows($res_activity);
	   $fet_activity=mysql_fetch_array($res_activity);
	   
	   if ($num_activity < 1)
	   	{
		echo "<div class=\"msg_warning\" style=\"text-align:center\">".$msg_no_activity_tkt."</div>";
		} else {
		do {
		echo "<div class=\"bline\" style=\"margin:10px 0px 10px 0px\">";
		echo "<div class=\"text_small\">".date("D, M d, Y H:i",strtotime($fet_activity['activity_date']))."</div>";
		echo "<div class=\"text_body\" style=\"font-weight:bold\">".$fet_activity['activitytype']."</div>";
		echo "<div class=\"text_body\">".$fet_activity['activity_details']."</div>";
		echo "<div class=\"text_small\"> by : ".$fet_activity['fname']." ".$fet_activity['lname']."</div>";
		echo "</div>";
			} while ($fet_activity=mysql_fetch_array($res_activity));
		}
		
	   ?>        </td>
        <td width="50%" valign="top"> 
        	<form method="post" action="" name="newaction">
            	<table border="0" width="100%" cellpadding="0" cellspacing="0" class="border_thick">
           	  <tr>
                    	<td height="27" colspan="2" class="tbl_h2">
                        <?php echo $lbl_report_action;?>
                        </td>
                    </tr>
                    <tr>
                    	<td colspan="2">
                        <div>
                        <?php 
                        if (isset($msg_ok)) { echo $msg_ok;}
                        if (isset($error_1)) { echo $error_1;}
                        if (isset($error_2)) { echo $error_2;}
                        if (isset($error_3)) { echo $error_3;}
                        ?>
                        </div>
                        </td>
                    </tr>
                	<tr>
                    	<td height="40">
                          <strong><?php echo $lbl_report_activity;?></strong> </td>
                      <td height="40">
                        <select name="activity">
                        <?php
						$sql_activitylist="SELECT  idtktactivitytype,tktactivityclass_idtktactivityclass,activitytype,activitytypedesc FROM tktactivitytype WHERE tktactivityclass_idtktactivityclass=2 ";
						$res_activitylist=mysql_query($sql_activitylist);
						$fet_activitylist=mysql_fetch_array($res_activitylist);
							
						?>
                        <option value="">----</option>
                        	<?php
							do {
							echo "<option value=\"".$fet_activitylist['idtktactivitytype']."\">".$fet_activitylist['activitytype']."</option>";
							} while ($fet_activitylist=mysql_fetch_array($res_activitylist));
							?>
                        </select>
                        </td>
                  </tr>
                    <tr>
                    	<td height="40">
                         
                         <strong> <?php
						echo $lbl_reportby;
						?></strong>
                       </td>
                      <td height="40">
                        <input type="text" readonly="readonly" style="background-color:#CCCCCC" value="<?php echo $_SESSION['MVGitHub_acname'];?>" />
                        </td>
                  </tr>
                     <tr>
                    	<td height="40">
                         
                        <strong>  <?php
						echo $lbl_timeaction;
						?></strong>
                        </td>
                       <td height="40">
                       <input size="15" onClick="displayDatePicker('timeaction');" name="timeaction" style="background-color:#F5F5F5" type="text" id="timeaction" value="<?php if (isset($_SESSION['timeaction'])) { echo $_SESSION['timeaction']; } ?>" readonly="readonly"> 
                        <img src="../assets_backend/btns/cal.gif" alt="Pick a date" width="30" height="30" border="0" align="absmiddle" onClick="displayDatePicker('timeaction');">                        
                        </td>
                  </tr>
                  <tr>
                  	<td valign="top">
                      <strong><?php echo $lbl_notes_action;?></strong> </td>
                    <td>
                   	  <textarea cols="15" rows="3" name="actiondetails"></textarea>
                    </td>
               </tr>
                     <tr>
                    	<td></td>
                        <td height="50">
                        <a href="#" id="button_submit" onclick="document.forms['newaction'].submit()"></a>  
                        <input type="hidden" value="newaction" name="formaction" />
                        </td>
                  </tr>
                </table>
            </form>
      </td>   
	</tr>
</table>
</div>	   