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
	header("Content-Disposition: attachment; filename=secondary_tkts_global.xls");
	header("Pragma: no-cache");
	header("Expires: 0");
	print "$header\n$data";	
	}	
		
?>	
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php if(!isset($_GET['exportid'])) { ?>
<link href="../css_report_print.css" rel="stylesheet" type="text/css" media="print" />
<link href="../css_report.css" rel="stylesheet" type="text/css" />
<?php } ?>
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
                <?php if(!isset($_GET['exportid'])) { ?>
                <td width="50%" align="right">
                	<span><a class="text_body" href="<?php $_SERVER['PHP_SELF'];?>?exportid=1">Export to Excel</a></span>
                </td>
                <?php } ?>
        	</tr>
       	</table> 
    
    </div>
    <div style="padding:10px 5px">
    <?php   
   
    //run the queries and do the tabular table below
    $sql="SELECT count(*) as tkts_st, userteamzonename,usrteamzone.idusrteamzone,
    (SELECT count(*) FROM tktin WHERE usrteamzone_idusrteamzone=usrteamzone.idusrteamzone AND date(tktin.timereported)>='".$_SESSION['timestart']."' AND date(tktin.timereported)<='".$_SESSION['timestop']."' AND tktin.tktcategory_idtktcategory=27 ) as TTL,
	(SELECT count(*) FROM tktin WHERE usrteamzone_idusrteamzone=usrteamzone.idusrteamzone AND (tktstatus_idtktstatus=4 OR tktstatus_idtktstatus=5) AND date(tktin.timeclosed) >= '".$_SESSION['timestart']."' AND date(tktin.timeclosed) <= '".$_SESSION['timestop']."' AND tktin.tktcategory_idtktcategory=27 ) as ToTC,    
	(SELECT count(*) FROM tktin WHERE usrteamzone_idusrteamzone=usrteamzone.idusrteamzone AND tktstatus_idtktstatus<4 AND tktin.tktcategory_idtktcategory=27 ) as ToTPEN
    FROM tktin 
    INNER JOIN usrteamzone ON tktin.usrteamzone_idusrteamzone=usrteamzone.idusrteamzone
    GROUP BY tktin.usrteamzone_idusrteamzone";
    $res=mysql_query($sql);
    $num=mysql_num_rows($res);
    $fet=mysql_fetch_array($res);
//echo $sql;
    if ($num > 0)
        {
    
    ?>
    <table border="0" cellpadding="0" cellspacing="0">
    <tr>
        <td width="45%">
    
    <table border="0" cellpadding="2" cellspacing="0">
        <tr>
          <td class="tbl_h">
            Region            		</td>
          <td class="tbl_h">
            Total Received          </td>
		  <td class="tbl_h">
            Total Closed            </td>
          <td class="tbl_h">
            Total Pending       </td>
      </tr>
        <?php
		
			$total_received="";
			$total_closed="";
			$total_pending="";
			
            do {
        ?>
        <tr>
            <td class="tbl_data">
            	<?php if( ($fet['TTL']==0) && ($fet['ToTC']==0) && ($fet['ToTPEN']==0) ) {?>	
                <strong><?php echo $fet['userteamzonename'];?></strong>
         		<?php } else { ?>
                <strong><a href="secondary_tkts_regional.php?regid=<?php echo $fet['idusrteamzone'];?>&amp;regname=<?php echo $fet['userteamzonename']?>"><?php echo $fet['userteamzonename'];?></a></strong>
                <?php } ?>
            </td>              
            <td class="tbl_data">
			<?php echo number_format($fet['TTL'],0);?>
            </td>
            <td class="tbl_data">
            <?php 
			//$perc_raw_clsd = ($fet['ToTC']/$fet['TTL'])*100;
			//$perc_w_clsd = number_format($perc_raw_clsd,2);
			
			//echo number_format($fet['ToTC'],0)." <span style=\"color:#ff6600;font-size:10px\">(".$perc_w_clsd."%)</span>";
			echo number_format($fet['ToTC'],0);
			?>
            </td>

          <td class="tbl_data">
            <?php 
/*			$perc_raw_w = ($fet['ToTPEN']/$fet['ToTC'])*100;
			$perc_w = number_format($perc_raw_w,2);
			
			echo $fet['ToTPEN']."   <span style=\"color:#009900;font-size:10px\">(".$perc_w."%)</span>";*/
			echo number_format($fet['ToTPEN'],0);
			?>
          </td>          
        </tr>
		<?php 
		$total_received=($total_received+$fet['TTL']);
		$total_closed=($total_closed+$fet['ToTC']);
		$total_pending=($total_pending+$fet['ToTPEN']);

		} while ($fet=mysql_fetch_array($res)); ?>  
        <tr>
        	<td class="tbl_h" style="background-color:#66FFFF; color:#000000;">
            Total
            </td>
        	<td class="tbl_h" style="background-color:#66FFFF; color:#000000;">
            	<?php echo number_format($total_received,0); ?>
            </td>
        	<td class="tbl_h" style="background-color:#66FFFF; color:#000000;">
            	<?php echo number_format($total_closed,0); ?>
            </td>
        	<td class="tbl_h" style="background-color:#66FFFF; color:#000000;">
            	<?php echo number_format($total_pending,0); ?>
            </td>
        </tr>
        
    </table>  
         </td>
    <td width="5%">
    </td>
    <td width="50%" valign="top">
    	<table width="100%" border="0" cellpadding="2" cellspacing="0" style="border:1px solid #999999">
        <tr>
            <td width="30%" class="tbl_h">Legend Title</td>
            <td width="70%" class="tbl_h" style="color:#cccccc">-</td>
        </tr>
        <tr>
            <td class="tbl_data">Tickets Received</td>
            <td class="tbl_data">All Secondary Tickets received within the specified period</td>
        </tr>
        <tr style="background-color:#FBFBFB">
            <td class="tbl_data">Tickets Closed</td>
            <td class="tbl_data">All Secondary Tickets closed within the specified period <br /><span class="text_small">[ Closed tickets include those recieved before but closed within the specified period ]</span></td>
        </tr>
        <tr>
            <td class="tbl_data">Tickets Pending</td>
            <td class="tbl_data">All the secondary tickets pending as at today</td>
        </tr>
<!--   	<tr>
            <td colspan="2" style="padding:15px 0px 10px 5px" bgcolor="#FBFBFB">
                <div class="text_small"><small>[ 1 ]</small> Tickets Closed includes Resolved as well as Invalidated Tickets</div>
                <div class="text_small"><small>[ 2 ] In this table, tickets are listed as Closed even if they were closed after the selected period, as long as they were originally received in the selected period</small></div>
            </td>
        </tr>
-->    </table>    
    </td>
    </tr>
    </table>    

    
<!--    <div style="padding:15px 2px" class="text_small"><small>In this table, all tickets that were closed within this month are listed, even if they were originally received in an earlier month. Tickets that were recorded in this month, but closed in another month are not listed here, but instead under the month they were closed in</small></div> -->
    <?php    
        } else {
        
         echo "<span style=\"font-family:arial;color:#ff0000\">No Data to Generate Report</span>";
        
        }
    ?>
    </div>
</div>
</body>
</html>
