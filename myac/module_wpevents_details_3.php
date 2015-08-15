<?php
require_once('../assets_backend/be_includes/check_login_easy.php');
//shared table for upload - set it's session below
$_SESSION['s_uploadtbl']="reportactivities";
?>
<div style="padding:0px 10px 15px 10px;">
<a href="#" id="button_upload" onclick="tb_open_new('pop_uploaddoc.php?keepThis=true&amp;TB_iframe=true&amp;height=450&amp;width=500&amp;modal=true')"></a>
</div>
<div>
    <?php
	//get the documents associated with this activity report
	$sql_docs="SELECT iddocattach,docname,doccomments,docpath,doccat,enteredon,usrname FROM docattach 
	INNER JOIN doccats ON docattach.doccats_iddoccat=doccats.iddoccat 
	INNER JOIN usrac ON docattach.enteredby=usrac.idusrac
	WHERE docattach.tbl_name='".$_SESSION['s_uploadtbl']."' AND tbl_fk_id=".$_SESSION['idreporting']." AND owner_usrteamid=".$_SESSION['MVGitHub_idacteam']." ";
	$res_docs=mysql_query($sql_docs);
	$fet_docs=mysql_fetch_array($res_docs);
	$num_docs=mysql_num_rows($res_docs);
	//echo $sql_docs;
	if ($num_docs > 0)
		{
	?>
   
<table border="0" cellpadding="3" cellspacing="0" width="95%">
	<tr>
    	<td class="tbl_h2" height="35">
        <?php 
		echo $lbl_docname;
		?>
        </td>
        <td class="tbl_h2">
        <?php echo $lbl_doccat;?>
        </td>
        <td class="tbl_h2">
        <?php echo $lbl_comments;?>
        </td>
        <td class="tbl_h2">
        <?php echo $lbl_uploadedon;?>
        </td>
        <td class="tbl_h2">
        <?php echo $lbl_uploadedby ;?>
        </td>
    </tr>
    <?php
	do {
	?>
	<tr <?php 
// technocurve arc 3 php mv block2/3 start
echo " style=\"background-color:$mocolor\" onMouseOver=\"this.style.backgroundColor='$mocolor3'\" onMouseOut=\"this.style.backgroundColor='$mocolor'\"";
// technocurve arc 3 php mv block2/3 end
?>>
    	<td class="tbl_data">
        
                <a href=""><?php echo $fet_docs['docname'];?></a>
               
        </td>
        <td class="tbl_data">
        <?php echo $fet_docs['doccat'];?>
        </td>
        <td class="tbl_data">
          <?php echo $fet_docs['doccomments'];?>.
        </td>
        <td class="tbl_data"><?php echo date("D, M d, Y H:i",strtotime($fet_docs['enteredon']));?></td>
        <td class="tbl_data"><?php echo $fet_docs['usrname'];?></td>
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
	} while ($fet_docs=mysql_fetch_array($res_docs));
	?>
</table>
	<?php
	} else {
	?>
    <div class="msg_warning">
    <?php echo $msg_no_record;?>
    </div>
    <?php
	}
	?>
</div>