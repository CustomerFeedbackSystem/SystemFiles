<?php
require_once('../assets_backend/be_includes/config.php');

//require_once('../Connections/connSystem.php');
//mysql_select_db($database_connSystem, $connSystem);

require_once('../assets_backend/be_includes/check_login_easy.php');

//include("fckeditor/fckeditor.php");

//get the title
if (isset($_GET['title']))
	{
	$_SESSION['wtitle']=preg_replace('/[^a-z\-_0-9\.:\/\s]/i','',mysql_escape_string(trim($_GET['title'])));
	}

if (isset($_GET['task']))
	{
	$_SESSION['wtaskid']=preg_replace('/[^a-z\-_0-9\.:\/]/i','',mysql_escape_string(trim($_GET['task'])));
	}

if (isset($_GET['channel']))
	{
	$_SESSION['presetchannel']=preg_replace('/[^a-z\-_0-9\.:\/\s]/i','',mysql_escape_string(trim($_GET['channel'])));
	}

if (isset($_POST['exists_rec']))
	{
	$record_exists=preg_replace('/[^a-z\-_0-9\.:\/\s]/i','',mysql_escape_string(trim($_POST['exists_rec'])));
	}

if (isset($_POST['tktcat']))
	{
	$_SESSION['thiscat']=preg_replace('/[^a-z\-_0-9\.:\/\s]/i','',mysql_escape_string(trim($_POST['tktcat'])));
	}
