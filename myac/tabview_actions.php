<?php
require_once('../assets_backend/be_includes/check_login_easy.php');

//update action properties
if ( (isset($_GET['status_action'])) && (isset($_GET['turn'])) )
	{
	
	$uturn = mysql_escape_string(trim($_GET['turn']));
	$saction = mysql_escape_string(trim($_GET['status_action']));
	
		if ($uturn == "on") //if needed to turn on the option
			{
			//first, ensure there is no similar record
			$sql_similar = "SELECT idwftskstatus FROM wftskstatus WHERE wftskstatustypes_idwftskstatustypes='".$saction."' AND wftskflow_idwftskflow=".$_SESSION['idflow']."";
			$res_similar = mysql_query($sql_similar);
			$num_similar = mysql_num_rows($res_similar);
			
			//then add
			if ($num_similar < 1) //no record found
				{
				$sql_add="INSERT INTO wftskstatus (wftskstatustypes_idwftskstatustypes,wftskflow_idwftskflow,createdby,createdon) 
				VALUES ('".$saction."','".$_SESSION['idflow']."','".$_SESSION['MVGitHub_idacname']."','".$timenowis."')";
				
				mysql_query($sql_add);
				
				$msg_ok="<span class=\"msg_success_small\">".$msg_changes_saved."</span>";//and acknowledge by giving a message
				}
			
			}
			
		if ($uturn == "off") //if needed to turn on the option
			{		
			//then remove
			$sql_remove = "DELETE FROM wftskstatus WHERE wftskstatustypes_idwftskstatustypes=".$saction." AND wftskflow_idwftskflow=".$_SESSION['idflow']." LIMIT 1";
			mysql_query($sql_remove);
			
			$msg_ok="<span class=\"msg_success_small\">".$msg_changes_saved."</span>";//and acknowledge by giving a message
			
			}
	
	}

//get the actions from the dtabase
$sql_actions = "SELECT idwftskstatustypes,wftskstatustype,wftskstatustypedesc FROM wftskstatustypes ORDER BY listorder ASC";
$res_actions = mysql_query($sql_actions);
$fet_actions = mysql_fetch_array($res_actions);
$num_actions = mysql_num_rows($res_actions);
?>
<div class="text_body">
<div>
<?php if (isset($msg_ok)) { echo $msg_ok; } ?>
</div>
<table border="0" width="650" cellpadding="2" cellspacing="0">
	<?php
	do {
	?>
<tr <?php 
// technocurve arc 3 php mv block2/3 start
echo " style=\"background-color:$mocolor\" onMouseOver=\"this.style.backgroundColor='$mocolor3'\" onMouseOut=\"this.style.backgroundColor='$mocolor'\"";
// technocurve arc 3 php mv block2/3 end
?>>
    	<td width="20" height="35" align="left" class="tbl_data">
        <?php
		//check if this action item has been allocated to this task
		$sql_allocated="SELECT wftskstatustypes_idwftskstatustypes FROM wftskstatus WHERE wftskflow_idwftskflow=".$_SESSION['idflow']." AND wftskstatustypes_idwftskstatustypes=".$fet_actions['idwftskstatustypes']." LIMIT 1";
		$res_allocated=mysql_query($sql_allocated);
		$num_allocated=mysql_num_rows($res_allocated);
		?>
        <a id="button_check<?php if ($num_allocated>0) { echo "_on";  }?>" href="<?php echo $_SERVER['PHP_SELF'];?>?status_action=<?php echo $fet_actions['idwftskstatustypes'];?>&amp;turn=<?php if ($num_allocated>0) { echo "off"; } else { echo "on"; }?>"></a>
        </td>
      	<td width="177" height="35" class="tbl_data">
        <strong><?php echo $fet_actions['wftskstatustype'];?> </strong>
        </td>
   	  	<td width="441" height="35" class="tbl_data">
        <?php echo $fet_actions['wftskstatustypedesc'];?>
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
	} while ($fet_actions = mysql_fetch_array($res_actions));
	?>
</table>
</div>