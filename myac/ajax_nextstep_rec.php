<?php
require_once('../assets_backend/be_includes/config.php');
require_once('../assets_backend/be_includes/check_login_easy.php');

//max joblevel accessible
$joblvl=($_SESSION['MVGitHub_joblevel']-2);

// Is there a posted query string?
if (isset($_POST['queryString'])) 
	{
	$queryString = mysql_escape_string($_POST['queryString']);
			
	// Is the string length greater than 0?
			
	if	(strlen($queryString) >0) {
				// Run the query: We use LIKE '$queryString%'
				// The percentage sign is a wild-card, in my example of countries it works like this...
				// $queryString = 'Uni';
				// Returned data = 'United States, United Kindom';
				
				// YOU NEED TO ALTER THE QUERY TO MATCH YOUR DATABASE.
				// eg: SELECT yourColumnName FROM yourTable WHERE yourColumnName LIKE '$queryString%' LIMIT 10
				
				//$query = $db->query("SELECT usrname FROM usrac WHERE usrname LIKE '$queryString%' LIMIT 10");
			/*	$query = "SELECT utitle,fname,lname,usrname,usrrolename,usrteamzone.region_pref FROM usrac
				INNER JOIN usrrole ON usrac.usrrole_idusrrole=usrrole.idusrrole 
				INNER JOIN usrteamzone ON usrrole.usrteamzone_idusrteamzone=usrteamzone.idusrteamzone
				WHERE ( usrrolename LIKE '%".$queryString."%' OR fname LIKE '%".$queryString."%' OR lname LIKE '%".$queryString."%' OR usrname LIKE '%".$queryString."%' ) 
				AND usrrole.joblevel >=".$joblvl." AND usrrole.usrteamzone_idusrteamzone=".$_SESSION['MVGitHub_userteamzoneid']."
				AND usrac.acstatus=1 LIMIT 10";
			*/	
				$query = "SELECT utitle,fname,lname,usrname,usrrolename,usrteamzone.region_pref FROM usrac
				INNER JOIN usrrole ON usrac.usrrole_idusrrole=usrrole.idusrrole 
				INNER JOIN usrteamzone ON usrrole.usrteamzone_idusrteamzone=usrteamzone.idusrteamzone
				WHERE ( usrrolename LIKE '%".$queryString."%' OR fname LIKE '%".$queryString."%' OR lname LIKE '%".$queryString."%' OR usrname LIKE '%".$queryString."%' ) 
				AND usrac.acstatus=1  AND ".$_SESSION['NoRTS']." LIMIT 80";
				//AND usrrole.joblevel >=".$joblvl." 
				
				$res=mysql_query($query);
				$fet=mysql_fetch_array($res);
				$num=mysql_num_rows($res);
				//echo "<span style=\"color:#ffffff\">".$query."</span>";
				if (($query) && ($num>0) ) {
					// While there are results loop through them - fetching an Object (i like PHP5 btw!).
					do {
						// Format the results, im using <li> for the list, you can change it.
						// The onClick function fills the textbox with the result.
						
						// YOU MUST CHANGE: $result->value to $result->your_colum
	         			echo '<li onClick="fill(\''.$fet['usrrolename'].', '.$fet['utitle'].' '.$fet['fname'].' '.$fet['lname'].' ['.$fet['region_pref'].']\');">'.$fet['usrrolename'].', '.$fet['utitle'].' '.$fet['fname'].' '.$fet['lname'].' ['.$fet['region_pref'].']</li>';
	         		} while ($fet=mysql_fetch_array($res));
				} else {
					echo '--Not Found--';
				}
			} else {
				// Dont do anything.
			} // There is a queryString.
		} else {
			echo 'There should be no direct access to this script!';
		}
		
		
?>