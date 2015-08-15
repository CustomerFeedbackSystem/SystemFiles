<?php
require_once('../../assets_backend/be_includes/config.php');

unset($_SESSION['rptttl']);

if (isset($_GET['display']))
	{
	$_SESSION['display_type']=trim($_GET['display']);
	} else {
	$_SESSION['display_type']="graph";
	}

if ((isset($_GET['timestart'])) && ($_GET['timestart']!=''))
	{
	$_SESSION['timestart']=substr(preg_replace('/[^a-z\-_0-9\.:\/\s]/i','',mysql_escape_string(trim($_GET['timestart']))),0,10);
	} 
	
	if (!isset($_SESSION['timestart']))
	{
	$error_1 = "<div class=\"msg_warning\">Start Date is Missing</div>";
	}
	
if ((isset($_GET['timestop'])) && ($_GET['timestop']!=''))
	{
	$_SESSION['timestop']=substr(preg_replace('/[^a-z\-_0-9\.:\/\s]/i','',mysql_escape_string(trim($_GET['timestop']))),0,10);
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
	header("Content-Disposition: attachment; filename=closed_tkts_tat_global.xls");
	header("Pragma: no-cache");
	header("Expires: 0");
	print "$header\n$data";	
	}	
		
?>	
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="../css_report_print.css" rel="stylesheet" type="text/css" media="print" />
<link href="../css_report.css" rel="stylesheet" type="text/css" />
<title><?php echo $_SESSION['reportname'];?></title>
</head>
<body>
<div style="padding:10px">
    <div >
    <div class="text_body_vlarge"><?php echo $_SESSION['MVGitHub_acteam'];?></div>
    <div class="text_body_large"><?php echo $_SESSION['reportname'];?></div>
    </div>

    <div class="text_body_large">
    
    	<table cellpadding="0" cellspacing="0" width="100%">
        	<tr>
            	<td width="50%">
	        		Report Period<br /> <?php echo date("D, M d, Y",strtotime($_SESSION['timestart'])); ?> - <?php echo date("D, M d, Y",strtotime($_SESSION['timestop'])); ?>
                </td>
                <td width="50%" align="right">
                	<span><a class="text_body" href="<?php $_SERVER['PHP_SELF'];?>?exportid=1">Export to Excel</a></span>
                </td>
        	</tr>
       	</table> 
    
    </div>
    <div style="padding:10px 5px">
    <?php   
   
    //run the queries and do the tabular table below
    $sql="SELECT count(*) as tkts_w, userteamzonename,usrteamzone.idusrteamzone,
    (SELECT count(*) FROM tktin WHERE usrteamzone_idusrteamzone=usrteamzone.idusrteamzone AND date(tktin.timereported)>='".$_SESSION['timestart']."' AND date(tktin.timereported)<='".$_SESSION['timestop']."' ) as TTL,
	(SELECT count(*) FROM tktin INNER JOIN tktcategory ON tktin.tktcategory_idtktcategory=tktcategory.idtktcategory WHERE usrteamzone_idusrteamzone=usrteamzone.idusrteamzone AND (tktstatus_idtktstatus=4 OR tktstatus_idtktstatus=5) AND date(tktin.timeclosed) >= '".$_SESSION['timestart']."' AND date(tktin.timeclosed) <= '".$_SESSION['timestop']."'  ) as ToTC,    
	(SELECT count(*) FROM tktin INNER JOIN tktcategory ON tktin.tktcategory_idtktcategory=tktcategory.idtktcategory WHERE usrteamzone_idusrteamzone=usrteamzone.idusrteamzone AND (tktstatus_idtktstatus=4 OR tktstatus_idtktstatus=5) AND date(tktin.timeclosed) >= '".$_SESSION['timestart']."' AND date(tktin.timeclosed) <= '".$_SESSION['timestop']."' AND TIME_TO_SEC(TIMEDIFF(tktin.timeclosed,tktin.timereported)) <= tktcategory.tat ) as ToTCTAT,
	(SELECT count(*) FROM tktin INNER JOIN tktcategory ON tktin.tktcategory_idtktcategory=tktcategory.idtktcategory WHERE usrteamzone_idusrteamzone=usrteamzone.idusrteamzone AND (tktstatus_idtktstatus=4 OR tktstatus_idtktstatus=5) AND date(tktin.timeclosed) >= '".$_SESSION['timestart']."' AND date(tktin.timeclosed) <= '".$_SESSION['timestop']."' AND TIME_TO_SEC(TIMEDIFF(tktin.timeclosed,tktin.timereported)) > tktcategory.tat ) as ToTBTAT
    FROM tktin 
    INNER JOIN usrteamzone ON tktin.usrteamzone_idusrteamzone=usrteamzone.idusrteamzone
    GROUP BY tktin.usrteamzone_idusrteamzone";
    $res=mysql_query($sql);
    $num=mysql_num_rows($res);
    $fet=mysql_fetch_array($res);

    if ($num > 0)
        {
    
    ?>
    <table border="0" cellpadding="0" cellspacing="0">
    <tr>
        <td width="55%">
    
    <table border="0" cellpadding="2" cellspacing="0">
        <tr>
          <td class="tbl_h">
            Region            		</td>
          <td class="tbl_h">
            Total Received          </td>
		  <td class="tbl_h">
            Total Closed            </td>
          <td class="tbl_h">
            Closed within TAT       </td>
          <td class="tbl_h">
            Closed Beyond TAT       </td>
      </tr>
        <?php
			$total_received="";
			$total_closed="";
			$total_closedWTAT="";
			$total_closedBTAT="";
			
            do {
        ?>
        <tr>
            <td class="tbl_data">
            	<strong><a href="../reports/closed_tickets_tat_regional.php?regid=<?php echo $fet['idusrteamzone'];?>&amp;regname=<?php echo $fet['userteamzonename']?>"><?php echo $fet['userteamzonename'];?></a></strong>
         	</td>              
            <td class="tbl_data">
			<?php echo number_format($fet['TTL'],0);?>
            </td>
            <td class="tbl_data">
            <?php 
			$perc_raw_clsd = ($fet['ToTC']/$fet['TTL'])*100;
			$perc_w_clsd = number_format($perc_raw_clsd,2);
			
			echo number_format($fet['ToTC'],0)." <span style=\"color:#ff6600;font-size:10px\">(".$perc_w_clsd."%)</span>";
			?>
            </td>

          <td class="tbl_data">
            <?php 
			if($fet['ToTC']!=0)
				{
				$perc_raw_w = ($fet['ToTCTAT']/$fet['ToTC'])*100;
				$perc_w = number_format($perc_raw_w,2);
				
				echo number_format($fet['ToTCTAT'],0)."   <span style=\"color:#009900;font-size:10px\">(".$perc_w."%)</span>";
				} else {
				echo "0";
				}
			?>
          </td>
          
          <td class="tbl_data">
            <?php 
		   /* $sql_b="SELECT count(*) as tkts_b FROM tktin INNER JOIN tktcategory ON tktin.tktcategory_idtktcategory=tktcategory.idtktcategory WHERE date(tktin.timeclosed) >= '".$_SESSION['timestart']."' AND date(tktin.timeclosed) <= '".$_SESSION['timestop']."' AND (tktstatus_idtktstatus=4 OR tktstatus_idtktstatus=5)  AND TIME_TO_SEC(TIMEDIFF(tktin.timeclosed,tktin.timereported)) > tktcategory.tat AND tktin.usrteamzone_idusrteamzone=".$fet['idusrteamzone']."";
			$res_b=mysql_query($sql_b);
			$num_b=mysql_num_rows($res_b);
			$fet_b=mysql_fetch_array($res_b);
echo  "<span style=\"color:#ffffff\">".$sql_b."</span>";
			if ($num_b>0) 
				{ 
				$perc_raw_b = ($fet_b['tkts_b']/$fet['ToTC'])*100;
				$perc_b = number_format($perc_raw_b,2);
				
				echo $fet_b['tkts_b']."";// <span style=\"color:#ff0000;font-size:10px\">(".$perc_b."%)</span>";
				}
				*/
			if($fet['ToTC']!=0)
				{
				$perc_raw_b = ($fet['ToTBTAT']/$fet['ToTC'])*100;
				$perc_b = number_format($perc_raw_b,2);
				
				echo number_format($fet['ToTBTAT'],0)."  <span style=\"color:#ff0000;font-size:10px\">(".$perc_b."%)</span>";
				} else {
				echo "0";	
				}
				
			?>
            </td>
        </tr>
		<?php 
			$total_received=$fet['TTL']+$total_received;
			$total_closed=$fet['ToTC']+$total_closed;
			$total_closedWTAT=$fet['ToTCTAT']+$total_closedWTAT;
			$total_closedBTAT=$fet['ToTBTAT']+$total_closedBTAT;
		} while ($fet=mysql_fetch_array($res)); ?>  
        <tr>
        	<td class="tbl_data2">
            Total
            </td>
            <td class="tbl_data2">
            <?php echo number_format($total_received,0);?>
            </td>
            <td class="tbl_data2">
            <?php echo number_format($total_closed,0);?>
            </td>
            <td class="tbl_data2">
            <?php echo number_format($total_closedWTAT,0);?>
            </td>
            <td class="tbl_data2">
           <?php echo number_format($total_closedBTAT,0);?>
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
            <td class="tbl_data">Tickets Received</td>
            <td class="tbl_data">All tickets received within the specified period</td>
        </tr>
        <tr>
            <td class="tbl_data">Tickets Closed</td>
            <td class="tbl_data">All the tickets closed within the specified period<small> <br />[ The closed tickets include those recieved before but closed within the specified period ]</small></td>
        </tr>
<!--        <tr>
            <td colspan="2" style="padding:15px 0px 10px 5px" bgcolor="#FBFBFB">
                <div class="text_small"><small>[ 1 ]</small> Tickets Closed includes Resolved as well as Invalidated Tickets</div>
                <div class="text_small"><small>[ 2 ] In this table, tickets are listed as Closed even if they were closed after the selected period, as long as they were originally received in the selected period</small></div>
            </td>
        </tr>
-->    </table>    
    </td>
    </tr>
    </table>    

    
    <div style="padding:15px 2px" class="text_small"><small>In this table, all tickets that were closed within this month are listed, even if they were originally received in an earlier month. Tickets that were recorded in this month, but closed in another month are not listed here, but instead under the month they were closed in</small></div> 
    <?php    
        } else {
        
         echo "<span style=\"font-family:arial;color:#ff0000\">No Data to Generate Report</span>";
        
        }
    ?>
    </div>
</div>
</body>
</html>
