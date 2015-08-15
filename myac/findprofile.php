<?php
require_once('../Connections/connSystem.php');
mysql_select_db($database_connSystem, $connSystem);

$zone=intval($_GET['zone']);

$query="SELECT idsysprofiles,sysprofile FROM sysprofiles WHERE usrteamzone_idusrteamzone=$zone ORDER BY sysprofile ASC";
$result=mysql_query($query);
//echo $query;
?>
<select name="profile">
<option value=""> --- </option>
<?php while($row=mysql_fetch_array($result)) { ?>
<option value=<?php echo $row['idsysprofiles']; ?>><?php echo $row['sysprofile']; ?></option>
<?php } ?>
</select>