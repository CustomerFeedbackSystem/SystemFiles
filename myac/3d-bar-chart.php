<?php

include 'php-ofc-library/open-flash-chart.php';


srand((double)microtime()*1000000);
$data = array();

// add random height bars:
for( $i=0; $i<9; $i++ )
  $data[] = rand(2,9);
  
// make the last bar a different colour:
$bar = new bar_value(5);
$bar->set_colour( '#900000' );
$bar->set_tooltip( 'Hello<br>#val#' );
$data[] = $bar;

$title = new title( date("D M d Y") );

$bar = new bar_3d();
$bar->set_values( $data );
$bar->colour = '#D54C78';

$x_axis = new x_axis();
$x_axis->set_3d( 5 );
$x_axis->colour = '#909090';
$x_axis->set_labels( array(1,2,3,4,5,6,7,8,9,10) );

$chart = new open_flash_chart();
$chart->set_title( $title );
$chart->add_element( $bar );
$chart->set_x_axis( $x_axis );

echo $chart->toPrettyString();
?>