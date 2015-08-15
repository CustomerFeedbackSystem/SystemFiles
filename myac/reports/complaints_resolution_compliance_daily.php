<?php
require_once('../../assets_backend/be_includes/config.php');
//echo "<span style=\"color:#ff0000\">maintenance work in progress on this report...</span>";
if (isset($_GET['display']))
	{
	$_SESSION['display_type']=trim($_GET['display']);
	} else {
	$_SESSION['display_type']="graph";
	}

if ((isset($_GET['timestart'])) && ($_GET['timestart']!=''))
	{
	$_SESSION['yaleo']=preg_replace('/[^a-z\-_0-9\.:\/\s]/i','',mysql_escape_string(trim($_GET['timestart'])));
	} 
	
if (!isset($_SESSION['yaleo']))
	{
	$error_1 = "<div class=\"msg_warning\">Start Date is Missing</div>";
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
	header("Content-Disposition: attachment; filename=complaints_resolution_compliance_daily.xls");
	header("Pragma: no-cache");
	header("Expires: 0");
	print "$header\n$data";	
	}


?>	
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $_SESSION['reportname'];?></title>
<?php if (!isset($_GET['exportid'])) { ?>
<link href="../css_report_print.css" rel="stylesheet" type="text/css" media="print" />
<link href="../css_report.css" rel="stylesheet" type="text/css" />
<?php } ?>
 <script type="text/javascript">
  
  //Function to hide the loading div
  function loadingDivHide()
  {
      document.getElementById("loading_div").style.display = "none";
      document.getElementById("content_area_div").style.display = "";
  }
  
</script>
<style type="text/css">
  <!--
     .loaderClass
     {
        position: absolute;
        top: 0px;
        left: 0px;
        z-index: 999999;
        text-align: center;
        width: 100%;
        height: 200px
     }
  //-->
  </style>
</head>
<body>

<div style="padding:10px">

    <div class="text_body_vlarge"><?php echo $_SESSION['MVGitHub_acteam'];?></div>
    <div class="text_body_large"><?php echo $_SESSION['reportname'];?></div>
	<div class="rpt_period">        
        <table cellpadding="0" cellspacing="0" width="100%">
        	<tr>
            	<td width="50%">
	        		Report Date : <span ><?php echo date("F jS, Y ",strtotime($_SESSION['yaleo'])); ?> </span>
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
    <table border="0" cellpadding="0" cellspacing="0">
    	<tr>
        	<td width="55%">

    <table border="0" cellpadding="2" cellspacing="0">
	   	<tr>
        	<td class="tbl_h">
            Region
            </td>
            <td class="tbl_h">
            B/F
            </td>
            <td class="tbl_h">
            Recorded
            </td>
            <td class="tbl_h">
            Total Pending
            </td>
            <td class="tbl_h">
            Closed within TAT
            </td>
            <td class="tbl_h">
            Closed beyond TAT
            </td>
            <td class="tbl_h">
            Total Closed
            </td>
            <td class="tbl_h">
            C/F
            </td>
        </tr>
          <?php
