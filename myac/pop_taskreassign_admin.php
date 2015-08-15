<?php
require_once('../assets_backend/be_includes/config.php');
//require_once('../Connections/connSystem.php');
//mysql_select_db($database_connSystem, $connSystem);

require_once('../assets_backend/be_includes/check_login.php');

//include("fckeditor/fckeditor.php");

//get the title
if (isset($_GET['title']))
	{
	$_SESSION['wtitle']=preg_replace('/[^a-z\-_0-9\.:\/]/i','',mysql_escape_string(trim($_GET['title'])));
	}

if (isset($_GET['tkt']))
	{
	$_SESSION['tktid']=preg_replace('/[^a-z\-_0-9\.:\/]/i','',mysql_escape_string(trim($_GET['tkt'])));	
	}
	
if (isset($_GET['retask']))
	{
	$_SESSION['retask']=preg_replace('/[^a-z\-_0-9\.:\/]/i','',mysql_escape_string(trim($_GET['retask'])));	
	}	

if (isset($_GET['display_reass']))
	{
	$_SESSION['display_reass']=mysql_escape_string($_GET['display_reass']);
	}	
	
if ( (isset($_POST['formaction'])) && ($_POST['formaction']=="adjust") ) 	
	{
	//clean up
	$wflowstep=trim($_POST['workflow_step']);
	$wfactor=trim($_POST['actor']);
	$wfdesc=trim($_POST['tktdesc']);
	$wfdeadline=trim($_POST['tktdate']);
	$tktdate=preg_replace('/[^a-z\-_0-9\.:\/\s]/i','',mysql_escape_string(trim($_POST['tktdate'])));
			$tktdate_trans=str_replace('/','-',$wfdeadline);

			$tktdate_fin=date("Y-m-d H:i:s",strtotime($tktdate_trans));
	
	//first, validate input for the workflow id and the account_usr
	if ($wflowstep < 1)
		{
		$error1=1;
		echo "<br><br><br><div class=msg_warning>Invalid Workflow Step</div>";
		}
	
	if ($wfactor < 1)
		{
		$error2=1;
		echo "<br><br><br><div class=msg_warning>Invalid Actor</div>";
		}
	
	if ( (!isset($error1)) && (!isset($error2)) )
		{
		//then first retrieve the usrac of this actor from the db
		$sql_usrid="SELECT idusrac FROM usrac WHERE usrrole_idusrrole=".$wfactor." LIMIT 1";
		$res_usrid=mysql_query($sql_usrid);
		$fet_usrid=mysql_fetch_array($res_usrid);
		
		//then record this change in the audit table
		$sql_audit="INSERT INTO audit_wftasksadj (idwftasks,
		usrrole_idusrrole,
		usrac_idusrac,
		sender_idusrrole,
		sender_idusrac,
		timedone,
		bywho
		)
		
		SELECT wftasks.idwftasks,
		wftasks.usrrole_idusrrole,
		wftasks.usrac_idusrac,
		wftasks.sender_idusrrole,
		wftasks.sender_idusrac,
		'".$timenowis."',
		'".$_SESSION['MVGitHub_idacname']."'
		FROM wftasks WHERE wftasks.idwftasks=".$_SESSION['retask']."";
		mysql_query($sql_audit);
		
		
		//then update the table record
		$sql_update="UPDATE wftasks SET 
		usrrole_idusrrole='".$wfactor."',
		usrac_idusrac='".$fet_usrid['idusrac']."',
		wftskflow_idwftskflow='".$wflowstep."',
		taskdesc='".$wfdesc."',
		timedeadline='".$tktdate_fin."'
		WHERE idwftasks=".$_SESSION['retask']."";
		mysql_query($sql_update);
		
		
		echo "<br><br><br><div class=msg_success>Task Reassigned / ReOrdered Successfully</div>";
	//echo $sql_update;
		}
	
	}
	
