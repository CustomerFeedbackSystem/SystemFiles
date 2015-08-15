<?php
require_once('../assets_backend/be_includes/config.php');
//require_once('../Connections/connSystem.php');
//mysql_select_db($database_connSystem, $connSystem);

require_once('../assets_backend/be_includes/check_login_easy.php');

//include("fckeditor/fckeditor.php");

//get the title
if (isset($_GET['title']))
	{
	$_SESSION['wtitle']=preg_replace('/[^a-z\-_0-9\.:\/]/i','',mysql_real_escape_string(trim($_GET['title'])));
	}

if (isset($_GET['task']))
	{
	$_SESSION['wtaskid']=preg_replace('/[^a-z\-_0-9\.:\/]/i','',mysql_real_escape_string(trim($_GET['task'])));	
	}

//UNSET THE SESSION VARIABLES
if (isset($_SESSION['tkttskmsg1'])) { unset($_SESSION['tkttskmsg1']); }
if (isset($_SESSION['tkttskmsg2'])) { unset($_SESSION['tkttskmsg2']); }
if (isset($_SESSION['tkttskmsg3'])) { unset($_SESSION['tkttskmsg3']); }
if (isset($_SESSION['tkttskmsg4'])) { unset($_SESSION['tkttskmsg4']); }
if (isset($_SESSION['tkttskmsg5'])) { unset($_SESSION['tkttskmsg5']); }
if (isset($_SESSION['tkttskmsg6'])) { unset($_SESSION['tkttskmsg6']); }
if (isset($_SESSION['num_rec_rcm'])) { unset($_SESSION['num_rec_rcm']); }

if ( (isset($_GET['recalltask'])) && ($_GET['recalltask']=="yes") )
	{
	//retrieve the details of the workflow
	$sql_wfdetails="SELECT idwftasks,wftaskstrac_idwftaskstrac,usrrole_idusrrole,wftasks_idwftasks,wftskflow_idwftskflow,tktin_idtktin,usrac_idusrac,wftskstatustypes_idwftskstatustypes,wftskstatusglobal_idwftskstatusglobal,tasksubject,taskdesc,timeinactual,timeoveralldeadline,timetatstart,timedeadline,timeactiontaken,sender_idusrrole,sender_idusrac
	FROM wftasks WHERE wftaskstrac_idwftaskstrac=".$_SESSION['wftaskstrac']." ORDER BY idwftasks DESC LIMIT 1,1";
	$res_wfdetails=mysql_query($sql_wfdetails);
	$fet_wfdetails=mysql_fetch_array($res_wfdetails);
	$num_wfdetails=mysql_num_rows($res_wfdetails);
	
	//echo $sql_wfdetails."<br>";
	if ($num_wfdetails > 0)
		{ // > than 0
		//log that action on the task updates
		$sql_wfupdates="INSERT INTO wftskupdates (wftaskstrac_idwftaskstrac,usrrole_idusrrole,usrac_idusrac,wftskstatusglobal_idwftskstatusglobal,wftskstatustypes_idwftskstatustypes,wftasks_idwftasks,wftskupdate,createdby,createdon) 
		VALUES ('".$_SESSION['wftaskstrac']."','".$_SESSION['MVGitHub_iduserrole']."','".$_SESSION['MVGitHub_idacname']."','3'
		,'10',
		'".$_SESSION['wtaskid']."',
		' [ TASK RETRACTED / RECALLED AT ".date("D, M d, Y H:i",strtotime($timenowis))." ] ',
		'".$_SESSION['MVGitHub_idacname']."',
		'".$timenowis."')";
//		mysql_query($sql_wfupdates);
		
		//update this task as retracted 
		$sql_updatetask="UPDATE wftasks SET wftskstatustypes_idwftskstatustypes='10',
		wftskstatusglobal_idwftskstatusglobal='2' WHERE idwftasks=".$_SESSION['wtaskid']." LIMIT 1";
//		mysql_query($sql_updatetask);
		
		//recreate a new task from the original
		$sql_newtask="INSERT INTO wftasks (wftaskstrac_idwftaskstrac,usrrole_idusrrole,wftasks_idwftasks,wftskflow_idwftskflow,tktin_idtktin,usrac_idusrac,wftskstatustypes_idwftskstatustypes,wftskstatusglobal_idwftskstatusglobal,tasksubject,taskdesc,timeinactual,timeoveralldeadline,timetatstart,timedeadline,timeactiontaken,sender_idusrrole,sender_idusrac,createdon,createdby) 
		VALUES 
		('".$fet_wfdetails['wftaskstrac_idwftaskstrac']."','".$fet_wfdetails['usrrole_idusrrole']."','".$fet_wfdetails['wftasks_idwftasks']."','".$fet_wfdetails['wftskflow_idwftskflow']."','".$fet_wfdetails['tktin_idtktin']."','".$fet_wfdetails['usrac_idusrac']."','0','1','".$fet_wfdetails['tasksubject']."','".$fet_wfdetails['taskdesc']."','".$fet_wfdetails['timeinactual']."','".$fet_wfdetails['timeoveralldeadline']."','".$fet_wfdetails['timetatstart']."','".$fet_wfdetails['timedeadline']."','".$fet_wfdetails['timeactiontaken']."','".$fet_wfdetails['sender_idusrrole']."','".$fet_wfdetails['sender_idusrac']."','".$timenowis."','".$_SESSION['MVGitHub_idacname']."')";
	//	mysql_query($sql_newtask);
		echo $sql_newtask;
		exit;
		?>
		<script language="javascript">
		window.location='pop_taskretracted.php';
		</script>
		<?php
        exit;
            } //greater than 0
        }	
