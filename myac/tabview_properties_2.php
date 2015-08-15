<?php
require_once('../assets_backend/be_includes/check_login_easy.php');
$wfselected=1;
//show properties
$sql_properties="SELECT * FROM wftskflow WHERE idwftskflow=".$_SESSION['idflow']." AND wfproc_idwfproc=".$wfselected." LIMIT 1";
$res_properties=mysql_query($sql_properties);
$fet_properties=mysql_fetch_array($res_properties);
$num_properties=mysql_num_rows($res_properties);

if ($num_properties < 1)
	{
	echo $msg_warn_contactadmin;
	exit;
	}

if ( (isset($_POST['formaction'])) && ($_POST['formaction']=="save"))
	{
	//clean them up
	$wfstepname=mysql_escape_string(trim($_POST['wfname']));
	$wfstepdesc=mysql_escape_string(trim($_POST['wfdesc']));
	$dtat=mysql_escape_string(trim($_POST['tat']));
	$dtat_cat=mysql_escape_string(trim($_POST['tat_cat']));
	$wflist=mysql_escape_string(trim($_POST['list_order']));
	
	//validate
	if (strlen($wfstepname) <1)
		{
		$error_1= "<div class=\"msg_warning\">".$msg_warning_wfname_required."</span>";
		}
	if (strlen($wfstepdesc) <1)
		{
		$error_2= "<div class=\"msg_warning\">".$msg_warning_desc_required."</span>";
		}	
	if (($dtat<1) || ($dtat==""))
		{
		$error_3 = "<div class=\"msg_warning\">".$msg_warning_tat_required."</div>";
		}
	if (strlen($dtat_cat)<2)
		{
		$error_4 = "<div class=\"msg_warning\">".$msg_warning_tatcat_required."</div>";
		}
	

	//process if no error
	if ( (!isset($error_1)) && (!isset($error_2)) && (!isset($error_3))  && (!isset($error_4)) )
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
			
			
			//then, let fix the list order by checking what the user has selected
			if ($wflist!="0.00")
				{
			$sql_listaft="SELECT listorder FROM wftskflow WHERE wfproc_idwfproc=".$wfselected." AND listorder > ".$wflist." ORDER BY listorder ASC LIMIT 1";
			$res_listaft=mysql_query($sql_listaft);
			$fet_listaft=mysql_fetch_array($res_listaft);
			$num_listaft=mysql_num_rows($res_listaft);
			
				if ($num_listaft < 1) //of no record exists, then make the next list order a whole number away
					{
					$next_list=($wflist+1.00);
					
					} else { //else if there is a next item, then lets get the avarage number
					
					$next_list = ($wflist + (($fet_listaft['listorder']-$wflist)/2) );
					
					} //close if no record exists
				} else {
				$next_list = "0.00";
				}
			//now we can update this record.
			$sql_update="UPDATE wftskflow SET listorder='".$next_list."',
			wftskflowname='".$wfstepname."',
			wftskflowdesc='".$wfstepdesc."',
			wftsktat='".$com_timeframe."',
			modifiedby='".$_SESSION['MVGitHub_idacname']."',
			modifiedon='".$timenowis."' WHERE idwftskflow=".$_SESSION['idflow']." LIMIT 1";
			
			mysql_query($sql_update);
			
			$okmsg_1="<span class=\"msg_success\">".$msg_changes_saved."</span>";
			
			} //close if no error
	
	} // close form action

?>
<div>
        <?php
		if ( (isset($error_1)) || (isset($error_2)) || (isset($error_3)) || (isset($error_4)) )
			{
			if (isset($error_1)) { echo $error_1; }
			if (isset($error_2)) { echo $error_2; }
			if (isset($error_3)) { echo $error_3; }
			if (isset($error_4)) { echo $error_4; }
			}
			
		if (isset($okmsg_1))
			{
			echo $okmsg_1;
			}
		
		?>
        
        </div>
