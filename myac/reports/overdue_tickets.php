<?php
mysql_select_db($database_connSystem, $connSystem);

if (!isset($_SESSION['parentview']))
	{
	$_SESSION['parentview']=1;
	}

if (isset($_GET['parentview']))
	{
	if ($_GET['parentview']>1)
		{
		$_SESSION['parentview']=mysql_real_escape_string(trim($_GET['parentview']));
		} else {
		$_SESSION['parentview']=1;
		}
	}
	
if (isset($_GET['parentview']))
	{
	if (isset($_GET['regionid']))
		{
		$_SESSION['regionid']=mysql_real_escape_string(trim($_GET['regionid']));
		$_SESSION['regionnm']=mysql_real_escape_string(trim($_GET['regionnm']));
		} 
	}

if (isset($_GET['dur']))
	{
	$_SESSION['dur']=mysql_real_escape_string(trim($_GET['dur']));
	}
	
if (isset($_GET['pageclick']))
	{
	$_SESSION['pageclick']=mysql_real_escape_string(trim($_GET['pageclick']));
	}	
	
if (isset($_GET['tktcatnm']))
	{
	$_SESSION['tktcatnm']=mysql_real_escape_string(trim($_GET['tktcatnm']));
	}
	
if (isset($_GET['tktcatid']))
	{
	$_SESSION['tktcatid']=mysql_real_escape_string(trim($_GET['tktcatid']));
	}	
	
