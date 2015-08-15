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
	
	$tyr=trim(mysql_escape_string($_POST['fyr']));
	$tm=trim(mysql_escape_string($_POST['fmonth']));
	$td=trim(mysql_escape_string($_POST['tdat']));

$begin = new DateTime( ''.$fyr.'-'.$fm.'-'.$fd.'' );
$end = new DateTime( ''.$tyr.'-'.$tm.'-'.$td.'' );
$end =  $end->modify( '+1 day' );
	
$interval = DateInterval::createFromDateString('1 day');
$period = new DatePeriod($begin, $interval, $end);
	}
?>
<form method="post" action="">
<span style="background-color:#CCCCCC; padding:0px">
Year :<input name="fyr" type="text" value="2014" size="15"> 
|
Month : <select name="fmonth">
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
</select>
(From) Date : <input name="fdat" type="text" value="<?php if (isset($_POST['fdat']))
	{ echo $_POST['fdat'];} else { echo "01"; } ?>" size="5" maxlength="2"> 
- To (Date)
<input name="tdat" type="text" value="<?php if (isset($_POST['tdat']))
	{ echo $_POST['tdat'];} else { echo "31"; } ?>" size="5" maxlength="2">
</span>

<span>
<input type="hidden" value="1" name="act">
<input type="submit" value="Run">
</span>
</form>
<?php
echo "<table border=1 cellpadding=2 cellspacing=0>";
echo "<tr>
	<td>Date</td><td>BF</td><td>Recorded</td><td>Total Pending</td><td>Closed within TAT</td><td>Closed Beyond TAT</td><td>Total Closed</td><td>C/F</td>
	</tr>";
if (isset($_POST['act'])) 
	{
	foreach ( $period as $dt )
	{
  	//echo $dt->format( "l Y-m-d H:i:s\n" );
	//echo $dt->format( "l Y-m-d H:i:s\n" );
	echo "<tr><td>";
	echo $dt->format( "Y-m-d" );
	$var_date=$dt->format( "Y-m-d" );
	echo "</td><td>";//column 1
	
	$sql_bf="SELECT count(*) as closed,
	(SELECT count(*) FROM tktin WHERE timereported<'".$var_date." 00:00:00' AND  date(timereported)!='0000-00-00' ) as overall
	 FROM tktin where timeclosed<'".$var_date." 00:00:00' AND  date(timeclosed)!='0000-00-00' ";	
	$res_bf=mysql_query($sql_bf);
	$fet_bf=mysql_fetch_array($res_bf);
	
	$Brought_Forward_raw=($fet_bf['overall']-$fet_bf['closed']);
	echo $Brought_Forward_raw;
	echo "</td><td>";//column 2
	
	$sql_recm="SELECT count(*) as RecordedM FROM tktin 
	WHERE date(timereported)='".$var_date."' ";
	//echo $sql_recm;
	$res_recm=mysql_query($sql_recm);
	$fet_recm=mysql_fetch_array($res_recm);
	echo $fet_recm['RecordedM'];
	
	echo "</td><td>";//column 3
	$totpen=$Brought_Forward_raw+$fet_recm['RecordedM'];
	echo $totpen;
	echo "</td><td>";//column 4
	
	$sql_wtat="SELECT count(*) as withinTAT FROM tktin 
	INNER JOIN tktcategory ON tktin.tktcategory_idtktcategory=tktcategory.idtktcategory 
	WHERE 
	TIME_TO_SEC(TIMEDIFF(tktin.timeclosed,tktin.timereported)) <= tktcategory.tat 
	AND date(tktin.timeclosed)='".$var_date."' 
	AND tktin.timeclosed!='0000-00-00 00:00:00'
	AND tktstatus_idtktstatus>3";
	
	//echo $sql_wtat;
	$res_wtat=mysql_query($sql_wtat);
	$fet_wtat=mysql_fetch_array($res_wtat);
	echo number_format($fet_wtat['withinTAT'],0); 
	
	echo "</td><td>";//column 5
	
	$sql_btat="SELECT count(*) as beyondTAT FROM tktin 
	INNER JOIN tktcategory ON tktin.tktcategory_idtktcategory=tktcategory.idtktcategory 
	WHERE 
	TIME_TO_SEC(TIMEDIFF(tktin.timeclosed,tktin.timereported)) > tktcategory.tat  					
	AND date(tktin.timeclosed)='".$var_date."' 
	AND tktin.timeclosed!='0000-00-00 00:00:00'
	AND tktstatus_idtktstatus>3";
	//echo $sql_bf;
	$res_btat=mysql_query($sql_btat);
	$fet_btat=mysql_fetch_array($res_btat);
	echo $fet_btat['beyondTAT'];
	
	echo "</td><td>";//column 6
	$totclsd=($fet_wtat['withinTAT']+$fet_btat['beyondTAT']);
	echo $totclsd;
	
	echo "</td><td>"; //column 7
	
	echo $totpen-$totclsd;
	
	echo "</td></tr>";
	}
echo "</table>";
}
?>