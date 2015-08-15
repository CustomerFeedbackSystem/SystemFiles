<?php
session_start();
include_once( 'php-ofc-library/open-flash-chart.php' );

require_once('../Connections/connSystem.php');
mysql_select_db($database_connSystem, $connSystem);

// generate  data
$sql_data="SELECT SUM(tktin.tktcategory_idtktcategory) as tkts, tktcategoryname FROM tktin
INNER JOIN tktcategory ON tktin.tktcategory_idtktcategory=tktcategory.idtktcategory WHERE usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']." GROUP BY tktin.tktcategory_idtktcategory";
$res_data=mysql_query($sql_data);
$num_data=mysql_num_rows($res_data);
$fet_data=mysql_fetch_array($res_data);

//get the highest - max limit
$sql_max="SELECT SUM(tktin.tktcategory_idtktcategory) as tkts, tktcategoryname FROM tktin
INNER JOIN tktcategory ON tktin.tktcategory_idtktcategory=tktcategory.idtktcategory WHERE usrteamzone_idusrteamzone=".$_SESSION['MVGitHub_userteamzoneid']." GROUP BY tktin.tktcategory_idtktcategory ORDER BY tkts DESC LIMIT 1";
$res_max=mysql_query($sql_max);
$num_max=mysql_num_rows($res_max);
$fet_max=mysql_fetch_array($res_max);

//$max_y=($num_data * 5);
$max_y=($fet_max['tkts'] + 50);

//$bar = new bar_outline( 50, '#3399cc', '#3399ff' );
$bar = new bar_outline( $max_y, '#3399cc', '#3399ff' );
$bar->key( 'Tickets', 10 );

$data = array();
$lbl=array();

if ($num_data > 0 ) //if there is data
	{
	
	do {
	
	$bar->data[] = intval($fet_data['tkts']);
    $xlabels[]= mysql_escape_string($fet_data['tktcategoryname']);
	
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