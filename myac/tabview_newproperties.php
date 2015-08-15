<?php
require_once('../assets_backend/be_includes/check_login_easy.php');

//show properties
$sql_properties="SELECT * FROM wftskflow WHERE wfproc_idwfproc=".$_SESSION['wfselected']." LIMIT 1";
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
	
	if (isset($_POST['limit_to_zone']))
		{
		$chk_zone=1;
		} else {
		$chk_zone=0;
		}
	
	if (isset($_POST['limit_to_department']))
		{
		$chk_dpt=1;
		} else {
		$chk_dpt=0;
		}	
		
	if (isset($_POST['share_task']))
		{
		$chk_sharetask=1;
		} else {
		$chk_sharetask=0;
		}	
	
	$dtaskgroupname = mysql_escape_string(trim($_POST['taskgroupname']));	
	
	if (isset($_POST['pos_side']))
		{
	$pos_side=mysql_escape_string(trim($_POST['pos_side']));
		} else {
	$pos_side="0";	
		}
	
	if (isset($_POST['condition_1']))
		{
		$condition_1=mysql_escape_string(trim($_POST['condition_1']));
		}
	if (isset($_POST['condition_2']))
		{
		$condition_2=mysql_escape_string(trim($_POST['condition_2']));
		}
		//get the list order from the array exploded
	if ((isset($_POST['list_order']) ) && (strlen($_POST['list_order'])>4) )
		{
		$raw_list_order=explode('_',''.$_POST['list_order'].'');
		$get_list_order=$raw_list_order[0];
		$get_hpos=$raw_list_order[1];
		$get_symbolid=$raw_list_order[2];
		$get_wfid=$raw_list_order[3];
		$wflist=mysql_escape_string(trim($get_list_order));
		} else {
		$error_7= "<div class=\"msg_warning\">".$msg_warning_order."</div>";
		}
	
	//validate
	if (strlen($wfstepname) <1)
		{
		$error_1= "<div class=\"msg_warning\">".$msg_warning_wfname_required."</div>";
		}
	if (strlen($wfstepdesc) <1)
		{
		$error_2= "<div class=\"msg_warning\">".$msg_warning_desc_required."</div>";
		}
	//there is an exeception to the validation below for gateways which do not have TATs
		
	if ( (($dtat<1) || ($dtat=="")) && ($_SESSION['asymbol']!=5) )
		{
		$error_3 = "<div class=\"msg_warning\">".$msg_warning_tat_required."</div>";
		}
		
		
	if ( (strlen($dtat_cat)<2) && ($_SESSION['asymbol']!=5) )
		{
		$error_4 = "<div class=\"msg_warning\">".$msg_warning_tatcat_required."</div>";
		}
	
	if ($_SESSION['asymbol']==5)	
		{
		if ((strlen($condition_1)<1) || (strlen($condition_2)<1) )
			{
			$error_5 = "<div class=\"msg_warning\"></div>";
			}
		}
		
	//check if a task with a similar name for this process exist
	$sql_exists = "SELECT idwftskflow,wftskflowname FROM wftskflow WHERE wfproc_idwfproc=".$_SESSION['wfselected']." LIMIT 1";
	$res_exists = mysql_query($sql_exists);
	$fet_exists = mysql_fetch_array($res_exists);
	
	if ($fet_exists['wftskflowname']==$wfstepname)
		{
		$error_6= "<div class=\"msg_warning\">".$msg_warning_duplicate_wf."</div>";
		}
	
	//if task share is selected , then check if a name has been enter
	if ( ($chk_sharetask==1) && (strlen($dtaskgroupname)<2) )
		{
		$error_8="<div class=\"msg_warning\">Please enter a Valid Task Share Group Name</div>";
		}

	//exit;
	//process if no error
	if ( (!isset($error_1)) && (!isset($error_2)) && (!isset($error_3))  && (!isset($error_4)) && (!isset($error_5)) && (!isset($error_6)) && (!isset($error_7)) && (!isset($error_8))  )
			{
			
			//if a gateway, give the exception in calculating the timeframe
			if ($_SESSION['asymbol']==5)
				{
				$dtat_cat="Hours";
				$dtat=0;
				}
			
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
			//echo $wflist."<br>";
		//	if ((isset($wflist)) && ($wflist!="0.00")) // if the selected is a Not a Start Event
		//		{
		//	if ($wflist!="0.00") // if the selected is a Not a Start Event
			if ( (isset($wflist)) && ($wflist!="0.00"))
				{ //if wflist[55]
				if ($get_symbolid==2) //if new item is a task (2) & going under another task (2) then
					{  //[909]$pos_side $get_hpos
					$sql_listaft="SELECT listorder FROM wftskflow WHERE wfproc_idwfproc=".$_SESSION['wfselected']." AND listorder > ".$wflist."  ORDER BY listorder ASC LIMIT 1";
					$res_listaft=mysql_query($sql_listaft);
					$fet_listaft=mysql_fetch_array($res_listaft);
					$num_listaft=mysql_num_rows($res_listaft);
					//echo $sql_listaft;
				//if there is a record after the selected one, then it's ok to formulate the next list order
					if ($num_listaft > 0)
						{
						$next_list=number_format((($fet_listaft['listorder']+$wflist)/2),2);	
						} else { //else if there is NONE, then create the next list order manually with a wide range to accomodate as many steps as possible :)
						$next_list=number_format(($wflist+3),2);	
						}					
										
					} //[909]

/*			
				if ($get_symbolid==5) //if new item is a task (2) & going under exclusive gatway then
					{  //[309]
					if ($get_hpos == $pos_side) //if the position selected for new item is same as the one on list after
						{
						$sql_listaft="SELECT listorder FROM wftskflow WHERE h_pos='".$get_hpos."' AND wfproc_idwfproc=".$_SESSION['wfselected']." AND listorder > ".$wflist."  ORDER BY listorder ASC LIMIT 1";
						
						} else { //else if not the same side of the position (LEFT,RIGHT or CENTER), then do the following

						//use the new position from the get on SIDE A and find out how many records are there
						
						
						//then use check the number of times on the other side
						
						
						$sql_listaft="SELECT listorder FROM wftskflow WHERE wfproc_idwfproc=".$_SESSION['wfselected']." AND listorder > ".$wflist."  ORDER BY listorder ASC LIMIT 1";					
						}
					$res_listaft=mysql_query($sql_listaft);
					$fet_listaft=mysql_fetch_array($res_listaft);
					$num_listaft=mysql_num_rows($res_listaft);
					
					if ($num_listaft > 0)
						{
						$next_list=number_format((($fet_listaft['listorder']+$wflist)/2),2);	
						} else { //else if there is NONE, then create the next list order manually with a wide range to accomodate as many steps as possible :)
						$next_list=number_format(($wflist+3),2);	
						}	
					
					} //[309]
*/
				} else { //if 0.00, the proceed
					
					$next_list=number_format(($wflist+3),2);

				} //close if wflist[55]
			//now we can insert this record.
			$sql_wflow="INSERT INTO wftskflow (wfsymbol_idwfsymbol,wfproc_idwfproc,listorder,wftskflowname,wftskflowdesc,wftsktat,createdby,createdon,h_pos,limit_to_zone,limit_to_dpt,group_task_share) 
			VALUES ('".$_SESSION['asymbol']."','".$_SESSION['wfselected']."','".$next_list."','".$wfstepname."','".$wfstepdesc."','".$com_timeframe."','".$_SESSION['MVGitHub_idacname']."','".$timenowis."','".$pos_side."','".$chk_zone."','".$chk_dpt."','".$chk_sharetask."')";			
			$result_wflow=mysql_query($sql_wflow);
		//	echo $sql_wflow;
				
			if (false !== $result_wflow) //if successful
				{		
					//retreive
					$sql_lastid="SELECT idwftskflow,wfprocname,wftskflowname,idwfsymbol FROM wftskflow 
					INNER JOIN wfproc ON wftskflow.wfproc_idwfproc=wfproc.idwfproc
					INNER JOIN wfsymbol ON wftskflow.wfsymbol_idwfsymbol=wfsymbol.idwfsymbol
					WHERE wftskflow.createdby=".$_SESSION['MVGitHub_idacname']." ORDER BY idwftskflow DESC LIMIT 1";
					$res_lastid=mysql_query($sql_lastid);
					$fet_lastid=mysql_fetch_array($res_lastid);
					
					//if the above query was successful and it is a taskshare, 
					//then we need to populate all the roles on the taskshare table including the name in the edit mode
					if ($chk_sharetask==1)
						{
						//first, add the new group name and that is enough for now
						$sql_newgroup="INSERT INTO wfactorsgroupname (wftskflow_idwftskflow,groupname,createdon,createdby) 
						VALUES ('".$fet_lastid['idwftskflow']."','".$dtaskgroupname."','".$timenowis."','".$_SESSION['MVGitHub_idacname']."')";
						mysql_query($sql_newgroup);
						}
					
					
					//now, if it is a gateway, then ensure the 2 conditions are filled
					if ($_SESSION['asymbol']==5)
						{
						//insert the gateways
						$sql_condition_1="INSERT INTO wftskflow_gateways(wftskflow_idwftskflow,wfsymbol_idwfsymbol,gateway_vars,gateway_type,gateway_splitpoint,h_pos,createdon,createdby) 
						VALUES('0','".$_SESSION['asymbol']."','".$condition_1."','SPLIT','".$fet_lastid['idwftskflow']."','-1','".$timenowis."','".$_SESSION['MVGitHub_idacname']."')";
						mysql_query($sql_condition_1);
						
						$sql_condition_2="INSERT INTO wftskflow_gateways(wftskflow_idwftskflow,wfsymbol_idwfsymbol,gateway_vars,gateway_type,gateway_splitpoint,h_pos,createdon,createdby) 
						VALUES('0','".$_SESSION['asymbol']."','".$condition_2."','SPLIT','".$fet_lastid['idwftskflow']."','1','".$timenowis."','".$_SESSION['MVGitHub_idacname']."')";
						mysql_query($sql_condition_2);	
						}
						
					//update the record for wf_gatway if this item is falling after the gatway
					if ( ($_SESSION['asymbol']==2) && ($get_symbolid==5)) //if a task and after a gateway symbol
						{
						$sql_update="UPDATE wftskflow_gateways SET wftskflow_idwftskflow='".$fet_lastid['idwftskflow']."'
						WHERE gateway_splitpoint=".$get_wfid." AND h_pos=".$pos_side." LIMIT 1";
						mysql_query($sql_update);
						}
					
					//only do this if the green light is given above
					
					//insert default working hours for this new task/event
					$sql_wkdefaults = "SELECT idwfworkinghrsdefault,usrteamzone_idusrteamzone,wfworkingdays_idwfworkingdays,time_earliest,time_latest FROM wfworkinghrsdefault WHERE usrteamzone_idusrteamzone=".$_SESSION['MVGitHub_userteamzoneid']." LIMIT 3";
					$res_wkdefaults = mysql_query($sql_wkdefaults);
					$fet_wkdefaults = mysql_fetch_array($res_wkdefaults);
						
						do { //limit the query loop to 3 for weekdays and to weekend days
						
						$sql_insert_wkhrs = "INSERT INTO wfworkinghrs (wftskflow_idwftskflow,wfworkingdays_idwfworkingdays,time_earliest,time_latest,notapplicable) 
						VALUES ('".$fet_lastid['idwftskflow']."','".$fet_wkdefaults['wfworkingdays_idwfworkingdays']."','".$fet_wkdefaults['time_earliest']."','".$fet_wkdefaults['time_latest']."','0')";
						mysql_query($sql_insert_wkhrs);
						
						} while ($fet_wkdefaults = mysql_fetch_array($res_wkdefaults));
				
			//header("location:pop_workflow_properties.php?wftskid=".$fet_lastid['idwftskflow']."&title=".$fet_wf['wfprocname']." ".$fet_lastid['wftskflowname']."&symbol=". $fet_lastid['idwfsymbol']."&tabview=1");
			//echo  "pop_workflow_properties.php?wftskid=".$fet_lastid['idwftskflow']."&title=".$fet_lastid['wftskflowname']."&symbol=".$fet_lastid['idwfsymbol']."&tabview=1";
			?>
			<script language="javascript">
			window.location='<?php echo "pop_workflow_properties.php?wftskid=".$fet_lastid['idwftskflow']."&title=".$fet_lastid['wftskflowname']."&symbol=".$fet_lastid['idwfsymbol']."&tabview=1";?>';
            </script>            
            <?php
			exit;
			
			$okmsg_1="<span class=\"msg_success\">".$msg_changes_saved."</span>";
			
			} else { 
			
			$error_critical="<span class=\"msg_warning\">".$msg_warn_contactadmin."</span>";
			
			}
			
			} //close if no error
	
	} // close form action

