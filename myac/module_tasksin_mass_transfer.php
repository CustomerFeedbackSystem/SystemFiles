<?php
require_once('../assets_backend/be_includes/check_login_easy.php');
?>
<?php
//first, clear any session error
if (isset($_SESSION['error'])) { unset($_SESSION['error']); }

//now construct the loop
$string=$_SESSION['tasks'];


if ( (isset($_POST['action_to'])) && ($_POST['action_to']==2) && (isset($_POST['formaction'])) && ($_POST['formaction']=="process_task") )
	{ //Select Task Action 2 ie: pass it on

	$tktaction=preg_replace('/[^a-z\-_0-9\.:\/]/i','',mysql_escape_string(trim($_POST['action_to']))); //action
	$tktasito2=preg_replace('/[^a-z\-_0-9\.:\/]/i','',mysql_escape_string(trim($_POST['assign_to_2'])));
	$tkttskmsg2=preg_replace('/[^a-z\-_0-9\.:\/\s]/i','',mysql_escape_string(strip_tags(trim($_POST['task_msg_2']))));
	$string_parts = explode(",",$string); //explode the tasks to transfer separated by a comma
	
	if (strlen($tkttskmsg2) < 1)
		{
		$error_2_1="<div class=\"msg_warning_small\">You need to create appropriate Task Message below which will appear on all the Tasks</div>";
		}
	if ( ($tktasito2<1) && (strlen($tktasito2) < 1) ) //to cater for exception
		{
		$error_2_2="<div class=\"msg_warning_small\">Please enter the person to Send these tasks to</div>";
		}
	if  ( ($tktasito2=="other_exception") && (strlen($_POST['recepient_alt']) < 3) )
		{
		$error_2_3="<div class=\"msg_warning_small\">Please indicate the person to send the task to</div>";
		}
	if ( ($tktasito2=="other_exception") && (!isset($error_2_3)) ) //if it's an exception
		{
	//check if the selected account_usr account is valid
		$str_prex=mysql_escape_string(trim($_POST['recepient_alt']));
		$str_ex=explode(',',$str_prex);
									
	//take ther ole
		$str_role=trim($str_ex[0]); //the first variable after comma
		$str_last=trim($str_ex[1]);
								
		$str_region=substr($str_last,-3,2);
								
	//get the id and user from the userac table
		$sql_userid="SELECT idusrac,usrrole_idusrrole FROM usrac 
		INNER JOIN usrrole ON usrac.usrrole_idusrrole=usrrole.idusrrole
		INNER JOIN usrteamzone ON usrrole.usrteamzone_idusrteamzone=usrteamzone.idusrteamzone
		WHERE usrrolename='".$str_role."' 
		AND usrteamzone.region_pref='".$str_region."'
		AND acstatus=1 
		LIMIT 1";
		$res_userid=mysql_query($sql_userid);
		$fet_userid=mysql_fetch_array($res_userid);
		$num_userid=mysql_num_rows($res_userid);
		//echo "line 1039: ".$sql_userid."<br>";
		//exit;
		if ($num_userid>0)
			{
			//echo $sql_userid;
			$recepient_roleid=$fet_userid['usrrole_idusrrole'];
			$recepient_usrid=$fet_userid['idusrac'];
			$recepient_groupid=0;
			} else {
			$error_2_4="<div class=\"msg_warning_small\">The Name or Role you entered does not exist or is not active</div>";
			}
	
		} //exception
						
		//check if the selection is a Group or an Individual Role
		$tktasito2_prefix=substr($tktasito2,0,3); //if a group, the result should be GRP	


		if ( (!isset($error_2_1)) && (!isset($error_2_2)) && (!isset($error_2_3)) && (!isset($error_2_4)) )//if the no error 
			{
			
			mysql_query("BEGIN");
								
			for($i = 0; $i < sizeof($string_parts); $i++)//then use the values to get the transactions goins
					{ //for loop 
						$string_parts[$i] = trim($string_parts[$i]);
						
						//echo $string_parts[$i]."<br>";
						
						//first, get the tasks idtktin and wftrac numbers
						$sql_taskdets="SELECT wftasks.idwftasks,wftasks.tktin_idtktin,wftasks.wftaskstrac_idwftaskstrac,wftasks.wftskflow_idwftskflow,wftasks.timeoveralldeadline,wftasks.usrrole_idusrrole,wftasks.batch_number,tktin.refnumber 
						FROM wftasks 
						INNER JOIN tktin ON wftasks.tktin_idtktin=tktin.idtktinPK
						WHERE idwftasks=".$string_parts[$i]." LIMIT 1";
						$res_taskdets=mysql_query($sql_taskdets);
						$num_taskdets=mysql_num_rows($res_taskdets);
						$fet_taskdets=mysql_fetch_array($res_taskdets);
					//	echo $sql_taskdets."<br><br>";
						//loop through with the details for this task
						//process each tasks - using transactions to lock each loop to avoid db corruptio
/////////////////////////////////////////////////////////////////									
						
								
								//update this task 
								$sql_update_task="UPDATE wftasks SET wftskstatustypes_idwftskstatustypes='2',wftskstatusglobal_idwftskstatusglobal='2',timeactiontaken='".$timenowis."',actedon_idusrrole=".$_SESSION['MVGitHub_iduserrole'].", actedon_idusrac='".$_SESSION['MVGitHub_idacname']."' WHERE idwftasks=".$fet_taskdets['idwftasks']." LIMIT 1";
								$query_1=mysql_query($sql_update_task);
								
								//create an update message on the record
								$sql_update_msg="INSERT INTO wftskupdates (wftaskstrac_idwftaskstrac,usrrole_idusrrole,usrac_idusrac,wftskstatusglobal_idwftskstatusglobal,wftskstatustypes_idwftskstatustypes,wftasks_idwftasks,wftskupdate,createdby,createdon) 
								VALUES ('".$fet_taskdets['wftaskstrac_idwftaskstrac']."','".$_SESSION['MVGitHub_iduserrole']."','".$_SESSION['MVGitHub_idacname']."','2','2','".$fet_taskdets['idwftasks']."','".$tkttskmsg2."','".$_SESSION['MVGitHub_idacname']."','".$timenowis."')";
								$query_2=mysql_query($sql_update_msg);
								
								//get task details
								//if tskflow > 0 then ok 
								if ($fet_taskdets['wftskflow_idwftskflow'] > 0) //if there is a taskflow, then proceed
									{
									$sql_task_details = "SELECT wftasks.wftaskstrac_idwftaskstrac,wftasks.idwftasks,wftasks.usrrole_idusrrole,wftasks.wftasks_idwftasks,wftasks.wftskflow_idwftskflow,wftskflow.wfproc_idwfproc,wftasks.tktin_idtktin,wftasks.usrac_idusrac,wftasks.wftskstatustypes_idwftskstatustypes,wftasks.wftskstatusglobal_idwftskstatusglobal,wftasks.tasksubject,wftasks.taskdesc,wftasks.timeinactual,wftasks.timeoveralldeadline,wftasks.timetatstart,wftasks.timedeadline,wftasks.timeactiontaken,wftasks.sender_idusrrole,wftasks.sender_idusrac,wftskflow.listorder,wftskflow.idwftskflow,wftskflow.wftsktat,wfproc.wfproctat FROM wftasks 
									INNER JOIN wftskflow ON wftasks.wftskflow_idwftskflow=wftskflow.idwftskflow 
									INNER JOIN wfproc ON wftskflow.wfproc_idwfproc=wfproc.idwfproc
									WHERE idwftasks=".$fet_taskdets['idwftasks']." LIMIT 1";
									} else {
									/*$sql_task_details = "SELECT wftasks.wftaskstrac_idwftaskstrac,wftasks.idwftasks,wftasks.usrrole_idusrrole,wftasks.wftasks_idwftasks,wftasks.wftskflow_idwftskflow,link_tskcategory_wfproc.wfproc_idwfproc,wftasks.tktin_idtktin,wftasks.usrac_idusrac,wftasks.wftskstatustypes_idwftskstatustypes,wftasks.wftskstatusglobal_idwftskstatusglobal,wftasks.tasksubject,wftasks.taskdesc,wftasks.timeinactual,wftasks.timeoveralldeadline,wftasks.timetatstart,wftasks.timedeadline,wftasks.timeactiontaken,wftasks.sender_idusrrole,wftasks.sender_idusrac,wfproc.wfproctat FROM wftasks 
									INNER JOIN wftasks_exceptions ON wftasks.wftasks_idwftasks=wftasks_exceptions.wftasks_idwftasks								
									INNER JOIN tktin ON wftasks.tktin_idtktin=tktin.idtktinPK
									INNER JOIN link_tskcategory_wfproc ON tktin.tktcategory_idtktcategory=tktin.tktcategory_idtktcategory
                                    INNER JOIN wfproc ON link_tskcategory_wfproc.wfproc_idwfproc=wfproc.idwfproc
									WHERE idwftasks=".$fet_taskdets['idwftasks']." AND tktin.idtktinPK=".$fet_taskdets['tktin_idtktin']."
									AND link_tskcategory_wfproc.usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']." LIMIT 1";*/
									$sql_task_details = "SELECT wftasks.wftaskstrac_idwftaskstrac,wftasks.idwftasks,wftasks.usrrole_idusrrole,wftasks.wftasks_idwftasks,wftasks.wftskflow_idwftskflow,link_tskcategory_wfproc.wfproc_idwfproc,wftasks.tktin_idtktin,wftasks.usrac_idusrac,wftasks.wftskstatustypes_idwftskstatustypes,wftasks.wftskstatusglobal_idwftskstatusglobal,wftasks.tasksubject,wftasks.taskdesc,wftasks.timeinactual,wftasks.timeoveralldeadline,wftasks.timetatstart,wftasks.timedeadline,wftasks.timeactiontaken,wftasks.sender_idusrrole,wftasks.sender_idusrac,wfproc.wfproctat FROM wftasks 
									INNER JOIN tktin ON wftasks.tktin_idtktin=tktin.idtktinPK
									INNER JOIN link_tskcategory_wfproc ON tktin.tktcategory_idtktcategory=tktin.tktcategory_idtktcategory
                                    INNER JOIN wfproc ON link_tskcategory_wfproc.wfproc_idwfproc=wfproc.idwfproc
									WHERE idwftasks=".$fet_taskdets['idwftasks']." AND tktin.idtktinPK=".$fet_taskdets['tktin_idtktin']."
									AND link_tskcategory_wfproc.usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']." LIMIT 1";									
									}
								
								$res_task_details = mysql_query($sql_task_details);
								$fet_task_details = mysql_fetch_array($res_task_details);
								//echo "<br><br><br>".$sql_task_details."<br>";
								//exit;
								//}
								
								//the next task flow is depended on the value in a hidden field
								$wftaskflow_id_txtbox=$fet_taskdets['wftskflow_idwftskflow'];
								
										
								////////////// START CALCULATION OF TIME /////////
								if ( ($tktasito2!="other_exception") || ($fet_taskdets['wftskflow_idwftskflow']!=0) ) //if NOT other exception OR sender had idtksflow, then follow this steps
									{ 
										//lock the query below to the above variable
										//find the next tasks
										$sql_nextwf="SELECT idwftskflow,wftskflow.wfsymbol_idwfsymbol as wfsymbol,wfactors.usrrole_idusrrole as usrrole,wfactors.usrgroup_idusrgroup as usrgroup,wftsktat,expubholidays
										FROM wftskflow INNER JOIN wfactors ON wftskflow.idwftskflow=wfactors.wftskflow_idwftskflow 
										WHERE wfproc_idwfproc=".$fet_task_details['wfproc_idwfproc']." AND wftskflow.wfsymbol_idwfsymbol=2 AND wftskflow.idwftskflow=".$wftaskflow_id_txtbox." ORDER BY listorder ASC LIMIT 1";
										//echo $sql_nextwf."<br>";
										//exit;
										$res_nextwf=mysql_query($sql_nextwf);
										$num_nextwf=mysql_num_rows($res_nextwf);
										$fet_nextwf=mysql_fetch_array($res_nextwf);
										
	
												//1. construct deadlines and start times against TATs and time task was received
												$ticket_wday = date("w",strtotime($timenowis)); //ticket day of the week
												$ticket_hour = date("H:i",strtotime($timenowis)); //ticket hour	
												$ticket_actualtimein = $timenowis;
												//$ticket_timein = $timenowis;
												
												$task_starttime_raw = $ticket_actualtimein;//exactly the time the task came in on the record
												$task_deadline_raw = date("Y-m-d H:i:s",strtotime($ticket_actualtimein)+$fet_nextwf['wftsktat']); //this is for the specific task
												$task_overalldeadline_raw = date("Y-m-d H:i:s",strtotime($ticket_actualtimein)+$fet_task_details['wfproctat']); //this is the overall time set for the whole process should take
												
													
												//CONSTRUCT STARTING TIME
												//a) Did this task fall on a Weekday, Working hours ?
												if (($ticket_wday>0) && ($ticket_wday<6)) //Monday - Friday
													{
													//check if it was a weekday
													$sql_workinghrs="SELECT time_earliest,time_latest,wfworkingdays_idwfworkingdays FROM wfworkinghrs WHERE wftskflow_idwftskflow=".$fet_task_details['idwftskflow']." AND 	wfworkingdays_idwfworkingdays=1";
													$res_workinghrs=mysql_query($sql_workinghrs);
													$num_workinghrs=mysql_num_rows($res_workinghrs);
													$fet_workinghrs=mysql_fetch_array($res_workinghrs);
													
													//echo $sql_workinghrs;
													//check time in
													if ( ( ($ticket_hour>=$fet_workinghrs['time_earliest']) && ($ticket_hour<=$fet_workinghrs['time_latest']) ) || ($ticket_hour<$fet_workinghrs['time_earliest']) )
														{
														
														$push_weekday = 0;//then do not add a day to the start time
														
														} else {
														
														$push_weekday = 1;
														
														}
														
													} else {
													
													$push_weekday = 0;
													
													}//close Monday - Friday
													
													
												if ($ticket_wday==6) // Saturday
													{

													//check if the task applies for Saturdays
					
													$sql_saturdays="SELECT time_earliest,time_latest,wfworkingdays_idwfworkingdays FROM wfworkinghrs WHERE wftskflow_idwftskflow=".$fet_task_details['idwftskflow']." AND wfworkingdays_idwfworkingdays=2 LIMIT 1";
													$res_saturdays=mysql_query($sql_saturdays);
													$num_saturdays=mysql_num_rows($res_saturdays);
													$fet_saturdays=mysql_fetch_array($res_saturdays);
														
													
					
													if ( ($fet_saturdays['time_earliest']=='00:00:00') && ($fet_saturdays['time_latest']=='00:00:00') )
														{
														$push_saturday = 1; // push a day
														
														} else { //then if not set to 00:00:00 as per the query above, compare the timein
				
															//check the time the ticket came in
															if ( ( ($ticket_hour>=$fet_saturdays['time_earliest']) && ($ticket_hour<=$fet_saturdays['time_latest']) ) || ($ticket_hour<$fet_saturdays['time_earliest']) )
																{
																$push_saturday =0;//then do not add a day to the start time
																} else {
																$push_saturday =1;//then do not add a day to the start time
																}
																	
														} //close if not set to 00:00:00
													
													} else {
															
													$push_saturday=0;
																						
													} //close if saturday
														
													
												if ($ticket_wday==0) // Sunday
														{
														//check if the task applies for sundays
														$sql_sundays="SELECT time_earliest,time_latest,wfworkingdays_idwfworkingdays FROM wfworkinghrs WHERE wftskflow_idwftskflow=".$fet_nextwf['idwftskflow']." AND wfworkingdays_idwfworkingdays=3 LIMIT 1";
														
														$res_sundays=mysql_query($sql_sundays);
														$num_sundays=mysql_num_rows($res_sundays);
														$fet_sundays=mysql_fetch_array($res_sundays);
														
														if (($fet_sundays['time_earliest']=='00:00:00') && ($fet_sundays['time_latest']=='00:00:00')) 
															{
															$push_sunday = 1; // push a day
															} else { //then if not set to 00:00:00 as per the query above, compare the timein
															//check the time the ticket came in
															if ( ( ($ticket_hour>=$fet_sundays['time_earliest']) && ($ticket_hour<=$fet_sundays['time_latest']) ) || ($ticket_hour<$fet_sundays['time_earliest']) )
																{
																
																$push_sunday =0;//then do not add a day to the start time
																
																} else {
																
																$push_sunday =1;//then do not add a day to the start time
																
																} //close if not within the pre-set sunday time frames
																																			
															} //close if not set to 00:00:00
														
														} else {
														
														$push_sunday=0;
														
														} //close if a Sunday
												
												
												//Adjust the Start and Stop Times
												$total_pushes = ($push_weekday + $push_saturday + $push_sunday); //number of adjustments across
												$total_pushes_sec = ($total_pushes * 86400); //
												
												$task_starttime_refined = date("Y-m-d H:i:s",strtotime($task_starttime_raw) + $total_pushes_sec);
												$task_deadline_refined = date("Y-m-d H:i:s",strtotime($task_deadline_raw) + $total_pushes_sec);
												$task_overalldeadline_refined = date("Y-m-d H:i:s",strtotime($task_overalldeadline_raw) + $total_pushes_sec);
												
												
												//Are public holidays Excluded
												if ($fet_nextwf['expubholidays']==1) //if set, then find out how many public holidays will count between the new start and end dates
													{
													
													$sql_holidays = "SELECT idwftskholiday FROM wftskholiday WHERE wftskholidaydate>='".$task_starttime_refined."' AND  wftskholidaydate<='".$task_deadline_refined."' ";
													$res_holidays = mysql_query($sql_holidays);
													$num_holidays = mysql_num_rows($res_holidays);
													
													
													
													$push_holidays=($num_holidays * 86400);
													
													} else { //else not set, then no holiday found
													
													$push_holidays=0;
													
													}
													
												//start and end times almost final
												$task_starttime_prefinal = date("Y-m-d H:i:s",strtotime($task_starttime_refined) + $push_holidays);
												$task_deadline_prefinal = date("Y-m-d H:i:s",strtotime($task_deadline_refined) + $push_holidays);
												$task_overalldeadline_prefinal = date("Y-m-d H:i:s",strtotime($task_overalldeadline_refined) + $push_holidays);
											
													
												//finally, within the span of the refined Start and End days, find how many Saturdays and Sundays will be exempted if excempt

												if ($push_saturday==1) //if Saturday was excempted
													{
													
													$count_saturdays = 0;
													
													$start_ts = strtotime($task_starttime_prefinal); // start time stamp
													$end_ts = strtotime($task_deadline_prefinal); // end time stamp
					
													
													while ($start_ts<=$end_ts) 
														{
															$day = date('w', $start_ts);
																if ($day == 6) 
																	{ // this is a saturday
																	//echo date('d', $working_ts)."<br>";
																	$count_saturdays++;
																	}
															$start_ts = $start_ts + $day_sec;
														}
													
													//number of exempt saturdays


													$ex_saturdays = $count_saturdays;
													
													} else {
													
													$ex_saturdays = 0;
													
													} //if Saturday was excempted
												
												
												
												if ($push_sunday==1) //if sunday was excempted
													{
													
													$count_sundays = 0;
													
													$start_ts = strtotime($task_starttime_prefinal); // start time stamp
													$end_ts = strtotime($task_deadline_prefinal); // end time stamp
					
													
													while ($start_ts<=$end_ts) 
														{
															$day = date('w', $start_ts);
																if ($day == 0) 
																	{ // this is a sunday
																	//echo date('d', $working_ts)."<br>";
																	$count_sundays++;
																	}
															$start_ts = $start_ts + $day_sec;
														}
													
													//number of exempt sundays
													$ex_sundays = $count_sundays;
													
													} else {
													
													$ex_sundays = 0;
													
													} //if sunday was excempted
												
												
												
												//FINAL START AND END DAYS FOR THE TASKS THEN AS FOLLOWS
												$push_ex_saturdays = ($ex_saturdays * 86400);
												$push_ex_sundays = ($ex_sundays * 86400);
																			
												$next_workflow_id=$fet_nextwf['idwftskflow'];
												
												
											} //close if NO EXCEPTION
											
											//if this is an actor exception, then there is no Next Workflow 
											if ( ($tktasito2=="other_exception") || ($fet_taskdets['wftskflow_idwftskflow']==0) ) //if NOT other exception, then follow this steps
												{ 
												//so just calculate manually
												$task_starttime_final = $timenowis; //time now
												$task_deadline_final = date("Y-m-d H:i:s",strtotime($timenowis) + (2*86400));; //just add 48 hours to the current
												$task_overalldeadline_final = $fet_taskdets['timeoveralldeadline']; //as original time deadline for this task
												
												} else { //if not exception
												
												$task_starttime_final = $task_starttime_prefinal; //the start time remains the same... only adjust the deadline to discount off the extra weekend days
												$task_deadline_final = date("Y-m-d H:i:s",strtotime($task_deadline_prefinal) + ($push_ex_saturdays + $push_ex_sundays));
												$task_overalldeadline_final = date("Y-m-d H:i:s",strtotime($task_overalldeadline_prefinal) + ($push_ex_saturdays + $push_ex_sundays));
											
												}
												
								///////////// END CALCULATION OF TIME ////////
													
								///////////// GET THE RECEPIENT DETAILS DEPENDING ON CONDITIONS FULFILLED								
								//get user account id
								if ( ($tktasito2_prefix!="GRP") && ($tktasito2!="other_exception") ) //It's not a group and Not an exception, then great
									{
									$sql_userid="SELECT idusrac FROM usrac WHERE usrrole_idusrrole=".$tktasito2." LIMIT 1";
									$res_userid=mysql_query($sql_userid);
									$fet_userid=mysql_fetch_array($res_userid);
								//vars below
									$recepient_roleid=$tktasito2;
									$recepient_usrid=$fet_userid['idusrac'];
									$recepient_groupid=0;
									
									}
									
								if ($tktasito2_prefix=="GRP")  //It's  a group, then
									{
									
									$tktasito2_suffix=substr($tktasito2,3); //if a group, the result should be GRP
									
									$sql_userid="SELECT idwfactorsgroupname FROM wfactorsgroupname WHERE idwfactorsgroupname=".$tktasito2_suffix." LIMIT 1";
									$res_userid=mysql_query($sql_userid);
									$fet_userid=mysql_fetch_array($res_userid);
								//vars below
									$recepient_roleid=0;
									$recepient_usrid=0;
									$recepient_groupid=$tktasito2_suffix; //groups id is store on the same select menu as the rolws
								//	echo $sql_userid;
									}
								
								
									
								///////////// END OF RECEPIENT DETAILS //////////////////
								
								//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~//

								
								//insert new task for the recepeint
								
								if ($fet_task_details['wftaskstrac_idwftaskstrac'] > 0) //ensure the track >0 - just a caution
									{
									$sql_new_task="INSERT INTO wftasks (wftaskstrac_idwftaskstrac,usrrole_idusrrole,wftasks_idwftasks,wftskflow_idwftskflow,tktin_idtktin,usrac_idusrac,wftskstatustypes_idwftskstatustypes,wftskstatusglobal_idwftskstatusglobal,tasksubject,taskdesc,timeinactual,timeoveralldeadline,timetatstart,timedeadline,timeactiontaken,sender_idusrrole,sender_idusrac,createdon,createdby,wfactorsgroup_idwfactorsgroup,wftasks_batch_idwftasks_batch,batch_number) 
									VALUES ('".$fet_task_details['wftaskstrac_idwftaskstrac']."','".$recepient_roleid."','".$fet_task_details['idwftasks']."','".$wftaskflow_id_txtbox."','".$fet_task_details['tktin_idtktin']."','".$recepient_usrid."','0','1','".$fet_task_details['tasksubject']."','".$tkttskmsg2."','".$timenowis."','".$fet_task_details['timeoveralldeadline']."','".$task_starttime_final."','".$task_deadline_final."','0000-00-00 00:00:00','".$_SESSION['MVGitHub_iduserrole']."','".$_SESSION['MVGitHub_idacname']."','".$timenowis."','".$_SESSION['MVGitHub_idacname']."','".$recepient_groupid."','".$fet_taskdets['batch_number']."','0')";
									$query_3=mysql_query($sql_new_task);
									}
								//echo "1-".$sql_new_task."<br>";
								//exit; 
								//if there was a batch, then you need to include that in the process
								if ( (isset($batch_no)) && ($batch_no>0) )
									{
									//retrieve the id for this task
									$sql_tasklastid="SELECT idwftasks FROM wftasks WHERE createdby=".$_SESSION['MVGitHub_idacname']." ORDER BY idwftasks DESC LIMIT 1";
									$res_tasklastid=mysql_query($sql_tasklastid);
									$fet_tasklastid=mysql_fetch_array($res_tasklastid);
									
									//check the last batch_no
									$sql_lastbatchno="SELECT countbatch FROM wftasks_batch WHERE idwftasks_batch=".$batch_no."";
									$res_lastbatchno=mysql_query($sql_lastbatchno);
									$fet_lastbatchno=mysql_fetch_array($res_lastbatchno);
									
									//create the new batch_no
									$new_batchno=($fet_lastbatchno['countbatch']+1);
									
									//update the task
									$sql_update="UPDATE wftasks SET 
									wftasks_batch_idwftasks_batch='".$batch_no."',
									batch_number='".$new_batchno."'
									WHERE idwftasks=".$fet_tasklastid['idwftasks']."";
									mysql_query($sql_update);
									
									//update the batch_no meta table
									$sql_updatecount="UPDATE wftasks_batch SET countbatch=(countbatch+1) WHERE idwftasks_batch=".$batch_no."";
									mysql_query($sql_updatecount);
									}
								
						/*	//check if there is a form data and if so, go ahead and process this transaction with inserts or updates
							if ( (isset($_POST['formdata_available'])) && ($_POST['formdata_available']==1) )
								{
								//echo "processed <br>";
								//check the db for this field by reusing the sql statement above
								$sql_val="SELECT idwfprocassetsaccess,assetname,perm_read,perm_write,perm_required,wfprocassets.wfprocdtype_idwfprocdtype,idwfprocassets FROM wfprocassetsaccess 
								INNER JOIN wfprocassets ON wfprocassetsaccess.wfprocassets_idwfprocassets=wfprocassets.idwfprocassets
								WHERE wftskflow_idwftskflow=".$fet_taskdets['wftskflow_idwftskflow']." AND wfprocassetsaccess.perm_read=1 ORDER BY wfprocassets.ordering ASC";
								$res_val=mysql_query($sql_val);
								$num_val=mysql_num_rows($res_val);
								$fet_val=mysql_fetch_array($res_val);
			
								if ($num_val > 0) //if there are some values, then
									{
									do {
									//master-checklist if  | it is required | there is a value | the data type to determine the field |  whether an update or insert
									
									//validate required
								//	echo "validation ";
								//	echo $_POST['required_'.$fet_val['idwfprocassetsaccess'].'']."<br>";
								//	echo $_POST['item_'.$fet_val['idwfprocassetsaccess'].''];
										if (
										(isset($_POST['required_'.$fet_val['idwfprocassetsaccess'].''])) 
										&& ($_POST['required_'.$fet_val['idwfprocassetsaccess'].'']==1) 
										&&  ($_POST['item_'.$fet_val['idwfprocassetsaccess'].'']=="")   
										)
											{
											//echo $_POST['item_'.$fet_val['idwfprocassetsaccess'].'']."<br>";
											$error_formdata=1;
											echo "<div class=\"msg_warning_small\">Data Sheet : ".$fet_val['assetname']." is required</div>";
											
											}
										
									//if no error on the dataform, then process
									if (!isset($error_formdata))
										{	
										if ($_POST['transtype_'.$fet_val['idwfprocassetsaccess'].'']=="INSERT")
											{
											//check the form item type first
											$ttype=$_POST['itemtype_'.$fet_val['idwfprocassetsaccess'].''];
											if (isset($_POST['item_'.$fet_val['idwfprocassetsaccess'].'']))
												{
												$fvalue=mysql_escape_string(trim($_POST['item_'.$fet_val['idwfprocassetsaccess'].'']));
												}
												
												if (($ttype==1) || ($ttype==5) || ($ttype==6) || ($ttype==7) || ($ttype==8) || ($ttype==9) ) //if textbox OR yes/no OR datepicker OR datetimepicker
													{
													//then process as below
													$sql_insert="INSERT INTO wfassetsdata (wfprocassetsaccess_idwfprocassetsaccess,
													wfprocassetschoice_idwfprocassetschoice,
													wfprocassets_idwfprocassets,
													wftasks_idwftasks,
													value_choice,
													value_path,
													wftaskstrac_idwftaskstrac,
													tktin_idtktin,
													createdby,
													createdon)
													VALUES ('".$fet_val['idwfprocassetsaccess']."',
													'0',
													'".$fet_val['idwfprocassets']."',
													'".$fet_taskdets['idwftasks']."',
													'".$fvalue."',
													'',
													'".$fet_taskdets['wftaskstrac_idwftaskstrac']."',
													'".$fet_taskdets['tktin_idtktin']."',
													'".$_SESSION['MVGitHub_idacname']."',
													'".$timenowis."'
													)";
													
													mysql_query($sql_insert);
													//echo $sql_insert;
													//exit;
													}
													
												if ( ($ttype==2) ||  ($ttype==4) )//if menulist
													{
													
													$sql_insert="INSERT INTO wfassetsdata (wfprocassetsaccess_idwfprocassetsaccess,
													wfprocassetschoice_idwfprocassetschoice,
													wfprocassets_idwfprocassets,
													wftasks_idwftasks,
													value_choice,
													value_path,
													wftaskstrac_idwftaskstrac,
													tktin_idtktin,
													createdby,
													createdon)
													VALUES ('".$fet_val['idwfprocassetsaccess']."',
													'".$fvalue."',
													'".$fet_val['idwfprocassets']."',
													'".$fet_taskdets['idwftasks']."',
													'',
													'',
													'".$fet_taskdets['wftaskstrac_idwftaskstrac']."',
													'".$fet_taskdets['tktin_idtktin']."',
													'".$_SESSION['MVGitHub_idacname']."',
													'".$timenowis."'
													)";
													
													mysql_query($sql_insert);
													
													}
											
											}
										if ($_POST['transtype_'.$fet_val['idwfprocassetsaccess'].'']=="UPDATE")
											{
											$ttype=$_POST['itemtype_'.$fet_val['idwfprocassetsaccess'].''];
											$fvalue=mysql_escape_string(trim($_POST['item_'.$fet_val['idwfprocassetsaccess'].'']));
											$itempk=mysql_escape_string(trim($_POST['itempk_'.$fet_val['idwfprocassetsaccess'].'']));
											
											//check the form item type first
											if (($ttype==1) || ($ttype==5) || ($ttype==6) || ($ttype==7) || ($ttype==8) || ($ttype==9)) //if textbox OR yes/no OR datepicker OR datetimepicker
													{
													//then process as below
													$sql_update="UPDATE wfassetsdata SET 
													value_choice='".$fvalue."',
													wftaskstrac_idwftaskstrac='".$fet_taskdets['wftaskstrac_idwftaskstrac']."',
													tktin_idtktin='".$fet_taskdets['tktin_idtktin']."',
													modifiedby='".$_SESSION['MVGitHub_idacname']."',
													modifiedon='".$timenowis."'
													WHERE idwfassetsdata=".$itempk." LIMIT 1";
													
													mysql_query($sql_update);
													//echo $sql_update;
													}
											
											if ( ($ttype==2) || ($ttype==4) )//if menulist
													{
													$sql_update="UPDATE wfassetsdata SET 
													wfprocassetschoice_idwfprocassetschoice='".$fvalue."',
													wftaskstrac_idwftaskstrac='".$fet_taskdets['wftaskstrac_idwftaskstrac']."',
													tktin_idtktin='".$fet_taskdets['tktin_idtktin']."',
													modifiedby='".$_SESSION['MVGitHub_idacname']."',
													modifiedon='".$timenowis."'
													WHERE idwfassetsdata=".$itempk." LIMIT 1";
													//echo $sql_update;
													mysql_query($sql_update);
													}
											}
											
										}
										
									} while ($fet_val=mysql_fetch_array($res_val));
									
									} //if record is > 0
								
								} //close form data checker
							*/							
								//Feedback SMS to send customer/sender a message
								if ( (isset($tktsms)) && (strlen($tktsms)>5) && (strlen($tktsenderphone)==12) )
									{
									$sql_smsout="INSERT INTO mdata_out_sms (destnumber,msgtext)  
									VALUES ('".$tktsenderphone."','".$_SESSION['MVGitHub_userteamshortname'].", Ticket ".$tktno." ".$tktsms."')";
									mysql_query($sql_smsout);
									}
							
		
									//notify if anyone is to be notified
									$sql_notify="SELECT idwfnotification,wfnotification.tktstatus_idtktstatus,usrrole_idusrrole,wftskflow_idwftskflow,notify_system,notify_email,notify_sms,idtktmsgs,tktmsg_sms,tktmsg_email,tktmsg_dashboard FROM wfnotification 
									INNER JOIN tktmsgs ON wfnotification.idwfnotification=tktmsgs.wfnotification_idwfnotification
									WHERE wftskflow_idwftskflow=".$fet_taskdets['wftskflow_idwftskflow']." ORDER BY idwfnotification ASC";
									$res_notify=mysql_query($sql_notify);
									$num_notify=mysql_num_rows($res_notify);
									$fet_notify=mysql_fetch_array($res_notify);
									//echo $sql_notify;
									if ($num_notify > 0 ) // if there is a notification setting
										{


										do {			
										//check for each of the settings 
											if ( ($fet_notify['notify_system']==1) && (strlen($fet_notify['tktmsg_dashboard'])>2) ) //system dashboard set on
												{
												$sql_dash="INSERT INTO tktmsglogs_dashboard (tktmsgs_idtktmsgs,msgto_roleid,msgto_subject,msgto_body,createdon,readon)
												VALUES ('".$fet_notify['idtktmsgs']."','".$fet_notify['usrrole_idusrrole']."','Notification - Tkt No : ".$fet_taskdets['refnumber'].",'".$fet_notify['tktmsg_dashboard']." ','".$timenowis."','0000-00-00 00:00:00')";
												mysql_query($sql_dash);									
												}// system dashboard set on
														
												//get this roles email address and phone numbers
												//ensure the account is active as well...
												$sql_rolecontacts="SELECT usremail,usrphone FROM usrac WHERE usrrole_idusrrole=".$fet_notify['usrrole_idusrrole']." AND acstatus=1 LIMIT 1";
												$res_rolecontacts=mysql_query($sql_rolecontacts);

												$fet_rolecontacts=mysql_fetch_array($res_rolecontacts);
												$num_rolecontacts=mysql_num_rows($res_rolecontacts);
												
												if ( ($fet_notify['notify_email']==1) && ($num_rolecontacts>0) && (strlen($fet_rolecontacts['usremail'])>6) && (strlen($fet_notify['tktmsg_email'])>2) )//email set on
													{
													$sql_email="INSERT INTO tktmsgslog_emails(tktmsgs_idtktmsgs,emailto,emailsubject,emailbody,createdon,senton) 
													VALUES ('".$fet_notify['idtktmsgs']."','".$fet_rolecontacts['usremail']."','Notification - Tkt No : ".$tktno.", ".$fet_ticketcat['tktcategoryname']."','".$fet_notify['tktmsg_email']." - Tkt No:".$tktno.", ".$fet_ticketcat['tktcategoryname']."','".$timenowis."','0000-00-00 00:00:00')";
														
													mysql_query($sql_email);
													}
													
													if ( ($fet_notify['notify_sms']==1) && ($num_rolecontacts>0) && (strlen($fet_rolecontacts['usrphone'])==13) )
													{
													$sql_sms="INSERT INTO mdata_out_sms (destnumber,msgtext) 
													VALUES ('".$fet_rolecontacts['usrphone']."',' Auto Notification - Tkt No:".$tktno.", ".$fet_ticketcat['tktcategoryname']." received')";
							
													mysql_query($sql_sms);
													}
														
											} while ($fet_notify=mysql_fetch_array($res_notify));								
												
										} //close - if there is a notification setting
							
							/////////////////////////////check and insert a new subscriber
							if ($fet_taskdets['usrrole_idusrrole']==2) //if this is the first ticket from the customer, then do this...
								{
								//check if a subscriber with the same credentials matches
								$sql_subis="SELECT idsmssubs FROM ".$_SESSION['MVGitHub_tblsmsbc']." WHERE subnumber='".$tktsenderphone."' AND usrtype=1";
								$res_subis=mysql_query($sql_subis);
								$num_subis=mysql_num_rows($res_subis);
								
								//if not, add the new credentials
								if ($num_subis==0)
									{
									$sql_subnew="INSERT INTO ".$_SESSION['MVGitHub_tblsmsbc']." (wftskid,tktid,subnumber,idloctown,idusrteamzone,usrtype,createdon,createdby)
									VALUES ('".$fet_taskdets['idwftasks']."','".$fet_taskdets['tktin_idtktin']."','".$fet_confirmloc['idloctowns']."','".$_SESSION['MVGitHub_userteamzoneid']."','1','".$timenowis."','".$_SESSION['MVGitHub_idacname']."')";
									mysql_query($sql_subnew);
									}
								}
							///////////////////////////// close check and insert new subscriber ////////////////////////////
							
							//if exception, then follow the following path...remember to add wftasks_exceptions table transaction
							if ($tktasito2=="other_exception") //if it's an exception
								{
								$sql_exceptionlog="INSERT INTO wftasks_exceptions (wftasks_idwftasks,wftskflow_idwftskflow,idusrrole_from,idusrac_from,idusrrole_to,idusrac_to,wfprocassetsaccess_idwfprocassetsaccess,createdon,createdby) 
								VALUES ('".$fet_taskdets['idwftasks']."','".$fet_taskdets['wftskflow_idwftskflow']."','".$_SESSION['MVGitHub_iduserrole']."','".$_SESSION['MVGitHub_idacname']."','".$recepient_roleid."','".$recepient_usrid."','0','".$timenowis."','".$_SESSION['MVGitHub_idacname']."')";
								mysql_query($sql_exceptionlog);
								}
							
							//echo $sql_task_details."<br>".$sql_update_task."<br>".$sql_update_msg."<br>".$sql_new_task;
							//exit;
							//if no error, then redirect to the correct page
								
////////////////////////////////////////////////////////////////////////
								
					} //for loop
					
					if ( (!isset($error_formdata)) && ($fet_task_details['wftaskstrac_idwftaskstrac']>0) )
								{
								
								if ( ($res_task_details) && ($query_1) && ($query_2) && ($query_3))
									{
									mysql_query("COMMIT");	
									///////////////////////////// close check and insert new subscriber ////////////////////////////
									//redirect to the correct page
									$success="OK";
									exit;
									} else {
									mysql_query("ROLLBACK");
									?>
                                    <script language="javascript">
									 alert ('Sorry! Please Try Again!');
									</script>
                                    <?php
									mysql_free_result($query_1); 
									mysql_free_result($query_2);
									mysql_free_result($query_3);
									mysql_free_result($res_task_details);	
									} //if the no error 1_1
								} //no error on form data			
					
			} //form is valid
		
	} //form action
?>
<div>
	<div class="bg_section">
    My Tasks &raquo; Assign Tasks
    </div>
  <div style="padding:20px 10px">

<script type="text/javascript" src="../scripts/jquery.autocomplete.js"></script>
<script language="javascript">
//autocomplete the staff 
$().ready(function() {
	$("#recepient_alt").autocomplete("findrole_multiassign.php", {
		width: 450,
		matchContains: true,
		//mustMatch: true
		//minChars: 0,
		//multiple: true,
		//highlight: true,
		//multipleSeparator: ",",
		selectFirst: false
	});
});

</script>
<style type='text/css'>
    .actionlist {
     display: none;
	 padding:0px;
	 margin:0px;   
}

.optionvalue {
     border: 0px;   
}

</style>
<div>
<?php
if (isset($error_2_1)) { echo $error_2_1; }
if (isset($error_2_2)) { echo $error_2_2; }
if (isset($error_2_3)) { echo $error_2_3; }
if (isset($error_2_4)) { echo $error_2_4; }
?>
</div>
<div>
<?php
if (!isset($success))
	{
?>
    <form method="post" action="" name="mass_act" style="margin:30px 0px">
    <div class="msg_instructions_small">
    Please Note : Bulk 'Passing On' of Tasks should only apply where individual analysis of Tasks is not needed!
    </div>
    <div>
    <table border="0" width="100%" cellpadding="0" cellspacing="0" >
  		<tr>
            <td width="26%" height="40" align="left" valign="middle" bgcolor="#F8F8F8" class="tbl_data" style="padding:0px 10px 0px 100px; font-weight:bold" >
            No. of Tasks            </td>
		  <td width="74%" valign="middle" bgcolor="#F8F8F8" class="tbl_data">
			<span style="font-size:18px"><?php echo $_SESSION['tasks_no']; ?></span>
       	  </td>
        </tr>
        <tr>
            <td width="26%" height="40" align="left" valign="middle" bgcolor="#FFFFFF" class="tbl_data" style="padding:0px 10px 0px 88px; font-weight:bold" ><img src="../assets_backend/images/arrow_red.png" width="8" align="absmiddle" height="10"> Appropriate Action</td>
			<td width="74%" valign="middle" bgcolor="#FFFFFF" class="tbl_data">
            <?php
			$sql_status="SELECT idwftskstatustypes,wftskstatustype FROM wftskstatustypes WHERE idwftskstatustypes=2";
			$res_status=mysql_query($sql_status);
			$fet_status=mysql_fetch_array($res_status);
			?>
            <select name="action_to" id="action_msg"  >
			<option value="<?php echo $fet_status['idwftskstatustypes'];?>"><?php echo $fet_status['wftskstatustype'];?></option>
			</select>                        
            </td>
        </tr>
    	<tr>
            <td width="26%" align="left" valign="top" bgcolor="#F8F8F8" class="tbl_data" style="padding:10px 10px 0px 85px; font-weight:bold" >
            <strong><?php echo $lbl_asterik;?><?php echo $lbl_youraction_msg?></strong>            </td>
          <td bgcolor="#F8F8F8" class="tbl_data" >
            <textarea cols="25" rows="4" name="task_msg_2"><?php if (isset($tkttskmsg2)) { echo $tkttskmsg2; }?></textarea>
            </td>
		</tr>
        <tr>
        	<td bgcolor="#FFFFFF" class="tbl_data" style="padding:0px 10px 0px 100px; font-weight:bold" >
            <?php
				echo $lbl_sendto;
				?>            </td>
            
        <td bgcolor="#FFFFFF" class="tbl_data"><div id="other_exception" >
                <div><span style="font-size:11px; font-weight:bold;color:#CC0000; background-color:#FFFFCC">Type Name or Role to Send To :</span></div>
                <div>
				<input type="text" name="recepient_alt" id="recepient_alt" maxlength="45" value="" size="38" />
                </div>      
                </div>
            </td>
        </tr>
        
        <tr>
        	<td>
            <a href="index.php?mod=2&amp;submod=0&amp;ua=view&amp;view=2&amp;uction=view_mod" id="button_cancel"></a>
            </td>
            <td height="50">
            <input type="hidden" name="assign_to_2" value="other_exception" />
            <input type="hidden" value="process_task" name="formaction" />
    		<a href="#" id="button_passiton" onClick="document.forms['mass_act'].submit()" ></a>
            </td>
        </tr>
	</table>
</div>
    </form>
<?php
}  //not success yet - else is success
if ( (isset($success)) && ($success=="OK") )
{
?>
<div class="msg_success">The Tasks have been Sent Successfully!</div>
<?php
}
?>
    </div>
  </div>
</div>    