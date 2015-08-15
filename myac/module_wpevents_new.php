<?php
require_once('../assets_backend/be_includes/check_login_easy.php');

//tabs navigation
if (!isset($_SESSION['tabview_con'])) //set default tab
	{
	$_SESSION['tabview_con'] = 1;
	}

if ( (isset($_SESSION['tabview_con'])) && ($_SESSION['tabview_con']!=1) && ($_SESSION['tabview_con']!=2) ) //set default tab
	{
	$_SESSION['tabview_con'] = 1;
	}

if (isset($_GET['tabview']))
	{
	$_SESSION['tabview_con'] = mysql_escape_string(trim($_GET['tabview']));
	}

if ((isset($_POST['formaction'])) && ($_POST['formaction']=="new_event"))
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
			$sql_new="INSERT INTO reportactivities (usrteam_idusrteam, notestatus_idnotestatus, wpquarters_idwpquarters, tktactivitytype_idtktactivitytype, yearfrom, event_startdate, event_enddate, event_venue, event_notes, event_starttime, event_endtime, value_number, value_budget, enteredby, enteredon, createdon, createdby) 
			VALUES ('".$_SESSION['MVGitHub_idacteam']."', '3', '".$report_qtr."', '".$report_activity."', '".$report_year."', '".$datestart."', '".$dateend."', '".$venue."', '".$tnotes."', '".$timestart."', '".$timeend."','".$tnumber."', '".$tbudget."', '".$_SESSION['MVGitHub_idacname']."','".$timenowis."', '".$timenowis."','".$_SESSION['MVGitHub_idacname']."')";
			mysql_query($sql_new);
			
			
			//retrieve the report id
			$sql_idreport="SELECT idreportactivities FROM reportactivities WHERE wpquarters_idwpquarters=".$report_qtr." AND createdby=".$_SESSION['MVGitHub_idacname']." AND yearfrom=".$report_year." ORDER BY idreportactivities DESC LIMIT 1";
			$res_idreport=mysql_query($sql_idreport);
			$fet_idreport=mysql_fetch_array($res_idreport);
			$num_idreport=mysql_num_rows($res_idreport);
			
			//check if disaggregations exist
			$count=count($_POST['disagg']);
			
			if ($count > 0 )
				{		
			//populate disaggregation
					for ($i=0; $i<$count; $i++) 
					{
					$sql_assign="INSERT INTO disaggresults (reportactivities_idreportactivities,disagg_iddisagg,disagg_value,addedon,addedby ) 
					VALUES ('".$fet_idreport['idreportactivities']."','".$_POST['disagg'][$i]."','".$timenowis."','".$_SESSION['MVGitHub_idacname']."')";
					mysql_query($sql_assign);
					
					}
				}
			
			//redirect to view the details of the event
			if ($num_idreport>0)
				{
				?>
				<script language="javascript">
                window.location='<?php echo $_SERVER['PHP_SELF'];?>?&submod=46&uction=edit_submod&idreport=<?php echo $fet_idreport['idreportactivities'];?>';
                </script>
                <?php
				exit;
				}

			}
	
	}
?>
<div>
	<div class="bg_section">
	<?php echo $fet_heading['modulename']; ?> &raquo; <?php echo $fet_heading['submodule']; ?>
	</div>
    <div>
    <?php
	if (isset($error)) { echo $error; }
	?>
    </div>
    <div>
    	<div class="tab_area">
            <span class="tab<?php if ( (isset($_SESSION['tabview_con'])) && ($_SESSION['tabview_con']==1)){ echo "_on"; } ?>"><a href="<?php $_SERVER['PHP_SELF'];?>?tabview=1"><?php echo $lbl_ovdetails;?></a></span>
            <span class="tab_off"><a href="#" style="color:#666666; text-decoration:none"><?php echo $lbl_attndlist;?></a></span>
            <span class="tab_off"><a href="#" style="color:#666666; text-decoration:none"><?php echo $lbl_docs;?></a></span>
            <!--<span class="tab_off"><a href="#" style="color:#666666; text-decoration:none"><?php //echo $lbl_photovideo;?></a></span>-->
            <span class="tab_off"><a href="#" style="color:#666666; text-decoration:none"><?php echo $lbl_notes;?></a></span>
        </div>
	</div>
    <div style="margin:10px 0px 0px 0px; padding:10px 10px 50px 10px">
    <form method="post" action="" name="new_event_header">
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
                	<option value="-1">----</option>
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
                                    <option value="">----</option>
                                    
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
                <option value="-1">-- Select Activity --</option>
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
                       <input size="15" onClick="displayDatePicker('datestart');" name="datestart" style="background-color:#F5F5F5" type="text" id="datestart" value="" readonly="readonly"> 
                        <img src="../assets_backend/btns/cal.gif" alt="Pick a date" width="30" height="30" border="0" align="absmiddle" onClick="displayDatePicker('datestart');">
                        &nbsp;&nbsp;&nbsp;<?php echo $lbl_timestart;?>
                        <select name="timestart">
                        <option value="">---</option>
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
                       <input size="15" onClick="displayDatePicker('dateend');" name="dateend" style="background-color:#F5F5F5" type="text" id="dateend" value="" readonly="readonly"> 
                        <img src="../assets_backend/btns/cal.gif" alt="Pick a date" width="30" height="30" border="0" align="absmiddle" onClick="displayDatePicker('dateend');">
                        &nbsp;&nbsp;&nbsp;<?php echo $lbl_timeend;?>
                        <select name="timeend">
                        <option value="">---</option>
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
                       <input type="text" value="" name="venue">
                       </td>
                  </tr>
                 <tr>
                 	<td colspan="2">
                     <div id="disaggdiv">
                       
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
                       <input onKeyUp="res(this,numb);" type="text" value="" name="tnumber">
                       
                       </td>
                  </tr>
                  <tr>
                    	<td height="40" class="tbl_data">
                          <?php
						echo $lbl_tbudget;
						?>
                    </td>
                       <td height="40" class="tbl_data">
                       <input type="text" onKeyUp="res(this,numb);" value="" name="tbudget">
                       </td>
                  </tr>
           			<tr>
                    	<td height="40" class="tbl_data">
                          <?php
						echo $lbl_notes."<small>&nbsp;(optional)</small>";
						?>
                    </td>
                       <td height="40" class="tbl_data">
                      <textarea cols="30" rows="4" name="notes"></textarea>
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
                <input type="hidden" name="formaction" value="new_event">
               <a href="#" onClick="document.forms['new_event_header'].submit()" id="button_save"></a>              </td>
          </tr>
			
        </table>
    </form>
    </div>
</div>    