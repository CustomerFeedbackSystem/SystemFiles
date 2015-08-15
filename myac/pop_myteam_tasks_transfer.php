<?php
require_once('../assets_backend/be_includes/config.php');
require_once('../assets_backend/be_includes/check_login_easy.php');

if ((isset($_GET['action'])) && ($_GET['action']=="delete") )
	{
	//clean up
	$del_id=trim(mysql_escape_string($_GET['rid']));
	
	//delete
	$sql_rid="DELETE FROM wftasks_transfers_usrto WHERE idwftasks_transfers_usrto=".$del_id." AND createdby=".$_SESSION['MVGitHub_idacname']." ";
	mysql_query($sql_rid);
	}
	
if ( (isset($_POST['formaction'])) && ($_POST['formaction']=="transfer") )
	{
	
	//get the Deadline Date for the Tasks Correctly
	if (isset($_POST['tktdate']))
		{
		$tktdate=preg_replace('/[^a-z\-_0-9\.:\/\s]/i','',mysql_escape_string(trim($_POST['tktdate'])));
		$tktdate_trans=str_replace('/','-',$tktdate);
		$tktdate_fin=date("Y-m-d H:i:s",strtotime($tktdate_trans));
		} else {
		$tktdate_fin=date("Y-m-d H:i:s",strtotime($timenowis)+ (1*86400));
		 
		}
	$notes=trim(mysql_escape_string($_POST['note']));
	
	$tasks=explode(",",$_SESSION['tasks']); //tasks from previous page
	//create array for the actors receiving the tasks
//	echo $tasks;
	$count=count($_POST['actor']);
	
	if ($count>0)
		{
		$usrid="";
		for ($i=0; $i<$count; $i++) //then pick and store the variables
			{
			$usrid.=$_POST['actor'][$i].",";				
			}	
			
		$usrid_final=substr($usrid,0,-1);

		$actors=explode(",",$usrid_final);
		}
	
	//process
		if (($count > 0) && ($_SESSION['tasks_no']>0) ) //if all OK, then go ahead
			{
			$index=0;//set initial index
			
			foreach ($tasks as $value) //then just loop the arrays
				{

				//get the usrrole id of this user - added status=1 check by daniel on 11th March 2015 by Daniel
				$sql_roleid="SELECT usrrole_idusrrole FROM usrac WHERE idusrac=".$actors[$index]." AND acstatus=1";
				$res_roleid=mysql_query($sql_roleid);
				$fet_roleid=mysql_fetch_array($res_roleid);
				
				if ($fet_roleid['usrrole_idusrrole'] > 0) // bug fix from $fet_roleid > 0 to $fet_roleid['usrrole_idusrrole'] on 8th Jan 2015 by Daniel
					{
					//get details of this task
					$sql_wftrack="SELECT idwftasks,wftaskstrac_idwftaskstrac,wftskflow_idwftskflow,tasksubject,tktin_idtktin,taskdesc,usrrole_idusrrole,usrac_idusrac,timeoveralldeadline,timetatstart FROM wftasks WHERE idwftasks=".$value."";
					$res_wftrack=mysql_query($sql_wftrack);
					$num_wftrack=mysql_num_rows($res_wftrack);
					$fet_wftrack=mysql_fetch_array($res_wftrack);
					
					if ($num_wftrack > 0)
						{
						mysql_query("BEGIN");
						
						//recreate the task message to indicate it's a transfer
						$msg_task=$fet_wftrack['taskdesc']."<br>[ TRANSFERRED OUT BY - ".$_SESSION['MVGitHub_usrfname']." ".$_SESSION['MVGitHub_usrlname']." said : ".$notes." ]";
						$msg_task2=$fet_wftrack['taskdesc']."<br>[ TRANSFERRED IN BY - ".$_SESSION['MVGitHub_usrfname']." ".$_SESSION['MVGitHub_usrlname']." said : ".$notes." ]";
						
						//update wftask first to change the status
						$sql_updatetask="UPDATE wftasks SET 
						wftskstatustypes_idwftskstatustypes=2,
						wftskstatusglobal_idwftskstatusglobal=2,
						timeactiontaken='".$timenowis."',
						taskdesc='".$msg_task."',
						actedon_idusrrole=".$_SESSION['MVGitHub_iduserrole'].",
						actedon_idusrac=".$_SESSION['MVGitHub_idacname']."
						WHERE idwftasks=".$value."";
						$query_1=mysql_query($sql_updatetask);
						
						//then create a new task linked to previous task
						//insert new task for the recepeint
						$sql_new_task="INSERT INTO wftasks (wftaskstrac_idwftaskstrac,usrrole_idusrrole,wftasks_idwftasks,wftskflow_idwftskflow,tktin_idtktin,usrac_idusrac,wftskstatustypes_idwftskstatustypes,wftskstatusglobal_idwftskstatusglobal,tasksubject,taskdesc,timeinactual,timeoveralldeadline,timetatstart,timedeadline,timeactiontaken,sender_idusrrole,sender_idusrac,createdon) 
						VALUES ('".$fet_wftrack['wftaskstrac_idwftaskstrac']."','".$fet_roleid['usrrole_idusrrole']."','".$fet_wftrack['idwftasks']."','".$fet_wftrack['wftskflow_idwftskflow']."','".$fet_wftrack['tktin_idtktin']."','".$actors[$index]."','0','1','".$fet_wftrack['tasksubject']."','".$msg_task2."','".$timenowis."','".$fet_wftrack['timeoveralldeadline']."','".$fet_wftrack['timetatstart']."','".$tktdate_fin."','0000-00-00 00:00:00','".$_SESSION['MVGitHub_iduserrole']."','".$_SESSION['MVGitHub_idacname']."','".$timenowis."')";
						$query_4=mysql_query($sql_new_task);
						
						//insert wftaskupdates
						$sql_history="INSERT INTO wftskupdates (wftaskstrac_idwftaskstrac,usrrole_idusrrole,usrac_idusrac,wftskstatusglobal_idwftskstatusglobal,wftskstatustypes_idwftskstatustypes,wftasks_idwftasks,wftskupdate,createdby,createdon) 
						VALUES (".$fet_wftrack['wftaskstrac_idwftaskstrac'].",'".$_SESSION['MVGitHub_iduserrole']."','".$_SESSION['MVGitHub_idacname']."','2','3','".$value."','[ TRANSFERRED IN ] ".$notes."','".$_SESSION['MVGitHub_idacname']."','".$timenowis."')";
						$query_2=mysql_query($sql_history);
					
						//keep a log in wftasks_transfers
						$sql_log="INSERT INTO wftasks_transfers (wftasks_idwftasks,usrroleid_from,usracid_from,usrroleid_to,usracid_to,createdby_idusrac,createdon,transfer_batch)
						VALUES (".$value.",".$fet_wftrack['usrrole_idusrrole'].",".$fet_wftrack['usrac_idusrac'].",".$fet_roleid['usrrole_idusrrole'].",".$actors[$index].",".$_SESSION['MVGitHub_idacname'].",'".$timenowis."',".$_SESSION['batch'].")";
						$query_3=mysql_query($sql_log);
						
						//echo $value."--->".$actors[$index]."<br>";
						if ( ($query_1) && ($query_2) && ($query_3) && ($query_4) )
							{
							mysql_query("COMMIT");	
							$transaction_ok=1;
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
							mysql_free_result($query_4);
							}
						}
					}
				
				if ($index >= $count-1)
					{
					$index=0;
					} else { 
					$index=$index + 1;
					}
				 }
				 
		
				 
			} //if ALL OK
	
	} //transfer
