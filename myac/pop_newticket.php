<?php
require_once('../assets_backend/be_includes/config.php');

//require_once('../Connections/connSystem.php');
//mysql_select_db($database_connSystem, $connSystem);

require_once('../assets_backend/be_includes/check_login.php');

//include("fckeditor/fckeditor.php");

//echo "<br><br><br><bR>";
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
	
if (isset($_POST['exists_pub']))
	{
	$public_exists=preg_replace('/[^a-z\-_0-9\.:\/\s]/i','',mysql_escape_string(trim($_POST['exists_pub'])));
	}	

if (isset($_POST['tktcat']))
	{
	$_SESSION['thiscat']=preg_replace('/[^a-z\-_0-9\.:\/\s]/i','',mysql_escape_string(trim($_POST['tktcat'])));
	}
//process task
//form action
if ( (isset($_POST['formaction'])) && ($_POST['formaction']=="process_task") )
	{
	//echo "niko hapa";
	//now, sanitize the inputs
		$tktno=preg_replace('/[^a-z\-_0-9\.:\/]/i','',mysql_escape_string(trim($_POST['tktnumber'])));
		$tktcat=preg_replace('/[^a-z\-_0-9\.:\/]/i','',mysql_escape_string(trim($_POST['tktcat'])));
		$tktacno=preg_replace('/[^a-z\-_0-9\.:\/]/i','',mysql_escape_string(trim($_POST['acnumber'])));
		$tktkiosk=preg_replace('/[^a-z\-_0-9\.:\/]/i','',mysql_escape_string(trim($_POST['kiosk'])));
		$tktsender=preg_replace('/[^a-z\-_0-9\.:\/\s]/i','',mysql_escape_string(trim($_POST['sendername'])));
		if (isset($_POST['billno'])) { $_SESSION['num_rec_rcm']=mysql_escape_string(trim($_POST['billno'])); }//store this in a session
		
		//construct the phone number
		$suff_tktsenderphone=preg_replace('/[^a-z\-_0-9\.:\/]/i','',mysql_escape_string(trim($_POST['senderphone'])));
		$pref_tktsenderphone=preg_replace('/[^a-z\-_0-9\.:\/]/i','',mysql_escape_string(trim($_POST['pref_senderphone'])));
		$tktsenderphone=$pref_tktsenderphone.substr($suff_tktsenderphone,1,9);

		$tktstreet=preg_replace('/[^a-z\-_0-9\.:\/\s]/i','',mysql_escape_string(trim($_POST['roadstreet'])));
		$tktbuilding=preg_replace('/[^a-z\-_0-9\.:\/\s]/i','',mysql_escape_string(trim($_POST['building'])));
		$tktunitno=preg_replace('/[^a-z\-_0-9\.:\/\s]/i','',mysql_escape_string(trim($_POST['unitnumber'])));
		$tktloc=mysql_escape_string(trim($_POST['locationtown']));
		$tktmsg=preg_replace('/[^a-z\-_0-9\.:\/\s]/i','',mysql_escape_string(strip_tags(trim($_POST['txtmsg']))));
		$tkttype=preg_replace('/[^a-z\-_0-9\.:\/]/i','',mysql_escape_string(strip_tags(trim($_POST['tkttype']))));
		$tktchannel=preg_replace('/[^a-z\-_0-9\.:\/]/i','',mysql_escape_string(strip_tags(trim($_POST['tktchannel']))));
		$tktaction=preg_replace('/[^a-z\-_0-9\.:\/]/i','',mysql_escape_string(trim($_POST['action_to'])));
		$updateperm=preg_replace('/[^a-z\-_0-9\.:\/]/i','',mysql_escape_string(trim($_POST['up'])));
		$usrgender=mysql_escape_string($_POST['usrgender']);
		$directions=mysql_escape_string($_POST['plandmark']);
		$prev_tktnumber= preg_replace('/[^a-z\-_0-9\.:\/]/i','',mysql_escape_string(strip_tags(trim($_POST['prev_tktnumber']))));
		//THE REGION (the region may not necessarily be the reporters' region hence check what region is selected by the creator
		$to_region=trim($_POST['assign_to_region']);

			
		//clean up optional fields
		if (isset($_POST['close_1']))
			{
			$tkticonfirm=preg_replace('/[^a-z\-_0-9\.:\/]/i','',mysql_escape_string(trim($_POST['close_1'])));
			}
		if (isset($_POST['task_msg_1']))
			{
			$tkttskmsg1=preg_replace('/[^a-z\-_0-9\.:\/\s]/i','',mysql_escape_string(strip_tags(trim($_POST['task_msg_1']))));
			$_SESSION['tkttskmsg1']=$tkttskmsg1;
			}
		if (isset($_POST['task_msg_2']))
			{
			$tkttskmsg2=preg_replace('/[^a-z\-_0-9\.:\/\s]/i','',mysql_escape_string(strip_tags(trim($_POST['task_msg_2']))));
			$_SESSION['tkttskmsg2']=$tkttskmsg2;
			}
		if (isset($_POST['task_msg_3']))
			{
			$tkttskmsg3=preg_replace('/[^a-z\-_0-9\.:\/\s]/i','',mysql_escape_string(strip_tags(trim($_POST['task_msg_3']))));
			$_SESSION['tkttskmsg3']=$tkttskmsg3;
			}
		if (isset($_POST['task_msg_4']))
			{
			$tkttskmsg4=preg_replace('/[^a-z\-_0-9\.:\/\s]/i','',mysql_escape_string(strip_tags(trim($_POST['task_msg_4']))));
			$_SESSION['tkttskmsg4']=$tkttskmsg4;
			}
		if (isset($_POST['task_msg_5']))
			{
			$tkttskmsg5=preg_replace('/[^a-z\-_0-9\.:\/\s]/i','',mysql_escape_string(strip_tags(trim($_POST['task_msg_5']))));
			$_SESSION['tkttskmsg5']=$tkttskmsg5;
			}
		if (isset($_POST['task_msg_6']))
			{
			$tkttskmsg6=preg_replace('/[^a-z\-_0-9\.:\/\s]/i','',mysql_escape_string(strip_tags(trim($_POST['task_msg_6']))));
			$_SESSION['tkttskmsg6']=$tkttskmsg6;
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
			$error_1="<div class=\"msg_warning_small\">".$msg_warning_nocategory."</div>";
			}
		if (strlen($tktloc) < 1)
			{
			$error_2="<div class=\"msg_warning_small\">".$msg_warning_location."</div>";
			}
		
		if (isset($_POST['subcat_exists'])) //if a subcategory exists
			{
				//then check for the subcategory details
				$sub_cat=mysql_escape_string(trim($_POST['subcat']));
				$_SESSION['sub_cat']=$sub_cat;
				//
				if (strlen($sub_cat)<5)
					{
					$error_1b="<div class=\"msg_warning_small\">Please Specify CMS Category </div>";
					} 			
			}
		
		if ( ($tkttype==1) && (strlen($tktacno) < 3) )
			{
			$error_acno="<div class=\"msg_warning_small\">Please enter the Account Number </div>";
			}
	
	
		if (!isset($error_2))
			{	
			$sql_confirmloc="SELECT idloctowns,locationname FROM loctowns WHERE locationname='".$tktloc."' LIMIT 1";
			$res_confirmloc=mysql_query($sql_confirmloc);
			$num_confirmloc=mysql_num_rows($res_confirmloc);
			$fet_confirmloc=mysql_fetch_array($res_confirmloc);
			
			if ($num_confirmloc > 0) //if there is a location then
				{
				
				$location_id=$fet_confirmloc['idloctowns'];
				
				} else {
				//add new location
				$sql_newloc="INSERT INTO loctowns (loccountry_idloccountry,locationname,lng,lat,createdby,createdon,is_valid,is_town)
				VALUES ('1','".$tktloc."','0','0','".$_SESSION['MVGitHub_idacname']."','".$timenowis."','1','0')";
				mysql_query($sql_newloc);
				
				//retreive that number
				$sql_idloc="SELECT idloctowns,locationname FROM loctowns WHERE createdby=".$_SESSION['MVGitHub_idacname']." ORDER BY idloctowns DESC LIMIT 1"; 
				$res_idloc=mysql_query($sql_idloc);
				$fet_idloc=mysql_fetch_array($res_idloc);
				
				$location_id=$fet_idloc['idloctowns']; //thats the new location
				
				//just send email to support to map the new address
				
				
				//in such a case, the new location does not have the coordinates. Therefore, alert ICT team to add the new coordinates
				//the ict teams must be of that region
				//1. check if this region has a ICT_SUPPORT_MV role 
/*				$sql_checksupport="SELECT idusrrole,wfactors.wftskflow_idwftskflow,idusrac FROM usrrole 
				INNER JOIN wfactors ON usrrole.idusrrole=wfactors.usrrole_idusrrole
				INNER JOIN wftskflow ON wfactors.wftskflow_idwftskflow=wftskflow.idwftskflow
				INNER JOIN wfproc ON wftskflow.wfproc_idwfproc=wfproc.idwfproc
				INNER JOIN usrac ON usrrole.idusrrole=usrac.usrrole_idusrrole
				WHERE usrrole.usrrolename='ICT_SUPPORT_MV' AND wfproc.wftskflowname='ICT_SUPPORT_MV' AND usrrole.usrteamzone_idusrteamzone=".$_SESSION['MVGitHub_userteamzoneid']." LIMIT 1";
				$res_checksupport=mysql_query($sql_checksupport);
				$num_checksupport=mysql_num_rows($res_checksupport);
				$fet_checksupport=mysql_fetch_array($res_checksupport);
				
				if ($num_checksupport > 0)
					{
					//2. then create a task for that role 
					$sql_gentracx="INSERT INTO wftaskstrac (createdon,createdby) VALUES ('".$timenowis."','".$_SESSION['MVGitHub_idacname']."')";
					mysql_query($sql_gentracx);

					$sql_tracx="SELECT idwftaskstrac FROM wftaskstrac WHERE createdby=".$_SESSION['MVGitHub_idacname']." ORDER BY idwftaskstrac DESC LIMIT 1";
					$res_tracx=mysql_query($sql_tracx);
					$fet_tracx=mysql_fetch_array($res_tracx);

															
					//insert new task for the recepeint
					$sql_new_taskx="INSERT INTO wftasks (wftaskstrac_idwftaskstrac,usrrole_idusrrole,wftasks_idwftasks,wftskflow_idwftskflow,tktin_idtktin,usrac_idusrac,wftskstatustypes_idwftskstatustypes,wftskstatusglobal_idwftskstatusglobal,tasksubject,taskdesc,timeinactual,timeoveralldeadline,timetatstart,timedeadline,timeactiontaken,sender_idusrrole,sender_idusrac,createdon,createdby) 
					VALUES ('".$fet_tracx['idwftaskstrac']."','".$fet_checksupport['iduserrole']."','0','".$fet_checksupport['wftskflow_idwftskflow']."','".$fet_tktid['idtktinPK']."','".$_SESSION['MVGitHub_idacname']."','1','3','".$fet_tktid['tktcategoryname']." - ".$ticketref."','[MANUAL ENTRY]".$tktmsg."','".$tktdate_fin."','".$deadline."','".$task_starttime_final."','".$task_deadline_final."','".$timenowis."','2','2','".$timenowis."','".$_SESSION['MVGitHub_idacname']."')";
					mysql_query($sql_new_taskx);
						
					//3. initiate the notifications as well for them to do the job of validating the new address
					
					}
*/
				}
			}
			
		if ($tktaction < 1)
			{
			$error_4="<div class=\"msg_warning_small\">".$msg_select_action."</div>";
			}
		if ( (isset($tktsendermail)) && (strlen($tktsendermail)>1) )
			{
			if ( (strlen($tktsendermail) > 5) && (!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $tktsendermail)) )
				{
				$error_5="<div class=\"msg_warning_small\">".$msg_warning_emailinv."</div>";
				}
			}
		if ( (isset($tktsendermail)) && (strlen($tktsendermail)>1) && (strlen($tktsendermail) < 6) )
			{
			$error_6 = "<div class=\"msg_warning_small\">".$msg_warning_emailinv."</div>";
			}
		
		if (strlen($tktmsg) < 5)
			{
			$error_7 = "<div class=\"msg_warning_small\">".$msg_warn_customermsg."</div>";
			}

		if ($tkttype < 1)
			{
			$error_8="<div class=\"msg_warning_small\">".$msg_warn_tkttype."</div>";
			}
		if ($tktchannel < 1)
			{
			$error_9="<div class=\"msg_warning_small\">".$msg_warn_tktchn."</div>";
			}
		if (strlen($tktdate_fin)<9)
			{
			$error_10="<div class=\"msg_warning_small\">".$msg_warn_newdl."</div>";
			}
		if (strlen($tktsender)<1)
			{
			$error_11="<div class=\"msg_warning_small\">".$msg_warning_fname."</div>";
			}
		if  ((strlen($tktsenderphone)!=12) && (($tktchannel==5) || ($tktchannel==6)) )
			{
			$error_12="<div class=\"msg_warning_small\">".$msg_warn_phone."</div>";
			}
	
		//first, check if there is a record by this user to this company (userteam in broad)
		if (!isset($record_exists) )
			{
			//run this query only where it is not excempted
			if ($tktacno!="")
				{
				$tktacno_filter=" OR waterac='".$tktacno."' ";
				} else {
				$tktacno_filter="";
				}
			
			$sql_exists="SELECT sendername,senderemail,refnumber FROM tktin WHERE (senderphone='".$tktsenderphone."' ".$tktacno_filter.") AND usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']." AND timereported>='".$fifteen_months_ago."' LIMIT 1";
			$res_exists=mysql_query($sql_exists);
			$num_exists=mysql_num_rows($res_exists);
			$fet_exists=mysql_fetch_array($res_exists);
			
			if ($num_exists > 0 ) //if record is more than 0
				{
				$form_app="

				<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"margin:10px; padding:10px\">
					<tr>
						<td style=\"background-color:#FFFFCC; color:#ff0000;padding:5px 10px 5px 10px;font-size:12px;font-family:arial,verdana; line-height:150%; font-weight:bold; margin:10px;text-align:left;border-top: 1px solid #FF0000;border-bottom: 1px solid #FF0000;\" ><img style=\"margin:0px 5px\" src=\"../assets_backend/icons/warning.gif\" border=\"0\" align=\"absmiddle\" /> Wait! We seem to have a Similar Ticket
						&nbsp;&nbsp;|&nbsp;&nbsp;<a href=\"pop_newticket_duplicate.php?sentby=".$tktsenderphone."&amp;ac=".$tktacno."&amp;tabview=1&amp;team_member=9&keepThis=true&TB_iframe=true&height=520&width=910&inlineId=hiddenModalContent&modal=true\" class=\"thickbox\"  title=\"View in a New Window\"><img src=\"../assets_backend/icons/icon_newwin.gif\" border=\"0\" align=\"absmiddle\" /> Click to View</a></span></td>
						</tr>
						<tr>
						<td style=\"padding:10px;background-color:#FFFFFF;style=\"font-size:12px;font-family:arial,verdana; \" >
						<div style=\"background-image:url(../assets_backend/btns/bg_proceed.png); width:240px; background-repeat:no-repeat; padding:8px 5px 15px 5px;\" >
						<a href=\"#\" >
						<label for=\"1\"><input style=\"font-size:30px\" onClick=\"getwkflow(this.value)\" type=\"checkbox\" value=\"".$tktcat."\" name=\"exists_rec\" id=\"1\"><strong> Just Proceed! Ignore this warning</strong></lable>
						</a>
						</div>
						</td>
					</tr>
				</table>";
				//
				$error_exists=1;
				}
			}
	
	
			//before generating the new ticket, first check if it is a public ticket already in the system
			if ($tkttype==2) //if it is public, then 
				{
				if (!isset($record_exists) )
					{
					//check if there is a similar ticket in the system not closed with 
					//same location + same category + public ticket + not closed
					$sql_pubsim="SELECT idtktinPK FROM tktin WHERE loctowns_idloctowns=".$location_id." AND tktcategory_idtktcategory=".$tktcat." AND tktstatus_idtktstatus<4";
					$res_pubsim=mysql_query($sql_pubsim);
					$num_pubsim=mysql_num_rows($res_pubsim);
					$fet_pubsim=mysql_fetch_array($res_pubsim);
					
					if ($num_pubsim > 0)//if a public ticket found
						{
						$pub_similar_exists=1;
						
						} //close if public ticket is found
					}
				
				} //close if pub
	
	//if (isset($_POST['step']))
	if (!isset($error_4))
		{ 
		//then, depending on the selection of the action, validate
		if ( strlen($tkttskmsg2) <1 )
			{
			$error_13="<div class=\"msg_warning_small\">Please enter your 'Task Message'</div>";
			}
				
		 if (  (!isset($tktasito2)) ||  ( (isset($tktasito2)) && ($tktasito2<1) ) ) 
			{
			$error_14="<div class=\"msg_warning_small\">Please Select the Recepient on 'Send To'</div>";
			}  
		
		}
		
	

	//if no error, then go ahead to create this ticket
	if ( (!isset($error_1)) && (!isset($error_2)) && (!isset($error_4))  && (!isset($error_5))  && (!isset($error_6))  && (!isset($error_7))  && (!isset($error_8))  && (!isset($error_9))  && (!isset($error_10))  && (!isset($error_11))  && (!isset($error_12)) && (!isset($error_13)) && (!isset($error_14)) && (!isset($error_exists)) && (!isset($error_nostep)) && (!isset($error_1b)) && (!isset($error_acno)) )
			{	
			
			$no_error=1;
			
			//first, retrieve the total turn around time for this task to calculate it's deadline
			$sql_tat="SELECT idwfproc,wfproctat,tktcategoryname FROM wfproc 
			INNER JOIN link_tskcategory_wfproc ON wfproc.idwfproc=link_tskcategory_wfproc.wfproc_idwfproc 
			INNER JOIN tktcategory ON link_tskcategory_wfproc.tktcategory_idtktcategory=tktcategory.idtktcategory
			WHERE link_tskcategory_wfproc.tktcategory_idtktcategory=".$tktcat." AND link_tskcategory_wfproc.usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']." LIMIT 1";
			$res_tat=mysql_query($sql_tat);
			$fet_tat=mysql_fetch_array($res_tat);
			
			//the first workflow of list order 00:000
			$sql_proc="SELECT idwfproc,idwftskflow,link_tskcategory_wfproc.wfproc_idwfproc,idwftskflow,wfproctat,wfproc.usrteam_idusrteam FROM link_tskcategory_wfproc
			INNER JOIN wfproc ON link_tskcategory_wfproc.wfproc_idwfproc=wfproc.idwfproc 
			INNER JOIN wftskflow ON wfproc.idwfproc=wftskflow.wfproc_idwfproc
			WHERE link_tskcategory_wfproc.tktcategory_idtktcategory=".$tktcat." AND wftskflow.listorder='0.00' AND wfsymbol_idwfsymbol=1 AND wfproc.wfstatus=1 LIMIT 1";
					
			$res_proc=mysql_query($sql_proc);
			$num_proc=mysql_num_rows($res_proc);
			$fet_proc=mysql_fetch_array($res_proc);

			///generate the workflow id for the task for the customer care 
			$sql_nextstep="SELECT idwftskflow FROM wftskflow WHERE listorder >'0.00' AND wfproc_idwfproc=".$fet_proc['idwfproc']." ORDER BY idwftskflow ASC LIMIT 1";
			$res_nextstep=mysql_query($sql_nextstep);
			$fet_nextstep=mysql_fetch_array($res_nextstep);

			$deadline=date("Y-m-d H:i:s",strtotime(date("Y-m-d H:i:s",strtotime($tktdate_fin))) + $fet_tat['wfproctat']);
			
			//determine closure date
			if ($tktaction==1)
				{
				$dateclose=$timenowis;
				} else {
				$dateclose="0000-00-00 00:00:00";
				}
			

			//exit;
		//	$_SESSION['debug_3']=$sql_tktid."<br>";
		//	$_SESSION['debug_4']=$_SESSION['idtktintrans']."<br>";
			//THEN THIS TASK CREATES A NEW AUTO TASK RECORD
			////////////// START CALCULATION OF TIME /////////
												
						$sql_nextwf="SELECT idwftskflow,wftskflow.wfsymbol_idwfsymbol as wfsymbol,wfactors.usrrole_idusrrole as usrrole,wfactors.usrgroup_idusrgroup as usrgroup,wftsktat,expubholidays
						FROM wftskflow INNER JOIN wfactors ON wftskflow.idwftskflow=wfactors.wftskflow_idwftskflow 
						WHERE wfproc_idwfproc=".$fet_tat['idwfproc']." AND wftskflow.wfsymbol_idwfsymbol=2 AND wftskflow.listorder>'0.00' ORDER BY listorder ASC LIMIT 1";
							
						//echo $sql_nextwf."<br>";
						//exit;
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
						
						//////////// BEGIN TRANSACTION WITH DB HERE /////////
					
						//$query = "BEGIN";
//echo "So Far Here..";
						mysql_query("BEGIN");
						
						//generate ticket number
						$sql_reg="SELECT idusrteamzone,region_pref FROM usrteamzone WHERE idusrteamzone=".$to_region."";
						$query_1=mysql_query($sql_reg);
						$fet_reg=mysql_fetch_array($query_1);
						
						//check the last ticket category for this region($_SESSION['MVGitHub_regionpref']) + 1
						//combine with the date
						//$sql_ticketlast="SELECT idtktinPK,refnumber FROM tktin WHERE refnumber REGEXP BINARY '^2' ORDER BY idtktinPK DESC LIMIT 1";
						$sql_ticketlast="SELECT count(*) as tkts_in,category_pref FROM tktin 
						INNER JOIN tktcategory ON tktin.tktcategory_idtktcategory=tktcategory.idtktcategory 
						WHERE tktin.usrteamzone_idusrteamzone=".$fet_reg['idusrteamzone']." 
						AND tktin.tktcategory_idtktcategory=".$tktcat." 
						AND date(timereported)='".$today."'";
						$query_2=mysql_query($sql_ticketlast);
						$fet_ticketlast=mysql_fetch_array($query_2);			
						
						$tktin_number=($fet_ticketlast['tkts_in']+1);
						
						//$_SESSION['debug0']=$sql_ticketlast."<br>";
						
			//			echo "result from query+1=> ".$tktin_number."<br><br>";
						//determine the ticket category	suffix	
								
						if ($tktin_number > 0)
							{
							
							$precat_tkt=$fet_ticketlast['category_pref']; //
							
							} else { //else query
							$sql_prefcat="SELECT category_pref FROM tktcategory WHERE idtktcategory=".$tktcat."";
							$query_3=mysql_query($sql_prefcat);
							$fet_prefcat=mysql_fetch_array($query_3);
							
							$precat_tkt=$fet_prefcat['category_pref']; //
							
						}
							
						//The DATE
						$date_tkt=date("ymd",strtotime($today)); //change from YYYY-MM-DD to YY-MM-DD and remove the dashes
					
							
						//construct the ticket
						//2SF130604WE1
						//[2] is the company id
						//[SF] is the category prefix_tkt	
						
						//NEW TICKET NUMBER
						$ticketref=$_SESSION['MVGitHub_idacteam'].$precat_tkt.$date_tkt.$fet_reg['region_pref'].$tktin_number;
						
						//generate a trac number for this ticket
						$sql_gentrac="INSERT INTO wftaskstrac (createdon,createdby) VALUES ('".$timenowis."','".$_SESSION['MVGitHub_idacname']."')";
						$query_3=mysql_query($sql_gentrac);

						$sql_trac="SELECT idwftaskstrac FROM wftaskstrac WHERE createdby=".$_SESSION['MVGitHub_idacname']." ORDER BY idwftaskstrac DESC LIMIT 1";
						$query_4=mysql_query($sql_trac);
						$fet_trac=mysql_fetch_array($query_4);


							
						//insert new ticket
						$sql_newtkt="INSERT INTO tktin (tktlang_idtktlang, usrteamzone_idusrteamzone, usrteam_idusrteam, tktgroup_idtktgroup, tktchannel_idtktchannel, tktstatus_idtktstatus, tktcategory_idtktcategory, tkttype_idtkttype, sendername,sendergender, senderemail, senderphone, refnumber, tktdesc, timereported, timedeadline, timeclosed, city_town, loctowns_idloctowns, road_street, building_estate, unitno, waterac, kioskno, usrsession, createdby, createdon,landmark,refnumber_prev,wftaskstrac_idwftaskstrac) 
						VALUES ( '1','".$to_region."', '".$_SESSION['MVGitHub_idacteam']."', '0', '".$tktchannel."', '1', '".$tktcat."', '".$tkttype."', '".$tktsender."','".$usrgender."', '".$tktsendermail."', '".$tktsenderphone."', '".$ticketref."', '".$tktmsg."', '".$tktdate_fin."','".$deadline."', '0000-00-00 00:00:00', '".$tktloc."', '".$location_id."', '".$tktstreet."', '".$tktbuilding."', '".$tktunitno."', '".$tktacno."', '".$tktkiosk."', '".session_id()."', '".$_SESSION['MVGitHub_idacname']."', '".$timenowis."','".$directions."','".$prev_tktnumber."','".$fet_trac['idwftaskstrac']."')";
						$query_5=mysql_query($sql_newtkt);	
						//echo "<br><br><br><br><br>".$sql_newtkt;					
								//retrieve the ticket number
								$sql_tktid="SELECT idtktinPK,refnumber,tktcategoryname FROM tktin 
								INNER JOIN tktcategory ON tktin.tktcategory_idtktcategory=tktcategory.idtktcategory
								WHERE tktin.createdby=".$_SESSION['MVGitHub_idacname']." AND wftaskstrac_idwftaskstrac=".$fet_trac['idwftaskstrac']." LIMIT 1";
								$query_6=mysql_query($sql_tktid);
								$fet_tktid=mysql_fetch_array($query_6);
								
								//echo $sql_tktid;
								$_SESSION['idtktintrans']=$fet_tktid['idtktinPK'];
								
								
								//////////////////////debugging
								$val=addslashes($sql_newtkt);
								
						/*		$dbug="INSERT INTO z_dbq (query,result,result2,src,tktnumber,customer_name,customer_number,complaint_details,region,addedby) 
								VALUES ('".$val."','".$fet_tktid['idtktinPK']."','".$_SESSION['idtktintrans']."','".$_SESSION['MVGitHub_idacname']."','".$ticketref."','".$tktsender."','".$tktsenderphone."','".$tktmsg."','".$to_region."','".$_SESSION['MVGitHub_acname']."') ";
								mysql_query($dbug);
								
								$sql_debug="SELECT id FROM z_dbq WHERE addedby='".$_SESSION['MVGitHub_acname']."' ORDER BY id desc";
								$res_debug=mysql_query($sql_debug);
								$fet_debug=mysql_fetch_array($res_debug);
							*/	
		/////////////////////////
		//echo $dbug;	
								//														
																						
								//insert new task for this step
								if ($fet_trac['idwftaskstrac'] > 0)
									{
									$sql_new_task="INSERT INTO wftasks (wftaskstrac_idwftaskstrac,usrrole_idusrrole,wftasks_idwftasks,wftskflow_idwftskflow,tktin_idtktin,usrac_idusrac,wftskstatustypes_idwftskstatustypes,wftskstatusglobal_idwftskstatusglobal,tasksubject,taskdesc,timeinactual,timeoveralldeadline,timetatstart,timedeadline,timeactiontaken,sender_idusrrole,sender_idusrac,createdon,createdby) 
									VALUES ('".$fet_trac['idwftaskstrac']."','".$_SESSION['MVGitHub_iduserrole']."','0','".$fet_proc['idwftskflow']."','".$fet_tktid['idtktinPK']."','".$_SESSION['MVGitHub_idacname']."','1','3','".$fet_tktid['tktcategoryname']." - ".$ticketref."','[MANUAL ENTRY]".$tktmsg."','".$tktdate_fin."','".$deadline."','".$task_starttime_final."','".$task_deadline_final."','".$timenowis."','2','2','".$timenowis."','".$_SESSION['MVGitHub_idacname']."')";
									$query_8=mysql_query($sql_new_task);

									}
								//echo "<span >1 ".$sql_new_task."</span><br><br>";
								//retreive
								$sql_idtask="SELECT idwftasks,wftaskstrac_idwftaskstrac,usrrole_idusrrole FROM wftasks WHERE createdby=".$_SESSION['MVGitHub_idacname']." ORDER BY idwftasks DESC LIMIT 1";
								$query_9=mysql_query($sql_idtask);
								$fet_idtask=mysql_fetch_array($query_9);
								
								//echo $sql_idtask."<br>";
								
								$_SESSION['wtaskid']=$fet_idtask['idwftasks'];
						
						
						///////////////END MAIN TRANSACTION WITH DB HERE


			//COMPOSE THE SMS FEEDBACK HERE
			//create the message body depending on the action chosen $tktaction
					if ( ($tktaction==1) || ($tktaction==2) ) //if it is pass it on or close task, then retrieve the message
						{
						//get the appropriate message from the db for either of the actiontypes above
					/*	$sql_appmsg="SELECT wftskstatustypedesc FROM wftskstatustypes WHERE idwftskstatustypes=".$tktaction."";
						$res_appmsg=mysql_query($sql_appmsg);
						$fet_appmsg=mysql_fetch_array($res_appmsg);
					*/	
						//prep the ticket sms message depending on the //the category selected	//and the action taken
						$sql_tktcategory="SELECT tktcategoryname FROM tktcategory WHERE idtktcategory=".$tktcat." LIMIT 1";
						$res_tktcategory=mysql_query($sql_tktcategory);
						$fet_tktcategory=mysql_fetch_array($res_tktcategory);
						
						//message feedback from the workflow step 1 (0.00)
						$sql_msg="SELECT tktfeedback.feedbacksms FROM tktfeedback
						INNER JOIN wftskflow ON tktfeedback.wftskflow_idwftskflow=wftskflow.idwftskflow 
						INNER JOIN link_tskcategory_wfproc ON wftskflow.wfproc_idwfproc=link_tskcategory_wfproc.wfproc_idwfproc
						WHERE wftskflow.listorder='0.00' AND link_tskcategory_wfproc.tktcategory_idtktcategory=".$tktcat." LIMIT 1";
						$res_msg=mysql_query($sql_msg);
						$fet_msg=mysql_fetch_array($res_msg);
					//	echo $sql_msg;
						$tktsms_pref=$_SESSION['MVGitHub_userteamshortname']." [Ticket ".$ticketref."-".$fet_tktcategory['tktcategoryname']."] ";						
					
						$tktsms=$tktsms_pref." ".$fet_msg['feedbacksms'];
						}
						
						
//FINALLY, THE APPROPRIATE ACTION CHOSEN BY THE USER				
///////////////  ACTION 2  ///////////////////////////////////////////////////////////////////////////////////////////////////
				
				if ($tktaction==2) { //Select Task Action 2 ie: pass it on

					//validate
					if (strlen($tkttskmsg2) < 1)
						{
						$error_2_1="<div class=\"msg_warning_small\">".$msg_warn_msgmis."</div>";
						}
					if ($tktasito2<1)
						{
						$error_2_2="<div class=\"msg_warning_small\">".$msg_warn_assign."</div>";
						}
						
					if ( (!isset($error_2_1)) && (!isset($error_2_2)) )//if the no error 
						{
						
						//update this task 
//						$sql_update_task="UPDATE wftasks SET wftskstatustypes_idwftskstatustypes='2',wftskstatusglobal_idwftskstatusglobal='2',sender_idusrrole='".$_SESSION['MVGitHub_iduserrole']."',sender_idusrac='".$_SESSION['MVGitHub_idacname']."',timeactiontaken='".$timenowis."'  WHERE idwftasks=".$_SESSION['wtaskid']." LIMIT 1";
						$sql_update_task="UPDATE wftasks SET wftskstatustypes_idwftskstatustypes='2',wftskstatusglobal_idwftskstatusglobal='2',timeactiontaken='".$timenowis."'  WHERE idwftasks=".$_SESSION['wtaskid']." LIMIT 1";
						$query_a=mysql_query($sql_update_task);
						
						//create an update message on the record
						if ($fet_idtask['wftaskstrac_idwftaskstrac']>0)
							{
							$sql_update_msg="INSERT INTO wftskupdates (wftaskstrac_idwftaskstrac,usrrole_idusrrole,usrac_idusrac,wftskstatusglobal_idwftskstatusglobal,wftskstatustypes_idwftskstatustypes,wftasks_idwftasks,wftskupdate,createdby,createdon) 
							VALUES ('".$fet_idtask['wftaskstrac_idwftaskstrac']."','".$_SESSION['MVGitHub_iduserrole']."','".$_SESSION['MVGitHub_idacname']."','2','2','".$_SESSION['wtaskid']."','".$tkttskmsg2."','".$_SESSION['MVGitHub_idacname']."','".$timenowis."')";
							$query_b=mysql_query($sql_update_msg);
							}
						
						//get task details
						$sql_task_details = "SELECT wftasks.wftaskstrac_idwftaskstrac,wftasks.idwftasks,wftasks.usrrole_idusrrole,wftasks.wftasks_idwftasks,wftasks.wftskflow_idwftskflow,wftskflow.wfproc_idwfproc,wftasks.tktin_idtktin,wftasks.usrac_idusrac,wftasks.wftskstatustypes_idwftskstatustypes,wftasks.wftskstatusglobal_idwftskstatusglobal,wftasks.tasksubject,wftasks.taskdesc,wftasks.timeinactual,wftasks.timeoveralldeadline,wftasks.timetatstart,wftasks.timedeadline,wftasks.timeactiontaken,wftasks.sender_idusrrole,wftasks.sender_idusrac,wftskflow.listorder,wftskflow.idwftskflow,wftskflow.wftsktat,wfproc.wfproctat FROM wftasks 
						INNER JOIN wftskflow ON wftasks.wftskflow_idwftskflow=wftskflow.idwftskflow 
						INNER JOIN wfproc ON wftskflow.wfproc_idwfproc=wfproc.idwfproc
						WHERE idwftasks=".$_SESSION['wtaskid']." LIMIT 1";
						$res_task_details = mysql_query($sql_task_details);
						$fet_task_details = mysql_fetch_array($res_task_details);
					//	echo "<span style=\"color:#ffffff\">".$sql_task_details."<span>";
						////////////// START CALCULATION OF TIME /////////
						//find the next tasks
						//echo $_POST['skip_to_step2']."<br>";
						if ( (isset($_POST['skip_to_step2'])) && ($_POST['skip_to_step2']==1) )
							{
							$sql_nextwf="SELECT idwftskflow,wftskflow.wfsymbol_idwfsymbol as wfsymbol,wfactors.usrrole_idusrrole as usrrole,wfactors.usrgroup_idusrgroup as usrgroup,wftsktat,expubholidays
						FROM wftskflow INNER JOIN wfactors ON wftskflow.idwftskflow=wfactors.wftskflow_idwftskflow 
						WHERE wfproc_idwfproc=".$fet_tat['idwfproc']." AND wftskflow.wfsymbol_idwfsymbol=2 AND wftskflow.listorder>'".$fet_task_details['listorder']."' GROUP BY idwftskflow ORDER BY listorder ASC LIMIT 1,1";
							} else {
							$sql_nextwf="SELECT idwftskflow,wftskflow.wfsymbol_idwfsymbol as wfsymbol,wfactors.usrrole_idusrrole as usrrole,wfactors.usrgroup_idusrgroup as usrgroup,wftsktat,expubholidays
						FROM wftskflow INNER JOIN wfactors ON wftskflow.idwftskflow=wfactors.wftskflow_idwftskflow 
						WHERE wfproc_idwfproc=".$fet_tat['idwfproc']." AND wftskflow.wfsymbol_idwfsymbol=2 AND wftskflow.listorder>'".$fet_task_details['listorder']."' ORDER BY listorder ASC LIMIT 1";
							}
						//echo $sql_nextwf."<br><br>";
						/*$sql_nextwf="SELECT idwftskflow,wftskflow.wfsymbol_idwfsymbol as wfsymbol,wfactors.usrrole_idusrrole as usrrole,wfactors.usrgroup_idusrgroup as usrgroup,wftsktat,expubholidays
						FROM wftskflow INNER JOIN wfactors ON wftskflow.idwftskflow=wfactors.wftskflow_idwftskflow 
						WHERE wfproc_idwfproc=".$fet_task_details['wfproc_idwfproc']." AND wftskflow.wfsymbol_idwfsymbol=2 AND wftskflow.listorder>'".$fet_task_details['listorder']."' ORDER BY idwftskflow ASC LIMIT 1";
						//echo $sql_nextwf."<br>";
						*/
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
									$sql_workinghrs="SELECT time_earliest,time_latest,wfworkingdays_idwfworkingdays FROM wfworkinghrs WHERE wftskflow_idwftskflow=".$fet_task_details['idwftskflow']." AND wfworkingdays_idwfworkingdays=1";
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
						
						
						//insert new task for the recepient
						$sql_new_task="INSERT INTO wftasks (wftaskstrac_idwftaskstrac,usrrole_idusrrole,wftasks_idwftasks,wftskflow_idwftskflow,tktin_idtktin,usrac_idusrac,wftskstatustypes_idwftskstatustypes,wftskstatusglobal_idwftskstatusglobal,tasksubject,taskdesc,timeinactual,timeoveralldeadline,timetatstart,timedeadline,timeactiontaken,sender_idusrrole,sender_idusrac,createdon,createdby) 
						VALUES ('".$fet_task_details['wftaskstrac_idwftaskstrac']."','".$tktasito2."','".$fet_task_details['idwftasks']."','".$fet_nextwf['idwftskflow']."','".$fet_tktid['idtktinPK']."','".$fet_userid['idusrac']."','0','1','".$fet_tktid['tktcategoryname']." - ".$ticketref."','".$tkttskmsg2."','".$timenowis."','".$fet_task_details['timeoveralldeadline']."','".$task_starttime_final."','".$task_deadline_final."','0000-00-00 00:00:00','".$_SESSION['MVGitHub_iduserrole']."','".$_SESSION['MVGitHub_idacname']."','".$timenowis."','".$_SESSION['MVGitHub_idacname']."')";
						$res_new_task=mysql_query($sql_new_task);
						
						//Feedback SMS to send customer/sender a message
						if ( (isset($tktsms)) && (strlen($tktsms)>5) && (strlen($tktsenderphone)==12) && ($res_new_task) )
							{
							$sql_smsout="INSERT INTO mdata_out_sms (destnumber,msgtext) 
							VALUES ('".$tktsenderphone."','".$tktsms."')";
							$res_smsout=mysql_query($sql_smsout);
							}
					
							//Update the ticket status
							/*if ($updateperm==1) //if set, then then update ticket details
								{
								$sql_updatetkt="UPDATE tktin SET 
								tktstatus_idtktstatus='2',
								tktcategory_idtktcategory='".$tktcat."',sendername='".$tktsender."',
								timeclosed='".$timenowis."',city_town='".$tktloc."',
								loctowns_idloctowns='".$location_id."',road_street='".$tktstreet."',building_estate='".$tktbuilding."',unitno='".$tktunitno."',
								waterac='".$tktacno."',kioskno='".$tktkiosk."',usrsession='".session_id()."',
								modifiedby='".$_SESSION['MVGitHub_idacname']."',modifiedon='".$timenowis."' WHERE idtktinPK=".$fet_tktid['idtktinPK']." LIMIT 1";
								mysql_query($sql_updatetkt);
								}*/

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
											VALUES ('".$fet_rolecontacts['usrphone']."','Notification-".$tktsms."')";
											
											mysql_query($sql_sms);
											//echo $sql_sms;
											//exit;
											}
												
									} while ($fet_notify=mysql_fetch_array($res_notify));								
										
								} //close - if there is a notification setting
					
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////					
					///Form Processing ///
					if ( (isset($_POST['formdata_available'])) && ($_POST['formdata_available']==1) )
								{						
								$sql_val="SELECT idwfprocassetsaccess,assetname,perm_read,perm_write,perm_required,wfprocassets.wfprocdtype_idwfprocdtype,idwfprocassets,wfprocassetsgrouplbl,sysprofiles_idsysprofiles,wfprocassetsaccess.wfprocforms_idwfprocforms
								FROM wfprocassetsaccess
								INNER JOIN wfprocassets ON wfprocassetsaccess.wfprocassets_idwfprocassets = wfprocassets.idwfprocassets
								INNER JOIN wfprocassetsgroup ON wfprocassets.wfprocassetsgroup_idwfprocassetsgroup = wfprocassetsgroup.idwfprocassetsgroup
								INNER JOIN wfprocforms_cats ON wfprocassetsaccess.wfprocforms_idwfprocforms = wfprocforms_cats.wfprocforms_idwfprocforms 
								WHERE sysprofiles_idsysprofiles=".$_SESSION['MVGitHub_iduserprofile']." AND wfprocforms_cats.tktcategory_idtktcategory=".$tktcat." AND wfprocassetsaccess.perm_read=1 ORDER BY wfprocassetsgrouplbl ASC,ordering ASC";
							//echo $sql_val;
								$res_val=mysql_query($sql_val);
								$num_val=mysql_num_rows($res_val);
								$fet_val=mysql_fetch_array($res_val);
//			echo $sql_val;
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
											echo "<div class=\"msg_warning_small\">Form : ".$fet_val['assetname']." is required</div>";
											
											}
									
									//if no error on the dataform, then process
									if (!isset($error_formdata))
										{	
			
										if ($_POST['transtype_'.$fet_val['idwfprocassetsaccess'].'']=="INSERT")
											{
											//check the form item type first
											$ttype=$_POST['itemtype_'.$fet_val['idwfprocassetsaccess'].''];
											
												
												if (($ttype==1) || ($ttype==4) || ($ttype==5) || ($ttype==6) || ($ttype==7) || ($ttype==8) || ($ttype==9) || ($ttype==10) ) //if textbox OR yes/no OR datepicker OR datetimepicker
													{
													$fvalue=mysql_real_escape_string(trim($_POST['item_'.$fet_val['idwfprocassetsaccess'].'']));
													
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
													'".$fet_task_details['idwftasks']."',
													'".$fvalue."',
													'',
													'".$fet_task_details['wftaskstrac_idwftaskstrac']."',
													'".$fet_tktid['idtktinPK']."',
													'".$_SESSION['MVGitHub_idacname']."',
													'".$timenowis."'
													)";
													
													mysql_query($sql_insert);
													
											//		echo $sql_insert."<br><br>";
													}
													
												if ($ttype==2)//if menulist
													{
													$fvalue=mysql_real_escape_string(trim($_POST['item_'.$fet_val['idwfprocassetsaccess'].'']));
													
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
													'".$fet_task_details['idwftasks']."',
													'',
													'',
													'".$fet_task_details['wftaskstrac_idwftaskstrac']."',
													'".$fet_tktid['idtktinPK']."',
													'".$_SESSION['MVGitHub_idacname']."',
													'".$timenowis."'
													)";
													
													mysql_query($sql_insert);
										//			echo $sql_insert."<br><br>";
													
													}
													
										if ( ($ttype==3) && (isset($_FILES["item_".$fet_val['idwfprocassetsaccess'].""]["name"])) && (strlen($_FILES["item_".$fet_val['idwfprocassetsaccess'].""]["name"])>4) )//if file upload".$fet_tktid['idtktinPK']."
											{
											$fvalue_upload=basename($_FILES["item_".$fet_val['idwfprocassetsaccess'].""]["name"]);
											$target_dir = "../documents/task_docs/".$today."/";
//											$docname=$fet_tktid['idtktinPK']."_".basename($_FILES["fileToUpload"]["name"]);
											$docname=$fet_tktid['idtktinPK']."_".$fvalue_upload;
											//we need to seed the document to make it unique_
											//lets include the ticket_ref number of the task to the name of the file
											$target_file = $target_dir . $docname;
											$uploadOk = 1;
											//just keep the file name only
											$file_name_only=$fet_tktid['idtktinPK']."_".$_FILES["item_".$fet_val['idwfprocassetsaccess'].""]["name"];
											$file_size_only=$_FILES["item_".$fet_val['idwfprocassetsaccess'].""]["size"];
											//check if there is any document before proceeding
											if (strlen($_FILES["item_".$fet_val['idwfprocassetsaccess'].""]["name"])>4)
												{
												
												$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
												
												//validation before uploading										 
												//check if file exists
												if (file_exists($target_file)) 
													{
													$upload_error_1 = "<div class=\"msg_warning_small\">File Missing</div>";
													}
												
												if ($_FILES["item_".$fet_val['idwfprocassetsaccess'].""]["size"] > 10485760) 
													{
													$upload_error_2 = "<div class=\"msg_warning_small\">File Max Size Exceeded( 10 MB)</div>";
													}
												
												if	($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
													&& $imageFileType != "gif" && $imageFileType != "doc" && $imageFileType != "docx" 
													&& $imageFileType != "pdf" && $imageFileType != "xls" && $imageFileType != "xlsx" 
													&& $imageFileType != "ppt" && $imageFileType != "pptx"  && $imageFileType != "csv"    ) {
														
													$upload_error_3 = "<div class=\"msg_warning_small\">Sorry, file format [".$imageFileType."] not allowed</div>";
													}
												//echo $upload_error_1.$upload_error_2.$upload_error_3;	
												//echo "Size -->".$_FILES["item_".$fet_val['idwfprocassetsaccess'].""]["size"];
												if ( (!isset($upload_error_1)) && (!isset($upload_error_2)) && (!isset($upload_error_3)) )
													{
													 if (move_uploaded_file($_FILES['item_'.$fet_val['idwfprocassetsaccess'].'']["tmp_name"], $target_file)) 
														{
														$upload_success=1;
														//log the record into the Database
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
														'".$fet_task_details['idwftasks']."',
														'',
														'".$file_name_only."',
														'".$fet_task_details['wftaskstrac_idwftaskstrac']."',
														'".$fet_tktid['idtktinPK']."',
														'".$_SESSION['MVGitHub_idacname']."',
														'".$timenowis."'
														)";
														
														mysql_query($sql_insert);
														
														//create the audit log
														$sql_audit="INSERT INTO audit_docuploads ( doc_name, doc_ext, doc_size, tktin_idtktin, createdon, createdby, usersess, usrip, wfprocassets_idwfprocassets) 
														VALUES ('".$file_name_only."', '".$imageFileType."', '".$file_size_only."', '".$fet_tktid['idtktinPK']."', '".$timenowis."', '".$_SESSION['MVGitHub_idacname']."', '".session_id()."', '".$_SERVER['REMOTE_ADDR']."','".$fet_val['idwfprocassets']."')";
														mysql_query($sql_audit);
														
														} else {
															$upload_error_4 = "<div class=\"msg_warning_small\">Sorry, we are unable to upload that file</div>";
														}
													} //no error
												} //where strlen > 4
											} //type==3
											
											} //inserts end
																																								
										}
										
									} while ($fet_val=mysql_fetch_array($res_val));
									
									} //if record is > 0
								
								} //close form data checker
