<?php
require_once('../assets_backend/be_includes/check_login_easy.php');

if ((isset($_POST['formaction'])) && ($_POST['formaction']=="update_event"))
	{
	
	//clean up values
	$report_year=preg_replace('/[^a-z\-_0-9\.:@\/\s]/i','',mysql_escape_string(trim($_POST['ryear'])));
	$report_yearto=($report_year+1);
	$report_qtr=preg_replace('/[^a-z\-_0-9\.:@\/\s]/i','',mysql_escape_string(trim($_POST['rqtr'])));
	$report_activity=preg_replace('/[^a-z\-_0-9\.:@\/\s]/i','',mysql_escape_string(trim($_POST['ract'])));
	$datestart=preg_replace('/[^a-z\-_0-9\.:@\/\s]/i','',mysql_escape_string(trim($_POST['datestart'])));
	$timestart=preg_replace('/[^a-z\-_0-9\.:@\/\s]/i','',mysql_escape_string(trim($_POST['timestart'])));
	$dateend=preg_replace('/[^a-z\-_0-9\.:@\/\s]/i','',mysql_escape_string(trim($_POST['dateend'])));
	$timeend=preg_replace('/[^a-z\-_0-9\.:@\/\s]/i','',mysql_escape_string(trim($_POST['timeend'])));
	$venue=preg_replace('/[^a-z\-_0-9\.:@\/\s]/i','',mysql_escape_string(trim($_POST['venue'])));
	$tnumber=preg_replace('/[^a-z\-_0-9\.:@\/\s]/i','',mysql_escape_string(trim($_POST['tnumber'])));
	$tbudget=preg_replace('/[^a-z\-_0-9\.:@\/\s]/i','',mysql_escape_string(trim($_POST['tbudget'])));
	$tnotes=preg_replace('/[^a-z\-_0-9\.:@\/\s]/i','',mysql_escape_string(trim($_POST['notes'])));
	
	//if they are both selected, then process
		if ( (strlen($report_year) < 4) || ($report_qtr < 1) || ($report_activity < 1) || (strlen($datestart) < 9) || (strlen($timestart) < 5) || (strlen($dateend) < 9) || (strlen($timeend) < 5) || (strlen($venue) < 1) || ($tnumber < 1) )
			{
			$error="<div class=\"msg_warning\">".$msg_warning_afr."</div>";

			} else {
			//insert the new record
			$sql_update="UPDATE reportactivities SET 
			 notestatus_idnotestatus='3',
			 wpquarters_idwpquarters='".$report_qtr."',
			 tktactivitytype_idtktactivitytype='".$report_activity."', 
			 yearfrom='".$report_year."',
			 event_startdate='".$datestart."', 
			 event_enddate='".$dateend."', 
			 event_venue='".$venue."', 
			 event_notes='".$tnotes."', 
			 event_starttime='".$timestart."',
			 event_endtime='".$timeend."', 
			 value_number='".$tnumber."', 
			 value_budget='".$tbudget."', 
			 modifiedby='".$_SESSION['MVGitHub_idacname']."', 
			 modifiedon='".$timenowis."' 
			 WHERE usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']." AND idreportactivities=".$_SESSION['idreporting']." LIMIT 1";
			mysql_query($sql_update);
						
			//check if disaggregations exist
		if (isset($_POST['disagg']))
			{	
				$count=count($_POST['disagg']);
			
				if ($count > 0 )
				{
			//populate disaggregation - update or insert depending on choice made by user
					for ($i=0; $i<$count; $i++) 
					{
					$sql_assign="INSERT INTO disaggresults (reportactivities_idreportactivities,disagg_iddisagg,disagg_value,addedon,addedby ) 
					VALUES ('".$_SESSION['idreporting']."','".$_POST['disagg'][$i]."','".$timenowis."','".$_SESSION['MVGitHub_idacname']."')";
					mysql_query($sql_assign);
					}
				}
			} //if post disaggr is set

			}
	
	}


