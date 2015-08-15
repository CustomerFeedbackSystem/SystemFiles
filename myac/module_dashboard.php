<?php
//require_once('../Connections/connSystem.php');
//mysql_select_db($database_connSystem, $connSystem);
require_once('../assets_backend/be_includes/check_login_easy.php');
include_once 'php-ofc-library/open_flash_chart_object.php';
?>
<div style="padding:0px 0px 20px 0px">
    <div>
    <table width="100%" border="0" cellpadding="0" cellspacing="0">
		<tr>
            <td width="50%" height="250" valign="top">
            <div style="padding:5px 10px 20px 10px">
            <div>
            <table border="0" cellpadding="0" cellspacing="0" width="100%">
    	<tr>
        	<td  >
   
    		</td>
            <td align="right" class="text_small" style="position:absolute; right:10px">
<!--            <a href="#" onclick="tb_open_new('pop_dashboardsettings.php?title=Customize_Dashboard&amp;&amp;tabview=1&amp;keepThis=true&amp;TB_iframe=true&amp;height=500&amp;width=800')">Customize My Dashboard</a>-->
            </td>
		</tr>
	</table>
            </div>
		  	<div style="padding:10px 10px 10px 0px">
             	<div class="title_header_blue">
				<?php echo $lbl_welcome; ?>, <?php echo $_SESSION['MVGitHub_usrtitle']." ".$_SESSION['MVGitHub_usrlname'];?>
                </div>
                <div>
                <?php
				if ($_SESSION['MVGitHub_joblevel'] < 7)
					{
					echo "<div class=\"msg_success\"> You can now view what is on your teams desk by clicking on 'My Team' menu at the top.</div>";
					}
				?>
                </div>
            	<div style="padding:10px 10px 0px 0px"></div>

                
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
open_flash_chart_object( '100%', '100%', 'chart_pie_1.php' );
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
					open_flash_chart_object( 450, 300, 'chart_graph_1_global.php' );
					} else { 
					open_flash_chart_object( 450, 300, 'chart_graph_1.php' );	
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
                <div>          
					<div id="map" style="width: 500px; height: 300px"></div>     
                </div>
            </div>
            </td>
        </tr>
    </table>
    </div>
</div>