?>	
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="../css_report_print.css" rel="stylesheet" type="text/css" media="print" />
<link href="../css_report.css" rel="stylesheet" type="text/css" />
<link href="../../thickbox/original_thickbox.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../../scripts/ajaxtabs.js"></script>
<script type="text/javascript" src="../../scripts/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="../../thickbox/thickbox_be.js"></script>
<title></title>
</head>
<body>
<?php 
if($_SESSION['parentview']==1) 
	{
///////////////////////////////////////////////////////VIEW ONE///////////////////////////////////////////////	
?>
<div style="padding:0px 5px 80px 0px">
<table border="0" cellpadding="0" cellspacing="0" width="100%">
    <tr>
        <td width="100%" class="bg_section">
        <?php echo $fet_heading['modulename']; ?> &raquo; <?php echo $fet_heading['submodule']; ?>
        </td>
    </tr>
        <tr>
        	<td style="padding:5px 0px 0px 0px">           
                <div class="title_header_blue">
	                Overdue Tickets by Duration
                </div>
                <div class="title_header_blue">
	                <small>All Regions</small>
                </div>
            	<div style="padding:10px 0px">
          		<table border="0" width="100%" cellpadding="2" cellspacing="0">            	
                    <tr>
                        <td valign="top"  style="padding:0px 0px 10px 0px">
                        </td>
                    </tr>
                    <tr>
                        <td class="table_header">                       
                        </td>
                       	<td class="table_header" style="font-size:11px; font-weight:bold">
                        Up to 3 Months
                       	</td>
                       	<td class="table_header" style="font-size:11px; font-weight:bold">
                        3 to 6 Months
                       	</td>
                       	<td class="table_header" style="font-size:11px; font-weight:bold">
                        6 to 9 Months
                       	</td>
                       	<td class="table_header" style="font-size:11px; font-weight:bold">
                        9 to 12 Months
                       	</td>
                       	<td class="table_header" style="font-size:11px; font-weight:bold">
                        Beyond 1 Year
                       	</td>
                    	<td class="table_header" style="font-size:11px; font-weight:bold">
                        Sub Total
                        </td>
                  	</tr>
                        <?php
						//loop through the regions
						$sql_region="SELECT idusrteamzone,userteamzonename FROM usrteamzone WHERE usrteam_idusrteam=2";
						$res_region=mysql_query($sql_region);
						$fet_region=mysql_fetch_array($res_region);
						
						$total_UPTO3_main=0;
						$total_UPTO6_main=0;
						$total_UPTO9_main=0;
						$total_UPTO12_main=0;
						$total_BEYOND12_main=0;
						$tkts_total_main=0;					
						
						do {
						$tkts_total=0;
						$total_UPTO3=0;
						$total_UPTO6=0;
						$total_UPTO9=0;
						$total_UPTO12=0;
						$total_BEYOND12=0;
						?>
                        <tr <?php 
						// technocurve arc 3 php mv block2/3 start
						echo " style=\"background-color:$mocolor\" onMouseOver=\"this.style.backgroundColor='$mocolor3'\" onMouseOut=\"this.style.backgroundColor='$mocolor'\"";
						// technocurve arc 3 php mv block2/3 end
						?>>
                        <td class="tbl_data">
                        <strong>
							<?php 
								echo "<a href=\"".$_SERVER['PHP_SELF']."?parentview=2&amp;regionnm=".$fet_region['userteamzonename']."&amp;regionid=".$fet_region['idusrteamzone']."\" style=\"color:#000033;font-weight:bold\"";
								echo " >";
								echo $fet_region['userteamzonename'];
								echo "</a>";
							?>
                        </strong>
                        </td>
                       	<td valign="top" class="tbl_data">
	                        <?php
							$sql_CEG_UPTO3="select count(idtktinPK) AS CEG_IN from tktin where DATEDIFF(NOW(),timedeadline)>0 AND DATEDIFF(NOW(),timedeadline) <=90
							AND tktstatus_idtktstatus<4
							AND tktin.usrteamzone_idusrteamzone='".$fet_region['idusrteamzone']."'";

							$res_CEG_UPTO3=mysql_query($sql_CEG_UPTO3);
							$fet_CEG_UPTO3=mysql_fetch_array($res_CEG_UPTO3);
												
							if($fet_CEG_UPTO3['CEG_IN']==0)							
								{ ?>
								<span style="color:#FF0000; font-size:10px; font-weight:500">--</span>
                                <?php
								} else {
								echo "<a href=\"".$_SERVER['PHP_SELF']."?parentview=3&dur=UPTO3&pageclick=1&amp;regionnm=".$fet_region['userteamzonename']."&amp;regionid=".$fet_region['idusrteamzone']."\" style=\"color:#ff0000;font-weight:bold\"";
								echo " >";
								echo number_format($fet_CEG_UPTO3['CEG_IN'],0);
								echo "</a>";
								}

							$total_UPTO3=$total_UPTO3+$fet_CEG_UPTO3['CEG_IN'];
							$tkts_total=$tkts_total+$total_UPTO3;
							$total_UPTO3_main=$total_UPTO3_main+$total_UPTO3;
							?>
                        </td>
                       	<td valign="top" class="tbl_data">
	                        <?php
							$sql_CEG_UPTO6="select count(idtktinPK) AS CEG_IN from tktin where DATEDIFF(NOW(),timedeadline)>90 AND DATEDIFF(NOW(),timedeadline) <=180
							AND tktstatus_idtktstatus<4
							AND tktin.usrteamzone_idusrteamzone='".$fet_region['idusrteamzone']."'";
					
							$res_CEG_UPTO6=mysql_query($sql_CEG_UPTO6);
							$fet_CEG_UPTO6=mysql_fetch_array($res_CEG_UPTO6);

							if($fet_CEG_UPTO6['CEG_IN']==0)							
								{ ?>
								<span style="color:#FF0000; font-size:10px; font-weight:500">--</span>
                                <?php
								} else {							
								echo "<a href=\"".$_SERVER['PHP_SELF']."?parentview=3&dur=UPTO6&pageclick=1&amp;regionnm=".$fet_region['userteamzonename']."&amp;regionid=".$fet_region['idusrteamzone']."\" style=\"color:#ff0000;font-weight:bold\"";
								echo " >";
								echo number_format($fet_CEG_UPTO6['CEG_IN'],0);
								echo "</a>";
								}
							
							$total_UPTO6=$total_UPTO6+$fet_CEG_UPTO6['CEG_IN'];
							$tkts_total=$tkts_total+$total_UPTO6;
							$total_UPTO6_main=$total_UPTO6_main+$total_UPTO6;
							?>
                        </td>
                       	<td valign="top" class="tbl_data">
	                        <?php
							$sql_CEG_UPTO9="select count(idtktinPK) AS CEG_IN from tktin where DATEDIFF(NOW(),timedeadline)>180 AND DATEDIFF(NOW(),timedeadline) <=270
							AND tktstatus_idtktstatus<4
							AND tktin.usrteamzone_idusrteamzone='".$fet_region['idusrteamzone']."'";
							
							$res_CEG_UPTO9=mysql_query($sql_CEG_UPTO9);
							$fet_CEG_UPTO9=mysql_fetch_array($res_CEG_UPTO9);

							if($fet_CEG_UPTO9['CEG_IN']==0)							
								{ ?>
								<span style="color:#FF0000; font-size:10px; font-weight:500">--</span>
                                <?php
								} else {
								echo "<a href=\"".$_SERVER['PHP_SELF']."?parentview=3&dur=UPTO9&pageclick=1&amp;regionnm=".$fet_region['userteamzonename']."&amp;regionid=".$fet_region['idusrteamzone']."\" style=\"color:#ff0000;font-weight:bold\"";
								echo " >";
								echo number_format($fet_CEG_UPTO9['CEG_IN'],0);
								echo "</a>";							
								}
							
							$total_UPTO9=$total_UPTO6+$fet_CEG_UPTO9['CEG_IN'];
							$tkts_total=$tkts_total+$total_UPTO9;
							$total_UPTO9_main=$total_UPTO9_main+$total_UPTO9;
							?>
                        </td>
                       	<td valign="top" class="tbl_data">
	                        <?php
							$sql_CEG_UPTO12="select count(idtktinPK) AS CEG_IN from tktin where DATEDIFF(NOW(),timedeadline)>270 AND DATEDIFF(NOW(),timedeadline) <=360
							AND tktstatus_idtktstatus<4
							AND tktin.usrteamzone_idusrteamzone='".$fet_region['idusrteamzone']."'";
							
							$res_CEG_UPTO12=mysql_query($sql_CEG_UPTO12);
							$fet_CEG_UPTO12=mysql_fetch_array($res_CEG_UPTO12);
						
							if($fet_CEG_UPTO12['CEG_IN']==0)							
								{ ?>
								<span style="color:#FF0000; font-size:10px; font-weight:500">--</span>
                                <?php
								} else {						
								echo "<a href=\"".$_SERVER['PHP_SELF']."?parentview=3&dur=UPTO12&pageclick=1&amp;regionnm=".$fet_region['userteamzonename']."&amp;regionid=".$fet_region['idusrteamzone']."\" style=\"color:#ff0000;font-weight:bold\"";
								echo " >";
								echo number_format($fet_CEG_UPTO12['CEG_IN'],0);
								echo "</a>";
								}

							$total_UPTO12=$total_UPTO12+$fet_CEG_UPTO12['CEG_IN'];
							$tkts_total=$tkts_total+$total_UPTO12;
							$total_UPTO12_main=$total_UPTO12_main+$total_UPTO12;
							?>
                        </td>
                       	<td valign="top" class="tbl_data">
	                        <?php
							$sql_CEG_BEYOND12="select count(idtktinPK) AS CEG_IN from tktin where DATEDIFF(NOW(),timedeadline)>360
							AND tktstatus_idtktstatus<4
							AND tktin.usrteamzone_idusrteamzone='".$fet_region['idusrteamzone']."'";
							
							$res_CEG_BEYOND12=mysql_query($sql_CEG_BEYOND12);
							$fet_CEG_BEYOND12=mysql_fetch_array($res_CEG_BEYOND12);

							if($fet_CEG_BEYOND12['CEG_IN']==0)							
								{ ?>
								<span style="color:#FF0000; font-size:10px; font-weight:500">--</span>
                                <?php
								} else {							
								echo "<a href=\"".$_SERVER['PHP_SELF']."?parentview=3&dur=BEYOND12&pageclick=1&amp;regionnm=".$fet_region['userteamzonename']."&amp;regionid=".$fet_region['idusrteamzone']."\" style=\"color:#ff0000;font-weight:bold\"";
								echo " >";
								echo number_format($fet_CEG_BEYOND12['CEG_IN'],0);
								echo "</a>";
								}

							$total_BEYOND12=$total_BEYOND12+$fet_CEG_BEYOND12['CEG_IN'];
							$tkts_total=$tkts_total+$total_BEYOND12;
							$total_BEYOND12_main=$total_BEYOND12_main+$total_BEYOND12;
							?>
                        </td>
                        <td class="tbl_sh">
                        <?php echo number_format($tkts_total,0);?>
                        </td>
                        </tr> 
                        <?php 
						// technocurve arc 3 php mv block3/3 start
						if ($mocolor == $mocolor1) {
							$mocolor = $mocolor2;
						} else {
							$mocolor = $mocolor1;
						}
						// technocurve arc 3 php mv block3/3 end
						?>
						<?php
						
						} while ($fet_region=mysql_fetch_array($res_region));
						?>
                   	<tr>
                   		<td class="tbl_sh" align="right">
                        Total
                        </td>
                  		<td class="tbl_sh" align="right">
                        <?php 
						echo number_format($total_UPTO3_main,0);?>
                        </td>
                  		<td class="tbl_sh" align="right">
                        <?php echo number_format($total_UPTO6_main,0);?>
                        </td>
                  		<td class="tbl_sh" align="right">
                        <?php echo number_format($total_UPTO9_main,0);?>
                        </td>
                  		<td class="tbl_sh" align="right">
                        <?php echo number_format($total_UPTO12_main,0);?>
                        </td>              
                  		<td class="tbl_sh" align="right">
                        <?php echo number_format($total_BEYOND12_main,0);?>
                        </td>                                  
                  		<td class="tbl_sh" align="right">
                        <?php 
						$tkts_total_main=$total_UPTO3_main+$total_UPTO6_main+$total_UPTO9_main+$total_UPTO12_main+$total_BEYOND12_main;
						echo number_format($tkts_total_main,0); 
						?>
                        </td>                                  
                   </tr>
			  </table>
            </div>
            </td>
        </tr>
</table>       
</div>
<?php } //End of parentview one 

