<?php
//require_once('../assets_backend/be_includes/config.php');
//require_once('../Connections/connSystem.php');

mysql_select_db($database_connSystem, $connSystem);
require_once('../assets_backend/be_includes/check_login_easy.php');

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
        	<td style="padding:15px 0px 0px 0px">
            <div>
            <?php if (isset($msg)) { ?>
            <script language="javascript">
			alert ('<?php echo $msg;?>');
			</script>
            <?php } ?>
            </div>
            <div>
			<table border="0" cellpadding="0" cellspacing="0">
            <tr>
                <td style="padding:5px" class="text_small">
                <?php
                if ((isset($is_perm_global)) && ($is_perm_global==1) )
                    {
                    //get all the regions on a loop to be used on the query above
                    $sql_regions="SELECT idusrteamzone,userteamzonename FROM usrteamzone WHERE usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']."";
                    $res_regions=mysql_query($sql_regions);
                    $fet_regions=mysql_fetch_array($res_regions);
                    
                    if (mysql_num_rows($res_regions) > 0)
                        {
                    ?>
                    <span>
                <a href="<?php echo $_SERVER['PHP_SELF'];?>?teamview=all" class="tab<?php if ($_SESSION['teamview']=="all") { echo "_on"; }?>">All Regions</a>
                </span>
                    <?php
                        do {
                ?>
            <!--    <span >
                <a class="tab<?php if ($_SESSION['teamview']==$fet_regions['idusrteamzone']) { echo "_on"; }?>" href="<?php echo $_SERVER['PHP_SELF'];?>?teamview=<?php echo $fet_regions['idusrteamzone'];?>"><?php echo str_replace('Region','',$fet_regions['userteamzonename']);?></a>
                </span>       	
                <?php 
                        } while ($fet_regions=mysql_fetch_array($res_regions));
                    }
                } else { ?>
                <span style="padding:0px 10px">
                <a href="<?php echo $_SERVER['PHP_SELF'];?>?teamview=<?php echo $_SESSION['MVGitHub_userteamzoneid'];?>"><?php echo $_SESSION['MVGitHub_userteamzone'];?></a>
                </span>
                <?php
                }
                ?>
              -->
                </td>
            </tr>
			</table>
            </div>
            <div style="padding:20px 0px">
          <table border="0" width="100%" cellpadding="2" cellspacing="0">
            	<tr>
                	<td class="table_header">
                   <span style="color:#FF0000; font-weight:bold"> On Wasreb's Watchlist  </span>
                   ( These tickets in <strong>red </strong>are over 10 days beyond the Deadline )                    </td>
            </tr>
                <tr>
                	<td valign="top"  style="padding:0px 0px 10px 0px">
                    <!-- start stats-->
                    	<table border="0" cellpadding="2" cellspacing="0" width="100%">
                        	<tr>
                            	<td height="30" class="tbl_h2" width="250">&nbsp;</td>
                      <?php
								//get all the regions on a loop to be used on the query above
								$sql_reg="SELECT idusrteamzone,userteamzonename FROM usrteamzone WHERE usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']." ORDER BY userteamzonename ASC";
								$res_reg=mysql_query($sql_reg);
								$fet_reg=mysql_fetch_array($res_reg);
								
								do {
								?>
                                <td class="tbl_h2" width="80">
                                <?php
								echo str_replace('Region','',$fet_reg['userteamzonename']);
								?>
                                </td>
                                <?php
									} while ($fet_reg=mysql_fetch_array($res_reg))
								?>
                                <td width="80" class="tbl_h2"></td>
                          </tr>
                            <?php
							//get the list of categories for this
							$sql_cats="SELECT DISTINCT tktcategoryname,idtktcategory FROM tktin 
							INNER JOIN tktcategory ON tktin.tktcategory_idtktcategory=tktcategory.idtktcategory 
							WHERE 
							timeclosed='0000-00-00 00:00:00' 
							AND
							datediff(now(),timedeadline)>10";
							$res_cats=mysql_query($sql_cats);
							$num_cats=mysql_num_rows($res_cats);
							$fet_cats=mysql_fetch_array($res_cats);
							
							if ($num_cats > 0)
								{
								//capture the total tickets for this column
								
								do {
							?>
                            <tr <?php 
// technocurve arc 3 php mv block2/3 start
echo " style=\"background-color:$mocolor\" onMouseOver=\"this.style.backgroundColor='$mocolor3'\" onMouseOut=\"this.style.backgroundColor='$mocolor'\"";
// technocurve arc 3 php mv block2/3 end
?>>
                           	  <td class="tbl_data" width="250" style="color:#FF0000">
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
									
									//loop through the region data below for the 6 or so regions if regions is set to all
									$res_regiondata=mysql_query("SELECT count(*) as tkts FROM tktin 
									WHERE 
									timeclosed='0000-00-00 00:00:00' 
									AND
									datediff(now(),timedeadline)>10 
									AND
									tktin.tktcategory_idtktcategory=".$fet_cats['idtktcategory']."
									AND 
									tktin.usrteamzone_idusrteamzone=".$fet_reglbl['idusrteamzone']."");
									$fet_regiondata=mysql_fetch_array($res_regiondata);
								
									echo "<td class=\"tbl_data\">";
									if ($fet_regiondata['tkts']>0)
										{
										echo number_format($fet_regiondata['tkts'],0);
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
								echo "
								<tr>
                            	<td class=\"tbl_sh\">
                                
                                </td>";
								
								//get the regions again with their totals
								$sql_regftr="SELECT idusrteamzone,userteamzonename FROM usrteamzone WHERE usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']." ORDER BY userteamzonename ASC";
								$res_regftr=mysql_query($sql_regftr);
								$fet_regftr=mysql_fetch_array($res_regftr);
								
								do {
									$sql_regtotal="SELECT count(*) as ttl FROM tktin 
									INNER JOIN usrteamzone ON tktin.usrteamzone_idusrteamzone=usrteamzone.idusrteamzone
									WHERE tktin.usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']." 
									AND
									tktin.timeclosed='0000-00-00 00:00:00' 
									AND
									datediff(now(),tktin.timedeadline)>10 
									AND
									usrteamzone_idusrteamzone=".$fet_regftr['idusrteamzone']."
									ORDER BY usrteamzone.userteamzonename ASC";
										
									$res_regtotal=mysql_query($sql_regtotal);
									$fet_regtotal=mysql_fetch_array($res_regtotal);
								
									echo "<td class=\"tbl_data\" style=\"font-weight:bold\">";
									echo number_format($fet_regtotal['ttl'],0);
									echo "</td>";
									} while ($fet_regftr=mysql_fetch_array($res_regftr));
								echo "<td></td>";
                            	echo "</tr>";
								}
							?>
                        </table>
                    <!-- end stats-->
                    </td>
                </tr>
                <?php
				$days = 10;
				 for ($i=$days; $i > 0; $i--)  
				 	{
				?>
                <tr>
                	<td class="table_header">
                    <?php
					echo $i;
					?> Day<?php if ($i>1) { echo "s";}?> beyond the Deadline
                    </td>
                </tr>
                <tr>
                	<td valign="top" style="padding:0px 0px 10px 0px">
                  		
                        <table border="0" cellpadding="2" cellspacing="0" width="100%">
                        	<tr>
                            	<td height="30" class="tbl_h2" width="250">&nbsp;</td>
                      <?php
								//get all the regions on a loop to be used on the query above
								$sql_reg="SELECT idusrteamzone,userteamzonename FROM usrteamzone WHERE usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']." ORDER BY userteamzonename ASC";
								$res_reg=mysql_query($sql_reg);
								$fet_reg=mysql_fetch_array($res_reg);
								
								do {
								?>
                                <td class="tbl_h2" width="80">
                                <?php
								echo str_replace('Region','',$fet_reg['userteamzonename']);
								?>
                                </td>
                                <?php
									} while ($fet_reg=mysql_fetch_array($res_reg))
								?>
                                <td width="80" class="tbl_h2"></td>
                          </tr>
                            <?php
							//get the list of categories for this
							$sql_cats="SELECT DISTINCT tktcategoryname,idtktcategory FROM tktin 
							INNER JOIN tktcategory ON tktin.tktcategory_idtktcategory=tktcategory.idtktcategory 
							WHERE 
							timeclosed='0000-00-00 00:00:00' 
							AND
							datediff(now(),timedeadline)=".$i."";
							$res_cats=mysql_query($sql_cats);
							$num_cats=mysql_num_rows($res_cats);
							$fet_cats=mysql_fetch_array($res_cats);
							
							if ($num_cats > 0)
								{
								do {
							?>
                            <tr <?php 
// technocurve arc 3 php mv block2/3 start
echo " style=\"background-color:$mocolor\" onMouseOver=\"this.style.backgroundColor='$mocolor3'\" onMouseOut=\"this.style.backgroundColor='$mocolor'\"";
// technocurve arc 3 php mv block2/3 end
?>>
                            	<td class="tbl_data" width="250">
                               <strong> <?php echo $fet_cats['tktcategoryname'];?></strong>
                                </td>
                                <?php
								//loop the regions again
								$sql_reglbl="SELECT idusrteamzone,userteamzonename FROM usrteamzone WHERE usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']." ORDER BY userteamzonename ASC";
								$res_reglbl=mysql_query($sql_reglbl);
								$fet_reglbl=mysql_fetch_array($res_reglbl);
								
									//create the tds
									do {
									
									//loop through the region data below for the 6 or so regions if regions is set to all
									$res_regiondata=mysql_query("SELECT count(*) as tkts FROM tktin 
									WHERE 
									timeclosed='0000-00-00 00:00:00' 
									AND
									datediff(now(),timedeadline)=".$i." 
									AND
									tktin.tktcategory_idtktcategory=".$fet_cats['idtktcategory']."
									AND 
									tktin.usrteamzone_idusrteamzone=".$fet_reglbl['idusrteamzone']."");
									$fet_regiondata=mysql_fetch_array($res_regiondata);
								
									$total_tkts=0;
									echo "<td class=\"tbl_data\">";
									if ($fet_regiondata['tkts']>0)
										{
										echo number_format($fet_regiondata['tkts'],0);
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
									
									echo "
								<tr>
                            	<td class=\"tbl_sh\">
                                
                                </td>";
								//get the regions again with their totals
								$sql_regftr="SELECT idusrteamzone,userteamzonename FROM usrteamzone WHERE usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']." ORDER BY userteamzonename ASC";
								$res_regftr=mysql_query($sql_regftr);
								$fet_regftr=mysql_fetch_array($res_regftr);
								
								do {
									$sql_regtotal="SELECT count(*) as ttl FROM tktin 
									INNER JOIN usrteamzone ON tktin.usrteamzone_idusrteamzone=usrteamzone.idusrteamzone
									WHERE tktin.usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']." 
									AND
									tktin.timeclosed='0000-00-00 00:00:00' 
									AND
									datediff(now(),tktin.timedeadline)=".$i." 
									AND
									usrteamzone_idusrteamzone=".$fet_regftr['idusrteamzone']."
									ORDER BY usrteamzone.userteamzonename ASC";
										
									$res_regtotal=mysql_query($sql_regtotal);
									$fet_regtotal=mysql_fetch_array($res_regtotal);
								
									echo "<td class=\"tbl_data\" style=\"font-weight:bold\">";
									echo number_format($fet_regtotal['ttl'],0);
									echo "</td>";
									} while ($fet_regftr=mysql_fetch_array($res_regftr));
								echo "<td></td>";
                            	echo "</tr>";
								}
								
							?>
                        </table>
                   
                    </td>
                </tr>
               <?php
			   } //end loop days
			   ?>
               
               
            </table>
            </div>
            </td>
        </tr>
</table>        
</div>