<?php
require_once('../assets_backend/be_includes/check_login.php');

if ( (isset($_POST['formaction'])) && ($_POST['formaction']=="save"))
	{
	//clean them up
	$wfstepname=mysql_escape_string(trim($_POST['wfname']));
	$wfstepdesc=mysql_escape_string(trim($_POST['wfdesc']));
	$dtat=mysql_escape_string(trim($_POST['tat']));
	$dtat_cat=mysql_escape_string(trim($_POST['tat_cat']));
		if (isset($_POST['is_milestone']))
		{
		$is_milestone=1;
		} else {
		$is_milestone=0;
		}
	
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
	
	$dtaskgroupname=mysql_escape_string(trim($_POST['taskgroupname']));	
	
	if (isset($_POST['pos_side'])) { $pos_side=mysql_escape_string(trim($_POST['pos_side'])); } else { $pos_side=0; }

		//get the list order from the array exploded
	if (isset($_POST['list_order'])) 
		{	
			$raw_list_order=explode('_',''.$_POST['list_order'].''); 
			$get_list_order=$raw_list_order[0];
			$wflist=mysql_escape_string(trim($get_list_order));
		}
	
	$wfprevlist=mysql_escape_string(trim($_POST['hidden_prev_listorder']));
	
	//validate
	if (strlen($wfstepname) <1)
		{
		$error_1= "<div class=\"msg_warning_small\">".$msg_warning_small_wfname_required."</span>";
		}
	if (strlen($wfstepdesc) <1)
		{
		$error_2= "<div class=\"msg_warning_small\">".$msg_warning_small_desc_required."</span>";
		}	
	if (($dtat<1) || ($dtat==""))
		{
		$error_3 = "<div class=\"msg_warning_small\">".$msg_warning_small_tat_required."</div>";
		}
	if (strlen($dtat_cat)<2)
		{
		$error_4 = "<div class=\"msg_warning_small\">".$msg_warning_small_tatcat_required."</div>";
		}
	
	//if task share is selected , then check if a name has been enter
	if ( ($chk_sharetask==1) && (strlen($dtaskgroupname)<2) )
		{
		$error_5="<div class=\"msg_warning_small\">Please enter a Valid Task Share Group Name</div>";
		}

	//process if no error
	if ( (!isset($error_1)) && (!isset($error_2)) && (!isset($error_3))  && (!isset($error_4))  && (!isset($error_5)) )
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
			
			
		//	echo "if (".$wflist."!=0.00) && (".$wfprevlist."!=".$wflist.")<br>";
		//	exit;
			
			//if the list order has not changed and it is not the start
			if ( (isset($wflist)) && ($wfprevlist!=$wflist) && ($_SESSION['asymbol']!=1))
			 //if the list order has not changed and it is not the start
				{
			
				$sql_listaft="SELECT listorder FROM wftskflow WHERE wfproc_idwfproc=".$_SESSION['wfselected']." AND listorder > ".$wflist." ORDER BY listorder ASC LIMIT 1";
				$res_listaft=mysql_query($sql_listaft);
				$fet_listaft=mysql_fetch_array($res_listaft);
				$num_listaft=mysql_num_rows($res_listaft);
			
				if ($num_listaft < 1) //of no record exists, then make the next list order a whole number away
					{
					
					$next_list=number_format(($wflist+1.00),2);
					
					} else { //else if there is a next item, then lets get the avarage number
					
					$next_list = number_format(($wflist + (($fet_listaft['listorder']-$wflist)/2) ),2);
					
					} //close if no record exists
					
					//now we can update this record.
					$sql_update="UPDATE wftskflow SET listorder='".$next_list."',
					wftskflowname='".$wfstepname."',
					wftskflowdesc='".$wfstepdesc."',
					wftsktat='".$com_timeframe."',
					limit_to_zone='".$chk_zone."',
					limit_to_dpt='".$chk_dpt."',
					group_task_share='".$chk_sharetask."',
					modifiedby='".$_SESSION['MVGitHub_idacname']."',
					modifiedon='".$timenowis."',h_pos='".$pos_side."',
					is_milestone='".$is_milestone."'
					WHERE idwftskflow=".$_SESSION['idflow']." LIMIT 1";
					
					//if $chk_sharetask checked, then update or insert a new record
					if ($chk_sharetask==1)
						{ //[12]
						//first check if it existed below
						$sql_groupis="SELECT idwfactorsgroupname FROM wfactorsgroupname WHERE wftskflow_idwftskflow=".$_SESSION['idflow']." LIMIT 1";
						$res_groupis=mysql_query($sql_groupis);
						$num_groupis=mysql_num_rows($res_groupis);
						$fet_groupis=mysql_fetch_array($res_groupis);
						
						if ($num_groupis > 0) //[A] if exists, then just update
							{
							$sql_updatelbl="UPDATE wfactorsgroupname SET groupname='".$dtaskgroupname."' WHERE wftskflow_idwftskflow=".$_SESSION['idflow']." LIMIT 1";
							mysql_query($sql_updatelbl);
							
							$idgroupname=$fet_groupis['idwfactorsgroupname'];// this is the group is if the record exists
							
							} else { //else insert new record
							
							$sql_newgroup="INSERT INTO wfactorsgroupname (wftskflow_idwftskflow,groupname,createdon,createdby) 
							VALUES ('".$fet_lastid['idwftskflow']."','".$dtaskgroupname."','".$timenowis."','".$_SESSION['MVGitHub_idacname']."')";
							mysql_query($sql_newgroup);
							
							//then retrieve that id for later use
							$sql_idgroup="SELECT idwfactorsgroupname FROM wfactorsgroupname WHERE createdby=".$_SESSION['MVGitHub_idacname']." ORDER BY idwfactorsgroupname DESC LIMIT 1";
							$res_idgroup=mysql_query($sql_idgroup);
							$fet_idgroup=mysql_fetch_array($res_idgroup);
							
							$idgroupname=$fet_idgroup['idwfactorsgroupname'];// otherwise, this is the NEW groups id
							
							//check the roles already in this workflow and populate the wfactorsgroup table
							////IF A USER ROLE
							$sql_actors_role="SELECT usrrole_idusrrole FROM wfactors WHERE wftskflow_idwftskflow=".$_SESSION['idflow']." AND usrrole_idusrrole>0 ORDER BY idwfactors ASC";
							$res_actors_role=mysql_query($sql_actors_role);
							$num_actors_role=mysql_num_rows($res_actors_role);
							$fet_actors_role=mysql_fetch_array($res_actors_role);
							
							//if there are records roles, then do the necessary
								if ($num_actors_role > 0)
									{
									//then loop and insert
									do {
										//as you insert, first precheck before inserting 
										$sql_exists_role="SELECT usrrole_idusrrole FROM wfactorsgroup WHERE usrrole_idusrrole=".$fet_actors_role['usrrole_idusrrole']." AND wftskflow_idwftskflow=".$_SESSION['idflow']."";
										$res_exists_role=mysql_query($sql_exists_role);
										$num_exists_role=mysql_num_rows($res_exists_role);
										
										if ($num_exists_role < 1) //if there is no record, then insert
											{
											$sql_insert_role="INSERT INTO wfactorsgroup (wfactorsgroupname_idwfactorsgroupname,usrrole_idusrrole,wftskflow_idwftskflow,createdby,createdon) 
											VALUES ('".$idgroupname."','".$fet_actors_role['usrrole_idusrrole']."','".$_SESSION['idflow']."','".$_SESSION['MVGitHub_idacname']."','".$timenowis."')";
											mysql_query($sql_insert_role);
											}										
										
										} while ($fet_actors_role=mysql_fetch_array($res_actors_role));
									} //if actor_role is > 0
							
							} //close [A]
												
						
						////IF B USER ROLE
							$sql_actors_group="SELECT usrgroup_idusrgroup FROM wfactors WHERE wftskflow_idwftskflow=".$_SESSION['idflow']." AND usrgroup_idusrgroup>0 LIMIT 1";
							$res_actors_group=mysql_query($sql_actors_group);
							$num_actors_group=mysql_num_rows($res_actors_group);
							$fet_actors_group=mysql_fetch_array($res_actors_group);
							
							//if there are records roles, then do the necessary
								if ($num_actors_group > 0)
									{
									//get the loop of the user roles in that group
									$sql_group_roles="SELECT usrrole_idusrrole FROM link_userrole_usergroup WHERE usrgroup_idusrgroup=".$fet_actors_group['usrgroup_idusrgroup']." AND usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']." ORDER BY idlink_userac_usergroup ASC";
									$res_group_roles=mysql_query($sql_group_roles);
									$num_group_roles=mysql_num_rows($res_group_roles);
									$fet_group_roles=mysql_fetch_array($res_group_roles);
									
									//then loop and insert
									do {
										//as you insert, first precheck before inserting 
										$sql_exists_group="SELECT usrrole_idusrrole FROM wfactorsgroup WHERE usrrole_idusrrole=".$fet_group_roles['usrrole_idusrrole']." AND wftskflow_idwftskflow=".$_SESSION['idflow']."";
										$res_exists_group=mysql_query($sql_exists_group);
										$num_exists_group=mysql_num_rows($res_exists_group);
										
										if ($num_exists_group < 1) //if there is no record, then insert
											{
											$sql_insert_group="INSERT INTO wfactorsgroup (wfactorsgroupname_idwfactorsgroupname,usrrole_idusrrole,wftskflow_idwftskflow,createdby,createdon) 
											VALUES ('".$idgroupname."','".$fet_group_roles['usrrole_idusrrole']."','".$_SESSION['idflow']."','".$_SESSION['MVGitHub_idacname']."','".$timenowis."')";
											mysql_query($sql_insert_group);
											}										
										
										} while ($fet_group_roles=mysql_fetch_array($res_group_roles));
									} //if actor_group is > 0
						
						
						} //[12]
					
					//if $chk_sharetask is not checked, then delete the record if it exists
					if ($chk_sharetask==0)
						{
						//delete the group name
						$sql_updatelbl="DELETE FROM wfactorsgroupname WHERE wftskflow_idwftskflow=".$_SESSION['idflow']." LIMIT 1";
						mysql_query($sql_updatelbl);
						
						//delete the roles themselves in that group
						$sql_deleteroles="DELETE FROM wfactorsgroup WHERE wftskflow_idwftskflow=".$_SESSION['idflow']."";
						mysql_query($sql_deleteroles);
						}
					
					
				} else {
				
				//then the next_list order should not be touched
				
					//now we can update this record.
					$sql_update="UPDATE wftskflow SET 
					wftskflowname='".$wfstepname."',
					wftskflowdesc='".$wfstepdesc."',
					wftsktat='".$com_timeframe."',
					limit_to_zone='".$chk_zone."',
					limit_to_dpt='".$chk_dpt."',
					group_task_share='".$chk_sharetask."',
					modifiedby='".$_SESSION['MVGitHub_idacname']."',
					modifiedon='".$timenowis."',h_pos='".$pos_side."',
					is_milestone='".$is_milestone."' 
					WHERE idwftskflow=".$_SESSION['idflow']." LIMIT 1";
				
					//if $chk_sharetask checked, then update the record
					if ($chk_sharetask==1)
						{
						$sql_updatelbl="UPDATE wfactorsgroupname SET groupname='".$dtaskgroupname."' WHERE wftskflow_idwftskflow=".$_SESSION['idflow']." LIMIT 1";
						mysql_query($sql_updatelbl);
						}
					
					
					//if $chk_sharetask checked, then update or insert a new record
					if ($chk_sharetask==1)
						{ //[12]
						//first check if it existed below
						$sql_groupis="SELECT idwfactorsgroupname FROM wfactorsgroupname WHERE wftskflow_idwftskflow=".$_SESSION['idflow']." LIMIT 1";
						$res_groupis=mysql_query($sql_groupis);
						$num_groupis=mysql_num_rows($res_groupis);
						$fet_groupis=mysql_fetch_array($res_groupis);
						
						if ($num_groupis > 0) //[A] if exists, then just update
							{
							$sql_updatelbl="UPDATE wfactorsgroupname SET groupname='".$dtaskgroupname."' WHERE wftskflow_idwftskflow=".$_SESSION['idflow']." LIMIT 1";
							mysql_query($sql_updatelbl);
							
							$idgroupname=$fet_groupis['idwfactorsgroupname'];// this is the group is if the record exists
							
							} else { //else insert new record
							
							$sql_newgroup="INSERT INTO wfactorsgroupname (wftskflow_idwftskflow,groupname,createdon,createdby) 
							VALUES ('".$_SESSION['idflow']."','".$dtaskgroupname."','".$timenowis."','".$_SESSION['MVGitHub_idacname']."')";
							mysql_query($sql_newgroup);
							
							//then retrieve that id for later use
							$sql_idgroup="SELECT idwfactorsgroupname FROM wfactorsgroupname WHERE createdby=".$_SESSION['MVGitHub_idacname']." ORDER BY idwfactorsgroupname DESC LIMIT 1";
							$res_idgroup=mysql_query($sql_idgroup);
							$fet_idgroup=mysql_fetch_array($res_idgroup);
							
							$idgroupname=$fet_idgroup['idwfactorsgroupname'];// otherwise, this is the NEW groups id
							
							//check the roles already in this workflow and populate the wfactorsgroup table
							////IF A USER ROLE
							$sql_actors_role="SELECT usrrole_idusrrole FROM wfactors WHERE wftskflow_idwftskflow=".$_SESSION['idflow']." AND usrrole_idusrrole>0 ORDER BY idwfactors ASC";
							$res_actors_role=mysql_query($sql_actors_role);
							$num_actors_role=mysql_num_rows($res_actors_role);
							$fet_actors_role=mysql_fetch_array($res_actors_role);
							
							//if there are records roles, then do the necessary
								if ($num_actors_role > 0)
									{
									//then loop and insert
									do {
										//as you insert, first precheck before inserting 
										$sql_exists_role="SELECT usrrole_idusrrole FROM wfactorsgroup WHERE usrrole_idusrrole=".$fet_actors_role['usrrole_idusrrole']." AND wftskflow_idwftskflow=".$_SESSION['idflow']."";
										$res_exists_role=mysql_query($sql_exists_role);
										$num_exists_role=mysql_num_rows($res_exists_role);
										
										if ($num_exists_role < 1) //if there is no record, then insert
											{
											$sql_insert_role="INSERT INTO wfactorsgroup (wfactorsgroupname_idwfactorsgroupname,usrrole_idusrrole,wftskflow_idwftskflow,createdby,createdon) 
											VALUES ('".$idgroupname."','".$fet_actors_role['usrrole_idusrrole']."','".$_SESSION['idflow']."','".$_SESSION['MVGitHub_idacname']."','".$timenowis."')";
											mysql_query($sql_insert_role);
											}										
										
										} while ($fet_actors_role=mysql_fetch_array($res_actors_role));
									} //if actor_role is > 0
							
							} //close [A]
												
						
						////IF B USER ROLE
							$sql_actors_group="SELECT usrgroup_idusrgroup FROM wfactors WHERE wftskflow_idwftskflow=".$_SESSION['idflow']." AND usrgroup_idusrgroup>0 LIMIT 1";
							$res_actors_group=mysql_query($sql_actors_group);
							$num_actors_group=mysql_num_rows($res_actors_group);
							$fet_actors_group=mysql_fetch_array($res_actors_group);
							
							//if there are records roles, then do the necessary
								if ($num_actors_group > 0)
									{
									//get the loop of the user roles in that group
									$sql_group_roles="SELECT usrrole_idusrrole FROM link_userrole_usergroup WHERE usrgroup_idusrgroup=".$fet_actors_group['usrgroup_idusrgroup']." AND usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']." ORDER BY idlink_userac_usergroup ASC";
									$res_group_roles=mysql_query($sql_group_roles);
									$num_group_roles=mysql_num_rows($res_group_roles);
									$fet_group_roles=mysql_fetch_array($res_group_roles);
									
									//then loop and insert
									do {
										//as you insert, first precheck before inserting 
										$sql_exists_group="SELECT usrrole_idusrrole FROM wfactorsgroup WHERE usrrole_idusrrole=".$fet_group_roles['usrrole_idusrrole']." AND wftskflow_idwftskflow=".$_SESSION['idflow']."";
										$res_exists_group=mysql_query($sql_exists_group);
										$num_exists_group=mysql_num_rows($res_exists_group);
										
										if ($num_exists_group < 1) //if there is no record, then insert
											{
											$sql_insert_group="INSERT INTO wfactorsgroup (wfactorsgroupname_idwfactorsgroupname,usrrole_idusrrole,wftskflow_idwftskflow,createdby,createdon) 
											VALUES ('".$idgroupname."','".$fet_group_roles['usrrole_idusrrole']."','".$_SESSION['idflow']."','".$_SESSION['MVGitHub_idacname']."','".$timenowis."')";
											mysql_query($sql_insert_group);
											}										
										
										} while ($fet_group_roles=mysql_fetch_array($res_group_roles));
									} //if actor_group is > 0
						
						
						} //[12]
					
					
					//if $chk_sharetask is not checked, then delete the record if it exists
					if ($chk_sharetask==0)
						{
						$sql_updatelbl="DELETE FROM wfactorsgroupname WHERE wftskflow_idwftskflow=".$_SESSION['idflow']." LIMIT 1";
						mysql_query($sql_updatelbl);
						
						//delete the roles themselves in that group
						$sql_deleteroles="DELETE FROM wfactorsgroup WHERE wftskflow_idwftskflow=".$_SESSION['idflow']."";
						mysql_query($sql_deleteroles);
						
						}
					
				} //close if the list order has not changed

		//	echo $sql_update;
			mysql_query($sql_update);
			
			$okmsg_1="<span class=\"msg_success_small\">".$msg_changes_saved."</span>";
			
			} //close if no error
	
	} // close form action