///////////////////////////////////////EXTRA FORM PROCESSING//////////////////
					
					
					
					/////////////////////////////check and insert a new subscriber
						//check if a subscriber with the same credentials matches
					/*	$sql_subis="SELECT idsmssubs FROM ".$_SESSION['MVGitHub_tblsmsbc']." WHERE subnumber='".$tktsenderphone."' AND usrtype=1";
						$res_subis=mysql_query($sql_subis);
						$num_subis=mysql_num_rows($res_subis);
						//echo $sql_subis;
						//if not, add the new credentials
						if ($num_subis==0)
							{
							$sql_subnew="INSERT INTO ".$_SESSION['MVGitHub_tblsmsbc']." (wftskid,tktid,subnumber,idloctown,idusrteamzone,usrtype,createdon,createdby)
							VALUES ('".$_SESSION['wtaskid']."','".$fet_tktid['idtktinPK']."','".$tktsenderphone."','".$location_id."','".$_SESSION['MVGitHub_userteamzoneid']."','1','".$timenowis."','".$_SESSION['MVGitHub_idacname']."')";
							mysql_query($sql_subnew);
							//echo $sql_subnew;
							}
						*/
							
								
					///////////////////////////// close check and insert new subscriber ////////////////////////////

					//redirect to the correct page
					if ( ($query_1) && ($query_2) && ($query_3) && ($query_4) && ($query_5) && ($query_6) && ($query_8) && ($query_9) && ($res_tktcategory) && ($res_msg) && ($query_a) && ($query_b) && ($res_task_details) && ($res_nextwf) && ($res_new_task) && ($res_smsout) && (!isset($upload_error_1)) && (!isset($upload_error_2)) && (!isset($upload_error_3)) && (!isset($upload_error_4)) )
						{
						mysql_query("COMMIT");
					?>
					<script language="javascript">
                    window.location='go_to_taskhistory_s.php?msg=<?php echo urlencode($msg_changes_saved);?>';
                    </script>
					<?php 
					exit;
						} else {
						mysql_query("ROLLBACK");
						$sql_error="INSERT INTO z_errorlog (errorcode,query,usersession,pageurl,idusr,tktnumber,idbug) 
						VALUES ('".mysql_error()."','','".session_id()."','".curPageURL()."','".$_SESSION['MVGitHub_idacname']."','".$fet_tktid['idtktinPK']."','0')";
						mysql_query($sql_error);
						
						?>
                   <script language="javascript">
                     alert ('Sorry! Please Try Again!');
                    </script>
                        <?php
						//echo $sql_newtkt;
						//release the queries
						mysql_free_result($query_1); 
						mysql_free_result($query_2);
						mysql_free_result($query_3);
						mysql_free_result($query_4);
						mysql_free_result($query_5);
						mysql_free_result($query_6);
						mysql_free_result($query_8);
						mysql_free_result($query_9);
						mysql_free_result($res_tktcategory);
						mysql_free_result($res_msg);
						mysql_free_result($query_a);
						mysql_free_result($query_b);
						mysql_free_result($res_task_details);
						mysql_free_result($res_nextwf);
						mysql_free_result($res_new_task);
						mysql_free_result($res_smsout);
						
						} //else query failed
						
						} //close no error on action 2
				
				} //close action 2
				
			
				
		}//close if no error
	
	} //form action to update ticket 
	
