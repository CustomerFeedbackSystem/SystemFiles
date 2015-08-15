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
    <div class="text_body_vlarge">
    <?php echo $_SESSION['reportname'];?>
    </div>
    <div>
    <div class="text_body_large">
    Report Period<br /> <?php echo date("D, M d, Y",strtotime($_SESSION['timestart'])); ?> - <?php echo date("D, M d, Y",strtotime($_SESSION['timestop'])); ?>
    </div>
    <div class="hidden">
            <table border="0" width="100%">
                <tr>
                    <td class="text_small" width="80%">
                     <span class="text_small" >
                
                <a href="#" class="text_body_mod">Switch to Graph</a>                </span>                    </td>
                    <td align="right" class="text_body_mod">
                    <a href="#" onClick="window.print()">Print</a>                    </td>
                    <td align="right" class="text_body_mod">
                    <form method="post" action="" name="excel" target="_blank">
                    <a href="#" onClick="document.forms['excel'].submit()">Export to Excel</a>
                    </form>
                    </td>
                </tr>
            </table>
            </div>
    </div>
    <div style="padding:10px 5px">
    <?php   
	
   
    //run the queries and do the tabular table below
    $sql="SELECT tktcategoryname,count(idtktin) as TOTc,tktin.tktcategory_idtktcategory
	FROM tktin 
	INNER JOIN tktcategory on tktin.tktcategory_idtktcategory=tktcategory.idtktcategory 
	WHERE tktin.timereported>='".$_SESSION['timestart']."' 
	AND tktin.timereported<='".$_SESSION['timestop']."'
	AND tktin.usrteamzone_idusrteamzone=".$_SESSION['MVGitHub_userteamzoneid']." 
	AND tktin.tktstatus_idtktstatus>3
	GROUP BY tktin.tktcategory_idtktcategory";

    $res=mysql_query($sql);
    $num=mysql_num_rows($res);
    $fet=mysql_fetch_array($res);

    if ($num > 0)
        {
    
    ?>
    <table border="0" cellpadding="2" cellspacing="0">
        <tr>
          <td class="tbl_h">
            Category            	</td>
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
            <td class="tbl_data">
              <strong><?php echo $fet['tktcategoryname'];?></strong> </td>
            <td class="tbl_data">
				<?php 
				$sql_totrec="SELECT count(*) as TTL
				FROM tktin 
				WHERE usrteamzone_idusrteamzone=".$_SESSION['MVGitHub_userteamzoneid']." 
				AND tktin.timereported>='".$_SESSION['timestart']."' 
				AND tktin.timereported<='".$_SESSION['timestop']."'
				AND tktin.tktcategory_idtktcategory=".$fet['tktcategory_idtktcategory']."";
				$res_totrec=mysql_query($sql_totrec);
				$fet_totrec=mysql_fetch_array($res_totrec);
							
				echo $fet_totrec['TTL'];
				?>
            </td>
            <td class="tbl_data">
            <?php 
				$perc_raw_clsd = ($fet['ToTC']/$fet_totrec['TTL'])*100;
				$perc_w_clsd = number_format($perc_raw_clsd,2);
				
				echo $fet['ToTC']."   <span style=\"color:#ff6600;font-size:10px\">(".$perc_w_clsd."%)</span>";
			?>
            </td>

          <td class="tbl_data">
            <?php 
		    $sql_w="SELECT count(*) as tkts_w
			FROM tktin 
			INNER JOIN tktcategory ON tktin.tktcategory_idtktcategory=tktcategory.idtktcategory
			WHERE TIME_TO_SEC(TIMEDIFF(tktin.timeclosed,tktin.timereported)) <= tktcategory.tat
			AND tktstatus_idtktstatus>3 
			AND tktin.timereported>='".$_SESSION['timestart']."' 
			AND tktin.timereported<='".$_SESSION['timestop']."'
			AND tktin.usrteamzone_idusrteamzone=".$_SESSION['MVGitHub_userteamzoneid']."
			AND tktin.tktcategory_idtktcategory=".$fet['tktcategory_idtktcategory']."";
			$res_w=mysql_query($sql_w);
			$num_w=mysql_num_rows($res_w);
			$fet_w=mysql_fetch_array($res_w);
			
			$perc_raw_w = ($fet_w['tkts_w']/$fet['ToTC'])*100;
			$perc_w = number_format($perc_raw_w,2);
			
			echo $fet_w['tkts_w']."   <span style=\"color:#009900;font-size:10px\">(".$perc_w."%)</span>";
			?>
          </td>
          
          <td class="tbl_data">
            <?php 
		    $sql_b="SELECT count(*) as tkts_b
			FROM tktin 
			INNER JOIN tktcategory ON tktin.tktcategory_idtktcategory=tktcategory.idtktcategory
			WHERE TIME_TO_SEC(TIMEDIFF(tktin.timeclosed,tktin.timereported)) > tktcategory.tat
			AND tktstatus_idtktstatus>3 
			AND tktin.timereported>='".$_SESSION['timestart']."' 
			AND tktin.timereported<='".$_SESSION['timestop']."'
			AND tktin.usrteamzone_idusrteamzone=".$_SESSION['MVGitHub_userteamzoneid']."
			AND tktin.tktcategory_idtktcategory=".$fet['tktcategory_idtktcategory']."";
			$res_b=mysql_query($sql_b);
			$num_b=mysql_num_rows($res_b);
			$fet_b=mysql_fetch_array($res_b);
			
			if ($num_b>0) 
				{ 
				$perc_raw_b = ($fet_b['tkts_b']/$fet['ToTC'])*100;
				$perc_b = number_format($perc_raw_b,2);
				
				echo $fet_b['tkts_b']." <span style=\"color:#ff0000;font-size:10px\">(".$perc_b."%)</span>";
				}?>
            </td>
        </tr>
		<?php } while ($fet=mysql_fetch_array($res)); ?>  
    </table>    
    <?php    
        } else {
        
         echo "<span style=\"font-family:arial;color:#ff0000\">No Data to Generate Report</span>";
        
        }
    ?>
    </div>
</div>
</body>
</html>
