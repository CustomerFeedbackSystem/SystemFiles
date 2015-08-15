<?php
require_once('../../assets_backend/be_includes/config.php');

if (isset($_GET['reportid']))
	{
	$_SESSION['reportid']=mysql_escape_string(trim($_GET['reportid']));
	} else {
	$_SESSION['reportid']=0;
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Reports Under Construction - NWC</title>
<link href="../../assets_backend/css/style.css" rel="stylesheet" type="text/css" />
<script language="javascript" type="text/javascript" src="../../scripts/datetimepicker.js"></script>
</head>
<body>
<h1>Reports Under Development - NWC</h1>
<div>
	<ol>
    <li class="border_bottom">
    <?php
	$lbl_rpt_1="Closed Tickets within TAT  ( Per Region )";
	?>
    <a class="text_body" <?php if ((isset($_SESSION['reportid'])) &&  ($_SESSION['reportid']==1)) { echo "style=\"text-decoration:none;color:#cc0000;font-weight:bold\""; } ?> href="<?php $_SERVER['PHP_SELF'];?>?reportid=1"><?php echo $lbl_rpt_1;?> </a>
    <br />
    <?php if ((isset($_SESSION['reportid'])) &&  ($_SESSION['reportid']==1)) { ?>
    <form method="get" action="reports_uv_1.php" target="_blank" style="margin:0px">
    <table border="0">
    	<tr>
        	<td><input name="timestart" type="text" id="timestart" value="<?php if (isset($_SESSION['timestart'])) { echo $_SESSION['timestart']; } ?>" onClick="displayDatePicker('timestart');" readonly="readonly"> 
                        <img src="../../assets_backend/btns/cal.gif" alt="Pick a date" width="30" height="30" border="0" align="absmiddle" onClick="displayDatePicker('timestart');">                        </td>
                        
			<td>
            <input name="timestop" type="text"  id="timestop" value="<?php if (isset($_SESSION['timestop'])) { echo $_SESSION['timestop']; } ?>" onClick="displayDatePicker('timestop');" readonly="readonly"> 
                        <img src="../../assets_backend/btns/cal.gif" alt="Pick a date" width="30" height="30" border="0" align="absmiddle" onClick="displayDatePicker('timestop');">
            </td> 
            <td>
            <input type="hidden" name="reportname" value="<?php echo $lbl_rpt_1;?>" />
            <input type="submit" value="Run Report" />
            </td>                       
        </tr>
    </table>
    </form>
    <?php } ?>
    </li>
   
    
    <li class="border_bottom">
    <?php
	 $lbl_rpt_2="Closed Tickets BEYOND TAT  ( Per Region )";
	?>
    <a class="text_body" <?php if ((isset($_SESSION['reportid'])) &&  ($_SESSION['reportid']==2)) { echo "style=\"text-decoration:none;color:#cc0000;font-weight:bold\""; } ?> href="<?php $_SERVER['PHP_SELF'];?>?reportid=2"><?php echo $lbl_rpt_2;?></a>
    <br />
    <?php if ((isset($_SESSION['reportid'])) &&  ($_SESSION['reportid']==2)) { ?>
    <form method="get" action="reports_uv_2.php" target="_blank" style="margin:0px">
    <table border="0">
    	<tr>
        	<td><input name="timestart" type="text" id="timestart" value="<?php if (isset($_SESSION['timestart'])) { echo $_SESSION['timestart']; } ?>" onClick="displayDatePicker('timestart');" readonly="readonly"> 
                        <img src="../../assets_backend/btns/cal.gif" alt="Pick a date" width="30" height="30" border="0" align="absmiddle" onClick="displayDatePicker('timestart');">                        </td>
                        
			<td>
            <input name="timestop" type="text"  id="timestop" value="<?php if (isset($_SESSION['timestop'])) { echo $_SESSION['timestop']; } ?>" onClick="displayDatePicker('timestop');" readonly="readonly"> 
                        <img src="../../assets_backend/btns/cal.gif" alt="Pick a date" width="30" height="30" border="0" align="absmiddle" onClick="displayDatePicker('timestop');">
            </td> 
            <td>
            <input type="hidden" name="reportname" value="<?php echo $lbl_rpt_2;?>" />
            <input type="submit" value="Run Report" />
            </td>                       
        </tr>
    </table>
    </form>
    <?php } ?>
    </li>
    
    <li class="border_bottom">
    <?php $lbl_rpt_3="Ticket Received Per Category ( Per Region ) ";?>
    <a class="text_body" <?php if ((isset($_SESSION['reportid'])) &&  ($_SESSION['reportid']==3)) { echo "style=\"text-decoration:none;color:#cc0000;font-weight:bold\""; } ?> href="<?php $_SERVER['PHP_SELF'];?>?reportid=3"><?php echo $lbl_rpt_3;?></a>
    <br />
    <?php if ((isset($_SESSION['reportid'])) &&  ($_SESSION['reportid']==3)) { ?>
    <form method="get" action="reports_uv_3.php" target="_blank" style="margin:0px">
    <table border="0">
    	<tr>
        	<td><input name="timestart" type="text" id="timestart" value="<?php if (isset($_SESSION['timestart'])) { echo $_SESSION['timestart']; } ?>" onClick="displayDatePicker('timestart');" readonly="readonly"> 
                        <img src="../../assets_backend/btns/cal.gif" alt="Pick a date" width="30" height="30" border="0" align="absmiddle" onClick="displayDatePicker('timestart');">                        </td>
                        
			<td>
            <input name="timestop" type="text"  id="timestop" value="<?php if (isset($_SESSION['timestop'])) { echo $_SESSION['timestop']; } ?>" onClick="displayDatePicker('timestop');" readonly="readonly"> 
                        <img src="../../assets_backend/btns/cal.gif" alt="Pick a date" width="30" height="30" border="0" align="absmiddle" onClick="displayDatePicker('timestop');">
            </td> 
            <td>
            <input type="hidden" name="reportname" value="<?php echo $lbl_rpt_3;?>" />
            <input type="submit" value="Run Report" />
            </td>                       
        </tr>
    </table>
    </form>
    <?php } ?>
    </li>
    
    
    <li class="border_bottom">
    <?php $lbl_rpt_4="Monthly Report : Complaints Resolution Compliance ( Per Region ) ";?>
    <a class="text_body" <?php if ((isset($_SESSION['reportid'])) &&  ($_SESSION['reportid']==4)) { echo "style=\"text-decoration:none;color:#cc0000;font-weight:bold\""; } ?> href="<?php $_SERVER['PHP_SELF'];?>?reportid=4"><?php echo $lbl_rpt_4;?></a>
    <br />
    <?php if ((isset($_SESSION['reportid'])) &&  ($_SESSION['reportid']==4)) { ?>
    <form method="get" action="../reports/reports_uv_4.php" target="_blank" style="margin:0px">
    <table border="0">
    	<tr>
        	<td>
            <?php
			$sql_month="SELECT report_month_lbl,report_month_val FROM reports_var_months ORDER BY list_order ASC";
			$res_month=mysql_query($sql_month);
			$num_month=mysql_num_rows($res_month);
			$fet_month=mysql_fetch_array($res_month);
			?>
            <select name="month" style="padding:0px">
            <option value="">--Select Month--</option>
            <?php
			do {
			echo "<option value=\"".$fet_month['report_month_val']."\">".$fet_month['report_month_lbl']."</option>";
			} while ($fet_month=mysql_fetch_array($res_month));
			?>
            </select>
            </td>
                        
			<td>
            <?php
			$sql_yr="SELECT report_year_lbl FROM reports_var_years ORDER BY report_year_lbl ASC";
			$res_yr=mysql_query($sql_yr);
			$num_yr=mysql_num_rows($res_yr);
			$fet_yr=mysql_fetch_array($res_yr);
			?>
            <select name="year" style="padding:0px">
            <option value="">--Select Year--</option>
            <?php
			do {
			echo "<option value=\"".$fet_yr['report_year_lbl']."\">".$fet_yr['report_year_lbl']."</option>";
			} while ($fet_yr=mysql_fetch_array($fet_yr));
			?>
            </select>
            </td> 
            <td>
            <input type="hidden" name="reportname" value="<?php echo $lbl_rpt_4;?>" />
            <input type="submit" value="Run Report" />
            </td>                       
        </tr>
    </table>
    </form>
    <?php } ?>
    </li>
    
    
     <li class="border_bottom">
    <?php $lbl_rpt_5="Monthly Report : Average Resolution Time ( Per Region ) ";?>
    <a class="text_body" <?php if ((isset($_SESSION['reportid'])) &&  ($_SESSION['reportid']==5)) { echo "style=\"text-decoration:none;color:#cc0000;font-weight:bold\""; } ?> href="<?php $_SERVER['PHP_SELF'];?>?reportid=5"><?php echo $lbl_rpt_5;?></a>
    <br />
    <?php if ((isset($_SESSION['reportid'])) &&  ($_SESSION['reportid']==5)) { ?>
    <form method="get" action="../reports/reports_uv_5.php" target="_blank" style="margin:0px">
    <table border="0">
    	<tr>
        	<td><input name="timestart" type="text" id="timestart" value="<?php if (isset($_SESSION['timestart'])) { echo $_SESSION['timestart']; } ?>" onClick="displayDatePicker('timestart');" readonly="readonly"> 
                        <img src="../../assets_backend/btns/cal.gif" alt="Pick a date" width="30" height="30" border="0" align="absmiddle" onClick="displayDatePicker('timestart');">                        </td>
                        
			<td>
            <input name="timestop" type="text"  id="timestop" value="<?php if (isset($_SESSION['timestop'])) { echo $_SESSION['timestop']; } ?>" onClick="displayDatePicker('timestop');" readonly="readonly"> 
                        <img src="../../assets_backend/btns/cal.gif" alt="Pick a date" width="30" height="30" border="0" align="absmiddle" onClick="displayDatePicker('timestop');">
            </td> 
            <td>
            <input type="hidden" name="reportname" value="<?php echo $lbl_rpt_3;?>" />
            <input type="submit" value="Run Report" />
            </td>                       
        </tr>
    </table>
    </form>
    <?php } ?>
    </li>
    
    </ol>
</div>
</body>
</html>