//show properties
$sql_properties="SELECT idwftskflow,wfsymbol_idwfsymbol,wfproc_idwfproc,listorder,wftskflowname,wftskflowdesc,wftsktat,expubholidays,createdby,createdon,modifiedby,modifiedon,h_pos,limit_to_zone,limit_to_dpt,group_task_share,is_milestone 
FROM wftskflow WHERE idwftskflow=".$_SESSION['idflow']." AND wfproc_idwfproc=".$_SESSION['wfselected']." LIMIT 1";
$res_properties=mysql_query($sql_properties);
$fet_properties=mysql_fetch_array($res_properties);
$num_properties=mysql_num_rows($res_properties);


if ($num_properties < 1)
	{
	echo $msg_warn_contactadmin;
	exit;
	}
?>
<div>
        <?php
		if ( (isset($error_1)) || (isset($error_2)) || (isset($error_3)) || (isset($error_4)) || (isset($error_5)) )
			{
			if (isset($error_1)) { echo $error_1; }
			if (isset($error_2)) { echo $error_2; }
			if (isset($error_3)) { echo $error_3; }
			if (isset($error_4)) { echo $error_4; }
			if (isset($error_5)) { echo $error_5; }
			}
			
		if (isset($okmsg_1))
			{
			echo $okmsg_1;
			}
		
		?>
        
        </div>
