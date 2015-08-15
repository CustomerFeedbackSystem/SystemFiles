<?php
require_once('../../assets_backend/be_includes/config.php');

if (isset($_GET['display']))
	{
	$_SESSION['display_type']=trim($_GET['display']);
	} else {
	$_SESSION['display_type']="graph";
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
	header("Content-Disposition: attachment; filename=pending_tkts_at_hq.xls");
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
<title><?php echo $_SESSION['reportname'];?></title>
</head>
<body>
<div style="padding:10px">
    <div class="text_body_vlarge">
    <?php echo $_SESSION['reportname'];?>
    </div>
    
    <div class="text_body_large">
        <table cellpadding="0" cellspacing="0" width="100%">
        	<tr>
            	<td width="50%">
	        		Report as at: <?php echo date("D, M d, Y",strtotime($timenowis)); ?>
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
    <?php   
					
	//1) GET ALL THE PENDING TICKETS IN THE REGION
    $sql="SELECT idtktinPK,refnumber,waterac,timereported,tktcategoryname FROM tktin
	INNER JOIN tktcategory ON tktin.tktcategory_idtktcategory=tktcategory.idtktcategory
	WHERE tktin.usrteamzone_idusrteamzone='".$_SESSION['MVGitHub_userteamzoneid']."' 
	AND tktstatus_idtktstatus<4 ORDER BY timereported ASC";
    $res=mysql_query($sql);
    $num=mysql_num_rows($res);
    $fet=mysql_fetch_array($res);

    if ($num > 0)
        { ?>
        <table border="0" cellpadding="2" cellspacing="0">
            <tr>
              <td class="tbl_h">
                Ticket No          		</td>
              <td class="tbl_h">
                Account No		         		      	</td>                
              <td class="tbl_h">Category</td>
              <td class="tbl_h">
                Reported On   			</td>
          </tr>        
        <?php 
		do 	{
			//2) GET THE LAST TASK FOR THIS TICKET
			$sql_lasttask="SELECT idwftasks,usrrole_idusrrole FROM wftasks 
			WHERE tktin_idtktin=".$fet['idtktinPK']." 
			AND ((wftskstatustypes_idwftskstatustypes=6 AND wftskstatusglobal_idwftskstatusglobal=2) OR
			(wftskstatustypes_idwftskstatustypes=0 AND wftskstatusglobal_idwftskstatusglobal=1) ) LIMIT 1";			

			$res_lasttask=mysql_query($sql_lasttask);
			$fet_lasttask=mysql_fetch_array($res_lasttask);
			$num_lasttask=mysql_num_rows($res_lasttask);
						
			//3) CHECK IF THE ROLE BELONGS TO HQ
			if($num_lasttask>0)
				{
				$sql_rolechek="SELECT usrteamzone_idusrteamzone FROM usrrole 
				WHERE idusrrole=".$fet_lasttask['usrrole_idusrrole']." LIMIT 1";
				$res_rolechek=mysql_query($sql_rolechek);
				$fet_rolechek=mysql_fetch_array($res_rolechek);

				if($fet_rolechek['usrteamzone_idusrteamzone']==2)
					{ ?>
                    <tr>
                        <td class="tbl_data">
                            <?php echo $fet['refnumber'];?></td>                            
                        <td class="tbl_data">
                            <?php echo $fet['waterac'];?></td>
                        <td class="tbl_data">
                            <?php echo $fet['tktcategoryname'];?></td>
                        <td class="tbl_data">
                            <?php echo $fet['timereported'];?></td>
                    </tr>
                    <?php 
					//$totpenatHQ=$totpenatHQ+1;	
					}
				}
			} while($fet=mysql_fetch_array($res));	?>
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