//process task
//form action
if ( (isset($_POST['formaction'])) && ($_POST['formaction']=="process_task") )
	{
	//now, sanitize the inputs
		$tktno=preg_replace('/[^a-z\-_0-9\.:\/]/i','',mysql_escape_string(trim($_POST['tktnumber'])));
		$tktcat=preg_replace('/[^a-z\-_0-9\.:\/]/i','',mysql_escape_string(trim($_POST['tktcat'])));
		$tktacno=preg_replace('/[^a-z\-_0-9\.:\/]/i','',mysql_escape_string(trim($_POST['acnumber'])));
		$tktkiosk=preg_replace('/[^a-z\-_0-9\.:\/]/i','',mysql_escape_string(trim($_POST['kiosk'])));
		$tktsender=preg_replace('/[^a-z\-_0-9\.:\/\s]/i','',mysql_escape_string(trim($_POST['sendername'])));
		$tktsenderphone=preg_replace('/[^a-z\-_0-9\.:\/]/i','',mysql_escape_string(trim($_POST['senderphone'])));
		$tktstreet=preg_replace('/[^a-z\-_0-9\.:\/\s]/i','',mysql_escape_string(trim($_POST['roadstreet'])));
		$tktbuilding=preg_replace('/[^a-z\-_0-9\.:\/\s]/i','',mysql_escape_string(trim($_POST['building'])));
		$tktunitno=preg_replace('/[^a-z\-_0-9\.:\/\s]/i','',mysql_escape_string(trim($_POST['unitnumber'])));
		$tktloc=preg_replace('/[^a-z\-_0-9\.:\/\s]/i','',mysql_escape_string(trim($_POST['locationtown'])));
		$tktsms=preg_replace('/[^a-z\-_0-9\.:\/\s]/i','',mysql_escape_string(strip_tags(trim($_POST['txtsms']))));
		$tktmsg=preg_replace('/[^a-z\-_0-9\.:\/\s]/i','',mysql_escape_string(strip_tags(trim($_POST['txtmsg']))));
		$tkttype=preg_replace('/[^a-z\-_0-9\.:\/]/i','',mysql_escape_string(strip_tags(trim($_POST['tkttype']))));
		$tktchannel=preg_replace('/[^a-z\-_0-9\.:\/]/i','',mysql_escape_string(strip_tags(trim($_POST['tktchannel']))));
		$tktaction=preg_replace('/[^a-z\-_0-9\.:\/]/i','',mysql_escape_string(trim($_POST['action_to'])));
		$updateperm=preg_replace('/[^a-z\-_0-9\.:\/]/i','',mysql_escape_string(trim($_POST['up'])));
		$usrgender=mysql_escape_string($_POST['usrgender']);
		//clean up optional fields
		if (isset($_POST['close_1']))
			{
			$tkticonfirm=preg_replace('/[^a-z\-_0-9\.:\/]/i','',mysql_escape_string(trim($_POST['close_1'])));
			}
		if (isset($_POST['task_msg_1']))
			{
			$tkttskmsg1=preg_replace('/[^a-z\-_0-9\.:\/\s]/i','',mysql_escape_string(strip_tags(trim($_POST['task_msg_1']))));
			}
		if (isset($_POST['task_msg_2']))
			{
			$tkttskmsg2=preg_replace('/[^a-z\-_0-9\.:\/\s]/i','',mysql_escape_string(strip_tags(trim($_POST['task_msg_2']))));
			}
		if (isset($_POST['task_msg_3']))
			{
			$tkttskmsg3=preg_replace('/[^a-z\-_0-9\.:\/\s]/i','',mysql_escape_string(strip_tags(trim($_POST['task_msg_3']))));
			}
		if (isset($_POST['task_msg_4']))
			{
			$tkttskmsg4=preg_replace('/[^a-z\-_0-9\.:\/\s]/i','',mysql_escape_string(strip_tags(trim($_POST['task_msg_4']))));
			}
		if (isset($_POST['task_msg_5']))
			{
			$tkttskmsg5=preg_replace('/[^a-z\-_0-9\.:\/\s]/i','',mysql_escape_string(strip_tags(trim($_POST['task_msg_5']))));
			}
		if (isset($_POST['task_msg_6']))
			{
			$tkttskmsg6=preg_replace('/[^a-z\-_0-9\.:\/\s]/i','',mysql_escape_string(strip_tags(trim($_POST['task_msg_6']))));
			}
		if (isset($_POST['assign_to_2']))
			{
			$tktasito2=preg_replace('/[^a-z\-_0-9\.:\/]/i','',mysql_escape_string(trim($_POST['assign_to_2'])));
			}
		if (isset($_POST['assign_to_3']))
			{
			$tktasito3=preg_replace('/[^a-z\-_0-9\.:\/]/i','',mysql_escape_string(trim($_POST['assign_to_3'])));
			}
		if (isset($_POST['assign_to_5']))
			{
			$tktasito5=preg_replace('/[^a-z\-_0-9\.:\/]/i','',mysql_escape_string(trim($_POST['assign_to_5'])));
			}
		if (isset($_POST['invalid_id']))
			{
			$tktinvalidid=preg_replace('/[^a-z\-_0-9\.:\/]/i','',mysql_escape_string(trim($_POST['invalid_id'])));
			}
		if (isset($_POST['add_reason']))
			{
			$tktinvalidnew=preg_replace('/[^a-z\-_0-9\.:\/\s]/i','',mysql_escape_string(trim($_POST['add_reason'])));
			}
		if (isset($_POST['tktdate']))
			{
			$tktdate=preg_replace('/[^a-z\-_0-9\.:\/\s]/i','',mysql_escape_string(trim($_POST['tktdate'])));
			$tktdate_trans=str_replace('/','-',$tktdate);

			$tktdate_fin=date("Y-m-d H:i:s",strtotime($tktdate_trans));
			
			}
		if (isset($_POST['senderemail']))
			{
			$tktsendermail=mysql_escape_string(trim($_POST['senderemail']));
			}
		
			
		// let's validate that all the fields have been entered
		if ($tktcat < 1)
			{
			$error_1="<div class=\"msg_warning\">".$msg_warning_nocategory."</div>";
			}
		if (strlen($tktloc) < 1)
			{
			$error_2="<div class=\"msg_warning\">".$msg_warning_location."</div>";
			}
		$sql_confirmloc="SELECT idloctowns,locationname FROM loctowns WHERE locationname='".$tktloc."' LIMIT 1";
		$res_confirmloc=mysql_query($sql_confirmloc);
		$num_confirmloc=mysql_num_rows($res_confirmloc);
		$fet_confirmloc=mysql_fetch_array($res_confirmloc);
		
		if ($num_confirmloc < 1)
			{
			$error_3="<div class=\"msg_warning\">".$msg_warning_invalidloc."</div>";
			}
		if ($tktaction < 1)
			{
			$error_4="<div class=\"msg_warning\">".$msg_select_action."</div>";
			}
		if ( (isset($tktsendermail)) && (strlen($tktsendermail)>1) )
			{
			if ( (strlen($tktsendermail) > 5) && (!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $tktsendermail)) )
				{
				$error_5="<div class=\"msg_warning\">".$msg_warning_emailinv."</div>";
				}
			}
		if ( (isset($tktsendermail)) && (strlen($tktsendermail)>1) && (strlen($tktsendermail) < 6) )
			{
			$error_6 = "<div class=\"msg_warning\">".$msg_warning_emailinv."</div>";
			}
		
		if (strlen($tktmsg) < 5)
			{
			$error_7 = "<div class=\"msg_warning\">".$msg_warn_customermsg."</div>";
			}

		if ($tkttype < 1)
			{
			$error_8="<div class=\"msg_warning\">".$msg_warn_tkttype."</div>";
			}
		if ($tktchannel < 1)
			{
			$error_9="<div class=\"msg_warning\">".$msg_warn_tktchn."</div>";
			}
		if (strlen($tktdate_fin)<9)
			{
			$error_10="<div class=\"msg_warning\">".$msg_warn_newdl."</div>";
			}
		if (strlen($tktsender)<1)
			{
			$error_11="<div class=\"msg_warning\">".$msg_warning_fname."</div>";
			}
		if (strlen($tktsenderphone)!=12)
			{
			$error_12="<div class=\"msg_warning\">".$msg_warn_phone."</div>";
			}
	
		//first, check if there is a record by this user to this company (userteam in broad)
		if (!isset($record_exists) )
			{
			//run this query only where it is not excempted
			$sql_exists="SELECT sendername,senderemail,refnumber FROM tktin WHERE senderphone='".$tktsenderphone."' AND usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']." AND timereported>='".$thirty_days_ago."' LIMIT 1";
			$res_exists=mysql_query($sql_exists);
			$num_exists=mysql_num_rows($res_exists);
			$fet_exists=mysql_fetch_array($res_exists);
			
			if ($num_exists > 0 ) //if record is more than 0
				{
				$form_app="<div style=\"background-color:#FFFFCC; color:#CC0000; padding:5px 10px 5px 10px; font-size:12px;font-family:arial,verdana; line-height:150%; font-weight:bold;; margin:10px;text-align:left\">".$msg_warning_duplicate_tkt."</div>
				<div style=\"background-color:#FFFFFF;padding:10px 10px 10px 15px\"><span style=\"font-size:12px;font-family:arial,verdana;\"><a target=\"_blank\" href=\"pop_newticket_duplicate.php?sentby=".$tktsenderphone."\">View Duplicate</a></span></div><div style=\"background-color:#FFFFFF;padding:10px 10px 10px 10px\"><span style=\"font-size:12px;font-family:arial,verdana;font-weight:bold\"><input type=\"checkbox\" value=\"1\" name=\"exists_rec\"> Just Proceed! Ignore this warning</a></div>";
				
				$error_exists=1;
				}
			}
	
	//if no error, then go ahead to create this ticket
		if ( (!isset($error_1)) && (!isset($error_2)) && (!isset($error_3))  && (!isset($error_4))  && (!isset($error_5))  && (!isset($error_6))  && (!isset($error_7))  && (!isset($error_8))  && (!isset($error_9))  && (!isset($error_10))  && (!isset($error_11))  && (!isset($error_12)) && (!isset($error_exists)) )
			{
			
			//NEW TICKET RECORD
			//first, retrieve the total turn around time for this task to calculate it's deadline
			$sql_tat="SELECT idwfproc,wfproctat,tktcategoryname FROM wfproc 
			INNER JOIN link_tskcategory_wfproc ON wfproc.idwfproc=link_tskcategory_wfproc.wfproc_idwfproc 
			INNER JOIN tktcategory ON link_tskcategory_wfproc.tktcategory_idtktcategory=tktcategory.idtktcategory
			WHERE link_tskcategory_wfproc.tktcategory_idtktcategory=".$tktcat." AND link_tskcategory_wfproc.usrteamzone_idusrteamzone=".$_SESSION['MVGitHub_userteamzoneid']." LIMIT 1";
			$res_tat=mysql_query($sql_tat);
			$fet_tat=mysql_fetch_array($res_tat);
			
			//the first workflow of list order 00:000
			$sql_proc="SELECT idwfproc,idwftskflow,link_tskcategory_wfproc.wfproc_idwfproc,idwftskflow,wfproctat,wfproc.usrteam_idusrteam FROM link_tskcategory_wfproc
			INNER JOIN wfproc ON link_tskcategory_wfproc.wfproc_idwfproc=wfproc.idwfproc 
			INNER JOIN wftskflow ON wfproc.idwfproc=wftskflow.wfproc_idwfproc
			WHERE link_tskcategory_wfproc.tktcategory_idtktcategory=".$tktcat." AND wftskflow.listorder='0.00' AND wfsymbol_idwfsymbol=1 AND wfproc.wfstatus=1 LIMIT 1";
					
			//echo $sql_proc."<br>";
			$res_proc=mysql_query($sql_proc);
			$num_proc=mysql_num_rows($res_proc);
			$fet_proc=mysql_fetch_array($res_proc);
			
			///generate the workflow id for the task for the customer care 
			$sql_nextstep="SELECT idwftskflow FROM wftskflow WHERE listorder > '0.00' AND wfproc_idwfproc=".$fet_proc['idwfproc']." ORDER BY idwftskflow ASC LIMIT 1";
			$res_nextstep=mysql_query($sql_nextstep);
			$fet_nextstep=mysql_fetch_array($res_nextstep);
			
			
			$deadline=date("Y-m-d H:i:s",strtotime(date("Y-m-d H:i:s",strtotime($tktdate_fin))) + $fet_tat['wfproctat']);
			
			//generate new ticket
			$sql_ticketlast="SELECT idtktinPK,refnumber FROM tktin ORDER BY idtktinPK DESC LIMIT 1";
			$res_ticketlast=mysql_query($sql_ticketlast);
			$fet_ticketlast=mysql_fetch_array($res_ticketlast);
			$num_ticketlast=mysql_num_rows($res_ticketlast);
							
							
							
							if ($num_ticketlast==0) //if number of records is zero, then this is the first one
								{
								
								$ticketref = "A001AAAA";
								
								} else { //else if there is a record, then process
								
								$ticketno=$fet_ticketlast['refnumber'];
								
								for ($i=0; $i<1; $i++)
									{
									$ticketno++;
									}
								
								$ticketref=$ticketno;
								
								} //close if number of records is zero
			
			//determine closure date
			if ($tktaction==1)
				{
				$dateclose=$timenowis;
				} else {
				$dateclose="0000-00-00 00:00:00";
				}
			
			
			//insert new ticket
			$sql_newtkt="INSERT INTO tktin (tktlang_idtktlang, usrteamzone_idusrteamzone, usrteam_idusrteam, tktgroup_idtktgroup, tktchannel_idtktchannel, tktstatus_idtktstatus, tktcategory_idtktcategory, tkttype_idtkttype, sendername,sendergender, senderemail, senderphone, refnumber, tktdesc, timereported, timedeadline, timeclosed, city_town, loctowns_idloctowns, road_street, building_estate, unitno, waterac, kioskno, usrsession, createdby, createdon) 
			VALUES ( '1','".$_SESSION['MVGitHub_userteamzoneid']."', '".$_SESSION['MVGitHub_idacteam']."', '0', '".$tktchannel."', '1', '".$tktcat."', '".$tkttype."', '".$tktsender."','".$usrgender."', '".$tktsendermail."', '".$tktsenderphone."', '".$ticketref."', '".$tktmsg."', '".$tktdate_fin."','".$deadline."', '".$dateclose."', '".$tktloc."', '".$fet_confirmloc['idloctowns']."', '".$tktstreet."', '".$tktbuilding."', '".$tktunitno."', '".$tktacno."', '".$tktkiosk."', '".session_id()."', '".$_SESSION['MVGitHub_idacname']."', '".$timenowis."')";
			mysql_query($sql_newtkt);	
			//echo $sql_newtkt;
			
			
			$sql_tktid="SELECT idtktinPK,refnumber,tktcategoryname FROM tktin 
			INNER JOIN tktcategory ON tktin.tktcategory_idtktcategory=tktcategory.idtktcategory
			WHERE tktin.createdby=".$_SESSION['MVGitHub_idacname']." ORDER BY idtktinPK DESC LIMIT 1";
			$res_tktid=mysql_query($sql_tktid);
			$fet_tktid=mysql_fetch_array($res_tktid);
			//echo $sql_tktid;
			$_SESSION['idtktintrans']=$fet_tktid['idtktinPK'];
			//exit;
			//THEN THIS TASK CREATES A NEW AUTO TASK RECORD
			////////////// START CALCULATION OF TIME /////////
						//find the next tasks
						$sql_nextwf="SELECT idwftskflow,wftskflow.wfsymbol_idwfsymbol as wfsymbol,wfactors.usrrole_idusrrole as usrrole,wfactors.usrgroup_idusrgroup as usrgroup,wftsktat,expubholidays
						FROM wftskflow INNER JOIN wfactors ON wftskflow.idwftskflow=wfactors.wftskflow_idwftskflow 
						WHERE wfproc_idwfproc=".$fet_tat['idwfproc']." AND wftskflow.wfsymbol_idwfsymbol=2 AND wftskflow.listorder>'0.00' ORDER BY idwftskflow ASC LIMIT 1";
						//echo $sql_nextwf."<br>";
						$res_nextwf=mysql_query($sql_nextwf);
						$num_nextwf=mysql_num_rows($res_nextwf);
						$fet_nextwf=mysql_fetch_array($res_nextwf);
						
							
								//1. construct deadlines and start times against TATs and time task was received
								$ticket_wday = date("w",strtotime($tktdate_fin)); //ticket day of the week
								$ticket_hour = date("H:i",strtotime($tktdate_fin)); //ticket hour	
								$ticket_actualtimein = $tktdate_fin;
								//$ticket_timein = $timenowis;
								
								$task_starttime_raw = $ticket_actualtimein;//exactly the time the task came in on the record
								$task_deadline_raw = date("Y-m-d H:i:s",strtotime($ticket_actualtimein)+$fet_nextwf['wftsktat']); //this is for the specific task
								$task_overalldeadline_raw = date("Y-m-d H:i:s",strtotime($ticket_actualtimein)+$fet_tat['wfproctat']); //this is the overall time set for the whole process should take
								
									
								//CONSTRUCT STARTING TIME
								//a) Did this task fall on a Weekday, Working hours ?
								if (($ticket_wday>0) && ($ticket_wday<6)) //Monday - Friday
									{
									//check if it was a weekday
									$sql_workinghrs="SELECT time_earliest,time_latest,wfworkingdays_idwfworkingdays FROM wfworkinghrs WHERE wftskflow_idwftskflow=".$fet_proc['idwftskflow']." AND wfworkingdays_idwfworkingdays=1";
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
	
									$sql_saturdays="SELECT time_earliest,time_latest,wfworkingdays_idwfworkingdays FROM wfworkinghrs WHERE wftskflow_idwftskflow=".$fet_proc['idwftskflow']." AND wfworkingdays_idwfworkingdays=2 LIMIT 1";
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
									
									$push_saturday =0;		
																		
									} //close if saturday
										
									
								if ($ticket_wday==0) // Sunday
										{
										//check if the task applies for sundays
										$sql_sundays="SELECT time_earliest,time_latest,wfworkingdays_idwfworkingdays FROM wfworkinghrs WHERE wftskflow_idwftskflow=".$fet_proc['idwftskflow']." AND wfworkingdays_idwfworkingdays=3 LIMIT 1";
										
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
									
										$push_sunday =0;
										
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
															
								$task_starttime_final = $task_starttime_prefinal; //the start time remains the same... only adjust the deadline to discount off the extra weekend days
								$task_deadline_final = date("Y-m-d H:i:s",strtotime($task_deadline_prefinal) + ($push_ex_saturdays + $push_ex_sundays));
								$task_overalldeadline_final = date("Y-m-d H:i:s",strtotime($task_overalldeadline_prefinal) + ($push_ex_saturdays + $push_ex_sundays));
						
						///////////// END CALCULATION OF TIME ////////
						
						//generate a trac number for this ticket
			$sql_gentrac="INSERT INTO wftaskstrac (createdon,createdby) VALUES ('".$timenowis."','".$_SESSION['MVGitHub_idacname']."')";
			mysql_query($sql_gentrac);
			
			$sql_trac="SELECT idwftaskstrac FROM wftaskstrac WHERE createdby=".$_SESSION['MVGitHub_idacname']." ORDER BY idwftaskstrac DESC LIMIT 1";
			$res_trac=mysql_query($sql_trac);
			$fet_trac=mysql_fetch_array($res_trac);
			//echo $sql_trac;
															
						//insert new task for the recepeint
						$sql_new_task="INSERT INTO wftasks (wftaskstrac_idwftaskstrac,usrrole_idusrrole,wftasks_idwftasks,wftskflow_idwftskflow,tktin_idtktin,usrac_idusrac,wftskstatustypes_idwftskstatustypes,wftskstatusglobal_idwftskstatusglobal,tasksubject,taskdesc,timeinactual,timeoveralldeadline,timetatstart,timedeadline,timeactiontaken,sender_idusrrole,sender_idusrac,createdon,createdby) 
						VALUES ('".$fet_trac['idwftaskstrac']."','".$_SESSION['MVGitHub_iduserrole']."','0','".$fet_nextstep['idwftskflow']."','".$fet_tktid['idtktinPK']."','".$_SESSION['MVGitHub_idacname']."','1','3','".$fet_tktid['tktcategoryname']." - ".$ticketref."','[MANUAL ENTRY]".$tktmsg."','".$tktdate_fin."','".$deadline."','".$task_starttime_final."','".$task_deadline_final."','".$timenowis."','2','2','".$timenowis."','".$_SESSION['MVGitHub_idacname']."')";
						mysql_query($sql_new_task);
						//echo $sql_new_task."<br>";
						//retreive
						$sql_idtask="SELECT idwftasks,wftaskstrac_idwftaskstrac,usrrole_idusrrole FROM wftasks WHERE createdby=".$_SESSION['MVGitHub_idacname']." ORDER BY idwftasks DESC LIMIT 1";
						$res_idtask=mysql_query($sql_idtask);
						$fet_idtask=mysql_fetch_array($res_idtask);
						
						//echo $sql_idtask."<br>";
						
						$_SESSION['wtaskid']=$fet_idtask['idwftasks'];
			
			//FINALLY, THE APPROPRIATE ACTION CHOSEN BY THE USER
///////////////  ACTION 1  ///////////////////////////////////////////////////////////////////////////////////////////////////
		
			//option 1 = Close Task as per DB key
			if ($tktaction==1) //Select Task Action 1
				{
				//validate
				if (strlen($tkttskmsg1) < 1)
					{
					$error_1_1="<div class=\"msg_warning\">".$msg_warn_msgmis."</div>";
					}
				if ((!isset($tkticonfirm)) || ($tkticonfirm!=1))
					{
					$error_1_2="<div class=\"msg_warning\">".$msg_warn_confirm."</div>";
					}
					
				if ( (!isset($error_1_1)) && (!isset($error_1_2)) )//if the no error 
					{
					//create an update message on the record
					$sql_update_msg="INSERT INTO wftskupdates (wftaskstrac_idwftaskstrac,usrrole_idusrrole,usrac_idusrac,wftskstatusglobal_idwftskstatusglobal,wftskstatustypes_idwftskstatustypes,wftasks_idwftasks,wftskupdate,createdby,createdon) 
					VALUES ('".$fet_idtask['wftaskstrac_idwftaskstrac']."','".$_SESSION['MVGitHub_iduserrole']."','".$_SESSION['MVGitHub_idacname']."','3','1','".$_SESSION['wtaskid']."','".$tkttskmsg1."','".$_SESSION['MVGitHub_idacname']."','".$timenowis."')";
					mysql_query($sql_update_msg);
					
					//Close this Task
					//$sql_update_task="UPDATE wftasks SET wftskstatustypes_idwftskstatustypes='1',wftskstatusglobal_idwftskstatusglobal='3',sender_idusrrole='".$_SESSION['MVGitHub_iduserrole']."',sender_idusrac='".$_SESSION['MVGitHub_idacname']."',timeactiontaken='".$timenowis."' WHERE idwftasks=".$_SESSION['wtaskid']." LIMIT 1";
					//mysql_query($sql_update_task);
					
					//Feedback SMS to send customer/sender a message
					if ( (isset($tktsms)) && (strlen($tktsms)>5) && (strlen($tktsenderphone)==12) )
						{
						$sql_smsout="INSERT INTO mdata_out_sms (destnumber,msgtext)  
						VALUES ('".$tktsenderphone."','".$_SESSION['MVGitHub_userteamshortname']." ".$pagetitle." Ticket ".$ticketref."-".$tktsms."')";
						mysql_query($sql_smsout);
						}
					
					//Update the ticket status
					if ($updateperm==1) //if set, then then update ticket details
							{
							$sql_updatetkt="UPDATE tktin SET 
							tktstatus_idtktstatus='4',
							tktcategory_idtktcategory='".$tktcat."',sendername='".$tktsender."',
							timeclosed='".$timenowis."',city_town='".$tktloc."',
							loctowns_idloctowns='".$fet_confirmloc['idloctowns']."',road_street='".$tktstreet."',building_estate='".$tktbuilding."',unitno='".$tktunitno."',
							waterac='".$tktacno."',kioskno='".$tktkiosk."',usrsession='".session_id()."',
							modifiedby='".$_SESSION['MVGitHub_idacname']."',modifiedon='".$timenowis."' WHERE idtktinPK=".$fet_tktid['idtktinPK']." LIMIT 1";
							mysql_query($sql_updatetkt);
							}
					
					//notify if anyone is to be notified
					$sql_notify="SELECT idwfnotification,wfnotification.tktstatus_idtktstatus,usrrole_idusrrole,wftskflow_idwftskflow,notify_system,notify_email,notify_sms,idtktmsgs,tktmsg_sms,tktmsg_email,tktmsg_dashboard FROM wfnotification 
					INNER JOIN tktmsgs ON wfnotification.idwfnotification=tktmsgs.wfnotification_idwfnotification
					WHERE wftskflow_idwftskflow=".$fet_proc['idwftskflow']." ORDER BY idwfnotification ASC";
					$res_notify=mysql_query($sql_notify);
					$num_notify=mysql_num_rows($res_notify);
					$fet_notify=mysql_fetch_array($res_notify);
					
					if ($num_notify > 0 ) // if there is a notification setting
						{
						do {			
						//check for each of the settings 
							if ( ($fet_notify['notify_system']==1) && (strlen($fet_notify['tktmsg_dashboard'])>2) ) //system dashboard set on
								{
								$sql_dash="INSERT INTO tktmsglogs_dashboard (tktmsgs_idtktmsgs,msgto_roleid,msgto_subject,msgto_body,createdon,readon)
								VALUES ('".$fet_notify['idtktmsgs']."','".$fet_notify['usrrole_idusrrole']."','Notification - Tkt No : ".$ticketref.", ".$fet_tktid['tktcategoryname'].",'".$fet_notify['tktmsg_dashboard']." - ".$fet_tktid['tktcategoryname']."','".$timenowis."','0000-00-00 00:00:00')";
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
									VALUES ('".$fet_notify['idtktmsgs']."','".$fet_rolecontacts['usremail']."','Notification - Tkt No : ".$ticketref.", ".$fet_tktid['tktcategoryname']."','".$fet_notify['tktmsg_email']." - Tkt No:".$ticketref.", ".$fet_tktid['tktcategoryname']."','".$timenowis."','0000-00-00 00:00:00')";
										
									mysql_query($sql_email);
									}
									
									if ( ($fet_notify['notify_sms']==1) && ($num_rolecontacts>0) && (strlen($fet_rolecontacts['usrphone'])==13) )
									{
									$sql_sms="INSERT INTO mdata_out_sms (destnumber,msgtext)
									VALUES ('".$fet_rolecontacts['usrphone']."','".$pagetitle." Auto Notification - Tkt No:".$ticketref.", ".$fet_tktid['tktcategoryname']." received')";
			
									mysql_query($sql_sms);
									}
										
							} while ($fet_notify=mysql_fetch_array($res_notify));								
								
						} //close - if there is a notification setting
					
					//redirect to the correct page
					//header('location:pop_viewtaskhistory.php');
					/////////////////////////////check and insert a new subscriber
					
						//check if a subscriber with the same credentials matches
						$sql_subis="SELECT idsmssubs FROM ".$_SESSION['MVGitHub_tblsmsbc']." WHERE subnumber='".$tktsenderphone."' AND usrtype=1";
						$res_subis=mysql_query($sql_subis);
						$num_subis=mysql_num_rows($res_subis);

						//if not, add the new credentials
						if ($num_subis==0)
							{
							$sql_subnew="INSERT INTO ".$_SESSION['MVGitHub_tblsmsbc']." (wftskid,tktid,subnumber,idloctown,idusrteamzone,usrtype,createdon,createdby)
							VALUES ('".$_SESSION['wtaskid']."','".$fet_tktid['idtktinPK']."','".$tktsenderphone."','".$fet_confirmloc['idloctowns']."','".$_SESSION['MVGitHub_userteamzoneid']."','1','".$timenowis."','".$_SESSION['MVGitHub_idacname']."')";
							mysql_query($sql_subnew);
							}
						
					///////////////////////////// close check and insert new subscriber ////////////////////////////
					?>
                  <script language="javascript">
					window.location='go_to_taskhistory_s.php?msg=<?php echo urlencode($msg_changes_saved);?>';
					</script>
					<?php
                    exit;
					
					} //close if no action 1 error
				
				} 
				
///////////////  ACTION 2  ///////////////////////////////////////////////////////////////////////////////////////////////////
				
				if ($tktaction==2) { //Select Task Action 2 ie: pass it on

					//validate
					if (strlen($tkttskmsg2) < 1)
						{
						$error_2_1="<div class=\"msg_warning\">".$msg_warn_msgmis."</div>";
						}
					if ($tktasito2<1)
						{
						$error_2_2="<div class=\"msg_warning\">".$msg_warn_assign."</div>";
						}
						
					if ( (!isset($error_2_1)) && (!isset($error_2_2)) )//if the no error 
						{
						
						//update this task 
//						$sql_update_task="UPDATE wftasks SET wftskstatustypes_idwftskstatustypes='2',wftskstatusglobal_idwftskstatusglobal='2',sender_idusrrole='".$_SESSION['MVGitHub_iduserrole']."',sender_idusrac='".$_SESSION['MVGitHub_idacname']."',timeactiontaken='".$timenowis."'  WHERE idwftasks=".$_SESSION['wtaskid']." LIMIT 1";
						$sql_update_task="UPDATE wftasks SET wftskstatustypes_idwftskstatustypes='2',wftskstatusglobal_idwftskstatusglobal='2',timeactiontaken='".$timenowis."'  WHERE idwftasks=".$_SESSION['wtaskid']." LIMIT 1";
						mysql_query($sql_update_task);
						
						//create an update message on the record
						$sql_update_msg="INSERT INTO wftskupdates (wftaskstrac_idwftaskstrac,usrrole_idusrrole,usrac_idusrac,wftskstatusglobal_idwftskstatusglobal,wftskstatustypes_idwftskstatustypes,wftasks_idwftasks,wftskupdate,createdby,createdon) 
						VALUES ('".$fet_idtask['wftaskstrac_idwftaskstrac']."','".$_SESSION['MVGitHub_iduserrole']."','".$_SESSION['MVGitHub_idacname']."','2','2','".$_SESSION['wtaskid']."','".$tkttskmsg2."','".$_SESSION['MVGitHub_idacname']."','".$timenowis."')";
						mysql_query($sql_update_msg);
						
						//get task details
						$sql_task_details = "SELECT wftasks.wftaskstrac_idwftaskstrac,wftasks.idwftasks,wftasks.usrrole_idusrrole,wftasks.wftasks_idwftasks,wftasks.wftskflow_idwftskflow,wftskflow.wfproc_idwfproc,wftasks.tktin_idtktin,wftasks.usrac_idusrac,wftasks.wftskstatustypes_idwftskstatustypes,wftasks.wftskstatusglobal_idwftskstatusglobal,wftasks.tasksubject,wftasks.taskdesc,wftasks.timeinactual,wftasks.timeoveralldeadline,wftasks.timetatstart,wftasks.timedeadline,wftasks.timeactiontaken,wftasks.sender_idusrrole,wftasks.sender_idusrac,wftskflow.listorder,wftskflow.idwftskflow,wftskflow.wftsktat,wfproc.wfproctat FROM wftasks 
						INNER JOIN wftskflow ON wftasks.wftskflow_idwftskflow=wftskflow.idwftskflow 
						INNER JOIN wfproc ON wftskflow.wfproc_idwfproc=wfproc.idwfproc
						WHERE idwftasks=".$_SESSION['wtaskid']." LIMIT 1";
						$res_task_details = mysql_query($sql_task_details);
						$fet_task_details = mysql_fetch_array($res_task_details);
						//echo $sql_task_details;
						////////////// START CALCULATION OF TIME /////////
						//find the next tasks
						$sql_nextwf="SELECT idwftskflow,wftskflow.wfsymbol_idwfsymbol as wfsymbol,wfactors.usrrole_idusrrole as usrrole,wfactors.usrgroup_idusrgroup as usrgroup,wftsktat,expubholidays
						FROM wftskflow INNER JOIN wfactors ON wftskflow.idwftskflow=wfactors.wftskflow_idwftskflow 
						WHERE wfproc_idwfproc=".$fet_task_details['wfproc_idwfproc']." AND wftskflow.wfsymbol_idwfsymbol=2 AND wftskflow.listorder>'".$fet_task_details['listorder']."' ORDER BY idwftskflow ASC LIMIT 1";
						//echo $sql_nextwf."<br>";
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
									
									$push_saturday =0;					
									} //close if saturday
										
									
								if ($ticket_wday==0) // Sunday
										{
										//check if the task applies for sundays
										$sql_sundays="SELECT time_earliest,time_latest,wfworkingdays_idwfworkingdays FROM wfworkinghrs WHERE wftskflow_idwftskflow=".$fet_proc['idwftskflow']." AND wfworkingdays_idwfworkingdays=3 LIMIT 1";
										
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
									
										$push_sunday =0;
										
										} //if sunday
								
								
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
															
								$task_starttime_final = $task_starttime_prefinal; //the start time remains the same... only adjust the deadline to discount off the extra weekend days
								$task_deadline_final = date("Y-m-d H:i:s",strtotime($task_deadline_prefinal) + ($push_ex_saturdays + $push_ex_sundays));
								$task_overalldeadline_final = date("Y-m-d H:i:s",strtotime($task_overalldeadline_prefinal) + ($push_ex_saturdays + $push_ex_sundays));
						
						///////////// END CALCULATION OF TIME ////////
						
						//get user account id
						$sql_userid="SELECT idusrac FROM usrac WHERE usrrole_idusrrole=".$tktasito2." LIMIT 1";
						$res_userid=mysql_query($sql_userid);
						$fet_userid=mysql_fetch_array($res_userid);
						
						
						//insert new task for the recepeint
						$sql_new_task="INSERT INTO wftasks (wftaskstrac_idwftaskstrac,usrrole_idusrrole,wftasks_idwftasks,wftskflow_idwftskflow,tktin_idtktin,usrac_idusrac,wftskstatustypes_idwftskstatustypes,wftskstatusglobal_idwftskstatusglobal,tasksubject,taskdesc,timeinactual,timeoveralldeadline,timetatstart,timedeadline,timeactiontaken,sender_idusrrole,sender_idusrac,createdon) 
						VALUES ('".$fet_task_details['wftaskstrac_idwftaskstrac']."','".$tktasito2."','".$fet_task_details['idwftasks']."','".$fet_nextwf['idwftskflow']."','".$fet_task_details['tktin_idtktin']."','".$fet_userid['idusrac']."','0','1','".$fet_tktid['tktcategoryname']." - ".$ticketref."','".$tkttskmsg2."','".$timenowis."','".$fet_task_details['timeoveralldeadline']."','".$task_starttime_final."','".$task_deadline_final."','0000-00-00 00:00:00','".$_SESSION['MVGitHub_iduserrole']."','".$_SESSION['MVGitHub_idacname']."','".$timenowis."')";
						mysql_query($sql_new_task);
						//echo $sql_new_task;
						//exit;
						//Feedback SMS to send customer/sender a message
						if ( (isset($tktsms)) && (strlen($tktsms)>5) && (strlen($tktsenderphone)==12) )
							{
							$sql_smsout="INSERT INTO mdata_out_sms (destnumber,msgtext) 
							VALUES ('".$tktsenderphone."','".$_SESSION['MVGitHub_userteamshortname']." ".$pagetitle." Ticket ".$ticketref."-".$tktsms."')";
							mysql_query($sql_smsout);
							
							//echo $sql_smsout;
							}
					
							//Update the ticket status
							if ($updateperm==1) //if set, then then update ticket details
								{
								$sql_updatetkt="UPDATE tktin SET 
								tktstatus_idtktstatus='2',
								tktcategory_idtktcategory='".$tktcat."',sendername='".$tktsender."',
								timeclosed='".$timenowis."',city_town='".$tktloc."',
								loctowns_idloctowns='".$fet_confirmloc['idloctowns']."',road_street='".$tktstreet."',building_estate='".$tktbuilding."',unitno='".$tktunitno."',
								waterac='".$tktacno."',kioskno='".$tktkiosk."',usrsession='".session_id()."',
								modifiedby='".$_SESSION['MVGitHub_idacname']."',modifiedon='".$timenowis."' WHERE idtktinPK=".$fet_tktid['idtktinPK']." LIMIT 1";
								mysql_query($sql_updatetkt);
								}

							//notify if anyone is to be notified
							$sql_notify="SELECT idwfnotification,wfnotification.tktstatus_idtktstatus,usrrole_idusrrole,wftskflow_idwftskflow,notify_system,notify_email,notify_sms,idtktmsgs,tktmsg_sms,tktmsg_email,tktmsg_dashboard FROM wfnotification 
							INNER JOIN tktmsgs ON wfnotification.idwfnotification=tktmsgs.wfnotification_idwfnotification
							WHERE wftskflow_idwftskflow=".$fet_proc['idwftskflow']." ORDER BY idwfnotification ASC";
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
										VALUES ('".$fet_notify['idtktmsgs']."','".$fet_notify['usrrole_idusrrole']."','Notification - Tkt No : ".$ticketref.", ".$fet_tktid['tktcategoryname'].",'".$fet_notify['tktmsg_dashboard']." - ".$fet_tktid['tktcategoryname']."','".$timenowis."','0000-00-00 00:00:00')";
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
											VALUES ('".$fet_notify['idtktmsgs']."','".$fet_rolecontacts['usremail']."','Notification - Tkt No : ".$ticketref.", ".$fet_tktid['tktcategoryname']."','".$fet_notify['tktmsg_email']." - Tkt No:".$ticketref.", ".$fet_tktid['tktcategoryname']."','".$timenowis."','0000-00-00 00:00:00')";
												
											mysql_query($sql_email);
											}
											
											if ( ($fet_notify['notify_sms']==1) && ($num_rolecontacts>0) && (strlen($fet_rolecontacts['usrphone'])==13) )
											{
											$sql_sms="INSERT INTO mdata_out_sms (destnumber,msgtext) 
											VALUES ('".$fet_rolecontacts['usrphone']."','".$pagetitle." Auto Notification - Tkt No:".$ticketref.", ".$fet_tktid['tktcategoryname']." received')";
					
											mysql_query($sql_sms);
											}
												
									} while ($fet_notify=mysql_fetch_array($res_notify));								
										
								} //close - if there is a notification setting
					
					/////////////////////////////check and insert a new subscriber
						//check if a subscriber with the same credentials matches
						$sql_subis="SELECT idsmssubs FROM ".$_SESSION['MVGitHub_tblsmsbc']." WHERE subnumber='".$tktsenderphone."' AND usrtype=1";
						$res_subis=mysql_query($sql_subis);
						$num_subis=mysql_num_rows($res_subis);
						//echo $sql_subis;
						//if not, add the new credentials
						if ($num_subis==0)
							{
							$sql_subnew="INSERT INTO ".$_SESSION['MVGitHub_tblsmsbc']." (wftskid,tktid,subnumber,idloctown,idusrteamzone,usrtype,createdon,createdby)
							VALUES ('".$_SESSION['wtaskid']."','".$fet_tktid['idtktinPK']."','".$tktsenderphone."','".$fet_confirmloc['idloctowns']."','".$_SESSION['MVGitHub_userteamzoneid']."','1','".$timenowis."','".$_SESSION['MVGitHub_idacname']."')";
							mysql_query($sql_subnew);
							//echo $sql_subnew;
							}
					///////////////////////////// close check and insert new subscriber ////////////////////////////
					
					//redirect to the correct page
					?>
                 <script language="javascript">
					window.location='go_to_taskhistory_s.php?msg=<?php echo urlencode($msg_changes_saved);?>';
				</script>
					<?php //exit;
						
						
						} //close no error on action 2
				
				} //close action 2
				

				
			}//close if no error
	
	} //form action to update ticket 
	
	


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Task Details</title>
<link href="../assets_backend/css/style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../scripts/jquery-latest.js"></script>
<script type="text/javascript" src="../scripts/animatedcollapse.js">