<form method="post" action="" name="properties">
<div>
<table border="0" width="100%" cellpadding="3" cellspacing="0">

				<tr>
                	<td width="171" height="35" class="tbl_data">
                    <strong><?php echo $lbl_name;?></strong>
                    </td>
              <td  height="35" class="tbl_data" style="padding:0px">
              <table border="0" cellpadding="0" cellspacing="0" width="100%">
              	<tr>
                	<td width="29%">
			  <input type="text" maxlength="60" size="38" value="<?php echo $fet_properties['wftskflowname'];?>" name="wfname" />
              		</td>
                  <td width="71%">
	              <label for="is_milestone"><input type="checkbox" <?php if ($fet_properties['is_milestone']==1) { echo "checked=\"checked\'"; } ?> value="1" id="is_milestone" name="is_milestone" /> 
	              <a href="#" class="tooltip"><img border="0" align="absmiddle" src="../assets_backend/icons/icon_milestone.png" /><span>Important for returning 'Exception Tasks' back to the Workflow, Generation of Reports in performance measurement etc.</span></a> Major Milestone </label> 
                  	</td>
                </tr>
              </table>
              </td>
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
                    <td height="30" class="tbl_data" style="padding:0px">
                    	<table border="0" cellpadding="0" cellspacing="0">
                        	<tr>	
                            	<td>
                    <?php
					if ($_SESSION['asymbol']==5)
						{
						$hpos=" AND h_pos=0 ";
						} else {
						$hpos="";
						}
					//get the list order from this process
					$sql_listorder="SELECT idwftskflow,listorder,wftskflowname,wfsymbol_idwfsymbol,h_pos FROM wftskflow WHERE wfproc_idwfproc=".$_SESSION['wfselected']." ".$hpos." ORDER BY listorder ASC";
					$res_listorder=mysql_query($sql_listorder);
					$num_listorder=mysql_num_rows($res_listorder);
					$fet_listorder=mysql_fetch_array($res_listorder);
				//	echo $sql_listorder;
					$sql_prev="SELECT idwftskflow,listorder,wftskflowname,h_pos FROM wftskflow WHERE wfproc_idwfproc=".$_SESSION['wfselected']." AND listorder < ".$fet_properties['listorder']." ORDER BY listorder DESC LIMIT 1";
					$res_prev=mysql_query($sql_prev);
					$fet_prev=mysql_fetch_array($res_prev);
					$num_prev=mysql_num_rows($res_prev);
					
					//get the previous listorder before this one
					echo "<input type=\"hidden\" name=\"hidden_prev_listorder\" value=\"".$fet_prev['listorder']."\">";
					if ($num_listorder ==1)//if there is only one echo "style=\"background-color:#cccccc\" readonly=\"readonly\"";
						{
						
						echo "<input type=\"hidden\" value=\"0.00\" name=\"list_order\"><input type=\"text\" readonly=\"readonly\" value=\"".$fval_sel_listo_dflt."\" name=\"lbldflt\" style=\"background-color:#cccccc;\">";
						} else {
							
								if ($num_prev > 0 ) //if there are records before this step
									
									{
										
										echo "<select name=\"list_order\" onChange=\"getpos(this.value)\">";
										do {
										echo "<option ";
										//select the current by default
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
							
									
										
											if ($fet_listorder['listorder']==$fet_prev['listorder'])
												{
												echo " selected=\"selected\"";  
												}
			
										echo " value=\"".$fet_listorder['listorder']."_".$fet_listorder['h_pos']."_".$fet_listorder['wfsymbol_idwfsymbol']."_".$fet_listorder['idwftskflow']."\">";
										
										if ($fet_listorder['wfsymbol_idwfsymbol']==5)
											{
											echo "*&nbsp;&nbsp;";
											}
										echo $lbl_after.$fet_listorder['wftskflowname'];
							
							
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
									
									} else { //else if this is the first one, then it does not apply
									echo "---";
									}
						}
					?>
                    	</td>
                        <td>
                        <?php
if ($_SESSION['asymbol']!=1)
	{
?>
               <div id="posdiv">
              <?php
  		$query="SELECT h_pos FROM wftskflow WHERE wfproc_idwfproc=".$_SESSION['wfselected']." AND h_pos=".$fet_properties['h_pos']." AND listorder='".$fet_properties['listorder']."' LIMIT 1";
		$result=mysql_query($query);
		//echo $query;
		$hpos=$fet_properties['h_pos'];
		?>
		<select name="pos_side">
		<?php
		while($row=mysql_fetch_array($result)) { 
		
		
			if ($hpos!=0) { //if the previous item was not center, then show "breakaway"
			$suf="[Break]";
			} else {
			$suf="";
			}
		
			if ($row['h_pos']=='-1')
			{
			$position="[L] Left";
			$position_title="Position this item on the left lane on the canvas";
			} else if ($row['h_pos']=='0') {
			$position="[C] Center".$suf;
			$position_title="Position this item at the center of the canvas";
			} else if ($row['h_pos']=='1') {
			$position="[R] Right";
			$position_title="Position this item on the right lane on the canvas";
			}
		?>
        <option title="<?php echo $position_title;?>" value=<?php echo $row['h_pos']; ?>><?php echo $position; ?></option>    
		<?php	
			}
			
			if ($hpos!='0')
				{
		?>
		<option value="0" title="Position this item at the center of the canvas">[C] Center <?php if (isset($suf)) { echo $suf; }?></option>
        	<?php
			}
			?>
		</select>
               </div>
<?php
}			   
?>
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
                    <input type="checkbox" <?php if ((isset($_POST['limit_to_zone'])) && ($_POST['limit_to_zone']==1) ) { echo "checked=\"checked\""; }  if ($fet_properties['limit_to_zone']==1) { echo "checked=\"checked\""; } ?> value="1" name="limit_to_zone" id="limit" /> 
                    Restrict Tasks IN to the actors' Region                    </label>                    </td>
	  <tr>
                <tr>
					<td height="35" class="tbl_data">
                    <strong>Limit to Department</strong>
                    </td>
         			 <td height="35" class="tbl_data">
    				<label for="limit2">
                    <input type="checkbox" value="1" <?php if ((isset($_POST['limit_to_department'])) && ($_POST['limit_to_department']==1) ) { echo "checked=\"checked\""; }  if ($fet_properties['limit_to_dpt']==1) { echo "checked=\"checked\""; } ?> name="limit_to_department" id="limit2" /> 
                    Restrict Tasks IN to the actors' Department                    </label>                    </td>
	  <tr>
                <tr>
					<td height="35" class="tbl_data"><strong>Group Task Sharing</strong></td>
         			 <td height="35" class="tbl_data">
                     <div>
    				<label for="share_task">
                    <input type="checkbox" value="1" <?php if ((isset($_POST['share_task'])) && ($_POST['share_task']==1) ) { echo "checked=\"checked\""; }  if ($fet_properties['group_task_share']==1) { echo "checked=\"checked\""; } ?> name="share_task" id="share_task" onclick = "c() ; showhide()" /> 
                    <img src="../assets_backend/icons/icon_group.gif" width="16" height="16" align="absmiddle" border="0" /> Visible to ALL actors <small><strong>( BUT only 1 actor can action it )</strong></small>                    </label>
                    </div>
                   <div id="myrow" style="visibility:<?php if ( ( (isset($_POST['share_task'])) && ($_POST['share_task']==1)) || ($fet_properties['group_task_share']==1)  ) { echo "visible"; } else { echo "hidden"; } ?>; background-color:#cccccc; padding:2px 5px">
                   <strong> Task Group Name : </strong>
                   <?php
				   if ($fet_properties['group_task_share']==1)
				   	{
					$sql_tskgroupname="SELECT groupname FROM wfactorsgroupname WHERE wftskflow_idwftskflow=".$fet_properties['idwftskflow']." LIMIT 1";
					$res_tskgroupname=mysql_query($sql_tskgroupname);
					$fet_tskgroupname=mysql_fetch_array($res_tskgroupname);
					
					$tskgroupname= $fet_tskgroupname['groupname'];
					
					} else {
					$tskgroupname= "";
					}
				   ?>
                   <input type="text" maxlength="20" value="<?php echo $tskgroupname;?>" size="15" name="taskgroupname" />
                    </div>
                  </td>
      			<tr>
                <tr>
                	<td></td>
                	<td height="55">
  <table border="0" style="margin:15px 10px 5px 0px" cellpadding="0" cellspacing="0">
                        	<tr>
                            	<td>
                                <a href="#" onclick="document.forms['properties'].submit()" id="button_save"></a></td>
                                <td style="padding:0px 0px 0px 10px">
                                <input type="hidden" value="save" name="formaction" />
                               <a href="#"  id="button_cancel" onclick="parent.tb_remove();"></a></td>
                            </tr>
                        </table>                    </td>
      </tr>
			</table>
</div>
</form>