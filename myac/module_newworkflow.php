<?php
require_once('../assets_backend/be_includes/check_login_easy.php');
	
//process this form
if ( (isset($_POST['formaction'])) && ($_POST['formaction']=="add") )
	{
	//first, clean up the data
	$ddesc=mysql_escape_string(trim($_POST['wfdesc']));
	$dtat=mysql_escape_string(trim($_POST['tat']));
	$dtat_cat=mysql_escape_string(trim($_POST['tat_cat']));
	$dwfname=mysql_escape_string(trim($_POST['wfname']));
	$dwtype=mysql_escape_string(trim($_POST['wftype']));
	
	
	//check for duplicate workflow for this userteamzone
	$sql_exists = "SELECT idwfproc FROM wfproc WHERE usrteamzone_idusrteamzone=".$_SESSION['MVGitHub_userteamzoneid']." AND wfprocname='".$dwfname."' AND usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']." LIMIT 1";
	$res_exists = mysql_query($sql_exists);
	$num_exists = mysql_num_rows($res_exists);
	//echo $sql_exists;
	if (isset($_POST['mobacc']))
		{
		$dmobacc=1;
		} else {
		$dmobacc=0;
		}
	//second, look for the required fields
	
	if (strlen($dwfname) < 1)
		{
		$error = "<div class=\"msg_warning\">".$msg_warning_wfname_required."</div>";
		}
	if (strlen($ddesc) < 1)
		{
		$error_1 = "<div class=\"msg_warning\">".$msg_warning_desc_required."</div>";
		}
	if (($dtat<1) || ($dtat==""))
		{
		$error_2 = "<div class=\"msg_warning\">".$msg_warning_tat_required."</div>";
		}
	if (strlen($dtat_cat)<2)
		{
		$error_3 = "<div class=\"msg_warning\">".$msg_warning_tatcat_required."</div>";
		}
	if ($num_exists > 0) //if record already exists
		{
		$error_4 = "<div class=\"msg_warning\">".$msg_warning_duplicate_wf."</div>";
		}
	//if there is no error, then process	
		if ( (!isset($error)) && (!isset($error_1)) && (!isset($error_2)) && (!isset($error_3)) && (!isset($error_4)) )
			{
			
			//first, lets fix the hours or days to seconds depending on the choice selected
			if ($dtat_cat=="Days")
				{
				$com_timeframe = $dtat*24*60*60;
				}
			if ($dtat_cat=="Hours")
				{
				$com_timeframe = $dtat*60*60;
				}
								
			//now, lets add the record
			$sql_wf="INSERT INTO wfproc (usrteam_idusrteam,usrteamzone_idusrteamzone,wfprocname,wfproctat,wfprocdesc,mobileaccess,createdby,createdon,wftype_idwftype) 
			VALUES ('".$_SESSION['MVGitHub_idacteam']."','".$_SESSION['MVGitHub_userteamzoneid']."','".$dwfname."','".$com_timeframe."','".$ddesc."','".$dmobacc."','".$_SESSION['MVGitHub_idacname']."','".$timenowis."','".$dwtype."')";
			mysql_query($sql_wf);
//echo $sql_wf;
//exit;
			//retrieve the last id by this user so as to redirect properly
			$sql_redid="SELECT idwfproc FROM wfproc WHERE createdby=".$_SESSION['MVGitHub_idacname']." ORDER BY idwfproc DESC LIMIT 1";
			$res_redid=mysql_query($sql_redid);
			$fet_redid=mysql_fetch_array($res_redid);
			
			//add the first start icon for this new workflow
			// NOTE : The symbol value for start event is 1 from wfsymbol table
			// NOTE : List Order is 0 for the highest item
			// NOTE : Workflow Name is "Ticket IN from Customer"
			$sql_wflow="INSERT INTO wftskflow (wfsymbol_idwfsymbol,wfproc_idwfproc,listorder,wftskflowname,wftskflowdesc,wftsktat,createdby,createdon) 
			VALUES ('1','".$fet_redid['idwfproc']."','0.00','Ticket IN','".$ddesc."','0','".$_SESSION['MVGitHub_idacname']."','".$timenowis."')";
			mysql_query($sql_wflow);
			
			//retrieve the wftskflow id
			$sql_tfid="SELECT idwftskflow FROM wftskflow WHERE createdby=".$_SESSION['MVGitHub_idacname']." ORDER BY idwftskflow DESC LIMIT 1";
			$res_tfid=mysql_query($sql_tfid);
			$fet_tfid=mysql_fetch_array($res_tfid);
			
			
			//insert default working hours for this new task/event
			$sql_wkdefaults = "SELECT idwfworkinghrsdefault,usrteamzone_idusrteamzone,wfworkingdays_idwfworkingdays,time_earliest,time_latest FROM wfworkinghrsdefault WHERE usrteamzone_idusrteamzone=".$_SESSION['MVGitHub_userteamzoneid']." LIMIT 3";
			$res_wkdefaults = mysql_query($sql_wkdefaults);
			$fet_wkdefaults = mysql_fetch_array($res_wkdefaults);
				
				do { //limit the query loop to 3 for weekdays and to weekend days
				
				$sql_insert_wkhrs = "INSERT INTO wfworkinghrs (wftskflow_idwftskflow,wfworkingdays_idwfworkingdays,time_earliest,time_latest,notapplicable) 
				VALUES ('".$fet_tfid['idwftskflow']."','".$fet_wkdefaults['wfworkingdays_idwfworkingdays']."','".$fet_wkdefaults['time_earliest']."','".$fet_wkdefaults['time_latest']."','0')";
				mysql_query($sql_insert_wkhrs);
				
				} while ($fet_wkdefaults = mysql_fetch_array($res_wkdefaults));
			?>
			<script language="javascript">
			alert ('<?php echo $msg_workflow_added;?>');
			window.location='<?php echo "".$_SERVER['PHP_SELF']."?uction=edit_workflow&wf=".$fet_redid['idwfproc']."";?>';
			</script>		
			<?php
			} //close if no error
		
	} //close if form is submitted
