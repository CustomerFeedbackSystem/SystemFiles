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
 
   
    <table border="0" cellpadding="2" cellspacing="0">
        
        <?php
		//Get the departments and pending tasks looping them
		$sql_dpt_tkts="SELECT usrrole.usrdpts_idusrdpts,usrrole.usrteamzone_idusrteamzone,usrdptname,count(idwftasks) AS untdtsks FROM wftasks 
		INNER JOIN usrrole ON wftasks.usrrole_idusrrole=usrrole.idusrrole 
		INNER JOIN usrdpts ON usrrole.usrdpts_idusrdpts=usrdpts.idusrdpts
		WHERE ((wftskstatustypes_idwftskstatustypes=6 AND wftskstatusglobal_idwftskstatusglobal=2) OR
		(wftskstatustypes_idwftskstatustypes=0 AND wftskstatusglobal_idwftskstatusglobal=1) )
		AND usrrole.usrteamzone_idusrteamzone='".$_SESSION['MVGitHub_userteamzoneid']."'
		GROUP BY usrrole.usrdpts_idusrdpts";
		$res_dpt_tkts=mysql_query($sql_dpt_tkts);
		$fet_dpt_tkts=mysql_fetch_array($res_dpt_tkts);
		$num_dpt_tkts=mysql_num_rows($res_dpt_tkts);

		if ($num_dpt_tkts>0)
			{ //List the departments and pending tickets
			?>
    
        <tr>
          <td class="tbl_h">
            Department         		</td>
          <td class="tbl_h">
            Pending Tickets         </td>
		  <td class="tbl_h">
            Tickets Beyond TAT         </td>
          <td class="tbl_h">
            Staff with Tickets 		</td>
      	</tr>  
        
        <?php 
	
			do	{
			
			//Check for the overdue tickets and no of users in the department with the tasks
			$sql_role_tkts="SELECT usrrole.usrrolename,count(idwftasks) AS untdtsks,wftasks.usrrole_idusrrole FROM wftasks 
			INNER JOIN usrrole ON wftasks.usrrole_idusrrole=usrrole.idusrrole 
			INNER JOIN usrdpts ON usrrole.usrdpts_idusrdpts=usrdpts.idusrdpts
			WHERE (wftskstatustypes_idwftskstatustypes=0 OR wftskstatustypes_idwftskstatustypes=6) 
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
					$sql_role_odtkts="SELECT idwftasks,wftaskstrac_idwftaskstrac,tktin_idtktin
					FROM wftasks 
					WHERE (wftskstatustypes_idwftskstatustypes=0 OR wftskstatustypes_idwftskstatustypes=6) 
					AND wftasks.usrrole_idusrrole='".$fet_role_tkts['usrrole_idusrrole']."'";

					$res_role_odtkts=mysql_query($sql_role_odtkts);
					$num_role_odtkts=mysql_num_rows($res_role_odtkts);
					$fet_role_odtkts=mysql_fetch_array($res_role_odtkts);
					
					if($num_role_odtkts>0)
						{
							$count_overdue=0;
						do	{
							//find out if this ticket is overdue
							$sql_chek_ovd="SELECT idtktin FROM tktin 
							INNER JOIN tktcategory ON tktin.tktcategory_idtktcategory=tktcategory.idtktcategory
							WHERE TIME_TO_SEC(TIMEDIFF(tktin.timeclosed,tktin.timereported)) > tktcategory.tat
							AND tktin.idtktinPK='".$fet_role_odtkts['tktin_idtktin']."' ";
							$res_chek_ovd=mysql_query($sql_chek_ovd);
							$num_chek_ovd=mysql_num_rows($res_chek_ovd);
							$fet_chek_ovd=mysql_fetch_array($res_chek_ovd);
														
							if($num_chek_ovd>0)
								{
								$count_overdue=$count_overdue+1;
								//echo $count_overdue."<br>";
								} else {
								//echo "not not<br>";
								}		
							} while ($fet_role_odtkts=mysql_fetch_array($res_role_odtkts));
						}				
						
					} while ($fet_role_tkts=mysql_fetch_array($res_role_tkts));
					
				}
			?> 
			<tr>
            	<td class="tbl_data">
              		<strong><?php echo $fet_dpt_tkts['usrdptname'];?></strong> </td>
            	<td class="tbl_data">
            		<?php echo $fet_dpt_tkts['untdtsks'];?>
            	</td>
                <td class="tbl_data">
            		<?php 
					$perc_raw_b = ($count_overdue/$fet_dpt_tkts['untdtsks'])*100;
					$perc_b = number_format($perc_raw_b,2);
			
					echo $count_overdue; 
					if($count_overdue>0) 
						{ 
						echo "&nbsp;<span style=\"color:#ff0000;font-size:10px\">(".$perc_b."%)</span>"; 
						} 
						
					?>
            	</td>
                
                <td class="tbl_data">
            		<?php echo $num_role_tkts;?>
            	</td>
			<?php
				} while ($fet_dpt_tkts=mysql_fetch_array($res_dpt_tkts)); 
			} else {
	         echo "<span style=\"font-family:arial;color:#ff0000\">No pending tasks for this department</span>";
			} ?>
	
    </table>    
    </div>
</div>
</body>
</html>
