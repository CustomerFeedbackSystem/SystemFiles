<?php
require_once('../assets_backend/be_includes/config.php');
//require_once('../Connections/connSystem.php');
//mysql_select_db($database_connSystem, $connSystem);

require_once('../assets_backend/be_includes/check_login_easy.php');

//get the title
if (isset($_GET['title']))
	{
	$_SESSION['wtitle']=preg_replace('/[^a-z\-_0-9\.:\/]/i','',mysql_escape_string(trim($_GET['title'])));
	}

if (isset($_GET['task']))
	{
	$_SESSION['wtaskid']=preg_replace('/[^a-z\-_0-9\.:\/]/i','',mysql_escape_string(trim($_GET['task'])));	
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
	FROM wftasks WHERE wftaskstrac_idwftaskstrac=".$fet_historyid['wftaskstrac_idwftaskstrac']." ORDER BY idwftasks DESC LIMIT 1,1";
	$res_wfdetails=mysql_query($sql_wfdetails);
	$fet_wfdetails=mysql_fetch_array($res_wfdetails);
	$num_wfdetails=mysql_num_rows($res_wfdetails);
	
	//echo $sql_wfdetails."<br>";
	if ($num_wfdetails > 0)
		{ // > than 0
		//log that action on the task updates
		$sql_wfupdates="INSERT INTO wftskupdates (wftaskstrac_idwftaskstrac,usrrole_idusrrole,usrac_idusrac,wftskstatusglobal_idwftskstatusglobal,wftskstatustypes_idwftskstatustypes,wftasks_idwftasks,wftskupdate,createdby,createdon) 
		VALUES ('".$fet_historyid['wftaskstrac_idwftaskstrac']."','".$_SESSION['MVGitHub_iduserrole']."','".$_SESSION['MVGitHub_idacname']."','3'
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
//echo "".$sql_historyid;

//query the database for this task details
$sql_history="SELECT idwftskupdates,wftasks.idwftasks,wftasks.wftasks_idwftasks,usrrole.usrrolename,usrrole.idusrrole,usrteamzone.userteamzonename,usrteamzone.usrteam_idusrteam,usrac.idusrac,usrac.utitle,usrac.lname,usrac.fname,usrac.usremail,usrac.usrphone,wftskstatustypes.wftskstatustype,wftskstatustypes.idwftskstatustypes,wftskupdates.wftskupdate,wftskstatustypes.wftskstatuslbl,wftskupdates.createdon,wftasks.tktin_idtktin,wftasks.actedon_idusrrole,wftasks.actedon_idusrac,wftskupdates.createdby,wftasks.wftaskstrac_idwftaskstrac,wftskupdates.wftasks_co_idwftasks_co 
FROM wftskupdates
INNER JOIN usrrole ON wftskupdates.usrrole_idusrrole=usrrole.idusrrole
INNER JOIN usrteamzone ON usrrole.usrteamzone_idusrteamzone=usrteamzone.idusrteamzone 	
INNER JOIN usrac ON wftskupdates.usrac_idusrac=usrac.idusrac
INNER JOIN wftasks ON wftskupdates.wftasks_idwftasks=wftasks.idwftasks 	
INNER JOIN wftskstatustypes ON wftskupdates.wftskstatustypes_idwftskstatustypes=wftskstatustypes.idwftskstatustypes
WHERE wftasks.wftaskstrac_idwftaskstrac=".$fet_historyid['wftaskstrac_idwftaskstrac']." AND usrteamzone.usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']." ORDER BY createdon DESC";
$res_history=mysql_query($sql_history);
$fet_history=mysql_fetch_array($res_history);
$num_history=mysql_num_rows($res_history);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="../assets_backend/css/style.css" rel="stylesheet" type="text/css" />
<title>Print Ticket History</title>
</head>
<body>
<div class="hidden" style="font-family:Arial, Helvetica, sans-serif;  width:650px; background-color:#FFFFFF;">
<table border="0" width="100%">
	<tr>
    	<td width="25%">
        <a href="#" onClick="window.print()"><img border="0" align="absmiddle" src="../assets_backend/btns/print_small.gif" /> Print this Page</a>        </td>
        <td width="75%" align="right"><div align="center">*** Please ensure your printer is connected first ***</div></td>
	</tr>
</table>
</div>
<?php
//get the ticket details and display them as the first history item at the bottom
$sql_tkt="SELECT idtktinPK,senderphone,refnumber,waterac,tktdesc,timereported,tktchannelname,tktstatusname,tkttypename,createdby,createdon,tktcategory_idtktcategory,tktin.wftasks_batch_idwftasks_batch FROM tktin 
INNER JOIN tktchannel ON tktin.tktchannel_idtktchannel=tktchannel.idtktchannel
INNER JOIN tktstatus ON tktin.tktstatus_idtktstatus=tktstatus.idtktstatus
INNER JOIN tkttype ON tktin.tkttype_idtkttype=tkttype.idtkttype
WHERE idtktinPK=".$_SESSION['tktid']." ";
$res_tkt=mysql_query($sql_tkt);
$fet_tkt=mysql_fetch_array($res_tkt);
//echo "<span style=\"color:#ffffff\">".$sql_tkt."</span>";
?>
<div style="width:650px">
<div>

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
    {
    ?>
    <div class="bline" style="margin:10px 0px 10px 0px; padding:5px 0px 10px 0px" >
    <span class="title_header_blue">Voucher No. : </span>
    <span class="text_body_large"><?php $voucher_raw=$fet_batch['batch_no_verbose']."/".$fet_tkt['voucher_number'];
    echo str_replace('/',' / ',$voucher_raw);?></span>
    </div>
    <?php
    }
    ?>
  <div class="bline" style="margin:10px 0px 10px 0px; padding:5px 0px 10px 0px" >
    <span class="title_header_blue"><?php echo $lbl_currentstatus;?> : </span>
    <span class="text_body_large"><?php echo $fet_tkt['tktstatusname'];?></span>
  </div>
    
    <div class="bline" style="margin:10px 0px 10px 0px; padding:5px 0px 10px 0px">
    <span class="title_header_blue" ><?php echo $lbl_tkttype;?> : </span>
    <span class="text_body_large"><?php echo $fet_tkt['tkttypename'];?></span>
  </div>

</div>



<div>
<?php 
				if ($num_history>0)
					{
					$hist=1;
					do {
				?>
                <div class="bline" <?php 
// technocurve arc 3 php mv block2/3 start
echo " style=\"background-color:$mocolor\" onMouseOver=\"this.style.backgroundColor='$mocolor3'\" onMouseOut=\"this.style.backgroundColor='$mocolor'\"";
// technocurve arc 3 php mv block2/3 end
?>>
                	<table border="0" width="100%" cellpadding="1" cellspacing="0">
                    	<tr>
							<td align="left" class="text_small" style="margin:0px; padding:0px" valign="top">
                            <div style="background-color:#D4D0C8; font-weight:bold; width:160px; padding:5px">
							<?php echo date("D, M d, Y H:i",strtotime($fet_history['createdon'])); ?>
                            </div>
                          </td>
						  <td align="right" class="text_small"><?php
						echo "<span style=\"color:#ffffff\">".$fet_history['idwftskupdates']."</span>"	;
						//find the recepient of this task $fet_history['idwftskstatustypes']
						//
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
                      	<td width="50%" class="text_small" >
                        <strong>
                          <?php  
						 
							if ($fet_history['wftasks_co_idwftasks_co']>0)
								{ //wftasks_co_idwftasks_co
								$sql_careof="SELECT fname,lname,idusrac_acting,idusrac_owner,usremail,usrphone,
								(SELECT fname FROM wftasks_co INNER JOIN usrac ON wftasks_co.idusrac_acting=usrac.idusrac WHERE idwftasks_co=".$fet_history['wftasks_co_idwftasks_co']." ) as actor_fname,
								(SELECT lname FROM wftasks_co INNER JOIN usrac ON wftasks_co.idusrac_acting=usrac.idusrac WHERE idwftasks_co=".$fet_history['wftasks_co_idwftasks_co']." ) as actor_lname,
								(SELECT usrphone FROM wftasks_co INNER JOIN usrac ON wftasks_co.idusrac_acting=usrac.idusrac WHERE idwftasks_co=".$fet_history['wftasks_co_idwftasks_co']." ) as actor_usrphone,
								(SELECT usremail FROM wftasks_co INNER JOIN usrac ON wftasks_co.idusrac_acting=usrac.idusrac WHERE idwftasks_co=".$fet_history['wftasks_co_idwftasks_co']." ) as actor_usremail    
								FROM wftasks_co INNER JOIN usrac ON wftasks_co.idusrac_owner=usrac.idusrac WHERE wftasks_co.idwftasks_co=".$fet_history['wftasks_co_idwftasks_co']."";
								$res_careof=mysql_query($sql_careof);
								$fet_careof=mysql_fetch_array($res_careof);
								
								echo "<span title=\"".$fet_careof['actor_usremail']." ".$fet_careof['actor_usrphone']."\"> By : ".$fet_careof['actor_fname']." ".$fet_careof['actor_lname']." </span>";
								echo "<span style=\"color:#ff0000\" title=\"".$fet_careof['usremail']." ".$fet_careof['usrphone']."\">( For : ".$fet_careof['fname']." ".$fet_careof['lname']." )</span>";
																
								} else {
								 echo "<span title=\"".$fet_history['utitle']." ".$fet_history['fname']." ".$fet_history['lname']. " ( ".$fet_history['usremail']. " ".$fet_history['usrphone'].") \">".$fet_history['usrrolename']."</span>";

								}
							?>
                        <br />
                        <small><?php echo $fet_history['userteamzonename'];?></small>
                        </strong>
                      
                        </td>
                      	<td class="text_small" width="50%">
                        <?php
						//echo $sql_recepient."<br><br>";
						if (($num_recepient >0) && ($fet_history['usrrolename']!=$fet_recepient['usrrolename']) && ($fet_history['idwftskstatustypes']!=6) )
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
                            <?php 
							//echo "<span style=\"color:#cc0000\">".$fet_history['idwftasks']."</span><br>";
							//echo "<span style=\"color:#cc0000\">".$fet_history['wftasks_idwftasks']."</span>";
							//process the task history
							//echo "<div style=\"font-size:11px;background-color:#FFFFC4;padding:5px 8px;margin:8px 0px\">";
							$search  = array('[[[', ']]]');
						    $replace = array('<div style="font-size:11px;background-color:#FFFFC4;padding:5px 8px;margin:8px 0px"><div class="text_small"><strong>SMS to Customer</strong></div><div class="text_small">', '</div></div>');
							
							echo str_replace($search, $replace, $fet_history['wftskupdate']);
							//echo $fet_history['wftskupdate'];
							
							
							//if there is a form data component with this history
							//LIMITED ONLY TO FORM ID 2 - :/ 
							$res_formdata=mysql_query("SELECT value_choice,utitle,fname,lname,value_choice,usrrole_idusrrole,
							(SELECT usrrolename FROM usrrole WHERE idusrrole=usrrole_idusrrole) as rolename
							 FROM wfassetsdata 
							 INNER JOIN wfprocassetsaccess ON wfassetsdata.wfprocassetsaccess_idwfprocassetsaccess=wfprocassetsaccess.idwfprocassetsaccess 
							 INNER JOIN wfprocassets ON wfprocassetsaccess.wfprocassets_idwfprocassets=wfprocassets.idwfprocassets
							 INNER JOIN usrac ON wfassetsdata.createdby=usrac.idusrac 
							 WHERE 
							 wfprocassetsaccess.wfprocforms_idwfprocforms=2 
							 AND wfassetsdata.wftskupdates_idwftskupdates=".$fet_history['idwftskupdates']."
							 AND wfassetsdata.wftasks_idwftasks=".$fet_history['idwftasks']."
							 AND wfassetsdata.createdby=".$fet_history['idusrac']." AND tktin_idtktin=".$_SESSION['tktid']." 
							 AND wftaskstrac_idwftaskstrac=".$fet_historyid['wftaskstrac_idwftaskstrac']." 
							 AND value_choice!='' AND wfprocassets.wfprocdtype_idwfprocdtype=10 LIMIT 1");//
							
							$num_formdata=mysql_num_rows($res_formdata);
							$fet_formdata=mysql_fetch_array($res_formdata);
							
							if ($num_formdata > 0)
								{
								echo "<div style=\"font-size:11px;background-color:#FFFFC4;padding:5px 8px;margin:8px 0px\">
								<a style=\"text-decoration:none\" target=\"_blank\" href=\"print_form_complaint.php?ticketnumber=".$fet_tkt['idtktinPK']."&amp;title=".$fet_tkt['refnumber']."\" >
								<div class=\"text_small\"><strong>Official Complaint Form Remarks by ".$fet_formdata['rolename']." (".$fet_formdata['utitle']." ".$fet_formdata['fname']." ".$fet_formdata['lname'].")</strong></div>
								<div>
								".$fet_formdata['value_choice']."
								</div>
								</a>
								</div>";
								}
							?>
                            </td>
                        </tr>
                    </table>
                </div>               
                
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
				$hist++;
					} while ($fet_history=mysql_fetch_array($res_history));					
				}
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
                              <strong><?php echo "Customer - via ".$fet_tkt['tktchannelname'];?></strong> </td>
                          <td align="right" class="text_small">
                            <?php echo "New Ticket";?>                            </td>
                </tr>
                        <tr>
                        	<td colspan="4" class="text_body">
                            <?php echo "[".$fet_tkt['refnumber']."]&nbsp;".$fet_tkt['tktdesc'];?>
                            </td>
                        </tr>
                    </table>
</div>
	<div class="tbl_sh">
    Printed On : <?php echo date("D, M d, Y H:i",strtotime($fet_tkt['createdon'])); ?>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    Printed By : <?php echo $_SESSION['MVGitHub_usrfname']." ".$_SESSION['MVGitHub_usrlname'];?>
  </div>
</div>
</div>
</body>
</html>
