<?php

$tktcat=intval($_GET['tktcat']);
echo $tktcat;
exit;
if ( ($fet_nextwf['listorder']==$fet_task['listorder']) && (!isset($_GET['tktconfirmed'])) )
	{ //if they are the same, then it means this is the first record after the ticket
	echo "<div class=\"msg_instructions\">Please Confirm Ticket Category on the Right</div>";
	} else {
	?>
<table border="0" width="100%" cellpadding="2" cellspacing="0">
   	  <tr>
                    	<td width="25%" class="tbl_data">
                        <strong><?php echo $lbl_from;?></strong></td>
						<td width="75%" class="tbl_data">
                        <?php
						if ($fet_task['sender_idusrrole']>0)
							{ 
							$sql_sender="SELECT usrrole.usrrolename,usrac.utitle,usrac.lname FROM usrrole,usrac	
							WHERE usrrole.idusrrole=".$fet_task['sender_idusrrole']." AND usrac.idusrac=".$fet_task['sender_idusrac']." LIMIT 1";
							$res_sender=mysql_query($sql_sender);
							$fet_sender=mysql_fetch_array($res_sender);
							echo $fet_sender['usrrolename'] ."<br><small>".$fet_sender['utitle']." ".$fet_sender['lname']."</small>";
							} else {
							echo $lbl_system;
							}
						?>
                        </td>
                </tr>
                  <tr>
                    	<td width="25%" valign="top" class="tbl_data">
                        <strong><?php echo $lbl_action_msg;?></strong></td>
						<td width="75%" valign="top" class="tbl_data">
                        <?php
                        echo $fet_task['taskdesc'];
						?>
                        
                        </td>
                  </tr>
                  <tr>
                    	<td width="25%" class="tbl_data" >
                        <strong><?php echo $lbl_action;?></strong></td>
                        <td class="tbl_data">
			<?php
			
			if ($fet_task['wftskstatustypes_idwftskstatustypes']==1) //if task is closed then halt
				{
				echo "<strong>".$lbl_closedtask."</strong>";
				
				} else {
			
					$sql_listactions="SELECT wftskstatustype,idwftskstatustypes,wftskstatustypedesc,idwftskstatus FROM wftskstatustypes
					INNER JOIN wftskstatus ON wftskstatustypes.idwftskstatustypes=wftskstatus.wftskstatustypes_idwftskstatustypes 
					WHERE wftskflow_idwftskflow=".$fet_task['wftskflow_idwftskflow']." ORDER BY wftskstatustypes.listorder ASC";
					$res_listactions=mysql_query($sql_listactions);
					$fet_listactions=mysql_fetch_array($res_listactions);
					$num_listactions=mysql_num_rows($res_listactions);
					//echo $sql_listactions;
					//check the last place this task is and which role has that task
					$sql_isat="SELECT usrrole_idusrrole,wftaskstrac_idwftaskstrac FROM wftasks WHERE wftaskstrac_idwftaskstrac=".$fet_task['wftaskstrac_idwftaskstrac']." ORDER BY idwftasks DESC LIMIT 1";
					$res_isat=mysql_query($sql_isat);
					$fet_isat=mysql_fetch_array($res_isat);
					
					//check the previous task - if it was a Request For Transfer RFT
					$sql_rft="SELECT wftskstatustypes_idwftskstatustypes FROM wftasks WHERE idwftasks=".$fet_task['wftasks_idwftasks']." AND wftskstatustypes_idwftskstatustypes=5 LIMIT 1";
					$res_rft=mysql_query($sql_rft);
					$num_rft=mysql_num_rows($res_rft);
					
					if ($num_rft > 0 )	//if RFT is 5, then go ahead and create menu for Transfer 
						{
						$sql_transmenu="SELECT idwftskstatustypes,wftskstatustype FROM wftskstatustypes WHERE idwftskstatustypes=3";
						$res_transmenu=mysql_query($sql_transmenu);
						$fet_transmenu=mysql_fetch_array($res_transmenu);
						
						$transfer_menu="<option value=".$fet_transmenu['idwftskstatustypes'].">".$fet_transmenu['wftskstatustype']."</option>";
						
						} else {
						$transfer_menu="";
						}
					
					if (($num_listactions > 0) && ($fet_isat['usrrole_idusrrole']==$_SESSION['MVGitHub_iduserrole']))
						{				
					?>
					<select name="action_to" class="switchaction" id="action_msg" onChange='Choice();' >
						<option value="0">---</option>
					<?php do { ?>
						<option value="<?php echo $fet_listactions['idwftskstatustypes'];?>"><?php echo $fet_listactions['wftskstatustype'];?></option>
					<?php } while ($fet_listactions=mysql_fetch_array($res_listactions)) ;?>
					<?php echo $transfer_menu; //show the extra Transfer menu for RFT tickets if set?>
                    </select>
						<?php } else {
						$sql_itswith="SELECT usrrolename,utitle,lname FROM usrrole 
						INNER JOIN usrac ON usrrole.idusrrole=usrac.usrrole_idusrrole
						WHERE usrrole.idusrrole=".$fet_isat['usrrole_idusrrole']." ";
						//echo $sql_itswith;
						$res_itswith=mysql_query($sql_itswith);
						$fet_itswith=mysql_fetch_array($res_itswith);
						//echo $sql_itswith;
						echo "<small>Currently assigned to:</small><br><strong> ".$fet_itswith['usrrolename']." (".$fet_itswith['utitle']." ".$fet_itswith['lname'].")</strong>";
						 } 
						 
					}  //close if task is closed
					?>
<?php
$sql_msgs="SELECT idwftskstatustypes,wftskstatustypedesc,listorder FROM wftskstatustypes ORDER BY wftskstatustypes.listorder ASC";
$res_msgs=mysql_query($sql_msgs);
$fet_msgs=mysql_fetch_array($res_msgs);
$num_msgs=mysql_num_rows($res_msgs);
//echo $sql_msgs;
?>
<script type='text/javascript'>//<![CDATA[ 
//change feedback to customer depending on the selection made here
var txtfeedback = new Array();

txtfeedback[0] = "";

<?php
do {
echo "txtfeedback[".$fet_msgs['listorder']."]=\"".$fet_msgs['wftskstatustypedesc']."\";\r\n";
	} while ($fet_msgs=mysql_fetch_array($res_msgs));
?>
function Choice() {

y = document.getElementById("action_msg");

document.getElementById("txtfeedback").value = txtfeedback[y.selectedIndex];
}
       
//]]>  
</script>                        </td>
				</tr>
                    <tr>
                        <td valign="top" colspan="2" style="padding:0px; margin:0px" align="left">
                        
<div class="scroll-content">

	<div class="actionlist 1" style="margin:0; padding:0">
    <table border="0" width="100%" cellpadding="0" cellspacing="0" >
    	<tr>
            <td width="25%" valign="top" class="tbl_data" align="left" >
            <strong><?php echo $lbl_youraction_msg?></strong>            </td>
            <td class="tbl_data" >
            <textarea cols="25" rows="4" name="task_msg_1"><?php if (isset($tkttskmsg1)) { echo $tkttskmsg1; }?></textarea>
            <?php
               /*             $sBasePath = $_SERVER['PHP_SELF'] ;
                            $sBasePath = substr( $sBasePath, 0, strpos( $sBasePath, "_samples" ) ) ;
                            $oFCKeditor = new FCKeditor('task_msg_1') ;
                            $oFCKeditor->Height = '150' ;
                            $oFCKeditor->BasePath	= "fckeditor/".$sBasePath ;
							if (isset($tkttskmsg1)) 
							{
							$oFCKeditor->Value =$tkttskmsg1 ;
							} else {
                            $oFCKeditor->Value = '-';
							}
                            $oFCKeditor->ToolbarSet = 'Basic' ;
                            $oFCKeditor->Create() ;*/
                            ?>            </td>
		</tr>
        <tr>
        	<td class="tbl_data"></td>
            <td class="tbl_data">
            <label for="1"><input type="checkbox" value="1" name="close_1" id="1" /><strong> <?php echo $lbl_confirmtktclose;?> </strong></label>            </td>
        </tr>
        <tr>
        	<td></td>
            <td height="50">
            <input type="hidden" value="process_task" name="formaction" />
    		<a href="#" id="button_close" onClick="document.forms['task'].submit()" ></a>
            </td>
        </tr>
	</table>
	</div>
	
	<div class="actionlist 2" style="margin:0; padding:0">
    <table border="0" width="100%" cellpadding="0" cellspacing="0" >
    	<tr>
            <td width="25%" valign="top" class="tbl_data" align="left" >
            <strong><?php echo $lbl_youraction_msg?></strong>            </td>
            <td class="tbl_data" >
            <textarea cols="25" rows="4" name="task_msg_2"><?php if (isset($tkttskmsg2)) { echo $tkttskmsg2; }?></textarea>
            <?php
                         /*   $sBasePath = $_SERVER['PHP_SELF'] ;
                            $sBasePath = substr( $sBasePath, 0, strpos( $sBasePath, "_samples" ) ) ;
                            $oFCKeditor = new FCKeditor('task_msg_2') ;
                            $oFCKeditor->Height = '150' ;
                            $oFCKeditor->BasePath	= "fckeditor/".$sBasePath ;
							if (isset($tkttskmsg2)) 
							{
							$oFCKeditor->Value =$tkttskmsg2;
							} else {
                            $oFCKeditor->Value = '-';
							}                            $oFCKeditor->ToolbarSet = 'Basic' ;
                            $oFCKeditor->Create() ;*/
                            ?>            </td>
		</tr>
        <tr>
        	<td class="tbl_data">
            <strong><?php echo $lbl_sendto;?></strong>
            </td>
            <td class="tbl_data">
            <?php
			//echo $fet_task['wfproc_idwfproc'];
						//confirm what the next workflow id is for this task
						if (isset($new_proc))
							{
							$sql_nextwf="SELECT idwftskflow,wfsymbol_idwfsymbol,wfproc_idwfproc FROM wftskflow WHERE wfproc_idwfproc=".$new_proc." AND listorder>".$fet_task['listorder']." ORDER BY listorder ASC LIMIT 1";
							} else {
							$sql_nextwf="SELECT idwftskflow,wfsymbol_idwfsymbol,wfproc_idwfproc FROM wftskflow WHERE wfproc_idwfproc=".$fet_task['wfproc_idwfproc']." AND listorder>".$fet_task['listorder']." ORDER BY listorder ASC LIMIT 1";
							}
						$res_nextwf=mysql_query($sql_nextwf);
						$fet_nextwf=mysql_fetch_array($res_nextwf);
						$num_nextwf=mysql_num_rows($res_nextwf);
						//echo $sql_nextwf."<br>";
						if ($num_nextwf > 0)//if there is a record
							{ 
							
							if ($fet_nextwf['wfsymbol_idwfsymbol']==10)//if it is the end of the process
								{
								
								$next_step="last_step";
								
								} else { //else if not end of the process, continue
								
									//confirm whether the actors are a group or individual role
									$sql_actors="SELECT usrrole_idusrrole,usrgroup_idusrgroup FROM wfactors WHERE wftskflow_idwftskflow=".$fet_nextwf['idwftskflow']." LIMIT 1 ";
									$res_actors=mysql_query($sql_actors);
									$fet_actors=mysql_fetch_array($res_actors);
									$num_actors=mysql_num_rows($res_actors);
									//echo $sql_actors;
									if ($fet_actors['usrrole_idusrrole'] >0 ) //if more than 0, then it is a allocated to a role
										{
										//find out the actual account assigned this role
										$sql_userac="SELECT idusrac,usrrolename,idusrrole,usrac.utitle,usrac.lname FROM usrac 
										INNER JOIN usrrole ON usrac.usrrole_idusrrole=usrrole.idusrrole WHERE usrrole_idusrrole=".$fet_actors['usrrole_idusrrole']." AND usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']." AND acstatus=1 ";
										$res_userac=mysql_query($sql_userac);
										$fet_userac=mysql_fetch_array($res_userac);
										$num_userac=mysql_num_rows($res_userac);
										//echo $sql_userac;

										if ($num_userac > 0)
											{
											
											$menu_item="";
												do {
													if ($fet_userac['idusrrole']!=$_SESSION['MVGitHub_iduserrole']) //list only if not current user
														{
														$menu_item.="<option title=\"".$fet_userac['utitle']." ".$fet_userac['lname']."\" value=\"".$fet_userac['idusrrole']."\">".$fet_userac['usrrolename']."</option>";
														} //end //list only if not current user
													} while ($fet_userac=mysql_fetch_array($res_userac));
									
											} else {
											
											echo "<div class=\"msg_warning\">--No Active User--<br><small>(Please Contact Admin)</small></div>";
											
											} //user exists
										} //close usrrole
								
									if ($fet_actors['usrgroup_idusrgroup'] > 0 ) //if allocated to a group, then do the following
										{ 
										//if group, check only those roles that do actually have users (active status) mapped to them
										//check who has had most work in the last 7 days (one week) in terms of hours
										//last 7 days
										//$timenow = ; //capture current time. You can adjust based on server settings
										$sevendaysago = date("Y-m-d H:i:s",strtotime(date("Y-m-d H:i:s",time())) - (7*86400)); //7 days ago
										
										//echo $sevendaysago."<br>-----";
											
										$sql_workdistr="SELECT SUM(TIMESTAMPDIFF(MINUTE,timetatstart,timedeadline)) AS minutes, usrac.idusrac, usrac.usrrole_idusrrole,usrac.utitle,usrac.lname,usrrole.usrrolename FROM wftasks 
										INNER JOIN usrrole ON wftasks.usrrole_idusrrole=usrrole.idusrrole 
										INNER JOIN link_userrole_usergroup ON usrrole.idusrrole = link_userrole_usergroup.usrrole_idusrrole
										INNER JOIN usrac ON link_userrole_usergroup.usrrole_idusrrole = usrac.usrrole_idusrrole
										WHERE wftasks.createdon>='".$sevendaysago."' AND wftasks.createdon<='".$timenowis."' AND link_userrole_usergroup.usrgroup_idusrgroup=".$fet_actors['usrgroup_idusrgroup']." AND acstatus=1 GROUP BY wftasks.usrac_idusrac ORDER BY minutes ASC";
										//echo "test";
										//echo $sql_workdistr;
										$res_workdistr=mysql_query($sql_workdistr);
										$num_workdistr=mysql_num_rows($res_workdistr);
										$fet_workdistr=mysql_fetch_array($res_workdistr);
											
											
										//check in case the group has not received anything in the last 7 days
										$sql_workdistolder7="SELECT SUM(TIMESTAMPDIFF(MINUTE,timetatstart,timedeadline)) AS minutes, usrac.idusrac, usrac.usrrole_idusrrole,usrac.utitle,usrac.lname,usrrole.usrrolename FROM wftasks 
										INNER JOIN usrrole ON wftasks.usrrole_idusrrole=usrrole.idusrrole 
										INNER JOIN link_userrole_usergroup ON usrrole.idusrrole = link_userrole_usergroup.usrrole_idusrrole
										INNER JOIN usrac ON link_userrole_usergroup.usrrole_idusrrole = usrac.usrrole_idusrrole
										WHERE wftasks.createdon<='".$timenowis."' AND link_userrole_usergroup.usrgroup_idusrgroup=".$fet_actors['usrgroup_idusrgroup']." AND acstatus=1 GROUP BY wftasks.usrac_idusrac ORDER BY minutes ASC";
										//echo "test";
										//echo $sql_workdistolder7;
										$res_workdistolder7=mysql_query($sql_workdistolder7);
										$num_workdistolder7=mysql_num_rows($res_workdistolder7);
										$fet_workdistolder7=mysql_fetch_array($res_workdistolder7);	
										
											
										//check also for any new user who perhaps has never received a task - new user
										$sql_newuser="SELECT idusrac, usrac.usrrole_idusrrole, usrrole.usrrolename,usrac.utitle,usrac.lname
										FROM link_userrole_usergroup
										INNER JOIN usrrole ON link_userrole_usergroup.usrrole_idusrrole = usrrole.idusrrole
										INNER JOIN usrac ON usrrole.idusrrole = usrac.usrrole_idusrrole
										WHERE link_userrole_usergroup.usrrole_idusrrole NOT
										IN (
										
										SELECT usrrole_idusrrole
										FROM wftasks
										)
										AND link_userrole_usergroup.usrgroup_idusrgroup=".$fet_actors['usrgroup_idusrgroup']." 
										AND acstatus=1";
										$res_newuser=mysql_query($sql_newuser);
										$num_newuser=mysql_num_rows($res_newuser);
										$fet_newuser=mysql_fetch_array($res_newuser);
								
								
										//if record exists, then pick 
										
										if ($num_newuser>0) //if there is a new user and user exists in the workflow
											{
											
											$menu_item3="";
												do {
													if ($fet_newuser['usrrole_idusrrole']!=$_SESSION['MVGitHub_iduserrole']) //list only if not current user
														{
														$menu_item3.="<option title=\"".$fet_newuser['utitle']." ".$fet_newuser['lname']."\" value=\"".$fet_newuser['usrrole_idusrrole']."\">".$fet_newuser['usrrolename']."</option>";
														} //end //list only if not current user
													} while ($fet_newuser=mysql_fetch_array($res_newuser));	
											//$menu_item="<option selected=\"selected\" title=\"".$fet_newuser['utitle']." ".$fet_newuser['lname']."\" value=\"".$fet_newuser['usrrole_idusrrole']."\">".$fet_newuser['usrrolename']."[2]</option>";
												
												
											} else if ($num_newuser==0) { //else if no one is new 
												
													if ($num_workdistr > 0 ) //if there are already users in the tasks
														{
														$menu_item2="";
															
															do {
															//don't list the current logged in user on the menu
															if ($fet_workdistr['usrrole_idusrrole']!=$_SESSION['MVGitHub_iduserrole'])
																	{
																	$menu_item2.="<option title=\"".$fet_workdistr['utitle']." ".$fet_workdistr['lname']."\" value=\"".$fet_workdistr['usrrole_idusrrole']."\">".$fet_workdistr['usrrolename']."</option>";
																	}
																} while ($fet_workdistr=mysql_fetch_array($res_workdistr));
														
														} else { //else create a task for the admin
														
															//check if older than 7 days
															if ($num_workdistolder7 > 0)
																{
																$menu_item2="";
																do {
																	//don't list the current logged in user on the menu
																	if ($fet_workdistolder7['usrrole_idusrrole']!=$_SESSION['MVGitHub_iduserrole'])
																			{
																			$menu_item2.="<option title=\"".$fet_workdistolder7['utitle']." ".$fet_workdistolder7['lname']."\" value=\"".$fet_workdistolder7['usrrole_idusrrole']."\">".$fet_workdistolder7['usrrolename']."</option>";
																			}
																		} while ($fet_workdistolder7=mysql_fetch_array($res_workdistolder7));
																
																} else {
																//create new task for the admin
																echo "<div class=\"msg_warning\">--No Active User--<br><small>(Please Contact Admin)</small></div>";
																}
											
														} //user exists
												
												} //close new user

											} //close user group

								} //not last step
							
							} else { //if no record
							
							$next_step="end_of_road";
							} //close if there is a record
							
						if ( (isset($menu_item)) || (isset($menu_item2)) || (isset($menu_item3)) )
							{			
						echo "<select name=\"assign_to_2\">";
						if(isset($menu_item)) { echo $menu_item; }
						if(isset($menu_item2)) { echo $menu_item2; }
						if(isset($menu_item3)) { echo $menu_item3; }
						echo "</select>";	
							}
							
					
						?>            </td>
        </tr>
        <tr>
        	<td></td>
            <td height="50">
            <input type="hidden" value="process_task" name="formaction" />
    		<a href="#" id="button_passiton" onClick="document.forms['task'].submit()" ></a>            </td>
        </tr>
	</table>
	</div>
    
	<div class="actionlist 3" style="margin:0; padding:0">
    <table border="0" width="100%" cellpadding="0" cellspacing="0" >
    	<tr>
            <td width="25%" valign="top" class="tbl_data" align="left" >
            <strong><?php echo $lbl_youraction_msg?></strong>            </td>
            <td class="tbl_data" >
            <textarea cols="25" rows="4" name="task_msg_3"><?php if (isset($tkttskmsg3)) { echo $tkttskmsg3; }?></textarea>
            <?php
			
                            /*$sBasePath = $_SERVER['PHP_SELF'] ;
                            $sBasePath = substr( $sBasePath, 0, strpos( $sBasePath, "_samples" ) ) ;
                            $oFCKeditor = new FCKeditor('task_msg_3') ;
                            $oFCKeditor->Height = '150' ;
                            $oFCKeditor->BasePath	= "fckeditor/".$sBasePath ;
							if (isset($tkttskmsg3)) 
							{
							$oFCKeditor->Value =$tkttskmsg3;
							} else {
                            $oFCKeditor->Value = '-';
							}
                            $oFCKeditor->ToolbarSet = 'Basic' ;
                            $oFCKeditor->Create() ;*/
                            ?>            </td>
		</tr>
        <tr>
        	<td class="tbl_data">
            <strong><?php echo $lbl_transto;?></strong>            </td>
            <td class="tbl_data">
            <?php
						//confirm what the next workflow id is for this task
						$sql_role="SELECT idusrrole,idusrac,usrrolename,utitle,lname FROM usrrole
						INNER JOIN usrac ON usrrole.idusrrole=usrac.usrrole_idusrrole
						INNER JOIN usrteamzone ON usrrole.usrteamzone_idusrteamzone=usrteamzone.idusrteamzone
						WHERE (usrteamzone.idusrteamzone=".$_SESSION['MVGitHub_userteamzoneid']." OR usrteamzone.usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam'].") AND idusrrole!=".$_SESSION['MVGitHub_iduserrole']."  ORDER BY usrrolename ASC";
						$res_role=mysql_query($sql_role);
						$fet_role=mysql_fetch_array($res_role);
						$num_role=mysql_num_rows($res_role);

								
						echo "<select name=\"assign_to_3\" >";
						echo "<option value=\"\">---</option>";
						do {
						echo "<option value=\"".$fet_role['idusrrole']."\">".$fet_role['usrrolename']." (".$fet_role['utitle']." ".$fet_role['lname'].")</option>";
						} while ($fet_role=mysql_fetch_array($res_role));
						echo "</select>";	
						?>            </td>
        </tr>
         <tr>
        	<td class="tbl_data">
            <strong><?php echo $lbl_newdeadline;?></strong>            </td>
            <td class="tbl_data">
            <input type="text" name="newdeadline" value="<?php if (isset($tktnewdeadline)) { echo $tktnewdeadline;}?>" readonly="readonly" onClick="javascript:show_calendar('document.task.newdeadline', document.task.newdeadline.value);" />
            <a href="javascript:show_calendar('document.task.newdeadline', document.task.newdeadline.value);">
            <img src="../assets_backend/btns/cal.gif" width="30" align="absmiddle" height="30" border="0" alt="Click Here to Pick up the timestamp"></a>            </td>
        <tr>
        	<td></td>
            <td height="40">
            <input type="hidden" value="process_task" name="formaction" />
    		<a href="#" id="button_submit" onClick="document.forms['task'].submit()" ></a>            </td>
        </tr>
	</table>
	</div>
    
    <div class="actionlist 4" style="margin:0; padding:0">
        <table border="0" width="100%" cellpadding="0" cellspacing="0" >
 <tr>
        	<td class="tbl_data" width="25%" valign="top">
            <strong><?php echo $lbl_reasoninvalid;?></strong>            </td>
            <td class="tbl_data">
            <?php
						//confirm what the next workflow id is for this task
						$sql_role="SELECT idwftskinvalidlist,wfttaskinvalidlistlbl FROM wftskinvalidlist ORDER BY wfttaskinvalidlistlbl ASC";
						$res_role=mysql_query($sql_role);
						$fet_role=mysql_fetch_array($res_role);
						$num_role=mysql_num_rows($res_role);
						
					//	echo "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr><td>";		
						echo "<select name=\"invalid_id\" id=\"invalid_id\">";
						echo "<option value=\"-1\" selected>----</option>";
						do {
						echo "<option value=\"".$fet_role['idwftskinvalidlist']."\">".$fet_role['wfttaskinvalidlistlbl']."</option>";
						} while ($fet_role=mysql_fetch_array($res_role));
						echo "<option value=\"0\">".$lbl_notlistedadd."</option>";
						echo "</select>";	
						
					//	echo "</td><td>";
						echo "<div id=\"0\" class=\"invalid_new\"  style=\"display:none; padding:10px 0px 10px 0px\">".$lbl_newreason." : <input type=\"text\" name=\"add_reason\"></div>";
					//	echo "</td></tr></table>";
						?>            </td>
        </tr>
    	<tr>
            <td width="25%" valign="top" class="tbl_data" align="left" >
            <strong><?php echo $lbl_youraction_msg?></strong>            </td>
            <td class="tbl_data" >
            <textarea cols="25" rows="4" name="task_msg_4"><?php if (isset($tkttskmsg4)) { echo $tkttskmsg4; }?></textarea>
            <?php
                           /* $sBasePath = $_SERVER['PHP_SELF'] ;
                            $sBasePath = substr( $sBasePath, 0, strpos( $sBasePath, "_samples" ) ) ;
                            $oFCKeditor = new FCKeditor('task_msg_4') ;
                            $oFCKeditor->Height = '150' ;
                            $oFCKeditor->BasePath	= "fckeditor/".$sBasePath ;
							if (isset($tkttskmsg4)) 
							{
							$oFCKeditor->Value =$tkttskmsg4;
							} else {
                            $oFCKeditor->Value = '-';
							}
                            $oFCKeditor->ToolbarSet = 'Basic' ;
                            $oFCKeditor->Create() ;*/
                            ?>            </td>
		</tr>
       
        <tr>
        	<td></td>
            <td height="50">
            <input type="hidden" value="process_task" name="formaction" />
    		<a href="#" id="button_submit" onClick="document.forms['task'].submit()" ></a>            </td>
        </tr>
	</table>
    </div>
    
    <div class="actionlist 5" style="margin:0; padding:0">
    <table border="0" width="100%" cellpadding="0" cellspacing="0" >
    	<tr>
            <td width="25%" valign="top" class="tbl_data" align="left" >
            <strong><?php echo $lbl_youraction_msg?></strong>            </td>
            <td class="tbl_data" >
            <textarea cols="25" rows="4" name="task_msg_5"><?php if (isset($tkttskmsg5)) { echo $tkttskmsg5; }?></textarea>
            <?php
                            /*$sBasePath = $_SERVER['PHP_SELF'] ;
                            $sBasePath = substr( $sBasePath, 0, strpos( $sBasePath, "_samples" ) ) ;
                            $oFCKeditor = new FCKeditor('task_msg_5') ;
                            $oFCKeditor->Height = '150' ;
                            $oFCKeditor->BasePath	= "fckeditor/".$sBasePath ;
							if (isset($tkttskmsg5)) 
							{
							$oFCKeditor->Value =$tkttskmsg5;
							} else {
                            $oFCKeditor->Value = '-';
							}
                            $oFCKeditor->ToolbarSet = 'Basic' ;
                            $oFCKeditor->Create() ;*/
                            ?>            </td>
		</tr>
        <tr>
        	<td class="tbl_data">
            <strong><?php echo $lbl_sendto;?></strong>            </td>
            <td class="tbl_data">
            <?php
						//confirm what the next workflow id is for this task
						$sql_role="SELECT idusrrole,idusrac,usrrolename,utitle,lname FROM usrrole
						INNER JOIN usrac ON usrrole.idusrrole=usrac.usrrole_idusrrole
						WHERE idusrrole=".$_SESSION['MVGitHub_reportingto']."  ORDER BY usrrolename ASC";
						$res_role=mysql_query($sql_role);
						$fet_role=mysql_fetch_array($res_role);
						$num_role=mysql_num_rows($res_role);

								
						echo "<select name=\"assign_to_5\" >";
						do {
						echo "<option value=\"".$fet_role['idusrrole']."\">".$fet_role['usrrolename']." (".$fet_role['utitle']." ".$fet_role['lname'].")</option>";
						} while ($fet_role=mysql_fetch_array($res_role));
						echo "</select>";	
						?>            </td>
        </tr>
        <tr>
        	<td></td>
            <td height="40">
            <input type="hidden" value="process_task" name="formaction" />
    		<a href="#" id="button_submit" onClick="document.forms['task'].submit()" ></a></td>
        </tr>
	</table>
	</div>
    
     <div class="actionlist 6" style="margin:0; padding:0">
    <table border="0" width="100%" cellpadding="0" cellspacing="0" >
    	<tr>
            <td width="25%" valign="top" class="tbl_data" align="left" >
            <strong><?php echo $lbl_update_msg;?></strong>            </td>
            <td class="tbl_data" >
            <textarea cols="25" rows="4" name="task_msg_6"><?php if (isset($tkttskmsg6)) { echo $tkttskmsg6; }?></textarea>
            <?php
              /*              $sBasePath = $_SERVER['PHP_SELF'] ;
                            $sBasePath = substr( $sBasePath, 0, strpos( $sBasePath, "_samples" ) ) ;
                            $oFCKeditor = new FCKeditor('task_msg_6') ;
                            $oFCKeditor->Height = '150' ;
                            $oFCKeditor->BasePath	= "fckeditor/".$sBasePath ;
							if (isset($tkttskmsg6)) 
							{
							$oFCKeditor->Value =$tkttskmsg6;
							} else {
                            $oFCKeditor->Value = '-';
							}
                            $oFCKeditor->ToolbarSet = 'Basic' ;
                            $oFCKeditor->Create() ;*/
                            ?>            </td>
		</tr>
       
        <tr>
        	<td></td>
            <td height="40">
            <input type="hidden" value="process_task" name="formaction" />
    		<a href="#" id="button_submit" onClick="document.forms['task'].submit()" ></a>            </td>
        </tr>
	</table>
	</div>
</div>		</td>
	</tr>
</table>
<?php
} //end if the ticket has been confirmed
?>