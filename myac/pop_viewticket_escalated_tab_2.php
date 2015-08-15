<?php
require_once('../assets_backend/be_includes/check_login_easy.php');

if ($tktistaken==1) //this variable is coming from the pop_viewticketfollowup.php parent include file
	{
	echo "<span class=\"msg_warning\">".$msg_warn_violation."</span>";
	exit;
	}

if ((isset($_POST['form_action'])) && ($_POST['form_action']=="assign"))
	{
	//clean up the variable
	$assign_to=preg_replace('/[^a-z\-_0-9\.:@\/\s]/i','',mysql_escape_string(trim($_POST['assignto'])));
	
	//validate
	if ($assign_to < 1)
		{
		$error="<div class=\"msg_warning\">".$msg_warning_selectusr."</div>";
		}
	
	if (!isset($error)) //if no error, then process
		{
			//insert 
			$sql_assign="INSERT INTO tktactivityowner (idtktinPK,idusrac,addedon,addedby ) 
			VALUES ('".$_SESSION['tktid']."','".$assign_to."','".$timenowis."','".$_SESSION['MVGitHub_idacname']."')";
//			mysql_query($sql_assign);
			
			//log this as an activity
			$sql_logactivity="INSERT INTO tktactivity (idtktesclevel, idtktinPK, idtktactivitytype, activity_date, activity_details, entered_by, entered_by_role, addedby, addedon, modifiedby, modifiedon) 
			VALUES ('1', '".$_SESSION['tktid']."', '1', '".$timenowis."', '".$_SESSION['MVGitHub_userteamshortname']." WSB Ticket Assignment to a WorkFlow','".$_SESSION['MVGitHub_idacname']."', '".$_SESSION['MVGitHub_iduserrole']."', '".$_SESSION['MVGitHub_idacname']."','".$timenowis."', '0000-00-00 00:00:00','0')";
//			mysql_query($sql_logactivity);
			
			$msg = "<div style=\"margin:20px 0px 0px 0px\" class=\"msg_success\">".$msg_changes_saved."</div>";
			$success="OK";
		}
		
	}
?>
<div style="padding:10px 20px 10px 0px">
	<div class="table_header">
        <?php echo $lbl_tktassignment;?>
    </div>
    
    <?php
if (!isset($_POST['form_action'])) 
	{
	?>
	<div class="msg_instructions" style="margin:5px 0px 10px 0px">
    <?php echo $msg_no_activity_r1;?>
    </div>
    <?php } ?>
    <?php
	if (isset($error)) { echo $error; }
	if (isset($msg)) { echo $msg; }
	?>
    <?php
	if (!isset($success))
	{
	?>
	<div>
   <form method="post" name="assign">
   	<table border="0">
    	<tr>
        	<td><?php echo $lbl_astktto;?></td>
            <td>
            <select name="assignto">
            	<option value="">-- Select Workflow --</option>
            <?php
         //active Workflows Only
				$sql_list="SELECT idwfproc,wfprocname,wfstatus FROM wfproc WHERE usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']." ";
				$res_list=mysql_query($sql_list);
				$fet_list=mysql_fetch_array($res_list);
				$num_list=mysql_num_rows($res_list);
				
				if ($num_list>0) 
					{
					do {
						
						echo "<option ";
							if ($fet_list['wfstatus']==0) { echo " disabled=\"disabled\" "; }
							echo " value=\"".$fet_list['idwfproc']."\">".$fet_list['wfprocname']."</option>";
							
					} while ($fet_list=mysql_fetch_array($res_list));
					
					} else {
					echo "<option disabled=\"\">No Workflows Found</option>";
					}
			
			?>
           	</select>
            </td>
        </tr>
        <tr>
        	<td></td>
            <td height="50">
            <input type="hidden" value="assign" name="form_action" />                           
             <a href="#" onclick="document.forms['assign'].submit()" id="btn_conasgn"></a>
            </td>
        </tr>
    </table>
   </form>
    </div>
<?php } ?>    
</div>