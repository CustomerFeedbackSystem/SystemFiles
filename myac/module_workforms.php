<?php
require_once('../assets_backend/be_includes/check_login.php');

//get the forms in the system so far
$sql_forms="SELECT idwfprocforms,submodule,form_description,form_status 
FROM wfprocforms 
INNER JOIN syssubmodule ON wfprocforms.syssubmodule_idsyssubmodule=syssubmodule.idsyssubmodule
WHERE wfprocforms.usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']." AND submod_type='FORM' ";
$res_forms=mysql_query($sql_forms);
$fet_forms=mysql_fetch_array($res_forms);
$num_forms=mysql_num_rows($res_forms);
?>
<div>
	<div class="bg_section">
    <?php echo $fet_heading['modulename']; ?> &raquo; <?php echo $fet_heading['submodule']; ?>
    </div>
    <div style="margin:15px 5px 15px 5px">
    	<div class="tab_area">
        	<span class="tab"><a href="<?php echo $_SERVER['PHP_SELF'];?>?uction=view_submod"><?php echo $tab_wftocat;?></a></span>
            <span class="tab"><a href="<?php echo $_SERVER['PHP_SELF'];?>?uction=view_wfs"><?php echo $tab_wfs;?></a></span>
            <span class="tab_on"><a href="<?php echo $_SERVER['PHP_SELF'];?>?uction=view_forms">Custom Forms</a></span>
           <!-- <span class="tab"><a href="<?php echo $_SERVER['PHP_SELF'];?>?uction=view_approvals">Approval Matrix</a></span>-->
        </div>
    </div>
    <div>
    <table border="0" cellpadding="2" cellspacing="0" width="100%" id="hi">
		<tr>
        	<td colspan="7" height="40">
           <a href="pop_newwfform.php?title=New_Form&amp;tabview=1&amp;tabview=1&amp;keepThis=true&amp;TB_iframe=true&amp;height=400&amp;width=650&amp;inlineId=hiddenModalContent&amp;modal=true" class="thickbox" id="button_newform"></a>
            </td>
        </tr>
        <tr>
        	<td width="17%" class="tbl_h">Form Name</td>
          <td width="16%" class="tbl_h">Description</td>
         
          <td width="15%" class="tbl_h">#Field Groups</td>
          <td width="15%" class="tbl_h">#Fields</td>
          <td width="15%" class="tbl_h">#Categories</td>
          <td width="15%" class="tbl_h">#Profiles</td>
           <td width="7%" class="tbl_h">Status</td>
   	  </tr>
<?php
if ($num_forms > 0)
	{
	do {
?>
        <tr  <?php 
// technocurve arc 3 php mv block2/3 start
echo " style=\"background-color:$mocolor\" onMouseOver=\"this.style.backgroundColor='$mocolor3'\" onMouseOut=\"this.style.backgroundColor='$mocolor'\"";
// technocurve arc 3 php mv block2/3 end
?>>
        	<td class="tbl_data"><a href="pop_editwfform.php?idform=<?php echo $fet_forms['idwfprocforms'];?>&amp;title=New_Form&amp;tabview=1&amp;tabview=1&amp;keepThis=true&amp;TB_iframe=true&amp;height=400&amp;width=650&amp;inlineId=hiddenModalContent&amp;modal=true" class="thickbox" ><?php echo $fet_forms['submodule'];?></a></td>
            <td class="tbl_data"><?php echo $fet_forms['form_description'];?></td>
            <td class="tbl_data">
			<?php 
			$res_secs=mysql_query('SELECT count(*) as secs FROM wfprocassetsgroup WHERE wfprocforms_idwfprocforms='.$fet_forms['idwfprocforms'].' AND userteam_owner='.$_SESSION['MVGitHub_idacteam'].''); 
			$fet_secs=mysql_fetch_array($res_secs);
			echo $fet_secs['secs']."&nbsp;";
			?><a href="pop_wfform_sections.php?idform=<?php echo $fet_forms['idwfprocforms'];?>&amp;title=New_Form&amp;tabview=1&amp;tabview=1&amp;keepThis=true&amp;TB_iframe=true&amp;height=400&amp;width=650&amp;inlineId=hiddenModalContent&amp;modal=true" class="thickbox" >[...]</a>
            </td>
            <td class="tbl_data">
            <?php 
			$res_fs=mysql_query('SELECT count(*) as fs FROM wfprocassets WHERE wfprocforms_idwfprocforms='.$fet_forms['idwfprocforms'].''); 
			$fet_fs=mysql_fetch_array($res_fs);
			echo $fet_fs['fs']."&nbsp;";
			?><a href="pop_wfform_fields.php?idform=<?php echo $fet_forms['idwfprocforms'];?>&amp;title=New_Form&amp;tabview=1&amp;tabview=1&amp;keepThis=true&amp;TB_iframe=true&amp;height=500&amp;width=810&amp;inlineId=hiddenModalContent&amp;modal=true" class="thickbox" >[...]</a>
            </td>
            <td class="tbl_data">
            <?php 
			$res_cats=mysql_query('SELECT count(*) as cats FROM wfprocforms_cats WHERE wfprocforms_idwfprocforms='.$fet_forms['idwfprocforms'].''); 
			$fet_cats=mysql_fetch_array($res_cats);
			echo $fet_cats['cats']."&nbsp;";
			?>
            <a href="pop_wfform_cats.php?idform=<?php echo $fet_forms['idwfprocforms'];?>&amp;title=New_Form&amp;tabview=1&amp;tabview=1&amp;keepThis=true&amp;TB_iframe=true&amp;height=500&amp;width=600&amp;inlineId=hiddenModalContent&amp;modal=true" class="thickbox" >[...]</a>
            </td>
             <td class="tbl_data">
            <?php 
			$res_prof=mysql_query('SELECT count(DISTINCT sysprofiles_idsysprofiles) as prof FROM wfprocassetsaccess WHERE wfprocforms_idwfprocforms='.$fet_forms['idwfprocforms'].' '); 
			$fet_prof=mysql_fetch_array($res_prof);
			echo $fet_prof['prof']."&nbsp;";
			?> 
            <a href="pop_wfform_profiles.php?idform=<?php echo $fet_forms['idwfprocforms'];?>&amp;title=New_Form&amp;tabview=1&amp;tabview=1&amp;profileid=0&amp;keepThis=true&amp;TB_iframe=true&amp;height=500&amp;width=810&amp;inlineId=hiddenModalContent&amp;modal=true" class="thickbox" >
            [...]</a></td>
                        <td class="tbl_data"><?php if ($fet_forms['form_status']==1) { echo "<span style=\"color:#009900;font-weight:bold\">ON</span>"; } else { echo "<span style=\"color:#ff0000;font-weight:bold\">OFF</span>"; }?></td>

        </tr>
<?php
		} while ($fet_forms=mysql_fetch_array($res_forms));

// technocurve arc 3 php mv block3/3 start
if ($mocolor == $mocolor1) {
	$mocolor = $mocolor2;
} else {
	$mocolor = $mocolor1;
}
// technocurve arc 3 php mv block3/3 end
} else {
	echo "<tr><td colspan=5 height:50>
	<div class=\"msg_warning\">You have not created any Custom Workflow Forms</div>
	</td></tr>";
}
?>        
    </table>
  </div>
</div>