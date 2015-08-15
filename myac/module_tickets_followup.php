<?php //require_once('../Connections/connSystem.php');
//mysql_select_db($database_connSystem, $connSystem);
require_once('../assets_backend/be_includes/check_login_easy.php');
?>
<div>
    <div >
	<table border="0" cellpadding="0" cellspacing="0" width="100%">
    	<tr>
        	<td width="100%" class="bg_section">
			<?php echo $fet_heading['modulename']; ?> &raquo; <?php echo $fet_heading['submodule']; ?>
            </td>
		</tr>
        <tr>
        	<td>
        <div>
            <div class="tab_area">
                <span class="tab<?php if ((isset($_SESSION['parenttabview'])) && ($_SESSION['parenttabview']==1)) { echo "_on";}?>"><a href="<?php echo $_SERVER['PHP_SELF'];?>?parentviewtab=1"><?php echo $lbl_wagtome;?></a></span>
                <span class="tab<?php if ((isset($_SESSION['parenttabview'])) && ($_SESSION['parenttabview']==2)) { echo "_on";}?>"><a href="<?php echo $_SERVER['PHP_SELF'];?>?parentviewtab=2"><?php echo $lbl_wagnewtkts;?></a></span>
                <span class="tab<?php if ((isset($_SESSION['parenttabview'])) && ($_SESSION['parenttabview']==3)) { echo "_on";}?>"><a href="<?php echo $_SERVER['PHP_SELF'];?>?parentviewtab=3"><?php echo $lbl_wagallod;?></a></span>
            </div>
            <div>
            <?php
			if (isset($_SESSION['parenttabview']))
				{
					if ($_SESSION['parenttabview']==1)
						{
						require_once('module_tickets_followup_1.php');
						}
					if ($_SESSION['parenttabview']==2)
						{
						require_once('module_tickets_followup_2.php');
						}
					if ($_SESSION['parenttabview']==3)
						{
						require_once('module_tickets_followup_3.php');
						}
				}
			?>
            </div>
		</div>
            </td>
        </tr>       
	</table>
	</div>
</div>
<?php
mysql_free_result($res_heading);
?>