?>
<div>
	<div class="bg_section">
    <?php echo $fet_heading['modulename']; ?> &raquo; <a href="<?php echo $_SERVER['PHP_SELF'];?>?uction=view_submod"><?php echo $fet_heading['submodule']; ?></a>
    </div>
    <div>
    	<div style="padding:5px 5px 10px 5px">
		<div class="tbl_h" style="padding:5px 0px 5px 5px"><?php echo $lbl_wfbasicinfo; ?></div>
        <div>
        <div>
        <?php		
		if ( (isset($error)) || (isset($error_1)) || (isset($error_2)) || (isset($error_3)) || (isset($error_4)) )
			{
			if (isset($error)) { echo $error; }
			if (isset($error_1)) { echo $error_1; }
			if (isset($error_2)) { echo $error_2; }
			if (isset($error_3)) { echo $error_3; }
			if (isset($error_4)) { echo $error_4; }
			}
		?>
        </div>
       <form method="post" action="" name="wfbasics">
        	<table border="0" cellpadding="3" cellspacing="0" class="border_thick">
				<tr>
                	<td width="171" height="35" class="tbl_data">
                    <strong><?php echo $lbl_wfname;?></strong>
                    </td>
              <td width="336" height="35" class="tbl_data">
			  <input name="wfname" type="text" id="wfname" value="<?php if (isset($_POST['dwfname'])){ echo $_POST['dwfname']; }?>" size="40" maxlength="60" /></td>
           	  </tr>
              <tr>
              	<td class="tbl_data">
                <strong><?php 
				echo $lbl_wftype;
				?></strong>
                </td>
                <td class="tbl_data">
                <select name="wftype">
                <?php
				$sql_wftype="SELECT idwftype,wftypelbl,wftypedesc,recstatus FROM wftype";
				$res_wftype=mysql_query($sql_wftype);
				$fet_wftype=mysql_fetch_array($res_wftype);
				
				do {
				?>
                <option <?php if ($fet_wftype['recstatus']==0) { echo "disabled=\"disabled\""; }?> value="<?php echo $fet_wftype['idwftype'];?>" title="<?php echo $fet_wftype['wftypedesc'];?>"><?php echo $fet_wftype['wftypelbl'];?></option>
                <?php
				} while ($fet_wftype=mysql_fetch_array($res_wftype));
				?>
                </select>
                </td>
              </tr>
                <tr>
                	<td class="tbl_data">
                   <strong> <?php echo $lbl_description;?></strong>
                   </td>
                  <td class="tbl_data">
                    <textarea cols="30" rows="3" name="wfdesc"><?php if (isset($_POST['wfdesc'])){ echo $_POST['wfdesc'];}  else { echo "Ticket is Received from Customer either via Mobile, Over the Counter or Telephone Call";}?></textarea>
                  </td>
                </tr>
                 <tr>
                	<td class="tbl_data" height="30">
                    <strong><?php echo $lbl_tat;?></strong>
                    </td>
                   <td height="30" class="tbl_data">
                    <input onkeyup="res(this,numb);" type="text" value="<?php if ( (isset($_POST['tat'])) && ($_POST['tat'])) { echo $_POST['tat'];} ?>" size="4" maxlength="2" name="tat" /> 
                    <select name="tat_cat">
                    <option <?php if ( (isset($_POST['tat_cat'])) && ($_POST['tat_cat']=="")){ echo "selected=\"selected\""; } ?> value="">---</option>
                    <option <?php if ( (isset($_POST['tat_cat'])) && ($_POST['tat_cat']=="".$lbl_hours."")){ echo "selected=\"selected\""; } ?> value="Hours"><?php echo $lbl_hours;?></option>
                    <option <?php if ( (isset($_POST['tat_cat'])) && ($_POST['tat_cat']=="".$lbl_days."")){ echo "selected=\"selected\""; } ?> value="Days"><?php echo $lbl_days;?></option>
                    </select>
                   </td>
              </tr>
              <tr>
                	<td class="tbl_data" height="30">
                    <strong><?php echo $lbl_status;?></strong>
                    </td>
                    <td class="tbl_data">
                    <select name="wfstate">
                    	 <option value="0" >OFF (Inactive)</option>
                         <option value="1" disabled="disabled">ON (Active &amp; Running)</option>
                    </select>
                    <?php
					
					?>
                    </td>
              </tr>
                 <tr>
                	<td class="tbl_data">
                   <!-- <strong><?php //echo $lbl_mobileaccess;?></strong>-->                    </td>
                   <td class="tbl_data">
                    
                   </td>
              </tr>
                <tr>
                <td></td>
                	<td valign="top">
              <table border="0" style="margin:5px 10px 5px 0px">
                        	<tr>
                            	<td>
                                <input type="hidden" value="1" name="mobacc"   />
                                <a href="#" onclick="document.forms['wfbasics'].submit()" id="button_save"></a>
                                </td>
                                <td style="padding:0px 0px 0px 10px">
                                <input type="hidden" value="add" name="formaction" />
                               <a href="<?php echo $_SERVER['PHP_SELF'];?>?uction=view_submod" id="button_cancel" onclick="return confirm('<?php echo $msg_prompt_sure_cancel;?>');"></a>
                                </td>
                            </tr>
                        </table>
                    </td>
              </tr>
			</table>
		</form>
        </div>
        </div>
        <div>
        	<div class="tbl_h" style="padding:5px 0px 5px 5px"><?php echo $lbl_wfdesign;?></div>
            <div class="msg_instructions">
            	<?php echo $msg_active_oft;?>
            </div>
        </div>
    </div>
</div>