<form method="post" action="" name="properties">
<div>
<table border="0" cellpadding="3" cellspacing="0">

				<tr>
                	<td width="171" height="35" class="tbl_data">
                    <strong><?php echo $lbl_name;?></strong>
                    </td>
              <td width="336" height="35" class="tbl_data">
			  <input  type="text" maxlength="60" size="40" value="<?php echo $fet_properties['wftskflowname'];?>" name="wfname" /></td>
           	  </tr>
                <tr>
                	<td class="tbl_data">
                   <strong> <?php echo $lbl_description;?></strong>                   </td>
                  <td class="tbl_data">
                    <textarea cols="30" rows="3" name="wfdesc"><?php if (isset($_POST['wfdesc'])){ echo $_POST['wfdesc'];} if (!isset($_POST['wfdesc'])){ echo $fet_properties['wftskflowdesc']; }?></textarea>
                  </td>
                </tr>
                 <tr>
                	<td class="tbl_data" height="30">
                    <strong><?php echo $lbl_tat;?></strong>
                    </td>
                   <td height="30" class="tbl_data">
                    <input onkeyup="res(this,numb);" type="text" value="<?php if ( (isset($_POST['tat'])) && ($_POST['tat'])) { echo $_POST['tat'];} ?><?php if (!isset($_POST['tat'])) { if ($fet_properties['wftsktat']>"172800"){ echo ($fet_properties['wftsktat']/(60 * 60 * 24));} else { echo ($fet_properties['wftsktat']/(60 * 60));} }?>" size="4" maxlength="2" name="tat" /> 
                    <select name="tat_cat">
                    <option <?php if ( (isset($_POST['tat_cat'])) && ($_POST['tat_cat']=="")){ echo "selected=\"selected\""; } ?> value="">---</option>
                    <option <?php if ( (isset($_POST['tat_cat'])) && ($_POST['tat_cat']=="Hours")){ echo "selected=\"selected\""; } ?><?php if (!isset($_POST['tat_cat'])){ if ($fet_properties['wftsktat']<="172800"){ echo "selected=\"selected\""; } } ?> value="Hours">Hours</option>
                    <option <?php if ( (isset($_POST['tat_cat'])) && ($_POST['tat_cat']=="Days")){ echo "selected=\"selected\""; } ?><?php if (!isset($_POST['tat_cat'])){ if ($fet_properties['wftsktat']>"172800"){ echo "selected=\"selected\""; } } ?> value="Days">Days</option>
                    </select>
                    </td>
              </tr>

              <tr>
					<td height="30" class="tbl_data">
					<strong><?php echo $lbl_order_place;?></strong>
                    </td>
                    <td height="30" class="tbl_data">
                    <?php
					//get the list order from this process
					$sql_listorder="SELECT idwftskflow,listorder,wftskflowname FROM wftskflow WHERE wfproc_idwfproc=".$wfselected." ORDER BY listorder ASC";
					$res_listorder=mysql_query($sql_listorder);
					$num_listorder=mysql_num_rows($res_listorder);
					$fet_listorder=mysql_fetch_array($res_listorder);
					
					
					if ($num_listorder ==1)//if there is only one echo "style=\"background-color:#cccccc\" readonly=\"readonly\"";
						{
						echo "<input type=\"hidden\" value=\"0.00\" name=\"list_order\"><input type=\"text\" readonly=\"readonly\" value=\"".$fval_sel_listo_dflt."\" name=\"lbldflt\" style=\"background-color:#cccccc;\">";
						} else {
							echo "<select name=\"list_order\">";
								if ($fet_properties['wfsymbol_idwfsymbol']==2) //if it is a task, give the option of same  horizontal level
									{
									echo "<option value=\"".$fet_properties['listorder']."\">-- Same Level --</a>";
									}
							do {
							echo "<option ";
								/*if ($fet_listorder['listorder']=="0.00")
									{
									echo "disabled=\"disabled\"";  //disable the option to select if the array is the 0.00 first choice which is reserved
									}*/
							echo " value=\"".$fet_listorder['listorder']."\">".$lbl_after.$fet_listorder['wftskflowname']."</option>";
							} while ($fet_listorder=mysql_fetch_array($res_listorder));
							echo "</select>";
						}
					?></td>
              </tr>
			  <tr>
					<td height="30" class="tbl_data">
                    <strong><?php echo $lbl_ltdregion;?></strong>
                    </td>
                    <td class="tbl_data">
                    
                    </td>
<tr>
                	<td></td>
                	<td height="55">
  <table border="0" style="margin:15px 10px 5px 0px" cellpadding="0" cellspacing="0">
                        	<tr>
                            	<td>
                                <a href="#" onclick="document.forms['properties'].submit()" id="button_save"></a>                                </td>
                                <td style="padding:0px 0px 0px 10px">
                                <input type="hidden" value="save" name="formaction" />
                               <a href="<?php echo $_SERVER['PHP_SELF'];?>" id="button_cancel" onclick="return confirm('<?php echo $msg_prompt_sure_cancel;?>');"></a>                                </td>
                            </tr>
                        </table>                    </td>
      </tr>
			</table>
</div>
</form>