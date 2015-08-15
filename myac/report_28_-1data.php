<?php
session_start();
include_once( 'php-ofc-library/open-flash-chart.php' );

require_once('../Connections/connSystem.php');
mysql_select_db($database_connSystem, $connSystem);

//check the category selected
if (isset($_SESSION['reportid'])) 
	{
	$_SESSION['filter_category']=" AND tktin.tktcategory_idtktcategory=".$_SESSION['reportid']." ";
	} else {
	$_SESSION['filter_category']="";
	}

// generate  data
$sql_data="SELECT SUM(tktin.tktcategory_idtktcategory) as tkts, timereported,tktcategoryname FROM tktin
INNER JOIN tktcategory ON tktin.tktcategory_idtktcategory=tktcategory.idtktcategory 
WHERE idtktin>0 ".$_SESSION['tktzone']." ".$_SESSION['tktstatus']." ".$_SESSION['tktchannel']." GROUP BY DATE(timereported)";
$res_data=mysql_query($sql_data);
$num_data=mysql_num_rows($res_data);
$fet_data=mysql_fetch_array($res_data);

/*echo $sql_data;
exit;*/

$max_y=($fet_data['tkts'] * 5);

$bar = new bar_outline( 50, '#3399cc', '#3399ff' );
$bar->key( 'Tickets', 10 );

$data = array();
$lbl=array();

if ($num_data > 0 ) //if there is data
	{
	
	do {
	
	$bar->data[] = intval($fet_data['tkt']);
    //$xlabels[]= date("D, M d, Y",strtotime($fet_data['timereported']));
	$xlabels[]= date("D, M d, Y",strtotime($fet_data['tktcategoryname']));
		} while ($fet_data=mysql_fetch_array($res_data));

$g = new graph();

$g->data_sets[] = $bar;
$g->bg_colour = '#ffffff';

$g->set_x_labels($xlabels);

$g->set_x_label_style( 10, '#9933CC', 2, 1 );
//
// and tick every second value:
//
$g->set_x_axis_steps( 1 );
//


$g->set_y_max( $max_y );
$g->y_label_steps( 4 );
$g->set_y_legend( 'No. of Tickets', 12, '#736AFF' );
echo $g->render();
	
} //close if thre is data
?>