//pull data from the db for this Activity / list of Events
$sql_activities="SELECT * FROM reportactivities
INNER JOIN notestatus ON reportactivities.notestatus_idnotestatus=notestatus.idnotestatus
INNER JOIN usrac ON reportactivities.enteredby=usrac.idusrac
INNER JOIN wpquarters ON reportactivities.wpquarters_idwpquarters=wpquarters.idwpquarters
INNER JOIN tktactivitytype ON reportactivities.tktactivitytype_idtktactivitytype =tktactivitytype.idtktactivitytype 
WHERE reportactivities.usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']." AND notestatus.tbl_name='reportactivities' AND idreportactivities=".$_SESSION['idreporting']." ORDER BY reportactivities.enteredon DESC";
$res_activities=mysql_query($sql_activities); 
$fet_activities=mysql_fetch_array($res_activities);
$num_activities=mysql_num_rows($res_activities);	
?>	
<div>
<?php
if (isset($error)) { echo $error; }
?>
</div>
<form method="post" action="" name="event_header">
    	<table border="0" cellpadding="3" cellspacing="0">
        	 <?php
				$sql_yrfrom="SELECT yearfrom FROM wpheader WHERE notestatus_idnotestatus=2 ORDER BY yearfrom ASC";
					$res_yrfrom=mysql_query($sql_yrfrom);
					$num_yrfrom=mysql_num_rows($res_yrfrom);
					$fet_yrfrom=mysql_fetch_array($res_yrfrom);
				if ($num_yrfrom >0)
					{					
					?>
                <tr>
            	<td class="tbl_data">
                <?php echo $lbl_year_from;?>
                </td>
                <td class="tbl_data">
               
                <select name="ryear" onChange="getqtr(this.value)">
                	<option value="<?php echo $fet_activities['yearfrom'];?>"><?php echo $fet_activities['yearfrom'];?></option>
                	<?php
					if ($num_yrfrom > 0)
						{
							do {
							echo "<option value=\"".$fet_yrfrom['yearfrom']."\">".$fet_yrfrom['yearfrom']."</option>";
							} while ($fet_yrfrom=mysql_fetch_array($res_yrfrom));
						}
					?>
                </select>
              
                </td>
            </tr>
              <?php 
				} else {
				echo "<div class=\"msg_warning\">You need a Workplan with Appropriate Status</div>";
				}
				 ?>
            <?php
				if ($num_yrfrom >0)
					{	
				?>
            <tr>
            	<td class="tbl_data">
                <?php echo $lbl_quarter;?>
                </td>
                <td class="tbl_data">
                <div id="qtrdiv">
                <select name="rqtr" onChange="getactivities(this.value)">
                                    <option value="<?php echo $fet_activities['wpquarter'];?>"><?php echo $fet_activities['wpquarter'];?></option>
                                    
                                    </select>
                                    </div>
                                 
                </td>
            </tr>
			<?php } ?>
             <tr>
            	<td class="tbl_data">
                <?php echo $lbl_activity;?>
                </td>
                <td class="tbl_data">
                <div id="activitydiv">
                <select name="ract" id="ract">
                <option value="<?php echo $fet_activities['idtktactivitytype'];?>"><?php echo $fet_activities['activitytype'];?></option>
                </select>
                </div>
                </td>
              </tr>
              <tr>
                    	<td height="40" class="tbl_data">
                          <?php
						echo $lbl_datestart;
						?>
                         </td>
                       <td height="40" class="tbl_data">
                       <input size="15" onClick="displayDatePicker('datestart');" name="datestart" style="background-color:#F5F5F5" type="text" id="datestart" value="<?php echo substr($fet_activities['event_startdate'],0,10);?>" readonly="readonly"> 
                        <img src="../assets_backend/btns/cal.gif" alt="Pick a date" width="30" height="30" border="0" align="absmiddle" onClick="displayDatePicker('datestart');">
                        &nbsp;&nbsp;&nbsp;<?php echo $lbl_timestart;?>
                        <select name="timestart">
                        <option value="<?php echo substr($fet_activities['event_starttime'],0,5);?>"><?php echo substr($fet_activities['event_starttime'],0,5);?></option>
                        <option value="06:00:00">6 AM</option>
                        <option value="07:00:00">7 AM</option>
                        <option value="08:00:00">8 AM</option>
                        <option value="09:00:00">9 AM</option>
                        <option value="10:00:00">10 AM</option>
                        <option value="11:00:00">11 AM</option>
                        <option value="12:00:00">12 PM</option>
                        <option value="13:00:00">1 PM</option>
                        <option value="14:00:00">2 PM</option>
                        <option value="15:00:00">3 PM</option>
                       <option value="16:00:00">4 PM</option>
                       <option value="17:00:00">5 PM</option>
                       <option value="18:00:00">6 PM</option>
                       <option value="19:00:00">7 PM</option>
                        <option value="20:00:00">8 PM</option>
                        </select>
                        </td>
                  </tr>
                  <tr>
                    	<td height="40" class="tbl_data">
                          <?php
						echo $lbl_dateend;
						?>
                         </td>
                       <td height="40" class="tbl_data">
                       <input size="15" onClick="displayDatePicker('dateend');" name="dateend" style="background-color:#F5F5F5" type="text" id="dateend" value="<?php echo substr($fet_activities['event_enddate'],0,10);?>" readonly="readonly"> 
                        <img src="../assets_backend/btns/cal.gif" alt="Pick a date" width="30" height="30" border="0" align="absmiddle" onClick="displayDatePicker('dateend');">
                        &nbsp;&nbsp;&nbsp;<?php echo $lbl_timeend;?>
                        <select name="timeend">
                        <option value="<?php echo substr($fet_activities['event_endtime'],0,6);?>"><?php echo substr($fet_activities['event_endtime'],0,5);?></option>
                        <option value="06:00:00">6 AM</option>
                        <option value="07:00:00">7 AM</option>
                        <option value="08:00:00">8 AM</option>
                        <option value="09:00:00">9 AM</option>
                        <option value="10:00:00">10 AM</option>
                        <option value="11:00:00">11 AM</option>
                        <option value="12:00:00">12 PM</option>
                        <option value="13:00:00">1 PM</option>
                        <option value="14:00:00">2 PM</option>
                        <option value="15:00:00">3 PM</option>
                       <option value="16:00:00">4 PM</option>
                       <option value="17:00:00">5 PM</option>
                       <option value="18:00:00">6 PM</option>
                       <option value="19:00:00">7 PM</option>
                        <option value="20:00:00">8 PM</option>
                        </select>
                        </td>
                  </tr>
                  <tr>
                    	<td height="40" class="tbl_data">
                          <?php
						echo $lbl_venue;
						?>
                    </td>
                       <td height="40" class="tbl_data">
                       <input type="text" value="<?php echo $fet_activities['event_venue'];?>" name="venue">
                       </td>
                  </tr>
                 <tr>
                 	<td colspan="2">
                     <div id="disaggdiv">
                       <?php
