<?php
require_once('../../assets_backend/be_includes/config.php');

if (isset($_GET['display']))
	{
	$_SESSION['display_type']=trim($_GET['display']);
	} else {
	$_SESSION['display_type']="graph";
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

if (isset($_GET['reportname']))
	{
	$_SESSION['reportname']=trim($_GET['reportname']);
	}

	
?>	
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="../css_report_print.css" rel="stylesheet" type="text/css" media="print" />
<link href="../css_report.css" rel="stylesheet" type="text/css" />
<title><?php echo $_SESSION['reportname'];?></title>
</head>
<body>
<div style="padding:10px">
    <div >
    <div class="text_body_vlarge"><?php echo $_SESSION['MVGitHub_acteam'];?></div>
    <div class="text_body_large"><?php echo $_SESSION['reportname'];?></div>
    </div>
    <?php
	//validate here before displaying teh data
	?>
    <div>
    <div class="rpt_period">
    Report Period : <span ><?php echo date("D, M d, Y",strtotime($_SESSION['timestart'])); ?> - <?php echo date("D, M d, Y",strtotime($_SESSION['timestop'])); ?>
    </span>
    </div>
    <div class="hidden">
            <table border="0" width="100%">
                <tr>
                    <td class="text_small" width="80%">
                     <span class="text_small" >
                
                <a href="#" style="color:#CCCCCC" class="text_body_mod">Switch to Graph</a>                </span>                    </td>
                    <td align="right" class="text_body_mod">
                    <a href="#" onClick="window.print()">Print</a>                    </td>
                    <td align="right" class="text_body_mod">
                    <form method="post" action="" name="excel" target="_blank">
                    <a href="#" onClick="document.forms['excel'].submit()">Export to Excel</a>
                    </form>
                    </td>
                </tr>
            </table>
            </div>
    </div>
    <div style="padding:10px 5px">
    <?php
    //run the regions
    $sql="SELECT idusrteamzone,userteamzonename,region_pref
	FROM usrteamzone WHERE usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']." ORDER BY idusrteamzone ASC";
    $res=mysql_query($sql);
    $num=mysql_num_rows($res);
    $fet=mysql_fetch_array($res);
    //echo $sql;
    if ($num > 0)
        {
    ?>
    <table border="0" cellpadding="2" cellspacing="0">
        <tr>
          <td class="tbl_h" valign="top">
          Region
          </td>
           <td class="tbl_h" valign="top">
          Category</td>
           <td class="tbl_h" valign="top">
          Pending Tickets</td>
           <td class="tbl_h" valign="top">Tickets Per Staff</td>
           <td class="tbl_h" valign="top">
          % Exceeding Task TAT
          </td>
           <td class="tbl_h" valign="top">% Exceeding Ticket TAT</td>
        </tr>
        <tr>
        	<td>
            asdf
            </td>
        </tr>
    <?php
	}
	?>
    </table>    
    <?php    
        } else {
        
        echo "No Data to Generate Report";
        
        }
    ?>
    </div>
</div>
</body>
</html>