//echo "<br><br><br>".$_SESSION['wtaskid'];	
//echo $_SESSION['wtaskid'];	
if (isset($_SESSION['wtaskid']))
	{
	$sql_historyid="SELECT wftasks_idwftasks,wftaskstrac_idwftaskstrac FROM wftasks WHERE idwftasks=".$_SESSION['wtaskid']." LIMIT 1";
	$res_historyid=mysql_query($sql_historyid);
	$fet_historyid=mysql_fetch_array($res_historyid);
	}


//query the database for this task details
/*
$sql_history="SELECT idwftskupdates,wftasks.idwftasks,usrrole.usrrolename,usrrole.idusrrole,usrteamzone.userteamzonename,usrteamzone.usrteam_idusrteam,usrac.idusrac,usrac.utitle,usrac.lname,usrac.fname,usrac.usremail,usrac.usrphone,wftskstatustypes.wftskstatustype,wftskstatustypes.idwftskstatustypes,wftskupdates.wftskupdate,wftskstatustypes.wftskstatuslbl,wftskupdates.createdon,wftasks.tktin_idtktin FROM wftskupdates
INNER JOIN usrrole ON wftskupdates.usrrole_idusrrole=usrrole.idusrrole
INNER JOIN usrteamzone ON usrrole.usrteamzone_idusrteamzone=usrteamzone.idusrteamzone 	
INNER JOIN usrac ON wftskupdates.usrac_idusrac=usrac.idusrac
INNER JOIN wftasks ON wftskupdates.wftasks_idwftasks=wftasks.idwftasks 	
INNER JOIN wftskstatustypes ON wftskupdates.wftskstatustypes_idwftskstatustypes=wftskstatustypes.idwftskstatustypes 
WHERE wftasks.wftaskstrac_idwftaskstrac=".$fet_historyid['wftaskstrac_idwftaskstrac']." 
AND wftasks.wftaskstrac_idwftaskstrac=wftskupdates.wftaskstrac_idwftaskstrac
AND usrteamzone.usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']." 
GROUP BY (date(wftskupdates.createdon) + INTERVAL 20 SECOND),
usrrolename 
ORDER BY idwftskupdates DESC";
*/
//change by Daniel on 14th April 2014 after a complaint that some tasks historys were not visible on the trail
//grouping by usrrolename was making some history not to appear
$sql_history="SELECT idwftskupdates,wftasks.idwftasks,usrrole.usrrolename,usrrole.idusrrole,usrteamzone.userteamzonename,usrteamzone.usrteam_idusrteam,usrac.idusrac,usrac.utitle,usrac.lname,usrac.fname,usrac.usremail,usrac.usrphone,wftskstatustypes.wftskstatustype,wftskstatustypes.idwftskstatustypes,wftskupdates.wftskupdate,wftskstatustypes.wftskstatuslbl,wftskupdates.createdon,wftasks.tktin_idtktin FROM wftskupdates
INNER JOIN usrrole ON wftskupdates.usrrole_idusrrole=usrrole.idusrrole
INNER JOIN usrteamzone ON usrrole.usrteamzone_idusrteamzone=usrteamzone.idusrteamzone 	
INNER JOIN usrac ON wftskupdates.usrac_idusrac=usrac.idusrac
INNER JOIN wftasks ON wftskupdates.wftasks_idwftasks=wftasks.idwftasks 	
INNER JOIN wftskstatustypes ON wftskupdates.wftskstatustypes_idwftskstatustypes=wftskstatustypes.idwftskstatustypes 
WHERE wftasks.wftaskstrac_idwftaskstrac=".$fet_historyid['wftaskstrac_idwftaskstrac']." 
AND wftasks.wftaskstrac_idwftaskstrac=wftskupdates.wftaskstrac_idwftaskstrac
AND usrteamzone.usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']." 
AND (date(wftskupdates.createdon) + INTERVAL 20 SECOND)
ORDER BY idwftskupdates DESC";


