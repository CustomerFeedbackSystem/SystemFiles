<?php
require_once('../assets_backend/be_includes/check_login_easy.php');

//wpheader
if (isset($_GET['wpid']))
	{
	$_SESSION['idworkplan']=preg_replace('/[^a-z\-_0-9\.:@\/\s]/i','',mysql_escape_string(trim($_GET['wpid'])));
	}
//save
if ( (isset($_POST['formaction'])) && ($_POST['formaction']=="save_notes") )	
	{
	//clean up values
	$nstatus=preg_replace('/[^a-z\-_0-9\.:@\/\s]/i','',mysql_escape_string(trim($_POST['newstatus'])));
	$nnotes=preg_replace('/[^a-z\-_0-9\.:@\/\s]/i','',mysql_escape_string(trim($_POST['notes'])));
	$msgsms=preg_replace('/[^a-z\-_0-9\.:@\/\s]/i','',mysql_escape_string(trim($_POST['msg_sms'])));
	$msgemail=preg_replace('/[^a-z\-_0-9\.:@\/\s]/i','',mysql_escape_string(trim($_POST['msg_email'])));
	
	if (isset($_POST['send_email']))
		{
		$send_email=1;
		} else {
		$send_email=0;
		}
		
	if (isset($_POST['send_sms']))
		{
		$send_sms=1;
		} else {
		$send_sms=0;
		}
		
	//validate
	if ( ($nstatus <1) || (strlen($nnotes) < 1) )
		{
		$error="<div class=\"msg_warning\">- ".$msg_warning_afr."</div>";
		
		} else {
		//insert
		$sql_new="INSERT INTO notes(notestatus_idnotestatus,statusnotes,enteredby,enteredon,createdby,createdon,tbl_fk_id,tbl_name)
		VALUES ('".$nstatus."','".$nnotes."','".$_SESSION['MVGitHub_idacname']."','".$timenowis."','".$_SESSION['MVGitHub_idacname']."','".$timenowis."','".$_SESSION['idworkplan']."','wpheader' )";
		mysql_query($sql_new);
		//echo $sql_new;
		//send email if checked
		if (($send_email==1) && (isset($_SESSION['wagemail'])) && (strlen($_SESSION['wagemail'])>6) )
			{
			//insert for processing
			$sql_email="INSERT INT0 log_notifications_email(tbl_name,tbl_fk_id,emailadd,emailmsg,notificationtime) 
			VALUES ('notes','idnotes','".$_SESSION['wagemail']."','".$msgemail."','".$timenowis."')";
			mysql_query($sql_email);
			
			} else {
			$error_2="<div class=\"msg_warning\">- ".$msg_warning_email."</div>";
			
			}
		
		//send sms if checked
		if (($send_sms==1) && (isset($_SESSION['wagsms'])) && (strlen($_SESSION['wagsms'])>11) )
			{
			//insert for processing
			$sql_sms="INSERT INT0 log_notifications_sms(tbl_name,tbl_fk_id,smsmsg,recnumber,notificationtime) 
			VALUES ('notes','idnotes','".$msgsms."','".$_SESSION['wagphone']."','".$timenowis."')";
			mysql_query($sql_sms);
			
			} else {
			
			$error_3="<div class=\"msg_warning\">- ".$msg_warning_phone."</div>";
			
			}
		
		//msg ok
		$msg="<div class=\"msg_success\">- ".$msg_changes_saved."</div>";
		
		}	
	}
	
//pull the details from the workplan header
$sql_wpdetails="SELECT idwpheader,yearfrom,yearto,usrname,enteredon,usrteamname,nstatus,utitle,fname,lname,usremail,usrphone FROM wpheader
INNER JOIN usrac ON wpheader.enteredby=usrac.idusrac 
INNER JOIN usrteam ON wpheader.usrteam_idusrteam=usrteam.idusrteam
INNER JOIN notestatus ON wpheader.notestatus_idnotestatus=notestatus.idnotestatus 
WHERE idwpheader=".$_SESSION['idworkplan']." AND notestatus.tbl_name='wpheader'  LIMIT 1";
$res_wpdetails=mysql_query($sql_wpdetails);
$fet_wpdetails=mysql_fetch_array($res_wpdetails);
$num_wpdetails=mysql_num_rows($res_wpdetails);

