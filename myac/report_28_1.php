<?php
require_once('../assets_backend/be_includes/check_login_easy.php');	

//decide what to display
if (isset($_GET['display']))
	{
	$_SESSION['display_type']=trim($_GET['display']);
	} else {
	$_SESSION['display_type']="graph";
	}

//generate filters for this report here
//1 - ZONES
if ($is_perm_global==1) //if perm global, then give this reports options below
	{
	if ( (isset($_GET['tktzone'])) && ($_GET['tktzone'] > 0) )
		{
		$tktzone=preg_replace('/[^a-z\-_0-9\.:\/\s]/i','',mysql_escape_string(trim($_GET['tktzone'])));
		
		$_SESSION['tktzoneval'] = $tktzone;
		$_SESSION['tktzone']=" AND tktin.usrteamzone_idusrteamzone=".$_SESSION['tktzoneval']." ";
		
		
		} else if ((isset($_GET['tktzone'])) && ($_GET['tktzone'] ==0)) {
		
		$_SESSION['tktzoneval'] = 0;
		$_SESSION['tktzone']="";
		
		} else {
		$_SESSION['tktzoneval'] = 0;
		$_SESSION['tktzone']="";
		}
	} else { //if not global then this is it
	$_SESSION['tktzone']=" AND tktin.usrteamzone_idusrteamzone=".$_SESSION['MVGitHub_userteamzoneid']." ";
	}
	
//2 - STATUSES
if ( (isset($_GET['tktstatus'])) && ($_GET['tktstatus'] > 0) )
	{
	$tktstatus=preg_replace('/[^a-z\-_0-9\.:\/\s]/i','',mysql_escape_string(trim($_GET['tktstatus'])));
	
	$_SESSION['tktstatusval'] = $tktstatus;
	$_SESSION['tktstatus']=" AND tktin.tktstatus_idtktstatus=".$_SESSION['tktstatusval']." ";
	
	
	} else if ((isset($_GET['tktstatus'])) && ($_GET['tktstatus'] ==0)) {
	
	$_SESSION['tktstatusval'] = 0;
	$_SESSION['tktstatus']="";
	
	} else {
	$_SESSION['tktstatusval'] = 0;
	$_SESSION['tktstatus']="";
	}	
	
//3 - CHANNEL
if ( (isset($_GET['tktchannel'])) && ($_GET['tktchannel'] > 0) )
	{
	$tktchannel=preg_replace('/[^a-z\-_0-9\.:\/\s]/i','',mysql_escape_string(trim($_GET['tktchannel'])));
	
	$_SESSION['tktchannelval'] = $tktchannel;
	$_SESSION['tktchannel']=" AND tktin.tktchannel_idtktchannel=".$_SESSION['tktchannelval']." ";
	
	
	} else if ((isset($_GET['tktchannel'])) && ($_GET['tktchannel'] ==0)) {
	
	$_SESSION['tktchannelval'] = 0;
	$_SESSION['tktchannel']="";
	
	} else {
	$_SESSION['tktchannelval'] = 0;
	$_SESSION['tktchannel']="";
	}		

?>
<div>
	<div class="hidden" style="padding:0px 0px 5px 0px">
    <table border="0" width="100%" cellpadding="0" cellspacing="0">
    	<tr>
        	<td>
            <span class="text_small" >
            <?php
            if ($_SESSION['display_type']=="tabular") { ?>
            <a href="<?php echo $_SERVER['PHP_SELF'];?>?display=graph&amp;timestop=<?php echo $_SESSION['timestop'];?>&amp;timestart=<?php echo $_SESSION['timestart'];?>&amp;pagetitle=<?php echo $_SESSION['report_title'];?>&amp;reportid=<?php echo $_SESSION['reportid'];?>">Swith to Graph</a>
            <?php } ?>
            <?php
            if ($_SESSION['display_type']=="graph") { ?>
            <a href="<?php echo $_SERVER['PHP_SELF'];?>?display=tabular&amp;timestop=<?php echo $_SESSION['timestop'];?>&amp;timestart=<?php echo $_SESSION['timestart'];?>&amp;pagetitle=<?php echo $_SESSION['report_title'];?>&amp;reportid=<?php echo $_SESSION['reportid'];?>">Switch to Table</a>
            <?php } ?>
            </span>
            </td>
            <td align="right" class="text_small">
            	<table border="0" cellpadding="0" cellspacing="0">
                	<tr>
                    	<td>
                          <a href="" style="padding:0px 50px 0px 0px">Print</a>
                        </td>
                        <td>
                        <form method="post" action="report_xcelxport.php" name="xcelxport">
                        <input type="hidden" value=" Tickets" name="report_name" />
                        <input type="hidden" name="report_print" value="<table border='0' cellpadding='2' cellspacing='0'>
    	<tr>
        	<td class='tbl_h'>
            Category
            </td>
            <td class='tbl_h'>
            No. of Tickets
            </td>
        </tr>
        <?php
		$sql_data_print="SELECT SUM(tktin.tktcategory_idtktcategory) as tkts, tktcategoryname FROM tktin
INNER JOIN tktcategory ON tktin.tktcategory_idtktcategory=tktcategory.idtktcategory 
WHERE idtktin>0 ".$_SESSION['tktzone']." ".$_SESSION['tktstatus']." ".$_SESSION['tktchannel']." GROUP BY tktin.tktcategory_idtktcategory";
$res_data_print=mysql_query($sql_data_print);
$num_data_print=mysql_num_rows($res_data_print);
$fet_data_print=mysql_fetch_array($res_data_print);

