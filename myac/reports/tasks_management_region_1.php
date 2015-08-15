<?php
require_once('../../assets_backend/be_includes/config.php');
require_once("../../assets_backend/be_includes/check_login_easy.php");



if (isset($_GET['title']))
	{
	$_SESSION['report_title']=trim($_GET['title']);
	}

//get the reporting dates
if (!isset($_GET['timestart']))
	{
	if (!isset($_SESSION['timestart']))
		{
		$_SESSION['timestart']=substr($thirty_days_ago,0,10);
		}
	} else {
	$_SESSION['timestart']=mysql_escape_string(trim($_GET['timestart']));
	}

if (!isset($_GET['timestop']))
	{
	if (!isset($_SESSION['timestop']))
		{
		$_SESSION['timestop']=substr($timenowis,0,10);
		}
	} else {
	$_SESSION['timestop']=mysql_escape_string(trim($_GET['timestop']));
	}


//get the categories
$sql_cats="SELECT idtktcategory,tktcategoryname FROM tktcategory ORDER BY tktcategoryname ASC";
$res_cats=mysql_query($sql_cats);
$fet_cats=mysql_fetch_array($res_cats);
$num_cats=mysql_num_rows($res_cats);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $_SESSION['report_title'];?></title>
<link href="../css_report_print.css" rel="stylesheet" type="text/css" media="print" />
<link href="../css_report.css" rel="stylesheet" type="text/css" />
<script language="javascript" type="text/javascript" src="../../scripts/datetimepicker.js"></script>
</head>
<body>
<div>
	<div class="hidden">
    	<table border="0" width="100%" cellpadding="0" cellspacing="0">
        	<tr>
            	<td>
				<a href="#" class="text_body_mod" onclick="window.print()"><img border="0" align="absmiddle" src="../../assets_backend/btns/print_small.gif" /> Print this Page</a>
                </td>
                <td align="right" >
				<a href="#" class="text_body_mod" onClick="parent.tb_remove();">Close Window [X]</a>
                </td>
			</tr>
		</table>
    </div>
    <div style="padding:10px">
    <span class="text_body_vlarge" ><?php echo $_SESSION['report_title'];?></span>
    <span class="text_body_large"> -  <?php echo $pagetitle;?> Report </span>
    </div>
    <div>
    <?php
//	echo "<div class=\"msg_warning\">Quick Reports Returning 17th Sep 2013<br>Sorry for inconvenience</div>";
//	exit;
	?>
    <form method="get" action="" >
    <table border="0" width="100%">
    	<tr>
        	<td width="39%" style="padding:0px 10px" >
            <?php
			echo "<span class=\"text_body_mod\">".date("D, M d, Y",strtotime($_SESSION['timestart']))."</span>&nbsp;&nbsp;<span class=\"text_small\">To</span>&nbsp;&nbsp;<span class=\"text_body_mod\">".date("D, M d, Y",strtotime($_SESSION['timestop']))."</span>";
			?>
            - <span class="text_body_large">by Category</span>
            </span></td>
		  <td width="61%" align="right" >
          	<span class="hidden" >
   		  <table border="0" cellpadding="0" cellspacing="0" style="padding:0px 10px; background-color:#e7e7e7">
            	<tr>
                	<td class="text_body_small">
                    Change Report Time Frame: 
                    </td>
               	  <td class="text_body_small"><input name="timestart" type="text" id="timestart" value="<?php if (isset($_SESSION['timestart'])) { echo $_SESSION['timestart']; } ?>" readonly="readonly" onClick="displayDatePicker('timestart');"> 
                        <img src="../../assets_backend/btns/cal.gif" alt="Pick a date" width="20" height="20" border="0" align="absmiddle" onClick="displayDatePicker('timestart');">                    </td>
                    <td class="text_body_small">
                     -&nbsp;&amp;&nbsp;-
                      <input name="timestop" type="text"  id="timestop" value="<?php if (isset($_SESSION['timestop'])) { echo $_SESSION['timestop']; } ?>" onClick="displayDatePicker('timestop');" readonly="readonly"> 
                        <img src="../../assets_backend/btns/cal.gif" alt="Pick a date" width="20" height="20" border="0" align="absmiddle" onClick="displayDatePicker('timestop');">                    </td>
                  <td style="padding:0px 5px">
                    <input type="submit" class="text_body_small" value="Refresh Data" />
                    </td>
                </tr>
            </table>
         
            </span>
			</td>
      </tr>
	</table>
    </form>
	</div>
    <div class="hidden" style="padding:2px 10px">
		<span class="text_body_small" style="padding:0px 10px 0px 0px"><img src="btns/bycat.jpg" border="0" align="absmiddle" /></span>
        <span class="text_body_small" style="padding:0px 10px 0px 10px"><a href="tasks_management_region_2.php?timestart=<?php echo $_SESSION['timestart'];?>&amp;timestop=<?php echo $_SESSION['timestop'];?>" ><img src="btns/bydpt_on.jpg" border="0" align="absmiddle"/></a></span>        </div>
