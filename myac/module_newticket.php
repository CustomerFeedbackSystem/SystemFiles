<?php
require_once('../assets_backend/be_includes/check_login_easy.php');
//this is sub module 24
/*if ($fet_modperm['syssubmodule_idsyssubmodule']!=24) //if no permission here, then halt everything
	{
	echo "<div class=\"msg_warning\">".$ec200."</div>";
	exit;
	}*/
?>
<div>
	<div class="bg_section">
    <?php echo $fet_heading['modulename']; ?> &raquo; <?php echo $fet_heading['submodule']; ?>
    </div>
   <!-- <div class="msg_warning">
    Minor Upgrade in Progress... Resuming at 13:00 Hrs. Sorry for the inconvenience
    </div>
    -->
    <table border="0" width="100%">
    	<tr>
         	<td valign="top" width="50%">
    <div style="padding:25px 25px 0px 45px">
    <div class="title_header_blue">Log a Complaint</div>
    	<div style="padding:2px">
	    <a href="#" onclick="tb_open_new('pop_newticket.php?channel=6&amp;title=Ticket&amp;&amp;tabview=1&amp;keepThis=true&amp;TB_iframe=true&amp;height=<?php echo $_SESSION['tb_height'];?>&width=<?php echo $_SESSION['tb_width'];?>&amp;modal=true')" id="button_ticketphone"></a>
       <!-- <a href="#" onclick="alert('OOps! It is embarassing! Tweaking something for you to work better. Check at 1pm');" id="button_ticketphone"></a>-->
    	</div>
        <div style="padding:2px">
	    <a  href="#" onclick="tb_open_new('pop_newticket.php?channel=5&amp;title=Ticket&amp;&amp;tabview=1&amp;keepThis=true&amp;TB_iframe=true&amp;height=<?php echo $_SESSION['tb_height'];?>&width=<?php echo $_SESSION['tb_width'];?>&amp;modal=true')" id="button_ticketcounter"></a>
		<!--<a  href="#" onclick="alert('OOPs! It is embarassing! Tweaking something for you to work better. Check at 1pm');" id="button_ticketcounter"></a>-->
    	</div>
    </div>
    		</td>
            <td valign="top" width="50%" style="padding:35px 10px">&nbsp;</td>
   	  </tr>
	</table>
</div>
    