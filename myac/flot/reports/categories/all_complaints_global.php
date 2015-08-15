<?php
require_once('../../../../assets_backend/be_includes/config.php');
require_once("../../../../assets_backend/be_includes/check_login_easy.php");

$timenowis = date("Y-m-d H:i:s",time()); //capture current time. You can adjust based

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="../examples.css" rel="stylesheet" type="text/css">
<style type="text/css">
/* rotate the x axis labels. */
.flot-x-axis .flot-tick-label {
-webkit-transform: rotate(45deg);
-moz-transform: rotate(45deg);
-ms-transform: rotate(45deg);
-o-transform: rotate(45deg);
}
</style>
	<!--[if lte IE 8]><script language="javascript" type="text/javascript" src="../../excanvas.min.js"></script><![endif]-->
	<script language="javascript" type="text/javascript" src="../../jquery.js"></script>
	<script language="javascript" type="text/javascript" src="../../jquery.flot.js"></script>
	<script language="javascript" type="text/javascript" src="../../jquery.flot.categories.js"></script>
	<script type="text/javascript">

	$(function() {

<?php
// generate  data
$sql_data="SELECT COUNT(*) as tkts, tktcategoryname,category_pref FROM tktin
INNER JOIN tktcategory ON tktin.tktcategory_idtktcategory=tktcategory.idtktcategory WHERE 
usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']." AND timereported>='".$thirty_days_ago."' GROUP BY tktin.tktcategory_idtktcategory";
$res_data=mysql_query($sql_data);
$num_data=mysql_num_rows($res_data);
$fet_data=mysql_fetch_array($res_data);


$data="";
do {
	
	$data.= " [\"".$fet_data['category_pref']."\" , ".intval($fet_data['tkts'])."] ,";
	
	} while ($fet_data=mysql_fetch_array($res_data));

echo "var data = [ ";
echo substr($data,0,-1);
echo " ] ";
?>
//		var data = [ ["January", 10], ["February", 8], ["March", 4], ["April", 13], ["May", 17], ["June", 9] ];

		$.plot("#placeholder", [ data ], {
			series: {
				bars: {
					show: true,
					barWidth: 0.6,
					align: "center"
				}
			},
			xaxis: {
				mode: "categories",
				tickLength: 0,
				autoscaleMargin: .10 // allow space left and right
			}
			
			
		});

		// Add the Flot version string to the footer

		$("#footer").prepend("Flot " + $.plot.version + " &ndash; ");
	});

	</script>
</head>

<body>
<?php
//echo $sql_data;
?>
<div id="content">

		<div class="chart-container" style="width:480px; height:350px">
			<div id="placeholder" class="chart-placeholder"></div>
		</div>

	</div>
</body>
</html>
