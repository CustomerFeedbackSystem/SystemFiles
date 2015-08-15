<?php
require_once('../../Connections/connSystem.php');
mysql_select_db($database_connSystem, $connSystem);


if ((isset($_POST['form_action'])) && ($_POST['form_action']=="authenticate"))
	{
		try
		{
		// Run CSRF check, on POST data, in exception mode, for 10 minutes, in one-time mode.
		NoCSRF::check( 'nocsrf', $_POST, true, 60*10, false );
				
			//first clean em up
			$username = preg_replace('/[^a-z\-_0-9\.:@\/\s]/i','',mysql_escape_string(trim($_POST['account_usr'])));
			$userpass = mysql_escape_string(trim($_POST['account_pwd']));
			
			//first, check the last time this person has tried logging in
			//capture the users ip in case they are using a proxy, use the function below
			function loggerIP()
			{ 
			if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
				{
				$theIP=$_SERVER['HTTP_X_FORWARDED_FOR'];
				} else {
				 $theIP=$_SERVER['REMOTE_ADDR'];
				}
			return trim($theIP);
			}
			
			$userIP = loggerIP();
			$userBrowser=$_SERVER['HTTP_USER_AGENT'];
			
			//check if the mac address for this server is valid before proceeding
		/*	ob_start(); // Turn on output buffering
			system('ipconfig /all'); //Execute external program to display output
			$mycom=ob_get_contents(); // Capture the output into a variable
			ob_clean(); // Clean (erase) the output buffer
			
			$findme = "Physical";
			$pmac = strpos($mycom, $findme); // Find the position of Physical text
			$mac=substr($mycom,($pmac+36),17); // Get Physical Address*/
		
			
			//then query and count number of attempts in the last 30 minutes
			$sql_lastlog="SELECT attempttime FROM audit_login WHERE userip='".$userIP."' AND usersession='".session_id()."' ORDER BY idaudit_login DESC LIMIT 7,1";
			$res_lastlog=mysql_query($sql_lastlog);
			$num_lastlog=mysql_num_rows($res_lastlog);
			$fet_lastlog=mysql_fetch_array($res_lastlog);
			
			$login_time = $fet_lastlog['attempttime'];
			
			$deducted_time = strtotime($timenowis) - strtotime($login_time) ;
			
		//if the earliest time logged in of the three (3) consecutive login attempts is less than 100 seconds, then kill the login process	
	/*		if ($deducted_time < 300 ){
				header ('location:error_times.php');
				exit;
			}
	*/		
			
			//check if the user has submitted this
			if ( (strlen($username)<1) && (strlen($userpass)<1) )
				{
				$error =  "<div class=\"msg_warning\">".$msg_loginerror_1."</div>";
				} else {
				//validate
				$sql_ghbcheck = "SELECT usrac.idusrac,usrac.usrname,usrac.usrpass,usrac.utitle,usrac.fname,usrac.lname,usrac.usremail,usrteam.usrteamname,usrteam.usrteamshortname,usrteam.usrteamshortname,usrteam.idusrteam,usrac.acstatus,usrrole.idusrrole,usrrole.reportingto,usrrole.usrrolename,usrrole.reportingto,usrrole.sysprofiles_idsysprofiles,usrteamzone.userteamzonename,usrteamzone.idusrteamzone,usrteam.mainlogo_path,usrteam.usrteamtype_idusrteamtype,usrteamzone.region_pref,usrrole.joblevel,usrrole.usrdpts_idusrdpts FROM usrac 
				INNER JOIN usrteam ON usrac.usrteam_idusrteam=usrteam.idusrteam
				INNER JOIN usrrole ON usrac.usrrole_idusrrole=usrrole.idusrrole
				INNER JOIN usrteamzone ON usrrole.usrteamzone_idusrteamzone=usrteamzone.idusrteamzone
				WHERE usrname='".$username."' AND usrpass='".sha1($userpass)."' LIMIT 1";
				
				$res_ghbcheck = mysql_query($sql_ghbcheck);
				$num_ghbcheck = mysql_num_rows($res_ghbcheck);
				$fet_ghbcheck = mysql_fetch_array($res_ghbcheck);
				echo $sql_ghbcheck;
				if ($num_ghbcheck < 1)
					{
				$error = "<div class=\"msg_warning\">[1] ".$msg_loginerror_2."</div>";
				
			//first, insert the attempt
			$sql_reclog="INSERT INTO audit_login (acname,userip,userbrowser,urlreferer,attempttime,attemptresult,usersession)
			VALUES ('".$username."','".$userIP."','".$userBrowser."','".$_SERVER['HTTP_REFERER']."','".$timenowis."','FAIL','".session_id()."')";
			mysql_query($sql_reclog);
			
					}
					
				if ( ($num_ghbcheck > 0) && ($fet_ghbcheck['acstatus']!="1") ) //if user status not active, then warn
					{
				$error = "<div class=\"msg_warning\">[2]".$msg_loginerror_3."</div>";
					}
				
				if ( ($num_ghbcheck > 0) && ($fet_ghbcheck['acstatus']=="1") )
					{	
					//create the session
					$_SESSION['MVGitHub_logstatus'] = "IS_LOGGED_IN";
					$_SESSION['MVGitHub_acname'] = $fet_ghbcheck['usrname'];
					$_SESSION['MVGitHub_acpass'] = $fet_ghbcheck['usrpass'];
					$_SESSION['MVGitHub_usrtitle'] = $fet_ghbcheck['utitle'];
					$_SESSION['MVGitHub_usrfname'] = $fet_ghbcheck['fname'];
					$_SESSION['MVGitHub_usrlname'] = $fet_ghbcheck['lname'];
					$_SESSION['MVGitHub_idacname'] = $fet_ghbcheck['idusrac'];
					$_SESSION['MVGitHub_usremail'] = $fet_ghbcheck['usremail'];
					$_SESSION['MVGitHub_acteam'] = $fet_ghbcheck['usrteamname'];
					$_SESSION['MVGitHub_acteamshrtname'] = $fet_ghbcheck['usrteamshortname'];
					$_SESSION['MVGitHub_logo'] = $fet_ghbcheck['mainlogo_path'];
					$_SESSION['MVGitHub_userrole'] = $fet_ghbcheck['usrrolename'];
					$_SESSION['MVGitHub_reportingto'] = $fet_ghbcheck['reportingto'];
					$_SESSION['MVGitHub_iduserrole'] = $fet_ghbcheck['idusrrole'];
					$_SESSION['MVGitHub_idacteam'] = $fet_ghbcheck['idusrteam'];
					$_SESSION['MVGitHub_iduserprofile'] = $fet_ghbcheck['sysprofiles_idsysprofiles'];
					$_SESSION['MVGitHub_userteamzone'] = $fet_ghbcheck['userteamzonename'];
					$_SESSION['MVGitHub_userteamzoneid'] = $fet_ghbcheck['idusrteamzone'];
					$_SESSION['MVGitHub_userteamshortname'] =$fet_ghbcheck['usrteamshortname'];
					$_SESSION['MVGitHub_userteamtype'] =$fet_ghbcheck['usrteamtype_idusrteamtype'];
					$_SESSION['MVGitHub_tblbill'] ="billacs_".strtolower($fet_ghbcheck['usrteamshortname']);
					$_SESSION['MVGitHub_tblsmsbc'] ="smssubs_".strtolower($fet_ghbcheck['usrteamshortname']);
					$_SESSION['MVGitHub_regionpref']=$fet_ghbcheck['region_pref'];
					$_SESSION['MVGitHub_joblevel']=$fet_ghbcheck['joblevel'];
					$_SESSION['MVGitHub_usrdpts']=$fet_ghbcheck['usrdpts_idusrdpts'];
			
					 	
					//record the current session
					$sql_sess="UPDATE usrac SET currentsess='".session_id()."',lastaccess='".$timenowis."' WHERE idusrac=".$_SESSION['MVGitHub_idacname']."";
					mysql_query($sql_sess);
					
					//first, insert the attempt
					$sql_reclog="INSERT INTO audit_login (acname,userip,userbrowser,urlreferer,attempttime,attemptresult,usersession)
					VALUES ('".$username."','".$userIP."','".$userBrowser."','".$_SERVER['HTTP_REFERER']."','".$timenowis."','OK','".session_id()."')";
					mysql_query($sql_reclog);
					//echo $sql_reclog;
					//echo "<br>";
					//send to new page
					//find out which module and submodule this person has
					$sql_mymods="SELECT syssubmodule_idsyssubmodule,sysprofiles_idsysprofiles,idsyssubmodule,sysmodule_idsysmodule 	FROM systemprofileaccess 
					INNER JOIN syssubmodule ON systemprofileaccess.syssubmodule_idsyssubmodule=syssubmodule.idsyssubmodule 
					WHERE systemprofileaccess.sysprofiles_idsysprofiles=".$fet_ghbcheck['sysprofiles_idsysprofiles']." ORDER BY idsystemprofileaccess ASC LIMIT 1 ";
					$res_mymods=mysql_query($sql_mymods);
					$fet_mymods=mysql_fetch_array($res_mymods);
					
					$_SESSION['sec_mod']=$fet_mymods['sysmodule_idsysmodule'];
					
					//CHECK IF THIS USER BELONGS TO A WORKTASKGROUP AND CONSTRUCT THIS QUERY ONLY ONCE FOR VIEWING TASKS
					$sql_mygroup="SELECT idwfactorsgroup FROM wfactorsgroup WHERE usrrole_idusrrole=".$_SESSION['MVGitHub_iduserrole']." ORDER BY idwfactorsgroup ASC ";
					$res_mygroup=mysql_query($sql_mygroup);
					$num_mygroup=mysql_num_rows($res_mygroup);
					$fet_mygroup=mysql_fetch_array($res_mygroup);
					//echo $sql_mygroup;
					$idwfgroup="";				
					
					if ($num_mygroup > 0)
						{
						do {
							$idwfgroup.= " OR wftasks.wfactorsgroup_idwfactorsgroup=".$fet_mygroup['idwfactorsgroup']." ";
							} while ($fet_mygroup=mysql_fetch_array($res_mygroup));
							
						$_SESSION['idwfgroup']=$idwfgroup;
						$_SESSION['idwfgroup_val']=$fet_mygroup['idwfactorsgroup'];
							
						} else {
						$_SESSION['idwfgroup'] = "";
						$_SESSION['idwfgroup_val']="";
						}
				//echo $_SESSION['idwfgroup'];
				//exit;
				//	echo "This is a Test";
				//	exit;
					header("location:../../myac/index.php?page=0&mod=".$fet_mymods['sysmodule_idsysmodule']."&submod=".$fet_mymods['idsyssubmodule']."&ua=view&view=".$_SESSION['sec_mod']."&uction=view_submod");
					exit;
					}//close if num check is >0 and userstatus is 1
					
				} //close if username and password are not empty
			
			} catch ( Exception $e ) {
		// CSRF attack detected
		$result = $e->getMessage() . ' Form ignored.';
		
		}
			
	} //close form action
//close this mysql connection

// Generate CSRF token to use in form hidden field

?>