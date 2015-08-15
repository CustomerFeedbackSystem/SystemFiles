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
	header("Content-Disposition: attachment; filename=global_pending_tickets_bycategory.xls");
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
   
    //run the queries and do the tabular table below
    $sql="SELECT tktin.tktcategory_idtktcategory,tktcategoryname,count(idtktin) as pending FROM tktin
	INNER JOIN tktcategory on tktin.tktcategory_idtktcategory=tktcategory.idtktcategory 
	WHERE tktstatus_idtktstatus<4
	GROUP BY tktin.tktcategory_idtktcategory";
    $res=mysql_query($sql);
    $num=mysql_num_rows($res);
    $fet=mysql_fetch_array($res);

    if ($num > 0)
        { ?>
    <table border="0" cellpadding="2" cellspacing="0">
        <tr>
          <td class="tbl_h">
            Category           		</td>
          <td class="tbl_h">
            Total Received          </td>
		  <td class="tbl_h">
            Total Pending  	         </td>
          <td class="tbl_h">
            Tickets Beyond TAT       </td>
      </tr>
        <?php
		$total_received="";
		$total_pending="";
		$total_beyondTAT="";
            do {
        ?>
        <tr>
            <td class="tbl_data">
              	<strong><?php echo $fet['tktcategoryname'];?></strong> </td>
            <td class="tbl_data">
				<?php 
				//Get total received for this category
				$sql_tot="SELECT count(idtktin) as tot FROM tktin
				INNER JOIN tktcategory on tktin.tktcategory_idtktcategory=tktcategory.idtktcategory 
				WHERE tktin.tktcategory_idtktcategory=".$fet['tktcategory_idtktcategory']." ";

				$res_tot=mysql_query($sql_tot);
				$num_tot=mysql_num_rows($res_tot);
				$fet_tot=mysql_fetch_array($res_tot);
				
				if ($num_tot>0) { echo number_format($fet_tot['tot'],0); }?>
            </td>
            <td class="tbl_data">
				<?php echo number_format($fet['pending'],0);?>            
           	</td>
	        <td class="tbl_data">
				<?php 
				//Get total received and overdure for this category
				$sql_ovd="SELECT count(idtktin) as ovd FROM tktin
				INNER JOIN tktcategory ON tktin.tktcategory_idtktcategory=tktcategory.idtktcategory 
				WHERE tktin.tktcategory_idtktcategory=".$fet['tktcategory_idtktcategory']." 
				AND TIME_TO_SEC(TIMEDIFF('".$timenowis."',tktin.timereported)) >= tktcategory.tat 
				AND tktstatus_idtktstatus<4 AND usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']."";
				//echo $sql_ovd;
				$res_ovd=mysql_query($sql_ovd);
				$num_ovd=mysql_num_rows($res_ovd);
				$fet_ovd=mysql_fetch_array($res_ovd);
				
				if ($num_ovd>0) 
					{ 
					$perc_raw = ($fet_ovd['ovd']/$fet['pending'])*100;
					$perc = number_format($perc_raw,2);

					echo $fet_ovd['ovd'];
					if ($fet_ovd['ovd']>0)
						{
						echo "&nbsp;<span style=\"color:#ff0000;font-size:10px\">(".$perc."%)</span>";
						}
					} ?>
			</td>
          
        </tr>
		<?php 
		$total_received=($total_received+$fet_tot['tot']);
		$total_pending=($total_pending+$fet['pending']);
		$total_beyondTAT=($total_beyondTAT+$fet_ovd['ovd']);
		} while ($fet=mysql_fetch_array($res)); ?>  
        <tr>
        	<td></td>
            <td class="tbl_data">
            <?php echo number_format($total_received,0);?>
            </td>
            <td class="tbl_data">
             <?php echo number_format($total_pending,0);?>
            </td>
            <td class="tbl_data">
             <?php echo number_format($total_beyondTAT,0);?>
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
</body>
</html>
