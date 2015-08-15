<?php
session_start(); //instantiate the session
////////////PLEASE BE CAREFUL WHILE EDITING THE VARIABLES ON THIS FILE AS THEY ARE GLOBAL ///////

//Prevent Cross Site Request Forgery
if (isset($_POST['nocsrf'])) //only run if the DKM post is set
	{
	include('../../nocsrf.php');
	}

//expire session if idle
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1200)) {
    // last request was more than 20 mins (60*20=1200)
    session_unset();     // unset $_SESSION variable for the runtime 
    session_destroy();   // destroy session data in storage
}

$_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp

// uncomment this when in production mode for security reasons
error_reporting(0); 

//the variable below tells the system if the mail server is availble or not available
$mailserver_avail="0";

require_once('config_settings.php');

// technocurve arc 3 php mv block1/3 start
$mocolor1 = "#FFFFFF";
$mocolor2 = "#FCFCFC";
$mocolor3 = "#F2FFF2";
$mocolor = $mocolor1;

//google maps key
$google_maps_key="";//place your google maps api here

//support email
$support_email="";//enter support email variable 

//current date
//$timenowis = date("Y-m-d H:i:s",time());
$timenowis = date("Y-m-d H:i:s",strtotime(date("Y-m-d H:i:s",time())) + (3*3600)); //3 hrs
$thirty_days_ago=date("Y-m-d H:i:s",strtotime($timenowis) - (30*86400)); //30 days ago
$seven_days_ago=date("Y-m-d H:i:s",strtotime($timenowis) - (7*86400)); //7 days ago
$seven_days_ago_notime=date("Y-m-d",strtotime($timenowis) - (7*86400)); //7 days ago without the time component
$one_day_ago=date("Y-m-d H:i:s",strtotime($timenowis) - (1*86400)); //1 days ago
$three_mins_ago=date("Y-m-d H:i:s",strtotime($timenowis) - (60*3)); //3 minutes ago
$day_sec = 86400;
$fifteen_months_ago=date("Y-m-d H:i:s",strtotime($timenowis) - (30*86400*15)); //30 days ago
$today = date("Y-m-d",time());
$this_year=date("Y",time());

//load license file
require_once('localization.php');

//get the IP 
$theip = $_SERVER["REMOTE_ADDR"];

if (!empty($_SERVER["HTTP_X_FORWARDED_FOR"])) {
    $theip .= '('.$_SERVER["HTTP_X_FORWARDED_FOR"].')';
}

if (!empty($_SERVER["HTTP_CLIENT_IP"])) {
    $theip .= '('.$_SERVER["HTTP_CLIENT_IP"].')';
}

$realip = substr($theip, 0, 250);

//process login
if (isset($_POST['form_action']))
	{
	$login_action=preg_replace('/[^a-z\-_0-9\.:\/\s]/i','',mysql_escape_string(trim($_POST['form_action'])));
	
	if ((isset($login_action)) && ($login_action=="authenticate"))
		{
		require_once('authenticate_login.php'); //run the authentication file and pass on the correct error code
		}
	}
//process navigation with sessions
if (isset($_GET['mod']))
	{
	$_SESSION['mod_celectd']=preg_replace('/[^a-z\-_0-9\.:\/\s]/i','',mysql_escape_string(trim($_GET['mod'])));
	}

if (!isset($_SESSION['mod_celectd']))
	{
	$_SESSION['mod_celectd'] = 1; // 1 being the dashboard
	}

/* current URL */
function curPageURL() {
 $pageURL = 'http';
 if ( (isset($_SERVER["HTTPS"])) && ($_SERVER["HTTPS"] == "on") ) {$pageURL .= "s";}
 $pageURL .= "://";
 if ($_SERVER["SERVER_PORT"] != "80") {
  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
 } else {
  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
 }
 return $pageURL;
 

 //just echo curPageURL();
}


