<?php
session_start();
include_once( 'php-ofc-library/open-flash-chart.php' );

require_once('../Connections/connSystem.php');
mysql_select_db($database_connSystem, $connSystem);


//My Tasks Pie Chart showing the Closed,Open, New and Overdue Personal Tasks
$sql="SELECT 
(SELECT count(*) FROM reports_wftasks INNER JOIN usrrole ON reports_wftasks.usrrole_idusrrole=usrrole.idusrrole WHERE usrrole.usrteamzone_idusrteamzone=".$_SESSION['MVGitHub_iduserrole']."  AND wftskstatusglobal_idwftskstatusglobal=1) AS new, 
(SELECT count(*) FROM reports_wftasks INNER JOIN usrrole ON reports_wftasks.usrrole_idusrrole=usrrole.idusrrole WHERE usrrole.usrteamzone_idusrteamzone=".$_SESSION['MVGitHub_iduserrole']." AND wftskstatusglobal_idwftskstatusglobal=2 AND wftskstatustypes_idwftskstatustypes=6 AND timeactiontaken!='0000-00-00 00:00:00' AND timedeadline>=timeactiontaken ) AS inprogress,
(SELECT count(*) FROM reports_wftasks INNER JOIN usrrole ON reports_wftasks.usrrole_idusrrole=usrrole.idusrrole WHERE usrrole.usrteamzone_idusrteamzone=".$_SESSION['MVGitHub_iduserrole']." AND wftskstatusglobal_idwftskstatusglobal=1 AND wftskstatustypes_idwftskstatustypes!=1 AND timeactiontaken='0000-00-00 00:00:00' AND timedeadline<='".$timenowis."' ) AS overdue,
(SELECT count(*) FROM reports_wftasks INNER JOIN usrrole ON reports_wftasks.usrrole_idusrrole=usrrole.idusrrole WHERE usrrole.usrteamzone_idusrteamzone=".$_SESSION['MVGitHub_iduserrole']." AND wftskstatustypes_idwftskstatustypes<6 AND ((wftskstatustypes_idwftskstatustypes>1 AND sender_idusrrole!=".$_SESSION['MVGitHub_iduserrole'].") OR (wftskstatustypes_idwftskstatustypes=1))) AS closed
FROM reports_wftasks INNER JOIN usrrole ON reports_wftasks.usrrole_idusrrole=usrrole.idusrrole WHERE usrrole.usrteamzone_idusrteamzone=".$_SESSION['MVGitHub_iduserrole']." GROUP BY usrrole_idusrrole ";
$res=mysql_query($sql);
$fet=mysql_fetch_array($res);

/*echo $sql;
exit;*/

$totalpc=($fet['new'] + $fet['inprogress'] + $fet['overdue'] + $fet['closed']);


/*if ($totalpc >0)
	{
	*/
/*if (number_format(($fet_new['new']/$totalpc)*100,2) > 0)
	{*/
	$new = number_format(($fet['new']/$totalpc)*100,0);
	if ($new > 0)
		{
		$lbl_new="New";
		} else {
		$lbl_new="";
		}
/*	} else {
	$new = 'null';
	}*/
	
/*if (number_format(($fet_ip['inprogress']/$totalpc)*100,2) > 0 )
	{*/
	$inprogress = number_format(($fet['inprogress']/$totalpc)*100,0);
	if ($inprogress > 0)
		{
		$lbl_inprogress="In Progress";
		} else {
		$lbl_inprogress="";
		}
/*	} else {
	$inprogress ='null';
	}*/
	
/*if (number_format(($fet_od['overdue']/$totalpc)*100,2) > 0 )
	{*/
	$overdue = number_format(($fet['overdue']/$totalpc)*100,0);
	if ($overdue > 0)
		{
		$lbl_overdue="Overdue";
		} else {
		$lbl_overdue="";
		}
/*	} else {
	$overdue = 'null';
	}*/

/*if (number_format(($fet_cl['closed']/$totalpc)*100,2) > 0 )
	{*/
	$closed = number_format(($fet['closed']/$totalpc)*100,0);
	if ($closed > 0)
		{
		$lbl_closed="Done";
		} else {
		$lbl_closed="";
		}
/*	} else {
	$closed = 'null';
	}*/
	
	/*}*/

/*echo "1 > ".$new."<br>2 > ".$inprogress."<br>3  >".$overdue."<br>4 >".$closed;
exit;
*/
$data = array(''.number_format($closed,0).'',''.number_format($new,0).'',''.number_format($inprogress,0).'',''.number_format($overdue,0).'');
$links = array('myoverdue.php','mynew.php','myinprogress.php','myclosed.php');

/*$data = array(''.number_format($closed,2).'','10','10','10');
$links = array('myclosed.php','null','null','null');*/

include_once('php-ofc-library/open-flash-chart.php');
$g = new graph();

//
// PIE chart, 60% alpha
//
$g->pie(95,'#ffffff','{font-size: 12px; color: #404040;');
//
// pass in two arrays, one of data, the other data labels
//
$g->pie_values( $data, array(''.$lbl_closed.'',''.$lbl_new.'',''.$lbl_inprogress.'',''.$lbl_overdue.''), $links );
//
// Colours for each slice, in this case some of the colours
// will be re-used (3 colurs for 5 slices means the last two
// slices will have colours colour[0] and colour[1]):
//
$g->pie_slice_colours( array('#009900','#356aa0','#C79810','#ff0000') );
$g->bg_colour = '#ffffff';
$g->set_tool_tip( '#x_label#:#val#%' );

//$g->title( 'My Tasks', '{font-size:18px; color: #d01f3c}' );

echo $g->render();

// generate  data
$sql_data="SELECT SUM(tktin.tktcategory_idtktcategory) as tkts, tktcategoryname FROM tktin
INNER JOIN tktcategory ON tktin.tktcategory_idtktcategory=tktcategory.idtktcategory 
WHERE idtktin>0 ".$_SESSION['tktzone']." ".$_SESSION['tktstatus']." ".$_SESSION['tktchannel']." GROUP BY tktin.tktcategory_idtktcategory";
$res_data=mysql_query($sql_data);
$num_data=mysql_num_rows($res_data);
$fet_data=mysql_fetch_array($res_data);

?>