<?php
	
	if ($num_cats>0)
		{
	?>
    <div style="padding:10px">
    	<table border="0" width="100%" cellpadding="3" cellspacing="0">
   	  <tr>
            	<td class="tbl_h">
                CATEGORY
                </td>
            	<td class="tbl_h">
                New (without activity)</td>
                <td class="tbl_h">
                In Progress
                </td>
                <td class="tbl_h">
                Closed
                </td>
                <td class="tbl_h">
                Invalidated
                </td>
                 <td class="tbl_h">
                TOTAL
                </td>
            </tr>
            <?php
			$grand_total="";
			do {
			//get the stats for this region only
			$sql_report="SELECT DISTINCT
			(SELECT count(*) FROM tktin INNER JOIN wftasks ON tktin.idtktinPK=wftasks.tktin_idtktin WHERE tktin.usrteamzone_idusrteamzone=".$_SESSION['MVGitHub_userteamzoneid']." AND tktin.tktstatus_idtktstatus=1 AND tktcategory_idtktcategory=".$fet_cats['idtktcategory']." AND tktin.timereported>='".$_SESSION['timestart']."' AND wftasks.wftasks_idwftasks=0 AND tktin.timereported<='".$_SESSION['timestop']."' GROUP BY tktin.tktstatus_idtktstatus) as new,
			(SELECT count(*) FROM tktin INNER JOIN wftasks ON tktin.idtktinPK=wftasks.tktin_idtktin WHERE tktin.usrteamzone_idusrteamzone=".$_SESSION['MVGitHub_userteamzoneid']." AND tktin.tktstatus_idtktstatus=2 AND tktcategory_idtktcategory=".$fet_cats['idtktcategory']." AND timereported>='".$_SESSION['timestart']."' AND wftasks.wftasks_idwftasks=0 AND timereported<='".$_SESSION['timestop']."' GROUP BY tktin.tktstatus_idtktstatus) as in_progress,
			(SELECT count(*) FROM tktin INNER JOIN wftasks ON tktin.idtktinPK=wftasks.tktin_idtktin WHERE tktin.usrteamzone_idusrteamzone=".$_SESSION['MVGitHub_userteamzoneid']." AND tktin.tktstatus_idtktstatus=4 AND tktcategory_idtktcategory=".$fet_cats['idtktcategory']." AND timereported>='".$_SESSION['timestart']."' AND wftasks.wftasks_idwftasks=0 AND timereported<='".$_SESSION['timestop']."' GROUP BY tktin.tktstatus_idtktstatus) as closed,
			(SELECT count(*) FROM tktin INNER JOIN wftasks ON tktin.idtktinPK=wftasks.tktin_idtktin WHERE tktin.usrteamzone_idusrteamzone=".$_SESSION['MVGitHub_userteamzoneid']." AND tktin.tktstatus_idtktstatus=5 AND tktcategory_idtktcategory=".$fet_cats['idtktcategory']." AND timereported>='".$_SESSION['timestart']."' AND wftasks.wftasks_idwftasks=0 AND timereported<='".$_SESSION['timestop']."' GROUP BY tktin.tktstatus_idtktstatus) as invalid
			FROM tktin
			WHERE tktin.usrteamzone_idusrteamzone=".$_SESSION['MVGitHub_userteamzoneid']." 
			AND tktin.timereported>='".$_SESSION['timestart']."' AND tktin.timereported<='".$_SESSION['timestop']."' ";
			$res_report=mysql_query($sql_report);
			$fet_report=mysql_fetch_array($res_report);
			$num_report=mysql_num_rows($res_report);
			//echo $sql_report."<br><br>";
			?>
            <tr <?php 
// technocurve arc 3 php mv block2/3 start
echo " style=\"background-color:$mocolor\" onMouseOver=\"this.style.backgroundColor='$mocolor3'\" onMouseOut=\"this.style.backgroundColor='$mocolor'\"";
// technocurve arc 3 php mv block2/3 end
?>>
            	<td class="tbl_data">
                <strong><a href="tasks_management_region_2.php?timestart=<?php echo $_SESSION['timestart'];?>&amp;timestop=<?php echo $_SESSION['timestop'];?>&amp;category=<?php echo $fet_cats['idtktcategory'];?>"><?php echo $fet_cats['tktcategoryname'];?></a></strong>
                </td>
            	<td class="tbl_data">
                <strong><?php
				if ($fet_report['new'] > 0)
					{
				?>
             <!--   <a href="tasks_management_region_2.php?status=new&val=1"> -->
				<?php
				echo $fet_report['new'];
				?>
             <!--   </a> -->
                <?php
				} else { 
				echo "---";
				}
				?></strong>
                </td>
                <td class="tbl_data">
                <strong><?php
				if ($fet_report['in_progress'] > 0)
					{
				?>
             <!--   <a href="tasks_management_region_2.php?status=in_progress&val=2">-->
				<?php
				echo $fet_report['in_progress'];
				?>
              <!--  </a> -->
                <?php
				} else { 
				echo "---";
				}
				?></strong>
                </td>
                <td class="tbl_data">
                 <strong><?php
				if ($fet_report['closed'] > 0)
					{
				?>
           <!--     <a href="tasks_management_region_2.php?status=closed&val=4"> -->
				<?php
				echo $fet_report['closed'];
				?>
           <!--    </a> -->
                <?php
				} else { 
				echo "---";
				}
				?> </strong>         
                </td>
                <td class="tbl_data">
                <strong><?php
				if ($fet_report['invalid'] > 0)
					{
				?>
           <!--     <a href="tasks_management_region_2.php?status=invalid&val=5">-->
				<?php
				echo $fet_report['invalid'];
				?>
           <!--     </a> -->
                <?php
				} else { 
				echo "---";
				}
				?></strong>
                </td>
                <td class="tbl_data">
                <strong>
                <?php
				$subtotal=($fet_report['new']+$fet_report['in_progress']+$fet_report['closed']+$fet_report['invalid']);
				echo $subtotal;
				?> 
                </strong>
                </td>
            </tr>
            <?php 
			//grand total calculation
			$grand_total=($grand_total+$subtotal);
			
			// technocurve arc 3 php mv block3/3 start
			if ($mocolor == $mocolor1) {
				$mocolor = $mocolor2;
			} else {
				$mocolor = $mocolor1;
			}
			// technocurve arc 3 php mv block3/3 end

			} while ($fet_cats=mysql_fetch_array($res_cats));
			?>
            <tr>
            	<td class="text_data"></td>
                <td class="text_data"></td>
                <td class="text_data"></td>
                <td class="text_data"></td>
                <td class="text_data"></td>
                <td class="text_data">
                <?php
				echo $grand_total;
				?>
                </td>
            </tr>
      </table>
  </div>
  <div class="text_data">
  NOTE: Figures may slightly vary by 0.1% due to unforwarded SMS, USSD and WEB Tickets
  </div>
    <?php } else { ?>
    <div class="msg_warning">
    No Data Found
    </div>
    <?php } ?>
</div>
</body>
</html>
