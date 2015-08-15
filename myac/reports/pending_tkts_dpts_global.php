<?php
require_once('../../assets_backend/be_includes/config.php');
set_time_limit(3600);
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
	header("Content-Disposition: attachment; filename=pending_tkts_dept_global.xls");
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
<script>
function strTime($s) {
  $d = intval($s/86400);
  $s -= $d*86400;

  $h = intval($s/3600);
  $s -= $h*3600;

  $m = intval($s/60);
  $s -= $m*60;

  if ($d) $str = $d . 'd ';
  if ($h) $str .= $h . 'h ';
  if ($m) $str .= $m . 'm ';
  if ($s) $str .= $s . 's';

  return $str;
}
</script>
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
    <table border="0" cellpadding="2" cellspacing="0" width="650">
    	
        <tr>
        	<td colspan="5" class="tbl_data">
				<span class="text_body_large"><?php echo $fet_reglist['userteamzonename'];?></span>
			</td>
       	</tr>
        <?php
		//Get the departments and pending tasks looping them
		$sql_dpt_tkts="SELECT usrrole.usrdpts_idusrdpts,usrrole.usrteamzone_idusrteamzone,usrdptname,count(distinct tktin_idtktin) AS untdtsks,wftasks.timeinactual
		FROM wftasks 
		INNER JOIN usrrole ON wftasks.usrrole_idusrrole=usrrole.idusrrole 
		INNER JOIN usrdpts ON usrrole.usrdpts_idusrdpts=usrdpts.idusrdpts
		WHERE ((wftskstatustypes_idwftskstatustypes=6 AND wftskstatusglobal_idwftskstatusglobal=2) OR
		(wftskstatustypes_idwftskstatustypes=0 AND wftskstatusglobal_idwftskstatusglobal=1) )
		AND usrrole.usrteamzone_idusrteamzone='".$fet_reglist['idusrteamzone']."'
		GROUP BY usrrole.usrdpts_idusrdpts";
		$res_dpt_tkts=mysql_query($sql_dpt_tkts);
		$fet_dpt_tkts=mysql_fetch_array($res_dpt_tkts);
		$num_dpt_tkts=mysql_num_rows($res_dpt_tkts);
		?>
    
        <tr>
          <td width="220" class="tbl_h">
            Department         		</td>
          <td width="100" class="tbl_h">
            Pending Tickets         </td>
		  <td width="100" class="tbl_h">
            Tickets Beyond TAT         </td>
          <td width="80" class="tbl_h">
            Staff with Tickets 		</td>
          <td width="270" class="tbl_h">
            Avg Time / Dept 		</td>
   	  </tr>  
        
        <?php 
		if ($num_dpt_tkts>0)
			{ //List the departments and pending tickets
			$totaltime=0;

			do	{
				//Calculate average time per department
				$timeFirst  = strtotime($fet_dpt_tkts['timeinactual']);
				$timeSecond = strtotime($timenowis);
				$differenceInSeconds = $timeSecond - $timeFirst;
				$totaltime=$totaltime+$differenceInSeconds;			
				$avgtimeInSeconds=$totaltime/$num_dpt_tkts;
			
				//Check for the overdue tickets and no of users in the department with the tasks
				$sql_role_tkts="SELECT usrrole.usrrolename,count(tktin_idtktin) AS untdtsks,wftasks.usrrole_idusrrole
				FROM wftasks 
				INNER JOIN usrrole ON wftasks.usrrole_idusrrole=usrrole.idusrrole 
				INNER JOIN usrdpts ON usrrole.usrdpts_idusrdpts=usrdpts.idusrdpts
				WHERE ((wftskstatustypes_idwftskstatustypes=6 AND wftskstatusglobal_idwftskstatusglobal=2) OR
				(wftskstatustypes_idwftskstatustypes=0 AND wftskstatusglobal_idwftskstatusglobal=1) )
				AND usrrole.usrteamzone_idusrteamzone='".$fet_dpt_tkts['usrteamzone_idusrteamzone']."'
				AND usrrole.usrdpts_idusrdpts='".$fet_dpt_tkts['usrdpts_idusrdpts']."'
				GROUP BY wftasks.usrrole_idusrrole";
				$res_role_tkts=mysql_query($sql_role_tkts);
				$num_role_tkts=mysql_num_rows($res_role_tkts);
				$fet_role_tkts=mysql_fetch_array($res_role_tkts);

			if ($num_role_tkts)
				{
				//Get the tickets per role
				do	{
/*					$sql_role_odtkts="SELECT idwftasks,wftaskstrac_idwftaskstrac,tktin_idtktin
					FROM wftasks 
					WHERE ((wftskstatustypes_idwftskstatustypes=6 AND wftskstatusglobal_idwftskstatusglobal=2) OR
					(wftskstatustypes_idwftskstatustypes=0 AND wftskstatusglobal_idwftskstatusglobal=1) )
					AND wftasks.usrrole_idusrrole='".$fet_role_tkts['usrrole_idusrrole']."'";
*/
					$sql_role_odtkts="SELECT COUNT(distinct tktin_idtktin) as ovrduetkts
					FROM wftasks 
					INNER JOIN tktin ON wftasks.tktin_idtktin=tktin.idtktinPK
					INNER JOIN tktcategory ON tktin.tktcategory_idtktcategory=tktcategory.idtktcategory
					WHERE ((wftskstatustypes_idwftskstatustypes=6 AND wftskstatusglobal_idwftskstatusglobal=2) OR
					(wftskstatustypes_idwftskstatustypes=0 AND wftskstatusglobal_idwftskstatusglobal=1) )
					AND TIME_TO_SEC(TIMEDIFF('".$timenowis."',tktin.timedeadline)) > tktcategory.tat 
					AND wftasks.usrrole_idusrrole='".$fet_role_tkts['usrrole_idusrrole']."'";

					$res_role_odtkts=mysql_query($sql_role_odtkts);
					$num_role_odtkts=mysql_num_rows($res_role_odtkts);
					$fet_role_odtkts=mysql_fetch_array($res_role_odtkts);
					
					if($num_role_odtkts>0)
						{
						
							$count_overdue=0;  
					
				/*		do	{
							//find out if this ticket is overdue
							$sql_chek_ovd="SELECT idtktin FROM tktin 
							INNER JOIN tktcategory ON tktin.tktcategory_idtktcategory=tktcategory.idtktcategory
							WHERE tktin.idtktinPK='".$fet_role_odtkts['tktin_idtktin']."'
							AND TIME_TO_SEC(TIMEDIFF(NOW(),tktin.timedeadline)) > tktcategory.tat 
							AND usrteamzone_idusrteamzone=11";
							$res_chek_ovd=mysql_query($sql_chek_ovd);
							$num_chek_ovd=mysql_num_rows($res_chek_ovd);
							$fet_chek_ovd=mysql_fetch_array($res_chek_ovd);

							if($num_chek_ovd>0)
								{
								$count_overdue+=1;
								} 		
							} while ($fet_role_odtkts=mysql_fetch_array($res_role_odtkts));
				*/			
						}				
					
					} while ($fet_role_tkts=mysql_fetch_array($res_role_tkts));
					
				}
			
			?> 
			<tr>
            	<td class="tbl_data">
              		<strong><?php echo $fet_dpt_tkts['usrdptname'];?></strong> </td>
            	<td class="tbl_data">
            		<?php echo number_format($fet_dpt_tkts['untdtsks'],0);?>
            	</td>
                <td class="tbl_data">
            		<?php 
				/*	$perc_raw_b = ($count_overdue/$fet_dpt_tkts['untdtsks'])*100;
					$perc_b = number_format($perc_raw_b,2);
			*/
				
					//echo $count_overdue; 
					echo number_format($fet_role_odtkts['ovrduetkts'],0);
					
				/*	if($count_overdue>0) 
						{ 
						echo "&nbsp;<span style=\"color:#ff0000;font-size:10px\">(".$perc_b."%)</span>"; 
						} 
				*/		
					?>
            	</td>
                <td class="tbl_data">
            		<?php echo number_format($num_role_tkts,0);?>
            	</td>
                <td class="tbl_data">
            		<small><?php 
					//average time in seconds = $avgtimeInSeconds
					if ($avgtimeInSeconds > 3600)
						{
						$days = $avgtimeInSeconds / 86400;
						$day_explode = explode(".", $days);
						$day = $day_explode[0];
						}else{
						$day = 0;
						}
						
						if ($avgtimeInSeconds > 3600)
						{
						$hours = '.'.@$day_explode[1].'';
						$hour = $hours * 24;
						$hourr = explode(".", $hour);
						$hourrs = $hourr[0];
						}else{
						$hours = $avgtimeInSeconds / 3600;
						$hourr = explode(".", $hours);
						$hourrs = $hourr[0];
						}
						
						$minute = '.'.@$hourr[1].'';
						$minutes = $minute * 60;
						$minute = explode(".", $minutes);
						$minuter = $minute[0];
						
						$avgtimeInSeconds = '.'.@$minute[1].'';
						$second = $avgtimeInSeconds * 60;
						$second = round($second);
						
						echo @$day.' Days '.@$hourrs.' Hrs '.@$minuter.' mins, '.@$second.' secs';
					
					?>
                    </small>
            	</td>
			<?php
				} while ($fet_dpt_tkts=mysql_fetch_array($res_dpt_tkts)); 
			} else {
	         echo "<span style=\"font-family:arial;color:#ff0000\">No pending tasks for this department</span>";
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