//the report per region
$sql="SELECT idusrteamzone,userteamzonename FROM usrteamzone 
WHERE usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']." ";
$res=mysql_query($sql);
$num=mysql_num_rows($res);
$fet=mysql_fetch_array($res);
//echo $sql;
/*
(SELECT count(*) FROM tktin WHERE tktin.timereported < '".$_SESSION['yaleo']."' AND tktin.usrteamzone_idusrteamzone=idusrteamzone AND (tktstatus_idtktstatus!=4 AND tktstatus_idtktstatus!=5) ) as BF,
(SELECT count(*) FROM tktin WHERE tktin.timereported >='".$_SESSION['yaleo']."' AND tktin.timereported <= '".$_SESSION['timestop']."' AND  tktin.usrteamzone_idusrteamzone=idusrteamzone ) as RecordedM,
(SELECT count(*) FROM tktin WHERE TIME_TO_SEC(TIMEDIFF(tktin.timeclosed,tktin.timereported)) <= tktcategory.tat AND tktin.timereported >= '".$_SESSION['yaleo']."' AND tktin.timereported <= '".$_SESSION['timestop']."' AND  tktin.usrteamzone_idusrteamzone=idusrteamzone AND (tktstatus_idtktstatus=4 OR tktstatus_idtktstatus=5) ) as withinTAT,
(SELECT count(*) FROM tktin WHERE TIME_TO_SEC(TIMEDIFF(tktin.timeclosed,tktin.timereported)) > tktcategory.tat AND tktin.timereported < '".$_SESSION['yaleo']."' AND tktin.timereported <= '".$_SESSION['timestop']."' AND  tktin.usrteamzone_idusrteamzone=idusrteamzone AND (tktstatus_idtktstatus=4 OR tktstatus_idtktstatus=5) ) as beyondTAT,
(SELECT count(*) FROM tktin WHERE tktin.usrteamzone_idusrteamzone=".$_SESSION['MVGitHub_userteamzoneid']." AND (tktstatus_idtktstatus!=4 AND tktstatus_idtktstatus!=5) AND tktin.usrteamzone_idusrteamzone=idusrteamzone ) as CF
*/
				if ($num > 0)
					{

		  			$totbf=0;	
					$totrecorded=0;
					$totpending=0;
					$totcwtat=0;
					$totcbtat=0;
					$totclosed=0;
					$totcf=0;
		  
					 do {
					?>
					<tr>
					<td class="tbl_data"><?php echo $fet['userteamzonename']; ?></td>
					<td class="tbl_data">
					<?php 
					$sql_bf="SELECT count(*) as closed,
					(SELECT count(*) FROM tktin WHERE timereported<'".$_SESSION['yaleo']." 00:00:00' AND  date(timereported)!='0000-00-00' AND usrteamzone_idusrteamzone=".$fet['idusrteamzone'].") as overall
					 FROM tktin where timeclosed<='".$_SESSION['yaleo']." 00:00:00' AND  date(timeclosed)!='0000-00-00' AND usrteamzone_idusrteamzone=".$fet['idusrteamzone']."";
					/*$sql_bf="SELECT count(*) as BF FROM tktin 
					WHERE timereported<'".$_SESSION['yaleo']." 00:00:00' 
					AND tktstatus_idtktstatus<4
					AND usrteamzone_idusrteamzone=".$fet['idusrteamzone']."";*/
					//echo $sql_bf;
					//replace $fet_bf['BF'] with $Brought_Forward;
					$res_bf=mysql_query($sql_bf);
					$fet_bf=mysql_fetch_array($res_bf);
					
					$Brought_Forward_raw=($fet_bf['overall']-$fet_bf['closed']);
					
					$Brought_Forward=number_format($Brought_Forward_raw,0); 
					echo $Brought_Forward;
					//echo number_format($fet_bf['BF'],0); 
					
					$totbf=$totbf+$Brought_Forward_raw;
					?>
                    </td>
					<td class="tbl_data">
                    <?php 
					$sql_recm="SELECT count(*) as RecordedM FROM tktin 
					WHERE date(timereported)='".$_SESSION['yaleo']."' 
					AND usrteamzone_idusrteamzone=".$fet['idusrteamzone']."";
					//echo $sql_recm;
					$res_recm=mysql_query($sql_recm);
					$fet_recm=mysql_fetch_array($res_recm);
					echo number_format($fet_recm['RecordedM'],0); 
					
					$totrecorded=$totrecorded+$fet_recm['RecordedM'];
					?>
                    </td>
                    <td class="tbl_data">
						<?php  
						$totpen=$Brought_Forward_raw+$fet_recm['RecordedM'];
						echo number_format($totpen,0); 
						
						$totpending=$totpending+$totpen;
						?>
                    </td>
					<td class="tbl_data">
					<?php 
					/*$sql_wtat="SELECT count(*) as withinTAT FROM tktin 
					INNER JOIN tktcategory ON tktin.tktcategory_idtktcategory=tktcategory.idtktcategory 
					WHERE TIME_TO_SEC(TIMEDIFF(tktin.timeclosed,tktin.timereported)) <= tktcategory.tat 
					AND tktin.timereported LIKE '%".$_SESSION['yaleo']."%'   
					AND (tktstatus_idtktstatus=4 OR tktstatus_idtktstatus=5)
					AND usrteamzone_idusrteamzone=".$fet['idusrteamzone']."";
					*/
					$sql_wtat="SELECT count(*) as withinTAT FROM tktin 
					INNER JOIN tktcategory ON tktin.tktcategory_idtktcategory=tktcategory.idtktcategory 
					WHERE 
					TIME_TO_SEC(TIMEDIFF(tktin.timeclosed,tktin.timereported)) <= tktcategory.tat 
					AND date(tktin.timeclosed)='".$_SESSION['yaleo']."' 
					AND tktin.timeclosed!='0000-00-00 00:00:00'
					AND tktstatus_idtktstatus>3
					AND usrteamzone_idusrteamzone=".$fet['idusrteamzone']."";
					
					//echo $sql_wtat;
					$res_wtat=mysql_query($sql_wtat);
					$fet_wtat=mysql_fetch_array($res_wtat);
					echo number_format($fet_wtat['withinTAT'],0); 
					
					$totcwtat=$totcwtat+$fet_wtat['withinTAT'];
					?>
                    </td>
					<td class="tbl_data">
                    <?php 
					/*$sql_btat="SELECT count(*) as beyondTAT FROM tktin 
					INNER JOIN tktcategory ON tktin.tktcategory_idtktcategory=tktcategory.idtktcategory
					WHERE TIME_TO_SEC(TIMEDIFF(tktin.timeclosed,tktin.timereported)) > tktcategory.tat 
					AND tktin.timereported LIKE '%".$_SESSION['yaleo']."%' 
					AND (tktstatus_idtktstatus=4 OR tktstatus_idtktstatus=5)
					AND usrteamzone_idusrteamzone=".$fet['idusrteamzone']."";
					*/
					$sql_btat="SELECT count(*) as beyondTAT FROM tktin 
					INNER JOIN tktcategory ON tktin.tktcategory_idtktcategory=tktcategory.idtktcategory 
					WHERE 
					TIME_TO_SEC(TIMEDIFF(tktin.timeclosed,tktin.timereported)) > tktcategory.tat  					
					AND date(tktin.timeclosed)='".$_SESSION['yaleo']."' 
					AND tktin.timeclosed!='0000-00-00 00:00:00'
					AND tktstatus_idtktstatus>3
					AND usrteamzone_idusrteamzone=".$fet['idusrteamzone']."";
					//echo $sql_bf;
					$res_btat=mysql_query($sql_btat);
					$fet_btat=mysql_fetch_array($res_btat);
					echo number_format($fet_btat['beyondTAT'],0); 
					
					$totcbtat=$totcbtat+$fet_btat['beyondTAT'];
					?>
                    </td>
                    <td class="tbl_data">
						<?php 
						$totclsd=$fet_wtat['withinTAT']+$fet_btat['beyondTAT'];
						echo number_format($totclsd,0); 
						
						$totclosed=$totclosed+$totclsd;
						?>
                   	</td>
					<td class="tbl_data">
					<?php 
					//echo $_SESSION['yaleo'];
					//$Date = "2010-09-17";
					//$DateCF=date('Y-m-d', strtotime($_SESSION['yaleo']. ' + 1 days'));
					/*
					$sql_cf="SELECT count(*) as CF FROM tktin 
					WHERE tktin.usrteamzone_idusrteamzone=".$fet['idusrteamzone']." 
					AND date(timereported)<'".date("Y-m-d",strtotime($_SESSION['yaleo']) + (1*86400))."' 
					AND tktin.timeclosed='0000-00-00 00:00:00'
					AND tktstatus_idtktstatus<4 ";
					$res_cf=mysql_query($sql_cf);
					$fet_cf=mysql_fetch_array($res_cf);
					//echo $sql_cf;
					echo number_format($fet_cf['CF'],0); 
					*/
					
					echo number_format(($totpen-$totclsd),0);
					$minus=$totpen-$totclsd;
					
					$totcf=$totcf+$minus;
					?>
                    </td>
					</tr>
					<?php
					 } while ($fet=mysql_fetch_array($res));
					?>
       	<tr>
        	<td class="tbl_h" style="background-color:#66FFFF; color:#000000;">
            Total
            </td>
        	<td class="tbl_h" style="background-color:#66FFFF; color:#000000;">
            	<?php echo number_format($totbf,0); ?>
            </td>
        	<td class="tbl_h" style="background-color:#66FFFF; color:#000000;">
            	<?php echo number_format($totrecorded,0); ?>
            </td>
        	<td class="tbl_h" style="background-color:#66FFFF; color:#000000;">
            	<?php echo number_format($totpending,0); ?>
            </td>
        	<td class="tbl_h" style="background-color:#66FFFF; color:#000000;">
            	<?php echo number_format($totcwtat,0); ?>
            </td>
        	<td class="tbl_h" style="background-color:#66FFFF; color:#000000;">
            	<?php echo number_format($totcbtat,0); ?>
            </td>
        	<td class="tbl_h" style="background-color:#66FFFF; color:#000000;">
            	<?php echo number_format($totclosed,0); ?>
            </td>
        	<td class="tbl_h" style="background-color:#66FFFF; color:#000000;">
            	<?php echo number_format($totcf,0); ?>
            </td>
        </tr>      
    
                    
        
    </table> 
    
        </td>
    <td width="5%">
    </td>
    <td width="40%" valign="top">
    	 <table width="100%" border="0" cellpadding="2" cellspacing="0" style="border:1px solid #999999">
        <tr>
            <td width="30%" class="tbl_h">Legend Title</td>
            <td width="70%" class="tbl_h" style="color:#cccccc">-</td>
        </tr>
        <tr>
            <td class="tbl_data">TAT</td>
            <td class="tbl_data">Turn Around Time<small> [ As per the Service Charter ]</small></td>
        </tr>
        <tr>
            <td bgcolor="#FBFBFB" class="tbl_data">B/F</td>
            <td bgcolor="#FBFBFB" class="tbl_data">Complaints Brought Forward</td>
        </tr>
        <tr>
            <td class="tbl_data">C/F</td>
            <td class="tbl_data">Complaints Carried Forward</td>
        </tr>
        <tr>
            <td colspan="2" style="padding:15px 0px 10px 5px" bgcolor="#FBFBFB">
                <div class="text_small"><small>[ 1 ]</small> Tickets Closed includes Resolved as well as Invalidated Tickets</div>
            </td>
        </tr>
    </table>    
    </td>
    </tr>
    </table>

       
    <?php    

	
	} else {
	
	echo "<span class=\"msg_warning\">Regions not found - Please contact Admin</span>";
	
	} //if regions exist

    ?>
    </div>
</div>
</body>
</html>
