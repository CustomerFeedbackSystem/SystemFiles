<?php
require_once('../assets_backend/be_includes/check_login_easy.php');

//post
if ( (isset($_POST['formaction'])) && ($_POST['formaction']=="addwp"))
	{
	//clean em up
	$yearfrom=preg_replace('/[^a-z\-_0-9\.:@\/\s]/i','',mysql_escape_string(trim($_POST['yrfrom'])));
	$yearto=preg_replace('/[^a-z\-_0-9\.:@\/\s]/i','',mysql_escape_string(trim($_POST['yrto'])));
	
	//validate keyed in fields
	//from cannot be greater than to
	if ($yearfrom > $yearto)
		{
		$error1="<div class=\"msg_warning\">- ".$msg_warning_yfyt."</div>";
		}
	
	//both fields are keyed in
	if ( (strlen($yearfrom) < 4) || (strlen($yearto) < 4) )
		{
		$error2="<div class=\"msg_warning\">- ".$msg_warning_afr."</div>";
		}
	
	$action_time=preg_replace('/[^a-z\-_0-9\.:\/\s]/i','',mysql_escape_string(trim($_POST['timeaction'])));
	if (strlen($action_time) > 1) { $action_time_post=date("Y-m-d H:i:s",strtotime($action_time)); }
	
	if (strlen($action_time) < 10) 
		{
		$error3="<div class=\"msg_warning\">- ".$msg_warning_date."</div>";
		}
	
	//check if duplicate exist
	if ( (!isset($error1)) && (!isset($error2)) && (!isset($error3)) )
		{
		//check the db for wp by this organisation for the same period
		$sql_duplicate="SELECT idwpheader FROM wpheader WHERE yearfrom=".$yearfrom." AND usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']." LIMIT 1";
		$res_duplicate=mysql_query($sql_duplicate);
		$num_duplicate=mysql_num_rows($res_duplicate);
		
		if ($num_duplicate > 0)
			{
			$error4="<div class=\"msg_warning\">- ".$msg_warning_duplicate_wf."</div>";
			
			} else {
			//insert the new WP
			$sql="INSERT INTO wpheader (tktactivitytype_idtktactivitytype, usrteam_idusrteam, yearfrom, yearto, enteredby, enteredon, createdon, createdby, modifiedon, modifiedby) VALUES 
			('14', '".$_SESSION['MVGitHub_idacteam']."', '".$yearfrom."', '".$yearto."', '".$_SESSION['MVGitHub_idacname']."', '".$action_time."','".$timenowis."', '".$_SESSION['MVGitHub_idacname']."', NULL, NULL)";
			mysql_query($sql);
			
			//retrieve this wp details
			$sql_id="SELECT idwpheader FROM wpheader WHERE createdby=".$_SESSION['MVGitHub_idacname']." ORDER BY idwpheader DESC LIMIT 1";
			$res_id=mysql_query($sql_id);
			$fet_id=mysql_fetch_array($res_id);
			
			//acknowledge
			$msg_ok="<span class=\"msg_success\">".$ins_wp."</span>";
			$success=1;
			} 
		
		}
	
	//if all above ok, then create record and redirect to edit to key in activities
	
	
	}
?>
<link href="../assets_backend/css/style.css" rel="stylesheet" type="text/css">


<div>
	<div class="bg_section">
	<?php echo $fet_heading['modulename']; ?> &raquo; <?php echo $fet_heading['submodule']; ?>
	</div>
    <div style="margin:10px 0px 0px 0px">
<?php
if (!isset($success)) {
?>  
  <div>
                        <?php 
                        if (isset($error1)) { echo $error1;}
                        if (isset($error2)) { echo $error2;}
                        if (isset($error3)) { echo $error3;}
						if (isset($error4)) { echo $error4;}
                        ?>
                        </div>
<form method="post" name="wp" action="">
<table border="0" cellpadding="5" cellspacing="0">
<tr>
	<td  colspan="2" class="table_header">
    <?php echo $lbl_newwp;?>    </td>
</tr>
	<tr>
    	<td height="35" class="tbl_data" >
        <?php echo $lbl_year_from;?>        </td>
        <td height="35" class="tbl_data" >
        <input onKeyUp="res(this,numb);" onblur="document.wp.yrto.value=(eval(document.wp.yrfrom.value) + 1);" type="text" maxlength="4" name="yrfrom" size="10">
        </td>
     </tr>
	<tr>
        <td height="35" class="tbl_data" >
        <?php echo $lbl_year_to;?>        </td>
         <td height="35" class="tbl_data" >
       <input  style="background-color:#F4F4F4"  tabindex="-1" readonly="readonly"  type="text" maxlength="4" name="yrto" size="10">       </td>
    </tr>
    <tr>
                    	<td height="40" class="tbl_data">
                          
                          <?php
						echo $lbl_reportby;
						?>
                         </td>
                      <td height="40" class="tbl_data">
                        <input type="text" readonly="readonly" style="background-color:#CCCCCC" value="<?php echo $_SESSION['MVGitHub_acname'];?>" />
                        </td>
                  </tr>
                     <tr>
                    	<td height="40" class="tbl_data">
                          <?php
						echo $lbl_timeaction;
						?>
                         </td>
                       <td height="40" class="tbl_data">
                       <input size="15" onClick="displayDatePicker('timeaction');" name="timeaction" style="background-color:#F5F5F5" type="text" id="timeaction" value="<?php if (isset($_SESSION['timeaction'])) { echo $_SESSION['timeaction']; } ?>" readonly="readonly"> 
                        <img src="../assets_backend/btns/cal.gif" alt="Pick a date" width="30" height="30" border="0" align="absmiddle" onClick="displayDatePicker('timeaction');">                        
                        </td>
                  </tr>
    <tr>
    	<td>
        </td>
        <td height="45">
        <input type="hidden" value="addwp" name="formaction">
        <a href="#" onclick="document.forms['wp'].submit()" id="button_btn_step_next"></a>        </td>
    </tr>
</table>
</form>
<?php } else { 
echo $msg_ok;
?>
<div style="margin:30px 0px 30px 10px">
<a href="<?php echo $_SERVER['PHP_SELF'];?>?uction=edit_submod&amp;view=44&amp;submod=44&amp;wpid=<?php echo $fet_id['idwpheader'];?>" id="btn_proceeddetails"></a>
</div>
<?php } ?>
	</div>
</div>    
