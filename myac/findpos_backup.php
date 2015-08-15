<?php
require_once('../assets_backend/be_includes/config.php');
require_once('../Connections/connSystem.php');
mysql_select_db($database_connSystem, $connSystem);

require_once('../assets_backend/be_includes/check_login_easy.php');

//get the objectid and it's position.
//the options are either the previous object id position ie [L] or [R] or Center [C]
if ( (isset($_GET['list_order'])) && (strlen($_GET['list_order']) > 0) )
	{
	//first, get the values
	$rawdata=trim($_GET['list_order']);
	$rawdata_array=explode('_',''.$_GET['list_order'].'');
	
	$listorder=$rawdata_array['0'];// list order
	$hpos=$rawdata_array['1'];//h position
	$symbolid=$rawdata_array['2']; //check the symbol id
	$idwflow=$rawdata_array['3']; //wkflow id
	}

if ( (isset($listorder)) && (strlen($listorder) > 0) )
	{
	
	if ($symbolid==5)
		{	
		$query="SELECT h_pos FROM wftskflow WHERE wfproc_idwfproc=".$_SESSION['wfselected']." AND h_pos=".$rawdata_array['1']." AND listorder='".$listorder."' LIMIT 1";
		} else {
		$query="SELECT h_pos FROM wftskflow WHERE wfproc_idwfproc=".$_SESSION['wfselected']." AND listorder='".$listorder."' LIMIT 1";
		}
		$result=mysql_query($query);
		$num=mysql_num_rows($result);
		//echo $query;
		?>
		<select name="pos_side">
		<?php
		//if the above result 
		if (($num==0) && ($symbolid==5) && ($row['h_pos']!='-1'))
			{
			echo "<option value='-1'>[L] Left</option>";
			echo "<option value='1'>[R] Right</option>";
			}	
		
		while($row=mysql_fetch_array($result)) { 
		//determine what to show the admin
		if ($hpos!=0) { //if the previous item was not center, then show "breakaway"
		$suf="[Break]";
		} else {
		$suf="";
		}
		
		
	if ($symbolid!=5)
		{
		if ($row['h_pos']=='-1')
			{
			$position="[L] Left";
			$position_title="Position this item on the left lane on the canvas";
			} else if ($row['h_pos']=='0') {
			$position="[C] Center".$suf;
			$position_title="Position this item at the center of the canvas";
			} else if ($row['h_pos']=='1') {
			$position="[R] Right";
			$position_title="Position this item on the right lane on the canvas";
			}
		}
		

		
		if ($row['h_pos']!='0')
			{
			?>
		<option title="<?php echo $position_title;?>" value=<?php echo $row['h_pos']; ?>><?php echo $position; ?></option>    
		<?php	
			}
		?>
		<option value="0" title="Position this item at the center of the canvas">[C] Center <?php echo $suf;?></option>
		<?php } ?>
		</select>
<?php
	}
?>        