//audit trail if user logged in
if  ((isset($_SESSION['MVGitHub_logstatus'])) && ($_SESSION['MVGitHub_logstatus']=="IS_LOGGED_IN") )
	{

	$insert_audit="INSERT INTO audit_access (usrid,usrname,timeaudit,url,load_session) 
	VALUES ('".$_SESSION['MVGitHub_idacname']."','".$_SESSION['MVGitHub_acname']."','".$timenowis."','".curPageURL()."','".session_id()."')";
//	mysql_query($insert_audit);
	}

///SMS LENGTH
if (isset($_SESSION['MVGitHub_userteamshortname']))
	{
	$sms_length_initial =160-27; //16 is for the ticket "less 50 for the Trailing Advert"
	//how long is the company shortname
	$sample_tktpref=" Ticket 2LE1306051 , ";
	$sms_reduce_by = strlen("".$_SESSION['MVGitHub_userteamshortname'].$sample_tktpref);
	
	$sms_length=$sms_length_initial-$sms_reduce_by;
	}

///menu sessions 
if (isset($_GET['page']))
	{
	$_SESSION['CFCMS_page'] = preg_replace('/[^a-z\-_0-9\.:\/]/i','',mysql_escape_string(trim($_GET['page'])));
	}
	
if (!isset($_SESSION['CFCMS_page']))
	{
	$_SESSION['CFCMS_page']=1;
	}

if (isset($_GET['view']))
	{
	$_SESSION['sec_view'] = preg_replace('/[^a-z\-_0-9\.:\/\s]/i','',mysql_escape_string(trim($_GET['view'])));
	}

if (isset($_GET['uction']))
	{
	$_SESSION['sec_uction'] = preg_replace('/[^a-z\-_0-9\.:\/\s]/i','',mysql_escape_string(trim($_GET['uction'])));
	}

if (isset($_GET['mod']))
	{
	$_SESSION['sec_mod'] = preg_replace('/[^a-z\-_0-9\.:\/\s]/i','',mysql_escape_string(trim($_GET['mod'])));
	}
		
if (isset($_GET['submod']))
	{
	$_SESSION['sec_submod'] = preg_replace('/[^a-z\-_0-9\.:\/\s]/i','',mysql_escape_string(trim($_GET['submod'])));
	}

//use session to manage what user sees besed on tabs selected
if (isset($_GET['parentviewtab']))
	{
	$_SESSION['parenttabview']=preg_replace('/[^a-z\-_0-9\.:\/\s]/i','',mysql_escape_string(trim($_GET['parentviewtab'])));
	}
if (!isset($_SESSION['parenttabview']))
	{
	$_SESSION['parenttabview']=1;
	}

//use session to manage what user sees besed on buttons selected
if (isset($_GET['btnview']))
	{
	$_SESSION['viewbtn']=preg_replace('/[^a-z\-_0-9\.:\/\s]/i','',mysql_escape_string(trim($_GET['btnview'])));
	}
if (!isset($_SESSION['viewbtn']))
	{
	$_SESSION['viewbtn']=1;
	}

//Tweak for ticket listing sake
if ( (isset($_SESSION['parenttabview'])) && (isset($_SESSION['sec_submod'])) && ($_SESSION['sec_submod']==6) && ($_SESSION['parenttabview']>2) )
	{
	$_SESSION['parenttabview']=1;
	}
	
//Batch View ON or OFF
//display the batch view or the normal view
if ( (isset($_GET['batchview'])) && ($_GET['batchview']=="batch") )
	{
	$_SESSION['batchview']=mysql_escape_string(trim($_GET['batchview']));
	$_SESSION['link_batch'] = "nobatch";
	} else if ( (isset($_GET['batchview'])) && ($_GET['batchview']=="nobatch") ) {
	$_SESSION['batchview']="nobatch";
	$_SESSION['link_batch'] = "batch";
	} 
	
	if (!isset($_SESSION['link_batch'])) 
	{
	$_SESSION['batchview']="nobatch";
	$_SESSION['link_batch'] = "batch";
	}
?>