$sql_disagg="SELECT * FROM disagglink INNER JOIN disagg ON disagglink.disagg_iddisagg=disagg.iddisagg
WHERE disagglink.tktactivitytype_idtktactivitytype=".$fet_activities['idreportactivities']."";
//echo $sql_disagg;
$res_disagg=mysql_query($sql_disagg);
$num_disagg=mysql_num_rows($res_disagg);
$fet_disagg=mysql_fetch_array($res_disagg);

if ($num_disagg > 0)
	{
	echo "<table border=0 cellpadding=2 cellspacing=0 style=\"margin:0px 0px 0px 10px; background-color:#f4f4f4\">";
	do {
	echo "<tr><td class=tbl_data><small>".$fet_disagg['disaggval']."</small></td>";
	echo "<td><input type=\"text\" onKeyUp=\"res(this,numb);\" value=\"\" name=\"disagg[]\"></td></tr>";
		} while ($fet_disagg=mysql_fetch_array($res_disagg));
	echo "</table>";
	}
					   ?>
                       </div>
                    </td>
                 </tr>
                  <tr>
                    	<td height="40" class="tbl_data">
                          <?php
						echo $lbl_tnumberpresent;
						?>
                    </td>
                       <td height="40" class="tbl_data">
                       <input onKeyUp="res(this,numb);" type="text" value="<?php echo $fet_activities['value_number'];?>" name="tnumber">
                       
                       </td>
                  </tr>
                  <tr>
                    	<td height="40" class="tbl_data">
                          <?php
						echo $lbl_tbudget;
						?>
                    </td>
                       <td height="40" class="tbl_data">
                       <input type="text" onKeyUp="res(this,numb);" value="<?php echo number_format($fet_activities['value_budget'],2);?>" name="tbudget">
                       </td>
                  </tr>
           			<tr>
                    	<td height="40" class="tbl_data">
                          <?php
						echo $lbl_notes."<small>&nbsp;(optional)</small>";
						?>
                    </td>
                       <td height="40" class="tbl_data">
                      <textarea cols="30" rows="4" name="notes"><?php echo $fet_activities['event_notes'];?></textarea>
                       </td>
                  </tr>
                   <tr>
                    	<td height="40" class="tbl_data">
                          
                          <?php
						echo $lbl_reportby;
						?>
                         </td>
                      <td height="40" class="tbl_data">
                        <input type="text" readonly="readonly" style="background-color:#CCCCCC" value="<?php echo $_SESSION['MVGitHub_acname'];?>" />
                        </td>
                  </tr>
            <tr>
            	<td></td>
                <td height="55">
                <input type="hidden" name="formaction" value="update_event">
               <a href="#" onClick="document.forms['event_header'].submit()" id="button_save"></a>              </td>
          </tr>
			
        </table>
    </form>