?>
<div style="padding:10px">
<?php
if (!isset($transaction_ok))
	{
?>
<table border="0" cellpadding="1" cellspacing="0">

    <tr>
    	<td align="right" class="tbl_h">No. of Tasks to Transfer</td>
    </tr>
     <tr>
      <td height="35" class="tbl_data"><span style="background-color:#f8f8f8; padding:5px; font-size:14px"><?php echo $_SESSION['tasks_no'];?> Tasks</span></td>
    </tr>
</table>
</div>
<div >
<table border="0" width="100%">
	<tr>
   	  <td width="50%" valign="top">
        <div >
        <?php
		if ((isset($_POST['formaction'])) && ($_POST['formaction']=="add_rec") && (isset($_POST['recepient_alt'])) && (strlen($_POST['recepient_alt'])>1) )
			{
			//commit to the transfer DB
			$val_usrto=trim(mysql_escape_string($_POST['recepient_alt']));
			
			//take ther role
			$str_ex=explode(',',$val_usrto);
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
			//exit;
			if ($num_userid>0)
				{
				$sql_insert_role="INSERT INTO wftasks_transfers_usrto (wftasks_transfers_batch_idtransferbatch,usrroleid_to,usracid_to,createdon,createdby) 
				VALUES (".$_SESSION['batch'].",".$fet_userid['usrrole_idusrrole'].",".$fet_userid['idusrac'].",'".$timenowis."',".$_SESSION['MVGitHub_idacname'].")";
				//echo $sql_insert_role;
				mysql_query($sql_insert_role);
				}
				
			} //form action
		?>
        <form method="post" action="" name="findusr">
        	<table border="0" cellpadding="0" cellspacing="0">
            	<tr>
                	<td colspan="2" class="tbl_h">
                   List of Recepients
                    </td>
                </tr>
            	<tr>
                	<td width="335" height="40" style="padding:0px 0px 0px 5px; margin:0px">
                    <input onclick="hidetxt()" onblur="showtxt()" type="text" name="recepient_alt" id="recepient_alt" maxlength="45" value="Search a User by Name or Role" size="55" />
                  </td>
                  <td width="155" height="40">
                    <input type="hidden" value="add_rec" name="formaction" />
                    <input type="submit" value="[+] Add User" style="font-size:10px">
                  </td>
              </tr>
            </table>
        </form>
        </div>
		<div style="padding:5px 0px">
        
         <div>
         <form method="post" action="">
        <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
        	<td class="tbl_sh">
            Role
            </td>
            <td class="tbl_sh">
            Name
            </td>
            <td class="tbl_sh">
            Region
            </td>
            <td class="tbl_sh"></td>
        </tr>
        <?php
		//loop through the batch to see if any users had been selected
		$sql_actors="SELECT idwftasks_transfers_usrto,idusrac,usrrolename,idusrrole,fname,lname,utitle,userteamzonename FROM wftasks_transfers_usrto
		INNER JOIN usrrole ON wftasks_transfers_usrto.usrroleid_to=usrrole.idusrrole
		INNER JOIN usrac ON usrrole.idusrrole=usrac.usrrole_idusrrole
		INNER JOIN usrteamzone ON usrrole.usrteamzone_idusrteamzone=usrteamzone.idusrteamzone
		WHERE wftasks_transfers_batch_idtransferbatch=".$_SESSION['batch']." ";
		$res_actors=mysql_query($sql_actors);
		$num_actors=mysql_num_rows($res_actors);
		$fet_actors=mysql_fetch_array($res_actors);
		
		if ($num_actors > 0)
			{
			$delist="";
		//echo $sql_actors;
			do {
		?>
        	<tr <?php 
// technocurve arc 3 php mv block2/3 start
echo " style=\"background-color:$mocolor\" onMouseOver=\"this.style.backgroundColor='$mocolor3'\" onMouseOut=\"this.style.backgroundColor='$mocolor'\"";
// technocurve arc 3 php mv block2/3 end
?>>
            	<td class="tbl_data">
                <label for="1">
		       	<input type="hidden" value="<?php echo $fet_actors['idusrac'];?>" name="actor[]" id="1"><strong><?php echo $fet_actors['usrrolename'];?></strong>
                </label>
                </td>
                <td class="tbl_data">
                 <?php echo $fet_actors['utitle']." ".$fet_actors['fname']." ".$fet_actors['lname'];?>
                </td>
                <td class="tbl_data">
                 <?php echo $fet_actors['userteamzonename'];?>
                </td>
                <td class="tbl_data" align="right" width="20">
                <a href="<?php echo $_SERVER['PHP_SELF'];?>?tabview=4&amp;action=delete&amp;rid=<?php echo $fet_actors['idwftasks_transfers_usrto'];?>" id="button_delete_vsmall"></a>
                </td>
             </tr>
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
			$delist=" OR idusrac!=".$fet_actors['idusrac'] ;
				} while ($fet_actors=mysql_fetch_array($res_actors));
				
			$_SESSION['delist']=$delist;
			$_SESSION['list_usrs']=$num_actors;
			} else {
			echo "<div class=\"msg_warning_small\">Please use the Search Box above to add Recepient(s) here...</div>";
			$_SESSION['delist']="";
			$_SESSION['list_usrs']=0;
			}
		?>
          <tr>
    	<td height="35" colspan="4" align="left" style="margin:0px; padding:0px">
        <div class="tbl_h" style="padding:5px; margin:10px 0px">
        Change Task Deadline</div>
        <div>
        <input <?php if ($num_actors < 1) { echo "disabled=\"disabled\" style=\"background-color:#cccccc\""; } ?> name="tktdate" type="text" class="small_field" id="tktdate" onClick="datetimepicker('tktdate');" value="<?php echo date("d/m/Y H:i",strtotime($timenowis)+(1*86400)); ?>" readonly="readonly" />

            <script language="javascript">
							$('#tktdate').datetimepicker({
							controlType: 'select',
							timeFormat: 'hh:mm',
							dateFormat: 'dd/mm/yy'
							});
							</script>
		</div>
			</td>
		</tr>
        <tr>
        	<td style="padding:0px" colspan="4">
            <div style="padding:15px 0px">
			<div class="tbl_h" style="padding:5px">Comment / Message</div>
			<div>
            <textarea <?php if ($num_actors < 1) { echo "disabled=\"disabled\" style=\"background-color:#cccccc\""; } ?> cols="44" rows="3" name="note"></textarea>
            </div>
            </div>
            </td>
        </tr>
             <tr>
             	<td colspan="4" height="40" align="left" style="padding:0px">
                <input type="hidden" name="formaction" value="transfer" />
                <input id="button_transfer"  onclick="return confirm('Transfer Tasks?');" <?php if ( (!isset($num_actors)) || ((isset($num_actors)) && ($num_actors<1)) || ($num_actors >$_SESSION['tasks_no']) ) { echo "disabled"; } ?> type="submit" style="font-size:14px; padding:5px" value="<?php if ($num_actors >$_SESSION['tasks_no']) { echo "Warning : Recepients Cannot be more than the Tasks"; } else { echo "Transfer Tasks Equally to the Above Recipients &raquo;"; } ?>">
                </td>
             </tr>
          </table>
          </form>
          </div>
        </div>
      </td>
      <td valign="top" width="50%">&nbsp;</td>
    </tr>
</table>
<?php 
} 

if (isset($transaction_ok))
	{
unset($_SESSION['tasks']);
?>
<script language="javascript">
window.location='pop_myteam_tasks_transferred.php';
</script>
<?php
exit;
	}
?>

</div>