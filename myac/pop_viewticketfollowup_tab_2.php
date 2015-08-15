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
			mysql_query($sql_assign);
			
			//log this as an activity
			$sql_logactivity="INSERT INTO tktactivity (idtktesclevel, idtktinPK, idtktactivitytype, activity_date, activity_details, entered_by, entered_by_role, addedby, addedon, modifiedby, modifiedon) 
			VALUES ('1', '".$_SESSION['tktid']."', '1', '".$timenowis."', 'Ticket Assignment to a WAG Officer','".$_SESSION['MVGitHub_idacname']."', '".$_SESSION['MVGitHub_iduserrole']."', '".$_SESSION['MVGitHub_idacname']."','".$timenowis."', '0000-00-00 00:00:00','0')";
			mysql_query($sql_logactivity);
			
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
            	<option value="">-- Select User --</option>
            	<option value="<?php echo $_SESSION['MVGitHub_idacname'];?>"><?php echo $lbl_astome."&nbsp;(".$_SESSION['MVGitHub_acname'].")";?></option>
            <?php
            //enable list if user is perm_global
			//drop down other people who are in this group/team other than the current user
			if ($is_perm_global==1)
				{
				$sql_list="SELECT idusrac,usrname,acstatus FROM usrac WHERE usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']." ORDER BY usrname ASC";
				$res_list=mysql_query($sql_list);
				$fet_list=mysql_fetch_array($res_list);
				
					do {
						
						//hide from list if it is the current user
						
						if ($fet_list['idusrac']!=$_SESSION['MVGitHub_idacname'])
							{
								echo "<option ";
									if ($fet_list['acstatus']==0) { echo " disabled=\"disabled\" "; }
								echo " value=\"".$fet_list['idusrac']."\">".$fet_list['usrname']."</option>";
							} 	
							
					} while ($fet_list=mysql_fetch_array($res_list));
				
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