//temporarily store this values in a session
$_SESSION['wagphone']=$fet_wpdetails['usrphone'];
$_SESSION['wagemail']=$fet_wpdetails['usremail'];

if ($num_wpdetails > 0)
	{
?>
<div>
  <div class="bg_section">
	<?php echo $fet_heading['modulename']; ?> &raquo; <?php echo $fet_heading['submodule']; ?>
  </div>
  
    <div style="padding:10px 0px 10px 0px">
    <?php echo "<span class=\"msg_instructions\">".$ins_wp_keyin."</span>";?>
    </div>
    <table border="0" width="100%">
        	<tr>
            	<td valign="top" width="55%">
				<div class="border_thick">
                <div style="padding:6px 0px 6px 5px; margin:0px 0px 0px 0px" class="table_header">
                    <?php echo $lbl_wpdetails;?>
                  </div>
                 <div style="padding:0px 0px 0px 0px"> 
                    <table border="0" cellpadding="5" width="100%" cellspacing="0">
                    <tr>
                        <td height="30"  colspan="4" class="tbl_h2">
                        <?php echo $lbl_wpentry;?>    </td>
                    </tr>
                        <tr>
                            <td height="35" class="tbl_data" >
                            <?php echo $lbl_year_from;?>        </td>
                          <td height="35" class="tbl_data" >
                            <input readonly="readonly" style="background-color:#CCCCCC" onKeyUp="res(this,numb);" value="<?php echo $fet_wpdetails['yearfrom'];?>"  type="text" maxlength="4" name="yrfrom" size="10">                            </td>
                          <td height="35" class="tbl_data" >
                            <?php echo $lbl_year_to;?>        </td>
                          <td height="35" class="tbl_data" >
                          <input readonly="readonly" style="background-color:#CCCCCC" onKeyUp="res(this,numb);"  value="<?php echo $fet_wpdetails['yearto'];?>"   type="text" maxlength="4" name="yrto" size="10">
                          </td>
                      </tr>
                      <tr>
                      	<td class="tbl_data">
                        <?php echo $lbl_createdby;?>
                        </td>
                        <td colspan="3" class="tbl_data">
                        <?php echo $fet_wpdetails['usrname'];?>
                        </td>
                      </tr>
                      <tr>
                      	<td class="tbl_data">
                        <?php echo $lbl_timeaction;?>
                        </td>
                        <td colspan="3" class="tbl_data">
                        <?php echo date("D, M d, Y H:i",strtotime($fet_wpdetails['enteredon'])); ?>
                        </td>
                      </tr>
                    </table>
                  </div>

                  <div style="padding:10px 0px 0px 5px" >
                    <?php
                    //list of activities against quarters
                    $sql_qs="SELECT idwpquarters,wpquarter FROM wpquarters ORDER BY list_order ASC";
                    $res_qs=mysql_query($sql_qs);
                    $fet_qs=mysql_fetch_array($res_qs);
                    $num_qs=mysql_num_rows($res_qs);
                        
                    if ($num_qs > 0)
                        {
                            do {
                            
                                //get the array for this quarter
                                $sql_activities="SELECT idwpdetails,activitytype,value_number,value_budget FROM wpdetails 
                                INNER JOIN tktactivitytype ON wpdetails.tktactivitytype_idtktactivitytype=tktactivitytype.idtktactivitytype 
                                WHERE wpquarters_idwpquarters=".$fet_qs['idwpquarters']." AND wpheader_idwpheader=".$_SESSION['idworkplan']." ";
                                $res_activities=mysql_query($sql_activities);
                                $fet_activities=mysql_fetch_array($res_activities);
                                $num_activities=mysql_num_rows($res_activities);
                            
                                if ($num_activities > 0)
                                    {
                            
                                echo "<div class=\"tbl_sh\" style=\"margin:15px 0px 0px 0px\">\r\n";
                                echo $fet_qs['wpquarter'];
                                echo "</div>\r\n";
                                echo "<div>\r\n";
                                echo "<table border=\"0\" width=\"100%\" cellpadding=\"2\" cellspacing=\"0\">\r\n";
                                echo "<tr>\r\n<td class=\"divcol\" width=\"40%\">".$lbl_activity."</td>\r\n<td width=\"20%\" class=\"divcol\">".$lbl_tnumber."</td>\r\n<td  width=\"20%\" class=\"divcol\">".$lbl_tbudget."</td>\r\n<td class=\"divcol\"  width=\"10%\"></td>\r\n<td width=\"10%\" class=\"divcol\"></td>\r\n</tr>\r\n";
                                    
                                            do {
                                                echo "<tr>\r\n";
                                                echo "<td class=\"tbl_data\">".$fet_activities['activitytype']."</td>\r\n";
                                                echo "<td class=\"tbl_data\">".$fet_activities['value_number']."</td>\r\n";
                                                echo "<td class=\"tbl_data\">".number_format($fet_activities['value_budget'],2)."</td>\r\n";
                                                echo "<td class=\"tbl_data\"></td>\r\n";
                                                echo "<td class=\"tbl_data\"></td>\r\n</tr>\r\n";
                                            
                                            } while ($fet_activities=mysql_fetch_array($res_activities));
                                    
                                echo "</table>\r\n";
                            echo "</div>\r\n";
							 }
                           } while ($fet_qs=mysql_fetch_array($res_qs));
                        } else {
                        echo "---";
                        }
                    ?>
                  </div>
                      
</div>               
                      
                </td>
                <td valign="top" width="45%" style="padding:3px 0px 10px 0px">
<div class="border_thick">
                <div style="padding:5px 0px 6px 5px; margin:0px 0px 0px 0px" class="table_header">
                    <?php echo $lbl_reviewstatus;?>
                  </div>
                  <div>
                  <?php
				  if (isset($error)) { echo $error; }
				   if (isset($error_2)) { echo $error_2; }
				    if (isset($error_3)) { echo $error_3; }
				  if (isset($msg)) { echo $msg; }
				  ?>
                  </div>
                 <div>
                 <form method="post" name="update_notes" action="">
                 	<table border="0" width="100%" cellpadding="2" cellspacing="0">
                    	<tr>
                        	<td class="tbl_data">
                              <strong><?php echo $lbl_currentstatus;?></strong> </td>
                            <td class="tbl_data">
                            <?php echo $fet_wpdetails['nstatus'];?>
                            </td>
                        </tr>
                        <tr>
                        	<td class="tbl_data">
                              <strong><?php echo $lbl_newstatus?></strong> </td>
                            <td class="tbl_data">
                            <select name="newstatus">
                            <option value="">---</option>
                            <?php
							$sql_newstatus="SELECT  idnotestatus,nstatus FROM notestatus WHERE tbl_name='wpheader'";
							$res_newstatus=mysql_query($sql_newstatus);
							$fet_newstatus=mysql_fetch_array($res_newstatus);
								do {
								echo "<option value=\"".$fet_newstatus['idnotestatus']."\">".$fet_newstatus['nstatus']."</option>";
								} while ($fet_newstatus=mysql_fetch_array($res_newstatus));
							?>
                            </select>
                            </td>
                        </tr>
                        <tr>
                        	<td valign="top" class="tbl_data">
                              <strong><?php echo $lbl_notes;?></strong> </td>
                            <td class="tbl_data">
                            <textarea name="notes" cols="20" rows="3"></textarea>
                            </td>
                        </tr>
                        <tr>
                        	<td colspan="2" class="text_small"><?php echo $lbl_feedback_msg;?> (optional) - <span class="text_small" style="font-weight:bold">The following messages will be sent to the WAG chairman</span></td>
                        </tr>
                        <tr>
                        	<td class="tbl_data">
                            <label for="1">
                             <input id="1" type="checkbox" value="1" name="send_email" /> <strong><?php echo $lbl_nemail;?></strong>
							</label>                             
                             </td>
                            <td class="tbl_data">
                            <div class="text_small" style="font-weight:bold"></div>
                            <div class="text_small" style="background-color:#f4f4f4">
                            <textarea cols="35" rows="3"  name="msg_email">Dear <?php echo $fet_wpdetails['utitle']." ".$fet_wpdetails['lname'];?>, your Workplan has just been reviewed by WASREB. Please log in to your account to check it's status.</textarea>
                            </div>
                            </td>
                        </tr>
                        <tr>
                        	<td class="tbl_data">
                            <label for="2">
                              <input id="2" type="checkbox" value="1" name="send_sms" />
                              <strong>
                            <?php echo $lbl_nsms;?></strong>
                            </label>
                            </td>
                            <td class="tbl_data">
                            <div class="text_small" style="font-weight:bold"></div>
                            <div class="text_small" style="background-color:#f4f4f4">
                            <textarea cols="20" rows="2"  name="msg_sms">[AUTO ALERT] Your Workplan has just been reviewed by WASREB. Please log in to check view status.</textarea>
                             </div>
                            </td>
                        </tr>
                        <tr>
                        	<td height="50"></td>
                            <td>
                            <input type="hidden" value="save_notes" name="formaction" />
                            <a href="#" id="button_submit" onclick="document.forms['update_notes'].submit();"></a>
                            </td>
                        </tr>
                    </table>
                 </form>
                 </div> 
                 </div>
                 <div class="border_thick" style="margin:10px 0px 0px 0px">
                   <div style="padding:6px 0px 6px 5px; margin:0px 0px 0px 0px" class="table_header">
                    <?php echo $lbl_reviews;?>
                  </div>
                    <div style="padding:5px 10px 10px 5px">
                   <?php
	   $sql_notes="SELECT nstatus,statusnotes,enteredon,usrname,fname,lname FROM notes 
	   INNER JOIN notestatus ON notes.notestatus_idnotestatus=notestatus.idnotestatus INNER JOIN usrac ON notes.enteredby=usrac.idusrac  
	   WHERE tbl_fk_id=".$_SESSION['idworkplan']." AND notes.tbl_name='wpheader' ORDER BY enteredon ASC";
	   $res_notes=mysql_query($sql_notes);
	   $num_notes=mysql_num_rows($res_notes);
	   $fet_notes=mysql_fetch_array($res_notes);
	  // echo  $sql_notes;
	   if ($num_notes < 1)
	   	{
		echo "<div class=\"msg_warning\" style=\"text-align:center\">".$msg_no_record ."</div>";
		} else {
		do {
		echo "<div class=\"bline\" style=\"margin:10px 0px 10px 0px\">";
		echo "<div class=\"text_small\">".date("D, M d, Y H:i",strtotime($fet_notes['enteredon']))."</div>";
		echo "<div class=\"text_body\" style=\"font-weight:bold\">".$fet_notes['nstatus']."</div>";
		echo "<div class=\"text_body\">".$fet_notes['statusnotes']."</div>";
		echo "<div class=\"text_small\"> by : ".$fet_notes['fname']." ".$fet_notes['lname']."</div>";
		echo "</div>";
			} while ($fet_notes=mysql_fetch_array($res_notes));
		}
		
	   ?>
                    </div>
</div>                    
                </td>
           </tr>
  </table>
</div>

</div>
<?php
} //close if num is greater
?>