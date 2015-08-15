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

	<!--[if lte IE 8]><script language="javascript" type="text/javascript" src="../../excanvas.min.js"></script><![endif]-->
	<script language="javascript" type="text/javascript" src="../../jquery.js"></script>
	<script language="javascript" type="text/javascript" src="../../jquery.flot.js"></script>
	<script language="javascript" type="text/javascript" src="../../jquery.flot.categories.js"></script>
	<script language="javascript" type="text/javascript" src="../../jquery.flot.time.js"></script>
	<script type="text/javascript">

	$(function() {

var startdate = new Date(<?php echo strtotime($_SESSION['timestart'])*1000; ?>);
var stopdate = new Date(<?php echo strtotime($_SESSION['timestop'])*1000; ?>);

<?php
// generate  data
$sql_data="SELECT COUNT(*) as tkts, tktcategoryname,timereported FROM tktin
INNER JOIN tktcategory ON tktin.tktcategory_idtktcategory=tktcategory.idtktcategory 
WHERE usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']." 
AND (timereported BETWEEN '".$_SESSION['timestart']."' AND '".$_SESSION['timestop']."')
".$_SESSION['tktregion']." 
".$_SESSION['tktstatus']."
".$_SESSION['filter_category']." 
GROUP BY DATE(timereported)";

$res_data=mysql_query($sql_data);
$num_data=mysql_num_rows($res_data);
$fet_data=mysql_fetch_array($res_data);

$data="";
do {
	$tktdate=date(strtotime($fet_data['timereported'])*1000);	
	$data.= " [\"".$tktdate."\" , ".intval($fet_data['tkts'])."] ,";
	} while ($fet_data=mysql_fetch_array($res_data));

	echo "var data = [ ";
	echo substr($data,0,-1);
	echo " ] ";
?>
//		var data = [[-373597200000, 115.71], [-370918800000, 167.45], [-363056400000, 115.86] ];

		$.plot("#placeholder", [ data ], {
			series: {
				bars: {
					show: true,
					barWidth: 0.6,
					align: "center"
				}
			},
			xaxis: {
				mode: "time",
				tickLength: 0,
				minTickSize: [1, "day"],
				autoscaleMargin: .10 // allow space left and right
			}
		
		});

		// Add the Flot version string to the footer
		$("#footer").prepend("Flot " + $.plot.version + " &ndash; ");
		});

	</script>
</head>
<body>

<div id="content">
    <div class="chart-container" style="width:950px; height:450px">
        <div id="placeholder" class="chart-placeholder"></div>        
    </div>
</div>
</body>
</html>