?>
<!DOCTYPE html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta content="utf-8" http-equiv="encoding">
<title>Create New Ticket</title>
<link href="../assets_backend/css/style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../scripts/jquery.js"></script>
<!--
<script type="text/javascript" src="../scripts/jquery-1.9.1.min.js"></script>
-->
<script type="text/javascript" src="../scripts/jquery-ui.min.js"></script>
<script type="text/javascript" src="../scripts/jquery-ui-timepicker-addon_.js"></script>
<script src="../scripts/gen_validatorv4.js"  type="text/javascript"></script>
<script type="text/javascript" src="../scripts/jquery.autocomplete.js"></script>

<link href="../thickbox/original_thickbox.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../thickbox/thickbox_be.js"></script>
<script language="javascript">
//restrict to numbers or alpha
var numb = "0123456789.";
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


//hide show invalid reasons text box
    $(function() {
        $('#invalid_id').change(function(){
            $('.invalid_new').hide();
            $('#' + $(this).val()).show();
        });
    });
	
	
function getAJAXHTTPREQ() { //fuction to return the xml http object
		var xmlhttp=false;	
		try{
			xmlhttp=new XMLHttpRequest();
		}
		catch(e)	{		
			try{			
				xmlhttp= new ActiveXObject("Microsoft.XMLHTTP");
			}
			catch(e){
				try{
				xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
				}
				catch(e1){
					xmlhttp=false;
				}
			}
		}
		 	
		return xmlhttp;
    }
	
	
