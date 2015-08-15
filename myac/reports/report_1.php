<?php
require_once('../../assets_backend/be_includes/config.php');
require_once('../../assets_backend/be_includes/check_login_easy.php');

if (isset($_GET['display']))
	{
	$_SESSION['display_type']=trim($_GET['display']);
	} else {
	$_SESSION['display_type']="graph";
	}
?>
<!doctype html>  
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

	<title>flot.tooltip plugin example page</title>
	<script src="../graphs/jquery-1.8.3.min.js"></script>
    <!--[if lte IE 8]><script src="../js/excanvas.min.js"></script><![endif]-->
	<script src="../js/jquery.flot.js"></script>
    <script src="../graphs/plugins/jquery.flot.pie.js"></script>
	<script src="../js/jquery.flot.tooltip.min.js"></script>
	<link href="../css_report_print.css" rel="stylesheet" type="text/css" media="print" />
    <link href="../css_report.css" rel="stylesheet" type="text/css" />
    <script language="javascript" type="text/javascript" src="../../scripts/datetimepicker.js"></script>
	<style type="text/css">
		body {font-family: sans-serif; font-size: 16px; margin: 50px; max-width: 800px;}
		h4, ul {margin: 0;}
		#flotTip 
		{
			padding: 3px 5px;
			background-color: #000;
			z-index: 100;
			color: #fff;
			box-shadow: 0 0 10px #555;
			opacity: .7;
			filter: alpha(opacity=70);
			border: 2px solid #fff;
			-webkit-border-radius: 4px;
			-moz-border-radius: 4px;
			border-radius: 4px;
		}
	</style>
</head>

<body>
    <h1>flot.tooltip plugin example page</h1>
	<div>
    	<div class="hidden">
    	<table border="0" width="100%">
        	<tr>
            	<td class="text_small" width="80%">
                 <span class="text_small" >
            <?php
            if ($_SESSION['display_type']=="tabular") { ?>
            <a href="<?php echo $_SERVER['PHP_SELF'];?>?display=graph&timestop=<?php echo $_SESSION['timestop'];?>&timestart=<?php echo $_SESSION['timestart'];?>&pagetitle=<?php echo $_SESSION['report_title'];?>&reportid=<?php echo $_SESSION['reportid'];?>">Swith to Graph</a>
            <?php } ?>
            <?php
            if ($_SESSION['display_type']=="graph") { ?>
            <a href="<?php echo $_SERVER['PHP_SELF'];?>?display=tabular&timestop=<?php echo $_SESSION['timestop'];?>&timestart=<?php echo $_SESSION['timestart'];?>&pagetitle=<?php echo $_SESSION['report_title'];?>&reportid=<?php echo $_SESSION['reportid'];?>">Switch to Table</a>
            <?php } ?>
            </span>
                </td>
                <td align="right" class="text_small">
                <a href="#" onClick="window.print()">Print</a>
                </td>
                <td align="right" class="text_small">
                <form method="post" action="" name="excel" target="_blank">
                <a href="#" onClick="document.forms['excel'].submit()">Export to Excel</a>
                </form>
                </td>
            </tr>
        </table>
        </div>
        <div>
        <?php
		if ($_SESSION['display_type']=="graph")
			{
			require_once('report_1_graph.php');
			}
		if ($_SESSION['display_type']=="tabular")
			{
			require_once('report_1_table.php');
			}
		?>
        </div>
    </div>

</body>
</html>
