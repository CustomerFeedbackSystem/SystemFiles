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
	$_SESSION['timestart']=substr($thirty_days_ago,0,10);
	} else {
	$_SESSION['timestart']=mysql_escape_string(trim($_GET['timestart']));
	}

if (!isset($_GET['timestop']))
	{
	$_SESSION['timestop']=substr($timenowis,0,10);
	} else {
	$_SESSION['timestop']=mysql_escape_string(trim($_GET['timestop']));
	}

if (isset($_GET['category']))
	{
	$_SESSION['spec_cat']=mysql_escape_string(trim($_GET['category']));
	}

//get the categories
if (isset($_SESSION['spec_cat']))
	{
	if ($_SESSION['spec_cat']>0)
		{
		$sql_cats="SELECT idtktcategory,tktcategoryname FROM tktcategory WHERE idtktcategory=".$_SESSION['spec_cat']." ";
		} else {
		$sql_cats="SELECT idtktcategory,tktcategoryname FROM tktcategory ORDER BY tktcategoryname ASC";
		}
	$res_cats=mysql_query($sql_cats);
	$fet_cats=mysql_fetch_array($res_cats);
	$num_cats=mysql_num_rows($res_cats);
	}
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
    <form method="get" action="" >
    <table border="0" width="100%" cellpadding="2" cellspacing="0">
    	<tr>
        	<td width="40%" style="padding:0px 10px" >
            <?php
			echo "<span class=\"text_body_mod\">".date("D, M d, Y",strtotime($_SESSION['timestart']))."</span>&nbsp;&nbsp;<span class=\"text_small\">To</span>&nbsp;&nbsp;<span class=\"text_body_mod\">".date("D, M d, Y",strtotime($_SESSION['timestop']))."</span>";
			?>
            - <span class="text_body_large">by Department / Individual</span>
            </span>
            
            </td>
		  <td align="right" width="60%" >
          	<span class="hidden" >
   		 	 <table border="0" cellpadding="0" cellspacing="0" style="padding:0px 10px; background-color:#e7e7e7">
            	<tr>
                	<td class="text_body_small"> Change Report Time Frame: </td>
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
		<span class="text_body_small" style="padding:0px 10px 0px 0px"><a href="tasks_management_region_1.php?timestart=<?php echo $_SESSION['timestart'];?>&amp;timestop=<?php echo $_SESSION['timestop'];?>"><img src="btns/bycat_on.jpg" border="0" align="absmiddle" /></a></span>
        <span class="text_body_small" style="padding:0px 10px 0px 10px"><img src="btns/bydpt.jpg" border="0" align="absmiddle"/></span>
	</div>
    <div class="text_body_mod">
    <form method="get" action="">
    Select Category
    <select name="category">
            <option value="">--Specify Category--</option>
            <option value="0" disabled="disabled">&lt;All Categories&gt;</option>
<?php            
    $sql_catslist="SELECT idtktcategory,tktcategoryname FROM tktcategory ORDER BY tktcategoryname ASC";
	$res_catslist=mysql_query($sql_catslist);
	$fet_catslist=mysql_fetch_array($res_catslist);
	$num_catslist=mysql_num_rows($res_catslist);
		do {
		echo "<option ";
			if ($_SESSION['spec_cat']==$fet_catslist['idtktcategory'])
				{
				echo " selected=\"selected\" ";
				}
		echo " value=\"".$fet_catslist['idtktcategory']."\">".$fet_catslist['tktcategoryname']."</option>";
		} while ($fet_catslist=mysql_fetch_array($res_catslist));
?>	
            </select>
            <input type="submit" class="text_body_small" value="List Data" />
    </form>
    </div>    
