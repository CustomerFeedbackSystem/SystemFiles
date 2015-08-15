<?php
//require_once('../assets_backend/be_includes/config.php');
//require_once('../Connections/connSystem.php');

mysql_select_db($database_connSystem, $connSystem);
require_once('../assets_backend/be_includes/check_login_easy.php');

if (!isset($_SESSION['parenttabview']))
	{
	$_SESSION['parenttabview']=1;
	}

if (isset($_GET['parentviewtab']))
	{
		if ( ($_GET['parentviewtab']>1) && ($_GET['parentviewtab']<4) )
			{
			$_SESSION['parenttabview']=mysql_real_escape_string(trim($_GET['parentviewtab']));
			} else {
			$_SESSION['parenttabview']=1;
			}
	}
	
	
if ($_SESSION['parenttabview']==1)
	{
//////////////////////////////////////////////////VIEW 1

//regional filters
if (isset($_GET['teamview'])) 
	{
	//clean it
	$teamview=preg_replace('/[^a-z\-_0-9\.:\/\s]/i','',mysql_escape_string(trim($_GET['teamview'])));
	
	$_SESSION['teamview']=$teamview;
	if ($teamview!="all")
		{
		$filter_qry_region=" AND usrteamzone_idusrteamzone=".$teamview." " ;
		} else {
		$filter_qry_region="";
		}
		
	} else {
	$_SESSION['teamview']=$_SESSION['MVGitHub_userteamzoneid'];//my region
	}
?>

<div style="padding:0px 5px 80px 0px">
<table border="0" cellpadding="0" cellspacing="0" width="100%">
    	<tr>
        	<td width="100%" class="bg_section">
			<?php echo $fet_heading['modulename']; ?> &raquo; <?php echo $fet_heading['submodule']; ?>
            </td>
		</tr>
        <tr>
        	<td style="padding:5px 0px 0px 0px">
           
            <div class="title_header_blue">
			Consumer Engagement Guideline Tickets Escalation
            </div>
            <div class="title_header_blue">
			<small>All Regions</small>
            </div>
            <div style="padding:20px 0px">
          <table border="0" width="100%" cellpadding="2" cellspacing="0">
            	
				<tr>
                	<td valign="top"  style="padding:0px 0px 10px 0px">
                    </td>
                    </tr>
                        
                        <tr>
                        <td class="table_header">
                        
                        </td>
                        <?php
                        $days = 10;
                         for ($i=$days; $i > 0; $i--)  
                            {
                        ?>
                            <td class="table_header" style="font-size:11px">
                            <div>
							<?php
							if ( $i > 9)
								{
								echo "10+";
								} else {
                            	echo $i;
								}
                            ?> Day<?php if ($i>1) { echo "s";}?> Overdue
                            </div>
                            <?php 
							if ($i==10)
								{
								echo "<div style=\"color:#ff0000;font-weight:bold;font-size:10px\">On Wasreb Watchlist</div>";
								}
							?>
                            </td>
                            <?php
                            }
                         ?>
                         	<td class="table_header" style="font-size:11px">
                            Sub Total
                            </td>
                        </tr>
                        <?php
						//loop through the categories here
						$sql_category="SELECT idtktcategory,tktcategoryname FROM tktcategory ";
						$res_category=mysql_query($sql_category);
						$fet_category=mysql_fetch_array($res_category);
											
						do {
						?>
                        <tr <?php 
// technocurve arc 3 php mv block2/3 start
echo " style=\"background-color:$mocolor\" onMouseOver=\"this.style.backgroundColor='$mocolor3'\" onMouseOut=\"this.style.backgroundColor='$mocolor'\"";
// technocurve arc 3 php mv block2/3 end
?>>
                        <td class="tbl_data">
                        <strong><?php echo $fet_category['tktcategoryname'];?></strong>
                        </td>
                        <?php
                        $day_val = 10;
						$tkts_total=0;
						
                         for ($k=$day_val; $k > 0; $k--)  
                            {
                            ?>
                          <td valign="top" class="tbl_data">
                                <?php 
								//per category
								//determine the value calculation to use dependin on whether it's the last leg  - 10 days or not
								if ($k > 9)
									{
									$res_data=mysql_query("SELECT count(*) as tkts FROM tktin 
									WHERE 
									timeclosed='0000-00-00 00:00:00' 
									AND
									datediff('".$timenowis."',date(timedeadline))>9 
									AND 
									tktin.tktcategory_idtktcategory=".$fet_category['idtktcategory']." 
									AND tktstatus_idtktstatus < 4 AND tktin.timereported>='2014-01-01 00:00:00'");
									
									} else {
									
									$res_data=mysql_query("SELECT count(*) as tkts FROM tktin 
									WHERE 
									timeclosed='0000-00-00 00:00:00' 
									AND
									datediff('".$timenowis."',timedeadline)=".$k."
									AND
									tktin.tktcategory_idtktcategory=".$fet_category['idtktcategory']." 
									AND tktstatus_idtktstatus < 4 AND tktin.timereported>='2014-01-01 00:00:00'");
									}
								$fet_data=mysql_fetch_array($res_data);
							
                               if ($fet_data['tkts']>0) 
							   		{ 
							   		//if ($k>9)
									//	{ 
										echo "<a href=\"".$_SERVER['PHP_SELF']."?parentviewtab=2&amp;category=".$fet_category['idtktcategory']."&odue=".$k."\" style=\"color:#ff0000;font-weight:bold\"";
										echo " >";
							   			echo number_format($fet_data['tkts'],0); 
										echo "</a>";
									//	} else { 
									//	echo "..."; 
									//	}
									} else {
									echo "..."; 
									}
							   //echo rand(100,400);
							   ?>               
                          </td>
                            <?php
							$tkts_total=$tkts_total+$fet_data['tkts'];
                           
						    } //end loop
                        ?>
                        	<td class="tbl_sh">
                            <?php echo number_format($tkts_total,0);?>
                            </td>
                        </tr> 
                        <?php 
						// technocurve arc 3 php mv block3/3 start
						if ($mocolor == $mocolor1) {
							$mocolor = $mocolor2;
						} else {
							$mocolor = $mocolor1;
						}
						// technocurve arc 3 php mv block3/3 end
						?>
						<?php
						
						} while ($fet_category=mysql_fetch_array($res_category));
						
						
						?>
                   <tr>
                   		<td class="tbl_sh" align="right">
                        Total
                        </td>
                        <?php
						
						$subtotal_row=0;
						for ($j=$day_val; $j > 0; $j--)  
                            {
							$total_row=0;
							//
							if ($j > 9)
									{
									$res_datarow=mysql_query("SELECT count(*) as tkts FROM tktin 
									WHERE 
									timeclosed='0000-00-00 00:00:00' 
									AND
									datediff('".$timenowis."',timedeadline)>9 
									AND 
									tktstatus_idtktstatus < 4 AND tktin.timereported>='2014-01-01 00:00:00'");
									
									} else {
									
									$res_datarow=mysql_query("SELECT count(*) as tkts FROM tktin 
									WHERE 
									timeclosed='0000-00-00 00:00:00' 
									AND
									datediff('".$timenowis."',timedeadline)=".$j."
									AND tktstatus_idtktstatus < 4 AND tktin.timereported>='2014-01-01 00:00:00'");
									}
								$fet_datarow=mysql_fetch_array($res_datarow);
								$total_row=$total_row+$fet_datarow['tkts']; //total per column
								$subtotal_row=$subtotal_row+$fet_datarow['tkts']; //total end
						?>
                        <td class="tbl_sh">
                        <?php echo number_format($total_row,0);?>
                        </td>
                        <?php
							} //end loop j
						?>
                        <td class="tbl_sh">
                        <?php echo number_format($subtotal_row,0);?>
                        </td>
                   </tr>
			  </table>
            </div>
            </td>
        </tr>
</table>       
</div>
<?php
}
///////////////////////////////////////////////////////END OF VIEW ONE ///////////////////////////////////////////////


if ($_SESSION['parenttabview']==2)
	{
	//the variable for the days is all one needs here as the categories are all listed anyway so no need to sort them out
	if (isset($_GET['odue'])) { $_SESSION['odue']=mysql_real_escape_string(trim($_GET['odue'])); }
	if (isset($_GET['category'])) { $_SESSION['cat']=mysql_real_escape_string(trim($_GET['category'])); }
	
	$res_cat=mysql_query("SELECT tktcategoryname FROM tktcategory WHERE idtktcategory=".$_SESSION['cat']."");
	$fet_cat=mysql_fetch_array($res_cat);
	?>
     <div class="title_header_blue">
			Consumer Engagement Guideline Tickets Escalation
            </div>
            <div class="title_header_blue">
			<small><?php echo $fet_cat['tktcategoryname'];?> Tickets <?php if ($_SESSION['odue'] >=10 ) { echo "Over ".$_SESSION['odue']; } else { echo $_SESSION['odue']; }?> days overdue</small>
            </div>
            <div>
	<table border="0" cellpadding="2" cellspacing="0" width="100%">
                        	<tr>
                            	<td height="30" class="table_header"><a href="">&laquo; Back to main</a></td>
                  <?php
								//get all the regions on a loop to be used on the query above
								$sql_reg="SELECT idusrteamzone,userteamzonename FROM usrteamzone WHERE usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']." ORDER BY userteamzonename ASC";
								$res_reg=mysql_query($sql_reg);
								$fet_reg=mysql_fetch_array($res_reg);
								
								do {
								?>
                                <td class="table_header">
                                <?php
								echo str_replace('Region','',$fet_reg['userteamzonename']);
								?>                              </td>
                          <?php
									} while ($fet_reg=mysql_fetch_array($res_reg))
								?>
                                <td width="80" class="table_header">Sub-Total</td>
                          </tr>
                            <?php
							//get the list of categories for this
							if ($_SESSION['odue'] > 9)
								{
								$sql_cats="SELECT DISTINCT tktcategoryname,idtktcategory FROM tktin 
								INNER JOIN tktcategory ON tktin.tktcategory_idtktcategory=tktcategory.idtktcategory 
								WHERE 
								timeclosed='0000-00-00 00:00:00' 
								AND
								datediff('".$timenowis."',timedeadline)>9";
								} else {
								$sql_cats="SELECT DISTINCT tktcategoryname,idtktcategory FROM tktin 
								INNER JOIN tktcategory ON tktin.tktcategory_idtktcategory=tktcategory.idtktcategory 
								WHERE 
								timeclosed='0000-00-00 00:00:00' 
								AND
								datediff('".$timenowis."',timedeadline)=".$_SESSION['odue']." AND tktin.timereported>='2014-01-01 00:00:00'";
								}
							$res_cats=mysql_query($sql_cats);
							$num_cats=mysql_num_rows($res_cats);
							$fet_cats=mysql_fetch_array($res_cats);
							
							if ($num_cats > 0)
								{
								//capture the total tickets for this column
								
								do {
							?>
                            <tr <?php 
if ($fet_cats['idtktcategory']==$_SESSION['cat'])
	{ 
	echo "style=\"background-color:#ffff99\"";
	} else {
	// technocurve arc 3 php mv block2/3 start
	echo " style=\"background-color:$mocolor\" onMouseOver=\"this.style.backgroundColor='$mocolor3'\" onMouseOut=\"this.style.backgroundColor='$mocolor'\"";
	// technocurve arc 3 php mv block2/3 end
	}
?>>
                           	  <td class="tbl_data" style="border-right:1px solid #f4f4f4">
                               <strong> <?php echo $fet_cats['tktcategoryname'];?></strong>
                               </td>
                                <?php
								//loop the regions again
								$sql_reglbl="SELECT idusrteamzone,userteamzonename FROM usrteamzone WHERE usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']." ORDER BY userteamzonename ASC";
								$res_reglbl=mysql_query($sql_reglbl);
								$fet_reglbl=mysql_fetch_array($res_reglbl);
								
									
									//create the tds values
									$total_tkts=0;
									do {
										if ($_SESSION['odue'] > 9)
											{
									//loop through the region data below for the 6 or so regions if regions is set to all
											$res_regiondata=mysql_query("SELECT count(*) as tkts FROM tktin 
											WHERE 
											timeclosed='0000-00-00 00:00:00' 
											AND
											datediff('".$timenowis."',timedeadline)>9 
											AND
											tktin.tktcategory_idtktcategory=".$fet_cats['idtktcategory']."
											AND 
											tktin.usrteamzone_idusrteamzone=".$fet_reglbl['idusrteamzone']." AND tktin.timereported>='2014-01-01 00:00:00'");
										$tes="SELECT count(*) as tkts FROM tktin 
											WHERE 
											timeclosed='0000-00-00 00:00:00' 
											AND
											datediff('".$timenowis."',timedeadline)>9 
											AND
											tktin.tktcategory_idtktcategory=".$fet_cats['idtktcategory']."
											AND 
											tktin.usrteamzone_idusrteamzone=".$fet_reglbl['idusrteamzone']." AND tktin.timereported>='2014-01-01 00:00:00'";
											} else {
											
											$res_regiondata=mysql_query("SELECT count(*) as tkts FROM tktin 
											WHERE 
											timeclosed='0000-00-00 00:00:00' 
											AND
											datediff('".$timenowis."',timedeadline)=".$_SESSION['odue']."
											AND
											tktin.tktcategory_idtktcategory=".$fet_cats['idtktcategory']."
											AND 
											tktin.usrteamzone_idusrteamzone=".$fet_reglbl['idusrteamzone']." AND tktin.timereported>='2014-01-01 00:00:00'");
								$tes="SELECT count(*) as tkts FROM tktin 
											WHERE 
											timeclosed='0000-00-00 00:00:00' 
											AND
											datediff('".$timenowis."',timedeadline)=".$_SESSION['odue']."
											AND
											tktin.tktcategory_idtktcategory=".$fet_cats['idtktcategory']."
											AND 
											tktin.usrteamzone_idusrteamzone=".$fet_reglbl['idusrteamzone']." AND tktin.timereported>='2014-01-01 00:00:00'";
											}
											
											$fet_regiondata=mysql_fetch_array($res_regiondata);
								
									echo "<td class=\"tbl_data\"  style=\"color:#FF0000;font-weight:bold;border-right:1px solid #f4f4f4\" width=\"90\">";
//echo "<small>".$tes."</small>";
									if ($fet_regiondata['tkts']>0)
										{
										echo number_format($fet_regiondata['tkts'],0);
										echo "&nbsp;&nbsp;<a href=\"pop_ceg.php?tabview=1&amp;region=".$fet_reglbl['idusrteamzone']."&cat=".$fet_cats['idtktcategory']."&keepThis=true&TB_iframe=true&height=".$_SESSION['tb_height']."&width=".$_SESSION['tb_width']."&inlineId=hiddenModalContent&modal=true\" class=\"thickbox\"><img src=\"../assets_backend/images/icon_magnify.png\" border=\"0\" align=\"absmiddle\" /></a>";
										} else {
										echo "-";
										}
									echo "</td>";
									$total_tkts=$total_tkts+$fet_regiondata['tkts'];
									} while ($fet_reglbl=mysql_fetch_array($res_reglbl));
									
									echo "<td class=\"tbl_data\" style=\"background-color:#f4f4f4;font-weight:bold\">";
									echo number_format($total_tkts,0);
									echo "</td>";
								?>
                            </tr>
                           
                            <?php 
// technocurve arc 3 php mv block3/3 start
if ($mocolor == $mocolor1) {
	$mocolor = $mocolor2;
} else {
	$mocolor = $mocolor1;
}
// technocurve arc 3 php mv block3/3 end
?>
                            <?php	
														
									} while ($fet_cats=mysql_fetch_array($res_cats));
								
								}
							?>
                        </table>
                        </div>
<?php
	}
?>