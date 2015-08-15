<?php
require_once('../../assets_backend/be_includes/config.php');
unset($_SESSION['exportid']);
unset($_SESSION['rptttl']);
unset($_SESSION['regid']);

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
	header("Content-Disposition: attachment; filename=average_ticket_resolution.xls");
	header("Pragma: no-cache");
	header("Expires: 0");
	print "$header\n$data";	
	}	
	
/*function seconds2human($ss) 
	{
	$s = $ss % 60;
	$m = (floor(($ss%3600)/60)>0)?floor(($ss%3600)/60).' minutes':”;
	$h = (floor(($ss % 86400) / 3600)>0)?floor(($ss % 86400) / 3600).' hours':”;
	$d = (floor(($ss % 2592000) / 86400)>0)?floor(($ss % 2592000) / 86400).' days':”;
	
	return "$d $h $m $s seconds";
	}		*/
	
function seconds2human($seconds) 
	{
    $ret = "";

    /*** get the days ***/
    $days = intval(intval($seconds) / (3600*24));
    if($days> 0)
    {
        $ret .= "$days days ";
    }

    /*** get the hours ***/
    $hours = (intval($seconds) / 3600) % 24;
    if($hours > 0)
    {
        $ret .= "$hours hours ";
    }

    /*** get the minutes ***/
    $minutes = (intval($seconds) / 60) % 60;
    if($minutes > 0)
    {
        $ret .= "$minutes minutes ";
    }

    /*** get the seconds ***/
    $seconds = intval($seconds) % 60;
    if ($seconds > 0) {
        $ret .= "$seconds seconds";
    }

    return $ret;
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
    <div>
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
    $sql="SELECT count(*) as tkts_w, usrteamzone_idusrteamzone,userteamzonename,usrteamzone.idusrteamzone,
    (SELECT count(*) FROM tktin WHERE usrteamzone_idusrteamzone=usrteamzone.idusrteamzone AND date(tktin.timereported)>='".$_SESSION['timestart']."' AND date(tktin.timereported)<='".$_SESSION['timestop']."' ) as TTL,
	(SELECT count(*) FROM tktin WHERE usrteamzone_idusrteamzone=usrteamzone.idusrteamzone AND date(tktin.timereported)>='".$_SESSION['timestart']."' AND date(tktin.timereported)<='".$_SESSION['timestop']."' AND (tktstatus_idtktstatus=4 OR tktstatus_idtktstatus=5) ) as ToTC    
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
            Average Resolution Time </td>
      </tr>
        <?php
			$total_received="";
			$total_closed="";
            do {
        ?>
        <tr>
            <td class="tbl_data">
            	<strong><a href="average_ticket_resolution_2.php?regid=<?php echo $fet['idusrteamzone'];?>&regname=<?php echo $fet['userteamzonename']?>"><?php echo $fet['userteamzonename'];?></a></strong>
         	</td>              
            <td class="tbl_data">
			<?php echo $fet['TTL'];?>
            </td>
            <td class="tbl_data">
            <?php 
			$perc_raw_clsd = ($fet['ToTC']/$fet['TTL'])*100;
			$perc_w_clsd = number_format($perc_raw_clsd,2);
			
			echo $fet['ToTC']." <span style=\"color:#ff6600;font-size:10px\">(".$perc_w_clsd."%)</span>";
			?>
            </td>

          <td class="tbl_data">
            <?php 
			//Get the average resolution time for the closed ticket
			$sql_avg="SELECT idtktinPK,timereported,timeclosed FROM tktin 
			WHERE tktin.usrteamzone_idusrteamzone=".$fet['usrteamzone_idusrteamzone']."
			AND date(tktin.timereported)>='".$_SESSION['timestart']."' 
			AND date(tktin.timereported)<='".$_SESSION['timestop']."' 
			AND (tktstatus_idtktstatus=4 OR tktstatus_idtktstatus=5)
			AND timeclosed!='0000-00-00 00:00:00'"; 
			$res_avg=mysql_query($sql_avg);
			$fet_avg=mysql_fetch_array($res_avg);
			$num_avg=mysql_num_rows($res_avg);
			
			if($num_avg>0)
				{
				$differenceInSeconds_total=0;
				
				do	{
					$differenceInSeconds=0;
					$timereported  = strtotime($fet_avg['timereported']);
					$timeclosed = strtotime($fet_avg['timeclosed']);
					$differenceInSeconds = $timeclosed-$timereported;
					$differenceInSeconds_total=$differenceInSeconds_total+$differenceInSeconds;
					
					} while($fet_avg=mysql_fetch_array($res_avg));
					//Get the average number of seconds for the closed tickets
					$differenceInSeconds_avg=($differenceInSeconds_total/$num_avg);				
					echo seconds2human($differenceInSeconds_avg);
	
				} else {
				echo "--";
				}
			?>
          </td>
        </tr>
		<?php 
			$total_received=$fet['TTL']+$total_received;
			$total_closed=$fet['ToTC']+$total_closed;
			} while ($fet=mysql_fetch_array($res));  
		}
		?>
        <tr>
        	<td class="tbl_data2">
	          	<strong><a href="average_ticket_resolution_2.php?regname=All Regions"><?php echo $fet['userteamzonename'];?>Total</a></strong>
			</td>
            <td class="tbl_data2"><?php echo number_format($total_received); ?></td>
		  	<td class="tbl_data2"><?php echo number_format($total_closed); ?></td>
          	<td class="tbl_data2">
				<?php 
				//Get the average resolution time for the closed ticket
				$sql_totavg="SELECT idtktinPK,timereported,timeclosed FROM tktin 
				WHERE date(tktin.timereported)>='".$_SESSION['timestart']."' 
				AND date(tktin.timereported)<='".$_SESSION['timestop']."' 
				AND (tktstatus_idtktstatus=4 OR tktstatus_idtktstatus=5)
				AND timeclosed!='0000-00-00 00:00:00'"; 
				$res_totavg=mysql_query($sql_totavg);
				$fet_totavg=mysql_fetch_array($res_totavg);
				$num_totavg=mysql_num_rows($res_totavg);
				
				if($num_totavg>0)
					{
					$differenceInSeconds_total_2=0;
					
					do	{
						$differenceInSeconds_2=0;
						$timereported_2  = strtotime($fet_totavg['timereported']);
						$timeclosed_2 = strtotime($fet_totavg['timeclosed']);
						$differenceInSeconds_2 = $timeclosed_2-$timereported_2;
						$differenceInSeconds_total_2=$differenceInSeconds_total_2+$differenceInSeconds_2;
						
						} while($fet_totavg=mysql_fetch_array($res_totavg));
						//Get the average number of seconds for the closed tickets
						$differenceInSeconds_avg_2=($differenceInSeconds_total_2/$num_totavg);				
						echo seconds2human($differenceInSeconds_avg_2);
		
					} else {
					echo "--";
					}					
				?>
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
            <td class="tbl_data">All tickets RECEIVED within the specified period</td>
        </tr>
        <tr>
            <td bgcolor="#FBFBFB" class="tbl_data">Tickets Closed</td>
            <td bgcolor="#FBFBFB" class="tbl_data">All the tickets RECEIVED within the specified period and CLOSED as of TODAY</td>
        </tr>
        <tr>
            <td colspan="2" style="padding:15px 0px 10px 5px">
                <div class="text_small"><small>[ 1 ]</small>&nbsp;&nbsp;&nbsp;When comparing turnaround time data, please ensure to only compare time periods or categories with similar closure rates. In particular, recent months with low closure rates may underestimate final turn-around-times because <span style="color:#FF0000; font-weight:bold"><u>pending tickets are not counted and will raise the average once closed</u></span></div>
            </td>
        </tr>
   		</table>    
    </td>
    </tr>
    </table>    
</div>
</div>
</body>
</html>