function getwkflow(tktcatId) {		
		
		var strURL="pop_newticket_wf.php?tktcat="+tktcatId;
		var req = getAJAXHTTPREQ();
			
		if (req) {
			
			req.onreadystatechange = function() {
				if (req.readyState == 4) {
					// only if "OK"
					if (req.status == 200) {						
						document.getElementById('workflowdiv').innerHTML=req.responseText;
					} else {
						alert("There was a problem while using XMLHTTP:\n" + req.statusText);
					}
				}				
			}			
			req.open("GET", strURL, true);
			req.send(null);
		}		
	}


	function getcategories(tkttypeId) {		
		
		var strURL="findtktcategories.php?tkttype="+tkttypeId;
		var req = getAJAXHTTPREQ();
			
		if (req) {
			
			req.onreadystatechange = function() {
				if (req.readyState == 4) {
					// only if "OK"
					if (req.status == 200) {						
						document.getElementById('tktcatdiv').innerHTML=req.responseText;						
					} else {
						alert("There was a problem while using XMLHTTP:\n" + req.statusText);
					}
				}				
			}			
			req.open("GET", strURL, true);
			req.send(null);
		}		
	}
	

function getrecepients(regionId) {		
		
		var strURL="findregrecipients.php?assign_to_region="+regionId;
		var req = getAJAXHTTPREQ();
			
		if (req) {
			
			req.onreadystatechange = function() {
				if (req.readyState == 4) {
					// only if "OK"
					if (req.status == 200) {						
						document.getElementById('recepientdiv').innerHTML=req.responseText;						
					} else {
						alert("There was a problem while using XMLHTTP:\n" + req.statusText);
					}
				}				
			}			
			req.open("GET", strURL, true);
			req.send(null);
		}		
	}

