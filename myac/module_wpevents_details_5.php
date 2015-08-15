<?php
require_once('../assets_backend/be_includes/check_login_easy.php');
?>
<table border="0" width="100%">
	<tr>
    	<td width="50%" valign="top">
        <?php
	   $sql_notes="SELECT nstatus,statusnotes,enteredon,usrname,fname,lname FROM notes 
	   INNER JOIN notestatus ON notes.notestatus_idnotestatus=notestatus.idnotestatus INNER JOIN usrac ON notes.enteredby=usrac.idusrac  
	   WHERE tbl_fk_id=".$_SESSION['idreporting']." AND notes.tbl_name='reportactivities' ORDER BY enteredon ASC";
	   $res_notes=mysql_query($sql_notes);
	   $num_notes=mysql_num_rows($res_notes);
	   $fet_notes=mysql_fetch_array($res_notes);
//	   echo  $sql_notes;
	   if ($num_notes < 1)
	   	{
		echo "<div class=\"msg_warning\" style=\"text-align:center\">".$msg_no_record ."</div>";
		} else {
		do {
		echo "<div class=\"bline\" style=\"margin:10px 0px 10px 0px\">";
		echo "<div class=\"text_small\">".date("D, M d, Y H:i",strtotime($fet_notes['enteredon']))."</div>";
		echo "<div class=\"text_body\" style=\"font-weight:bold\">".$fet_notes['nstatus']."</div>";
		echo "<div class=\"text_body\">".$fet_notes['statusnotes']."</div>";
		echo "<div class=\"text_small\"> by : ".$fet_notes['fname']." ".$fet_notes['lname']."</div>";
		echo "</div>";
			} while ($fet_notes=mysql_fetch_array($res_notes));
		}
		
	   ?>        </td>
        <td width="50%" valign="top"> 
        	<form method="post" action="" name="newaction">
       	  </form>
      </td>   
	</tr>
</table>