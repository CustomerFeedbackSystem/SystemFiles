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
	} else {
	$_SESSION['regid']=$_SESSION['MVGitHub_userteamzoneid'];
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
	header("Content-Disposition: attachment; filename=closed_tkts_tat_regional.xls");
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
                <?php if(isset($_SESSION['regid'])) {?>
                <td width="10%"><strong>
                	<a href="../reports/closed_tickets_tat_global.php">Back</a></strong>
               	</td>
                <?php } ?>
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
/*  $sql="SELECT count(*) as tkts_w, userteamzonename,usrteamzone.idusrteamzone,tktcategory.idtktcategory,tktcategoryname,
    (SELECT count(*) FROM tktin WHERE usrteamzone_idusrteamzone=usrteamzone.idusrteamzone AND tktcategory_idtktcategory=tktcategory.idtktcategory AND ( (date(tktin.timereported)>'".$_SESSION['timestart']."') OR (date(tktin.timereported)='".$_SESSION['timestart']."')) AND ( (date(tktin.timereported)<'".$_SESSION['timestop']."') OR (date(tktin.timereported)='".$_SESSION['timestop']."')) ) as TTL,
	(SELECT count(*) FROM tktin WHERE usrteamzone_idusrteamzone=usrteamzone.idusrteamzone AND tktcategory_idtktcategory=tktcategory.idtktcategory AND tktstatus_idtktstatus>3 AND ( (date(tktin.timeclosed) = '".$_SESSION['timestart']."') OR (date(tktin.timeclosed) > '".$_SESSION['timestart']."') ) AND ( (date(tktin.timeclosed) = '".$_SESSION['timestop']."') OR (date(tktin.timeclosed) < '".$_SESSION['timestop']."') ) ) as ToTC
    FROM tktin 
    INNER JOIN tktcategory ON tktin.tktcategory_idtktcategory=tktcategory.idtktcategory
    INNER JOIN usrteamzone ON tktin.usrteamzone_idusrteamzone=usrteamzone.idusrteamzone
    WHERE TIME_TO_SEC(TIMEDIFF(tktin.timeclosed,tktin.timereported)) <= tktcategory.tat
    AND tktstatus_idtktstatus>3 
    AND ( (date(tktin.timeclosed) = '".$_SESSION['timestart']."') OR (date(tktin.timeclosed) > '".$_SESSION['timestart']."') )
    AND ( (date(tktin.timeclosed) = '".$_SESSION['timestop']."') OR (date(tktin.timeclosed) < '".$_SESSION['timestop']."') ) 
    AND tktin.usrteamzone_idusrteamzone=".$_SESSION['regid']." 
	GROUP BY tktin.tktcategory_idtktcategory";
*/
    $sql="SELECT count(*) as tkts_w,tktin.tktcategory_idtktcategory,tktcategoryname
    FROM tktin 
    INNER JOIN tktcategory ON tktin.tktcategory_idtktcategory=tktcategory.idtktcategory
    WHERE tktin.usrteamzone_idusrteamzone=".$_SESSION['regid']."  
	AND (date(tktin.timereported) >= '".$_SESSION['timestart']."')
    AND (date(tktin.timereported) <= '".$_SESSION['timestop']."') 
	GROUP BY tktin.tktcategory_idtktcategory";

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
            Closed within TAT       </td>
          <td class="tbl_h">
            Closed Beyond TAT       </td>
      </tr>
        <?php
            do {
        ?>
        <tr>
            <td class="tbl_data"><strong><a href="../reports/closed_tickets_tat_regional_1.php?catid=<?php echo $fet['tktcategory_idtktcategory'];?>&amp;catname=<?php echo $fet['tktcategoryname']?>"><?php echo $fet['tktcategoryname'];?></a></strong></td>              
            <td class="tbl_data">
			<?php echo $fet['tkts_w'];?>
            </td>
            <td class="tbl_data">
            <?php 
/*			$sql_totcld="SELECT count(*) as totcld FROM tktin 
			WHERE tktin.usrteamzone_idusrteamzone=".$_SESSION['regid']." 
			AND tktin.tktcategory_idtktcategory=".$fet['tktcategory_idtktcategory']."
			AND ( (date(tktin.timereported)>'".$_SESSION['timestart']."') OR (date(tktin.timereported)='".$_SESSION['timestart']."')) 
			AND ( (date(tktin.timereported)<'".$_SESSION['timestop']."') OR (date(tktin.timereported)='".$_SESSION['timestop']."'))
			AND tktstatus_idtktstatus>3 ";
*/			
			$sql_totcld="SELECT count(*) as totcld
			FROM tktin
			WHERE tktin.usrteamzone_idusrteamzone=".$_SESSION['regid']."  
			AND (date(tktin.timeclosed) >= '".$_SESSION['timestart']."')
			AND (date(tktin.timeclosed) <= '".$_SESSION['timestop']."') 
			AND tktin.tktcategory_idtktcategory=".$fet['tktcategory_idtktcategory']."
			AND (tktstatus_idtktstatus=4 OR tktstatus_idtktstatus=5) ";

			$res_totcld=mysql_query($sql_totcld);
			$fet_totcld=mysql_fetch_array($res_totcld);
		
			$perc_raw_clsd = ($fet_totcld['totcld']/$fet['tkts_w'])*100;
			$perc_w_clsd = number_format($perc_raw_clsd,2);
			
			echo $fet_totcld['totcld']."   <span style=\"color:#ff6600;font-size:10px\">(".$perc_w_clsd."%)</span>";
			?>
            </td>

          <td class="tbl_data">
            <?php 
/*			$sql_totcldwt="SELECT count(*) as totcldwt FROM tktin 
			INNER JOIN tktcategory ON tktin.tktcategory_idtktcategory=tktcategory.idtktcategory
			WHERE tktin.usrteamzone_idusrteamzone=".$_SESSION['regid']." 
			AND tktin.tktcategory_idtktcategory=".$fet['tktcategory_idtktcategory']."
			AND ( (date(tktin.timereported)>'".$_SESSION['timestart']."') OR (date(tktin.timereported)='".$_SESSION['timestart']."')) 
			AND ( (date(tktin.timereported)<'".$_SESSION['timestop']."') OR (date(tktin.timereported)='".$_SESSION['timestop']."'))
			AND tktstatus_idtktstatus>3 
			AND TIME_TO_SEC(TIMEDIFF(tktin.timeclosed,tktin.timereported)) <= tktcategory.tat";
*/	

			$sql_totcldwt="SELECT count(*) as totcldwt
			FROM tktin 
			INNER JOIN tktcategory ON tktin.tktcategory_idtktcategory=tktcategory.idtktcategory
			WHERE tktin.usrteamzone_idusrteamzone=".$_SESSION['regid']."  
			AND (date(tktin.timeclosed) >= '".$_SESSION['timestart']."')
			AND (date(tktin.timeclosed) <= '".$_SESSION['timestop']."') 
			AND tktin.tktcategory_idtktcategory=".$fet['tktcategory_idtktcategory']."
			AND tktstatus_idtktstatus>3
			AND TIME_TO_SEC(TIMEDIFF(tktin.timeclosed,tktin.timereported)) <= tktcategory.tat";

			$res_totcldwt=mysql_query($sql_totcldwt);
			$fet_totcldwt=mysql_fetch_array($res_totcldwt);

			$perc_raw_w = ($fet_totcldwt['totcldwt']/$fet_totcld['totcld'])*100;
			$perc_w = number_format($perc_raw_w,2);
			
			echo $fet_totcldwt['totcldwt']."   <span style=\"color:#009900;font-size:10px\">(".$perc_w."%)</span>";
			?>
          </td>
          
          <td class="tbl_data">
            <?php 
/*			$sql_totcldbt="SELECT count(*) as totcldbt FROM tktin 
			INNER JOIN tktcategory ON tktin.tktcategory_idtktcategory=tktcategory.idtktcategory
			WHERE tktin.usrteamzone_idusrteamzone=".$_SESSION['regid']." 
			AND tktin.tktcategory_idtktcategory=".$fet['tktcategory_idtktcategory']."
			AND ( (date(tktin.timereported)>'".$_SESSION['timestart']."') OR (date(tktin.timereported)='".$_SESSION['timestart']."')) 
			AND ( (date(tktin.timereported)<'".$_SESSION['timestop']."') OR (date(tktin.timereported)='".$_SESSION['timestop']."'))
			AND tktstatus_idtktstatus>3 
			AND TIME_TO_SEC(TIMEDIFF(tktin.timeclosed,tktin.timereported)) > tktcategory.tat";
*/
			$sql_totcldbt="SELECT count(*) as totcldbt
			FROM tktin 
			INNER JOIN tktcategory ON tktin.tktcategory_idtktcategory=tktcategory.idtktcategory
			WHERE tktin.usrteamzone_idusrteamzone=".$_SESSION['regid']."  
			AND (date(tktin.timeclosed) >= '".$_SESSION['timestart']."')
			AND (date(tktin.timeclosed) <= '".$_SESSION['timestop']."') 
			AND tktin.tktcategory_idtktcategory=".$fet['tktcategory_idtktcategory']."
			AND tktstatus_idtktstatus>3
			AND TIME_TO_SEC(TIMEDIFF(tktin.timeclosed,tktin.timereported)) > tktcategory.tat";
			
			$res_totcldbt=mysql_query($sql_totcldbt);
			$fet_totcldbt=mysql_fetch_array($res_totcldbt);

			$perc_raw_b = ($fet_totcldbt['totcldbt']/$fet_totcld['totcld'])*100;
			$perc_b = number_format($perc_raw_b,2);
			
			echo $fet_totcldbt['totcldbt']."   <span style=\"color:#009900;font-size:10px\">(".$perc_b."%)</span>";
			?>
            </td>
        </tr>
		<?php } while ($fet=mysql_fetch_array($res)); ?>  
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