/***********************************************
* Animated Collapsible DIV v2.4- (c) Dynamic Drive DHTML code library (www.dynamicdrive.com)
* This notice MUST stay intact for legal use
* Visit Dynamic Drive at http://www.dynamicdrive.com/ for this script and 100s more
***********************************************/

</script>


<script type="text/javascript">
animatedcollapse.addDiv('details', 'fade=0,speed=400,group=pets')
animatedcollapse.addDiv('contacts', 'fade=0,speed=400,group=pets,persist=1,hide=1')
animatedcollapse.addDiv('feedback', 'fade=0,speed=400,group=pets,hide=1')

animatedcollapse.ontoggle=function($, divobj, state){ //fires each time a DIV is expanded/contracted
	//$: Access to jQuery
	//divobj: DOM reference to DIV being expanded/ collapsed. Use "divobj.id" to get its ID
	//state: "block" or "none", depending on state
}

animatedcollapse.init()

</script>
<script type="text/javascript" src="../scripts/jquery-1.7.2.min.js"></script>
<script language="JavaScript" src="../scripts/ts_picker.js"></script>
<script language="javascript">
//restrict to numbers or alpha
var numb = "0123456789";
var alpha = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ ";
function res(t,v){
var w = "";
for (i=0; i < t.value.length; i++) {
x = t.value.charAt(i);
if (v.indexOf(x,0) != -1)
w += x;
}
t.value = w;
}