do {
		?>
        <tr>
        	<td class='tbl_data'>
            <?php echo $fet_data_print['tktcategoryname'];?>
            </td>
            <td class='tbl_data'>
            <?php echo $fet_data_print['tkts'];?>
            </td>
        </tr>
<?php
	} while ($fet_data_print=mysql_fetch_array($res_data_print));
?>        
    </table>" />
    <a href="#" class="text_small" onclick="document.forms['xcelxport'].submit()">Export to Excel</a>
    
                        </form>
                        </td>
                    </tr>
                </table>
            </td>
		</tr>
	</table>
    </div>
     <div style="background-color:#E7E7E7; padding:5px; margin:0px 0px 15px 0px">
    <form method="get" action="">
    <table border="0" cellpadding="0" cellspacing="0">
    	<tr>
        	<td class="text_small">
            Zone / Region
            </td>
        	<td class="text_small">
            Status
            </td>
        	<td class="text_small">
            Channel
            </td>
            <td></td>
        </tr>
    	<tr>
        	<td>
            <?php
			if ($is_perm_global==1) //if perm global, then give this reports options below
				{
			?>
            <select class="text_small" name="tktzone">
            <option value="0" <?php if ((isset($_SESSION['tktzoneval'])) && ($_SESSION['tktzoneval']==0)) { echo "selected=\"selected\""; } ?>>All Zones / Regions</option>
            <?php
			$sql_zones="SELECT idusrteamzone,userteamzonename FROM usrteamzone WHERE usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']." ORDER BY userteamzonename ASC";
			$res_zones=mysql_query($sql_zones);
			$fet_zones=mysql_fetch_array($res_zones);
			$num_zones=mysql_num_rows($res_zones);
			if ($num_zones > 0)
				{
				do {
				echo "<option ";
				if ((isset($_SESSION['tktzoneval'])) && ($_SESSION['tktzoneval']==$fet_zones['idusrteamzone'])) { echo "selected=\"selected\""; }
				echo " value=\"".$fet_zones['idusrteamzone']."\">".$fet_zones['userteamzonename']."</option>";
				} while ($fet_zones=mysql_fetch_array($res_zones));
				}
			?>
            </select>
            <?php } else { ?>
           <span class="text_small"> Permission Required </span>
            <?php } ?>
            </td>
            <td>
            <select class="text_small" name="tktstatus">
            <option value="0" <?php if ( (isset($_SESSION['tktstatusval'])) && ($_SESSION['tktstatusval']==0)) { echo "selected=\"selected\""; } ?>>All Statuses</option>
            <?php
            $sql_stat="SELECT idtktstatus,tktstatusname FROM tktstatus";
			$res_stat=mysql_query($sql_stat);
			$fet_stat=mysql_fetch_array($res_stat);
			do {
			echo "<option ";
			if ( (isset($_SESSION['tktstatusval'])) && ($_SESSION['tktstatusval']==$fet_stat['idtktstatus'])) { echo "selected=\"selected\""; }
			echo " value=\"".$fet_stat['idtktstatus']."\">".$fet_stat['tktstatusname']."</option>";
			} while ($fet_stat=mysql_fetch_array($res_stat));
			?>
            </select>
            </td>
            <td>
            <select class="text_small" name="tktchannel">
            <option value="0">All Channels</option>
            <?php
			$sql_chn="SELECT idtktchannel,tktchannelname FROM tktchannel";
			$res_chn=mysql_query($sql_chn);
			$fet_chn=mysql_fetch_array($res_chn);
			do {
			echo "<option ";
			if ( (isset($_SESSION['tktchannelval'])) && ($_SESSION['tktchannelval']==$fet_chn['idtktchannel'])) { echo "selected=\"selected\""; }
			echo " value=\"".$fet_chn['idtktchannel']."\">".$fet_chn['tktchannelname']."</option>";
			} while ($fet_chn=mysql_fetch_array($res_chn));
			?>
            </select>
            </td>
            <td>
            <div class="hidden">
            <input class="text_small" type="submit" value="Refresh!" />
            </div>
            </td>
        </tr>
    </table>
    </form>
    </div>
    <div class="text_small" style="padding:0px 0px 10px 0px">
    *Includes all Categories
    </div>    
    <div>
    <?php
	if ($_SESSION['display_type']=="graph")
		{
		include_once 'php-ofc-library/open_flash_chart_object.php';
		open_flash_chart_object( '100%', '100%', 'report_28_-1_data.php' );
		}
	?>
    </div>
    <?php
	if ($_SESSION['display_type']=="tabular")
		{
	?>
    <div>
    <div>
    <table border="0" cellpadding="2" cellspacing="0">
    	<tr>
        	<td class="tbl_h">
            Category
            </td>
            <td class="tbl_h">
            No. of Tickets
            </td>
        </tr>
        <?php
		$sql_data="SELECT SUM(tktin.tktcategory_idtktcategory) as tkts, tktcategoryname FROM tktin
INNER JOIN tktcategory ON tktin.tktcategory_idtktcategory=tktcategory.idtktcategory 
WHERE idtktin>0 ".$_SESSION['tktzone']." ".$_SESSION['tktstatus']." ".$_SESSION['tktchannel']." GROUP BY tktin.tktcategory_idtktcategory";
$res_data=mysql_query($sql_data);
$num_data=mysql_num_rows($res_data);
$fet_data=mysql_fetch_array($res_data);

do {
		?>
        <tr>
        	<td class="tbl_data">
            <?php echo $fet_data['tktcategoryname'];?>
            </td>
            <td class="tbl_data">
            <?php echo $fet_data['tkts'];?>
            </td>
        </tr>
<?php
	} while ($fet_data=mysql_fetch_array($res_data));
?>        
    </table>
    </div>
    </div>
    <?php 
	} 
	?>
</div>