//echo "<span><br><br><br><br>".$sql_history."</span>";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Task Details</title>
<link href="../assets_backend/css/style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../scripts/jquery.js"></script>
<script type="text/javascript" src="../scripts/jquery-ui.min.js"></script>
<script type="text/javascript" src="../scripts/jquery-ui-timepicker-addon_.js"></script>
<script language="javascript" type="text/javascript">
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
		
	
	function getfrom(fromId) {		
		
		var strURL="ajax_from.php?workflow_step="+fromId;
		var req = getAJAXHTTPREQ();
			
		if (req) {
			
			req.onreadystatechange = function() {
				if (req.readyState == 4) {
					// only if "OK"
					if (req.status == 200) {						
						document.getElementById('fromdiv').innerHTML=req.responseText;						
					} else {
						alert("There was a problem while using XMLHTTP:\n" + req.statusText);
					}
				}				
			}			
			req.open("GET", strURL, true);
			req.send(null);
		}		
	}
	

</script>	
</head>
<body>
<table border="0" width="100%" cellpadding="2" cellspacing="2" bgcolor="#FFFFFF" >
	<tr>
    	<td style="padding:0px 0px 30px 0px" colspan="3">
        <div class="tbl_sh" style="position:fixed; margin:0px; padding:0px ; top:0px; width:100%">
        	<table border="0" cellpadding="0" cellspacing="0" width="100%">
            	<tr>
                	<td width="900">
					<?php echo $lbl_taskhistory;?>
                    </td>
                  <td>
                   <a href="#" onClick="parent.tb_remove();" id="button_closewin"></a>
                    </td>
				</tr>
			</table>
		</div>            
        </td>
    </tr>
    <?php
	if ( (isset($_SESSION['display_reass'])) && ($_SESSION['display_reass']=="yes") )
		{
//get the details of this task
$sql_task="SELECT idtktin, wftasks.idwftasks, wftasks.usrrole_idusrrole, tasksubject, usrrolename, wftasks.sender_idusrrole, tktin.refnumber, wftasks.taskdesc, wftasks.timeinactual, wftasks.timeactiontaken, wftskstatustypes.wftskstatuslbl, wftasks.wftskflow_idwftskflow, usrac.fname, usrac.lname, usrac.usrname, wfprocname, idwfproc,wftskflowname, idwftskflow
FROM wftasks
INNER JOIN tktin ON wftasks.tktin_idtktin = tktin.idtktinPK
INNER JOIN usrrole ON wftasks.usrrole_idusrrole = usrrole.idusrrole
INNER JOIN wftskflow ON wftasks.wftskflow_idwftskflow = wftskflow.idwftskflow
LEFT JOIN wftskstatustypes ON wftasks.wftskstatustypes_idwftskstatustypes = wftskstatustypes.idwftskstatustypes
INNER JOIN usrac ON wftasks.usrrole_idusrrole = usrac.usrrole_idusrrole
INNER JOIN wfproc ON wftskflow.wfproc_idwfproc = wfproc.idwfproc
WHERE wftasks.idwftasks =".$_SESSION['retask']." ";
$res_task=mysql_query($sql_task);
$num_task=mysql_num_rows($res_task);
$fet_task=mysql_fetch_array($res_task);		
//	echo "<br><br><br><br>".$sql_task;	

/*** Store Proc ID on Session **/
$_SESSION['thisWFsession']=$fet_task['idwfproc'];

//then find out if the workflow step is assigned to a role or a group
$sql_rg="SELECT idwfactors,usrrole_idusrrole,usrgroup_idusrgroup FROM wfactors WHERE wftskflow_idwftskflow=".$fet_task['wftskflow_idwftskflow']." LIMIT 1";
$res_rg=mysql_query($sql_rg);
$fet_rg=mysql_fetch_array($res_rg);
//echo "<br><br><br><br>". $sql_rg;
if ($fet_rg['usrrole_idusrrole']>0)
	{
	$actor_type="ROLE";
	}
	
if ($fet_rg['usrgroup_idusrgroup']>0)
	{
	$actor_type="GROUP";
	}
	
	?>
<div>
	<tr>
    	<td colspan="3" class="text_small">
<a href="<?php echo $_SERVER['PHP_SELF'];?>?display_reass=no" style="font-weight:bold">
Back to List
</a>
        </td>
    </tr>
    <tr>
    	<td>
        </td>
        <td colspan="2" class="topmenu">
        Explore Workflow Below...</td>
    </tr>
    <tr>
    	<td valign="top" width="400" >
       <div class="border_thick">
       <form method="post" action="" name="reassign">
       		<table border="0" cellpadding="2" cellspacing="0" width="100%"  > 
				<tr>
                	<td height="30" class="tbl_data"><strong>Task Subject</strong></td>
                <td height="30" class="tbl_data"><?php echo $fet_task['tasksubject'];?></td>
              </tr>
              <tr>
                	<td height="30" class="tbl_data"><strong>Task Description</strong></td>
                  <td height="30" class="tbl_data"><?php echo $fet_task['taskdesc'];?></td>
              </tr>
              <tr>
                	<td height="30" class="tbl_data"><strong>Ticket Number</strong></td>
                  <td height="30" class="tbl_data"><?php echo $fet_task['refnumber'];?></td>
              </tr>
              <tr>
                	<td height="30" class="tbl_data"><strong>Workflow Name</strong></td>
                  <td height="30" class="tbl_data"><?php 
				  echo $fet_task['wfprocname']; 
				  ?></td>
              </tr>
              <tr>
                	<td height="30" class="tbl_data"><strong>Current Workflow Step</strong></td>
                  <td height="30" class="tbl_data" style="color:#CC0000">[<?php echo $fet_task['idwftskflow'];?>] <?php echo $fet_task['wftskflowname'];?></td>
              </tr>
              
                <tr>
                	<td height="30" class="tbl_data"><strong>Task Sent From</strong></td>
                  <td height="30" class="tbl_data">
                  <?php
				  $sql_sender="SELECT DISTINCT usrac.fname,usrac.lname,usrac.usrname,usrrole.usrrolename FROM usrrole INNER JOIN usrac ON usrrole.idusrrole=usrac.usrrole_idusrrole WHERE usrrole.idusrrole=".$fet_task['sender_idusrrole']."";
				  $res_sender=mysql_query($sql_sender);
				  $fet_sender=mysql_fetch_array($res_sender);
				  echo $fet_sender['usrrolename'];
				  echo "<br><small>".$fet_sender['usrname']." ".$fet_sender['fname']." ".$fet_sender['lname'] ."</small>";
				  ?>
                  <div>                  </div>                  </td>
              </tr>
                <tr>
                	<td height="30" class="tbl_data"><strong>On Who's Desk (Actor)</strong></td>
                  <td height="30" class="tbl_data">
                  <?php
				  echo $fet_task['usrrolename'];
				   echo "<br><small>".$fet_task['usrname']." ".$fet_task['fname']." ".$fet_task['lname'] ."</small>";
				  ?>
                   <div>                  </div>                  </td>
              </tr>
              <tr>
              	<td height="5" colspan="2" bgcolor="#333333" class="topmenu">Change To...</td>
              </tr>  
                <tr>
                	<td colspan="2" height="30" class="tbl_data"><strong>Workflow Step </strong></td>
			  </tr>                    
                  <td height="30" colspan="2" class="tbl_data">
                  <?php
			/* if ($actor_type=="ROLE")
				  	{
				  	$sql_wfs="SELECT idwftskflow,wftskflowname,
					(SELECT DISTINCT wftskflow_idwftskflow FROM wfactors WHERE usrrole_idusrrole=".$fet_task['usrrole_idusrrole']." AND wfactors.wftskflow_idwftskflow=wftskflow.idwftskflow ) as wfactor
					 FROM wftskflow WHERE wfproc_idwfproc=".$fet_task['idwfproc']." ORDER BY listorder ASC";
					}
				if ($actor_type=="GROUP")
				  	{
				  	$sql_wfs="SELECT idwftskflow,wftskflowname,
					(SELECT DISTINCT wfactors.wftskflow_idwftskflow FROM wfactors INNER JOIN link_userrole_usergroup ON wfactors.usrgroup_idusrgroup=link_userrole_usergroup.usrgroup_idusrgroup  
					WHERE  link_userrole_usergroup.usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']." AND link_userrole_usergroup.usrrole_idusrrole=".$fet_task['usrrole_idusrrole']." AND link_userrole_usergroup.usrgroup_idusrgroup=".$fet_rg['usrgroup_idusrgroup']." AND wfactors.wftskflow_idwftskflow=wftskflow.idwftskflow ) as wfactor
					FROM wftskflow WHERE wfproc_idwfproc=".$fet_task['idwfproc']." ORDER BY listorder ASC";
					}
				*/	
				/*	$sql_wfs="SELECT idwftskflow,wftskflowname,
					(SELECT DISTINCT wftskflow_idwftskflow FROM wfactors WHERE usrrole_idusrrole=".$fet_task['usrrole_idusrrole']." AND wfactors.wftskflow_idwftskflow=wftskflow.idwftskflow ) as wfactor,
					(SELECT DISTINCT wftskflow_idwftskflow FROM wfactors WHERE usrrole_idusrrole=".$fet_task['usrrole_idusrrole']." AND wfactors.wftskflow_idwftskflow=wftskflow.idwftskflow ) as wfactor2
					 FROM wftskflow WHERE wfproc_idwfproc=".$fet_task['idwfproc']." ORDER BY listorder ASC";
					*/
					$sql_wfs="SELECT idwftskflow,wftskflowname,listorder,wfsymbol_idwfsymbol
					FROM wftskflow WHERE wfproc_idwfproc=".$fet_task['idwfproc']." ORDER BY listorder ASC";
					$res_wfs=mysql_query($sql_wfs);
					$fet_wfs=mysql_fetch_array($res_wfs);
					//echo $sql_wfs;
				  ?>
                    <select name="workflow_step" id="workflow_step" onChange="getfrom(this.value)">
                    <option value="">----</option>
                    <?php
					do {
					?>
                    <option <?php if ( ($fet_wfs['wfsymbol_idwfsymbol']=='1') || ($fet_wfs['wfsymbol_idwfsymbol']=='10') ) { echo "disabled=\"disabled\""; } ?> value="<?php echo $fet_wfs['idwftskflow'];?>" title="<?php echo $fet_wfs['wftskflowname'];?>">[<?php echo $fet_wfs['idwftskflow'];?>] <?php echo substr($fet_wfs['wftskflowname'],0,20);?></option>
                    <?php
					} while ($fet_wfs=mysql_fetch_array($res_wfs));
					?>
                    </select>                    </td>
              </tr>
              <tr>
                	<td height="30" colspan="2" class="tbl_data"><strong>Select Actor</strong></td>
              </tr>
              <tr>
                  <td height="30" class="tbl_data" colspan="2">
                  <div id="fromdiv">
                  </div>
                  </td>
              </tr>
              <tr>
                	<td height="30" colspan="2" class="tbl_data"><strong>New Task Deadline</strong></td>
              </tr>
              <tr>
              	<td colspan="2">
                <?php
				$one_day_togo=date("Y-m-d H:i:s",strtotime($timenowis) + (1*86400)); //1 days ago
				?>
                <input name="tktdate" type="text" class="small_field" id="tktdate" onClick="datetimepicker('tktdate');" value="<?php echo date("d/m/Y H:i",strtotime($one_day_togo)); ?>" readonly="readonly" />

            <script language="javascript">
							$('#tktdate').datetimepicker({
							controlType: 'select',
							timeFormat: 'hh:mm',
							dateFormat: 'dd/mm/yy'
							});
							</script>
                </td>
              </tr>
              <tr>
                	<td height="30" colspan="2" class="tbl_data"><strong>Task Description</strong></td>
              </tr>
              <tr>
              	<td colspan="2">
              <textarea name="tktdesc" cols="50" rows="3" id="tktdesc">[TRANSFERRED FROM <?php echo $fet_task['fname']." ".$fet_task['lname'];?> by ADMIN ] <?php echo $fet_task['taskdesc'];?></textarea>
                </td>
              </tr>
              <tr>
                <td height="50" colspan="2">
                <input type="hidden" value="adjust" name="formaction" />
                <a href="#" onclick="document.forms['reassign'].submit()" id="button_save"></a>                </td>
              </tr>
            </table>
       </form>
       </div>
        <div class="border_thick" style="margin:10px 0px 0px 0px">
        <table border="0" width="100%">
         <tr>
              	<td height="5" colspan="2" bgcolor="#333333" class="topmenu"><?php
				  echo "( ".$fet_task['usrrolename'];
				   echo " - <small>".$fet_task['usrname']." ".$fet_task['fname']." ".$fet_task['lname'] ."</small> )";
				  ?> also found in ...</td>
              </tr>
        	
            <?php
			$sql_act="SELECT DISTINCT idwftskflow,wftskflowname FROM wftskflow 
			INNER JOIN wfactors ON wftskflow.idwftskflow =wfactors.wftskflow_idwftskflow 
			WHERE wfactors.usrrole_idusrrole=".$fet_task['usrrole_idusrrole']." 
			AND wfproc_idwfproc=".$fet_task['idwfproc']."
			ORDER BY wftskflow.listorder ASC ";
			$res_act=mysql_query($sql_act);
			$fet_act=mysql_fetch_array($res_act);
			if (mysql_num_rows($res_act) > 0)
				{
			do {
			?>
        	<tr>
            	<td width="19%" class="tbl_data">
                <?php
				echo $fet_act['idwftskflow'];
				?>                </td>
             <td width="81%" class="tbl_data">
                 <?php
				echo $fet_act['wftskflowname'];
				?>                </td>
            </tr>
            <?php
				} while ($fet_act=mysql_fetch_array($res_act));
				} else {
				echo "<tr><td colspan=2 class=msg_warning>This User is not a Valid Actor in any of the Steps</td></tr>";
				}
			?>
        </table>
        </div>
        </td>
        <td valign="top" bgcolor="#ffffff"  width="250">
        <div class="tbl_h2" style="padding:5px;">Workflow Step &gt;&gt;</div>
        <div>
        <iframe src="pop_taskreassign_admin_1.php" width="100%" frameborder="0" height="500" marginheight="0" marginwidth="0"></iframe>
        </div>
        </td>
        <td valign="top" bgcolor="#ffffff"  width="250">
        <div class="tbl_h2" style="padding:5px;">&gt;&gt; Actors that can receive tasks...</div>
        <div>
        <iframe name="reassign_2" src="pop_taskreassign_admin_2.php" width="100%" frameborder="0" height="500" marginheight="0" marginwidth="0"></iframe>
        </div>
        </td>
    </tr>
    </div>
    <?php
	 } else {
	?>
    <div>
    <?php
$sql_list="SELECT idtktinPK,wftasks.idwftasks,tasksubject,usrrolename,tktin.refnumber,wftasks.timeinactual,wftasks.timeactiontaken,wftskstatustypes_idwftskstatustypes,wftskstatustypes.wftskstatuslbl,wftasks.wftskflow_idwftskflow,usrac.fname,usrac.lname,usrac.usrname,sender_idusrrole
FROM wftasks
INNER JOIN tktin ON wftasks.tktin_idtktin = tktin.idtktinPK
INNER JOIN usrrole ON wftasks.usrrole_idusrrole=usrrole.idusrrole
INNER JOIN wftskflow ON wftasks.wftskflow_idwftskflow=wftskflow.idwftskflow
LEFT JOIN wftskstatustypes ON wftasks.wftskstatustypes_idwftskstatustypes=wftskstatustypes.idwftskstatustypes
INNER JOIN usrac ON wftasks.usrrole_idusrrole=usrac.usrrole_idusrrole
WHERE wftasks.tktin_idtktin=".$_SESSION['tktid']." GROUP BY wftasks.idwftasks ORDER BY wftskflow.listorder ASC";

$res_list=mysql_query($sql_list);
$num_list=mysql_num_rows($res_list);
$fet_list=mysql_fetch_array($res_list);
//echo "<br><br><br>".$sql_list;
?>
<tr>
	<td colspan="3" valign="top">
		<table border="0" cellpadding="2" cellspacing="0" width="100%">
        	<tr>
            	<td class="tbl_sh">
                Step #
                </td>
                <td class="tbl_sh">
                Subject
                </td>
                <td class="tbl_sh">
                Time Received
                </td>
                <td class="tbl_sh">
                Time Actioned
                </td>
                <td class="tbl_sh">
                On Who's Desk</td>
                <td class="tbl_sh">
                Status
                </td>
            </tr>
<?php
do {
?>
<tr <?php 
// technocurve arc 3 php mv block2/3 start
echo " style=\"background-color:$mocolor\" onMouseOver=\"this.style.backgroundColor='$mocolor3'\" onMouseOut=\"this.style.backgroundColor='$mocolor'\"";
// technocurve arc 3 php mv block2/3 end
?>>
            	<td class="tbl_data">
                <?php
				if ( ($fet_list['sender_idusrrole']!=2) &&  ( ($fet_list['wftskstatustypes_idwftskstatustypes']==0) || ($fet_list['wftskstatustypes_idwftskstatustypes']==6) ) )
					{
				?>
                <a href="<?php echo $_SERVER['PHP_SELF'];?>?display_reass=yes&amp;retask=<?php echo $fet_list['idwftasks'];?>">
                <?php echo $fet_list['wftskflow_idwftskflow'];?>
                </a>
                	<?php } else { ?>
                    <span style="background-color:#009900; font-weight:bold; color:#FFFFFF; padding:5px"><?php echo $fet_list['wftskflow_idwftskflow'];?></span>
                    <?php } ?>
                </td>
                <td class="tbl_data">
                <?php
				//echo $fet_list['wftskstatustypes_idwftskstatustypes']."<br>";
				echo $fet_list['tasksubject'];
				?>
                </td>
                <td class="tbl_data">
                <?php
				echo date("D, M d, Y H:i",strtotime($fet_list['timeinactual']));
				?>
                </td>
                <td class="tbl_data">
                <?php
				if ($fet_list['timeactiontaken']!='0000-00-00 00:00:00')
				{
				echo date("D, M d, Y H:i",strtotime($fet_list['timeactiontaken']));
				} else {
				echo "---";
				}
				?>
                </td>
                <td class="tbl_data">
                <?php
				echo $fet_list['usrrolename'];
				?><br /><small>(<?php echo $fet_list['usrname']." ".$fet_list['fname']." ".$fet_list['lname'];?>)</small>
                </td>
                <td class="tbl_data">
                <?php
				if ($fet_list['wftskstatustypes_idwftskstatustypes']==0)
					{
					echo "<span style=\"color:#ff0000;font-weight:bold\">No Action Taken</span>";
					} else {
					echo $fet_list['wftskstatuslbl'];
					}
				?>
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
	} while ($fet_list=mysql_fetch_array($res_list));
?> 		</table>
	</td>
</tr>
    </div>
    <?php
	}
	?>
</table>
</body>
</html>