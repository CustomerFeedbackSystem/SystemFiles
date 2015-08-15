<?php
require_once('../../../../assets_backend/be_includes/config.php');
require_once("../../../../assets_backend/be_includes/check_login_easy.php");

$timenowis = date("Y-m-d H:i:s",time()); //capture current time. You can adjust based on server settings
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>Flot Examples: Pie Charts</title>
<link href="../examples.css" rel="stylesheet" type="text/css">
	<style type="text/css">

	.chart-container {
		position: relative;
		height: 250px;
	}

	#placeholder {
		width: 400px;
	}

	#menu {
		position: absolute;
		top: 20px;
		left: 625px;
		bottom: 20px;
		right: 20px;
		width: 200px;
	}

	#menu button {
		display: inline-block;
		width: 200px;
		padding: 3px 0 2px 0;
		margin-bottom: 4px;
		background: #eee;
		border: 1px solid #999;
		border-radius: 2px;
		font-size: 16px;
		-o-box-shadow: 0 1px 2px rgba(0,0,0,0.15);
		-ms-box-shadow: 0 1px 2px rgba(0,0,0,0.15);
		-moz-box-shadow: 0 1px 2px rgba(0,0,0,0.15);
		-webkit-box-shadow: 0 1px 2px rgba(0,0,0,0.15);
		box-shadow: 0 1px 2px rgba(0,0,0,0.15);
		cursor: pointer;
	}

	#description {
		margin: 15px 10px 20px 10px;
	}

	#code {
		display: block;
		width: 870px;
		padding: 15px;
		margin: 10px auto;
		border: 1px dashed #999;
		background-color: #f8f8f8;
		font-size: 16px;
		line-height: 20px;
		color: #666;
	}



	</style>
	<!--[if lte IE 8]><script language="javascript" type="text/javascript" src="../../excanvas.min.js"></script><![endif]-->
	<script language="javascript" type="text/javascript" src="../../jquery.js"></script>
	<script language="javascript" type="text/javascript" src="../../jquery.flot.js"></script>
	<script language="javascript" type="text/javascript" src="../../jquery.flot.pie.js"></script>
	<script type="text/javascript">

	$(function() {

		// Example Data

		//var data = [
		//	{ label: "Series1",  data: 10},
		//	{ label: "Series2",  data: 30},
		//	{ label: "Series3",  data: 90},
		//	{ label: "Series4",  data: 70},
		//	{ label: "Series5",  data: 80},
		//	{ label: "Series6",  data: 110}
		//];

		//var data = [
		//	{ label: "Series1",  data: [[1,10]]},
		//	{ label: "Series2",  data: [[1,30]]},
		//	{ label: "Series3",  data: [[1,90]]},
		//	{ label: "Series4",  data: [[1,70]]},
		//	{ label: "Series5",  data: [[1,80]]},
		//	{ label: "Series6",  data: [[1,0]]}
		//];

		//var data = [
		//	{ label: "Series A",  data: 0.2063},
		//	{ label: "Series B",  data: 38888}
		//];

		// Randomly Generated Data

/*		var data = [],
			series = Math.floor(Math.random() * 6) + 3;

		for (var i = 0; i < series; i++) {
			data[i] = {
				label: "Series" + (i + 1),
				data: Math.floor(Math.random() * 100) + 1
			}
		}
*/

<?php

$sql="SELECT 
(SELECT count(*) FROM reports_wftasks INNER JOIN tktin ON reports_wftasks.tktin_idtktin=tktin.idtktinPK WHERE usrrole_idusrrole=".$_SESSION['MVGitHub_iduserrole']."  AND wftskstatustypes_idwftskstatustypes=0 AND tktin.timedeadline>='".$timenowis."' AND reports_wftasks.timeactiontaken='0000-00-00 00:00:00') AS new, 
(SELECT count(*) FROM reports_wftasks WHERE usrrole_idusrrole=".$_SESSION['MVGitHub_iduserrole']." AND wftskstatusglobal_idwftskstatusglobal=2 AND wftskstatustypes_idwftskstatustypes=6 AND timeactiontaken!='0000-00-00 00:00:00' AND timedeadline>=timeactiontaken ) AS inprogress,
(SELECT count(*) FROM reports_wftasks WHERE usrrole_idusrrole=".$_SESSION['MVGitHub_iduserrole']." AND wftskstatusglobal_idwftskstatusglobal=1 AND wftskstatustypes_idwftskstatustypes!=1 AND timeactiontaken='0000-00-00 00:00:00' AND timedeadline<='".$timenowis."' ) AS overdue,
(SELECT count(*) FROM reports_wftasks WHERE reports_wftasks.usrrole_idusrrole=".$_SESSION['MVGitHub_iduserrole']." AND wftskstatustypes_idwftskstatustypes<6 AND ((wftskstatustypes_idwftskstatustypes>1 AND sender_idusrrole!=".$_SESSION['MVGitHub_iduserrole'].") OR (wftskstatustypes_idwftskstatustypes=1))) AS closed
FROM reports_wftasks WHERE usrrole_idusrrole=".$_SESSION['MVGitHub_iduserrole']." GROUP BY usrrole_idusrrole ";

//echo $sql;
$res=mysql_query($sql);
$fet=mysql_fetch_array($res);

$totalpc=($fet['new'] + $fet['inprogress'] + $fet['overdue'] + $fet['closed']);

if ($totalpc > 0)
	{	
	$new = number_format(($fet['overdue']/$totalpc)*100,0);
	if ($new > 0)
		{
		$lbl_new="Overdue";
		} else {
		$lbl_new="";
		}
	} else {
	$lbl_new="";
	$new=0;
	}
/*	} else {
	$new = 'null';
	}*/
	
/*if (number_format(($fet_ip['inprogress']/$totalpc)*100,2) > 0 )
	{*/
if ($totalpc > 0)
	{	
	$inprogress = number_format(($fet['closed']/$totalpc)*100,0);
	if ($inprogress > 0)
		{
		$lbl_inprogress="Done";
		} else {
		$lbl_inprogress="";
		}
	} else {
	$lbl_inprogress="";
	$inprogress=0;
	}
/*	} else {
	$inprogress ='null';
	}*/
	
/*if (number_format(($fet_od['overdue']/$totalpc)*100,2) > 0 )
	{*/
if ($totalpc > 0)
	{
	$overdue = number_format(($fet['new']/$totalpc)*100,0);
	if ($overdue > 0)
		{
		$lbl_overdue="New";
		} else {
		$lbl_overdue="";
		}
	} else {
	$lbl_overdue="";
	$overdue=0;
	}
/*	} else {
	$overdue = 'null';
	}*/

/*if (number_format(($fet_cl['closed']/$totalpc)*100,2) > 0 )
	{*/
if ($totalpc > 0)
	{
	$closed = number_format(($fet['inprogress']/$totalpc)*100,0);
	if ($closed > 0)
		{
		$lbl_closed="In Progress";
		} else {
		$lbl_closed="";
		}
	} else {
	$lbl_closed="";
	$closed=0;
	}


echo "var data = [
			{ label: \"".$lbl_overdue."\",  data: \"".number_format($overdue,0)."\"},
			{ label: \"".$lbl_closed."\",  data: \"".number_format($closed,0)."\"},
			{ label: \"".$lbl_new."\",  data: \"".number_format($new,0)."\"},
			{ label: \"".$lbl_inprogress."\",  data: \"".number_format($inprogress,0)."\"}
		]";



?>



		var placeholder = $("#placeholder");

		$("#layout-4").click(function() {

			placeholder.unbind();

			$("#title").text("Label Radius");
			$("#description").text("Slightly more transparent label backgrounds and adjusted the radius values to place them within the pie.");

			$.plot(placeholder, data, {
				series: {
					pie: { 
						show: true,
						radius: 1,
						label: {
							show: true,
							radius: 3/4,
							formatter: labelFormatter,
							background: {
								opacity: 0.5
							}
						}
					}
				},
				legend: {
					show: false
				}
			});

			setCode([
				"$.plot('#placeholder', data, {",
				"    series: {",
				"        pie: {",
				"            show: true,",
				"            radius: 1,",
				"            label: {",
				"                show: true,",
				"                radius: 3/4,",
				"                formatter: labelFormatter,",
				"                background: {",
				"                    opacity: 0.5",
				"                }",
				"            }",
				"        }",
				"    },",
				"    legend: {",
				"        show: false",
				"    }",
				"});"
			]);
		});


		// Show the initial default chart

		$("#layout-4").click();

		// Add the Flot version string to the footer

		$("#footer").prepend("Flot " + $.plot.version + " &ndash; ");
	});

	// A custom label formatter used by several of the plots

	function labelFormatter(label, series) {
		return "<div style='font-size:8pt; text-align:center; padding:2px; color:white;'>" + label + "<br/>" + Math.round(series.percent) + "%</div>";
	}

	//

	function setCode(lines) {
		$("#code").text(lines.join("\n"));
	}

	</script>
</head>
<body>
<?php
//echo $sql;
?>
<div id="content" style="text-align:center">
<div class="chart-container" style=" height:200px; width:400px; ">
			<!--
            <div style="text-align:right; display: inline-block;display: inline-block; position:absolute; right:10px">
           <?php
		   //if this logged in users has people that report to him/her, then he/she can see their graphs at a glance
		   //if ($totalpc > 0) {
		   ?>
		    <select name="" style="font-size:10px">
            <option value="">--My Tasks --</option>
            </select>
            <input type="submit" value="Refresh!" style="font-size:10px">
            <?php
			//}
			?>
            </div>
            -->
            <?php
			if ($totalpc > 0) {
			?>
            
			<div id="placeholder" class="chart-placeholder" style="text-align:center" ></div>
				<div id="layout-4"></div>
            <?php } else { echo "<span style=\"font-size:12px;font-family:arial\">You haven't done any tasks!</span>";  } ?>
		</div>
        </div>

		</body>
</html>