<?php
$sql_msgs="SELECT idwftskstatustypes,wftskstatustypedesc,listorder FROM wftskstatustypes ORDER BY wftskstatustypes.listorder ASC";
$res_msgs=mysql_query($sql_msgs);
$fet_msgs=mysql_fetch_array($res_msgs);
$num_msgs=mysql_num_rows($res_msgs);
//echo $sql_msgs;
?>            
//<![CDATA[ 
//change feedback to customer depending on the selection made here
var txtfeedback = new Array();

txtfeedback[0] = "";

<?php
do {
echo "txtfeedback[".$fet_msgs['idwftskstatustypes']."]=\"".$fet_msgs['wftskstatustypedesc']."\";\r\n";
	} while ($fet_msgs=mysql_fetch_array($res_msgs));
?>
function Choice() {

y = document.getElementById("action_msg");

document.getElementById("txtfeedback").value = txtfeedback[y.selectedIndex];
}
       
//]]>  

</script>
<script type="text/javascript">
function showstuff(element){
    document.getElementById("1").style.display = element=="1"?"block":"none";
    document.getElementById("2").style.display = element=="2"?"block":"none";
}
</script>

<script type="text/javascript">
$().ready(function() {
	$("#locationtown").autocomplete("findlocation_2.php", {
		width: 350,
		matchContains: true,
		//mustMatch: true,
		//minChars: 0,
		//multiple: true,
		//highlight: false,
		//multipleSeparator: ",",
		selectFirst: false
	});
});
</script>
<!-- Preloader on Click Below -->
<script type="text/javascript" src="../uilock/jquery.uilock.js"></script>
<script language="javascript">
			$(document).ready(function() {
				//$('#lock').click(function(){
				$('#button_passiton').click(function(){
				
					// To lock user interface interactions
					// Optinal: put html on top of the lock section,
					// like animated loading gif
					
					//$.uiLock('some html and <a href="#" onclick="$.uiUnlock();">unlock</a>');
				$.uiLock('<center class=msg_ok_overlay>Please Wait One Moment ...</center>');
					
				});
				
				
				// To unlock user interface interactions
				//$.uiUnlock();

			});

