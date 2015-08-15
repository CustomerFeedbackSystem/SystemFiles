<?php
require_once('../assets_backend/be_includes/check_login_easy.php');
?>
<div>
	<div class="bg_section">
    <?php echo $fet_heading['modulename']; ?> &raquo; <?php echo $fet_heading['submodule']; ?>
    </div>
    <div>
    df
    </div>
    <div>
    <table border="0" cellpadding="2" cellspacing="0" width="100%" id="hi">
            <tr>
                <td width="23%" class="tbl_h"><?php echo $lbl_wf;?></td>
              <td width="30%" class="tbl_h"><?php echo $lbl_description;?></td>
              <td width="18%" class="tbl_h"><?php echo $lbl_tat;?></td>
              <td width="17%" class="tbl_h"><?php echo $lbl_lastedit;?></td>
              <td width="12%" class="tbl_h"></td>
          </tr>
            <?php
			$sql_wf="SELECT idwfproc,usrteamzone_idusrteamzone,wfprocname,wfproctat,wfprocdesc,wfstatus,mobileaccess,modifiedon,createdon FROM wfproc WHERE usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']." ORDER BY wfprocname ASC";
			$res_wf=mysql_query($sql_wf);
			$num_wf=mysql_num_rows($res_wf);
			$fet_wf=mysql_fetch_array($res_wf);
			
			if ($num_wf > 0) 
				{
			do {
			?>
            <tr <?php 
// technocurve arc 3 php mv block2/3 start
echo " style=\"background-color:$mocolor\" onMouseOver=\"this.style.backgroundColor='$mocolor3'\" onMouseOut=\"this.style.backgroundColor='$mocolor'\"";
// technocurve arc 3 php mv block2/3 end
?>>
            	<td class="tbl_data">&nbsp;</td>
                <td class="tbl_data">&nbsp;</td>
                <td class="tbl_data">&nbsp;</td>
                <td class="tbl_data">&nbsp;</td>
                <td class="tbl_data">
                <a onclick="return confirm('<?php echo $msg_prompt_delete;?>');" href="<?php echo $_SERVER['PHP_SELF'];?>?saction=delete_wf&amp;wf=<?php echo $fet_wf['idwfproc'];?>" id="button_delete_small"></a>
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
			} while ($fet_wf=mysql_fetch_array($res_wf));
			
			} else {
			?>
            <tr>
            	<td colspan="5" align="center" height="50">
                <span class="msg_warning" >
                <?php echo $msg_warning_nowf; ?>
                </span>
                </td>
            </tr>
            <?php
			}
			?>
		</table>
    </div>
</div>