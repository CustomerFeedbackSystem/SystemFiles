<?php
require_once('../Connections/connSystem.php');
mysql_select_db($database_connSystem, $connSystem);

$country=intval($_GET['country']);

$query="SELECT idlocregion,locregionname FROM locregion WHERE loccountry_idloccountry=$country ORDER BY locregionname ASC";
$result=mysql_query($query);
//echo $query;
?>
<select name="region" onChange="getcounty(<?php echo $country;?>,this.value)">
<option value=""> --- </option>
<?php while($row=mysql_fetch_array($result)) { ?>
<option value=<?php echo $row['idlocregion']; ?>><?php echo $row['locregionname']; ?></option>
<?php } ?>
</select>