//pull the account details form the acc_ncwsc table
function showUser(str)
{
if (str=="")
  {
  document.getElementById("txtHint").innerHTML="";
  return;
  }
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
    document.getElementById("txtHint").innerHTML=xmlhttp.responseText;
    }
  }
xmlhttp.open("GET","ajax_getacc.php?q="+str,true);
xmlhttp.send();
}
		
</script>
</head>
<?php //flush(); ?>
<body  <?php /*if there is an error, then reload the right-side panel automatically*/ if ( (isset($_POST['formaction'])) && ($_POST['formaction']=="process_task") ) { echo "onLoad=\"getwkflow(".$tktcat."); getrecepients(".$to_region.");\""; } ?>  >
<div>
	<div class="tbl_sh" style="position:fixed; margin:0px; padding:0px; top:0px">
    <table border="0" cellpadding="0" cellspacing="0" width="<?php echo $_SESSION['tb_width'];?>">
  		<tr>
        	<td >
            <?php echo $lbl_ticket_new;?>
            </td>
        	<td align="right">
            	<table border="0" cellpadding="0" cellspacing="0">
                	<tr>
                    	<td><!--<a href="integrate_cms_free.php" id="button_cms"></a>--></td>
                        <td><a href="#" onClick="parent.tb_remove();" id="button_closewin"></a></td>
                    </tr>
                 </table>
            	
            </td>
      </tr>
    </table>
    </div>
  
    <div style="padding:30px 0px 0px 0px">
    <?php

		if (isset($error_1)) { echo $error_1; }
		if (isset($error_2)) { echo $error_2; }
		if (isset($error_4)) { echo $error_4; }
		if (isset($error_5)) { echo $error_5; }
		if (isset($error_6)) { echo $error_6; }
		if (isset($error_7)) { echo $error_7; }
		if (isset($error_8)) { echo $error_8; }
		if (isset($error_9)) { echo $error_9; }
		if (isset($error_10)) { echo $error_10; }
		if (isset($error_11)) { echo $error_11; }
		if (isset($error_12)) { echo $error_12; }
		if (isset($error_13)) { echo $error_13; }
		if (isset($error_14)) { echo $error_14; }
		if (isset($error_15)) { echo $error_15; }
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
		if (isset($error_1b)) { echo $error_1b; }
		if (isset($error_acno)) { echo $error_acno; }
	?>
    </div>
