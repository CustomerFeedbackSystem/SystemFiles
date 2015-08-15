<?php
require_once('../assets_backend/be_includes/config.php');
require_once('../Connections/connSystem.php');
mysql_select_db($database_connSystem, $connSystem);

$regionId=intval($_GET['assign_to_region']);
//echo $regionId;
//before listing the roles, please confirm that
//1. the Category in use has a valid workflow
$sql_wf="SELECT wfproc_idwfproc FROM link_tskcategory_wfproc 
INNER JOIN wfproc ON link_tskcategory_wfproc.wfproc_idwfproc=wfproc.idwfproc
WHERE link_tskcategory_wfproc.usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']." AND link_tskcategory_wfproc.tktcategory_idtktcategory=".$_SESSION['tktcat']." AND wfproc.wfstatus=1 LIMIT 1";
$res_wf=mysql_query($sql_wf);
$num_wf=mysql_num_rows($res_wf);
$fet_wf=mysql_fetch_array($res_wf);
//echo $sql_wf;		
if ($num_wf>0)
	{
		//2. get the workflow
			//ensure that if the person creating this ticket is the Customer Care agent /or the first one on the workflow, 
			//then they should not send the ticket to themselves and so it should go to the next step
						$sql_precheck="SELECT idwftskflow,wfsymbol_idwfsymbol,wfproc_idwfproc FROM wftskflow WHERE wfproc_idwfproc=".$fet_wf['wfproc_idwfproc']." AND listorder>0.00  ORDER BY listorder ASC LIMIT 1"; //precheck to get the idtskflow for this process
						$res_precheck=mysql_query($sql_precheck);
						$fet_precheck=mysql_fetch_array($res_precheck);
						$num_precheck=mysql_num_rows($res_precheck);
						//echo $sql_precheck;
						//check if the actor
						if ($num_precheck > 0) 
							{
							$sql_preactors="SELECT usrrole_idusrrole,usrgroup_idusrgroup,wftskflow_idwftskflow FROM wfactors WHERE wftskflow_idwftskflow=".$fet_precheck['idwftskflow']." AND usrteamzone_idusrteamzone=".$regionId." LIMIT 1 ";
							$res_preactors=mysql_query($sql_preactors);
							$fet_preactors=mysql_fetch_array($res_preactors);
							$num_preactors=mysql_num_rows($res_preactors);
							//echo $sql_preactors;
								if ($num_preactors > 0)
									{
										if ($fet_preactors['usrrole_idusrrole'] > 0)
											{
											//check if this user's account and if so, lets skip to the next taskworkflow id
											$sql_thisusr="SELECT usrrole_idusrrole FROM wfactors WHERE usrrole_idusrrole=".$_SESSION['MVGitHub_iduserrole']." AND wftskflow_idwftskflow=".$fet_preactors['wftskflow_idwftskflow']." LIMIT 1";
											$res_thisusr=mysql_query($sql_thisusr);
											$fet_thisusr=mysql_fetch_array($res_thisusr);
											$num_thisusr=mysql_num_rows($res_thisusr);
												//echo $sql_thisusr;
												if ($num_thisusr > 0)
													{
													$skip_to_step2=1; //set variable to skip to next workflow
													}
											}
										
										if ($fet_preactors['usrgroup_idusrgroup'] > 0)
											{
											$sql_thisusr="SELECT usrrole_idusrrole FROM link_userrole_usergroup WHERE usrrole_idusrrole=".$fet_preactors['usrgroup_idusrgroup']." AND usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']." LIMIT 1";
											$res_thisusr=mysql_query($sql_thisusr);
											$fet_thisusr=mysql_fetch_array($res_thisusr);
											
												if ($fet_thisusr['usrrole_idusrrole']==$_SESSION['MVGitHub_iduserrole'])
													{
													$skip_to_step2=1; //set variable to skip to next workflow
													}
											
											}
									
									}
							}
					
						if (isset($skip_to_step2))
							{						
							$sql_nextwf="SELECT idwftskflow,wfsymbol_idwfsymbol,wfproc_idwfproc FROM wftskflow WHERE wfproc_idwfproc=".$fet_wf['wfproc_idwfproc']." AND listorder>0.00  ORDER BY listorder ASC LIMIT 1,1";
							} else {
							$sql_nextwf="SELECT idwftskflow,wfsymbol_idwfsymbol,wfproc_idwfproc FROM wftskflow WHERE wfproc_idwfproc=".$fet_wf['wfproc_idwfproc']." AND listorder>0.00  ORDER BY listorder ASC LIMIT 1";
							}
						//echo $sql_nextwf;	
						$res_nextwf=mysql_query($sql_nextwf);
						$fet_nextwf=mysql_fetch_array($res_nextwf);
						$num_nextwf=mysql_num_rows($res_nextwf);
						
						//echo $sql_nextwf."<br>";
						if ($num_nextwf > 0)//if there is a record
							{ 
							
							if ($fet_nextwf['wfsymbol_idwfsymbol']==10)//if it is the end of the process
								{
								
								$next_step="last_step";
								
								} else { //else if not end of the process, continue
								
									//confirm whether the actors are a group or individual role
									$sql_actors="SELECT usrrole_idusrrole,usrgroup_idusrgroup FROM wfactors WHERE wftskflow_idwftskflow=".$fet_nextwf['idwftskflow']." AND usrteamzone_idusrteamzone=".$regionId."  LIMIT 1 ";
									$res_actors=mysql_query($sql_actors);
									$fet_actors=mysql_fetch_array($res_actors);
									$num_actors=mysql_num_rows($res_actors);
									//echo $sql_actors;
									if ($fet_actors['usrrole_idusrrole'] >0 ) //if more than 0, then it is a allocated to a role
										{
										//find out the actual account assigned this role
										$sql_userac="SELECT idusrac,usrrolename,idusrrole,usrac.utitle,usrac.lname,usrac.usrname,usrac.fname FROM wfactors
										INNER JOIN usrrole ON wfactors.usrrole_idusrrole=usrrole.idusrrole
										INNER JOIN usrac ON usrrole.idusrrole=usrac.usrrole_idusrrole
										WHERE wfactors.wftskflow_idwftskflow=".$fet_nextwf['idwftskflow']." AND usrac.usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']." AND acstatus=1 AND wfactors.usrteamzone_idusrteamzone=".$regionId." ";
										$res_userac=mysql_query($sql_userac);
										$fet_userac=mysql_fetch_array($res_userac);
										$num_userac=mysql_num_rows($res_userac);
										//echo $sql_userac."<br>";

										if ($num_userac > 0)
											{
											
											$menu_item="";
												do {
												
													$usrman=substr($fet_userac['usrname'],3,10);
													
													if ($fet_userac['idusrrole']!=$_SESSION['MVGitHub_iduserrole']) //list only if not current user
														{
														//get the man number from the account name
														$menu_item.="<option title=\"".$fet_userac['utitle']." ".$fet_userac['fname']." ".$fet_userac['lname']." (".$usrman.")\" value=\"".$fet_userac['idusrrole']."\">".$fet_userac['usrrolename']."</option>";
														} else {
														$menu_item.="<option title=\"".$fet_userac['utitle']." ".$fet_userac['fname']." ".$fet_userac['lname']." (".$usrman.")\" value=\"".$fet_userac['idusrrole']."\">*** [ To My TasksIN ]</option>";
														} //end //list only if not current user
													} while ($fet_userac=mysql_fetch_array($res_userac));
									
											} else {
											
											echo "<div class=\"msg_warning\">--No Active User--<br><small>(Please Contact Admin)</small></div>";
											
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
											
										$sql_workdistr="SELECT SUM(TIMESTAMPDIFF(MINUTE,timetatstart,timedeadline)) AS minutes, usrac.idusrac, usrac.usrrole_idusrrole,usrac.utitle,usrac.lname,usrrole.usrrolename FROM wftasks 
										INNER JOIN usrrole ON wftasks.usrrole_idusrrole=usrrole.idusrrole 
										INNER JOIN link_userrole_usergroup ON usrrole.idusrrole = link_userrole_usergroup.usrrole_idusrrole
										INNER JOIN usrac ON link_userrole_usergroup.usrrole_idusrrole = usrac.usrrole_idusrrole
										WHERE wftasks.createdon>='".$sevendaysago."' AND wftasks.createdon<='".$timenowis."' AND usrac.usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']." AND link_userrole_usergroup.usrgroup_idusrgroup=".$fet_actors['usrgroup_idusrgroup']." AND acstatus=1 AND usrrole.usrteamzone_idusrteamzone=".$regionId." GROUP BY wftasks.usrac_idusrac ORDER BY minutes ASC";
										//echo "test";
										//echo $sql_workdistr."<br>";
										$res_workdistr=mysql_query($sql_workdistr);
										$num_workdistr=mysql_num_rows($res_workdistr);
										$fet_workdistr=mysql_fetch_array($res_workdistr);
											
											
										//check in case the group has not received anything in the last 7 days
										$sql_workdistolder7="SELECT SUM(TIMESTAMPDIFF(MINUTE,timetatstart,timedeadline)) AS minutes, usrac.idusrac, usrac.usrrole_idusrrole,usrac.utitle,usrac.lname,usrrole.usrrolename FROM wftasks 
										INNER JOIN usrrole ON wftasks.usrrole_idusrrole=usrrole.idusrrole 
										INNER JOIN link_userrole_usergroup ON usrrole.idusrrole = link_userrole_usergroup.usrrole_idusrrole
										INNER JOIN usrac ON link_userrole_usergroup.usrrole_idusrrole = usrac.usrrole_idusrrole
										WHERE wftasks.createdon<='".$timenowis."' AND link_userrole_usergroup.usrgroup_idusrgroup=".$fet_actors['usrgroup_idusrgroup']." AND usrac.usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']." AND acstatus=1 AND usrrole.usrteamzone_idusrteamzone=".$regionId." GROUP BY wftasks.usrac_idusrac ORDER BY minutes ASC";
										//echo "test";
										//echo $sql_workdistolder7."<br>";
										$res_workdistolder7=mysql_query($sql_workdistolder7);
										$num_workdistolder7=mysql_num_rows($res_workdistolder7);
										$fet_workdistolder7=mysql_fetch_array($res_workdistolder7);	
										
											
										//check also for any new user who perhaps has never received a task - new user
										$sql_newuser="SELECT idusrac, usrac.usrrole_idusrrole, usrrole.usrrolename,usrac.utitle,usrac.lname
										FROM link_userrole_usergroup
										INNER JOIN usrrole ON link_userrole_usergroup.usrrole_idusrrole = usrrole.idusrrole
										INNER JOIN usrac ON usrrole.idusrrole=usrac.usrrole_idusrrole
										WHERE link_userrole_usergroup.usrrole_idusrrole NOT
										IN (
										
										SELECT usrrole_idusrrole
										FROM wftasks
										)
										AND link_userrole_usergroup.usrgroup_idusrgroup=".$fet_actors['usrgroup_idusrgroup']." 
										AND usrrole.usrteamzone_idusrteamzone=".$regionId."
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
														$menu_item3.="<option title=\"".$fet_newuser['utitle']." ".$fet_newuser['lname']."\" value=\"".$fet_newuser['usrrole_idusrrole']."\">".$fet_newuser['usrrolename']."</option>";
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
																	$menu_item2.="<option title=\"".$fet_workdistr['utitle']." ".$fet_workdistr['lname']."\" value=\"".$fet_workdistr['usrrole_idusrrole']."\">".$fet_workdistr['usrrolename']."</option>";
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
																			$menu_item2.="<option title=\"".$fet_workdistolder7['utitle']." ".$fet_workdistolder7['lname']."\" value=\"".$fet_workdistolder7['usrrole_idusrrole']."\">".$fet_workdistolder7['usrrolename']."</option>";
																			}
																		} while ($fet_workdistolder7=mysql_fetch_array($res_workdistolder7));
																
																} else {
																//create new task for the admin
																echo "<div class=\"msg_warning\">--No Active User--<br><small>(Please Contact Admin)</small></div>";
																}
											
														} //user exists
												
												} //close new user

											} //close user group

								} //not last step
							
							} else { //if no record
							
							$next_step="end_of_road";
							} //close if there is a record


						//set default
						if ( (isset($_POST['formaction'])) && ($_POST['formaction']=="process_task") && ($tktasito2>0) )
							{
							$sql_default="SELECT idusrrole,rolename FROM usrrole WHERE idusrrole=".$tktasito2." ";
							$res_default=mysql_query($sql_default);
							$fet_default=mysql_fetch_array($res_default);
							
							$menu_default="<option selected=\"selected\" value=\"".$fet_default['idusrrole']."\">".$fet_default['rolename']."</option>";
							
							} else {
							$menu_default="";
							}						
							
						if ( (isset($menu_item)) || (isset($menu_item2)) || (isset($menu_item3)) )
							{			
						echo "<select name=\"assign_to_2\" id=\"assign_to_2\">";
						echo "<option value=\"\">---</option>";
						echo $menu_default;
						if(isset($menu_item)) { echo $menu_item; }
						if(isset($menu_item2)) { echo $menu_item2; }
						if(isset($menu_item3)) { echo $menu_item3; }
						echo "</select>";	
										
							}
						
						}
						?>