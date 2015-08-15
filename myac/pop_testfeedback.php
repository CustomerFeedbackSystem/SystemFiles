<?php
require_once('../assets_backend/be_includes/config.php');
//require_once('../Connections/connSystem.php');
//mysql_select_db($database_connSystem, $connSystem);
require_once('../assets_backend/be_includes/check_login_easy.php');

if ((isset($_POST['formaction'])) && ($_POST['formaction']==1) )
	{
	//clean up the variables
	$dtitle=mysql_escape_string(trim($_POST['defect_title']));
	$ddesc=mysql_escape_string(trim($_POST['defect_description']));
	$drep=mysql_escape_string(trim($_POST['defect_replication']));
	$dsev=mysql_escape_string(trim($_POST['defect_severity']));
	$darea=mysql_escape_string(trim($_POST['defect_area']));
	$dstat=mysql_escape_string(trim($_POST['defect_status']));
	
	//validate
	if ( (strlen($dtitle)<1) ||  (strlen($ddesc)<1) || (strlen($drep)<1) || (strlen($dsev)<1) || (strlen($darea)<1) || (strlen($dstat)<1)  )
		{
		echo "<div style=\"text-align:center;font-family:arial;color:#ffffff;font-weight:bold; background-color:#FF0000\">Please fill in all the fields.</div>";
		} else {
		//process
		$sql_insert="INSERT INTO z_testfeedback ( tester, defect_title, defect_description, defect_replication, defect_severity, defect_area, defect_status, createdon) 
		VALUES ( '".$_SESSION['tester']."', '".$dtitle."', '".$ddesc."', '".$drep."', '".$dsev."', '".$darea."', '".$dstat."', '".$timenowis."')";
		mysql_query($sql_insert);
		
		echo "<div style=\"text-align:center;font-family:arial;color:#ffffff;font-weight:bold; background-color:#009900\">Your Feedback has been posted successfully</div>";
		echo "<a style=\"text-align:center;font-family:arial; font-size:11px\" href=\"pop_testfeedback.php?viewlist=off\">[View Form]</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a style=\"text-align:center;font-family:arial; font-size:11px\" href=\"pop_testfeedback.php?viewlist=on\">[View List]</a>";
		exit;
		}
	//acknowledge
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Feedback</title>
<link href="../assets_backend/css/style.css" rel="stylesheet" type="text/css" />
</head>
<body>
<?php
if ((isset($_REQUEST['viewlist'])) && ($_REQUEST['viewlist']=="on")) 
{ 
?>
<div class="text_body" style="text-align:right; font-weight:bold; padding:5px">
<a href="<?php echo $_SERVER['PHP_SELF'];?>?viewlist=off">View Feedback Form</a>
</div>

<table border="0" cellpadding="2" cellspacing="0" width="100%">
	<tr>
    	<td class="tbl_h">
        Title
        </td>
        <td class="tbl_h">
        Issue
        </td>
        <td class="tbl_h">
        Severity
        </td>
        <td class="tbl_h">
        Status
        </td>
         <td class="tbl_h">
        Reported by
        </td>
    </tr>
    <?php
	$sqllist="SELECT * FROM z_testfeedback ORDER BY createdon DESC";
	$reslist=mysql_query($sqllist);
	$numlist=mysql_num_rows($reslist);
	$fetlist=mysql_fetch_array($reslist);
	do {
	?>
    <tr <?php 
// technocurve arc 3 php mv block2/3 start
echo " style=\"background-color:$mocolor\" onMouseOver=\"this.style.backgroundColor='$mocolor3'\" onMouseOut=\"this.style.backgroundColor='$mocolor'\"";
// technocurve arc 3 php mv block2/3 end
?>>
    	<td class="tbl_data">
        <?php echo $fetlist['defect_title'];?>
        </td>
        <td class="tbl_data">
        <?php echo $fetlist['defect_description'];?>
        </td>
        <td class="tbl_data">
        <?php echo $fetlist['defect_severity'];?>
        </td>
        <td class="tbl_data">
        <?php echo $fetlist['defect_status'];?>
        </td>
        <td class="tbl_data">
        <?php echo $fetlist['tester'];?>
        </td>
    </tr>
    <?php 
// technocurve arc 3 php mv block3/3 start
if ($mocolor == $mocolor1) {
	$mocolor = $mocolor2;
} else {
	$mocolor = $mocolor1;
}
// technocurve arc 3 php mv block3/3 end
?> 
	<?php
	} while ($fetlist=mysql_fetch_array($reslist));
	?>
</table>
<?php } ?>
<?php
if ((!isset($_REQUEST['viewlist'])) || ($_REQUEST['viewlist']!="on")) 
{ 
?>
<form method="post" action="">
<table border="0" cellpadding="2" cellspacing="0" width="100%">
	<tr>
    	<td class="msg_instructions">
        Please fill in the form below to submit feedback
        </td>
        <td class="text_body" align="right" style="font-weight:bold; position:fixed; right:10px">
        <a href="<?php echo $_SERVER['PHP_SELF'];?>?viewlist=on">View Feedback &amp; Status for <?php 
		$sql="SELECT count(*) AS no FROM z_testfeedback";
		$res=mysql_query($sql);
		$fet=mysql_fetch_array($res);
		echo $fet['no']. " items";
		?> &raquo;</a>
        </td>
  </tr>
	<tr>
    	<td class="tbl_data"><strong>
        Reported By        </strong></td>
       	<td class="tbl_data">
        <?php echo $_SESSION['tester'];?>        </td>
    </tr>
    <tr>
    	<td class="tbl_data"><strong>
        Title<em> (eg: Cannot Add Item )</em></strong></td>
   	  <td class="tbl_data">
        <input name="defect_title" type="text" id="defect_title" value="" size="40" maxlength="150" />        </td>
    </tr>
	<tr>
    	<td valign="top" class="tbl_data"><strong>
        Defect / Issue Description</strong></td>
       	<td class="tbl_data">
        <textarea name="defect_description" cols="40" rows="3"></textarea>        </td>
	</tr>
	<tr>
    	<td class="tbl_data"><strong>
        How to Replicate It</strong></td>
       	<td class="tbl_data"><textarea name="defect_replication" cols="40" rows="3"></textarea></td>
	</tr>
	<tr>
    	<td class="tbl_data"><strong>Severity</strong></td>
       	<td class="tbl_data">
        <select name="defect_severity">
        <option value="Just_A_Comment">Just A Comment</option>
        <option value="Good_To_Have">Good_To_Have</option>
        <option value="Important">Important</option>
        <option value="Critical">Critical - Must Have</option>
        </select>        </td>
	</tr>
	<tr>
    	<td class="tbl_data"><strong> Defect Area<em> (eg: Dashboard &gt; View Report )</em></strong></td>
       	<td class="tbl_data">
        <input type="text" value="" maxlength="150" size="40" name="defect_area" />        </td>
	</tr>
	<tr>
    	<td class="tbl_data"><strong>
        Defect Status</strong></td>
       	<td class="tbl_data">
        <select name="defect_status">
        <option value="Open" selected="selected"> Open</option>
        <option value="Work_In_Progress" disabled="disabled">Work_In_Progress </option>
        <option value="Fixed" disabled="disabled">Fixed</option>
        <option value="Closed" disabled="disabled">Closed</option>
        </select>        </td>
	</tr>
	<tr>
    	<td height="50" class="tbl_data">&nbsp;</td>
       	<td class="tbl_data">
        <input type="submit" value="Submit Feedback"  />
        <input type="hidden" value="1"  name="formaction" />
        </td>
	</tr>
</table>
</form>
<?php } ?>
</body>
</html>
