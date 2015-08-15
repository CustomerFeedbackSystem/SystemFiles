<?php
require_once('../assets_backend/be_includes/config.php');
require_once('../assets_backend/be_includes/check_login_easy.php');

if ( (isset($_GET['pagetitle'])) && ($_GET['pagetitle']!="") )
	{
	$_SESSION['report_title'] = $_GET['pagetitle'];
	}

if ((isset($_GET['timestart'])) && ($_GET['timestart']!=''))
	{
	$_SESSION['timestart']=preg_replace('/[^a-z\-_0-9\.:\/\s]/i','',mysql_escape_string(trim($_GET['timestart'])));
	} 
	
	if (!isset($_SESSION['timestart']))
	{
	$error_1 = "<div class=\"msg_warning\">Start Date is Missing</div>";
	}
	
if ((isset($_GET['timestop'])) && ($_GET['timestop']!=''))
	{
	$_SESSION['timestop']=preg_replace('/[^a-z\-_0-9\.:\/\s]/i','',mysql_escape_string(trim($_GET['timestop'])));
	} 
	
	if (!isset($_SESSION['timestop'])) {
	$error_2="<div class=\"msg_warning\">Ending Date is Missing</div>";
	}

if ( (isset($_GET['reportid'])) && ($_GET['reportid']!='') )
	{
	$_SESSION['reportid'] = mysql_escape_string(trim($_GET['reportid']));
	}
	
	if (!isset($_SESSION['reportid'])) {
	$error_3="<div class=\"msg_warning\">Report Type / Specification is missing</div>";
	}

//refine the report title
//for TICKET REPORTS HERE
if ( ($_SESSION['reportid']>0) && ($_SESSION['sec_submod']==28) )
	{
	$sql_title="SELECT tktcategoryname FROM tktcategory WHERE idtktcategory=".$_SESSION['reportid']." LIMIT 1 ";
	$res_title=mysql_query($sql_title);
	$fet_title=mysql_fetch_array($res_title);
	
	$_SESSION['report_subtitle']=$fet_title['tktcategoryname'];
	}
	
if (($_SESSION['reportid']=="-1") && ($_SESSION['sec_submod']==28)) 
	{
	$_SESSION['report_subtitle']="Overall Ticket Volume";
	}
	
if (($_SESSION['reportid']=="-1") && ($_SESSION['sec_submod']==29)) 
	{
	$_SESSION['report_subtitle']="Overview (".$_SESSION['MVGitHub_userteamshortname'].")";
	}	

if (($_SESSION['reportid']=="-2") && ($_SESSION['sec_submod']==29)) 
	{
	$_SESSION['report_subtitle']="My Team (".$_SESSION['MVGitHub_userteamshortname'].")";
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $_SESSION['report_title'];?></title>
<link href="css_report_print.css" rel="stylesheet" type="text/css" media="print" />
<link href="css_report.css" rel="stylesheet" type="text/css" />
<script language="javascript" type="text/javascript" src="../scripts/datetimepicker.js"></script>
</head>
<body>
<div style="padding:5px;">
	<div class="brline">
		<div class="text_body_large" ><?php echo $_SESSION['MVGitHub_acteam'];?></div>
		<div class="text_body_mod"><?php echo $_SESSION['report_title']."&nbsp;-&nbsp;".$_SESSION['report_subtitle'];?></div>
	</div>
    <?php
	if ( (!isset($error_1)) && (!isset($error_2)) && (!isset($error_3)) && (isset($_SESSION['sec_submod'])) ) {
	?>
	<div class="text_timestamp">
	<?php echo date("l jS  F Y",strtotime($_SESSION['timestart'])) ?> - <?php echo date("l dS  F Y",strtotime($_SESSION['timestop'])) ?>
    </div>
	<div>
	<?php
	if ( ($_SESSION['reportid']=="-1") || ($_SESSION['reportid']=="-2"))
		{
		require_once('../myac/report_'.$_SESSION['sec_submod'].'_'.$_SESSION['reportid'].'.php');
		}
		
	if ($_SESSION['reportid'] > 0 )	
		{
		require_once('../myac/report_'.$_SESSION['sec_submod'].'.php');	
		}
	?>
	</div>
    <?php 
	} else { 
	echo "<div style=\"text-align:center\">";
	if (isset($error_1)) { echo $error_1; }
	if (isset($error_2)) { echo $error_2; }
	if (isset($error_3)) { echo $error_3; }
	echo "</div>";
	} 
	?>
</div>
<div class="text_timestamp">
Printed by : <?php echo $_SESSION['MVGitHub_usrtitle']." ".$_SESSION['MVGitHub_usrlname']."-".$_SESSION['MVGitHub_userteamzone'].",".$_SESSION['MVGitHub_acteam'];?>
</div>
</body>
</html>