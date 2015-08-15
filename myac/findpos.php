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
	$rawdata_array=explode('_',$rawdata);
	
	$listorder=$rawdata_array['0'];// list order
	$hpos=$rawdata_array['1'];//h position
	$symbolid=$rawdata_array['2']; //check the symbol id
	$idwflow=$rawdata_array['3']; //wkflow id
	}
//echo $rawdata;
if ( (isset($listorder)) && (strlen($listorder) > 0) )
	{ // [1]

	//definitions
		$lbl_left="<option value='-1'>[L] Left</option>";
		$lbl_center="<option value='0'>[C] Center</option>";
		$lbl_right="<option value='1'>[R] Right</option>";
		$lbl_center_break="<option value='0'>[C] Center [Break]</option>";

	echo "<select name=\"pos_side\">";
		
		//if exclusive gateway AND position is middle (0) then
		if (($symbolid=="5") && ($hpos=="0") )//if it is centered, then give only right or left options
			{
			echo $lbl_left;
			echo $lbl_right;		
			}
		
		if (($symbolid=="5") && ($hpos!="0") ) //if the exclusive split is not centered, then the current option can only be to left or right
			{
				if ($hpos=="-1")
					{
					echo $lbl_left;
					}
			echo $lbl_center_break;
				if ($hpos=="1")
					{
					echo $lbl_right;
					}
			}	
			
		if ($symbolid==2) //if a task, then
			{
				if ($hpos=="-1")
					{
					echo $lbl_left;
					echo $lbl_center_break;
					}
					
				if ($hpos=="0")
					{
					echo $lbl_center_break;
					}
					
				if ($hpos=="1")
					{
					echo $lbl_right;
					echo $lbl_center_break;
					}				
			}
			
		if ($symbolid==1) //if a start event, then
			{
				echo $lbl_center;
			}
		
	echo "</select>";
	} // [1]
	

?>        