if($_SESSION['parentview']==2) 
	{
	///////////////////////////////////////////////////////VIEW TWO///////////////////////////////////////////////	
?>
<div style="padding:0px 5px 80px 0px">
<table border="0" cellpadding="0" cellspacing="0" width="100%">
    <tr>
        <td width="100%" class="bg_section">
        <?php echo $fet_heading['modulename']; ?> &raquo; <?php echo $fet_heading['submodule']; ?>
        </td>
    </tr>
        <tr>
        	<td style="padding:5px 0px 0px 0px">           
                <div class="title_header_blue">
	                Overdue Tickets by Duration
                </div>
                <div class="title_header_blue">
	                <small><?php echo $_SESSION['regionnm']; ?></small>
                </div>
            	<div style="padding:10px 0px">
          		<table border="0" width="100%" cellpadding="2" cellspacing="0">            	
                    <tr>
                        <td valign="top"  style="padding:0px 0px 10px 0px">
                        </td>
                    </tr>
                    <tr>
                        <td class="table_header" style="font-size:11px; font-weight:bold">
							<a href="?parentview=1">&laquo; Back to main</a></td>                       
                        </td>
                       	<td class="table_header" style="font-size:11px; font-weight:bold">
                        Up to 3 Months
                       	</td>
                       	<td class="table_header" style="font-size:11px; font-weight:bold">
                        3 to 6 Months
                       	</td>
                       	<td class="table_header" style="font-size:11px; font-weight:bold">
                        6 to 9 Months
                       	</td>
                       	<td class="table_header" style="font-size:11px; font-weight:bold">
                        9 to 12 Months
                       	</td>
                       	<td class="table_header" style="font-size:11px; font-weight:bold">
                        Beyond 1 Year
                       	</td>
                    	<td class="table_header" style="font-size:11px; font-weight:bold">
                        Sub Total
                        </td>
                  	</tr>
                        <?php
						//loop through the categories
						$sql_category="SELECT idtktcategory,tktcategoryname FROM tktcategory";
						$res_category=mysql_query($sql_category);
						$fet_category=mysql_fetch_array($res_category);
						
						$total_UPTO3_main=0;
						$total_UPTO6_main=0;
						$total_UPTO9_main=0;
						$total_UPTO12_main=0;
						$total_BEYOND12_main=0;
						$tkts_total_main=0;					
						
						do {
						$tkts_total=0;
						$total_UPTO3=0;
						$total_UPTO6=0;
						$total_UPTO9=0;
						$total_UPTO12=0;
						$total_BEYOND12=0;
						?>
                        <tr <?php 
						// technocurve arc 3 php mv block2/3 start
						echo " style=\"background-color:$mocolor\" onMouseOver=\"this.style.backgroundColor='$mocolor3'\" onMouseOut=\"this.style.backgroundColor='$mocolor'\"";
						// technocurve arc 3 php mv block2/3 end
						?>>
                        <td class="tbl_data">
                        <strong><?php echo $fet_category['tktcategoryname'];?></strong>
                        </td>
                       	<td valign="top" class="tbl_data">
	                        <?php
							$sql_CEG_UPTO3="select count(idtktinPK) AS CEG_IN from tktin where DATEDIFF(NOW(),timedeadline)>0 AND DATEDIFF(NOW(),timedeadline) <=90
							AND tktstatus_idtktstatus<4
							AND tktin.usrteamzone_idusrteamzone=".$_SESSION['regionid']."
							AND tktin.tktcategory_idtktcategory=".$fet_category['idtktcategory']."";

							$res_CEG_UPTO3=mysql_query($sql_CEG_UPTO3);
							$fet_CEG_UPTO3=mysql_fetch_array($res_CEG_UPTO3);

							if($fet_CEG_UPTO3['CEG_IN']==0)							
								{ ?>
								<span style="color:#FF0000; font-size:10px; font-weight:500">--</span>
                                <?php
								} else {							
								echo "<a href=\"".$_SERVER['PHP_SELF']."?parentview=3&dur=UPTO3&pageclick=2&amp;tktcatid=".$fet_category['idtktcategory']."&amp;tktcatnm=".$fet_category['tktcategoryname']."&amp;regionid=".$_SESSION['regionid']."&amp;regionnm=".$_SESSION['regionnm']."\" style=\"color:#ff0000;font-weight:bold\"";
								echo " >";
								echo number_format($fet_CEG_UPTO3['CEG_IN'],0);
								echo "</a>";
								}

							$total_UPTO3=$total_UPTO3+$fet_CEG_UPTO3['CEG_IN'];
							$tkts_total=$tkts_total+$total_UPTO3;
							$total_UPTO3_main=$total_UPTO3_main+$total_UPTO3;
							?>
                        </td>
                       	<td valign="top" class="tbl_data">
	                        <?php
							$sql_CEG_UPTO6="select count(idtktinPK) AS CEG_IN from tktin where DATEDIFF(NOW(),timedeadline)>90 AND DATEDIFF(NOW(),timedeadline) <=180
							AND tktstatus_idtktstatus<4
							AND tktin.usrteamzone_idusrteamzone=".$_SESSION['regionid']."
							AND tktin.tktcategory_idtktcategory=".$fet_category['idtktcategory']."";
							
							$res_CEG_UPTO6=mysql_query($sql_CEG_UPTO6);
							$fet_CEG_UPTO6=mysql_fetch_array($res_CEG_UPTO6);

							if($fet_CEG_UPTO6['CEG_IN']==0)							
								{ ?>
								<span style="color:#FF0000; font-size:10px; font-weight:500">--</span>
                                <?php
								} else {							
								echo "<a href=\"".$_SERVER['PHP_SELF']."?parentview=3&dur=UPTO6&pageclick=2&amp;tktcatid=".$fet_category['idtktcategory']."&amp;tktcatnm=".$fet_category['tktcategoryname']."&amp;regionid=".$_SESSION['regionid']."&amp;regionnm=".$_SESSION['regionnm']."\" style=\"color:#ff0000;font-weight:bold\"";
								echo " >";
								echo number_format($fet_CEG_UPTO6['CEG_IN'],0);
								echo "</a>";
								}

							$total_UPTO6=$total_UPTO6+$fet_CEG_UPTO6['CEG_IN'];
							$tkts_total=$tkts_total+$total_UPTO6;
							$total_UPTO6_main=$total_UPTO6_main+$total_UPTO6;
							?>
                        </td>
                       	<td valign="top" class="tbl_data">
	                        <?php
							$sql_CEG_UPTO9="select count(idtktinPK) AS CEG_IN from tktin where DATEDIFF(NOW(),timedeadline)>180 AND DATEDIFF(NOW(),timedeadline) <=270
							AND tktstatus_idtktstatus<4
							AND tktin.usrteamzone_idusrteamzone=".$_SESSION['regionid']."
							AND tktin.tktcategory_idtktcategory=".$fet_category['idtktcategory']."";
							
							$res_CEG_UPTO9=mysql_query($sql_CEG_UPTO9);
							$fet_CEG_UPTO9=mysql_fetch_array($res_CEG_UPTO9);

							if($fet_CEG_UPTO9['CEG_IN']==0)							
								{ ?>
								<span style="color:#FF0000; font-size:10px; font-weight:500">--</span>
                                <?php
								} else {							
								echo "<a href=\"".$_SERVER['PHP_SELF']."?parentview=3&dur=UPTO9&pageclick=2&amp;tktcatid=".$fet_category['idtktcategory']."&amp;tktcatnm=".$fet_category['tktcategoryname']."&amp;regionid=".$_SESSION['regionid']."&amp;regionnm=".$_SESSION['regionnm']."\" style=\"color:#ff0000;font-weight:bold\"";
								echo " >";
								echo number_format($fet_CEG_UPTO9['CEG_IN'],0);
								echo "</a>";
								}
							
							$total_UPTO9=$total_UPTO6+$fet_CEG_UPTO9['CEG_IN'];
							$tkts_total=$tkts_total+$total_UPTO9;
							$total_UPTO9_main=$total_UPTO9_main+$total_UPTO9;
							?>
                        </td>
                       	<td valign="top" class="tbl_data">
	                        <?php
							$sql_CEG_UPTO12="select count(idtktinPK) AS CEG_IN from tktin where DATEDIFF(NOW(),timedeadline)>270 AND DATEDIFF(NOW(),timedeadline) <=360
							AND tktstatus_idtktstatus<4
							AND tktin.usrteamzone_idusrteamzone=".$_SESSION['regionid']."
							AND tktin.tktcategory_idtktcategory=".$fet_category['idtktcategory']."";
							
							$res_CEG_UPTO12=mysql_query($sql_CEG_UPTO12);
							$fet_CEG_UPTO12=mysql_fetch_array($res_CEG_UPTO12);

							if($fet_CEG_UPTO12['CEG_IN']==0)							
								{ ?>
								<span style="color:#FF0000; font-size:10px; font-weight:500">--</span>
                                <?php
								} else {							
								echo "<a href=\"".$_SERVER['PHP_SELF']."?parentview=3&dur=UPTO12&pageclick=2&amp;tktcatid=".$fet_category['idtktcategory']."&amp;tktcatnm=".$fet_category['tktcategoryname']."&amp;regionid=".$_SESSION['regionid']."&amp;regionnm=".$_SESSION['regionnm']."\" style=\"color:#ff0000;font-weight:bold\"";
								echo " >";
								echo number_format($fet_CEG_UPTO12['CEG_IN'],0);
								echo "</a>";
								}
	
							$total_UPTO12=$total_UPTO12+$fet_CEG_UPTO12['CEG_IN'];
							$tkts_total=$tkts_total+$total_UPTO12;
							$total_UPTO12_main=$total_UPTO12_main+$total_UPTO12;
							?>
                        </td>
                       	<td valign="top" class="tbl_data">
	                        <?php
							$sql_CEG_BEYOND12="select count(idtktinPK) AS CEG_IN from tktin where DATEDIFF(NOW(),timedeadline)>360
							AND tktstatus_idtktstatus<4
							AND tktin.usrteamzone_idusrteamzone=".$_SESSION['regionid']."
							AND tktin.tktcategory_idtktcategory=".$fet_category['idtktcategory']."";
							
							$res_CEG_BEYOND12=mysql_query($sql_CEG_BEYOND12);
							$fet_CEG_BEYOND12=mysql_fetch_array($res_CEG_BEYOND12);

							if($fet_CEG_BEYOND12['CEG_IN']==0)							
								{ ?>
								<span style="color:#FF0000; font-size:10px; font-weight:500">--</span>
                                <?php
								} else {							
								echo "<a href=\"".$_SERVER['PHP_SELF']."?parentview=3&dur=BEYOND12&pageclick=2&amp;tktcatid=".$fet_category['idtktcategory']."&amp;tktcatnm=".$fet_category['tktcategoryname']."&amp;regionid=".$_SESSION['regionid']."&amp;regionnm=".$_SESSION['regionnm']."\" style=\"color:#ff0000;font-weight:bold\"";
								echo " >";
								echo number_format($fet_CEG_BEYOND12['CEG_IN'],0);
								echo "</a>";
								}

							$total_BEYOND12=$total_BEYOND12+$fet_CEG_BEYOND12['CEG_IN'];
							$tkts_total=$tkts_total+$total_BEYOND12;
							$total_BEYOND12_main=$total_BEYOND12_main+$total_BEYOND12;
							?>
                        </td>
                        <td class="tbl_sh">
                        <?php echo number_format($tkts_total,0);?>
                        </td>
                        </tr> 
                        <?php 
						// technocurve arc 3 php mv block3/3 start
						if ($mocolor == $mocolor1) {
							$mocolor = $mocolor2;
						} else {
							$mocolor = $mocolor1;
						}
						// technocurve arc 3 php mv block3/3 end
						?>
						<?php
						
						} while ($fet_category=mysql_fetch_array($res_category));
						?>
                   	<tr>
                   		<td class="tbl_sh" align="right">
                        Total
                        </td>
                  		<td class="tbl_sh" align="right">
                        <?php 
						echo number_format($total_UPTO3_main,0);?>
                        </td>
                  		<td class="tbl_sh" align="right">
                        <?php echo number_format($total_UPTO6_main,0);?>
                        </td>
                  		<td class="tbl_sh" align="right">
                        <?php echo number_format($total_UPTO9_main,0);?>
                        </td>
                  		<td class="tbl_sh" align="right">
                        <?php echo number_format($total_UPTO12_main,0);?>
                        </td>              
                  		<td class="tbl_sh" align="right">
                        <?php echo number_format($total_BEYOND12_main,0);?>
                        </td>                                  
                  		<td class="tbl_sh" align="right">
                        <?php 
						$tkts_total_main=$total_UPTO3_main+$total_UPTO6_main+$total_UPTO9_main+$total_UPTO12_main+$total_BEYOND12_main;
						echo number_format($tkts_total_main,0); 
						?>
                        </td>                                  
                   </tr>
			  </table>
            </div>
            </td>
        </tr>
</table>       
</div>
<?php } //End of parentview two

