<?php
require_once('../assets_backend/be_includes/config.php');
//* NOTE :  THis is GUI for WORKFLOW VERSION 2 To include complex routing procedures such as
//various kinds of Gateways, Form integration etc.//
//Logic is available in the Database - Just the User Interface simple and validated for Administration Purposes
require_once('../assets_backend/be_includes/check_login_easy.php');
//mysql_select_db($database_connSystem, $connSystem);
$wfselected = 1; //repace $wfselected with  this
?>
<link href="../assets_backend/css/style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../scripts/jquery-1.4.2.min.js"></script>
<link href="../thickbox/original_thickbox.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../thickbox/thickbox.js"></script>



<div>
<?php
$sql_symbol="SELECT idwfsymbol,wfsymbolname,wfsymbol_imgpath,wfsymboldesc FROM wfsymbol";
$res_symbol=mysql_query($sql_symbol);
$fet_symbol=mysql_fetch_array($res_symbol);

do {
?>
	<span style="padding:5px; margin:5px">
    <a href="#" onclick="tb_open_new('pop_newworkflow_properties_2.php?wftskid=new item&amp;title=New_Item&amp;symbol=<?php echo $fet_symbol['idwfsymbol'];?>&amp;tabview=1&amp;keepThis=true&amp;TB_iframe=true&amp;height=410&amp;width=780&amp;modal=true')" ><img src="../assets_backend/bpm_icon/<?php echo $fet_symbol['wfsymbol_imgpath'];?>" border="0" align="absmiddle" />
    </a>
    </span>
<?php
} while ($fet_symbol=mysql_fetch_array($res_symbol));
?>
</div>
<div style="text-align:center; padding:40px 0px 50px 0px">

<?php
//display the process menu here 
$sql_flow="SELECT idwftskflow,idwfsymbol,wfsymbolname,wfsymbol_imgpath,wfsymboldesc,wftskflowname,wftskflowdesc,idwfsymbol,wftsktat,listorder FROM wftskflow 
INNER JOIN wfsymbol ON wftskflow.wfsymbol_idwfsymbol=wfsymbol.idwfsymbol WHERE wfproc_idwfproc=".$wfselected." GROUP BY listorder ORDER BY listorder ASC";
$res_flow=mysql_query($sql_flow);
$num_flow=mysql_num_rows($res_flow);
$fet_flow=mysql_fetch_array($res_flow);
//echo $sql_flow;
	do {
?>			

	<div style="text-align:center">
    <?php //check how many are on this level as long as it is an activity ie:symbol id 2
	if ($fet_flow['idwfsymbol']==2 )
		{
$sql_hloop="SELECT idwftskflow,idwfsymbol,wfsymbolname,wfsymbol_imgpath,wfsymboldesc,wftskflowname,wftskflowdesc,idwfsymbol,wftsktat,listorder FROM wftskflow 
INNER JOIN wfsymbol ON wftskflow.wfsymbol_idwfsymbol=wfsymbol.idwfsymbol WHERE wfproc_idwfproc=".$wfselected." AND listorder=".$fet_flow['listorder']."  ORDER BY listorder ASC";
$res_hloop=mysql_query($sql_hloop);
$num_hloop=mysql_num_rows($res_hloop);
$fet_hloop=mysql_fetch_array($res_hloop);
		
		 if ($num_hloop > 0)
		 	{
			do {
	?>
    <span style="text-align:center; width:100px; height:50px; margin:5px; padding:5px">
    <img src="../assets_backend/bpm_icon/<?php echo $fet_flow['wfsymbol_imgpath'];?>" border="0" align="absmiddle" />
    </span>
    <?php 
			} while ($fet_hloop=mysql_fetch_array($res_hloop));
		} //if numhloop  > 0
	} //if id=2 ?>
    </div>
    
<?php } while ($fet_flow=mysql_fetch_array($res_flow));?>

    <div style="text-align:center">
        <span style="text-align:center; width:100px; height:50px; margin:5px; padding:5px">asdfs</span>
        <span style="text-align:center; width:100px; height:50px; margin:5px; padding:5px">asdfs</span>
        <span style="text-align:center; width:100px; height:50px; margin:5px; padding:5px">asdfs</span>
    </div>
	<div style="text-align:center">
        <span style="text-align:center;margin:5px; padding:5px">asdfs</span>
        <span style="text-align:center; width:200px; height:50px; margin:5px; padding:5px">asdfs</span>
        <span style="text-align:center; width:200px; height:50px; margin:5px; padding:5px">asdfs</span>
    </div>
</div>