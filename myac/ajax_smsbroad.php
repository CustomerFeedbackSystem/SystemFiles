<?php
session_start();

//create the form fields or the regions either for customers or kiosks
require_once('../Connections/connSystem.php');
mysql_select_db($database_connSystem, $connSystem);

require_once('../assets_backend/be_includes/check_login_easy.php');

// technocurve arc 3 php mv block1/3 start
$mocolor1 = "#FFFFFF";
$mocolor2 = "#FCFCFC";
$mocolor3 = "#F2FFF2";
$mocolor = $mocolor1;

if ($_GET['ch'] == '1') //if subscribers
	{
	//query the list of subscribers for this company
	$sql_listnos="SELECT count(*) AS subs,usrteamzone.userteamzonename, usrteamzone.idusrteamzone FROM ".$_SESSION['MVGitHub_tblsmsbc']." 
	INNER JOIN usrteamzone ON ".$_SESSION['MVGitHub_tblsmsbc'].".idusrteamzone=usrteamzone.idusrteamzone WHERE usrteamzone.usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']." AND ".$_SESSION['MVGitHub_tblsmsbc'].".usrtype=1 GROUP BY ".$_SESSION['MVGitHub_tblsmsbc'].".idusrteamzone";
	$res_listnos=mysql_query($sql_listnos);
	$num_listnos=mysql_num_rows($res_listnos);
	$fet_listnos=mysql_fetch_array($res_listnos);
//	echo $sql_listnos;
	if ($num_listnos > 0)
		{
		echo "<div class=\"tbl_h\" style=\"padding:5px 10px 5px 10px;margin:15px 0px 0px 0px\">
        <strong>Select Region(s) </strong>
        </div>";
		do {
			echo "<div class=\"tbl_data\" style=\"padding:10px 0px 10px 0px;background-color:$mocolor;\" onMouseOver=\"this.style.backgroundColor='$mocolor3'\" onMouseOut=\"this.style.backgroundColor='$mocolor'\"><label for=\"".$fet_listnos['idusrteamzone']."\">
			<input type=\"checkbox\" name=\"sendto[]\" value=\"".$fet_listnos['idusrteamzone']."\" id=\"".$fet_listnos['idusrteamzone']."\"> <strong>".$fet_listnos['userteamzonename']."</strong> (".$fet_listnos['subs'].")
			</label></div>";
			} while ($fet_listnos=mysql_fetch_array($res_listnos));
			echo "<div style=\"padding:20px 10px 20px 0px\">
			<input type=\"hidden\" name=\"smswizstep\" value=\"2\">
			<input type=\"hidden\" name=\"next_step\" value=\"2\">
			<a href=\"#\" onclick=\"document.forms['subscribe_1'].submit()\" id=\"button_btn_step_next\"></a></div>";
		} else {
			echo "<div class=\"msg_warning\">You Don't have any Customers in the Database</div>";
		}
	
	}
	
	
if ($_GET['ch'] == '2') // if kiosk owners
	{
	//query the list of kiosk owners
	$sql_listnos="SELECT count(*) AS subs,usrteamzone.userteamzonename, usrteamzone.idusrteamzone FROM ".$_SESSION['MVGitHub_tblsmsbc']." 
	INNER JOIN usrteamzone ON ".$_SESSION['MVGitHub_tblsmsbc'].".idusrteamzone=usrteamzone.idusrteamzone WHERE usrteamzone.usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']." AND ".$_SESSION['MVGitHub_tblsmsbc'].".usrtype=2 GROUP BY ".$_SESSION['MVGitHub_tblsmsbc'].".idusrteamzone";
	$res_listnos=mysql_query($sql_listnos);
	$num_listnos=mysql_num_rows($res_listnos);
	$fet_listnos=mysql_fetch_array($res_listnos);
	
	if ($num_listnos > 0)
		{
		echo "<div class=\"tbl_h\" style=\"padding:5px 10px 5px 10px;margin:15px 0px 0px 0px\">
        <strong>Select Region(s) </strong>
        </div>";
		do {
			echo "<div class=\"tbl_data\" style=\"padding:10px 0px 10px 0px;background-color:$mocolor;\" onMouseOver=\"this.style.backgroundColor='$mocolor3'\" onMouseOut=\"this.style.backgroundColor='$mocolor'\"><label for=\"".$fet_listnos['idusrteamzone']."\">
			<input type=\"checkbox\" name=\"sendto[]\" value=\"".$fet_listnos['idusrteamzone']."\" id=\"".$fet_listnos['idusrteamzone']."\"> <strong>".$fet_listnos['userteamzonename']."</strong> (".$fet_listnos['subs'].")
			</label></div>";
			} while ($fet_listnos=mysql_fetch_array($res_listnos));
			echo "<div style=\"padding:20px 10px 20px 0px\"><a href=\"#\" onclick=\"document.forms['subscribe_1'].submit()\" id=\"button_btn_step_next\"></a></div>";
		} else {
			echo "<div class=\"msg_warning\">You Don't have any Kiosks in the Database</div>";
		}
	}	
	
if ($_GET['ch'] == '0') 
	{
	echo "<span class=\"msg_warning\">Please Select Target Group</span> ";
	}
?>