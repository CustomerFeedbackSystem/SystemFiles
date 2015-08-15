<?php
require_once('../../assets_backend/be_includes/config.php');

if (isset($_GET['display']))
	{
	$_SESSION['display_type']=trim($_GET['display']);
	} else {
	$_SESSION['display_type']="graph";
	}

if ((isset($_GET['timestart'])) && ($_GET['timestart']!=''))
	{
	$_SESSION['timestart']=preg_replace('/[^a-z\-_0-9\.:\/\s]/i','',mysql_escape_string(trim($_GET['timestart'])));
	} 
	
	if (!isset($_SESSION['timestart']))
	{
	$error_1 = "<div class=\"msg_warning\">Start Date is Missing</div>";
	}
	
if ((isset($_GET['timestop'])) && ($_GET['timestop']!=''))
	{
	$_SESSION['timestop']=preg_replace('/[^a-z\-_0-9\.:\/\s]/i','',mysql_escape_string(trim($_GET['timestop'])));
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
	header("Content-Disposition: attachment; filename=team_overall_tasks_region.xls");
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
    <div >
    <div class="text_body_vlarge"><?php echo $_SESSION['MVGitHub_acteam'];?></div>
    <div class="text_body_large"><?php echo $_SESSION['reportname'];?></div>
    </div>

    <div class="text_body_large">    	
    	<table cellpadding="0" cellspacing="0" width="100%">
        	<tr>
            	<td width="50%">
	        		Report Period<br /> <?php echo date("D, M d, Y",strtotime($_SESSION['timestart'])); ?> - <?php echo date("D, M d, Y",strtotime($_SESSION['timestop'])); ?>
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

    <table border="0" cellpadding="2" cellspacing="0">
	<?php
    //Get the departments and pending tasks looping them
    $sql_dpt="SELECT usrrole.usrdpts_idusrdpts,usrrole.usrteamzone_idusrteamzone,usrdptname FROM wftasks 
    INNER JOIN usrrole ON wftasks.usrrole_idusrrole=usrrole.idusrrole 
    INNER JOIN usrdpts ON usrrole.usrdpts_idusrdpts=usrdpts.idusrdpts
    WHERE usrrole.usrteamzone_idusrteamzone=".$_SESSION['MVGitHub_userteamzoneid']."
    GROUP BY usrrole.usrdpts_idusrdpts";
    $res_dpt=mysql_query($sql_dpt);
    $fet_dpt=mysql_fetch_array($res_dpt);
    $num_dpt=mysql_num_rows($res_dpt);

	if($num_dpt>0)
		{
		do	{ ?>
            <tr>
                <td colspan="4" class="tbl_data">
                    <span class="text_body_large"><?php echo $fet_dpt['usrdptname'];?></span>
                </td>
            </tr>

			<?php
            $sql="SELECT fname,lname,usrrolename,wftasks.usrrole_idusrrole,usrac_idusrac,count(idwftasks) as totrec FROM wftasks 
            INNER JOIN tktin ON wftasks.tktin_idtktin=tktin.idtktinPK
            INNER JOIN usrac on wftasks.usrac_idusrac=usrac.idusrac
            INNER JOIN usrrole on usrac.usrrole_idusrrole=usrrole.idusrrole
            WHERE usrrole.usrteamzone_idusrteamzone=".$_SESSION['MVGitHub_userteamzoneid']."
			AND usrrole.usrdpts_idusrdpts=".$fet_dpt['usrdpts_idusrdpts']."
            AND tktin.timereported>='".$_SESSION['timestart']."' 
            AND timereported<='".$_SESSION['timestop']."'
            GROUP BY wftasks.usrac_idusrac";
  
            $res=mysql_query($sql);
            $num=mysql_num_rows($res);
            $fet=mysql_fetch_array($res);
                
            if ($num > 0)
                {
            ?>
            <tr>
              <td class="tbl_h">
                User           </td>
              <td class="tbl_h">
                Designation            </td>
              <td class="tbl_h">
                Tasks Received            </td>
              <td class="tbl_h">
                Tasks Acted On            </td>
              <td class="tbl_h">
                Tasks Pending          </td>
          	</tr>
		   	<?php
                do { ?>
            <tr>
                <td class="tbl_data">
                  <strong><?php echo $fet['fname']." ".$fet['lname'];?></strong> </td>
                <td class="tbl_data">
                <?php echo $fet['usrrolename'];?>
                </td>
                <td class="tbl_data">
                <?php echo $fet['totrec'];?>            
                </td>
                <td class="tbl_data">
                <?php 
				$sql_act="SELECT fname,lname,usrrolename,wftasks.usrrole_idusrrole,count(idwftasks) as totact FROM wftasks 
				INNER JOIN tktin ON wftasks.tktin_idtktin=tktin.idtktinPK
				INNER JOIN usrac on wftasks.usrac_idusrac=usrac.idusrac
				INNER JOIN usrrole on usrac.usrrole_idusrrole=usrrole.idusrrole
				WHERE wftasks.usrac_idusrac=".$fet['usrac_idusrac']."
				AND usrrole.usrteamzone_idusrteamzone=".$_SESSION['MVGitHub_userteamzoneid']."
				AND ( (wftskstatustypes_idwftskstatustypes=2 AND wftskstatusglobal_idwftskstatusglobal=2) OR
				(wftskstatustypes_idwftskstatustypes=1 AND wftskstatusglobal_idwftskstatusglobal=3) OR
				(wftskstatustypes_idwftskstatustypes=4 AND wftskstatusglobal_idwftskstatusglobal=3) )
				AND tktin.timereported>='".$_SESSION['timestart']."' 
				AND timereported<='".$_SESSION['timestop']."' ";

				$res_act=mysql_query($sql_act);
				$num_act=mysql_num_rows($res_act);
				$fet_act=mysql_fetch_array($res_act);

				if($num_act>0)
					{
					echo $fet_act['totact'];
					} else {
					echo "0";
					}
					?>                        
                </td>
                <td class="tbl_data">
                <?php 
				$sql_pen="SELECT fname,lname,usrrolename,count(idwftasks) as totpen FROM wftasks 
				INNER JOIN tktin ON wftasks.tktin_idtktin=tktin.idtktinPK
				INNER JOIN usrac on wftasks.usrac_idusrac=usrac.idusrac
				INNER JOIN usrrole on usrac.usrrole_idusrrole=usrrole.idusrrole
				WHERE wftasks.usrac_idusrac=".$fet['usrac_idusrac']."
				AND usrrole.usrteamzone_idusrteamzone=".$_SESSION['MVGitHub_userteamzoneid']."
				AND ((wftskstatustypes_idwftskstatustypes=6 AND wftskstatusglobal_idwftskstatusglobal=2) OR
				(wftskstatustypes_idwftskstatustypes=0 AND wftskstatusglobal_idwftskstatusglobal=1) )
				AND tktin.timereported>='".$_SESSION['timestart']."' 
				AND timereported<='".$_SESSION['timestop']."' ";
			
				$res_pen=mysql_query($sql_pen);
				$num_pen=mysql_num_rows($res_pen);
				$fet_pen=mysql_fetch_array($res_pen);

				if($num_pen>0)
					{
					echo $fet_pen['totpen'];
					} else {
					echo "0";
					}
					?>                        

                </td>
            </tr>
			<?php
            } while ($fet=mysql_fetch_array($res));
            ?>
		<?php    
            } else {           
            echo "<span style=\"font-family:arial;color:#ff0000\">No Data to Generate Report</span>";
            }
		} while ($fet_dpt=mysql_fetch_array($res_dpt));
	} ?>
    </table>
    </div>
</div>
</body>
</html>
