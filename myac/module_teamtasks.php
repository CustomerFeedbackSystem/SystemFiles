<?php
require_once('../assets_backend/be_includes/config.php');
require_once('../Connections/connSystem.php');


mysql_select_db($database_connSystem, $connSystem);
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
    </table>
    </div>
	<div style="padding:0px 0px 10px 0px">
    <?php
	//if records exist
	?>
<table border="0" cellpadding="2" cellspacing="0" width="100%">
    </tr>
	<tr>
    	<td colspan="7">
        	<table border="0" cellpadding="0" cellspacing="0">
            	<tr>
					<td class="text_small">&nbsp;</td>
				  <td class="text_body">&nbsp;</td>
			  </tr>
            </table>
        </td>
    </tr>
	<tr>
		<td width="25%" class="tbl_h2">
			<table border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td class="tbl_h2">
					User Account
                    </td>
                    <td width="8px">
                    <a href="index.php?mod=2&amp;submod=0&amp;ua=view&amp;view=2&amp;uction=view_mod&amp;pageNum_rs_list=&amp;orderlist=ascending&amp;orderwhat=from" id="button_asc"></a>
                    </td>
                    <td width="8px">
                    <a href="index.php?mod=2&amp;submod=0&amp;ua=view&amp;view=2&amp;uction=view_mod&amp;pageNum_rs_list=&amp;orderlist=descending&amp;orderwhat=from" id="button_desc"></a>
                    </td>
				</tr>
			</table>
		</td>
		<td width="25%" class="tbl_h2">Time Frame</td>
		<td width="20%" class="tbl_h2">
		<table border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td class="tbl_h2">New/Undone</td>
                    <td width="12px">
                    <a href="index.php?mod=2&amp;submod=0&amp;ua=view&amp;view=2&amp;uction=view_mod&amp;pageNum_rs_list=&amp;orderlist=ascending&amp;orderwhat=datein" id="button_asc"></a>
                    </td>
                    <td width="12px">
                    <a href="index.php?mod=2&amp;submod=0&amp;ua=view&amp;view=2&amp;uction=view_mod&amp;pageNum_rs_list=&amp;orderlist=descending&amp;orderwhat=datein" id="button_desc"></a>
                    </td>
				</tr>
			</table>
		</td>

        <td width="15%" class="tbl_h2">
		<table border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td class="tbl_h2">In Progress</td>
                    <td width="12px">
                    <a href="index.php?mod=2&amp;submod=0&amp;ua=view&amp;view=2&amp;uction=view_mod&amp;pageNum_rs_list=&amp;orderlist=ascending&amp;orderwhat=trem" id="button_asc"></a>
                    </td>
                    <td width="12px">
                    <a href="index.php?mod=2&amp;submod=0&amp;ua=view&amp;view=2&amp;uction=view_mod&amp;pageNum_rs_list=&amp;orderlist=descending&amp;orderwhat=trem" id="button_desc"></a>
                    </td>
				</tr>
			</table>
            </td>
            <td width="15%" class="tbl_h2">
		<table border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td class="tbl_h2">Done</td>
                    <td width="12px">
                    <a href="index.php?mod=2&amp;submod=0&amp;ua=view&amp;view=2&amp;uction=view_mod&amp;pageNum_rs_list=&amp;orderlist=ascending&amp;orderwhat=trem" id="button_asc"></a>
                    </td>
                    <td width="12px">
                    <a href="index.php?mod=2&amp;submod=0&amp;ua=view&amp;view=2&amp;uction=view_mod&amp;pageNum_rs_list=&amp;orderlist=descending&amp;orderwhat=trem" id="button_desc"></a>
                    </td>
				</tr>
			</table>
            </td>
            <td width="15%" class="tbl_h2">
		<table border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td class="tbl_h2">Overdue</td>
                    <td width="12px">
                    <a href="index.php?mod=2&amp;submod=0&amp;ua=view&amp;view=2&amp;uction=view_mod&amp;pageNum_rs_list=&amp;orderlist=ascending&amp;orderwhat=trem" id="button_asc"></a>
                    </td>
                    <td width="12px">
                    <a href="index.php?mod=2&amp;submod=0&amp;ua=view&amp;view=2&amp;uction=view_mod&amp;pageNum_rs_list=&amp;orderlist=descending&amp;orderwhat=trem" id="button_desc"></a>
                    </td>
				</tr>
			</table>
            </td>
	</tr>  
    
      <tr <?php 
// technocurve arc 3 php mv block2/3 start
echo " style=\"background-color:$mocolor\" onMouseOver=\"this.style.backgroundColor='$mocolor3'\" onMouseOut=\"this.style.backgroundColor='$mocolor'\"";
// technocurve arc 3 php mv block2/3 end
?>>
		<td class="tbl_data">&nbsp;</td>
		<td class="tbl_data">&nbsp;</td>
		<td class="tbl_data" >&nbsp;</td>
        <!--
		<td class="tbl_data" >
        asdf
		</td>
        -->
        <td class="tbl_data">&nbsp;</td>
        <td class="tbl_data">&nbsp;</td>
        <td class="tbl_data">&nbsp;</td>
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

     <tr>
    	<td colspan="7">&nbsp;</td>
    </tr>
</table>

	<div style="text-align:center; margin:45px 15px;" class="msg_warning">
    <?php echo $msg_no_record_4u; ?>
    </div>

  </div>
</div>