$res_history=mysql_query($sql_history);
$fet_history=mysql_fetch_array($res_history);
$num_history=mysql_num_rows($res_history);

//echo "<span><br><br><br><br>".$sql_history."</span>";

//ADDITION TO CATER FOR SECONDARY COMPLAINTS HISTORY --- BY DICKSON ON JULY 25TH 2014

if (isset($_SESSION['tsk_sectkt']))
	{
	$sql_historyid_sec="SELECT wftasks_idwftasks,wftaskstrac_idwftaskstrac FROM wftasks WHERE idwftasks=".$_SESSION['tsk_sectkt']." LIMIT 1";
	$res_historyid_sec=mysql_query($sql_historyid_sec);
	$fet_historyid_sec=mysql_fetch_array($res_historyid_sec);	
	

//query the database for this task details
$sql_history_sec="SELECT idwftskupdates,wftasks.idwftasks,usrrole.usrrolename,usrrole.idusrrole,usrteamzone.userteamzonename,usrteamzone.usrteam_idusrteam,usrac.idusrac,usrac.utitle,usrac.lname,usrac.fname,usrac.usremail,usrac.usrphone,wftskstatustypes.wftskstatustype,wftskstatustypes.idwftskstatustypes,wftskupdates.wftskupdate,wftskstatustypes.wftskstatuslbl,wftskupdates.createdon,wftasks.tktin_idtktin FROM wftskupdates
INNER JOIN usrrole ON wftskupdates.usrrole_idusrrole=usrrole.idusrrole
INNER JOIN usrteamzone ON usrrole.usrteamzone_idusrteamzone=usrteamzone.idusrteamzone 	
INNER JOIN usrac ON wftskupdates.usrac_idusrac=usrac.idusrac
INNER JOIN wftasks ON wftskupdates.wftasks_idwftasks=wftasks.idwftasks 	
INNER JOIN wftskstatustypes ON wftskupdates.wftskstatustypes_idwftskstatustypes=wftskstatustypes.idwftskstatustypes
WHERE wftasks.wftaskstrac_idwftaskstrac=".$fet_historyid_sec['wftaskstrac_idwftaskstrac']." 
AND usrteamzone.usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']." 
ORDER BY idwftskupdates DESC";
$res_history_sec=mysql_query($sql_history_sec);
$fet_history_sec=mysql_fetch_array($res_history_sec);
$num_history_sec=mysql_num_rows($res_history_sec);

}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Task Details</title>
<link href="../assets_backend/css/style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../scripts/jquery.js"></script>
<link href="../thickbox/original_thickbox.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../thickbox/thickbox_be.js"></script>

