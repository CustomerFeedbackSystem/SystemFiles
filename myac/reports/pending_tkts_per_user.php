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
	header("Content-Disposition: attachment; filename=pending_tkts_per_user.xls");
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
    //get the departments
	$sql_reglist="SELECT usrrole.usrdpts_idusrdpts,usrrole.usrteamzone_idusrteamzone,usrdptname,count(idwftasks) AS untdtsks,wftasks.timeinactual
	FROM wftasks 
	INNER JOIN usrrole ON wftasks.usrrole_idusrrole=usrrole.idusrrole 
	INNER JOIN usrdpts ON usrrole.usrdpts_idusrdpts=usrdpts.idusrdpts
	WHERE ((wftskstatustypes_idwftskstatustypes=6 AND wftskstatusglobal_idwftskstatusglobal=2) OR
	(wftskstatustypes_idwftskstatustypes=0 AND wftskstatusglobal_idwftskstatusglobal=1) )
	AND usrrole.usrteamzone_idusrteamzone=".$_SESSION['MVGitHub_userteamzoneid']."
	GROUP BY usrrole.usrdpts_idusrdpts";
	$res_reglist=mysql_query($sql_reglist);
	$fet_reglist=mysql_fetch_array($res_reglist);
	$num_reglist=mysql_num_rows($res_reglist);

    if ($num_reglist > 0)
        {  
		do	{ ?>
    	<table border="0" cellpadding="2" cellspacing="0">
            <tr>
                <td colspan="6" class="tbl_data">
                    <span class="text_body_large"><?php echo $fet_reglist['usrdptname']." Department";?></span>
                </td>
            </tr>
            <?php
			//Get the departments and pending tasks looping them
			$sql="SELECT fname,lname,wftasks.usrac_idusrac
			FROM wftasks
			INNER JOIN usrrole ON wftasks.usrrole_idusrrole=usrrole.idusrrole
			INNER JOIN usrdpts ON usrrole.usrdpts_idusrdpts=usrdpts.idusrdpts
			INNER JOIN usrac ON wftasks.usrac_idusrac=usrac.idusrac 	
			INNER JOIN tktin ON wftasks.tktin_idtktin=tktin.idtktinPK
			WHERE tktin.usrteamzone_idusrteamzone=".$_SESSION['MVGitHub_userteamzoneid']."
			AND ( (wftasks.wftskstatustypes_idwftskstatustypes=0 AND wftasks.wftskstatusglobal_idwftskstatusglobal=1) OR (wftasks.wftskstatustypes_idwftskstatustypes=6 AND wftasks.wftskstatusglobal_idwftskstatusglobal=2))
			AND usrrole.usrdpts_idusrdpts=".$fet_reglist['usrdpts_idusrdpts']."
			GROUP BY wftasks.usrac_idusrac
			ORDER BY fname ASC";
			$res=mysql_query($sql);
			$fet=mysql_fetch_array($res);
			$num=mysql_num_rows($res);

			if ($num>0)
				{ //List pending tickets per individual	
				do	{ ?>
					<tr>
						<td class="tbl_data" style="background-color:#00FFFF;" colspan="5"><?php echo $fet['fname']." ".$fet['lname'];?></td>                            
					</tr>
                    <tr>
                      <td class="tbl_h">
                        Ticket No         	</td>
                      <td class="tbl_h">
                        Account No         	</td>
                      <td class="tbl_h">
                        Category 			</td>
                      <td class="tbl_h">
                        Date Received 		</td>
                       <td class="tbl_h">
                        Customer's Mobile 	</td>
                    </tr>  			
                    <?php 
					
					//Get tickets per user	
					$sql2="SELECT fname,lname,refnumber,waterac,date(timereported) as reportedon,senderphone,tktcategoryname
					FROM wftasks
					INNER JOIN usrrole ON wftasks.usrrole_idusrrole=usrrole.idusrrole
					INNER JOIN usrdpts ON usrrole.usrdpts_idusrdpts=usrdpts.idusrdpts
					INNER JOIN usrac ON wftasks.usrac_idusrac=usrac.idusrac 	
					INNER JOIN tktin ON wftasks.tktin_idtktin=tktin.idtktinPK
					INNER JOIN tktcategory ON tktin.tktcategory_idtktcategory=tktcategory.idtktcategory
					WHERE tktin.usrteamzone_idusrteamzone=".$_SESSION['MVGitHub_userteamzoneid']."
					AND ( (wftasks.wftskstatustypes_idwftskstatustypes=0 AND wftasks.wftskstatusglobal_idwftskstatusglobal=1) OR (wftasks.wftskstatustypes_idwftskstatustypes=6 AND wftasks.wftskstatusglobal_idwftskstatusglobal=2))
					AND usrrole.usrdpts_idusrdpts=".$fet_reglist['usrdpts_idusrdpts']."
					AND wftasks.usrac_idusrac=".$fet['usrac_idusrac']."
					GROUP BY wftasks.tktin_idtktin 
					ORDER BY fname ASC, reportedon ASC ";
					$res2=mysql_query($sql2);
					$fet2=mysql_fetch_array($res2);
					$num2=mysql_num_rows($res2);
					
					if($num2>0)
						{
						do	{ ?>
                            <tr>
  	                        	<td class="tbl_data"><?php echo $fet2['refnumber'];?></td>                            
                                <td class="tbl_data"><?php echo $fet2['waterac'];?></td>                            
                                <td class="tbl_data"><?php echo $fet2['tktcategoryname'];?></td>                            
                                <td class="tbl_data"><?php echo $fet2['reportedon'];?></td>                            
                                <td class="tbl_data"><?php echo $fet2['senderphone'];?></td>                            
                        	</tr>			
                        	<?php 
							} while($fet2=mysql_fetch_array($res2));
						} else {
						echo "<span style=\"font-family:arial;color:#ff0000\">No pending tasks for this user</span>";
						}	 
						
					} while ($fet=mysql_fetch_array($res)); 			
				} else {
				 echo "<span style=\"font-family:arial;color:#ff0000\">No pending tasks for this department</span>";
				}
			} while ($fet_reglist=mysql_fetch_array($res_reglist)); ?>  
   	 	</table>    
    	<?php    
        } else {       
         echo "<span style=\"font-family:arial;color:#ff0000\">No departments with tickets in this region</span>";
        } ?>
    </div>
</div>
</body>
</html>