<form method="post" action="" name="task" id="task" autocomplete="off" enctype="multipart/form-data">
	<div>
    <table border="0" width="100%" cellpadding="0" cellspacing="0">
    	<tr>
        	<td valign="top" width="50%" class="hline" style="padding:15px 0px 0px 0px">
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
                        <td width="40%" class="tbl_data">Previous Ticket No :  </td>
                       <td class="tbl_data">
						<input name="prev_tktnumber" type="text" class="small_field" id="prev_tktnumber"  value="" /> 
						[ OPTIONAL ]
                        <a href="#" style="text-decoration:none;" class="tooltip"><img src="../assets_backend/icons/help.gif" border="0" align="absmiddle" /><span><?php echo $msg_tip_recurr;?></span></a>
                       </td>
                    </tr>
                    <tr>
                    	<td class="tbl_data">
                        <?php echo $lbl_asterik;?><?php echo $lbl_tkttype;?>
                        </td>
                        <td class="tbl_data">
                        <select name="tkttype" id="tkttype" onChange="getcategories(this.value)">
                        <option value="0">---</option>
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
                      <div id="tktcatdiv">
                      <?php
					  //check if a post is set or else just display ---
if ((isset($_POST['tktcat'])) && ($_POST['tktcat']>0) )
	  	{
$query="SELECT  DISTINCT  idtktcategory,tktcategoryname,wfproc.wfstatus FROM tktcategory 
INNER JOIN link_tktcategory_tktype ON tktcategory.idtktcategory=link_tktcategory_tktype.tktcategory_idtktcategory
INNER JOIN link_tskcategory_wfproc ON link_tktcategory_tktype.tktcategory_idtktcategory=link_tskcategory_wfproc.tktcategory_idtktcategory 
LEFT JOIN wfproc ON link_tskcategory_wfproc.wfproc_idwfproc=wfproc.idwfproc
WHERE link_tskcategory_wfproc.usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']." 
AND link_tktcategory_tktype.tkttype_idtkttype=".$tkttype." 
AND tktcategory.internal_task_cat=0
ORDER BY tktcategoryname ASC";
$result=mysql_query($query);
//echo $query;
?>
<select name="tktcat" onBlur="getwkflow(this.value)" onChange="getwkflow(this.value)">
<option value="0"> --- </option>
<?php while($row=mysql_fetch_array($result)) { ?>
<option <?php if ($row['wfstatus']!=1) { echo "disabled=\"disabled\""; } ?> <?php  if ( (isset($_POST['tktcat'])) && ($_POST['tktcat']==$row['idtktcategory']) ) { echo "selected=\"selected\""; } ?> value=<?php echo $row['idtktcategory']; ?>><?php echo $row['tktcategoryname']; ?></option>
<?php } ?>
</select>
					<?php
						} else {
					 ?>
                     <select name="tktcat">
                     <option value="0">---</option>
					</select>
                     <?php
					 }
					 ?>
                      </div>
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
			<input name="tktdate" style="background-color:#CCCCCC" type="text" class="small_field" id="tktdate" value="<?php if (isset($tktnewdeadline)) { echo $tktnewdeadline;} else { echo date("d/m/Y H:i",strtotime($timenowis)); }?>" readonly="readonly" />

            <!--
            onClick="datetimepicker('tktdate');" 
            <script language="javascript">
							$('#tktdate').datetimepicker({
							controlType: 'select',
							timeFormat: 'hh:mm',
							dateFormat: 'dd/mm/yy'
							});
							</script>