<?php
if ( (!isset($_SESSION['spec_cat'])) || ($_SESSION['spec_cat']==0) )
	{
	echo "<div class=\"msg_warning\" style=\"margin:10px; padding:10px\">-- Please Specify the Category --</div>";
	exit;
	}
		
	if (($num_cats>0) && (isset($_SESSION['spec_cat'])) && ($_SESSION['spec_cat']>0) )
		{
	?>
    <div style="padding:10px">
    	<table border="0" width="100%" cellpadding="3" cellspacing="0">
        <?php
		do {
		?>
       
			<tr>
				<td colspan="6" class="border_top_thick" style="padding:15px 0px 0px 0px">
                <span class="text_body_vlarge"><?php echo $fet_cats['tktcategoryname'];?></span>
                </td>
            </tr>
            <?php
			//get the relevant departments and loop them
			$sql_dpts="SELECT idusrdpts,usrdptname FROM wftasks
			INNER JOIN usrrole ON wftasks.usrrole_idusrrole=usrrole.idusrrole
			INNER JOIN usrdpts ON usrrole.usrdpts_idusrdpts=usrdpts.idusrdpts
			INNER JOIN tktin ON wftasks.tktin_idtktin=tktin.idtktinPK
			WHERE
			wftasks.timeinactual>='".$_SESSION['timestart']."' AND wftasks.timeinactual<='".$_SESSION['timestop']."' 
			AND tktin.tktcategory_idtktcategory=".$fet_cats['idtktcategory']." 
			AND tktin.usrteamzone_idusrteamzone=".$_SESSION['MVGitHub_userteamzoneid']."
			GROUP BY usrdpts.idusrdpts";
			$res_dpts=mysql_query($sql_dpts);
			$fet_dpts=mysql_fetch_array($res_dpts);
			$num_dpts=mysql_num_rows($res_dpts);
			//echo $sql_dpts."<br><br>";
			if ($num_dpts>0)
				{
				do {
				?>
				<tr>
					<td colspan="7" class="tbl_data">
					<span class="text_body_large"><?php echo $fet_dpts['usrdptname'];?></span>
					</td>
				</tr>
                <tr>
            	<td width="20%" class="tbl_h">Name</td>
            	<td width="20%" class="tbl_h">
                Role</td>
                <td width="10%" class="tbl_h">Total Received</td>
                <td width="10%" class="tbl_h">New <small>(without activity)</small></td>
                <td width="10%" class="tbl_h">On Desk <small>/ In Progress </small></td>
                <td width="10%" class="tbl_h">Passed ON</td>
                 <td width="10%" class="tbl_h">Closed Tickets</td>
          </tr>
				<?php
				//query the team with the data here ==category + usrrole
			/*	$sql_report="SELECT DISTINCT usrrole.usrrolename,usrrole.idusrrole as taskrole,usrac.fname,usrac.lname,
				(SELECT DISTINCT count(*) FROM tktin INNER JOIN wftasks ON tktin.idtktinPK=wftasks.tktin_idtktin WHERE tktin.usrteamzone_idusrteamzone=".$_SESSION['MVGitHub_userteamzoneid']." AND tktin.tktcategory_idtktcategory=".$fet_cats['idtktcategory']." AND wftasks.timeinactual>='".$_SESSION['timestart']."' AND wftasks.timeinactual<='".$_SESSION['timestop']."' AND wftasks.usrrole_idusrrole=taskrole AND wftasks.wftasks_idwftasks=0 GROUP BY wftasks.usrrole_idusrrole) as totalin,
				(SELECT count(*) FROM tktin INNER JOIN wftasks ON tktin.idtktinPK=wftasks.tktin_idtktin WHERE tktin.usrteamzone_idusrteamzone=".$_SESSION['MVGitHub_userteamzoneid']." AND tktin.tktcategory_idtktcategory=".$fet_cats['idtktcategory']." AND wftasks.timeinactual>='".$_SESSION['timestart']."' AND wftasks.timeinactual<='".$_SESSION['timestop']."' AND wftasks.usrrole_idusrrole=taskrole AND wftskstatustypes_idwftskstatustypes=0 AND wftskstatusglobal_idwftskstatusglobal=1 GROUP BY wftasks.usrrole_idusrrole) as new,
				(SELECT count(*) FROM tktin INNER JOIN wftasks ON tktin.idtktinPK=wftasks.tktin_idtktin WHERE wftasks.wftasks_idwftasks=0 AND tktin.usrteamzone_idusrteamzone=".$_SESSION['MVGitHub_userteamzoneid']." AND tktin.tktstatus_idtktstatus=2 AND tktin.tktcategory_idtktcategory=".$fet_cats['idtktcategory']." AND wftasks.timeinactual>='".$_SESSION['timestart']."' AND wftasks.timeinactual<='".$_SESSION['timestop']."' AND wftasks.usrrole_idusrrole=taskrole AND wftskstatustypes_idwftskstatustypes=6 AND wftskstatusglobal_idwftskstatusglobal=2 GROUP BY tktin.tktstatus_idtktstatus) as in_progress,
				(SELECT count(*) FROM tktin INNER JOIN wftasks ON tktin.idtktinPK=wftasks.tktin_idtktin WHERE wftasks.wftasks_idwftasks=0 AND tktin.usrteamzone_idusrteamzone=".$_SESSION['MVGitHub_userteamzoneid']." AND tktin.tktstatus_idtktstatus=4 AND tktin.tktcategory_idtktcategory=".$fet_cats['idtktcategory']." AND wftasks.timeinactual>='".$_SESSION['timestart']."' AND wftasks.timeinactual<='".$_SESSION['timestop']."' AND wftasks.usrrole_idusrrole=taskrole AND wftskstatustypes_idwftskstatustypes=1 AND wftskstatusglobal_idwftskstatusglobal=3  GROUP BY tktin.tktstatus_idtktstatus) as closed,
				(SELECT count(*) FROM tktin INNER JOIN wftasks ON tktin.idtktinPK=wftasks.tktin_idtktin WHERE wftasks.wftasks_idwftasks=0 AND tktin.usrteamzone_idusrteamzone=".$_SESSION['MVGitHub_userteamzoneid']." AND tktin.tktstatus_idtktstatus=5 AND tktin.tktcategory_idtktcategory=".$fet_cats['idtktcategory']." AND wftasks.timeinactual>='".$_SESSION['timestart']."' AND wftasks.timeinactual<='".$_SESSION['timestop']."' AND wftasks.usrrole_idusrrole=taskrole AND wftskstatustypes_idwftskstatustypes=4 AND wftskstatusglobal_idwftskstatusglobal=2  GROUP BY tktin.tktstatus_idtktstatus) as invalid
				FROM wftasks
				INNER JOIN tktin ON wftasks.tktin_idtktin=tktin.idtktinPK
				INNER JOIN usrrole ON wftasks.usrrole_idusrrole=usrrole.idusrrole
				INNER JOIN usrac ON usrrole.idusrrole=usrac.usrrole_idusrrole
				WHERE tktin.usrteamzone_idusrteamzone=".$_SESSION['MVGitHub_userteamzoneid']." 
				AND wftasks.timeinactual>='".$_SESSION['timestart']."' 
				AND wftasks.timeinactual<='".$_SESSION['timestop']."' 
				AND tktin.tktcategory_idtktcategory=".$fet_cats['idtktcategory']."
				AND wftasks.wftasks_idwftasks=0
				AND usrrole.usrdpts_idusrdpts=".$fet_dpts['idusrdpts']." ";
				*/
				$sql_report="SELECT DISTINCT usrrole.usrrolename,usrrole.idusrrole as taskrole,usrac.fname,usrac.lname
				FROM wftasks
				INNER JOIN tktin ON wftasks.tktin_idtktin=tktin.idtktinPK
				INNER JOIN usrrole ON wftasks.usrrole_idusrrole=usrrole.idusrrole
				INNER JOIN usrac ON usrrole.idusrrole=usrac.usrrole_idusrrole
				WHERE tktin.usrteamzone_idusrteamzone=".$_SESSION['MVGitHub_userteamzoneid']." 
				AND wftasks.timeinactual>='".$_SESSION['timestart']."' 
				AND wftasks.timeinactual<='".$_SESSION['timestop']."' 
				AND tktin.tktcategory_idtktcategory=".$fet_cats['idtktcategory']."
				AND usrrole.usrdpts_idusrdpts=".$fet_dpts['idusrdpts']." ";
				$res_report=mysql_query($sql_report);
				$fet_report=mysql_fetch_array($res_report);
				$num_report=mysql_num_rows($res_report);
				
				//echo $sql_report."<br><br>";
				
				do {
				?>
				<tr <?php 
	// technocurve arc 3 php mv block2/3 start
	echo " style=\"background-color:$mocolor\" onMouseOver=\"this.style.backgroundColor='$mocolor3'\" onMouseOut=\"this.style.backgroundColor='$mocolor'\"";
	// technocurve arc 3 php mv block2/3 end
	?>>
					<td class="tbl_data" >
					<?php echo $fet_report['fname'];?> <?php echo $fet_report['lname'];?>
					</td>
					<td class="tbl_data">
					<?php echo $fet_report['usrrolename'];?>
					</td>
					<td class="tbl_data">
					<?php 
					$sql_totalin="SELECT DISTINCT count(*) as totalin FROM wftasks INNER JOIN tktin ON wftasks.tktin_idtktin=tktin.idtktinPK WHERE tktin.usrteamzone_idusrteamzone=".$_SESSION['MVGitHub_userteamzoneid']." AND tktin.tktcategory_idtktcategory=".$fet_cats['idtktcategory']." AND wftasks.timeinactual>='".$_SESSION['timestart']."' AND wftasks.timeinactual<='".$_SESSION['timestop']."' AND wftasks.usrrole_idusrrole=".$fet_report['taskrole']." GROUP BY wftasks.usrrole_idusrrole";
					$res_totalin=mysql_query($sql_totalin);
					$fet_totalin=mysql_fetch_array($res_totalin);
//					echo $sql_totalin;
					echo $fet_totalin['totalin'];?>
					</td>
                    <td class="tbl_data">
					<?php 
					$sql_justin="SELECT DISTINCT count(*) as new FROM wftasks INNER JOIN tktin ON wftasks.tktin_idtktin=tktin.idtktinPK WHERE tktin.usrteamzone_idusrteamzone=".$_SESSION['MVGitHub_userteamzoneid']." AND tktin.tktcategory_idtktcategory=".$fet_cats['idtktcategory']." AND wftasks.timeinactual>='".$_SESSION['timestart']."' AND wftasks.timeinactual<='".$_SESSION['timestop']."' AND wftasks.usrrole_idusrrole=".$fet_report['taskrole']." AND wftasks.wftskstatustypes_idwftskstatustypes=0 AND wftasks.wftskstatusglobal_idwftskstatusglobal=1 GROUP BY wftasks.usrrole_idusrrole";
					$res_justin=mysql_query($sql_justin);
					$fet_justin=mysql_fetch_array($res_justin);
//					echo $sql_totalin;
					echo $fet_justin['new'];?>
					</td>
					<td class="tbl_data">
					<?php 
					$sql_inprog="SELECT DISTINCT count(*) as progress FROM wftasks INNER JOIN tktin ON wftasks.tktin_idtktin=tktin.idtktinPK WHERE tktin.usrteamzone_idusrteamzone=".$_SESSION['MVGitHub_userteamzoneid']." AND tktin.tktcategory_idtktcategory=".$fet_cats['idtktcategory']." AND wftasks.timeinactual>='".$_SESSION['timestart']."' AND wftasks.timeinactual<='".$_SESSION['timestop']."' AND wftasks.usrrole_idusrrole=".$fet_report['taskrole']." AND wftasks.wftskstatustypes_idwftskstatustypes=6 AND wftasks.wftskstatusglobal_idwftskstatusglobal=2 GROUP BY wftasks.usrrole_idusrrole";
					$res_inprog=mysql_query($sql_inprog);
					$fet_inprog=mysql_fetch_array($res_inprog);
//					echo $sql_totalin;
					echo $fet_inprog['progress'];?>
					</td>
					<td class="tbl_data">
					<?php 
					$sql_pass="SELECT DISTINCT count(*) as pass FROM wftasks INNER JOIN tktin ON wftasks.tktin_idtktin=tktin.idtktinPK WHERE tktin.usrteamzone_idusrteamzone=".$_SESSION['MVGitHub_userteamzoneid']." AND tktin.tktcategory_idtktcategory=".$fet_cats['idtktcategory']." AND wftasks.timeinactual>='".$_SESSION['timestart']."' AND wftasks.timeinactual<='".$_SESSION['timestop']."' AND wftasks.usrrole_idusrrole=".$fet_report['taskrole']." AND wftasks.wftskstatustypes_idwftskstatustypes=2 AND wftasks.wftskstatusglobal_idwftskstatusglobal=2 GROUP BY wftasks.usrrole_idusrrole";
					$res_pass=mysql_query($sql_pass);
					$fet_pass=mysql_fetch_array($res_pass);
//					echo $sql_totalin;
					echo $fet_pass['pass'];?>
					</td>
					<td class="tbl_data">
					<?php 
					$sql_closed="SELECT DISTINCT count(*) as closed FROM wftasks INNER JOIN tktin ON wftasks.tktin_idtktin=tktin.idtktinPK WHERE tktin.usrteamzone_idusrteamzone=".$_SESSION['MVGitHub_userteamzoneid']." AND tktin.tktcategory_idtktcategory=".$fet_cats['idtktcategory']." AND wftasks.timeinactual>='".$_SESSION['timestart']."' AND wftasks.timeinactual<='".$_SESSION['timestop']."' AND wftasks.usrrole_idusrrole=".$fet_report['taskrole']." AND wftasks.wftskstatustypes_idwftskstatustypes=1 AND wftasks.wftskstatusglobal_idwftskstatusglobal=3 GROUP BY wftasks.usrrole_idusrrole";
					$res_closed=mysql_query($sql_closed);
					$fet_closed=mysql_fetch_array($res_closed);
//					echo $sql_totalin;
					echo $fet_closed['closed'];?>
					</td>
				</tr>
				<?php
					} while ($fet_report=mysql_fetch_array($res_report));
				
				// technocurve arc 3 php mv block3/3 start
				if ($mocolor == $mocolor1) {
					$mocolor = $mocolor2;
				} else {
					$mocolor = $mocolor1;
				}
				// technocurve arc 3 php mv block3/3 end
				
				} while ($fet_dpts=mysql_fetch_array($res_dpts));
				
				
				
				} else {
				
				echo "<tr><td><div class=\"text_body_small\">-- No Data --</div></td></tr>";
				
				} //
				?>

            <?php 
		} while ($fet_cats=mysql_fetch_array($res_cats));
	
			
			?>
      </table>
  </div>
  <div class="text_data">
  NOTE: The above numbers represent the Tasks initiated by the Customer Tickets
  </div>
    <?php } else { ?>
    <div class="text_body_small">
    -- No Data--
    </div>
    <?php } ?>
</div>
</body>
</html>
