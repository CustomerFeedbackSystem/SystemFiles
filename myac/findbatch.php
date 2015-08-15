<?php
session_start();
require_once('../Connections/connSystem.php');
mysql_select_db($database_connSystem, $connSystem);

//check the status of the tickets
$res_tktstatus=mysql_query("SELECT tktstatus_idtktstatus FROM tktin WHERE idtktinPK=".$_SESSION['batch_tktid']."");
$fet_tktstatus=mysql_fetch_array($res_tktstatus);

$batchtype=intval($_GET['batch']);

$query="SELECT idwftasks_batch,batch_no,countbatch,batchtypelbl,region_pref,batch_no_verbose,
(SELECT count(*) FROM tktin WHERE wftasks_batch_idwftasks_batch=idwftasks_batch) as batched,
(SELECT max_size FROM wftasks_batchtype WHERE idwftasks_batchtype=wftasks_batch.wftasks_batchtype_idwftasks_batchtype LIMIT 1) as maxsize
 FROM wftasks_batch
INNER JOIN wftasks_batchtype ON wftasks_batch.wftasks_batchtype_idwftasks_batchtype=wftasks_batchtype.idwftasks_batchtype
INNER JOIN usrteamzone ON wftasks_batch.usrteamzone_idusrteamzone=usrteamzone.idusrteamzone
WHERE usrteamzone.usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']." 
AND idwftasks_batchtype=".$batchtype." 
AND wftasks_batch.usrteamzone_idusrteamzone=".$_SESSION['MVGitHub_userteamzoneid']."
AND wftasks_batch.createdon > NOW() - INTERVAL 30 DAY 
ORDER BY wftasks_batch.createdon DESC LIMIT 10";
$result=mysql_query($query);
//echo $query;
?>
<select name="batch">
<option value=""> --- </option>
<?php while($row=mysql_fetch_array($result)) { 
if  (
	($row['batched']>=$row['maxsize'])
	||
	($fet_tktstatus['tktstatus_idtktstatus']>2)
	)
	{ 
	$flag_disable=" disabled=\"disabled\" ";
	} else {
	$flag_disable="";
	}
?>
<option <?php echo $flag_disable;?> value=<?php echo $row['idwftasks_batch']; ?>><?php echo $row['batch_no_verbose']; ?> <?php if ($row['countbatch'] > 0) { echo "&nbsp;(&nbsp;".$row['countbatch']. " Record";  if ($row['countbatch'] > 1) { echo "s"; } echo "&nbsp;)"; }?></option>
<?php } ?>
</select>