if($_SESSION['parentview']==3) 
	{
	///////////////////////////////////////////////////////VIEW THREE///////////////////////////////////////////////	
?>
<div style="padding:0px 5px 80px 0px">
<table border="0" cellpadding="0" cellspacing="0" width="100%">
    <tr>
        <td width="100%" class="bg_section">
        <?php echo $fet_heading['modulename']; ?> &raquo; <?php echo $fet_heading['submodule']; ?>
        </td>
    </tr>
   	<tr>
    	<td style="padding:5px 0px 0px 0px">           
            <div class="title_header_blue">
                Overdue Tickets by Duration
            </div>
            <div class="title_header_blue">
                <small><?php echo $_SESSION['regionnm']; ?></small>
            </div>
           	<div style="padding:10px 0px">
          		<table border="0" width="100%" cellpadding="2" cellspacing="0">            	
                    <tr>
                        <td valign="top"  style="padding:0px 0px 10px 0px ;font-size:11px; font-weight:bold">
	                        <?php 
							if($_SESSION['pageclick']==1)
								{?>
								<a href="?parentview=1">&laquo; Back to main</a>
							<?php } else { ?> 
								<a href="?parentview=2">&laquo; Back to main</a>
							<?php } ?>
                        </td>
                   	</tr>
                    <tr>
                       	<td class="table_header" style="font-size:11px; font-weight:bold">
                        Ticket No
                       	</td>
                       	<td class="table_header" style="font-size:11px; font-weight:bold">
                        Account No
                       	</td>
                       	<td class="table_header" style="font-size:11px; font-weight:bold">
                        Reported On
                       	</td>
                       	<td class="table_header" style="font-size:11px; font-weight:bold">
                        Overdue On
                       	</td>
                       	<td class="table_header" style="font-size:11px; font-weight:bold">
                        Location
                       	</td>
                    	<td class="table_header" style="font-size:11px; font-weight:bold">
                        Category
                        </td>
                      	<td class="table_header" style="font-size:11px; font-weight:bold">
                        Status
                        </td>
                  	</tr>
                        <?php
						//Get the tickets details
						if($_SESSION['pageclick']==1)
							{
							$qry="AND tktin.usrteamzone_idusrteamzone=".$_SESSION['regionid']."";
							} else {
							$qry="AND tktin.usrteamzone_idusrteamzone=".$_SESSION['regionid']." AND tktin.tktcategory_idtktcategory=".$_SESSION['tktcatid']." ";
							}
						
						if($_SESSION['dur']=='UPTO3')
							{
							$sql_ticket="SELECT idtktinPK,refnumber,timereported,timedeadline,waterac,tktstatusname,tktcategoryname,locationname FROM tktin 
							INNER JOIN tktstatus ON tktin.tktstatus_idtktstatus=tktstatus.idtktstatus
							INNER JOIN tktcategory ON tktin.tktcategory_idtktcategory=tktcategory.idtktcategory
							LEFT JOIN loctowns ON tktin.loctowns_idloctowns=loctowns.idloctowns
							WHERE DATEDIFF(NOW(),timedeadline)>0 AND DATEDIFF(NOW(),timedeadline) <=90
							AND tktstatus_idtktstatus<4 ".$qry."";
					
							$res_ticket=mysql_query($sql_ticket);
							$fet_ticket=mysql_fetch_array($res_ticket);
							}

						if($_SESSION['dur']=='UPTO6')
							{
							$sql_ticket="SELECT idtktinPK,refnumber,timereported,timedeadline,waterac,tktstatusname,tktcategoryname,locationname FROM tktin 
							INNER JOIN tktstatus ON tktin.tktstatus_idtktstatus=tktstatus.idtktstatus
							INNER JOIN tktcategory ON tktin.tktcategory_idtktcategory=tktcategory.idtktcategory
							LEFT JOIN loctowns ON tktin.loctowns_idloctowns=loctowns.idloctowns
							WHERE DATEDIFF(NOW(),timedeadline)>90 AND DATEDIFF(NOW(),timedeadline) <=180
							AND tktstatus_idtktstatus<4 ".$qry."";
					
							$res_ticket=mysql_query($sql_ticket);
							$fet_ticket=mysql_fetch_array($res_ticket);
							}

						if($_SESSION['dur']=='UPTO9')
							{
							$sql_ticket="SELECT idtktinPK,refnumber,timereported,timedeadline,waterac,tktstatusname,tktcategoryname,locationname FROM tktin 
							INNER JOIN tktstatus ON tktin.tktstatus_idtktstatus=tktstatus.idtktstatus
							INNER JOIN tktcategory ON tktin.tktcategory_idtktcategory=tktcategory.idtktcategory
							LEFT JOIN loctowns ON tktin.loctowns_idloctowns=loctowns.idloctowns
							WHERE DATEDIFF(NOW(),timedeadline)>180 AND DATEDIFF(NOW(),timedeadline) <=270
							AND tktstatus_idtktstatus<4 ".$qry."";

							$res_ticket=mysql_query($sql_ticket);
							$fet_ticket=mysql_fetch_array($res_ticket);
							}

						if($_SESSION['dur']=='UPTO12')
							{
							$sql_ticket="SELECT idtktinPK,refnumber,timereported,timedeadline,waterac,tktstatusname,tktcategoryname,locationname FROM tktin 
							INNER JOIN tktstatus ON tktin.tktstatus_idtktstatus=tktstatus.idtktstatus
							INNER JOIN tktcategory ON tktin.tktcategory_idtktcategory=tktcategory.idtktcategory
							LEFT JOIN loctowns ON tktin.loctowns_idloctowns=loctowns.idloctowns
							WHERE DATEDIFF(NOW(),timedeadline)>270 AND DATEDIFF(NOW(),timedeadline) <=360
							AND tktstatus_idtktstatus<4 ".$qry."";
					
							$res_ticket=mysql_query($sql_ticket);
							$fet_ticket=mysql_fetch_array($res_ticket);
							}
							
						if($_SESSION['dur']=='BEYOND12')
							{
							$sql_ticket="SELECT idtktinPK,refnumber,timereported,timedeadline,waterac,tktstatusname,tktcategoryname,locationname FROM tktin 
							INNER JOIN tktstatus ON tktin.tktstatus_idtktstatus=tktstatus.idtktstatus
							INNER JOIN tktcategory ON tktin.tktcategory_idtktcategory=tktcategory.idtktcategory
							LEFT JOIN loctowns ON tktin.loctowns_idloctowns=loctowns.idloctowns
							WHERE DATEDIFF(NOW(),timedeadline)>360
							AND tktstatus_idtktstatus<4 ".$qry."";

							$res_ticket=mysql_query($sql_ticket);
							$fet_ticket=mysql_fetch_array($res_ticket);
							}
						
						do { 
						//Get the task details for the ticket to view the history
						$sql_task="SELECT idwftasks FROM wftasks 
						WHERE tktin_idtktin=".$fet_ticket['idtktinPK']." 
						AND ((wftasks.wftskstatustypes_idwftskstatustypes=0 AND wftasks.wftskstatusglobal_idwftskstatusglobal=1) OR (wftasks.wftskstatustypes_idwftskstatustypes=6 AND wftasks.wftskstatusglobal_idwftskstatusglobal=2))
						ORDER BY idwftasks DESC LIMIT 1 ";
						$res_task=mysql_query($sql_task);
						$fet_task=mysql_fetch_array($res_task);
						
						?>
                        <tr <?php 
						// technocurve arc 3 php mv block2/3 start
						echo " style=\"background-color:$mocolor\" onMouseOver=\"this.style.backgroundColor='$mocolor3'\" onMouseOut=\"this.style.backgroundColor='$mocolor'\"";
						// technocurve arc 3 php mv block2/3 end
						?>>
                        <td class="tbl_data">
                        <strong>
                            <a href="pop_taskview.php?tkt=<?php echo $fet_ticket['idtktinPK'];?>&task=<?php echo $fet_task['idwftasks'];?>&amp;tabview=1&amp;tabview=1&keepThis=true&TB_iframe=true&height=500&width=800&inlineId=hiddenModalContent&modal=true" class="thickbox"><?php echo $fet_ticket['refnumber'];?></a>
                        </strong>
                        </td>
                       	<td valign="top" class="tbl_data">
	                        <?php if($fet_ticket['waterac']=='') { ?><span style="color:#FF0000; font-style:italic; font-size:10px;">Not Specified</span><?php } else { echo $fet_ticket['waterac']; } ?>
                        </td>
                       	<td valign="top" class="tbl_data">
							<?php echo $fet_ticket['timereported']; ?>                        
                       	</td>
                       	<td valign="top" class="tbl_data">
							<?php echo $fet_ticket['timedeadline']; ?>                        
                        </td>
                       	<td valign="top" class="tbl_data">
							<?php if($fet_ticket['locationname']=='') { ?><span style="color:#FF0000; font-style:italic; font-size:10px;">Not Specified</span><?php } else { echo $fet_ticket['locationname']; } ?>
                        </td>
                       	<td valign="top" class="tbl_data">
							<?php echo $fet_ticket['tktcategoryname']; ?>                        
                        </td>
                       	<td valign="top" class="tbl_data">
							<?php echo $fet_ticket['tktstatusname']; ?>                        
                        </td>
                        </tr> 
                        <?php 
						// technocurve arc 3 php mv block3/3 start
						if ($mocolor == $mocolor1) {
							$mocolor = $mocolor2;
						} else {
							$mocolor = $mocolor1;
						}
						// technocurve arc 3 php mv block3/3 end
						?>
						<?php
						
						} while ($fet_ticket=mysql_fetch_array($res_ticket));
						?>
			  </table>
            </div>
            </td>
        </tr>
</table>       
</div>
<?php } //End of parentview two ?>
</body>
</html>