?>
<div>
        <?php
//		if ( (isset($error_1)) || (isset($error_2)) || (isset($error_3)) || (isset($error_4)) || (isset($error_5)) || (isset($error_6)) )
//			{
			if (isset($error_1)) { echo $error_1; }
			if (isset($error_2)) { echo $error_2; }
			if (isset($error_3)) { echo $error_3; }
			if (isset($error_4)) { echo $error_4; }
			if (isset($error_5)) { echo $error_5; }
			if (isset($error_6)) { echo $error_6; }
			if (isset($error_7)) { echo $error_7; }
			if (isset($error_8)) { echo $error_8; }
			if (isset($error_critical)) { echo $error_critical; }
//			}
			
		if (isset($okmsg_1))
			{
			echo $okmsg_1;
			}
		
		?>
        
        </div>
<form method="post" action="" name="properties" autocomplete="off">
<div>
<table border="0" cellpadding="3" cellspacing="0">

<tr>
                	<td width="171" height="35" class="tbl_data">
                    <strong><?php echo $lbl_name;?></strong></td>
              <td width="336" height="35" class="tbl_data">
			  <input type="text" maxlength="60" size="40" value="<?php if (isset($_POST['wfname'])) {  echo $_POST['wfname'];} ?>" name="wfname" /></td>
           	  </tr>
              <?php
			  if ($_SESSION['asymbol']==5) //if exclusive gateway
			  		{
			  ?>
              <tr>
                	<td class="tbl_data">
                   <strong> <?php echo $lbl_pathscond;?></strong>
                    </td>
                    <td class="tbl_data">
                    <div class="hline">
                        <div class="text_small">
                        <?php echo $lbl_path1;?>
                        </div>
                        <div>
                        <input name="condition_1" type="text" id="condition_1" size="20" maxlength="30" />
                        </div>
                    </div>
                    <div class="hline">
                        <div class="text_small">
                        <?php echo $lbl_path2;?></div>
                        <div>
                        <input name="condition_2" type="text" id="condition_2" size="20" maxlength="30" />
                        </div>
                    </div>
                    </td>
              </tr>
              <?php
			  }
			  ?>
                <tr>
                	<td class="tbl_data">
                   <strong> <?php echo $lbl_description;?></strong>
                    </td>
                  <td class="tbl_data">
                    <textarea cols="30" rows="3" name="wfdesc"><?php if (isset($_POST['wfdesc'])){ echo $_POST['wfdesc'];} ?></textarea>
                  </td>
                </tr>
                 <tr>
                	<td class="tbl_data" height="35">
                    <strong><?php echo $lbl_tat;?></strong>                    </td>
                   <td height="35" class="tbl_data">
                   <?php 
				   if ($_SESSION['asymbol']!=5)
				   	{
				   ?>
                    <input onkeyup="res(this,numb);" type="text" value="<?php if ( (isset($_POST['tat'])) && ($_POST['tat'])) { echo $_POST['tat'];} ?><?php if (!isset($_POST['tat'])) { if ($fet_properties['wftsktat']>"172800"){ echo ($fet_properties['wftsktat']/(60 * 60 * 24));} else { echo ($fet_properties['wftsktat']/(60 * 60));} }?>" size="4" maxlength="2" name="tat" /> 
                    <select name="tat_cat">
                    <option <?php if ( (isset($_POST['tat_cat'])) && ($_POST['tat_cat']=="")){ echo "selected=\"selected\""; } ?> value="">---</option>
                    <option <?php if ( (isset($_POST['tat_cat'])) && ($_POST['tat_cat']=="Hours")){ echo "selected=\"selected\""; } ?> value="Hours">Hours</option>
                    <option <?php if ( (isset($_POST['tat_cat'])) && ($_POST['tat_cat']=="Days")){ echo "selected=\"selected\""; } ?> value="Days">Days</option>
                    </select>
                    <?php
                    } else {
					echo "N/A"
					?>
                    <input type="hidden" name="tat_cat"  value=""/>
                    <input type="hidden"  name="tat"  value=""/>
                    <?php
					}
					?>
                    </td>
      </tr>

              <tr>
					<td height="35" class="tbl_data">
					<strong><?php echo $lbl_order_place;?></strong>                    </td>
              <td height="35" class="tbl_data" style="padding:0px">
              		<table border="0" cellpadding="0" cellspacing="0">
                    	<tr>
                        	<td>
                <?php
					//echo $_SESSION['asymbol'];
					//get the list order from this process
					$sql_listorder="SELECT idwftskflow,listorder,wftskflowname,wfsymbol_idwfsymbol,h_pos
					 FROM wftskflow WHERE wfproc_idwfproc=".$_SESSION['wfselected']." ORDER BY listorder ASC,h_pos ASC";
					$res_listorder=mysql_query($sql_listorder);
					$num_listorder=mysql_num_rows($res_listorder);
					$fet_listorder=mysql_fetch_array($res_listorder);
				//	echo $sql_listorder;
					
					//if ($num_listorder ==1)//if there is only one echo "style=\"background-color:#cccccc\" readonly=\"readonly\"";
					//	{
					//	echo "<input type=\"hidden\" value=\"0.00\" name=\"list_order\"><input type=\"text\" readonly=\"readonly\" value=\"".$fval_sel_listo_dflt."\" name=\"lbldflt\" style=\"background-color:#cccccc;\">";
					//	} else {
							echo "<select name=\"list_order\" id=\"list_order\" onChange=\"getpos(this.value)\">\r\n";
							echo "<option value=\"\">---</option>";
							do {
							//loop and see if is a gateway
							$sql_gw="SELECT wftskflow_idwftskflow FROM wftskflow_gateways WHERE gateway_splitpoint=".$fet_listorder['idwftskflow']." LIMIT 1";
							$res_gw=mysql_query($sql_gw);
							$fet_gw=mysql_fetch_array($res_gw);
							//echo $sql_gw."<br>";
							echo "<option ";
							
							//disable depending on whether the following conditions exist
							if (($_SESSION['asymbol']==2) && ($fet_listorder['wfsymbol_idwfsymbol']==10))
								{ //if symbol is activity, then it cannot be after the close
								echo "disabled=\"disabled\"";
								}
							
							if (($_SESSION['asymbol']==10) && ($fet_listorder['wfsymbol_idwfsymbol']==1))
								{ //if symbol is stop, then it cannot be immediately after the start
								echo "disabled=\"disabled\"";
								}
								
							if (($_SESSION['asymbol']==10) && ($fet_listorder['wfsymbol_idwfsymbol']==5))
								{ //if symbol is stop, then it cannot be immediately after the start
								echo "disabled=\"disabled\"";
								}
								
							if (($_SESSION['asymbol']==5) && ($fet_listorder['wfsymbol_idwfsymbol']==1))
								{ //if symbol is stop, then it cannot be immediately after the start
								echo "disabled=\"disabled\"";
								}
								
							if (($_SESSION['asymbol']==5) && ($fet_listorder['wfsymbol_idwfsymbol']==10))
								{ //if symbol is stop, then it cannot be immediately after the start
								echo "disabled=\"disabled\"";
								}
								
							if (($_SESSION['asymbol']==5) && ($fet_listorder['wfsymbol_idwfsymbol']==5))
								{ //if symbol is gateway, then it cannot be immediately after another gateway
								echo "disabled=\"disabled\"";
								}
								
					/*		if (($_SESSION['asymbol']==2) && ($fet_gw['wftskflow_idwftskflow']>0))
								{ //if symbol is gateway, then it cannot be immediately after another gateway
								echo "disabled=\"disabled\"";
								}
					*/									
								/*if ($fet_listorder['listorder']=="0.00")
									{
									echo "disabled=\"disabled\"";  //disable the option to select if the array is the 0.00 first choice which is reserved
									}*/
									
							if (($fet_listorder['h_pos']!=0) && ($fet_listorder['wfsymbol_idwfsymbol']==5)) //if not at the center, then do not allow selection
								{
								echo "disabled=\"disabled\"";
								}
					
							echo " value=\"".$fet_listorder['listorder']."_".$fet_listorder['h_pos']."_".$fet_listorder['wfsymbol_idwfsymbol']."_".$fet_listorder['idwftskflow']."\">";
										
										if ($fet_listorder['wfsymbol_idwfsymbol']==5)
											{
											echo "*&nbsp;&nbsp;";
											}
										echo $lbl_after.$fet_listorder['wftskflowname'];
							
						//	echo " value=\"".$fet_listorder['listorder']."_".$fet_listorder['h_pos']."_".$fet_listorder['wfsymbol_idwfsymbol']."_".$fet_listorder['idwftskflow']."\">".$lbl_after.$fet_listorder['wftskflowname'];
							
							
							if ($fet_listorder['h_pos']=='-1')
								{
								echo "&nbsp;[L]";
								}
							
							if ($fet_listorder['h_pos']==0)
								{
								echo "&nbsp;[C]";
								}
								
							if ($fet_listorder['h_pos']==1)
								{
								echo "&nbsp;[R]";
								}
							
							echo " (".$fet_listorder['idwftskflow'].")";
							
							echo "</option>\r\n";
							} while ($fet_listorder=mysql_fetch_array($res_listorder));
							echo "</select>";
						//}
					?>
                    		</td>
                            <td>
                            <div id="posdiv"></div>
                            </td>
                            </tr>
                            </table>
                    </td>
      </tr>
			
            	<tr>
					<td height="35" class="tbl_data">
                    <strong><?php echo $lbl_ltdregion;?></strong>
                    </td>
         			 <td height="35" class="tbl_data">
    				<label for="limit">
                    <input type="checkbox" <?php if ((isset($_POST['limit_to_zone'])) && ($_POST['limit_to_zone']==1) ) { echo "checked=\"checked\""; } ?> value="1" name="limit_to_zone" id="limit" /> 
                    Limit actors interaction to a Region
                    </label>
                    </td>
      			<tr>
                <tr>
					<td height="35" class="tbl_data">
                    <strong>Limit to Department</strong>
                    </td>
         			 <td height="35" class="tbl_data">
    				<label for="limit2">
                    <input type="checkbox" value="1" <?php if ((isset($_POST['limit_to_department'])) && ($_POST['limit_to_department']==1) ) { echo "checked=\"checked\""; } ?> name="limit_to_department" id="limit2" /> 
                    Limit  actors interaction to a Department                    </label>
                    </td>
      			<tr>
                <tr>
					<td height="35" class="tbl_data"><strong>Group Task Sharing</strong></td>
         			 <td height="35" class="tbl_data">
                     <div>
    				<label for="share_task">
                    <input type="checkbox" value="1" <?php if ((isset($_POST['share_task'])) && ($_POST['share_task']==1) ) { echo "checked=\"checked\""; } ?> name="share_task" id="share_task" onclick = "c() ; showhide()" /> 
                    Visible to ALL actors <small><strong>( BUT only 1 actor can action it )</strong></small>
                    </label>
                    </div>
                   <div id="myrow" style="visibility:<?php if ( (isset($_POST['share_task'])) && ($_POST['share_task']==1) ) { echo "visible"; } else { echo "hidden"; }?>; background-color:#cccccc; padding:0px 5px">
                   <strong> Task Group Name : </strong><input type="text" maxlength="20" size="15" name="taskgroupname" />
                    </div>
                    </td>
      			<tr>
                <tr>
                	<td></td>
                	<td height="55">
  <table border="0" style="margin:15px 10px 5px 0px" cellpadding="0" cellspacing="0">
                        	<tr>
                            	<td>
                                <a href="#" onclick="document.forms['properties'].submit()" id="button_save"></a>                                </td>
                                <td style="padding:0px 0px 0px 10px">
                                <input type="hidden" value="save" name="formaction" />
                               <a href="#" id="button_cancel" onclick="parent.tb_remove();"></a>                                </td>
                            </tr>
                        </table>                    </td>
      </tr>
			</table>
</div>
</form>