function lookup(locationtown) {
		if(document.task.locationtown.length == 0) {
			// Hide the suggestion box.
			$('#suggestions').hide();
		} else {
			$.post("findlocation.php", {queryString: ""+locationtown+""}, function(data){
				if(data.length >0) {
				
					$('#suggestions').show();
					$('#autoSuggestionsList').html(data);
				}
			});
		}
	} // lookup
	
	function fill(thisValue) {
		$('#locationtown').val(thisValue);
		setTimeout("$('#suggestions').hide();", 200);
	}
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
<script type='text/javascript'>
//list the relevant fields basedon the action selected by the user
//<![CDATA[ 
$(window).load(function(){
$('.switchaction').change(function(){
    var selected = $(this).find(':selected');
    $('.actionlist').hide();
   $('.'+selected.val()).show(); 
    $('.optionvalue').html(selected.html());
});
});//]]>  

//hide show invalid reasons text box
    $(function() {
        $('#invalid_id').change(function(){
            $('.invalid_new').hide();
            $('#' + $(this).val()).show();
        });
    });
</script>
</head>
<?php //flush(); ?>
<body>
<div>
	<div class="tbl_sh">
    <table border="0" cellpadding="0" cellspacing="0" width="800">
  		<tr>
        	<td width="660">
            <?php echo $lbl_ticket_new;?>

            </td>
        	<td align="right">
            	<a href="#" onClick="parent.tb_remove();" id="button_closewin"></a>
            </td>
      </tr>
    </table>
    </div>
  
    <div>
    <?php
	if ( (isset($error_1)) || (isset($error_2)) || (isset($error_3)) || (isset($error_4)) || (isset($error_5)) || (isset($error_6)) || (isset($error_7)) || (isset($error_8)) || (isset($error_9)) || (isset($error_10))  || (isset($error_11)) || (isset($error_12)) || (isset($error_1_1)) || (isset($error_1_2)) || (isset($error_2_1)) ||  (isset($error_2_2)) ||  (isset($error_3_1)) || (isset($error_3_2)) || (isset($error_3_3)) || (isset($error_3_4)) || (isset($error_4_1)) || (isset($error_4_3)) || (isset($error_4_4)) || (isset($error_5_1)) || (isset($error_5_2)) || (isset($error_6_1)) )
	{
		if (isset($error_1)) { echo $error_1; }
		if (isset($error_2)) { echo $error_2; }
		if (isset($error_3)) { echo $error_3; }
		if (isset($error_4)) { echo $error_4; }
		if (isset($error_5)) { echo $error_5; }
		if (isset($error_6)) { echo $error_6; }
		if (isset($error_7)) { echo $error_7; }
		if (isset($error_8)) { echo $error_8; }
		if (isset($error_9)) { echo $error_9; }
		if (isset($error_10)) { echo $error_10; }
		if (isset($error_11)) { echo $error_11; }
		if (isset($error_12)) { echo $error_12; }
		if (isset($error_1_1)) { echo $error_1_1; }
		if (isset($error_1_2)) { echo $error_1_2; }
		if (isset($error_2_1)) { echo $error_2_1; }
		if (isset($error_2_2)) { echo $error_2_2; }
		if (isset($error_3_1)) { echo $error_3_1; }
		if (isset($error_3_2)) { echo $error_3_2; }
		if (isset($error_3_3)) { echo $error_3_3; }
		if (isset($error_3_4)) { echo $error_3_4; }
		if (isset($error_4_1)) { echo $error_4_1; }
		if (isset($error_4_3)) { echo $error_4_3; }
		if (isset($error_4_4)) { echo $error_4_4; }
		if (isset($error_5_1)) { echo $error_5_1; }
		if (isset($error_5_2)) { echo $error_5_2; }
		if (isset($error_6_1)) { echo $error_6_1; }
	}
	?>
    </div>

        <form method="post" action="" name="task" id="task">
        <div>
        <?php
		if (isset($form_app)) { echo $form_app; } ?>
        </div>
	<div >
    <table border="0" width="100%" cellpadding="0" cellspacing="0">
    	<tr>
        	<td valign="top" width="50%" class="hline">
            <?php
			//Does this userprofile have permissions to edit/update TICKET VIEWING MODULE
			$sql_perminsert="SELECT perminsert FROM systemprofileaccess WHERE syssubmodule_idsyssubmodule=24  AND sysprofiles_idsysprofiles=".$_SESSION['MVGitHub_iduserprofile']." LIMIT 1";
			$res_perminsert=mysql_query($sql_perminsert);
			$num_perminsert=mysql_num_rows($res_perminsert);
			$fet_perminsert=mysql_fetch_array($res_perminsert);
			
			if ($num_perminsert > 0)
				{
				if ($fet_perminsert['perminsert']==1)
					{
					$field_status="";
					} else if ($fet_perminsert['perminsert']==0) {
					$field_status=" readonly=\"readonly\" style=\"background-color:#EFEFEF\" ";
					}
				} else if ($num_perminsert < 1) {
				$field_status=" readonly=\"readonly\" style=\"background-color:#EFEFEF\" ";
				}
		?>
			  <div style="padding:2px 8px 2px 8px">   
                        
                <div class="table_header" ><?php echo $lbl_ticketdetails;?> </div>
                  <div  style="background-color:#FFFFFF">
                    <table border="0" width="100%" cellpadding="2" cellspacing="0">
                    <tr>
                        <td width="40%" class="tbl_data"><?php echo $lbl_ticketno;?></td>
                        <td class="tbl_data">
                        <input type="hidden" value="<?php echo $fet_perminsert['perminsert'];?>" name="up" />
						<input name="tktnumber" type="text" class="small_field" id="tktnumber" style="background-color:#EFEFEF" value="AUTO_GENERATED" readonly="readonly" />
                        </td>
                    </tr>
                    <tr>
                    	<td class="tbl_data">
                        <?php echo $lbl_asterik;?><?php echo $lbl_tkttype;?>
                        </td>
                        <td class="tbl_data">
                        <select name="tkttype">
                        <option value="">---</option>
                        <?php 
						$sql_tkttype="SELECT * FROM tkttype";
						$res_tkttype=mysql_query($sql_tkttype);
						$fet_tkttype=mysql_fetch_array($res_tkttype);
							do {
							echo "<option value=\"".$fet_tkttype['idtkttype']."\" ";
							if ((isset($tkttype)) && ($tkttype==$fet_tkttype['idtkttype'])) { echo " selected=\"selected\" "; }
							echo ">".$fet_tkttype['tkttypename']."</option>";
							} while ($fet_tkttype=mysql_fetch_array($res_tkttype));
						?>
                        </select>
                        </td>
                    </tr>
                    <tr>
                        <td width="40%" class="tbl_data"><?php echo $lbl_asterik;?><?php echo $lbl_ticketchn;?></td>
                      <td class="tbl_data">
                        <select name="tktchannel">
                        <?php
						$sql_channel="SELECT idtktchannel,tktchannelname FROM tktchannel WHERE idtktchannel>4";
						$res_channel=mysql_query($sql_channel);
						$num_channel=mysql_num_rows($res_channel);
						$fet_channel=mysql_fetch_array($res_channel);
						do {
						echo "<option";
						if ($_SESSION['presetchannel']==$fet_channel['idtktchannel'])
							{
							echo " selected=\"selected\" ";
							}
						echo " value=\"".$fet_channel['idtktchannel']."\">".$fet_channel['tktchannelname']."</option>";
						} while ($fet_channel=mysql_fetch_array($res_channel));
						?>
                        </select></td>
                    </tr>
                    <tr>
                        <td width="40%" class="tbl_data"><?php echo $lbl_asterik;?><?php echo $lbl_tktcat;?></td>
                      <td class="tbl_data">
                       <span <?php if (!isset($_POST['tktcat']))
							{ echo "style=\"border:4px solid #ff0000\""; } ?>
                            > 
                        <select name="tktcat" class="small_field" onChange="this.form.submit();">
                        <option value="">---</option>
                        <?php
						$sql_cat="SELECT idtktcategory,tktcategoryname FROM tktcategory INNER JOIN link_tskcategory_wfproc ON tktcategory.idtktcategory=link_tskcategory_wfproc.tktcategory_idtktcategory 
						INNER JOIN wfproc ON link_tskcategory_wfproc.wfproc_idwfproc=wfproc.idwfproc 
						WHERE wfproc.usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']."";
						$res_cat=mysql_query($sql_cat);
						$num_cat=mysql_num_rows($res_cat);
						$fet_cat=mysql_fetch_array($res_cat);
							do {
						?>
                        <option <?php if ( (isset($tktcat)) && ($tktcat==$fet_cat['idtktcategory'])) { echo " selected=\"selected\" "; }  if ((isset($_SESSION['thiscat'])) && ($_SESSION['thiscat']==$fet_cat['idtktcategory']) ) { echo " selected=\"selected\""; } ?> value="<?php echo $fet_cat['idtktcategory'];?>"><?php echo $fet_cat['tktcategoryname'];?></option>
                        <?php
							} while ($fet_cat=mysql_fetch_array($res_cat));
						?>
                        </select>
                        </span>
                        </td>
                    </tr>
                    <tr>
                        <td width="40%" valign="top" class="tbl_data"><?php echo $lbl_asterik;?><?php echo $lbl_ticketnmsg2;?></td>
                      <td class="tbl_data">
                        <textarea cols="20" rows="2" <?php echo $field_status;?> name="txtmsg"><?php if (isset($tktmsg)) { echo $tktmsg; } ?></textarea>
                      </td>
                    </tr>
                     <tr>
                        <td width="40%" class="tbl_data"><?php echo $lbl_asterik;?><?php echo $lbl_timereported;?></td>
                       <td class="tbl_data">
			<input name="tktdate" type="text" class="small_field" id="tktdate" onClick="javascript:show_calendar('document.task.newdeadline', document.task.newdeadline.value);" value="<?php if (isset($tktnewdeadline)) { echo $tktnewdeadline;} else { echo date("d/m/Y H:i",strtotime($timenowis)); }?>" readonly="readonly" />
            <a href="javascript:show_calendar('document.task.newdeadline', document.task.newdeadline.value);">
            <img src="../assets_backend/btns/cal.gif" width="30" align="absmiddle" height="30" border="0" alt="Click Here to Pick up the timestamp"></a>
						</td>
                    </tr>
                    <tr>
                        <td width="40%" class="tbl_data"><?php echo $lbl_deadline_tkt;?></td>
                        <td class="tbl_data"><?php
						echo "AUTO_GENERATED";
						?></td>
                    </tr>
                     <tr>
                        <td width="40%" class="tbl_data"><?php echo $lbl_waterac;?></td>
                        <td class="tbl_data">
                        <input type="text" <?php echo $field_status;?> class="small_field" value="<?php if (isset($tktacno)) { echo $tktacno; } ?>" name="acnumber" maxlength="20" size="20" />
                        </td>
                    </tr>
                    <tr>
                        <td width="40%" class="tbl_data"><?php echo $lbl_kioskno;?></td>
                        <td class="tbl_data">
                        <input type="text" class="small_field" <?php echo $field_status;?> value="<?php if (isset($tktkiosk)) { echo $tktkiosk; } ?>" name="kiosk" maxlength="20" size="20" />
                        </td>
                    </tr>
                    </table>
                  </div>
                <a href="#" style="text-decoration:none" rel="toggle[contacts]" data-openimage="../assets_backend/btns/btn_collapse.gif" data-closedimage="../assets_backend/btns/btn_expand.gif">
                <div class="divcol">
                <img src="../assets_backend/btns/btn_collapse.gif" border="0" align="absmiddle" /> <?php echo $lbl_contactdetails;?>
                </div>
                </a>
                  <div id="contacts" style="background-color:#FFFFFF">
                   <table border="0" width="100%" cellpadding="2" cellspacing="0">
                    <tr>
                        <td width="40%" class="tbl_data"><?php echo $lbl_asterik;?><?php echo $lbl_fname;?></td>
                      <td class="tbl_data"><input <?php echo $field_status;?> name="sendername" type="text" class="small_field" id="sendername" value="<?php if (isset($tktsender)) { echo $tktsender;} ?>" size="25" maxlength="80" /></td>
                    </tr>
                    <tr>
                        <td width="40%" class="tbl_data"><?php echo $lbl_gender;?></td>
                      <td class="tbl_data">
                      <select name="usrgender">
                    <option value="-"  >---</option>
                    <option value="F"  >Female</option>
                    <option value="M"  >Male</option>
                    </select>
                      </td>
                    </tr>
                    <tr>
                        <td width="40%" class="tbl_data"><?php echo $lbl_asterik;?><?php echo $lbl_mobile;?></td>
                      <td class="tbl_data"><input onKeyUp="res(this,numb);"  name="senderphone" type="text" class="small_field" id="senderphone" value="<?php if (isset($tktsenderphone)) { echo $tktsenderphone;} else { echo "2547"; } ?>" size="25" maxlength="12" /></td>
                    </tr>
                    <tr>
                        <td width="40%" class="tbl_data"><?php echo $lbl_email;?></td>
                        <td class="tbl_data"><input  name="senderemail" type="text" class="small_field" id="senderphone" size="25" maxlength="70" /></td>
                    </tr>
                    <tr>
                        <td width="40%" class="tbl_data"><?php echo $lbl_rdstreet;?></td>
                        <td class="tbl_data"><input <?php echo $field_status;?> name="roadstreet" type="text" class="small_field" id="roadstreet" value="<?php if (isset($tktstreet)) { echo $tktstreet; } ?>" size="32" maxlength="80" /></td>
                    </tr>
                    <tr>
                        <td width="40%" class="tbl_data"><?php echo $lbl_bldestate;?></td>
                        <td class="tbl_data"><input <?php echo $field_status;?> name="building" type="text" class="small_field" id="building" value="<?php if (isset($tktbuilding)) { echo $tktbuilding; }?>" size="32" maxlength="80" /></td>
                    </tr>
                     <tr>
                        <td width="40%" class="tbl_data"><?php echo $lbl_unitno;?></td>
                        <td class="tbl_data"><input <?php echo $field_status;?> name="unitnumber" type="text" class="small_field" id="unitnumber" value="<?php if (isset($tktunitno)) { echo $tktunitno; }?>" size="32" maxlength="80" /></td>
                    </tr>
                    <tr>
                        <td width="40%" class="tbl_data"><?php echo $lbl_asterik;?><?php echo $lbl_town_city;?></td>
                      <td class="tbl_data">
                        <input type="text" <?php echo $field_status;?> value="<?php if (isset($tktloc)) { echo $tktloc;} ?>" name="locationtown" id="locationtown" autocomplete="off" maxlength="100" size="32" onKeyUp="lookup(this.value);" class="small_field" />
					<div class="suggestionsBox3" id="suggestions" style="display:none;">
					<div class="suggestionList" id="autoSuggestionsList">&nbsp;</div>
					</div>
                        </td>
                    </tr>

                    </table>
                  </div>
              
			
               	<a href="#" style="text-decoration:none" rel="toggle[feedback]" data-openimage="../assets_backend/btns/btn_collapse.gif" data-closedimage="../assets_backend/btns/btn_expand.gif">
                <div class="divcol">
                <img src="../assets_backend/btns/btn_collapse.gif" border="0" align="absmiddle" /> <?php echo $lbl_feedback_msg;?>
                </div>
                </a>
                	<div id="feedback" style="background-color:#FFFFFF">
                    <div><em><?php echo $lbl_smsmsgtosub;?></em></div>
                        <div>
                        <textarea <?php echo $field_status;?> cols="25" rows="3" name="txtsms" id="txtfeedback"><?php if (isset($tktsms)) { echo $tktsms; } ?></textarea>
                        </div>
                	</div>
              
            </div>
          </td>
          <td valign="top" width="50%" style="padding:5px 10px 0px 5px">
