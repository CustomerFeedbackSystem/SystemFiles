<?php
session_start();

require_once('../Connections/connSystem.php');
mysql_select_db($database_connSystem, $connSystem);
require_once('../assets_backend/be_includes/check_login.php');
if (isset($_POST['act'])) 
	{
	$fyr=trim(mysql_escape_string($_POST['fyr']));
	$fm=trim(mysql_escape_string($_POST['fmonth']));
	$fd=trim(mysql_escape_string($_POST['fdat']));
	
	$tyr=trim(mysql_escape_string($_POST['tyr']));
	$tm=trim(mysql_escape_string($_POST['tmonth']));
	$td=trim(mysql_escape_string($_POST['tdat']));
	
$start = $month = strtotime(''.$fyr.'-'.$fm.'-'.$fd.'');
$end = strtotime(''.$tyr.'-'.$tm.'-'.$td.'');
//$end = $end->modify( '+1 day' );
	}
?>
<form method="post" action="">
<span style="background-color:#CCCCCC; padding:0px">
FROM : 
Year :<input type="text" maxlength="4" name="fyr" value="<?php if (isset($_POST['fyr']))
	{ echo $_POST['fyr'];} else { echo "2014"; } ?>"> |
Month :<select name="fmonth">
<?php 
if (isset($_POST['fmonth']))
	{
	echo "<option value=\"".$_POST['fmonth']."\">".$_POST['fmonth']."</option>";	
	}
?>	
<option value="01">Jan</option>
<option value="02">Feb</option>
<option value="03">Mar</option>
<option value="04">Apr</option>
<option value="05">May</option>
<option value="06">Jun</option>
<option value="07">Jul</option>
<option value="08">Aug</option>
<option value="09">Sep</option>
<option value="10">Oct</option>
<option value="11">Nov</option>
<option value="12">Dec</option>
</select> |
<input type="hidden" name="fdat" value="01">
</span>
<span style="background-color:#CCCCCC; padding:0px 20px">
<br>
TO : 
Year :
<input type="text" name="tyr" value="<?php if (isset($_POST['tyr']))
	{ echo $_POST['tyr'];} else { echo "2014"; } ?>"> |
Month : <select name="tmonth">
<?php 
if (isset($_POST['tmonth']))
	{
	echo "<option value=\"".$_POST['tmonth']."\">".$_POST['tmonth']."</option>";	
	}
?>	
<option value="01">Jan</option>
<option value="02">Feb</option>
<option value="03">Mar</option>
<option value="04">Apr</option>
<option value="05">May</option>
<option value="06">Jun</option>
<option value="07">Jul</option>
<option value="08">Aug</option>
<option value="09">Sep</option>
<option value="10">Oct</option>
<option value="11">Nov</option>
<option value="12">Dec</option>
</select> |
<input type="hidden" name="tdat" value="01">
</span>

<span>
<input type="hidden" value="1" name="act">
<input type="submit" value="Run">
</span>
</form>
<?php
echo "<table border=1 cellpadding=2 cellspacing=0>";
echo "<tr>
	<td>Month</td><td>Reported</td><td>Resolved</td><td>Pending</td>
	</tr>";
if (isset($_POST['act'])) 
	{
		while($month <= $end)
		{
		echo "<tr><td>";
			 echo date('F Y', $month);
			//echo date('Y-d-m', $month), PHP_EOL;
			$month_val=date('m', $month);
			$year_val=date('Y', $month);
			$date_val=date('Y-m-d', $month);
			$date_val_last=date('Y-m-t', $month);
			
			$month = strtotime("+1 month", $month);
			
		echo "</td><td>"; //column 2
		
		$sql_recm="SELECT count(*) as RecordedM FROM tktin 
		WHERE month(timereported)='".$month_val."' AND year(timereported)='".$year_val."'";
		//echo $sql_recm;
		$res_recm=mysql_query($sql_recm);
		$fet_recm=mysql_fetch_array($res_recm);
		echo $fet_recm['RecordedM']; 
				
		echo "</td><td>"; //column 3
		
		$sql_c="SELECT count(*) as Closed FROM tktin 
		WHERE month(timeclosed)='".$month_val."' AND year(timeclosed)='".$year_val."'";
		//echo $sql_recm;
		$res_c=mysql_query($sql_c);
		$fet_c=mysql_fetch_array($res_c);
		echo $fet_c['Closed']; 
		
		echo "</td><td>"; //column 4
				
		$sql_bf="SELECT count(*) as closed,
		(SELECT count(*) FROM tktin 
		WHERE date(timereported)<='".$date_val_last."' 
		AND date(timereported)!='0000-00-00' ) as overall
		FROM tktin 
		WHERE 
		date(timeclosed)!='0000-00-00'
		AND date(timeclosed)<='".$date_val_last."' 
		AND date(timereported)<='".$date_val_last."' 
		AND date(timereported)!='0000-00-00' ";
		//echo $sql_bf;
		//echo $date_val_last;
		$res_bf=mysql_query($sql_bf);
		$fet_bf=mysql_fetch_array($res_bf);
		
		$Brought_Forward_raw=($fet_bf['overall']-$fet_bf['closed']); //pending 
		echo $Brought_Forward_raw;
		echo "</td></tr>";
		}
	}
echo "</table>";	
?>