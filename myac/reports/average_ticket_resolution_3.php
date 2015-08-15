<?php
require_once('../../assets_backend/be_includes/config.php');

if (isset($_GET['catid']))
	{
	$_SESSION['catid']=substr(preg_replace('/[^a-z\-_0-9\.:\/\s]/i','',mysql_escape_string(trim($_GET['catid']))),0,20);
	}

if (isset($_GET['catname']))
	{
	$_SESSION['catname']=substr(preg_replace('/[^a-z\-_0-9\.:\/\s]/i','',mysql_escape_string(trim($_GET['catname']))),0,30);
	}

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
	header("Content-Disposition: attachment; filename=average_ticket_resolution_3.xls");
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
<link href="../../thickbox/original_thickbox.css" rel="stylesheet" type="text/css" />
<?php } ?>
<script type="text/javascript" src="../../scripts/ajaxtabs.js"></script>
<script type="text/javascript" src="../../scripts/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="../../thickbox/thickbox_be.js"></script>
<title><?php echo $_SESSION['reportname'];?></title>
</head>
<body>
<div style="padding:10px">
    <div >
    <div class="text_body_vlarge"><?php echo $_SESSION['MVGitHub_acteam'];?></div>
    <div class="text_body_large"><?php echo $_SESSION['rptname']." - ".$_SESSION['catname'];?></div>
    </div>

    <div class="text_body_large">
        	<table cellpadding="0" cellspacing="0" width="100%">
        	<tr>
            	<td width="40%">
				    Report Period<br /> <?php echo date("D, M d, Y",strtotime($_SESSION['timestart'])); ?> - <?php echo date("D, M d, Y",strtotime($_SESSION['timestop'])); ?>
                </td>
                <?php if (!isset($_GET['exportid'])) { ?>
                <td width="10%"><strong>
                    <a href="average_ticket_resolution.php?timestart=<?php echo $_SESSION['timestart']; ?>&timestop=<?php echo $_SESSION['timestop']; ?>">Back</a></strong>
               	</td>
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
	if(isset($_SESSION['regid']))
		{
		$sql="SELECT wftasks.idwftasks,tktin.idtktinPK,tktin.refnumber,tktin.timereported,tktin.timeclosed,usrac.fname,usrac.lname,usrrole.usrrolename,usrac.usrname,idusrrole 
		FROM wftasks
		INNER JOIN tktin ON wftasks.tktin_idtktin=tktin.idtktinPK	
		LEFT JOIN usrrole ON wftasks.usrrole_idusrrole=usrrole.idusrrole
		LEFT JOIN usrac ON usrrole.idusrrole=usrac.usrrole_idusrrole 
		WHERE
		((wftasks.wftskstatustypes_idwftskstatustypes=1 AND wftasks.wftskstatusglobal_idwftskstatusglobal=3) OR (wftasks.wftskstatustypes_idwftskstatustypes=4 AND wftasks.wftskstatusglobal_idwftskstatusglobal=3))
		AND ( (date(tktin.timereported) = '".$_SESSION['timestart']."') OR (date(tktin.timereported) > '".$_SESSION['timestart']."') )
		AND ( (date(tktin.timereported) = '".$_SESSION['timestop']."') OR (date(tktin.timereported) < '".$_SESSION['timestop']."') ) 
		AND tktin.usrteamzone_idusrteamzone=".$_SESSION['regid']."
		AND (tktstatus_idtktstatus=4 OR tktstatus_idtktstatus=5)
		AND timeclosed!='0000-00-00 00:00:00'
		AND tktin.tktcategory_idtktcategory=".$_SESSION['catid']."";
		} else {
		$sql="SELECT wftasks.idwftasks,tktin.idtktinPK,tktin.refnumber,tktin.timereported,tktin.timeclosed,usrac.fname,usrac.lname,usrrole.usrrolename,usrac.usrname,idusrrole 
		FROM wftasks
		INNER JOIN tktin ON wftasks.tktin_idtktin=tktin.idtktinPK	
		LEFT JOIN usrrole ON wftasks.usrrole_idusrrole=usrrole.idusrrole
		LEFT JOIN usrac ON usrrole.idusrrole=usrac.usrrole_idusrrole 
		WHERE
		((wftasks.wftskstatustypes_idwftskstatustypes=1 AND wftasks.wftskstatusglobal_idwftskstatusglobal=3) OR (wftasks.wftskstatustypes_idwftskstatustypes=4 AND wftasks.wftskstatusglobal_idwftskstatusglobal=3))
		AND ( (date(tktin.timereported) = '".$_SESSION['timestart']."') OR (date(tktin.timereported) > '".$_SESSION['timestart']."') )
		AND ( (date(tktin.timereported) = '".$_SESSION['timestop']."') OR (date(tktin.timereported) < '".$_SESSION['timestop']."') ) 
		AND (tktstatus_idtktstatus=4 OR tktstatus_idtktstatus=5)
		AND timeclosed!='0000-00-00 00:00:00'
		AND tktin.tktcategory_idtktcategory=".$_SESSION['catid']."";		
		}

    $res=mysql_query($sql);
    $num=mysql_num_rows($res);
    $fet=mysql_fetch_array($res);

    if ($num > 0)
        {
    
    ?>
    <table border="0" cellpadding="2" cellspacing="0">
        <tr>
          <td class="tbl_h">
            Ticket No            	</td>
          <td class="tbl_h">
            Time Reported          	</td>
          <td class="tbl_h">
            Time Closed          	</td>  
		  <td class="tbl_h">
            Role            		</td>
          <td class="tbl_h">
            User 				      </td>
          <td class="tbl_h">
            Name      </td>
      </tr>
        <?php
            do {
        ?>
        <tr>
        	<td class="tbl_data">
            	<!--<a href="pop_taskview.php?task=<?php echo $row_rs_list['idwftasks']; ?>&amp;title=<?php echo $row_rs_list['tasksubject']; ?>&amp;tabview=1&amp;tabview=1&keepThis=true&TB_iframe=true&height=500&width=800&inlineId=hiddenModalContent&modal=true" class="thickbox" >-->
            	<strong><a href="../pop_taskview.php?tkt=<?php echo $fet['idtktinPK'];?>&task=<?php echo $fet['idwftasks'];?>&amp;tabview=1&amp;tabview=1&keepThis=true&TB_iframe=true&height=500&width=800&inlineId=hiddenModalContent&modal=true" class="thickbox"><?php echo $fet['refnumber'];?></a></strong>
           	</td>              
            <td class="tbl_data">
			<?php echo $fet['timereported'];?>
            </td>
            <td class="tbl_data">
			<?php echo $fet['timeclosed'];?>
            </td>
            <td class="tbl_data">
            <?php echo $fet['usrrolename'];?>
            </td>
            <td class="tbl_data">   
			<?php if(strlen($fet['usrname'])==0) { ?><span style="color:#FF0000; font-style:italic; font-size:10px;">Vacant Role</span><?php } else { echo $fet['usrname']; } ?>
            </td>
            <td class="tbl_data">
            <?php if(strlen($fet['fname'])==0) { ?><span style="color:#FF0000; font-style:italic; font-size:10px;">Vacant Role</span><?php } else { echo $fet['fname']." ".$fet['lname']; } ?>
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

    </div>
</div>
</body>
</html>
