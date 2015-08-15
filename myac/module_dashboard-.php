<?php
//require_once('../Connections/connSystem.php');
//mysql_select_db($database_connSystem, $connSystem);
require_once('../assets_backend/be_includes/check_login_easy.php');

$sql_new="SELECT count(*) as new FROM wftasks WHERE usrrole_idusrrole=".$_SESSION['MVGitHub_iduserrole']."  AND  ( (wftskstatusglobal_idwftskstatusglobal=1) OR (wftskstatusglobal_idwftskstatusglobal=2 AND wftskstatustypes_idwftskstatustypes=6) )";
$res_new=mysql_query($sql_new);
$fet_new=mysql_fetch_array($res_new);
?>
<div style="padding:0px 0px 20px 0px; background-color:#FFFFFF">
    <div>
    <table width="100%" border="0" cellpadding="0" cellspacing="0">
		<tr>
            <td width="50%" height="250" valign="top">
             <div style="padding:5px 0px 20px 0px">
                <div class="bg_section" style="color:#3366CC">
               <?php echo $lbl_welcome; ?>, <?php echo $_SESSION['MVGitHub_usrtitle']." ".$_SESSION['MVGitHub_usrlname'];?>
                </div>
                <div style="padding:20px 5px 10px 5px">
                <table border="0" cellpadding="0" cellspacing="0" width="100%">
           	  <tr <?php 
// technocurve arc 3 php mv block2/3 start
echo " style=\"background-color:$mocolor\" onMouseOver=\"this.style.backgroundColor='$mocolor3'\" onMouseOut=\"this.style.backgroundColor='$mocolor'\"";
// technocurve arc 3 php mv block2/3 end
?>>
                    	<td height="40" class="tbl_data" style="color:<?php echo $mocolor1;?>">
                        <?php
						if ($fet_new['new']>0)
							{
							$style=" style=\"font-weight:bold\" ";
							} else {
							$style="  ";
							}
						
                        echo "<a href=\"index.php?mod=2&submod=0&ua=view&view=2&uction=view_mod&pageNum_rs_list=0&parentviewtab=1\" >";
						?>
                       <span <?php echo $style;?> > <img src="../assets_backend/images/icon_mytasks.gif" border="0" align="absmiddle" /> You currently have   
                        <?php
						if ($fet_new['new']>0)
							{
							echo "<span style=\"background-color:#cc0000; padding:1px 3px; color:#ffffff; \">".$fet_new['new']."</span> Task";
							if ($fet_new['new']>1) { echo "s"; }
							echo "</a>";
							} else {
							echo "<span style=\"background-color:#006699; padding:2px 5px; color:#ffffff\">No New Tasks</a>";
							}
						?>  </span>                      </td>
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

// technocurve arc 3 php mv block3/3 start
if ($mocolor == $mocolor1) {
	$mocolor = $mocolor2;
} else {
	$mocolor = $mocolor1;
}
// technocurve arc 3 php mv block3/3 end
?>                     
                </table>
               </div>
			</div>
          </td>
          <td width="50%" valign="top">
            <div style="padding:5px 0px 20px 0px">
                <div class="bg_section">
               <?php echo $lbl_myoverall_graph;?>
                </div>
                <div>
				<?php
                echo "<iframe src=\"flot/reports/series-pie/myworkload.php\" width=\"500\" height=\"270\" scrolling=\"no\" frameborder=\"0\"></iframe> ";
                ?>
                </div>
            </div>
            </td>
        </tr>
        <tr>
            <td width="50%" valign="top"  height="250" >
            <div >
                <div class="bg_section">
                <?php echo $lbl_overall_graph;?>
                </div>
                <div>
                <?php
				//determine the 
				if ($is_perm_global==1) //global permission within the user organization
					{
					echo "<iframe src=\"flot/reports/categories/all_complaints_global.php\" width=\"550\" height=\"450\" scrolling=\"no\" frameborder=\"0\"></iframe> ";
					} else { 
					echo "<iframe src=\"flot/reports/categories/all_complaints.php\" width=\"550\" height=\"450\" scrolling=\"no\" frameborder=\"0\"></iframe> ";
					}
				?>
                </div>
            </div>
          </td>
            <td width="50%" valign="top">
           <div >
                <div class="bg_section">
               <?php echo $lbl_mapview_dash;?>
                </div>
                <div style="padding:10px 0px 10px 0px">          
					<iframe src="module_dashboard_map.php" width="550" height="350" scrolling="no" frameborder="0"></iframe>   
                </div>
            </div>
            </td>
        </tr>
    </table>
    </div>
</div>