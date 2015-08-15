<?php
require_once('../../assets_backend/be_includes/config.php');
unset($_SESSION['exportid']);
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
	
if (isset($_GET['regid']))
	{
	$_SESSION['regid']=trim($_GET['regid']);
	} 	
	
if (isset($_GET['regname']))
	{
	$_SESSION['regname']=trim($_GET['regname']);;
	}

if (isset($_SESSION['regname']))
	{
	$_SESSION['rptname']="Closed Tickets ( ".$_SESSION['regname']." )";
	}
	
if (isset($_GET['rptttl']))
	{
	$_SESSION['rptttl']=mysql_real_escape_string(trim($_GET['rptttl']));
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
	header("Content-Disposition: attachment; filename=average_ticket_resolution_2.xls");
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
	
	return $d." ".$h." ".$m." ".$s." seconds";
	}*/

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
<?php if (!isset($_GET['exportid'])) { ?>
<link href="../css_report_print.css" rel="stylesheet" type="text/css" media="print" />
<link href="../css_report.css" rel="stylesheet" type="text/css" />
<?php } ?>
<title><?php echo $_SESSION['rptname'];?></title>
</head>
<body>
<div style="padding:10px">
    <div >
    <div class="text_body_vlarge"><?php echo $_SESSION['MVGitHub_acteam'];?></div>
    <div class="text_body_large"><?php echo $_SESSION['rptname']; ?></div>
    </div>

    <div class="text_body_large">
        	<table cellpadding="0" cellspacing="0" width="100%">
        	<tr>
            	<td width="50%">
				    Report Period<br /> <?php echo date("D, M d, Y",strtotime($_SESSION['timestart'])); ?> - <?php echo date("D, M d, Y",strtotime($_SESSION['timestop'])); ?>
                </td>
                <?php //if(isset($_SESSION['regid'])) {?>
                <td width="10%"><strong>
                	<a href="average_ticket_resolution.php?timestart=<?php echo $_SESSION['timestart']; ?>&timestop=<?php echo $_SESSION['timestop']; ?>">Back</a></strong>
               	</td>
                <?php //} ?>
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
    	
	//run the queries and do the tabular table below
	if (isset($_SESSION['regid']))
		{
		$sql="SELECT count(*) as tkts_w,tktin.tktcategory_idtktcategory,tktcategoryname
		FROM tktin 
		INNER JOIN tktcategory ON tktin.tktcategory_idtktcategory=tktcategory.idtktcategory
		WHERE tktin.usrteamzone_idusrteamzone=".$_SESSION['regid']."  
		AND (date(tktin.timereported) >= '".$_SESSION['timestart']."')
		AND (date(tktin.timereported) <= '".$_SESSION['timestop']."') 
		GROUP BY tktin.tktcategory_idtktcategory";
		} else {
		//For the total accros the regions
		$sql="SELECT count(*) as tkts_w,tktin.tktcategory_idtktcategory,tktcategoryname
		FROM tktin 
		INNER JOIN tktcategory ON tktin.tktcategory_idtktcategory=tktcategory.idtktcategory
		WHERE (date(tktin.timereported) >= '".$_SESSION['timestart']."')
		AND (date(tktin.timereported) <= '".$_SESSION['timestop']."') 
		GROUP BY tktin.tktcategory_idtktcategory";
		}

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
            Category            		</td>
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
            <td class="tbl_data"><strong><a href="average_ticket_resolution_3.php?catid=<?php echo $fet['tktcategory_idtktcategory'];?>&catname=<?php echo $fet['tktcategoryname']?>"><?php echo $fet['tktcategoryname'];?></a></strong></td>              
            <td class="tbl_data">
			<?php echo $fet['tkts_w'];?>
            </td>
            <td class="tbl_data">
            <?php 			
			if (isset($_SESSION['regid']))
				{
				$sql_totcld="SELECT count(*) as totcld
				FROM tktin
				WHERE tktin.usrteamzone_idusrteamzone=".$_SESSION['regid']."  
				AND date(tktin.timereported)>='".$_SESSION['timestart']."' 
				AND date(tktin.timereported)<='".$_SESSION['timestop']."'
				AND tktin.tktcategory_idtktcategory=".$fet['tktcategory_idtktcategory']."
				AND (tktstatus_idtktstatus=4 OR tktstatus_idtktstatus=5) ";
				} else {
				$sql_totcld="SELECT count(*) as totcld
				FROM tktin
				WHERE date(tktin.timereported)>='".$_SESSION['timestart']."' 
				AND date(tktin.timereported)<='".$_SESSION['timestop']."'
				AND tktin.tktcategory_idtktcategory=".$fet['tktcategory_idtktcategory']."
				AND (tktstatus_idtktstatus=4 OR tktstatus_idtktstatus=5) ";				
				}

			$res_totcld=mysql_query($sql_totcld);
			$fet_totcld=mysql_fetch_array($res_totcld);
		
			$perc_raw_clsd = ($fet_totcld['totcld']/$fet['tkts_w'])*100;
			$perc_w_clsd = number_format($perc_raw_clsd,2);
			
			echo $fet_totcld['totcld']."   <span style=\"color:#ff6600;font-size:10px\">(".$perc_w_clsd."%)</span>";
			?>
            </td>

          <td class="tbl_data">
            <?php 
			//Get the average resolution time for the closed ticket
			if (isset($_SESSION['regid']))
				{
				$sql_avg="SELECT idtktinPK,timereported,timeclosed FROM tktin 
				WHERE tktin.usrteamzone_idusrteamzone=".$_SESSION['regid']."  
				AND tktin.tktcategory_idtktcategory=".$fet['tktcategory_idtktcategory']."
				AND date(tktin.timereported)>='".$_SESSION['timestart']."' 
				AND date(tktin.timereported)<='".$_SESSION['timestop']."' 
				AND (tktstatus_idtktstatus=4 OR tktstatus_idtktstatus=5)
				AND timeclosed!='0000-00-00 00:00:00'"; 
				} else {
				$sql_avg="SELECT idtktinPK,timereported,timeclosed FROM tktin 
				WHERE tktin.tktcategory_idtktcategory=".$fet['tktcategory_idtktcategory']."
				AND date(tktin.timereported)>='".$_SESSION['timestart']."' 
				AND date(tktin.timereported)<='".$_SESSION['timestop']."' 
				AND (tktstatus_idtktstatus=4 OR tktstatus_idtktstatus=5)
				AND timeclosed!='0000-00-00 00:00:00'"; 				
				}

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
	 		$total_received= $fet['tkts_w']+$total_received;
			$total_closed=$fet_totcld['totcld']+$total_closed;

			} while ($fet=mysql_fetch_array($res)); ?>  
    	
        <tr>
        	<td class="tbl_data2">
	          	Total
			</td>
            <td class="tbl_data2"><?php echo number_format($total_received); ?></td>
		  	<td class="tbl_data2"><?php echo number_format($total_closed); ?></td>
          	<td class="tbl_data2">
				<?php 
					//Get the average resolution time for the closed ticket
				if (isset($_SESSION['regid']))
					{
					$sql_totavg="SELECT idtktinPK,timereported,timeclosed FROM tktin 
					WHERE tktin.usrteamzone_idusrteamzone=".$_SESSION['regid']."  
					AND date(tktin.timereported)>='".$_SESSION['timestart']."' 
					AND date(tktin.timereported)<='".$_SESSION['timestop']."' 
					AND (tktstatus_idtktstatus=4 OR tktstatus_idtktstatus=5)
					AND timeclosed!='0000-00-00 00:00:00'"; 
					$res_totavg=mysql_query($sql_totavg);
					$fet_totavg=mysql_fetch_array($res_totavg);
					$num_totavg=mysql_num_rows($res_totavg);
					} else {
					$sql_totavg="SELECT idtktinPK,timereported,timeclosed FROM tktin 
					WHERE date(tktin.timereported)>='".$_SESSION['timestart']."' 
					AND date(tktin.timereported)<='".$_SESSION['timestop']."' 
					AND (tktstatus_idtktstatus=4 OR tktstatus_idtktstatus=5)
					AND timeclosed!='0000-00-00 00:00:00'"; 
					$res_totavg=mysql_query($sql_totavg);
					$fet_totavg=mysql_fetch_array($res_totavg);
					$num_totavg=mysql_num_rows($res_totavg);
					}
				
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
    
    <?php    
        } else {        
         echo "<span style=\"font-family:arial;color:#ff0000\">No Data to Generate Report</span>";
        }
    ?>
    </div>
</div>

    </div>
</div>
</body>
</html>
