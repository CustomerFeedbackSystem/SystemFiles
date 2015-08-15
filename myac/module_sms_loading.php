<?php //require_once('../Connections/connSystem.php');
//mysql_select_db($database_connSystem, $connSystem);
require_once('../assets_backend/be_includes/check_login_easy.php');

if (!isset($_SESSION['parenttabview']))
	{
	$_SESSION['parenttabview']=1;
	}
?>
<div >
	<table border="0" cellpadding="0" cellspacing="0" width="100%">
    	<tr>
        	<td width="100%" class="bg_section">
			<?php echo $fet_heading['modulename']; ?> &raquo; <?php echo $fet_heading['submodule']; ?>
            </td>
		</tr>
    </table>
</div>
<div style="padding:15px 0px">
    <div class="tab_area">
    <span class="tab_<?php if ($_SESSION['parenttabview']==1){ echo "on";} else { echo "off"; }?>"><a href="<?php echo $_SERVER['PHP_SELF'];?>?parentviewtab=1">Load SMSes</a></span>
    <span class="tab_<?php if ($_SESSION['parenttabview']==2){ echo "on";} else { echo "off"; }?>"><a href="<?php echo $_SERVER['PHP_SELF'];?>?parentviewtab=2">Submit Receipt ( Top Up )</a></span>    
    </div>
</div>
<div>
<?php 
if ((isset($_SESSION['parenttabview'])) && ($_SESSION['parenttabview']<3) )
	{
	if ($_SESSION['parenttabview']==1)
		{
		require_once('module_sms_loading_1.php');
		} else if ($_SESSION['parenttabview']==2) {
		require_once('module_sms_loading_2.php');
		} 
	
	} else {
	require_once('module_sms_loading_1.php');
	}
	?>
</div>
    