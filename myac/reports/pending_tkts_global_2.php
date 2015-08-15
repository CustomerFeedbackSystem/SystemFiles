<?php
require_once('../../assets_backend/be_includes/config.php');

if (isset($_GET['display']))
	{
	$_SESSION['display_type']=trim($_GET['display']);
	} else {
	$_SESSION['display_type']="graph";
	}

if (isset($_GET['regionid']))
	{
	$_SESSION['regionid']=mysql_escape_string(trim($_GET['regionid']));
	}
/*

if (isset($_GET['reportname']))
	{
	$_SESSION['reportname']=trim($_GET['reportname']);
	}

	*/
$_SESSION['reportname']="Pending Tasks with Tickets | By Role";//temporary
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
    <?php
	//get the region
	$res_region=mysql_query("SELECT userteamzonename FROM usrteamzone WHERE idusrteamzone=".$_SESSION['regionid']."");
	$fet_region=mysql_fetch_array($res_region);
	echo " ".$fet_region['userteamzonename'];
	?>
    </div>
    <div>
    <div class="text_body_large">
   	Report as at: <?php echo date("D, M d, Y",strtotime($timenowis)); ?>
    </div>
    <div class="hidden">
            <table border="0" width="100%">
                <tr>
                	<td width="21%">
                    <a href="pending_tkts_global.php" class="text_body_mod">&laquo; Back to Regional Report</a>                    </td>
                    <td class="text_small" width="64%">
                     <span class="text_small" >
                
              </td>
                    <td width="5%" align="right" class="text_body_mod">
                    <a href="#" onClick="window.print()">Print</a>                    </td>
                    <td width="10%" align="right" class="text_body_mod">
                    <form method="post" action="" name="excel" target="_blank">
                    <a href="#" onClick="document.forms['excel'].submit()">Export to Excel</a>
                    </form>
                  </td>
              </tr>
              <tr>
              	<td colspan="4" class="text_body_mod">
                <a href="pending_tkts_global_1.php?<?php echo $fet['usrteamzone_idusrteamzone'];?>">By Category</a><span style="padding:0px 30px">  |</span>   By User Account                </td>
              </tr>
            </table>
            </div>
    </div>
    <div style="padding:10px 5px">
    <?php   
   
    //run the queries and do the tabular table below
    $sql="SELECT count(DISTINCT wftasks.tktin_idtktin) as tkts,usrac.fname,usrac.lname,usrrole.usrrolename,usrac.usrname,idusrrole FROM wftasks 
	INNER JOIN tktin ON wftasks.tktin_idtktin=tktin.idtktinPK
	INNER JOIN usrrole ON wftasks.usrrole_idusrrole=usrrole.idusrrole 
	INNER JOIN usrac ON usrrole.idusrrole=usrac.usrrole_idusrrole 
	WHERE 
	((wftasks.wftskstatustypes_idwftskstatustypes=0 AND wftasks.wftskstatusglobal_idwftskstatusglobal=1) OR (wftasks.wftskstatustypes_idwftskstatustypes=6 AND wftasks.wftskstatusglobal_idwftskstatusglobal=2))
	AND  usrrole.usrteamzone_idusrteamzone=".$_SESSION['regionid']."
	AND tktin.tktstatus_idtktstatus<4
	GROUP BY wftasks.usrrole_idusrrole";
    $res=mysql_query($sql);
    $num=mysql_num_rows($res);
    $fet=mysql_fetch_array($res);

    if ($num > 0)
        { ?>
    <table border="0" cellpadding="2" cellspacing="0">
        <tr>
          <td class="tbl_h">Tasks with Tickets</td>
          <td class="tbl_h">
            Role
          </td>
           <td class="tbl_h">
            User
          </td>
           <td class="tbl_h">
            Name
          </td>
	  	</tr>
        <?php
            do {
        ?>
        <tr>
            <td class="tbl_data"><a target="_blank" href="../pop_team_tasks.php?tabview=1&team_member=<?php echo $fet['idusrrole'];?>"><?php echo $fet['tkts'];?></a></td>
            <td class="tbl_data"><?php echo $fet['usrrolename'];?></td>
            <td class="tbl_data"><?php echo $fet['usrname'];?></td>
            <td class="tbl_data"><?php echo $fet['fname']." ".$fet['lname'];?></td>
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
