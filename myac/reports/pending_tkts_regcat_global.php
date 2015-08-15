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
	header("Content-Disposition: attachment; filename=pending_tkts_regcat_global.xls");
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
   
    //get the regions
	$sql_reglist="SELECT idusrteamzone,userteamzonename FROM usrteamzone WHERE usrteam_idusrteam='".$_SESSION['MVGitHub_idacteam']."' ORDER BY userteamzonename ASC";
	$res_reglist=mysql_query($sql_reglist);
	$fet_reglist=mysql_fetch_array($res_reglist);
	$num_reglist=mysql_num_rows($res_reglist);

    if ($num_reglist > 0)
        {  
		do	{ ?>
    <table border="0" cellpadding="2" cellspacing="0">
    	
  <tr>
        	<td colspan="4" class="tbl_data">
				<span class="text_body_large"><?php echo $fet_reglist['userteamzonename'];?></span>
			</td>
       	</tr>
        
        <?php
		//Get the categories and pending tasks looping them
		$sql_cat_tkts="SELECT tktin.tktcategory_idtktcategory,tktcategoryname,count(idtktinPK) as pending,idtktinPK, 
		(SELECT count(DISTINCT usrrole_idusrrole) FROM wftasks WHERE tktin_idtktin=idtktinPK 
		AND (wftskstatustypes_idwftskstatustypes=0 OR wftskstatustypes_idwftskstatustypes=6) ) as count_users
		FROM tktin
		INNER JOIN tktcategory on tktin.tktcategory_idtktcategory=tktcategory.idtktcategory 
		WHERE tktin.tktstatus_idtktstatus<4
		AND tktin.usrteamzone_idusrteamzone=".$fet_reglist['idusrteamzone']."
		GROUP BY tktin.tktcategory_idtktcategory";
		$res_cat_tkts=mysql_query($sql_cat_tkts);
		$fet_cat_tkts=mysql_fetch_array($res_cat_tkts);
		$num_cat_tkts=mysql_num_rows($res_cat_tkts);
		
//echo $sql_cat_tkts."<br>"; 		
		?>
    
        <tr>
          <td class="tbl_h">
            Category         		</td>
          <td class="tbl_h">
            Pending Tickets         </td>
		  <td class="tbl_h">
             Tickets Beyond TAT         </td>
          <td class="tbl_h">
            Staff with Tickets 		</td>
            <td class="tbl_h">
            Tasks Beyond TAT		</td>

      	</tr>  
        <tr>
            	<td bgcolor="#f4f4f4" class="tbl_data">New with CCA</td>
            	<td bgcolor="#f4f4f4" class="tbl_data">
            		<?php
					$sql_new="SELECT count(*) as new FROM tktin WHERE tktstatus_idtktstatus=1 
					AND tktin.tktcategory_idtktcategory='".$fet_cat_tkts['tktcategory_idtktcategory']."'
					AND tktin.usrteamzone_idusrteamzone=".$fet_reglist['idusrteamzone']." ";
					$res_new=mysql_query($sql_new);
					$fet_new=mysql_fetch_array($res_new);
					echo $fet_new['new']. " <small>New</small>";
					?>            	</td>
                <td bgcolor="#f4f4f4" class="tbl_data">
            		<?php
					$sql_otkts="SELECT count(*) as overdue FROM tktin
					INNER JOIN tktcategory on tktin.tktcategory_idtktcategory=tktcategory.idtktcategory 
					WHERE tktstatus_idtktstatus=1
					AND tktin.tktcategory_idtktcategory='".$fet_cat_tkts['tktcategory_idtktcategory']."'
					AND tktin.usrteamzone_idusrteamzone=".$fet_reglist['idusrteamzone']." 
					AND TIME_TO_SEC(TIMEDIFF(NOW(),tktin.timedeadline)) > tktcategory.tat";

					$res_otkts=mysql_query($sql_otkts);
					$num_otkts=mysql_num_rows($res_otkts);
					$fet_otkts=mysql_fetch_array($res_otkts);
					echo $fet_otkts['overdue'];
					?>
                </td>
                <td bgcolor="#f4f4f4" class="tbl_data">
                -                </td>
                <td bgcolor="#f4f4f4" class="tbl_data">
                <?php
					$sql_new="SELECT count(*) as over_tat FROM tktin 
					INNER JOIN wftasks ON tktin.idtktinPK=wftasks.tktin_idtktin
					WHERE tktstatus_idtktstatus=1 AND tktin.tktcategory_idtktcategory='".$fet_cat_tkts['tktcategory_idtktcategory']."'
					AND tktin.usrteamzone_idusrteamzone=".$fet_reglist['idusrteamzone']."
					AND wftasks.timeactiontaken>wftasks.timedeadline ";
					$res_new=mysql_query($sql_new);
					$fet_new=mysql_fetch_array($res_new);
					echo $fet_new['over_tat'];
					?>
                 </td>
      </tr>
  <?php 
		if ($num_cat_tkts>0)
			{ //Get the pending tickets per category
			$subtotal_pending="";
			$subtotal_beyondTAT="";
			$subtotal_stafftkts="";
			$subtotal_tasksTAT="";
			do	{
			
			//Check for the overdue tickets and no of users in the category with the tasks
/*			$sql_tkts="SELECT count(*) as overdue FROM tktin
			INNER JOIN tktcategory on tktin.tktcategory_idtktcategory=tktcategory.idtktcategory 
			WHERE tktstatus_idtktstatus<4
			AND tktin.tktcategory_idtktcategory='".$fet_cat_tkts['tktcategory_idtktcategory']."'
			AND tktin.usrteamzone_idusrteamzone=".$fet_reglist['idusrteamzone']." 
			AND TIME_TO_SEC(TIMEDIFF(NOW(),tktin.timedeadline)) > tktcategory.tat";
*/			
			$sql_tkts="SELECT count(*) as overdue FROM tktin
			INNER JOIN tktcategory on tktin.tktcategory_idtktcategory=tktcategory.idtktcategory 
			WHERE tktstatus_idtktstatus<4
			AND tktin.tktcategory_idtktcategory='".$fet_cat_tkts['tktcategory_idtktcategory']."'
			AND tktin.usrteamzone_idusrteamzone=".$fet_reglist['idusrteamzone']." 
			AND TIME_TO_SEC(TIMEDIFF('".$timenowis."',tktin.timereported)) > tktcategory.tat";

			$res_tkts=mysql_query($sql_tkts);
			$num_tkts=mysql_num_rows($res_tkts);
			$fet_tkts=mysql_fetch_array($res_tkts);

	/*
			if ($num_tkts>0)
				{
				//check if the ticket is overdue
				$count_overdue=0;
				do	{
					$sql_gettkt="SELECT idtktin FROM tktin 
					INNER JOIN tktcategory ON tktin.tktcategory_idtktcategory=tktcategory.idtktcategory
					WHERE tktin.idtktinPK='".$fet_tkts['idtktinPK']."' 
					AND TIME_TO_SEC(TIMEDIFF(tktin.timeclosed,tktin.timereported)) > tktcategory.tat ";
					
					$res_gettkt=mysql_query($sql_gettkt);
					$num_gettkt=mysql_num_rows($res_gettkt);
					$fet_gettkt=mysql_fetch_array($res_gettkt);
					
					if($num_gettkt>0)
						{
						$count_overdue=$count_overdue+1;
						//echo $count_overdue."<br>";
						} else {
						//echo "not not<br>";
						}		
					} while ($fet_tkts=mysql_fetch_array($res_tkts));					
				}
			*/

			?> 
			<tr>
            	<td class="tbl_data">
              		<strong><?php echo $fet_cat_tkts['tktcategoryname'];?></strong><small> (In Progress)</small> </td>
            	<td class="tbl_data">
            		<?php echo $fet_cat_tkts['pending'];?>
            	</td>
                <td class="tbl_data">
            		<?php 
					$perc_raw_b = ($fet_tkts['overdue']/$fet_cat_tkts['pending'])*100;
					$perc_b = number_format($perc_raw_b,2);
			
					echo $fet_tkts['overdue']." <span style=\"color:#ff0000;font-size:10px\">(".$perc_b."%)</span>";
					?>
            	</td>
              <td class="tbl_data">
            		<?php 
					//
					$sql_uwt="SELECT count(DISTINCT usrrole_idusrrole) as usrs FROM wftasks
					INNER JOIN tktin ON wftasks.tktin_idtktin=tktin.idtktinPK
					WHERE tktin.tktcategory_idtktcategory=".$fet_cat_tkts['tktcategory_idtktcategory']." 
					AND tktin.usrteamzone_idusrteamzone=".$fet_reglist['idusrteamzone']."
					AND (wftskstatustypes_idwftskstatustypes=0 OR wftskstatustypes_idwftskstatustypes=6)";
					$res_uwt=mysql_query($sql_uwt);
					$fet_uwt=mysql_fetch_array($res_uwt);
				//	echo "<small>".$sql_uwt."</small>";
					echo $fet_uwt['usrs'];?>
           	  </td>
                <td class="tbl_data">
                 <?php
					$sql_new="SELECT DISTINCT count(*) as over_tat FROM tktin 
					INNER JOIN wftasks ON tktin.idtktinPK=wftasks.tktin_idtktin
					WHERE tktstatus_idtktstatus<3 AND tktin.tktcategory_idtktcategory='".$fet_cat_tkts['tktcategory_idtktcategory']."'
					AND tktin.usrteamzone_idusrteamzone=".$fet_reglist['idusrteamzone']."
					AND wftasks.timeactiontaken>wftasks.timedeadline 
					AND (wftasks.wftskstatustypes_idwftskstatustypes=0 OR wftasks.wftskstatustypes_idwftskstatustypes=6) ";
					$res_new=mysql_query($sql_new);
					$fet_new=mysql_fetch_array($res_new);
					echo $fet_new['over_tat'];
					
					if ($fet_new['over_tat'] > 0)
						{
					echo "&nbsp;<span style=\"color:#ff0000;font-size:10px\">(".number_format((($fet_new['over_tat']/$fet_cat_tkts['pending'])*100),2)." %)</span>";
						}
					?>
                </td>
			</tr>               
			<?php
			$subtotal_pending=($subtotal_pending+$fet_cat_tkts['pending']);
			$subtotal_beyondTAT=($subtotal_beyondTAT+$fet_tkts['overdue']);
			$subtotal_stafftkts=($subtotal_stafftkts+$fet_uwt['usrs']);
			$subtotal_tasksTAT=($subtotal_tasksTAT+$fet_new['over_tat']);
			
				} while ($fet_cat_tkts=mysql_fetch_array($res_cat_tkts)); 
			?>
                    <tr>
        	<td bgcolor="#f4f4f4" class="tbl_data">
            Sub Total
            </td>
            <td bgcolor="#f4f4f4" class="tbl_data">
            <?php echo number_format($subtotal_pending,0);?>
            </td>
            <td bgcolor="#f4f4f4" class="tbl_data">
            <?php echo number_format($subtotal_beyondTAT,0);?>
            </td>
            <td bgcolor="#f4f4f4" class="tbl_data">
            <?php echo number_format($subtotal_stafftkts,0);?>
            </td>
            <td bgcolor="#f4f4f4" class="tbl_data">
            <?php echo number_format($subtotal_tasksTAT,0);?>
            </td>
            </tr>
            <?php } else {
	         echo "<span style=\"font-family:arial;color:#ff0000\">No pending tasks for this category</span>";
			}
	 	} while ($fet_reglist=mysql_fetch_array($res_reglist)); ?>  
    </table>    
    <?php    
        } else {
        
         echo "<span style=\"font-family:arial;color:#ff0000\">No Regions in this company</span>";
        
        }
    ?>
    </div>
</div>
</body>
</html>
