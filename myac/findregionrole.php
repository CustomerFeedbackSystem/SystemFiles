<?php
session_start();
require_once('../Connections/connSystem.php');
mysql_select_db($database_connSystem, $connSystem);

$zone=intval($_GET['zone']);

$query="SELECT idusrrole,usrrolename,usrrole_idusrrole,usrroledesc,userteamzonename FROM usrrole 
LEFT JOIN usrac ON usrrole.idusrrole=usrac.usrrole_idusrrole
INNER JOIN usrteamzone ON usrrole.usrteamzone_idusrteamzone=usrteamzone.idusrteamzone 
WHERE usrteamzone.usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']." AND usrteamzone_idusrteamzone=".$zone." ORDER BY usrrolename ASC";

/*$query="SELECT idusrrole,usrrolename FROM usrrole 
WHERE usrteamzone_idusrteamzone=$zone ORDER BY usrrolename ASC";
*/
$result=mysql_query($query);
$num_role=mysql_num_rows($result);
//echo $query;
if ($num_role < 1)
	{
	echo "<div class=\"msg_warning\">".$msg_no_role."</div>";
	} else {

	echo "<select name=\"usrrole\" >";
	echo "<option value=\"NULL\">---</option>";
			while($row=mysql_fetch_array($result)) {
				echo "<option value=\"".$row['idusrrole']."\" title=\"".$row['usrroledesc']."\" ";
			//disable selection if the role has already been selected
				if ($row['usrrole_idusrrole']==$row['idusrrole'])
					{
					echo " disabled=\"disabled\" ";
					}
					echo ">".$row['usrrolename']."</option>";
				} 
			echo "</select>";
		}
?>
<!--
<select name="usrrole">
<option value=""> --- </option>
<?php //while($row=mysql_fetch_array($result)) { ?>
<option value=<?php echo $row['idusrrole']; ?>><?php echo $row['usrrolename']; ?></option>
<?php //} ?>
</select>
-->