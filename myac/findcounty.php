<?php
require_once('../Connections/connSystem.php');
mysql_select_db($database_connSystem, $connSystem);

$countryId=intval($_GET['country']);
$regionId=intval($_GET['region']);

$query="SELECT idloccounty,loccountyname FROM loccounty WHERE locregion_idlocregion=$regionId AND liststatus=1 ORDER BY loccountyname ASC";
$result=mysql_query($query);
//echo $query;
?>
<select name="county">
<option value="">---</option>
<?php while($row=mysql_fetch_array($result)) { ?>
<option value="<?php echo $row['idloccounty']; ?>"><?php echo $row['loccountyname'];?></option>
<?php } ?>
</select>