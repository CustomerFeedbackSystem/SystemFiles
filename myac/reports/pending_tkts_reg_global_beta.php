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
	header("Content-Disposition: attachment; filename=pending_tickets_regional_global.xls");
	header("Pragma: no-cache");
	header("Expires: 0");
	print "$header\n$data";	
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
   	
    	<table cellpadding="0" cellspacing="0" width="100%">
        	<tr>
            	<td width="50%">
	        		Report as at: <?php echo date("D, M d, Y",strtotime($timenowis)); ?>
                </td>
                <td width="50%" align="right">
                	<span><a class="text_body" href="<?php $_SERVER['PHP_SELF'];?>?exportid=1">Export to Excel</a></span>
                </td>
        	</tr>
       	</table>     
    
    </div>
    </div>
    <div style="padding:10px 5px">
    <?php   
   
    //run the queries and do the tabular table below
  /*  $sql="SELECT tktin.usrteamzone_idusrteamzone,userteamzonename,count(idtktin) as pending,idtktinPK,
	(SELECT count(distinct tktin_idtktin) FROM wftasks WHERE tktin_idtktin=idtktinPK AND 
	((wftskstatustypes_idwftskstatustypes=6 AND wftskstatusglobal_idwftskstatusglobal=2) OR 
	(wftskstatustypes_idwftskstatustypes=0 AND wftskstatusglobal_idwftskstatusglobal=1) ))
	FROM tktin
	INNER JOIN usrteamzone ON tktin.usrteamzone_idusrteamzone=usrteamzone.idusrteamzone
	WHERE tktstatus_idtktstatus<4
	GROUP BY tktin.usrteamzone_idusrteamzone";*/
	 $sql="SELECT tktin.usrteamzone_idusrteamzone,userteamzonename,count(idtktin) as pending,idtktinPK
	FROM tktin
	INNER JOIN usrteamzone ON tktin.usrteamzone_idusrteamzone=usrteamzone.idusrteamzone
	WHERE tktstatus_idtktstatus<4
	GROUP BY tktin.usrteamzone_idusrteamzone";
//	echo $sql;
    $res=mysql_query($sql);
    $num=mysql_num_rows($res);
    $fet=mysql_fetch_array($res);

    if ($num > 0)
        { ?>
    <table border="0" cellpadding="2" cellspacing="0">
        <tr>
          <td class="tbl_h">
            Region           		</td>
          <td class="tbl_h">
            Total Received          </td>
		  <td class="tbl_h">
            Total Pending       	</td>
		 <td class="tbl_h">
            Pending (at HQ)   </td>
          <td class="tbl_h">
            Tickets Beyond TAT      </td>
      </tr>
        <?php
            do {
        ?>
        <tr>
            <td class="tbl_data">
              	<strong><?php echo $fet['userteamzonename'];?></strong> </td>
            <td class="tbl_data">
				<?php 
				//Get total received for this category
				$sql_tot="SELECT count(idtktin) as tot FROM tktin
				INNER JOIN usrteamzone ON tktin.usrteamzone_idusrteamzone=usrteamzone.idusrteamzone
				WHERE tktin.usrteamzone_idusrteamzone='".$fet['usrteamzone_idusrteamzone']."' ";

				$res_tot=mysql_query($sql_tot);
				$num_tot=mysql_num_rows($res_tot);
				$fet_tot=mysql_fetch_array($res_tot);
				
				if ($num_tot>0) { echo $fet_tot['tot']; }?>
            </td>
            <td class="tbl_data">
				<?php echo $fet['pending'];?>            
           	</td>
			<td class="tbl_data">
				<?php 
				$res_athq=mysql_query('SELECT count(DISTINCT tktin_idtktin) as hq FROM wftasks 
				INNER JOIN tktin ON wftasks.tktin_idtktin=tktin.idtktinPK 
				INNER JOIN usrrole ON wftasks.usrrole_idusrrole=usrrole.idusrrole
				WHERE tktin.usrteamzone_idusrteamzone='.$fet['usrteamzone_idusrteamzone'].' AND usrrole.usrteamzone_idusrteamzone=2
				AND ((wftskstatustypes_idwftskstatustypes=6 AND wftskstatusglobal_idwftskstatusglobal=2) OR
				(wftskstatustypes_idwftskstatustypes=0 AND wftskstatusglobal_idwftskstatusglobal=1) )');
			
				$fet_athq=mysql_fetch_array($res_athq);
				echo $fet_athq['hq'];
				/*
				//GET THE PENDING IN HQ FOR THIS REGION
				$totpenatHQ="";
								
				//1) GET ALL THE PENDING TICKETS IN THE REGION
			  	$sql_HQ="SELECT idtktinPK FROM tktin
				WHERE tktin.usrteamzone_idusrteamzone='".$fet['usrteamzone_idusrteamzone']."' 
				AND tktstatus_idtktstatus<4 ";

				$res_HQ=mysql_query($sql_HQ);
				$num_HQ=mysql_num_rows($res_HQ);
				$fet_HQ=mysql_fetch_array($res_HQ);
				
				if($num_HQ>0)
					{
					do 	{
						//2) GET THE LAST TASK FOR THIS TICKET
						$sql_lasttask="SELECT idwftasks,usrrole_idusrrole FROM wftasks 
						WHERE tktin_idtktin=".$fet_HQ['idtktinPK']."
						AND ((wftskstatustypes_idwftskstatustypes=6 AND wftskstatusglobal_idwftskstatusglobal=2) OR
						(wftskstatustypes_idwftskstatustypes=0 AND wftskstatusglobal_idwftskstatusglobal=1) )
						 LIMIT 1";			

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
								{
								$totpenatHQ=$totpenatHQ+1;	
								}
							}
						} while($fet_HQ=mysql_fetch_array($res_HQ));
						
					$perc_rawHQ = ($totpenatHQ/$fet['pending'])*100;
					$percHQ = number_format($perc_rawHQ,2);

					echo $totpenatHQ;
					
					if($totpenatHQ>0)
						{
						echo "&nbsp;<span style=\"color:#ff0000;font-size:10px\">(".$percHQ."%)</span>";
						}	
					} 
					*/
				?>            
           	</td>
	        <td class="tbl_data">
				<?php 
				//Get total received and overdure for this category
				$sql_ovd="SELECT count(idtktin) as ovd FROM tktin
				INNER JOIN tktcategory ON tktin.tktcategory_idtktcategory=tktcategory.idtktcategory 
				INNER JOIN usrteamzone ON tktin.usrteamzone_idusrteamzone=usrteamzone.idusrteamzone
				WHERE tktin.usrteamzone_idusrteamzone='".$fet['usrteamzone_idusrteamzone']."' 
				AND TIME_TO_SEC(TIMEDIFF(NOW(),tktin.timedeadline)) > tktcategory.tat 
				AND tktstatus_idtktstatus<4";

				$res_ovd=mysql_query($sql_ovd);
				$num_ovd=mysql_num_rows($res_ovd);
				$fet_ovd=mysql_fetch_array($res_ovd);
				
				if ($num_ovd>0) 
					{ 
					$perc_raw = ($fet_ovd['ovd']/$fet['pending'])*100;
					$perc = number_format($perc_raw,2);

					echo $fet_ovd['ovd'];
					
					if($fet_ovd['ovd']>0)
						{
						echo "&nbsp;<span style=\"color:#ff0000;font-size:10px\">(".$perc."%)</span>";
						}
					} ?>
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
