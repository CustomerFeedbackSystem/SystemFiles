<?php
require_once('../../assets_backend/be_includes/config.php');

if (isset($_GET['display']))
	{
	$_SESSION['display_type']=trim($_GET['display']);
	} else {
	$_SESSION['display_type']="graph";
	}

/*

if (isset($_GET['reportname']))
	{
	$_SESSION['reportname']=trim($_GET['reportname']);
	}

	*/
$_SESSION['reportname']="Regions with Pending Tickets";//temporary
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
   	Report as at: <?php echo date("D, M d, Y",strtotime($timenowis)); ?>
    </div>
    <div class="hidden">
            <table border="0" width="100%">
                <tr>
                    <td class="text_small" width="80%">&nbsp;</td>
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
    $sql="SELECT idusrteamzone,userteamzonename,count(DISTINCT tktin_idtktin) as tkts FROM wftasks 
	INNER JOIN usrrole ON wftasks.usrrole_idusrrole=usrrole.idusrrole
	INNER JOIN usrteamzone ON usrrole.usrteamzone_idusrteamzone=usrteamzone.idusrteamzone
	INNER JOIN tktin ON wftasks.tktin_idtktin=tktin.idtktinPK
	WHERE 
	((wftasks.wftskstatustypes_idwftskstatustypes=0 AND wftasks.wftskstatusglobal_idwftskstatusglobal=1) OR (wftasks.wftskstatustypes_idwftskstatustypes=6 AND wftasks.wftskstatusglobal_idwftskstatusglobal=2))
	AND tktin.tktstatus_idtktstatus<4
	GROUP BY usrrole.usrteamzone_idusrteamzone";
    $res=mysql_query($sql);
    $num=mysql_num_rows($res);
    $fet=mysql_fetch_array($res);

    if ($num > 0)
        { ?>
    <table border="0" cellpadding="2" cellspacing="0">
        <tr>
          <td class="tbl_h">Region</td>
          <td class="tbl_h">
            Tasks with Tickets</td>
	  </tr>
        <?php
            do {
        ?>
        <tr>
            <td class="tbl_data">
              	<strong><?php echo $fet['userteamzonename'];?></strong> </td>
            <td class="tbl_data"><a href="pending_tkts_global_1.php?regionid=<?php echo $fet['idusrteamzone'];?>"><?php echo $fet['tkts'];?></a></td>
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
