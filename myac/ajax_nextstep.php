<?php
require_once('../assets_backend/be_includes/config.php');
require_once('../assets_backend/be_includes/check_login_easy.php');


if (isset($_REQUEST['nextstep']))
	{
	//clean it up
	$var_next=mysql_escape_string(trim($_REQUEST['nextstep']));
	
	if ($var_next=="other_exception")
		{
		?>
		
			<div>
				<div style="font-size:11px; font-weight:bold;color:#CC0000; background-color:#FFFFCC">Type Name or Role to Send To :</div>
				<input type="text" size="35" maxlength="250" value="" id="inputString" name="recepient_alt"  onkeyup="lookup(this.value);" onblur="fill();" />
                <?php
				echo "<span style=\"color:#CCCCCC; text-align:right; font-size:small\">0 - 0</span>";
				echo "<input type=\"hidden\" name=\"wftaskflow_id\" value=\"0\">";
				echo "<input type=\"hidden\" name=\"assign_to_2\" value=\"other_exception\" >";
				?>
			</div>
			
			<div class="suggestionsBox" id="suggestions" style="display: none;">
				<div class="suggestionList" id="autoSuggestionsList">
				</div>
			</div>
		       
        <?php
		} else {

			if (($var_next>0) && ($var_next!="other_exception"))
				{
				
					//do the db logic query here
						$sql_checkrole="SELECT idwftskflow,wfsymbol_idwfsymbol,wfproc_idwfproc,limit_to_zone,limit_to_dpt 
						FROM wftskflow 
						INNER JOIN wfactors ON wftskflow.idwftskflow=wfactors.wftskflow_idwftskflow
						INNER JOIN usrrole ON wfactors.usrrole_idusrrole=usrrole.idusrrole
						INNER JOIN usrac ON usrrole.idusrrole=usrac.usrrole_idusrrole
						WHERE ( 
						(wftskflow.limit_to_zone=0 AND wftskflow.limit_to_dpt=0) OR 
						(wftskflow.limit_to_zone=1 AND usrrole.usrteamzone_idusrteamzone=".$_SESSION['MVGitHub_userteamzoneid']." AND wftskflow.limit_to_dpt=0) OR 
						(wftskflow.limit_to_zone=0 AND wftskflow.limit_to_dpt=".$_SESSION['MVGitHub_usrdpts']." AND wftskflow.limit_to_dpt=0) OR 
						(wftskflow.limit_to_zone=".$_SESSION['MVGitHub_userteamzoneid']." AND wftskflow.limit_to_dpt=".$_SESSION['MVGitHub_usrdpts'].") 
						)
						AND 
						wfactors.usrrole_idusrrole>0 AND
						wftskflow.wfproc_idwfproc=".$_SESSION['wfproc_idwfproc']." AND 
						wfactors.wftskflow_idwftskflow=".$var_next." AND
						usrac.acstatus=1  AND ".$_SESSION['NoRTS']."
						ORDER BY wftskflow.listorder ASC LIMIT 1";
						$res_checkrole=mysql_query($sql_checkrole);	
						$fet_checkrole=mysql_fetch_array($res_checkrole);
						
						//echo $sql_checkrole."<br>";
						
						//CHECK BASED ON GROUP
						$sql_checkroleg="SELECT idwftskflow,wfsymbol_idwfsymbol,wfproc_idwfproc,limit_to_zone,limit_to_dpt 
						FROM wftskflow 
						INNER JOIN wfactors ON wftskflow.idwftskflow=wfactors.wftskflow_idwftskflow
						INNER JOIN link_userrole_usergroup ON wfactors.usrgroup_idusrgroup=link_userrole_usergroup.usrgroup_idusrgroup 	
						INNER JOIN usrrole ON link_userrole_usergroup.usrrole_idusrrole=usrrole.idusrrole
						INNER JOIN usrac ON usrrole.idusrrole=usrac.usrrole_idusrrole
						WHERE ( 
						(wftskflow.limit_to_zone=0 AND wftskflow.limit_to_dpt=0) OR 
						(wftskflow.limit_to_zone=1 AND usrrole.usrteamzone_idusrteamzone=".$_SESSION['MVGitHub_userteamzoneid']." AND wftskflow.limit_to_dpt=0) OR 
						(wftskflow.limit_to_zone=0 AND wftskflow.limit_to_dpt=".$_SESSION['MVGitHub_usrdpts']." AND wftskflow.limit_to_dpt=0) OR 
						(wftskflow.limit_to_zone=".$_SESSION['MVGitHub_userteamzoneid']." AND wftskflow.limit_to_dpt=".$_SESSION['MVGitHub_usrdpts'].") 
						)
						AND
						wfactors.usrgroup_idusrgroup>0 AND
						wftskflow.wfproc_idwfproc=".$_SESSION['wfproc_idwfproc']." AND 
						wfactors.wftskflow_idwftskflow=".$var_next." AND
						usrac.acstatus=1 AND ".$_SESSION['NoRTS']."
						ORDER BY wftskflow.listorder ASC LIMIT 1";
						$res_checkroleg=mysql_query($sql_checkroleg);	
						$fet_checkroleg=mysql_fetch_array($res_checkroleg);
						
				//		echo $sql_checkroleg;
						if (($fet_checkrole['idwftskflow'] <1 ) && ($fet_checkroleg['idwftskflow'] < 1))
							{
							echo "<div style=\"font-size:11px; font-weight:bold;color:#CC0000; background-color:#FFFFCC\">
							-- No Valid Actors Found ---
							</div>";
							exit;
							}
						
						
						//take the least - else if the same, take whichever idtskwkflow
						if ($fet_checkrole['idwftskflow'] > 0)
							{
							$tskflowid_role = $fet_checkrole['idwftskflow'];							
							}
							
						if ($fet_checkroleg['idwftskflow'] > 0)
							{							
							$tskflowid_grp = $fet_checkroleg['idwftskflow'];
							}
							
						//pick the lower number
						if ( (isset($tskflowid_role)) && (isset($tskflowid_grp)) )
							{
								if ($tskflowid_role < $tskflowid_grp)
									{
									$next_tskflowid=$tskflowid_role;
									} else if ($tskflowid_role > $tskflowid_grp) {
									$next_tskflowid=$tskflowid_grp;
									} else {
									$next_tskflowid=$tskflowid_role;
									}
									
							} else if ( (isset($tskflowid_role)) && (!isset($tskflowid_grp)) ) {
								
								$next_tskflowid=$tskflowid_role;	
														
							} else if ( (!isset($tskflowid_role)) && (isset($tskflowid_grp)) ) {
								
								$next_tskflowid=$tskflowid_grp;	
								
							} else {
								
								$next_tskflowid=0;	
								
							}
							
						
							$sql_nextwf="SELECT idwftskflow,wfsymbol_idwfsymbol,wfproc_idwfproc,limit_to_zone,limit_to_dpt 
							FROM wftskflow 
							WHERE idwftskflow=".$next_tskflowid." ";
							$res_nextwf=mysql_query($sql_nextwf);
							$fet_nextwf=mysql_fetch_array($res_nextwf);
							$num_nextwf=mysql_num_rows($res_nextwf);
							//echo $sql_nextwf."<br>";
							if ($num_nextwf > 0)//if there is a record
								{ 
								
								//check if it is limit region
								if ($fet_nextwf['limit_to_zone']==1)
									{
									//limit this users region
									$limit_to_zone_qry1=" AND usrrole.usrteamzone_idusrteamzone=".$_SESSION['MVGitHub_userteamzoneid']." ";
									} else {
									$limit_to_zone_qry1="";
									}
									
								//check if it is limit region
								if ($fet_nextwf['limit_to_dpt']==1)
									{
									//limit this user to their department
									$limit_to_dpt_qry1=" AND usrrole.usrdpts_idusrdpts=".$_SESSION['MVGitHub_usrdpts']." ";
									} else {
									$limit_to_dpt_qry1="";
									}
								
								if ($fet_nextwf['wfsymbol_idwfsymbol']==10) //if it is the end of the process
									{
									
									$next_step="last_step";
									
									} else { //else if not end of the process, continue
									
										//confirm whether the actors are a group or individual role
										$sql_actors="SELECT usrrole_idusrrole,usrgroup_idusrgroup FROM wfactors WHERE wftskflow_idwftskflow=".$fet_nextwf['idwftskflow']." LIMIT 1 ";
										$res_actors=mysql_query($sql_actors);
										$fet_actors=mysql_fetch_array($res_actors);
										$num_actors=mysql_num_rows($res_actors);
										//echo $sql_actors;
										if ($fet_actors['usrrole_idusrrole'] >0 ) //if more than 0, then it is a allocated to a role
											{
											//find out the actual account assigned this role
											$sql_userac="SELECT idusrac,usrrolename,idusrrole,usrac.utitle,usrac.lname,usrac.fname,usrteamzone.region_pref FROM wfactors
											INNER JOIN usrrole ON wfactors.usrrole_idusrrole=usrrole.idusrrole
											INNER JOIN usrac ON usrrole.idusrrole=usrac.usrrole_idusrrole
											INNER JOIN usrteamzone ON usrrole.usrteamzone_idusrteamzone=usrteamzone.idusrteamzone
											WHERE wfactors.wftskflow_idwftskflow=".$fet_nextwf['idwftskflow']." AND usrac.usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']." ".$limit_to_zone_qry1." ".$limit_to_dpt_qry1." AND acstatus=1 AND ".$_SESSION['NoRTS']." ORDER BY usrteamzone.idusrteamzone";
											$res_userac=mysql_query($sql_userac);
											$fet_userac=mysql_fetch_array($res_userac);
											$num_userac=mysql_num_rows($res_userac);
										//	echo "<span style=color:#ffffff>".$sql_userac."</spa<br>";
	
											if ($num_userac > 0)
												{
												
												$menu_item="";
													do {
														if ($fet_userac['idusrrole']!=$_SESSION['MVGitHub_iduserrole']) //list only if not current user
															{
															$menu_item.="<option title=\"".$fet_userac['utitle']." ".$fet_userac['fname']." ".$fet_userac['lname']."\" value=\"".$fet_userac['idusrrole']."\">[".$fet_userac['region_pref']."] ".$fet_userac['usrrolename']." ( ".$fet_userac['utitle']." ".$fet_userac['fname']." ".$fet_userac['lname'].")</option>";
															} else {
															$menu_item.="<option title=\"".$fet_userac['utitle']." ".$fet_userac['fname']." ".$fet_userac['lname']."\" value=\"".$fet_userac['idusrrole']."\">*** [ To My TasksIN ]</option>";
															} //end //list only if not current user
														} while ($fet_userac=mysql_fetch_array($res_userac));
										
												} else {
												

												echo "<div class=\"msg_warning_small\">--No Active User--<br><small>(Please Contact Admin)</small></div>";
												
												} //user exists
											} //close usrrole
									
										if ($fet_actors['usrgroup_idusrgroup'] > 0 ) //if allocated to a group, then do the following
											{ 
											//if group, check only those roles that do actually have users (active status) mapped to them
											//check who has had most work in the last 7 days (one week) in terms of hours
											//last 7 days
											//$timenow = ; //capture current time. You can adjust based on server settings
											$sevendaysago = date("Y-m-d H:i:s",strtotime(date("Y-m-d H:i:s",time())) - (7*86400)); //7 days ago
											
											//echo $sevendaysago."<br>-----";
												
											$sql_workdistr="SELECT SUM(TIMESTAMPDIFF(MINUTE,timetatstart,timedeadline)) AS minutes, usrac.idusrac, usrac.usrrole_idusrrole,usrac.utitle,usrac.lname,usrrole.usrrolename,usrteamzone.region_pref FROM wftasks 
											INNER JOIN usrrole ON wftasks.usrrole_idusrrole=usrrole.idusrrole 
											INNER JOIN link_userrole_usergroup ON usrrole.idusrrole = link_userrole_usergroup.usrrole_idusrrole
											INNER JOIN usrac ON link_userrole_usergroup.usrrole_idusrrole = usrac.usrrole_idusrrole
											INNER JOIN usrteamzone ON usrrole.usrteamzone_idusrteamzone=usrteamzone.idusrteamzone
											WHERE wftasks.createdon>='".$sevendaysago."' AND wftasks.createdon<='".$timenowis."' ".$limit_to_zone_qry1." ".$limit_to_dpt_qry1." AND usrac.usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']." AND link_userrole_usergroup.usrgroup_idusrgroup=".$fet_actors['usrgroup_idusrgroup']." AND acstatus=1 AND ".$_SESSION['NoRTS']." GROUP BY wftasks.usrac_idusrac ORDER BY minutes ASC";
											//echo "test";
											//echo $sql_workdistr."<br>";
											$res_workdistr=mysql_query($sql_workdistr);
											$num_workdistr=mysql_num_rows($res_workdistr);
											$fet_workdistr=mysql_fetch_array($res_workdistr);
												
												
											//check in case the group has not received anything in the last 7 days
											$sql_workdistolder7="SELECT SUM(TIMESTAMPDIFF(MINUTE,timetatstart,timedeadline)) AS minutes, usrac.idusrac, usrac.usrrole_idusrrole,usrac.utitle,usrac.lname,usrac.fname,usrrole.usrrolename,usrteamzone.region_pref FROM wftasks 
											INNER JOIN usrrole ON wftasks.usrrole_idusrrole=usrrole.idusrrole 
											INNER JOIN link_userrole_usergroup ON usrrole.idusrrole = link_userrole_usergroup.usrrole_idusrrole
											INNER JOIN usrac ON link_userrole_usergroup.usrrole_idusrrole = usrac.usrrole_idusrrole
											INNER JOIN usrteamzone ON usrrole.usrteamzone_idusrteamzone=usrteamzone.idusrteamzone
											WHERE wftasks.createdon<='".$timenowis."' AND link_userrole_usergroup.usrgroup_idusrgroup=".$fet_actors['usrgroup_idusrgroup']." AND usrac.usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']." ".$limit_to_zone_qry1." ".$limit_to_dpt_qry1." AND acstatus=1 AND ".$_SESSION['NoRTS']." GROUP BY wftasks.usrac_idusrac ORDER BY minutes ASC";
											//echo "test";
											//echo $sql_workdistolder7."<br>";
											$res_workdistolder7=mysql_query($sql_workdistolder7);
											$num_workdistolder7=mysql_num_rows($res_workdistolder7);
											$fet_workdistolder7=mysql_fetch_array($res_workdistolder7);	
											
												
											//check also for any new user who perhaps has never received a task - new user
											$sql_newuser="SELECT idusrac, usrac.usrrole_idusrrole, usrrole.usrrolename,usrac.utitle,usrac.lname,usrac.fname,usrteamzone.region_pref
											FROM link_userrole_usergroup
											INNER JOIN usrrole ON link_userrole_usergroup.usrrole_idusrrole = usrrole.idusrrole
											INNER JOIN usrac ON usrrole.idusrrole=usrac.usrrole_idusrrole
											INNER JOIN usrteamzone ON usrrole.usrteamzone_idusrteamzone=usrteamzone.idusrteamzone
											WHERE link_userrole_usergroup.usrrole_idusrrole NOT
											IN (
											
											SELECT usrrole_idusrrole
											FROM wftasks
											)
											AND link_userrole_usergroup.usrgroup_idusrgroup=".$fet_actors['usrgroup_idusrgroup']." 
											".$limit_to_zone_qry1." ".$limit_to_dpt_qry1."
											AND acstatus=1";
											$res_newuser=mysql_query($sql_newuser);
											$num_newuser=mysql_num_rows($res_newuser);
											$fet_newuser=mysql_fetch_array($res_newuser);
									
								//	echo $sql_newuser;
											//if record exists, then pick 
											
											if ($num_newuser>0) //if there is a new user and user exists in the workflow
												{
												
												$menu_item3="";
													do {
														if ($fet_newuser['usrrole_idusrrole']!=$_SESSION['MVGitHub_iduserrole']) //list only if not current user
															{
															$menu_item3.="<option title=\"".$fet_newuser['utitle']."  ".$fet_newuser['fname']." ".$fet_newuser['lname']."\" value=\"".$fet_newuser['usrrole_idusrrole']."\">[".$fet_newuser['region_pref']."] ".$fet_newuser['usrrolename']." (".$fet_newuser['utitle']."  ".$fet_newuser['fname']." ".$fet_newuser['lname'].")</option>";
															} //end //list only if not current user
														} while ($fet_newuser=mysql_fetch_array($res_newuser));	
												//$menu_item="<option selected=\"selected\" title=\"".$fet_newuser['utitle']." ".$fet_newuser['lname']."\" value=\"".$fet_newuser['usrrole_idusrrole']."\">".$fet_newuser['usrrolename']."[2]</option>";
													
													
												} else if ($num_newuser==0) { //else if no one is new 
													
														if ($num_workdistr > 0 ) //if there are already users in the tasks
															{
															$menu_item2="";
																
																do {
																//don't list the current logged in user on the menu
																if ($fet_workdistr['usrrole_idusrrole']!=$_SESSION['MVGitHub_iduserrole'])
																		{
																		$menu_item2.="<option title=\"".$fet_workdistr['utitle']." ".$fet_workdistr['fname']." ".$fet_workdistr['lname']."\"  value=\"".$fet_workdistr['usrrole_idusrrole']."\">[".$fet_workdistr['region_pref']."] ".$fet_workdistr['usrrolename']." (".$fet_workdistr['utitle']." ".$fet_workdistr['fname']." ".$fet_workdistr['lname'].")</option>";
																		}
																	} while ($fet_workdistr=mysql_fetch_array($res_workdistr));
															
															} else { //else create a task for the admin
															
																//check if older than 7 days
																if ($num_workdistolder7 > 0)
																	{
																	$menu_item2="";
																	do {
																		//don't list the current logged in user on the menu
																		if ($fet_workdistolder7['usrrole_idusrrole']!=$_SESSION['MVGitHub_iduserrole'])
																				{
																				$menu_item2.="<option title=\"".$fet_workdistolder7['utitle']." ".$fet_workdistolder7['fname']." ".$fet_workdistolder7['lname']."\"  value=\"".$fet_workdistolder7['usrrole_idusrrole']."\">[".$fet_workdistolder7['region_pref']." ] ".$fet_workdistolder7['usrrolename']." (".$fet_workdistolder7['utitle']." ".$fet_workdistolder7['fname']." ".$fet_workdistolder7['lname'].")</option>";
																				}
																			} while ($fet_workdistolder7=mysql_fetch_array($res_workdistolder7));
																	
																	} else {
																	//create new task for the admin
																	echo "<div class=\"msg_warning_small\">--No Active User--<br><small>(Please Contact Admin)</small></div>";
																	}
												
															} //user exists
													
													} //close new user
	
												} //close user group
	
									} //not last step
								
								} else { //if no record
								
								$next_step="end_of_road";
								} //close if there is a record

							
						if ( (isset($menu_item)) || (isset($menu_item2)) || (isset($menu_item3)) )
							{
							
						$_SESSION['next_tskflowid']=$next_tskflowid; //store in a session and see if it appears the other file
						
						echo "<input type=\"hidden\" name=\"wftaskflow_id\" value=\"".$next_tskflowid."\">";				
						echo "<select style=\"width:250px\" name=\"assign_to_2\" id=\"assign_to_2\" onchange=\"showstuff(this.value);\">";
						echo "<option value=\"\">---Click Here---</option>";
						
								if(isset($menu_item)) { echo $menu_item; }
								if(isset($menu_item2)) { echo $menu_item2; }
								if(isset($menu_item3)) { echo $menu_item3; }

								//find out if there is a group for this
								$sql_groupname="SELECT idwfactorsgroupname,groupname FROM wfactorsgroupname WHERE wftskflow_idwftskflow=".$fet_nextwf['idwftskflow']." LIMIT 1";
								$res_groupname=mysql_query($sql_groupname);
								$fet_groupname=mysql_fetch_array($res_groupname);						
										
							if ($fet_groupname['idwfactorsgroupname'] > 0)
								{	
								echo "<option title=\"Send to a Group but only one will Action\" value=\"GRP".$fet_groupname['idwfactorsgroupname']."\">[Group] ".$fet_groupname['groupname']."</option>";
								}
						//	echo "<option disabled=\"disabled\">-----------------</option>";	
						//	echo "<option style=\"color:#ff0000;font-weight:bold;background-color:#ffffcc\" title=\"If Recepient is not Listed, then make this an Exception\" value=\"other_exception\">[ Not Listed Above ]</option>";
							echo "</select>";	
							echo "<span style=\"color:#CCCCCC; text-align:right; font-size:small\">0 - ".$next_tskflowid."</span>";
							echo "<input type=\"hidden\" name=\"wftaskflow_id\" value=\"".$next_tskflowid."\">";		
							} //select menu
		
		//end query logic here				
		
		}
	}
}

?>