</head>
<body>
<table border="0" width="100%" cellpadding="2" cellspacing="0" >
	<tr>
    	<td style="padding:0px 0px 30px 0px">
            <div class="tbl_sh" style="position:fixed; margin:0px; padding:0px ; top:0px; width:100%">
                <table border="0" cellpadding="0" cellspacing="0" width="100%">
                    <tr>
                        <td width="700">
                        <?php echo $lbl_taskhistory;?>
                        </td>
                        <td>&nbsp;</td>
                      <td>
                        <?php
                        //only show if not a duplicate check
                        if (!isset($_GET['duplicate']))
                            {
                        ?>
                        <a href="pop_taskview.php?tktcat=<?php echo $_SESSION['tktid'];?>&task=<?php echo $_SESSION['wtaskid'];?>" id="button_taskentry"></a>
                         <?php } ?>
                        </td>
                        <td>
                       <a href="#" onClick="parent.tb_remove();" id="button_closewin"></a>
                        </td>
                    </tr>
                </table>
            </div>            
       	</td>
    </tr>
    <?php if (isset($_REQUEST['msg'])) { 
	$msg=preg_replace('/[^a-z\-_0-9\.:\/\s]/i','',mysql_real_escape_string(trim($_GET['msg'])));
	?>
    <tr>
    	<td height="40">
        <div class="msg_success_small"><?php echo $msg;?></div>
        <div class="text_body_large" style="padding:0px 10px; font-weight:bold"><a href="pop_newticket.php">CREATE ANOTHER TICKET</a></div>
        </td>
    </tr>
    <?php } ?>
    <tr>
    	<td>
        <table border="0" width="100%" cellpadding="0" cellspacing="0">
            <tr>
                <td valign="top" width="60%" class="hline">
                <?php 
				if ($num_history>0)
					{
					$hist=1;
					do { ?>
                	<div class="bline" <?php 
						// technocurve arc 3 php mv block2/3 start
						echo " style=\"background-color:$mocolor\" onMouseOver=\"this.style.backgroundColor='$mocolor3'\" onMouseOut=\"this.style.backgroundColor='$mocolor'\"";
						// technocurve arc 3 php mv block2/3 end
						?>>
                		<table border="0" width="100%" cellpadding="1" cellspacing="0">
                    		<tr>
								<td width="250" align="left" class="text_small" style="margin:0px; padding:0px" valign="top">
                                    <div style="background-color:#D4D0C8; font-weight:bold; width:160px; padding:5px">
                                    <?php echo date("D, M d, Y H:i",strtotime($fet_history['createdon'])); ?>
                                    </div>
                                </td>

								<td align="right" class="text_small">
									<?php
                                    echo "<span style=\"color:#ffffff\">".$fet_history['idwftskupdates']."</span>"	;
                                    //find the recepient of this task
                                    $sql_recepient="SELECT utitle,lname,fname,usrrolename,idusrrole,usrphone,usremail,userteamzonename FROM wftasks
                                    INNER JOIN usrrole ON wftasks.usrrole_idusrrole=usrrole.idusrrole 
                                    INNER JOIN usrac ON wftasks.usrac_idusrac=usrac.idusrac
                                    INNER JOIN usrteamzone ON usrrole.usrteamzone_idusrteamzone=usrteamzone.idusrteamzone
                                    WHERE wftasks_idwftasks=".$fet_history['idwftasks']." LIMIT 1";
                                    $res_recepient=mysql_query($sql_recepient);
                                    $num_recepient=mysql_num_rows($res_recepient);
                                    $fet_recepient=mysql_fetch_array($res_recepient);
                            
                                    if ($fet_history['usrrolename']!=$fet_recepient['usrrolename'])
                                        {
                                        echo $fet_history['wftskstatuslbl']; //
                                        } else {
                                        echo "In Progress (My Tasks IN)";
                                        }
                                 
                                         //option to recall this task if the sender is the one viewing this task - only applies to the latest task
                                        // if ( ($hist==1) && (
                                    /*	 echo "<br>loop >".$hist."<br>";
                                         echo "sender role id >".$fet_history['idusrrole']."<br>";
                                         echo "sender account_usr id >". $fet_history['idusrac']."<br>";
                                         echo "status type >".$fet_history['idwftskstatustypes']."<br>";
                                         echo "rept role id >".$fet_recepient['idusrrole']."<br>";
                                         echo "user role id >".$_SESSION['MVGitHub_iduserrole'];
                                    */ 
                                     if (
                                     ($fet_history['idusrrole']==$_SESSION['MVGitHub_iduserrole'] ) && 
                                     ($hist==1) && 
                                     ($fet_history['idusrrole']!=$fet_recepient['idusrrole']) && 
                                     ($fet_history['idusrrole']==$_SESSION['MVGitHub_iduserrole'] ) && 
                                     ($fet_history['idwftskstatustypes']==2) 
                                     )
                                         {
                                        //echo "<a href=\"".$_SERVER['PHP_SELF']."?recalltask=yes\" title=\"This task will be returned to your Tasks IN\" onclick=\"return confirm('Are you sure you want to recall this Task? If you click OK, this task will return to your Tasks IN');\" id=\"button_recall\"></a>";
                                         }
                                    ?>
                                </td>
                      		</tr>
                            
                      		<tr>
                                <td class="text_small" title="<?php echo $fet_history['utitle']." ".$fet_history['fname']. " ".$fet_history['lname']. " ( ".$fet_history['usremail'];
                                if (strlen($fet_history['usrphone']) > 5) { 
                                echo " | ".$fet_history['usrphone'];
                                }
                                echo " )";?>">
                                <strong><?php echo $fet_history['usrrolename'];?><br /><small><?php echo $fet_history['userteamzonename'];?></small></strong>
                                </td>

                                <td class="text_small">
                                    <?php
                                    //echo $sql_recepient."<br><br>";
                                    if (($num_recepient >0) && ($fet_history['usrrolename']!=$fet_recepient['usrrolename']) )
                                        {
                                        echo "&raquo;&nbsp;&nbsp;&nbsp;<span style=\"font-weight:bold\" title=\"".$fet_recepient['utitle']." ".$fet_recepient['fname']." ".$fet_recepient['lname']." ( ".$fet_recepient['usremail'];
                                            if (strlen($fet_recepient['usrphone']) > 5) { 
                                            echo " | ".$fet_recepient['usrphone'];
                                            }
                                    echo " )\">".$fet_recepient['usrrolename']."<br><small>".$fet_recepient['userteamzonename']."</small></span>";
                                        }
                                    ?>
                                </td>
                      		</tr>
                            <tr>
                                <td colspan="4" class="text_body">
                                <?php echo $fet_history['wftskupdate'];?>
                                </td>
                            </tr>
                    	</table>
                	</div>               
                
						<?php 
                        // technocurve arc 3 php mv block3/3 start
                        if ($mocolor == $mocolor1) 
                            {
                            $mocolor = $mocolor2;
                            } else {
                            $mocolor = $mocolor1;
                            }
                        // technocurve arc 3 php mv block3/3 end
						$hist++;
						} while ($fet_history=mysql_fetch_array($res_history));					
					} 

				//get the ticket details and display them as the first history item at the bottom
				$sql_tkt="SELECT idtktinPK,senderphone,refnumber,waterac,tktdesc,timereported,tktchannelname,tktstatusname,tkttypename,createdby ,wftasks_batch_idwftasks_batch,voucher_number FROM tktin 
				INNER JOIN tktchannel ON tktin.tktchannel_idtktchannel=tktchannel.idtktchannel
				INNER JOIN tktstatus ON tktin.tktstatus_idtktstatus=tktstatus.idtktstatus
				INNER JOIN tkttype ON tktin.tkttype_idtkttype=tkttype.idtkttype
				WHERE idtktinPK=".$_SESSION['tktid']." ";
				$res_tkt=mysql_query($sql_tkt);
				$fet_tkt=mysql_fetch_array($res_tkt);

				?>
                
                <div class="bline" <?php 
					// technocurve arc 3 php mv block2/3 start
					echo " style=\"background-color:$mocolor\" onMouseOver=\"this.style.backgroundColor='$mocolor3'\" onMouseOut=\"this.style.backgroundColor='$mocolor'\"";
					// technocurve arc 3 php mv block2/3 end
					?>>
	
					<table border="0" width="100%" cellpadding="1" cellspacing="0">
						<tr>
							<td width="160" align="left" bgcolor="#D4D0C8" class="text_small"><strong><?php echo date("D, M d, Y H:i",strtotime($fet_tkt['timereported'])); ?></strong></td>
							<td align="left" class="text_small">
							  <strong><?php echo "Customer - via ".$fet_tkt['tktchannelname'];?></strong> 
							</td>
							<td align="right" class="text_small">
							<?php echo "New Ticket";?>                            
							</td>
						</tr>
						<tr>
							<td colspan="4" class="text_body">
							<?php echo "[".$fet_tkt['refnumber']."]&nbsp;".$fet_tkt['tktdesc'];?>
							</td>
						</tr>
					</table>
				</div>
                
                <?php if(isset($_SESSION['tkt_sectkt'])) { ?>
                <div style="background-color:#007DB1; color:#FFFFFF; padding:3px; font-size:12px; font-weight:bold">Previous Ticket History - <?php echo $_SESSION['prev_tkt_num']; ?></div>
                <div style="border-bottom:thick; border-bottom-color:#FFFFFF"></div>
                
                <!-- TASK HISTORY FOR THE PREVIOUS TICKET-->
              	<?php 
				if ($num_history_sec>0)
					{
					$hist=1;
					do { ?>
                	<div class="bline" <?php 
						// technocurve arc 3 php mv block2/3 start
						echo " style=\"background-color:$mocolor\" onMouseOver=\"this.style.backgroundColor='$mocolor3'\" onMouseOut=\"this.style.backgroundColor='$mocolor'\"";
						// technocurve arc 3 php mv block2/3 end
						?>>
                		<table border="0" width="100%" cellpadding="1" cellspacing="0">
                    		<tr>
								<td width="250" align="left" class="text_small" style="margin:0px; padding:0px" valign="top">
                                    <div style="background-color:#007DB1; color:#FFFFFF; font-weight:bold; width:160px; padding:5px">
                                    <?php echo date("D, M d, Y H:i",strtotime($fet_history_sec['createdon'])); ?>
                                    </div>
                                </td>

								<td align="right" class="text_small">
									<?php
                                    echo "<span style=\"color:#ffffff\">".$fet_history_sec['idwftskupdates']."</span>"	;
                                    //find the recepient of this task
                                    $sql_recepient_sec="SELECT utitle,lname,fname,usrrolename,idusrrole,usrphone,usremail,userteamzonename FROM wftasks
                                    INNER JOIN usrrole ON wftasks.usrrole_idusrrole=usrrole.idusrrole 
                                    INNER JOIN usrac ON wftasks.usrac_idusrac=usrac.idusrac
                                    INNER JOIN usrteamzone ON usrrole.usrteamzone_idusrteamzone=usrteamzone.idusrteamzone
                                    WHERE wftasks_idwftasks=".$fet_history_sec['idwftasks']." LIMIT 1";
                                    $res_recepient_sec=mysql_query($sql_recepient_sec);
                                    $num_recepient_sec=mysql_num_rows($res_recepient_sec);
                                    $fet_recepient_sec=mysql_fetch_array($res_recepient_sec);
                            
                                    if ($fet_history_sec['usrrolename']!=$fet_recepient_sec['usrrolename'])
                                        {
                                        echo $fet_history_sec['wftskstatuslbl']; //
                                        } else {
                                        echo "In Progress (My Tasks IN)";
                                        }
                                 
                                         //option to recall this task if the sender is the one viewing this task - only applies to the latest task
                                        // if ( ($hist==1) && (
                                    /*	 echo "<br>loop >".$hist."<br>";
                                         echo "sender role id >".$fet_history['idusrrole']."<br>";
                                         echo "sender account_usr id >". $fet_history['idusrac']."<br>";
                                         echo "status type >".$fet_history['idwftskstatustypes']."<br>";
                                         echo "rept role id >".$fet_recepient['idusrrole']."<br>";
                                         echo "user role id >".$_SESSION['MVGitHub_iduserrole'];
                                    */ 
                                     if (
                                     ($fet_history_sec['idusrrole']==$_SESSION['MVGitHub_iduserrole'] ) && 
                                     ($hist==1) && 
                                     ($fet_history_sec['idusrrole']!=$fet_recepient_sec['idusrrole']) && 
                                     ($fet_history_sec['idusrrole']==$_SESSION['MVGitHub_iduserrole'] ) && 
                                     ($fet_history_sec['idwftskstatustypes']==2) 
                                     )
                                         {
                                        //echo "<a href=\"".$_SERVER['PHP_SELF']."?recalltask=yes\" title=\"This task will be returned to your Tasks IN\" onclick=\"return confirm('Are you sure you want to recall this Task? If you click OK, this task will return to your Tasks IN');\" id=\"button_recall\"></a>";
                                         }
                                    ?>
                                </td>
                      		</tr>
                            
                      		<tr>
                                <td class="text_small" title="<?php echo $fet_history_sec['utitle']." ".$fet_history_sec['fname']. " ".$fet_history_sec['lname']. " ( ".$fet_history_sec['usremail'];
                                if (strlen($fet_history_sec['usrphone']) > 5) { 
                                echo " | ".$fet_history_sec['usrphone'];
                                }
                                echo " )";?>">
                                <strong><?php echo $fet_history_sec['usrrolename'];?><br /><small><?php echo $fet_history_sec['userteamzonename'];?></small></strong>
                                </td>

                                <td class="text_small">
                                    <?php
                                    //echo $sql_recepient."<br><br>";
                                    if (($num_recepient_sec >0) && ($fet_history_sec['usrrolename']!=$fet_recepient_sec['usrrolename']) )
                                        {
                                        echo "&raquo;&nbsp;&nbsp;&nbsp;<span style=\"font-weight:bold\" title=\"".$fet_recepient_sec['utitle']." ".$fet_recepient_sec['fname']." ".$fet_recepient_sec['lname']." ( ".$fet_recepient_sec['usremail'];
                                            if (strlen($fet_recepient_sec['usrphone']) > 5) { 
                                            echo " | ".$fet_recepient_sec['usrphone'];
                                            }
                                    echo " )\">".$fet_recepient_sec['usrrolename']."<br><small>".$fet_recepient_sec['userteamzonename']."</small></span>";
                                        }
                                    ?>
                                </td>
                      		</tr>
                            <tr>
                                <td colspan="4" class="text_body">
                                <?php echo $fet_history_sec['wftskupdate'];?>
                                </td>
                            </tr>
                    	</table>
                	</div>               
                
						<?php 
                        // technocurve arc 3 php mv block3/3 start
                        if ($mocolor == $mocolor1) 
                            {
                            $mocolor = $mocolor2;
                            } else {
                            $mocolor = $mocolor1;
                            }
                        // technocurve arc 3 php mv block3/3 end
						$hist++;
						} while ($fet_history_sec=mysql_fetch_array($res_history_sec));					
					} 

				//get the ticket details and display them as the first history item at the bottom
				$sql_tkt_sec="SELECT idtktinPK,senderphone,refnumber,waterac,tktdesc,timereported,tktchannelname,tktstatusname,tkttypename,createdby FROM tktin 
				INNER JOIN tktchannel ON tktin.tktchannel_idtktchannel=tktchannel.idtktchannel
				INNER JOIN tktstatus ON tktin.tktstatus_idtktstatus=tktstatus.idtktstatus
				INNER JOIN tkttype ON tktin.tkttype_idtkttype=tkttype.idtkttype
				WHERE idtktinPK=".$_SESSION['tkt_sectkt']." ";
				$res_tkt_sec=mysql_query($sql_tkt_sec);
				$fet_tkt_sec=mysql_fetch_array($res_tkt_sec);
				//echo $sql_tkt;
				?>
                
                <div class="bline" <?php 
					// technocurve arc 3 php mv block2/3 start
					echo " style=\"background-color:$mocolor\" onMouseOver=\"this.style.backgroundColor='$mocolor3'\" onMouseOut=\"this.style.backgroundColor='$mocolor'\"";
					// technocurve arc 3 php mv block2/3 end
					?>>
	
					<table border="0" width="100%" cellpadding="1" cellspacing="0">
						<tr>
							<td width="160" align="left" style="background-color:#007DB1; color:#FFFFFF;" class="text_small"><strong><?php echo date("D, M d, Y H:i",strtotime($fet_tkt_sec['timereported'])); ?></strong></td>
							<td align="left" class="text_small">
							  <strong><?php echo "Customer - via ".$fet_tkt_sec['tktchannelname'];?></strong> 
							</td>
							<td align="right" class="text_small">
							<?php echo "New Ticket";?>                            
							</td>
						</tr>
						<tr>
							<td colspan="4" class="text_body">
							<?php echo "[".$fet_tkt_sec['refnumber']."]&nbsp;".$fet_tkt_sec['tktdesc'];?>
							</td>
						</tr>
					</table>
				</div>
                <?php } ?>
                <!-- END OF TASK HISTORY FOR THE PREVIOUS TICKET -->
                
             	</td>
              	<td valign="top" width="40%" style="padding:0px 20">
              		<div class="bline" style="margin:10px 0px 10px 0px; padding:5px 0px 10px 0px" >
                  		<span class="title_header_blue"><?php echo $lbl_ticketno;?> : </span>
                  		<span class="text_body_large"><?php echo $fet_tkt['refnumber'];?></span>
                  	</div>
												                  			
                  	<div class="bline" style="margin:10px 0px 10px 0px; padding:5px 0px 10px 0px">
                  		<span class="title_header_blue" >Account Number : </span>
                  		<span class="text_body_large"><?php echo $fet_tkt['waterac'];?></span>
                  	</div>
                  
					 <?php
                    $res_batch=mysql_query("SELECT batch_no_verbose FROM wftasks_batch WHERE idwftasks_batch=".$fet_tkt['wftasks_batch_idwftasks_batch']."");
                    $fet_batch=mysql_fetch_array($res_batch);
                    $num_batch=mysql_num_rows($res_batch);
				
					if ($num_batch > 0)
						{ ?>
                        <div class="bline" style="margin:10px 0px 10px 0px; padding:5px 0px 10px 0px" >
                            <span class="title_header_blue">Voucher No. : </span>
                            <span class="text_body_large"><?php $voucher_raw=$fet_batch['batch_no_verbose']."/".str_pad($fet_tkt['voucher_number'], 4, '0', STR_PAD_LEFT);
                            echo str_replace('/',' / ',$voucher_raw);?></span>
                        </div>
					<?php } ?>
                    
               	  	<div class="bline" style="margin:10px 0px 10px 0px; padding:5px 0px 10px 0px" >
                  		<span class="title_header_blue"><?php echo $lbl_currentstatus;?> : </span>
                  		<span class="text_body_large"><?php echo $fet_tkt['tktstatusname'];?></span>
                  	</div>
                  
                  	<div class="bline" style="margin:10px 0px 10px 0px; padding:5px 0px 10px 0px">
                  		<span class="title_header_blue" ><?php echo $lbl_tkttype;?> : </span>
                  		<span class="text_body_large"><?php echo $fet_tkt['tkttypename'];?></span>
                  	</div>
                    
                  	<!-- option to print complaint number if issue was keyed in manually -->
                  	<div class="text_body">
                  		<?php 
				  		if ($fet_tkt['createdby']!=2) { 
				  		?>
                  		<table border="0">
                  			<tr>
                    			<td>
                        			<a href="#" onClick="tb_open_new('print_form_complaint.php?ticketnumber=<?php echo $fet_tkt['idtktinPK']; ?>&amp;title=<?php echo $fet_tkt['refnumber']; ?>&amp;tabview=1&amp;keepThis=true&amp;TB_iframe=true&amp;height=400&width=800&amp;modal=true')" title="Print Complaint Form" id="btn_printsmall"></a>
                        		</td>
                        		<td class="text_body">
                        			<a href="#" onClick="tb_open_new('print_form_complaint.php?ticketnumber=<?php echo $fet_tkt['idtktinPK']; ?>&amp;title=<?php echo $fet_tkt['refnumber']; ?>&amp;tabview=1&amp;keepThis=true&amp;TB_iframe=true&amp;height=400&width=800&amp;modal=true')" title="Print Complaint Form" >Print Complaint Form</a>
                        		</td>
                    		</tr>
                  		</table>
                  		<?php } ?>
                  	</div>
              	</td>
			</tr>
		</table>
  		</td>
	</tr>
    <tr>
    	<td style="color:#FFFFFF">
        <?php
//		echo $_SESSION['debug'];
//		echo $_SESSION['debug_2'];
//		echo $_SESSION['debug_3'];
//		echo $_SESSION['debug_4'];

//echo "<span><br><br><br><br>".$sql_history."</span>";

		?>
        </td>
    </tr>
</table>
<?php
//echo "<span style=\"color:#ffffff\"><br><br><br><br>".$sql_history."</span>";
?>
</body>
</html>