<?php
if (!isset($_POST['tktcat']))
	{ //if they are the same, then it means this is the first record after the ticket
	$ticketisnew=1;
	echo "<div class=\"msg_warning\" style=\"margin:100px 5px; text-align:left\"><strong>Please Select the Ticket <em>Category</em> indicated in Red</strong></div>";
	} else { 
?>	
          <table border="0" width="100%" cellpadding="2" cellspacing="0">
          	<tr>
            	<td colspan="2" class="table_header">
                <?php echo $lbl_taskalloc;?>
                </td>
            </tr>
   	  			<tr>
                    	<td width="25%" class="tbl_data">
                        <strong><?php echo $lbl_from;?></strong>
                        </td>
						<td width="75%" class="tbl_data">
                        <?php echo $_SESSION['MVGitHub_userrole'];?>, <small><?php echo $_SESSION['MVGitHub_usrtitle']." ".$_SESSION['MVGitHub_usrlname'];?></small>
                        </td>
                </tr>
                  <tr>
                    	<td width="25%" class="tbl_data" >
                        <strong><?php echo $lbl_action;?></strong></td>
                        <td class="tbl_data">
		<?php
			$sql_listactions="SELECT  wftskstatustype,idwftskstatustypes,wftskstatustypedesc,idwftskstatus FROM wftskstatustypes
			INNER JOIN wftskstatus ON wftskstatustypes.idwftskstatustypes=wftskstatus.wftskstatustypes_idwftskstatustypes 
			WHERE idwftskstatustypes<3 GROUP BY idwftskstatustypes ORDER BY wftskstatustypes.listorder ASC";
			$res_listactions=mysql_query($sql_listactions);
			$fet_listactions=mysql_fetch_array($res_listactions);
			$num_listactions=mysql_num_rows($res_listactions);
			?>
			<select name="action_to" class="switchaction" id="action_msg" onChange='Choice();' >
            	<option value="0">---</option>
                <?php do { ?>
                <option value="<?php echo $fet_listactions['idwftskstatustypes'];?>"><?php echo $fet_listactions['wftskstatustype'];?></option>
                <?php } while ($fet_listactions=mysql_fetch_array($res_listactions)) ;?>
            </select>
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
            <textarea cols="25" rows="4" name="task_msg_1"><?php if (isset($tkttskmsg1)) { echo $tkttskmsg1; } ?></textarea>
            <?php
             /*               $sBasePath = $_SERVER['PHP_SELF'] ;
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
    		<a href="#" id="button_close" onClick="document.forms['task'].submit()" ></a></td>
        </tr>
	</table>
	</div>
	
	<div class="actionlist 2" style="margin:0; padding:0">
    <table border="0" width="100%" cellpadding="0" cellspacing="0" >
    	<tr>
            <td width="25%" valign="top" class="tbl_data" align="left" >
            <strong><?php echo $lbl_youraction_msg?></strong>            </td>
            <td class="tbl_data" >
            <textarea cols="25" rows="4" name="task_msg_2"><?php if (isset($tkttskmsg2)) { echo $tkttskmsg2; } ?></textarea>
            <?php
             /*               $sBasePath = $_SERVER['PHP_SELF'] ;
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
            <strong><?php echo $lbl_sendto;?></strong>            </td>
            <td class="tbl_data">
            <?php
			//before listing the roles, please confirm that
			//1. the Category in use has a valid workflow
			$sql_wf="SELECT wfproc_idwfproc FROM link_tskcategory_wfproc 
			INNER JOIN wfproc ON link_tskcategory_wfproc.wfproc_idwfproc=wfproc.idwfproc
			WHERE link_tskcategory_wfproc.usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']." AND link_tskcategory_wfproc.tktcategory_idtktcategory=".$tktcat." AND wfproc.wfstatus=1 LIMIT 1";
			$res_wf=mysql_query($sql_wf);
			$num_wf=mysql_num_rows($res_wf);
			$fet_wf=mysql_fetch_array($res_wf);
			
			//2. get the workflow
						$sql_nextwf="SELECT idwftskflow,wfsymbol_idwfsymbol,wfproc_idwfproc FROM wftskflow WHERE wfproc_idwfproc=".$fet_wf['wfproc_idwfproc']." AND listorder>0.00 ORDER BY listorder ASC LIMIT 1";
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
										$sql_userac="SELECT idusrac,usrrolename,idusrrole,usrac.utitle,usrac.lname FROM wfactors
										INNER JOIN usrrole ON wfactors.usrrole_idusrrole=usrrole.idusrrole
										INNER JOIN usrac ON usrrole.idusrrole=usrac.usrrole_idusrrole
										WHERE wfactors.wftskflow_idwftskflow=".$fet_nextwf['idwftskflow']." AND usrac.usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']." AND acstatus=1 ";
										$res_userac=mysql_query($sql_userac);
										$fet_userac=mysql_fetch_array($res_userac);
										$num_userac=mysql_num_rows($res_userac);
										//echo $sql_userac."<br>";

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
										WHERE wftasks.createdon>='".$sevendaysago."' AND wftasks.createdon<='".$timenowis."' AND usrac.usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']." AND link_userrole_usergroup.usrgroup_idusrgroup=".$fet_actors['usrgroup_idusrgroup']." AND acstatus=1 GROUP BY wftasks.usrac_idusrac ORDER BY minutes ASC";
										//echo "test";
										//echo $sql_workdistr."<br>";
										$res_workdistr=mysql_query($sql_workdistr);
										$num_workdistr=mysql_num_rows($res_workdistr);
										$fet_workdistr=mysql_fetch_array($res_workdistr);
											
											
										//check in case the group has not received anything in the last 7 days
										$sql_workdistolder7="SELECT SUM(TIMESTAMPDIFF(MINUTE,timetatstart,timedeadline)) AS minutes, usrac.idusrac, usrac.usrrole_idusrrole,usrac.utitle,usrac.lname,usrrole.usrrolename FROM wftasks 
										INNER JOIN usrrole ON wftasks.usrrole_idusrrole=usrrole.idusrrole 
										INNER JOIN link_userrole_usergroup ON usrrole.idusrrole = link_userrole_usergroup.usrrole_idusrrole
										INNER JOIN usrac ON link_userrole_usergroup.usrrole_idusrrole = usrac.usrrole_idusrrole
										WHERE wftasks.createdon<='".$timenowis."' AND link_userrole_usergroup.usrgroup_idusrgroup=".$fet_actors['usrgroup_idusrgroup']." usrac.usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']." AND acstatus=1 GROUP BY wftasks.usrac_idusrac ORDER BY minutes ASC";
										//echo "test";
										//echo $sql_workdistolder7."<br>";
										$res_workdistolder7=mysql_query($sql_workdistolder7);
										$num_workdistolder7=mysql_num_rows($res_workdistolder7);
										$fet_workdistolder7=mysql_fetch_array($res_workdistolder7);	
										
											
										//check also for any new user who perhaps has never received a task - new user
										$sql_newuser="SELECT idusrac, usrac.usrrole_idusrrole, usrrole.usrrolename,usrac.utitle,usrac.lname
										FROM link_userrole_usergroup
										INNER JOIN usrrole ON link_userrole_usergroup.usrrole_idusrrole = usrrole.idusrrole
										INNER JOIN usrac ON usrrole.idusrrole=usrac.usrrole_idusrrole
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
								
							//	echo $sql_newuser;
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
			
			
			
			
					/*
						//confirm what the next workflow id is for this task
						$sql_role="SELECT idusrrole,idusrac,usrrolename,utitle,lname FROM usrrole
						INNER JOIN usrac ON usrrole.idusrrole=usrac.usrrole_idusrrole
						INNER JOIN usrteamzone ON usrrole.usrteamzone_idusrteamzone=usrteamzone.idusrteamzone
						WHERE (usrteamzone.idusrteamzone=".$_SESSION['MVGitHub_userteamzoneid']." OR usrteamzone.usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam'].") AND idusrrole!=".$_SESSION['MVGitHub_iduserrole']."  ORDER BY usrrolename ASC";
						$res_role=mysql_query($sql_role);
						$fet_role=mysql_fetch_array($res_role);
						$num_role=mysql_num_rows($res_role);

								
						echo "<select name=\"assign_to_2\" >";
						echo "<option value=\"\">---</option>";
						do {
								
						echo "<option value=\"".$fet_role['idusrrole']."\" title=\"".$fet_role['utitle']." ".$fet_role['lname']." - ".$fet_role['usrrolename']."\">".substr($fet_role['usrrolename'],0,30)." </option>";
						} while ($fet_role=mysql_fetch_array($res_role));
						echo "</select>";
						*/	
						?>          </td>
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
            <textarea cols="25" rows="4" name="task_msg_3"><?php if (isset($tkttskmsg3)) { echo $tkttskmsg3; } ?></textarea>
            <?php
             /*               $sBasePath = $_SERVER['PHP_SELF'] ;
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
            <textarea cols="25" rows="4" name="task_msg_4"><?php if (isset($tkttskmsg4)) { echo $tkttskmsg4; } ?></textarea>
            <?php
            /*                $sBasePath = $_SERVER['PHP_SELF'] ;
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
            <textarea cols="25" rows="4" name="task_msg_5"><?php if (isset($tkttskmsg5)) { echo $tkttskmsg5; } ?></textarea>
            <?php
                     /*       $sBasePath = $_SERVER['PHP_SELF'] ;
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
						echo "<option ";
						if ( (isset($tktasito5)) && ($tktasito5==$fet_role['idusrrole']) ) { echo " selected=\"selected\" "; }
						echo " value=\"".$fet_role['idusrrole']."\">".$fet_role['usrrolename']." (".$fet_role['utitle']." ".$fet_role['lname'].")</option>";
						} while ($fet_role=mysql_fetch_array($res_role));
						echo "</select>";	
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
    
     <div class="actionlist 6" style="margin:0; padding:0">
    <table border="0" width="100%" cellpadding="0" cellspacing="0" >
    	<tr>
            <td width="25%" valign="top" class="tbl_data" align="left" >
            <strong><?php echo $lbl_update_msg;?></strong>            </td>
            <td class="tbl_data" >
            <textarea cols="25" rows="4" name="task_msg_6"><?php if (isset($tkttskmsg6)) { echo $tkttskmsg6; } ?></textarea>
            <?php
                           /* $sBasePath = $_SERVER['PHP_SELF'] ;
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
<?php
}
?>    
	</div>
</div>		</td>
	</tr>
</table>
            
            
          </td>
        </tr>
    </table>
	</div>
  </form>

</div>  
</body>
</html>
