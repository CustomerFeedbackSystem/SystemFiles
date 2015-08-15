<?php
require_once('../assets_backend/be_includes/check_login_easy.php');
?>
<div>
	<div class="bg_section">
	<?php echo $fet_heading['modulename']; ?> &raquo; <?php echo $fet_heading['submodule']; ?>
	</div>
    <div style="margin:10px 0px 0px 0px">
    <table border="0" width="100%" cellpadding="3" cellspacing="0">
    	<tr>
        	<td class="tbl_h2" height="35"><?php echo $lbl_projectname;?></td>
            <td class="tbl_h2"><?php echo $lbl_datestart;?></td>
            <td class="tbl_h2"><?php echo $lbl_dateend;?></td>
             <td class="tbl_h2"><?php echo $lbl_projectcat;?></td>
             <td class="tbl_h2"><?php echo $lbl_status;?></td>
             <td class="tbl_h2"><?php echo $lbl_updatelast;?></td>
        </tr>
        <?php
		$sql_projects="SELECT idprojects,projectname,startdate,stopdate,lastupdate,pstatus,ptype FROM projects
		INNER JOIN projectstatus ON projects.projectstatus_idprojectstatus=projectstatus.idprojectstatus
		INNER JOIN projecttype ON projects.projecttype_idprojecttype=projecttype.idprojecttype
		WHERE usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']." ORDER BY createdon ASC";
		$res_projects=mysql_query($sql_projects);
		$num_projects=mysql_num_rows($res_projects);
		$fet_projects=mysql_fetch_array($res_projects);
		//echo $sql_projects;
		if ($num_projects > 0 )
			{
			do {
		?>
        <tr>
        	<td class="tbl_data"><a href="<?php echo $_SERVER['PHP_SELF'];?>?uction=edit_submod&amp;projid=<?php echo $fet_projects['idprojects'];?>"><?php echo $fet_projects['projectname'];?></a></td>
            <td class="tbl_data"><?php echo date("D, M d, Y",strtotime($fet_projects['stopdate'])); ?></td>
            <td class="tbl_data"><?php echo date("D, M d, Y",strtotime($fet_projects['startdate'])); ?></td>
            <td class="tbl_data"><?php echo $fet_projects['ptype'];?></td>
            <td class="tbl_data"><?php echo $fet_projects['pstatus'];?></td>
            <td class="tbl_data"><?php echo date("D, M d, Y",strtotime($fet_projects['lastupdate'])); ?></td>
        </tr>
        <?php
			} while ($fet_projects=mysql_fetch_array($res_projects));
		} else {
		?>
        <tr>
        	<td colspan="6">
            <div style="text-align:center; margin:45px 15px;" class="msg_warning">
			<?php echo $msg_no_record; ?>
            </div>
            </td>
        </tr>
        <?php
		}
		?>
    </table>
    </div>
</div>    