-->                           
 
						</td>
                    </tr>
                    <tr>
                        <td width="40%" class="tbl_data"><?php echo $lbl_deadline_tkt;?></td>
                        <td class="tbl_data"><?php
						echo "AUTO_GENERATED";
						?></td>
                    </tr>
                     <tr>
                        <td width="40%" class="tbl_data"><?php echo $lbl_asterik;?><?php echo $lbl_waterac;?><small> ( for Private Complaints )</small></td>
                        <td class="tbl_data">
                        <input onBlur="showUser(this.value)"  type="text" <?php echo $field_status;?> class="small_field" value="<?php if (isset($tktacno)) { echo $tktacno; } ?>" name="acnumber" maxlength="20" size="20" />
                        <div id="txtHint"></div>
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
                
                <div class="table_header"><?php echo $lbl_contactdetails;?>                </div>

                  <div style="background-color:#FFFFFF">
                   <table border="0" width="100%" cellpadding="2" cellspacing="0">
                    <tr>
                        <td width="40%" class="tbl_data"><?php echo $lbl_asterik;?><?php echo $lbl_cname;?></td>
                      <td class="tbl_data"><input <?php echo $field_status;?> name="sendername" type="text" class="small_field" id="sendername" value="<?php if (isset($tktsender)) { echo $tktsender;} ?>" size="25" maxlength="50" /></td>
                    </tr>
                    <tr>
                        <td width="40%" class="tbl_data"><?php echo $lbl_gender;?></td>
                      <td class="tbl_data">
                      <select name="usrgender">
                    <option value="-"  >---</option>
                    <option value="F"  >Female</option>
                    <option value="M"  >Male</option>
                    </select>                      </td>
                    </tr>
                    <tr>
                        <td width="40%" class="tbl_data"><?php echo $lbl_asterik;?><?php echo $lbl_mobile;?></td>
                      <td class="tbl_data">
                      <select name="pref_senderphone">
                      <option value="254" selected="selected">(254)</option>
                      </select>
                      <input onKeyUp="res(this,numb);"  name="senderphone" type="text" class="small_field" id="senderphone" value="<?php if (isset($suff_tktsenderphone)) { echo $suff_tktsenderphone;} else { echo "07"; } ?>" size="18" maxlength="10" /></td>
                    </tr>
                    <tr>
                        <td width="40%" class="tbl_data"><?php echo $lbl_email;?></td>
                        <td class="tbl_data"><input  name="senderemail" type="text" value="<?php if (isset($tktsendermail)) { echo $tktsendermail; }?>" class="small_field" id="senderemail" size="25" maxlength="70" /></td>
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
                        <td width="40%" class="tbl_data"><?php echo $lbl_asterik;?> Problem Location</td>
                      <td class="tbl_data">
                    <input type="text" <?php echo $field_status;?> value="<?php if (isset($tktloc)) { echo $tktloc;} ?>" name="locationtown" id="locationtown" autocomplete="off" maxlength="50" size="32"  class="small_field" />                        </td>
                    </tr>
					<tr>
                        <td width="40%" valign="top" class="tbl_data">Directions / Landmark <br /><strong>OR</strong><br /> Postal Address</td>
                        <td class="tbl_data">
                        <input type="hidden" value="<?php echo $_SESSION['MVGitHub_userteamshortname']; ?> [ TICKET NUMBER WILL BE AUTO-GENERATED AND SENT TO THE CUSTOMER ]" readonly="readonly" <?php echo $field_status;?> cols="25" rows="3" name="txtsms" id="txtfeedback">
                        <textarea name="plandmark" cols="20" rows="2"><?php if (isset($directions)) { echo $directions;}  ?></textarea>                        </td>
                    </tr>
                    </table>
                </div>
               </td>
          <td valign="top" width="50%" style="padding:17px 10px 0px 5px">
<div style="width:<?php echo ($_SESSION['tb_width']/2);?>px">
<?php
if (isset($form_app)) { echo "<div id=\"form_app\">".$form_app."</div>"; } 
?>
    <div id="workflowdiv" style="display:block" >
    </div>
    <div>
    <!-- content for static form here-->   
    <!-- end content here-->
    </div>
</div>            
            
          </td>
        </tr>
    </table>
	</div>    
</form>
<script type="text/javascript">
var myformValidator = new Validator("task");
myformValidator.addValidation("tktcat","req","Please select the Ticket Category");
myformValidator.addValidation("tkttype","req","Please select the Ticket Type");
myformValidator.addValidation("txtmsg","req","Please key in the Customers Message");
if ((document.task.tkttype.value==1) && (document.task.acnumber.value==""))
	{
	myformValidator.addValidation("acnumber","req","Please enter the Account Number");
	}
/*
myformValidator.addValidation("acnumber","req","Please enter the Account Number");*/
myformValidator.addValidation("sendername","req","Please enter the Customers First Name");
myformValidator.addValidation("senderphone","minlen=10","Please enter the Customers Mobile Phone Number eg:0722654321");
myformValidator.addValidation("locationtown","req","Please enter the Location");
/*
myformValidator.addValidation("task_msg_8","req","Please enter the Task Message");
myformValidator.addValidation("task_msg_2","req","Please enter the Task Message");*/

function submitform()
{
  if (document.task.onsubmit())
  {
    document.task.submit();
  }
}
</script>
</div>	

</body>
</html>
