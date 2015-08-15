<?php
require_once('../../assets_backend/be_includes/config.php');

if (isset($_GET['display']))
	{
	$_SESSION['display_type']=trim($_GET['display']);
	} else {
	$_SESSION['display_type']="graph";
	}

if ((isset($_GET['timestart'])) && ($_GET['timestart']!=''))
	{
	$_SESSION['timestart']=preg_replace('/[^a-z\-_0-9\.:\/\s]/i','',mysql_escape_string(trim($_GET['timestart'])));
	} 
	
	if (!isset($_SESSION['timestart']))
	{
	$error_1 = "<div class=\"msg_warning\">Start Date is Missing</div>";
	}
	
if ((isset($_GET['timestop'])) && ($_GET['timestop']!=''))
	{
	$_SESSION['timestop']=preg_replace('/[^a-z\-_0-9\.:\/\s]/i','',mysql_escape_string(trim($_GET['timestop'])));
	} 
	
	if (!isset($_SESSION['timestop'])) {
	$error_2="<div class=\"msg_warning\">Ending Date is Missing</div>";
	}

if (isset($_GET['reportname']))
	{
	$_SESSION['reportname']=trim($_GET['reportname']);
	}

if (isset($_GET['exportid']))
	{
	$_SESSION['exportid']=mysql_real_escape_string(trim($_GET['exportid']));
	} else {
	$_SESSION['exportid']=0;
	}

if($_SESSION['exportid']==1)
	{
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=global_tickets_received_by_category.xls");
	header("Pragma: no-cache");
	header("Expires: 0");
	print "$header\n$data";	
	}
	
?>	
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php if (!isset($_GET['exportid'])) { ?>
<link href="../css_report_print.css" rel="stylesheet" type="text/css" media="print" />
<link href="../css_report.css" rel="stylesheet" type="text/css" />
<?php } ?>
<title><?php echo $_SESSION['reportname'];?></title>
</head>
<body>
<div style="padding:10px">
    <div class="text_body_vlarge"><?php echo $_SESSION['MVGitHub_acteam'];?></div>
    <div class="text_body_large"><?php echo $_SESSION['reportname'];?></div>
    </div>
    <?php
	//validate here before displaying teh data
	?>
    <div>
    <div class="rpt_period">
    	
       	<table cellpadding="0" cellspacing="0" width="100%">
        	<tr>
            	<td width="50%">
                	Report Period : <span ><?php echo date("D, M d, Y",strtotime($_SESSION['timestart'])); ?> - <?php echo date("D, M d, Y",strtotime($_SESSION['timestop'])); ?></span>
	        		<!--Report Period : <span ><?php echo date("F jS, Y ",strtotime($_SESSION['timestart']))." to ".date("F jS, Y ",strtotime($_SESSION['timestop'])); ?> </span>-->
                </td>
                <?php if (!isset($_GET['exportid'])) { ?>
                <td width="50%" align="right">
                	<span><a class="text_body" href="<?php $_SERVER['PHP_SELF'];?>?exportid=1">Export to Excel</a></span>
                </td>
                <?php } ?>
        	</tr>
       	</table>     

    
    </div>    
    <div style="padding:10px 5px">
    <?php
    //run the regions
    $sql="SELECT idusrteamzone,userteamzonename,region_pref
	FROM usrteamzone WHERE usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']." ORDER BY idusrteamzone ASC";
    $res=mysql_query($sql);
    $num=mysql_num_rows($res);
    $fet=mysql_fetch_array($res);
    //echo $sql;
    if ($num > 0)
        {
    
    ?>
    <table border="0" cellpadding="2" cellspacing="0">
        <tr>
          <td class="tbl_h">
            Region / Type
            </td>
            <?php
			 do {
			?>
          	<td class="tbl_h">
            <?php
		//	echo $fet['region_pref'];
		echo str_replace('Region','',$fet['userteamzonename']);
			?>
            </td>
			<?php
            } while ($fet=mysql_fetch_array($res));
            ?>
          <td class="tbl_h">
            Total
           </td>
        </tr>
     <?php
	 $sql_cat="SELECT idtktcategory,tktcategory_idtktcategory,tktcategoryname FROM tktcategory WHERE tat > 0";
	 $res_cat=mysql_query($sql_cat);
	 $fet_cat=mysql_fetch_array($res_cat);
	 
	 do {
	 ?>
        <tr>
            <td class="tbl_data">
           <?php echo $fet_cat['tktcategoryname'];?>
            </td>
            <?php
			//run the regions
		$sql_data="SELECT idusrteamzone,userteamzonename,
		(SELECT count(*) FROM tktin 
		WHERE usrteamzone_idusrteamzone=idusrteamzone 
		AND tktcategory_idtktcategory=".$fet_cat['idtktcategory']." 
		AND ( (date(tktin.timereported) = '".$_SESSION['timestart']."') OR (date(tktin.timereported) > '".$_SESSION['timestart']."') )
		AND ( (date(tktin.timereported) = '".$_SESSION['timestop']."') OR (date(tktin.timereported) < '".$_SESSION['timestop']."') ) ) as tkts
		FROM usrteamzone WHERE usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']." ORDER BY idusrteamzone ASC";
		$res_data=mysql_query($sql_data);
		$num_data=mysql_num_rows($res_data);
		$fet_data=mysql_fetch_array($res_data);
		//echo $sql;
		if ($num > 0)
			{
			$total=0;
			do {
		?>
        <td class="tbl_data">
            <?php if ($fet_data['tkts']>0) { echo $fet_data['tkts']; } else { echo "-"; }?>
		</td>
        <?php
			$total = ($total + $fet_data['tkts']);
			} while ($fet_data=mysql_fetch_array($res_data));
		}
		?>
            <td class="tbl_data2">
            <?php echo $total;?>
            </td>
        </tr>
	<?php
	   	} while ($fet_cat=mysql_fetch_array($res_cat));
	   ?>
    <?php
	//final row for totals for each regionn
    $sql_totals="SELECT idusrteamzone,
	(SELECT count(*) FROM tktin 
	WHERE usrteamzone_idusrteamzone=idusrteamzone 
	AND ( (date(tktin.timereported) = '".$_SESSION['timestart']."') OR (date(tktin.timereported) > '".$_SESSION['timestart']."') )
	AND ( (date(tktin.timereported) = '".$_SESSION['timestop']."') OR (date(tktin.timereported) < '".$_SESSION['timestop']."') ) ) as totalh
	FROM usrteamzone WHERE usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']." 
	ORDER BY idusrteamzone ASC";
    $res_totals=mysql_query($sql_totals);
    $num_totals=mysql_num_rows($res_totals);
    $fet_totals=mysql_fetch_array($res_totals);
//    echo   $sql_totals;
    if ($num > 0)
        {
		echo "<tr><td class=\"tbl_data2\"></td>";
			$total_h=0;
			do {
			echo "<td class=\"tbl_data2\">".$fet_totals['totalh']."</td>";
			$total_h=($total_h+$fet_totals['totalh']);
			} while ($fet_totals=mysql_fetch_array($res_totals));
		echo "<td class=\"tbl_data2\">".$total_h."</td></tr>";
		}
	?>
    </table>    
    <?php    
        } else {
        
        echo "No Data to Generate Report";
        
        }
    ?>
    </div>
</div>
</body>
</html>
